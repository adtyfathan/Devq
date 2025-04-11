document.addEventListener("DOMContentLoaded", () => {
    const userId = window.Laravel?.user_id;
    const historyWrapper = document.getElementById("history-wrapper");

    if (!userId) return console.error("User ID not found.");

    const fetchHistory = async () => {
        try {
            const response = await fetch(`/api/history/${userId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const quizzes = await response.json();
            renderHistory(quizzes);
        } catch (error) {
            console.error("Error fetching quiz history:", error);
        }
    };

    const formatDate = (datetime) => {
        const date = new Date(datetime);

        const day = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const time = date.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        return `${time} ${day}`;
    };

    const renderHistory = (quizzes) => {
        historyWrapper.innerHTML = quizzes.map(({ id, category, difficulty, score, completed_at }) => `
            <div class="history-container">
                <h1>${difficulty} ${category} Quiz</h1>
                <p>${formatDate(completed_at)}</p>
                <p>Score: ${score}/100</p>
                <a href="/summary?id=${id}">Detail</a>
            </div>
        `).join('');
    };

    fetchHistory();
});
