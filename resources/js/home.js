function fetchQuestions(page) {
    let category = $('#category').val();
    let difficulty = $('#difficulty').val();
    let perPage = 5;

    $.ajax({
        url: `/api/questions?category=${category}&difficulty=${difficulty}&page=${page}&per_page=${perPage}`,
        method: "GET",
        success: function (response) {
            $('#questions-container').html('');

            response.forEach(question => {
                $('#questions-container').append(`
                            <div class="question-card">
                                <h3>${question.category} - ${question.difficulty} Quiz ${page}</h3>
                                <p>${question.question}</p>
                                <button onclick="startQuiz('${question.category}', '${question.difficulty}', ${page})">Start Quiz</button>
                            </div>
                        `);
            });
        }
    });
}

function startQuiz(category, difficulty, page) {
    window.location.href = `/quiz/${category}/${difficulty}/${page}`;
}

// Load questions automatically when the page loads
window.onload = function () {
    fetchQuestions(1);
};