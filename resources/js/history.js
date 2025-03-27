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
                            <p>${quiz.completed_at}</p>
                            <p>Score: ${quiz.score}/100</p>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error("Error fetching data:", error);
            });
    }

    fetchHistory();
});
