document.addEventListener("DOMContentLoaded", () => {
    const summaryWrapper = document.getElementById("summary-wrapper");
    const quizId = new URLSearchParams(window.location.search).get("id");

    if (!quizId) return console.error("No quiz ID provided.");

    const fetchQuiz = async () => {
        try {
            const response = await fetch(`/api/quiz/${quizId}`);
            const quiz = await response.json();
            renderSummary(quiz);
        } catch (error) {
            console.error("Error fetching quiz:", error);
        }
    };

    const formatDate = (datetime) => {
        const date = new Date(datetime);
        const dateStr = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        const timeStr = date.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        return `${timeStr} ${dateStr}`;
    };

    const renderSummary = (quiz) => {
        summaryWrapper.innerHTML = `
            <h1>${quiz.difficulty} ${quiz.category} Quiz</h1>
            <p>${formatDate(quiz.completed_at)}</p>
            <p>Score: ${quiz.score}/100</p>
            <a href="/review?id=${quiz.id}">Review</a>
        `;
    };

    fetchQuiz();
});
