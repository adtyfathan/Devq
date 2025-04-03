document.addEventListener('DOMContentLoaded', function(){
    const urlParams = new URLSearchParams(window.location.search);
    const category = window.location.pathname.split("/").pop();
    const difficulty = urlParams.get("difficulty") || "easy";
    const hostId = window.Laravel.user_id;

    fetch('/api/multiplayer/host', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            host_id: hostId
            // quiz_id:
        })
    }).then(response => response.json())
    .then(data => console.log(data))
});


