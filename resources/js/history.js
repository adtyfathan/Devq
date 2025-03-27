document.addEventListener("DOMContentLoaded", function () {
    const userId = window.Laravel.user_id;
    const historyWrapper = document.getElementById("history-wrapper");

    function fetchHistory() {
        fetch(`/profile/user/history/${userId}`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log(data)
                data.forEach(quiz => {
                    historyWrapper.innerHTML += `
                        <div class="history-container">
                            <h1>${quiz.difficulty} ${quiz.category} Quiz</h1>
                            <p>${formatDate(quiz.completed_at)}</p>
                            <p>Score: ${quiz.score}/100</p>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error("Error fetching data:", error);
            });
    }

    function formatDate(quizDate){
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

    fetchHistory();
});
