document.addEventListener('DOMContentLoaded', async () => {
    const userId = window.Laravel.user_id;

    const ongoingData = await getOngoingQuizById(userId);
    if (!ongoingData.success) return;

    displayOngoingQuiz(ongoingData);
});

const multiplayerForm = document.getElementById("multiplayer-form");
multiplayerForm.addEventListener("submit", event => {
    event.preventDefault();

    const lobbyId = document.getElementById("lobby-input").value;
    joinLobby(lobbyId);
})

function displayOngoingQuiz(data) {
    const container = document.getElementById("ongoing-container");
    const content = document.getElementById("ongoing-content");

    const { multiplayer_session, username } = data;
    const { quiz_template, session_code } = multiplayer_session;

    const title = `${quiz_template.difficulty} ${quiz_template.category} quiz`;
    const url = `/multiplayer/player/${session_code}`;

    container.style.display = "block";
    content.innerHTML = `
        <h3>${title}</h3>
        <p>${username}</p>
        <p>Session code: ${session_code}</p>
        <a href="${url}">Join</a>
    `;
}

async function joinLobby(lobbyId) {
    try {
        const response = await fetch(`/api/multiplayer/lobby/${lobbyId}`);
        const data = await response.json();

        if (!data.success) {
            return showErrorMessage(data.message);
        } 

        const userId = window.Laravel.user_id;
        const username = document.getElementById("lobby-username").value;

        const session = await getSessionId(lobbyId);
        const sessionId = session.data.id;

        const sessionPlayer = await insertSessionPlayers(sessionId, userId, username);

        if (!sessionPlayer.success) {
            return showErrorMessage(sessionPlayer.message || "Failed to join the session.");
        }

        window.location.href = `/multiplayer/player/${lobbyId}`;
        
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

        return {
            success: response.ok,
            ...data
        };
    } catch(error){
        console.error("Error: ", error);

        return {
            success: false,
            message: "Failed to join the session due to a network error."
        };
    }
}

async function getOngoingQuizById(playerId){
    try {
        const response = await fetch(`/api/multiplayer/get-player-session/${playerId}`);
        
        const data = await response.json();

        return {
            success: response.ok,
            ...data.data
        };
    } catch(error){
        console.error("Error: ", error);

        return {
            success: false,
            message: "Failed to join the session due to a network error."
        };
    }
}