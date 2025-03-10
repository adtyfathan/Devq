document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const category = window.location.pathname.split("/").pop();
    const difficulty = urlParams.get("difficulty") || "easy";
    const limit = urlParams.get("limit") || 10;

    let questions = [];
    let currentQuestionIndex = 0;
    let countdown;
    let timeLeft = 10;
    let selectedAnswer = [];

    const quizContainer = document.getElementById("quiz-container");
    const timerText = document.getElementById("timer");;

    function getCorrectAnswers(correctAnswers){
        return Object.entries(correctAnswers)
            .filter(([key, value]) => value === "true") // Filter only correct answers
            .map(([key]) => key.replace("_correct", "")) // Extract the answer key
            .shift();
    }

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

    function startTimer() {
        clearInterval(countdown); 
        timeLeft = 10; 
        timerText.innerHTML = `Timer: ${timeLeft}`;

        countdown = setInterval(() => {
            timeLeft--;
            timerText.innerHTML = `Timer: ${timeLeft}`;

            if (timeLeft <= 0) {
                clearInterval(countdown);

                const correctAnswer = getCorrectAnswers(questions[currentQuestionIndex].correct_answers);

                checkAnswer(correctAnswer, null);

                // jeda buat animasi benar/salah
                setTimeout(nextQuestion, 3000);
            }
        }, 1000);
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

        startTimer();

        document.querySelectorAll(".answer-option").forEach(option => {
            option.addEventListener("change", function () {
                selectedAnswer[currentQuestionIndex] = this.value

                const correctAnswer = getCorrectAnswers(questions[currentQuestionIndex].correct_answers);
                const userAnswer = this.value;

                document.querySelectorAll(".answer-option").forEach(input => {
                    input.disabled = true;
                });

                checkAnswer(correctAnswer, userAnswer);

                clearInterval(countdown); 

                // jeda buat animasi benar/salah
                setTimeout(nextQuestion, 3000);
            });
        });
    }

    function checkAnswer(correctAnswer, userAnswer){
        if (correctAnswer === userAnswer) {
            alert("Benar");
        } else {
            alert(`Salah, jawaban benar ${correctAnswer}`);
        }
    }

    function nextQuestion() {
        clearInterval(countdown); 
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            displayQuestion(questions[currentQuestionIndex]);
        } else {
            // push user id, new generated quiz id, score, jawaban benar, questions, selected answer, time generated
            quizContainer.innerHTML = "<p>Quiz Completed!</p>";
            console.log(selectedAnswer);
        }
    }

    fetchQuestions();
});
