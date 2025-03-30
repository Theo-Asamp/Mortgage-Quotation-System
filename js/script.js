document.addEventListener('DOMContentLoaded', function () {
    console.log("JavaScript loaded successfully! ✅");

    const calculateBtn = document.getElementById('calculateBtn');

    if (calculateBtn) {
        calculateBtn.addEventListener('click', function () {
            // Get values from input fields
            const income = parseFloat(document.getElementById('income').value) || 0;
            const bonus = parseFloat(document.getElementById('bonus').value) || 0;
            const overtime = parseFloat(document.getElementById('overtime').value) || 0;
            const outcome = parseFloat(document.getElementById('outcome').value) || 0;
            const propertyValue = parseFloat(document.getElementById('property').value) || 0;
            const borrowAmount = parseFloat(document.getElementById('borrow').value) || 0;

            const years = parseInt(document.getElementById('years').value) || 0;
            const months = parseInt(document.getElementById('months').value) || 0;

            // Check if input values are valid
            if (income === 0 || propertyValue === 0 || borrowAmount === 0) {
                alert("Please fill out all required fields!");
                return;
            }

            // Basic mortgage calculation
            const totalIncome = income + bonus + overtime;
            const maxBorrow = totalIncome * 4 - outcome;

            // Monthly repayment calculation
            const totalMonths = years * 12 + months;
            const monthlyRepayment = borrowAmount / totalMonths;

            // Display results
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `
                <h3>Results:</h3>
                <p><strong>Max Borrowing Capacity:</strong> £${maxBorrow.toFixed(2)}</p>
                <p><strong>Monthly Repayment:</strong> £${monthlyRepayment.toFixed(2)}</p>
                <p><strong>Repayment Period:</strong> ${years} years and ${months} months</p>
            `;
        });
    } else {
        console.error("Submit button not found! ❌");
    }
});
