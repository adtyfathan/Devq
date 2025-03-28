document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const category = window.location.pathname.split("/").pop();
    const difficulty = urlParams.get("difficulty") || "easy";
    const limit = urlParams.get("limit") || 10;

    let questions = [];
    let currentQuestionIndex = 0;
    let countdown;
    let timeLeft = 10;
    let userAnswers = [];
    let falseAnswerCount = 0;

    const quizContainer = document.getElementById("quiz-container");
    const timerText = document.getElementById("timer");

    function getCorrectAnswers(correctAnswers){
        return Object.entries(correctAnswers)
            .filter(([key, value]) => value === "true") 
            .map(([key]) => key.replace("_correct", "")) 
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

                // jeda buat animasi benar/salah pas waktu 10 detik abis
                // setTimeout(nextQuestion, 3000);
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
                const correctAnswer = getCorrectAnswers(questions[currentQuestionIndex].correct_answers);
                const userAnswer = this.value;

                document.querySelectorAll(".answer-option").forEach(input => {
                    input.disabled = true;
                });

                checkAnswer(correctAnswer, userAnswer);

                userAnswers.push({
                    question_id: questions[currentQuestionIndex].id,
                    answer: userAnswer
                });

                clearInterval(countdown); 
                
                // jeda buat animasi benar/salah pas jawab
                // setTimeout(nextQuestion, 3000);
                nextQuestion();
            });
        });
    }

    function checkAnswer(correctAnswer, userAnswer){
        if (correctAnswer === userAnswer) {
            alert("Benar");
        } else {
            falseAnswerCount++;
            alert(`Salah, jawaban benar ${correctAnswer}`);
        }
    }

    function nextQuestion() {
        clearInterval(countdown); 
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            displayQuestion(questions[currentQuestionIndex]);
        } else {
            const userId = window.Laravel.user_id;
            const score = ((questions.length - falseAnswerCount) / questions.length) * 100;
            quizContainer.innerHTML = `<p>Quiz Completed! Score :</p> ${score}`;
            fetch('/api/quiz/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    user_id: userId,
                    score: score,
                    category: category,
                    difficulty: difficulty,
                    user_answer: userAnswers,
                    questions: questions
                    // question_id, question_question, question_desc
                })
            })
                .then(response => response.json())
                .then(data => {
                    setTimeout(() => {
                        window.location.href = `/summary?id=${data.id}`;
                    }, 5000);
                })
        }
    }

    fetchQuestions();
});
