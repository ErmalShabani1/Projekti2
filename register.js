const users = [];

document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("regform")
    .addEventListener("submit", function (event) {
      event.preventDefault(); // Prevent normal form submission

      const emri = document
        .querySelector("#regform input[name='emri']")
        .value.trim();
      const email = document
        .querySelector("#regform input[name='email']")
        .value.trim();
      const password = document
        .querySelector("#regform input[name='password']")
        .value.trim();
      const confirmPassword = document
        .querySelector("#regform input[name='confirm_password']")
        .value.trim();

      // Check if passwords match
      if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return;
      }

      // Check if username exists (you can add your own user validation here)
      const usernameExists = users.some((user) => user.username === emri);
      if (usernameExists) {
        alert("Username already exists, please choose another one.");
        return;
      }

      // Check if email exists
      const emailExists = users.some((user) => user.email === email);
      if (emailExists) {
        alert("Email is already registered. Please use another one.");
        return;
      }

      // Validate if all fields are filled
      if (!emri || !email || !password || !confirmPassword) {
        alert("Please fill in all fields.");
        return;
      }

      // Create a FormData object to send via AJAX
      const formData = new FormData();
      formData.append("emri", emri);
      formData.append("email", email);
      formData.append("password", password);

      // Create an XMLHttpRequest to send the form data to PHP
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "register.php", true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          alert(`Successfully registered, ${emri}!`);
          document.getElementById("regform").reset();
        } else {
          alert(`Error: ${xhr.responseText}`);
        }
      };
      xhr.send(formData);
    });
});
