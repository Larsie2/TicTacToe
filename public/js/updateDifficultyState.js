const gamemode = document.getElementById('gamemode');
const difficulty = document.getElementById('difficulty');

function updateDifficultyState() {
    if (gamemode.value === "PvP") {
        difficulty.value = 'None';
        difficulty.disabled = true;
    } else {
        difficulty.disabled = false;
    }
}

gamemode.addEventListener('change', updateDifficultyState);
document.addEventListener('DOMContentLoaded', updateDifficultyState);
