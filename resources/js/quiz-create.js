const cardForms = document.querySelectorAll(".card-form");
const userId = window.Laravel.user_id;

cardForms.forEach((form) => {
    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const parentCard = form.closest(".quiz-card"); 

        const category = parentCard?.dataset.category;
        const difficulty = form.querySelector('input[name="difficulty"]:checked')?.value;
        const questionCount = parseInt(form.querySelector('input[name="limit"]:checked')?.value);
        console.log(questionCount)
        console.log(typeof(questionCount))

        // create template ok
        const template = await createTemplate(category, difficulty, questionCount);
        const templateId = template.data.id

        // create session ok
        const session = await createSession(userId, templateId);
        console.log(session)
        const sessionCode = session.data.session_code;

        window.location.href = `/multiplayer/host/${sessionCode}`;
    });
});

async function createTemplate(category, difficulty, questionCount) {
    try {
        const response = await fetch('/api/template/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                category: category,
                difficulty: difficulty,
                question_count: questionCount
            })
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error: ", error)
    }
}

async function createSession(hostId, templateId) {
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

