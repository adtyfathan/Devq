document.addEventListener("DOMContentLoaded", function(){
    const urlParams = new URLSearchParams(window.location.search);
    const quizId = urlParams.get("id");
    const reviewWrapper = document.getElementById("review-wrapper");

    if (!quizId) {
        console.error("No quiz ID provided.");
        return;
    }

    function fetchQuiz(){
        fetch(`/api/quiz/${quizId}`)
            .then(response => response.json())
            .then(quiz => {
                let answers = JSON.parse(quiz.user_answer);
                answers.forEach(answer => {
                    // string
                    const userAnswer = answer.answer;
                    
                    fetch(`/api/question/${answer.question_id}`)
                        .then(response => response.json())
                        .then(question => {
                            displayQuestion(question, userAnswer);
                        })
                        .catch(error => console.error("Error fetching question:", error));
                });
            })
            .catch(error => console.error("Error fetching quiz:", error));
    }

    function getCorrectAnswers(correctAnswers) {
        const parsedAnswer = JSON.parse(correctAnswers)

        return Object.entries(parsedAnswer)
            .filter(([key, value]) => value === "true")
            .map(([key]) => key.replace("_correct", ""))
            .shift();
    }

    function displayQuestion(question, userAnswer) {
        const options = question.answers;

        // string
        const correct_answer= getCorrectAnswers(question.correct_answers)
    
        const parsedOption = JSON.parse(options);

        const questionState = checkAnswer(userAnswer, correct_answer);

        reviewWrapper.innerHTML += `
            <div class="question-container ${questionState === true ? "correct-container" : "wrong-container"}">
                <h3>${question.question}</h3>
                <ul id="answers">
                    ${Object.entries(parsedOption)

                    // ANSWER CLASSIFICATION
                    // if (questionState === true) {
                    //     if (key === correct_answer) {
                    //         'correct-answer'
                    //     }
                    // } else {
                    //     if (key === correct_answer) {
                    //         'correct-answer'
                    //     } else if (key === userAnswer) {
                    //         'user-answer'
                    //     } else {
                    //         ''
                    //     }
                    // }

                    .map(([key, option]) => option ? `
                            <li class="${
                                    questionState
                                        ? (key === correct_answer ? 'correct-answer' : '')
                                        : (key === correct_answer 
                                            ? 'correct-answer' 
                                            : key === userAnswer
                                                ? 'user-answer'
                                                : ''
                                            )
                                    }">
                                ${option}
                            </li>` : null)
                    .join('')}
                </ul>
            </div>
        `;
    }

    function checkAnswer(userAnswer, correctAnswer){
        if (userAnswer === correctAnswer){
            return true;
        } else {
            return false;
        }
    }

    fetchQuiz();
});