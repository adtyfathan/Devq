const multiplayerForm = document.getElementById("multiplayer-form");

multiplayerForm.addEventListener("submit", event => {
    event.preventDefault();
    const lobbyId = document.getElementById("lobby-input").value;
    
    joinLobby(lobbyId);
})

async function joinLobby(lobbyId) {
    try {
        let response = await fetch(`/api/multiplayer/lobby/${lobbyId}`);
        let data = await response.json();

        if (!data.success) {
            showErrorMessage(data.message);
        } else {
            const userId = window.Laravel.user_id;
            const username = document.getElementById("lobby-username").value;

            const session = await getSessionId(lobbyId);
            const sessionId = session.data.id;

            const sessionPlayer = await insertSessionPlayers(sessionId, userId, username);
            console.log(sessionPlayer)

            // window.location.href = `/multiplayer/player/${lobbyId}`;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

function showErrorMessage(message) {
    document.getElementById('error-message').innerText = message;
    document.getElementById('error-message').style.display = 'block';
}

async function getSessionId(sessionCode){
    try {
        const response = await fetch(`/api/multiplayer/get-session-by-code/${sessionCode}`);
        const data = await response.json();
        return data;
    } catch (error){
        console.error("Error: ", error);
    }
}

async function insertSessionPlayers(sessionId, playerId, username){
    try {
        const response = await fetch('/api/multiplayer/add-session-player', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                session_id: sessionId,
                player_id: playerId,
                username: username
            })
        });
        const data = await response.json();
        return data;
    } catch(error){
        console.error("Error: ", error);
    }
}