const registerForm = document.getElementById("registerForm");
const emailField = document.getElementById("email");
let emailExists = false;

// AJAX email check
emailField.addEventListener("blur", function () {
    const email = emailField.value.trim();
     if (email.length < 3) return; // don't check too early
    let formData = new FormData();
    formData.append("email", email);
    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "check_email.php", true);
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
           // console.log("AJAX response:" , JSON.stringify(this.responseText));
            //const response= this.responseText.trim();
            if (this.responseText.trim() === "exists") {
                document.getElementById("err_email").innerHTML= "Email already in use!";
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

    // Full name validation
    if (!/^[A-Za-z ]+$/.test(name)) {
        alert("Full name must contain only letters and spaces.");
        event.preventDefault();
        return;
    }

    // Email validation
    const username = email.split("@")[0];
    if (!email.includes("@") || !/[a-zA-Z]/.test(username)) {
        alert("Email must be valid and contain at least one letter before @");
        event.preventDefault();
        return;
    }

    // Password validation
    if (!/^(?=.*[A-Z]).{6,}$/.test(pass)) {
        alert("Password must be at least 6 characters and contain one uppercase letter.");
        event.preventDefault();
        return;
    }

    // Confirm password
    if (pass !== cpass) {
        alert("Passwords do not match.");
        event.preventDefault();
        return;
    }

    // Email already exists
    if (emailExists) {
        alert("Email already in use.");
        event.preventDefault();
        return;
    }
});
