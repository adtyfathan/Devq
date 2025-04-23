const sessionCode = window.location.pathname.split("/").pop();
const playerId = window.Laravel.user_id;
let countdown;
let hasAnswered;
let questionCounter = 0;

const containers = {
    opening: document.getElementById("opening-container"),
    quiz: document.getElementById("quiz-container"),
    meme: document.getElementById("meme-container"),
    standings: document.getElementById("standings-container"),
};

const timerText = document.getElementById("timer");

document.addEventListener("DOMContentLoaded", async () => {
    const sessionId = await getSessionIdBySessionCode(sessionCode);
    Echo.private(`quiz.${sessionId}`)
        .listen('QuestionBroadcasted', event => handleQuestionEvent(event))
        .listen('StandingsUpdated', event => handleStandingsEvent(event));
});

async function getSessionIdBySessionCode(code) {
    try {
        const response = await fetch(`/api/multiplayer/get-session-by-code/${code}`);
        const data = await response.json();
        return data.data.id;
    } catch (error) {
        console.error("Failed to get session ID:", error);
    }
}

function handleQuestionEvent(event) {
    questionCounter++;
    hasAnswered = false;

    const now = new Date();

    const openingTime = new Date(event.openingAt);
    const questionTime = new Date(event.questionAt);
    const memeTime = new Date(event.memeAt);

    const openingDelay = Math.max(0, openingTime - now);
    const questionDelay = Math.max(0, questionTime - now);
    const memeDelay = Math.max(0, memeTime - now);

    setTimeout(() => {
        startTimer(event.openingAt, 5);
        displaySection("opening", () => {
            containers.opening.innerHTML = `<h1>Question Number ${questionCounter}</h1>`;
        });
    }, openingDelay);

    setTimeout(() => {
        const timeoutDuration = 15000;
        startTimer(event.questionAt, 15);
        displaySection("quiz", () => renderQuestion(event.question));
        setTimeout(() => {
            if (!hasAnswered) handlePlayerAnswer(0, null, false);
        }, timeoutDuration);
    }, questionDelay);

    setTimeout(() => {
        startTimer(event.memeAt, 5);
        displaySection("meme", () => {
            containers.meme.innerHTML = `<p>Ini mim</p>`;
        });
    }, memeDelay);
}

function handleStandingsEvent(event) {
    const now = new Date();
    const standingsTime = new Date(event.standingsAt);
    const standingsDelay = Math.max(0, standingsTime - now);

    setTimeout(() => {
        startTimer(event.standingsAt, 10);
        displaySection("standings", () => renderStandings(event.players));
    }, standingsDelay);
}


function displaySection(sectionName, renderCallback) {
    Object.keys(containers).forEach(name => {
        containers[name].style.display = name === sectionName ? "block" : "none";
    });
    renderCallback();
}

function renderQuestion(question) {
    const answersHTML = Object.entries(question.answers)
        .map(([key, text]) => text ? `
            <li>
                <input type="radio" name="question" value="${key}" class="answer-option"> ${text}
            </li>` : '')
        .join('');

    containers.quiz.innerHTML = `
        <h3>${question.question}</h3>
        <ul id="answers">${answersHTML}</ul>
    `;

    document.querySelectorAll(".answer-option").forEach(option => {
        option.addEventListener("change", async () => {
            hasAnswered = true;
            const userAnswer = option.value;
            const correctAnswer = getCorrectAnswer(question.correct_answers);

            document.querySelectorAll(".answer-option").forEach(input => input.disabled = true);
            const isTrue = checkAnswer(correctAnswer, userAnswer);

            const correctPoint = 100;

            const handlePlayer = await handlePlayerAnswer(correctPoint, userAnswer, isTrue);

            console.log(handlePlayer.message)
        });
    });
}

function getCorrectAnswer(correctAnswers) {
    return Object.entries(correctAnswers)
        .find(([_, value]) => value === "true")?.[0].replace("_correct", "");
}

function checkAnswer(correct, selected) {
    if(correct === selected){
        console.log("Benar");
        // edit DOM
        return true;
    } else {
        console.log(`Salah, jawaban benar ${correct}`);
        // edit DOM
        return false;
    }
}

function renderStandings(players) {
    const tableBody = document.querySelector('#standings-table tbody');
    tableBody.innerHTML = players.map((player, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${player.username}</td>
            <td>${player.point}</td>
        </tr>
    `).join('');
}

function startTimer(eventScheduledAt, duration) {
    const scheduledAt = new Date(eventScheduledAt);
    const now = new Date();
    const elapsed = Math.floor((now - scheduledAt) / 1000);
    let timeLeft = Math.max(0, duration - elapsed);

    clearInterval(countdown);
    timerText.textContent = `Timer: ${timeLeft}`;

    countdown = setInterval(() => {
        timeLeft--;
        timerText.textContent = `Timer: ${timeLeft}`;
        if (timeLeft <= 0) clearInterval(countdown);
    }, 1000);
}

async function handlePlayerAnswer(point, userAnswer, isCorrect){
    const sessionId = await getSessionIdBySessionCode(sessionCode);
    try {
        const response = await fetch('/api/multiplayer/update-player-point', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                multiplayer_session_id: sessionId,
                player_id: playerId,
                point: point,
                answer: userAnswer,
                is_correct: isCorrect
            })
        });

        const data = await response.json();

        console.log(data)
    } catch (error) {
        console.error("Error: ", error);
    }
}
