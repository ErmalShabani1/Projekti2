const user = JSON.parse(localStorage.getItem("loggedInUser"));

if (user) {
    document.getElementById("username").textContent = `Username: ${user.username}`;
    document.getElementById("email").textContent = `Email: ${user.email}`;
} else {

    window.location.href = "login.php";
}
