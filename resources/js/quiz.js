document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const category = window.location.pathname.split("/").pop();
    const difficulty = urlParams.get("difficulty") || "easy";
    const limit = urlParams.get("limit") || 10;

    let questions = [];
    let currentQuestionIndex = 0;

    const quizContainer = document.getElementById("quiz-container");
    const nextButton = document.getElementById("nextQuestionBtn");

    function fetchQuestions() {
        fetch(`/api/questions?category=${category}&difficulty=${difficulty}&limit=${limit}`)
            .then(response => response.json())
            .then(data => {
                questions = data.questions;
                if (questions.length > 0) {
                    displayQuestion(questions[currentQuestionIndex]);
                } else {
                    quizContainer.innerHTML = "<p>No questions found.</p>";
                }
            })
            .catch(error => console.error("Fetch error:", error));
    }

    function displayQuestion(question) {
        quizContainer.innerHTML = `
            <h3>${currentQuestionIndex + 1}. ${question.question}</h3>
            <ul id="answers">
                ${Object.entries(question.answers)
                .map(([key, answer]) => answer ? `
                        <li>
                            <input type="radio" name="question" value="${key}" class="answer-option">
                            ${answer}
                        </li>` : '')
                .join('')}
            </ul>
        `;

        nextButton.style.display = "none";

        document.querySelectorAll(".answer-option").forEach(option => {
            option.addEventListener("change", function () {
                nextButton.style.display = "block";
            });
        });
    }

    nextButton.addEventListener("click", function () {
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            displayQuestion(questions[currentQuestionIndex]);
        } else {
            quizContainer.innerHTML = "<p>Quiz Completed!</p>";
            nextButton.style.display = "none";
        }
    });

    fetchQuestions();
});
