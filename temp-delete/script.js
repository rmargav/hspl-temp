document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("inquiryForm");

    form.addEventListener("submit", (e) => {
        const company = document.querySelector('input[name="company"]').value.trim();
        
        if (company === "") {
            e.preventDefault(); // Stop submission
            alert("Bro, please fill out the company name!");
        }
    });
});