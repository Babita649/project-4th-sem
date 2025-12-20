const gameDetails = {
    "Dragon Ball Z": "A fast-paced fighting game based on the Dragon Ball anime.",
    "Mortal Kombat": "A brutal fighting game famous for its finishing moves.",
    "God of War": "An action-adventure game based on Norse mythology.",
    "Spider-Man": "An open-world superhero game with web-swinging action.",
    "Chess Ultra": "A modern and realistic chess simulation.",
    "Spider-Man 2": "Enhanced sequel featuring multiple playable characters.",
    "Human Fall Flat": "A physics-based puzzle platformer.",
    "Solasta": "A tactical RPG based on Dungeons & Dragons rules."
};

function openGame(name) {
    document.getElementById("gameTitle").innerText = name;
    document.getElementById("gameDesc").innerText = gameDetails[name];
    document.getElementById("modal").style.display = "block";
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}
