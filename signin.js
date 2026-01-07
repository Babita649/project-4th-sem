// Signin Validation
document.getElementById("loginBtn").addEventListener("submit", function (event) {
    let email = document.getElementById("email").value.trim();
    let pass = document.getElementById("password").value.trim();

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
        return;
    }

    const passwordPattern = /^(?=.*[A-Z]).{6,}$/;
    if (!passwordPattern.test(pass)) {
        alert("Password must be at least 6 characters and contain one uppercase letter.");
        event.preventDefault();
        return;
    }
});

