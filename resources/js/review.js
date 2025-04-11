document.addEventListener("DOMContentLoaded", () => {
    const quizId = new URLSearchParams(window.location.search).get("id");
    const reviewWrapper = document.getElementById("review-wrapper");

    if (!quizId) return console.error("No quiz ID provided.");

    const fetchReview = async () => {
        try {
            const response = await fetch(`/api/review/${quizId}`);
            const { questions } = await response.json();

            questions.forEach(({ question, answers, correct_answers, user_answer }) => {
                renderQuestion(question, JSON.parse(answers), user_answer, getCorrectAnswer(correct_answers));
            });
        } catch (error) {
            console.error("Error fetching quiz:", error);
        }
    };

    const getCorrectAnswer = (correctAnswers) => {
        const parsed = JSON.parse(correctAnswers);
        return Object.entries(parsed)
            .find(([_, value]) => value === "true")?.[0]
            .replace("_correct", "");
    };

    const renderQuestion = (text, options, userAnswer, correctAnswer) => {
        const isCorrect = userAnswer === correctAnswer;

        const optionsHTML = Object.entries(options).map(([key, label]) => {
            if (!label) return '';
            const className = isCorrect
                ? (key === correctAnswer ? "correct-answer" : "")
                : key === correctAnswer
                    ? "correct-answer"
                    : key === userAnswer
                        ? "user-answer"
                        : "";

            return `<li class="${className}">${label}</li>`;
        }).join('');

        reviewWrapper.innerHTML += `
            <div class="question-container ${isCorrect ? "correct-container" : "wrong-container"}">
                <h3>${text}</h3>
                <ul id="answers">${optionsHTML}</ul>
            </div>
        `;
    };

    fetchReview();
});
