document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const quizId = urlParams.get("id");
    const summaryWrapper = document.getElementById("summary-wrapper");

    if (!quizId) {
        console.error("No quiz ID provided.");
        return;
    }

    function fetchQuiz() {
        fetch(`/api/quiz/${quizId}`) 
            .then(response => response.json())
            .then(data => {
                displaySummary(data);
            })
            .catch(error => console.error("Error fetching quiz:", error));
    }

    function formatDate(quizDate) {
        const date = new Date(quizDate);

        const formattedDate = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });

        const formattedTime = date.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        });

        const finalDate = formattedTime + " " + formattedDate;

        return finalDate;
    }

    function displaySummary(quiz){
        summaryWrapper.innerHTML += `
            <h1>${quiz.difficulty} ${quiz.category} Quiz</h1>
            <p>${formatDate(quiz.completed_at)}</p>
            <p>score :${quiz.score}/100</p>
            <a href="/review?id=${quiz.id}">Review</a>
        `;
    }

    fetchQuiz();
});