const express = require('express');
const app = express();
const fs = require('fs');
const path = require('path');
const PORT = process.env.PORT || 3000;

app.set('view engine', 'ejs');

app.use(express.static(path.join(__dirname, 'public')));

app.get('/slike', (req, res) => {

    const folderPath = path.join(__dirname, 'public', 'images');
    const files = fs.readdirSync(folderPath);

    const images = files
        .filter(file => /\.(jpg|jpeg|png|webp)$/i.test(file))
        .map((file, index) => ({
            id: `slika-${index + 1}`,
            url: `/images/${file}`,
            title: `Slika ${index + 1}`
        }));

    res.render('slike', { images });
});

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.get('/grafikon', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'grafikon.html'));
});

app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});