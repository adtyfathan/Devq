const leaveButton = document.getElementById('leave-button');
const userId = window.Laravel.user_id;
const sessionCode = window.location.pathname.split("/").pop();

document.addEventListener("DOMContentLoaded", async function () {
    const sessionId = await getSessionIdBySessionCode(sessionCode);
    
    const playersData = await getPlayers(sessionId);

    if (playersData && Array.isArray(playersData.data)) {
        playersData.data.forEach(player => displayPlayers(player));
    } else {
        document.getElementById("error-text").textContent = "There is no player here...";
    }

    Echo.private(`multiplayer.${sessionId}`)
        .listen('CreateMultiplayerLobby', async (event) => {
            if (event.player) {
                document.getElementById("error-text").textContent = "";
            }
            addPlayers(event.player);
        })
        .listen('LeaveMultiplayerLobby', async (event) => {
            removePlayer(event.player.id);
        })
        .listen('QuizStarted', async (event) => {
            window.location.href = `/multiplayer/quiz/${event.session.session_code}`;
        });
});

leaveButton.addEventListener('click', async function(){
    const lobbyId = await getSessionIdBySessionCode(sessionCode);

    const removePlayer = await deleteUserFromLobby(lobbyId, userId);

    window.location.href = `/`;
});

async function deleteUserFromLobby(lobbyId, userId) {
    try {
        const response = await fetch("/api/multiplayer/remove-session-player", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                session_id: lobbyId,
                player_id: userId
            })
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error: ", error);
    }
}

async function getSessionIdBySessionCode(sessionCode){
    try {
        const response = await fetch(`/api/multiplayer/get-session-by-code/${sessionCode}`);
        const data = await response.json();
        return data.data.id;
    } catch (error) {
        console.error("Error: ", error);
    }
}

async function getPlayers(sessionId) {
    try {
        const response = await fetch(`/api/multiplayer/get-players/${sessionId}`);

        if (!response.ok) {
            if (response.status === 404) {
                document.getElementById("error-text").textContent = "There is no player here..."
                return;
            }
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error: ", error);
    }
}

function displayPlayers(player) {
    const playersWrapper = document.getElementById("players-wrapper");

    playersWrapper.innerHTML += `
        <div class="player-container" data-id=${player.user_id}>
            <p>${player.username}</p>
        </div>
    `;
}

function addPlayers(player) {
    const playersWrapper = document.getElementById("players-wrapper");

    playersWrapper.innerHTML += `
        <div class="player-container" data-id=${player.user_id}>
            <p>${player.username}</p>
        </div>
    `;
}

function removePlayer(playerId) {
    const playerElement = document.querySelector(`.player-container[data-id="${playerId}"]`);
    if (playerElement) {
        playerElement.remove();
    }
}