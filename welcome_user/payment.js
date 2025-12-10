
// Get duration from URL
let duration = getQueryParam("duration") || 1; // default 1 hour
let ratePerHour = 50;
let totalAmount = ratePerHour * parseInt(duration);

document.getElementById("duration").innerText = duration + (duration == 1 ? " Hour" : " Hours");
document.getElementById("rate").innerText = `₹${ratePerHour}/hr`;
document.getElementById("total").innerText = `₹${totalAmount}`;

// Handle form submission
document.getElementById("paymentForm").addEventListener("submit", function(e){
    e.preventDefault();
    document.getElementById("successMessage").style.display = "block";
});

// Go back to duration selection
function goBack() {
    window.history.back();
}
