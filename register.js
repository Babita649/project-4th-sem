// Register Page Validation
// ----------------------------
document.getElementById("registerBtn").addEventListener("click", function (event) {
    let name = document.getElementById("fullname").value.trim();
    let email = document.getElementById("email").value.trim();
    let pass = document.getElementById("regPassword").value.trim();
    let cpass = document.getElementById("confirmPassword").value.trim();

    // Full Name Validation (Only letters and spaces)
    const namePattern = /^[A-Za-z ]+$/;
    if (!namePattern.test(name)) {
        alert("Full name must contain only letters and spaces (no numbers allowed).");
        event.preventDefault();
        return;
    }

    // Email Validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
        return;
    }

    // Password Validation — At least 6 chars & 1 Capital Letter
    const passwordPattern = /^(?=.*[A-Z]).{6,}$/;
    if (!passwordPattern.test(pass)) {
        alert("Password must be at least 6 characters long and contain at least one uppercase letter.");
        event.preventDefault();
        return;
    }

    // Confirm Password Check
    if (pass !== cpass) {
        alert("Passwords do not match.");
        event.preventDefault();
        return;
    }

    // Prevent submit if email already exists (from AJAX)
    if (emailExists) {
        alert("Email already in use. Please enter a different one.");
        event.preventDefault();
        return;
    }

    alert("Registration successful!");
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


