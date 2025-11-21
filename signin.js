
// Login Page Validation
// ----------------------------
if (document.getElementById("loginBtn")) {
    document.getElementById("loginBtn").addEventListener("click", function () {
        let user = document.getElementById("username").value.trim();
        let pass = document.getElementById("password").value.trim();

        if (user === "" || pass === "") {
            alert("Please enter both username and password.");
            return;
        }

        alert("Login successful.");
    });
}

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

