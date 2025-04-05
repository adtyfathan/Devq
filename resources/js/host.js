document.addEventListener('DOMContentLoaded', async function(){
    const urlParams = new URLSearchParams(window.location.search);
    const category = window.location.pathname.split("/").pop();
    const difficulty = urlParams.get("difficulty") || "easy";
    const limit = urlParams.get("limit") || 5;
    const userId = window.Laravel.user_id;
    const codeDigitContainer = document.getElementById("code-text");
    

    const questions = await getQuestions(category, difficulty, limit);

    const template = await createTemplate(category, difficulty);
    const templateId = template.data.id

    // console.log(questions.data)

    const session = await createSession(userId, templateId);
    const sessionCode = session.data.session_code;

    codeDigitContainer.textContent = `Your room code is: ${sessionCode}`;

    Echo.private(`multiplayer.${userId}`)
        .listen('CreateMultiplayerLobby', (event) => {
            console.log(event)
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

async function createTemplate(category, difficulty){
    try {
        const response = await fetch('/api/template/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                category: category,
                difficulty: difficulty
            })
        });
        const data = await response.json();
        return data; 
    } catch(error){
        console.error("Error: ", error)
    }
}

async function createSession(hostId, templateId){
    try {
        const response = await fetch('/api/multiplayer/session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                host_id: hostId,
                quiz_id: templateId,
                status: "waiting"
            })
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error: ", error);
    }
}

async function addPlayerToLobby(){
    // add table multiplayer user pas player join
}

