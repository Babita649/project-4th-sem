// Register Page Validation
// ----------------------------
// Variables
const registerForm = document.getElementById("registerForm");
const emailField = document.getElementById("email");
let emailExists = false;

// Email AJAX check
emailField.addEventListener("keyup", function () {
    let formData = new FormData();
    formData.append("email", emailField.value);

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "check_email.php", true);
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
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

// Form validation
registerForm.addEventListener("submit", function (event) {
    const name = document.getElementById("fullname").value.trim();
    const email = emailField.value.trim();
    const pass = document.getElementById("regPassword").value.trim();
    const cpass = document.getElementById("confirmPassword").value.trim();

    // Full Name Validation
    if (!/^[A-Za-z ]+$/.test(name)) {
        alert("Full name must contain only letters and spaces.");
        event.preventDefault();
        return;
    }

    // Email Validation
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
        return;
    }

    // Password Validation
    if (!/^(?=.*[A-Z]).{6,}$/.test(pass)) {
        alert("Password must be at least 6 characters long and contain at least one uppercase letter.");
        event.preventDefault();
        return;
    }

    // Confirm Password
    if (pass !== cpass) {
        alert("Passwords do not match.");
        event.preventDefault();
        return;
    }

    // Check email flag from AJAX
    if (emailExists) {
        alert("Email already in use. Please enter a different one.");
        event.preventDefault();
        return;
    }

    // ✅ Allow form submission; PHP will handle insert and redirect
});
