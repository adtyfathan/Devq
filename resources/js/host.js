document.addEventListener('DOMContentLoaded', function(){
    const urlParams = new URLSearchParams(window.location.search);
    const category = window.location.pathname.split("/").pop();
    const difficulty = urlParams.get("difficulty") || "easy";
    const limit = urlParams.get("limit") || 5;

    getQuestions(category, difficulty, limit);

    createTemplate(category, difficulty);

    // bikin multiplayer session baru
});

async function getQuestions(category, difficulty, limit){
    try {
        fetch(`/api/multiplayer/host/${category}?difficulty=${difficulty}&limit=${limit}`)
            .then(response => response.json())
            .then(data => console.log(data));
    } catch(error){
        console.error("Error: ", error)
    }
}

async function createTemplate(category, difficulty){
    try {
        fetch('/api/template/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                category: category,
                difficulty: difficulty
            })
        }).then(response => response.json())
            .then(data => console.log(data.message));
    } catch(error){
        console.error("Error: ", error)
    }
}


