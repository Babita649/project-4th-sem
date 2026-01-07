const registerForm = document.getElementById("registerForm");
const emailField = document.getElementById("email");
let emailExists = false;

// AJAX email check
<<<<<<< HEAD
emailField.addEventListener("blur", function () {
    const email = emailField.value.trim();
     if (email.length < 3) return; // don't check too early
=======
emailField.addEventListener("keyup", function () {
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
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

// Validation
registerForm.addEventListener("submit", function (event) {
    const name = document.getElementById("fullname").value.trim();
    const email = emailField.value.trim();
    const pass = document.getElementById("regPassword").value.trim();
    const cpass = document.getElementById("confirmPassword").value.trim();

<<<<<<< HEAD
    // Full name validation
=======
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
    if (!/^[A-Za-z ]+$/.test(name)) {
        alert("Full name must contain only letters and spaces.");
        event.preventDefault();
        return;
    }

<<<<<<< HEAD
    // Email validation
    const username = email.split("@")[0];
    if (!email.includes("@") || !/[a-zA-Z]/.test(username)) {
        alert("Email must be valid and contain at least one letter before @");
=======
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Invalid email address.");
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
        event.preventDefault();
        return;
    }

<<<<<<< HEAD
    // Password validation
=======
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
    if (!/^(?=.*[A-Z]).{6,}$/.test(pass)) {
        alert("Password must be at least 6 characters and contain one uppercase letter.");
        event.preventDefault();
        return;
    }

<<<<<<< HEAD
    // Confirm password
=======
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
    if (pass !== cpass) {
        alert("Passwords do not match.");
        event.preventDefault();
        return;
    }

<<<<<<< HEAD
    // Email already exists
=======
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
    if (emailExists) {
        alert("Email already in use.");
        event.preventDefault();
    }
});
