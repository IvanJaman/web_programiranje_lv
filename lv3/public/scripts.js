let sviFilmovi = [];
let prikazaniFilmovi = [];
let kosarica = [];

fetch('movies.csv')
    .then(res => res.text())
    .then(csv => {
        const rezultat = Papa.parse(csv, {
            header: true,
            skipEmptyLines: true
        });

        sviFilmovi = rezultat.data.map(film => ({
            naslov: film.Naslov,
            zanr: film.Zanr,
            godina: Number(film.Godina),
            trajanje: Number(film.Trajanje_min),
            ocjena: Number(film.Ocjena),
            reziser: film.Rezisery,
            zemlja: film.Zemlja_porijekla
                ? film.Zemlja_porijekla.split(',').map(c => c.trim())
                : []
        }));

        renderTable(sviFilmovi);
    })
    .catch(err => {
        console.error('Greška pri dohvaćanju CSV-a:', err);
    });

function renderTable(filmovi) {
    const tbody = document.getElementById("film-table-body");

    tbody.innerHTML = "";

    prikazaniFilmovi = filmovi;

    filmovi.forEach((film, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${film.naslov}</td>
            <td>${film.zanr}</td>
            <td>${film.godina}</td>
            <td>${film.trajanje}</td>
            <td>${film.ocjena}</td>
            <td>${film.reziser}</td>
            <td>${film.zemlja.join(", ")}</td>
            <td>
                <button onclick="dodajUKosaricu(${index})">
                    Dodaj u košaricu
                </button>
            </td>
        `;

        tbody.appendChild(row);
    });
}

const ocjenaSlider = document.getElementById("filter-ocjena");
const ocjenaDisplay = document.getElementById("ocjena-vrijednost");

ocjenaSlider.addEventListener("input", () => {
    ocjenaDisplay.textContent = ocjenaSlider.value;
});

function filtrirajFilmove() {
    const zanr = document.getElementById("filter-zanr").value;
    const godina = document.getElementById("filter-godina").value;
    const ocjena = document.getElementById("filter-ocjena").value;

    const filtrirani = sviFilmovi.filter(film => {

        const matchZanr = !zanr || film.zanr.split(",").map(z => z.trim()).includes(zanr);

        const matchGodina = !godina || film.godina >= Number(godina);

        const matchOcjena = film.ocjena >= Number(ocjena);

        return matchZanr && matchGodina && matchOcjena;
    });

    renderTable(filtrirani);
}

document.getElementById("filter-btn")
    .addEventListener("click", filtrirajFilmove);

const kosaricaBtn = document.getElementById("kosarica-btn");
const kosaricaDropdown = document.getElementById("kosarica-dropdown");

const menuToggle = document.getElementById("menu-toggle");
const navMenu = document.querySelector(".menu nav");

kosaricaBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    kosaricaDropdown.classList.toggle("hidden");

    navMenu.classList.remove("open");
});

menuToggle.addEventListener("click", (e) => {
    e.stopPropagation();

    navMenu.classList.toggle("open");

    kosaricaDropdown.classList.add("hidden");
});

document.addEventListener("click", () => {
    kosaricaDropdown.classList.add("hidden");
    navMenu.classList.remove("open");
});

function dodajUKosaricu(index) {
    const film = prikazaniFilmovi[index];

    if (!film) return;

    const postoji = kosarica.some(f => f.naslov === film.naslov);

    if (!postoji) {
        kosarica.push(film);
        osvjeziKosaricu();
    } else {
        alert("Film je već u košarici!");
    }
}

function osvjeziKosaricu() {
    const lista = document.getElementById("kosarica-lista");
    lista.innerHTML = "";

    if (kosarica.length === 0) {
        const li = document.createElement("li");
        li.textContent = "Košarica je prazna";
        lista.appendChild(li);
        return;
    }

    kosarica.forEach((film, index) => {
        const li = document.createElement("li");

        li.innerHTML = `
            <span>${film.naslov} (${film.godina})</span>
            <button onclick="ukloniIzKosarice(${index})">Ukloni</button>
        `;
        lista.appendChild(li);
    });
}

function ukloniIzKosarice(index) {
    kosarica.splice(index, 1);
    osvjeziKosaricu();
}

document.getElementById("potvrdi-kosaricu").addEventListener("click", () => {
    if (kosarica.length === 0) {
        alert("Košarica je prazna!");
        return;
    }

    alert(`Uspješno ste dodali ${kosarica.length} filmova u košaricu!`);

    kosarica = [];
    osvjeziKosaricu();
});