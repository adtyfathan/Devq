document.addEventListener("DOMContentLoaded", function(){
    const userId = window.Laravel.user_id;
    console.log(userId)

    const titleText = document.getElementById("title");
    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const pointInput = document.getElementById("point");

    function fetchUser(){
        fetch(`user/${userId}`)
            .then(response => response.json())
            .then(data => {
                displayUser(data);
            })
            .catch(error => console.error("Fetch error:", error));
    }

    function displayUser(user){
        titleText.innerHTML = `Halo ${user.name}!`;
        nameInput.value = user.name;
        emailInput.value = user.email;
        pointInput.value = user.point;
    }

    fetchUser();
})