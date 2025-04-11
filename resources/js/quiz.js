document.addEventListener("DOMContentLoaded", () => {
    const category = window.location.pathname.split("/").pop();
    const urlParams = new URLSearchParams(window.location.search);
    const difficulty = urlParams.get("difficulty") || "easy";
    const limit = urlParams.get("limit") || 10;

    const quizContainer = document.getElementById("quiz-container");
    const timerText = document.getElementById("timer");

    let questions = [];
    let currentQuestionIndex = 0;
    let countdown;
    let timeLeft = 10;
    let userAnswers = [];
    let falseAnswerCount = 0;

    const getCorrectAnswer = (correctAnswers) =>
        Object.entries(correctAnswers)
            .find(([_, value]) => value === "true")?.[0]
            .replace("_correct", "");

    const fetchQuestions = async () => {
        try {
            const res = await fetch(`/api/questions?category=${category}&difficulty=${difficulty}&limit=${limit}`);
            const data = await res.json();
            questions = data.questions;

            if (questions.length) {
                displayQuestion(questions[currentQuestionIndex]);
            } else {
                quizContainer.innerHTML = "<p>No questions found.</p>";
            }
        } catch (err) {
            console.error("Fetch error:", err);
        }
    };

    const startTimer = () => {
        clearInterval(countdown);
        timeLeft = 10;
        updateTimerText();

        countdown = setInterval(() => {
            timeLeft--;
            updateTimerText();

            if (timeLeft <= 0) {
                clearInterval(countdown);
                const correctAnswer = getCorrectAnswer(questions[currentQuestionIndex].correct_answers);
                checkAnswer(correctAnswer, null);
                nextQuestion(); // Optionally wrap with setTimeout for feedback animation
            }
        }, 1000);
    };

    const updateTimerText = () => {
        timerText.textContent = `Timer: ${timeLeft}`;
    };

    const displayQuestion = (question) => {
        const answersHTML = Object.entries(question.answers)
            .map(([key, text]) => text ? `
                <li>
                    <input type="radio" name="question" value="${key}" class="answer-option">
                    ${text}
                </li>` : ''
            ).join('');

        quizContainer.innerHTML = `
            <h3>${currentQuestionIndex + 1}. ${question.question}</h3>
            <ul id="answers">${answersHTML}</ul>
        `;

        startTimer();

        document.querySelectorAll(".answer-option").forEach(option => {
            option.addEventListener("change", () => {
                const userAnswer = option.value;
                const correctAnswer = getCorrectAnswer(questions[currentQuestionIndex].correct_answers);

                document.querySelectorAll(".answer-option").forEach(input => input.disabled = true);

                checkAnswer(correctAnswer, userAnswer);
                userAnswers.push(userAnswer);

                clearInterval(countdown);
                nextQuestion(); // Optionally wrap with setTimeout for feedback animation
            });
        });
    };

    const checkAnswer = (correct, selected) => {
        if (correct === selected) {
            alert("Benar");
        } else {
            falseAnswerCount++;
            alert(`Salah, jawaban benar ${correct}`);
        }
    };

    const nextQuestion = () => {
        clearInterval(countdown);
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            displayQuestion(questions[currentQuestionIndex]);
        } else {
            completeQuiz();
        }
    };

    const completeQuiz = async () => {
        const userId = window.Laravel.user_id;
        const score = ((questions.length - falseAnswerCount) / questions.length) * 100;

        quizContainer.innerHTML = `<p>Quiz Completed! Score : ${score}</p>`;

        try {
            const res = await fetch('/api/quiz/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    user_id: userId,
                    score,
                    category,
                    difficulty,
                    user_answer: userAnswers,
                    questions
                })
            });

            const data = await res.json();

            if (data.id) {
                setTimeout(() => window.location.href = `/summary?id=${data.id}`, 5000);
            } else {
                console.error("Quiz ID not returned:", data);
            }
        } catch (err) {
            console.error("Error:", err);
        }
    };

    fetchQuestions();
});
