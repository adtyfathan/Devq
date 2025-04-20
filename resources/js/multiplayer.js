const sessionCode = window.location.pathname.split("/").pop();

document.addEventListener("DOMContentLoaded", async function () {
    const sessionId = await getSessionIdBySessionCode(sessionCode);

    Echo.private(`quiz.${sessionId}`)
        .listen('QuestionBroadcasted', async (event) => {
            console.log("New question received:", event.question);
            console.log("Scheduled at:", event.questionAt);
        })
        .listen('StandingsUpdated', async (event) => {
            console.log("New standings received:", event.players);
            console.log("Scheduled at:", event.standingsAt);
        });
})

async function getSessionIdBySessionCode(sessionCode) {
    try {
        const response = await fetch(`/api/multiplayer/get-session-by-code/${sessionCode}`);
        const data = await response.json();
        return data.data.id;
    } catch (error) {
        console.error("Error: ", error);
    }
}