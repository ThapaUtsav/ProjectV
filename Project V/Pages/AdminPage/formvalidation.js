// Wait until the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
    // Get the form element
    const form = document.querySelector("form");

    // Add event listener to form submission
    form.addEventListener("submit", function (event) {
        // Prevent form submission if validation fails
        if (!validateForm()) {
            event.preventDefault();
        }
    });

    function validateForm() {
        let isValid = true;

        // Email Validation
        const email = document.getElementById("email").value;
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            isValid = false;
        }

        // Date of Birth Validation (between 1950 and 2008)
        const dob = document.getElementById("dob").value;
        if (dob) {
            const dobDate = new Date(dob);
            const minDate = new Date("1950-01-01");
            const maxDate = new Date("2008-12-31");
            if (dobDate < minDate || dobDate > maxDate) {
                alert("Date of Birth should be between the years 1950 and 2008.");
                isValid = false;
            }
        }

        // Phone Number Validation (10 digits only)
        const phone = document.getElementById("phone").value;
        const phonePattern = /^\d{10}$/;
        if (phone && !phonePattern.test(phone)) {
            alert("Phone Number should be exactly 10 digits.");
            isValid = false;
        }

        // ZIP Code Validation (5 digits only)
        const zip = document.getElementById("zip").value;
        const zipPattern = /^\d{5}$/;
        if (!zipPattern.test(zip)) {
            alert("ZIP Code should be exactly 5 digits.");
            isValid = false;
        }

        // Account Created Date Validation (should be today or earlier)
        const accDate = document.getElementById("accDate").value;
        if (accDate) {
            const accountDate = new Date(accDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set to midnight for comparison
            if (accountDate > today) {
                alert("Account Created On date cannot be in the future.");
                isValid = false;
            }
        }

        return isValid;
    }
});
