document.addEventListener("DOMContentLoaded", function () {
    let currentQuestionIndex = 0;

    function loadNextQuestion() {
        fetch('/quiz/next')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                try {
                    const data = JSON.parse(text); // Try parsing JSON
                    if (data.question) {
                        displayQuestion(data.question);
                    } else {
                        document.getElementById("quiz-container").innerHTML = `<p>Quiz Completed!</p>`;
                        document.getElementById("nextQuestionBtn").style.display = "none";
                    }
                } catch {
                    console.error("Invalid JSON response.");
                }
            })
            .catch(() => {
                console.error("Failed to fetch the next question.");
            });
    }


    function displayQuestion(question) {
        document.getElementById("quiz-container").innerHTML = `
            <h3>${currentQuestionIndex + 1}. ${question.question}</h3>
            <ul>
                ${Object.entries(question.answers).map(([key, answer]) => answer ? `
                    <li>
                        <input type="radio" name="question" value="${key}" class="answer-option">
                        ${answer}
                    </li>
                ` : '').join('')}
            </ul>
        `;
        document.getElementById("nextQuestionBtn").style.display = "none";
    }

    document.getElementById("quiz-container").addEventListener("change", function () {
        document.getElementById("nextQuestionBtn").style.display = "block";
    });

    document.getElementById("nextQuestionBtn").addEventListener("click", function () {
        currentQuestionIndex++;
        loadNextQuestion();
    });
    loadNextQuestion(); // Start quiz 
});
