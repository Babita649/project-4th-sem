const registerForm = document.getElementById("registerForm");
const emailField = document.getElementById("email");
let emailExists = false;

// AJAX email check
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

// Validation
registerForm.addEventListener("submit", function (event) {
    const name = document.getElementById("fullname").value.trim();
    const email = emailField.value.trim();
    const pass = document.getElementById("regPassword").value.trim();
    const cpass = document.getElementById("confirmPassword").value.trim();

    if (!/^[A-Za-z ]+$/.test(name)) {
        alert("Full name must contain only letters and spaces.");
        event.preventDefault();
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Invalid email address.");
        event.preventDefault();
        return;
    }

    if (!/^(?=.*[A-Z]).{6,}$/.test(pass)) {
        alert("Password must be at least 6 characters and contain one uppercase letter.");
        event.preventDefault();
        return;
    }

    if (pass !== cpass) {
        alert("Passwords do not match.");
        event.preventDefault();
        return;
    }

    if (emailExists) {
        alert("Email already in use.");
        event.preventDefault();
    }
});
