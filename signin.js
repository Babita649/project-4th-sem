
// Login Page Validation
// ----------------------------
document.getElementById("signinForm").addEventListener("submit", function(e) {
    let user = document.getElementById("email").value.trim();
    let pass = document.getElementById("password").value.trim();

    if (user === "" || pass === "") {
        alert("Please enter both email and password.");
        e.preventDefault(); // stop form submission
    }
});


// ----------------------------
// Register Page Validation
// ----------------------------
document.getElementById("registerBtn").addEventListener("click",
     function () {
    let name = document.getElementById("fullname").value.trim();
    let email = document.getElementById("email").value.trim();
    let pass = document.getElementById("regPassword").value.trim();
    let cpass = document.getElementById("confirmPassword").value.trim();

    if (name === "") {
        alert("Full name cannot be empty.");
        return;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    if (pass.length < 6) {
        alert("Password must be at least 6 characters long.");
        return;
    }

    if (pass !== cpass) {
        alert("Passwords do not match.");
        return;
    }

    alert("Registration successful!");
     document.getElementById("signinForm").reset();
    document.getElementById("registerForm").reset();
    window.location.href = "signin.html";
});
//Ajax
let emailField = document.getElementById("email");
let emailExists = false; // Flag to prevent submission

emailField.addEventListener("keyup", function () {
    let formData = new FormData();
    formData.append("email", emailField.value);

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "check_email.php", true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.trim() === "exists") {
                document.getElementById("err_email").innerHTML = "Email already in use!";
                emailExists = true;
            } else {
                document.getElementById("err_email").innerHTML = "";
                emailExists = false;
            }
        }
    };
    xhttp.send(formData);
});

// Stop form submitting if emailExists = true
document.getElementById("registerForm").addEventListener("submit", function (event) {
    if (emailExists) {
        event.preventDefault(); // STOP FORM SUBMISSION
        alert("Please fix errors before submitting.");
    }
});


