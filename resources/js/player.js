const leaveButton = document.getElementById('leave-button');
const sessionCode = window.location.pathname.split("/").pop();
const userId = window.Laravel.user_id;

leaveButton.addEventListener('click', async function(){
    const lobbyId = await getSessionIdBySessionCode(sessionCode);

    const removePlayer = await deleteUserFromLobby(lobbyId, userId);

    console.log(removePlayer)

    // window.location.href = `/`;
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