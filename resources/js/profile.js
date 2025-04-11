document.addEventListener("DOMContentLoaded", () => {
    const userId = window.Laravel?.user_id;
    if (!userId) return console.error("User ID not found.");

    const titleText = document.getElementById("title");
    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const pointInput = document.getElementById("point");

    const fetchUser = async () => {
        try {
            const response = await fetch(`/user/${userId}`);
            const user = await response.json();
            renderUser(user);
        } catch (error) {
            console.error("Error fetching user:", error);
        }
    };

    const renderUser = ({ name, email, point }) => {
        titleText.textContent = `Halo ${name}!`;
        nameInput.value = name;
        emailInput.value = email;
        pointInput.value = point;
    };

    fetchUser();
});
