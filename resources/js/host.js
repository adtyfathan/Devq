document.addEventListener('DOMContentLoaded', async function(){
    const sessionCode = window.location.pathname.split("/").pop();
    
    const userId = window.Laravel.user_id;

    const codeDigitContainer = document.getElementById("code-text");
    
    // const questions = await getQuestions(category, difficulty, limit);

    const session = await getSessionIdBySessionCode(sessionCode);
    const sessionId = session.data.id;

    codeDigitContainer.textContent = `Your room code is: ${sessionCode}`;

    const playersData = await getPlayers(sessionId);
    const players = playersData.data;

    players.forEach(player => displayPlayers(player));

    Echo.private(`multiplayer.${userId}`)
        .listen('CreateMultiplayerLobby', async (event) => {
            console.log(event.player)
            addPlayers(event.player)
        })
});

async function getQuestions(category, difficulty, limit){
    try {
        const response = await fetch(`/api/multiplayer/host/${category}?difficulty=${difficulty}&limit=${limit}`);
        const data = await response.json();
        return data;
    } catch(error){
        console.error("Error: ", error)
    }
}

async function getPlayers(sessionId){
    try{
        const response = await fetch(`/api/multiplayer/get-players/${sessionId}`);
        const data = await response.json();
        return data;
    } catch(error){
        console.error("Error: ", error);
    }
}

async function getSessionIdBySessionCode(sessionCode) {
    try {
        const response = await fetch(`/api/multiplayer/get-session-by-code/${sessionCode}`);
        const data = await response.json();
        return data;
    } catch (error){
        console.error("Error: ", error);
    }
}

function displayPlayers(player){
    const playersWrapper = document.getElementById("players-wrapper");

    playersWrapper.innerHTML += `
        <p>${player.pivot.username }</p>
    `;
}

function addPlayers(player){
    const playersWrapper = document.getElementById("players-wrapper");

    playersWrapper.innerHTML += `
        <p>${player.username}</p>
    `;
}
