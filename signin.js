
// Login Page Validation
// ----------------------------
// Signin Validation
document.getElementById("loginBtn").addEventListener("click", function (event) {
    let email = document.getElementById("email").value.trim();
    let pass = document.getElementById("password").value.trim();

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
        return;
    }

    // Password validation — at least 6 characters & 1 capital letter
    const passwordPattern = /^(?=.*[A-Z]).{6,}$/;
    if (!passwordPattern.test(pass)) {
        alert("Password must be at least 6 characters long and contain at least one uppercase letter.");
        event.preventDefault();
        return;
    }
});

