const multiplayerForm = document.getElementById("multiplayer-form");

multiplayerForm.addEventListener("submit", event => {
    event.preventDefault();
    const lobbyId = parseInt(document.getElementById("lobby-input").value);
    
    joinLobby(lobbyId);
})

async function joinLobby(lobbyId) {
    try {
        let response = await fetch(`/api/multiplayer/lobby/${lobbyId}`);
        let data = await response.json();

        if (!data.success) {
            showErrorMessage(data.message);
        } else {
            window.location.href = `/multiplayer/player/${lobbyId}`;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

function showErrorMessage(message) {
    document.getElementById('error-message').innerText = message;
    document.getElementById('error-message').style.display = 'block';
}