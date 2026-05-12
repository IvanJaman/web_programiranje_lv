const ocjenaSlider = document.getElementById("filter-ocjena");
const ocjenaDisplay = document.getElementById("ocjena-vrijednost");

if (ocjenaSlider && ocjenaDisplay) {
    ocjenaSlider.addEventListener("input", () => {
        ocjenaDisplay.textContent = ocjenaSlider.value;
    });
}

const menuToggle = document.getElementById("menu-toggle");
const navMenu = document.querySelector(".menu nav");

if (menuToggle && navMenu) {
    menuToggle.addEventListener("click", (e) => {
        e.stopPropagation();
        navMenu.classList.toggle("open");
    });
}

document.addEventListener("click", () => {
    if (navMenu) navMenu.classList.remove("open");
});