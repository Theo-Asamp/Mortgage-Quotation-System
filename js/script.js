document.addEventListener('DOMContentLoaded', function () {
  const calculateBtn = document.getElementById('calculateBtn');
  const saveBtn = document.getElementById('saveQuoteBtn');
  const resultsDiv = document.getElementById('results');

  if (saveBtn) saveBtn.style.display = 'none';

  function getFormValues() {
    return {
      income: parseFloat(document.getElementById('income').value) || 0,
      bonus: parseFloat(document.getElementById('bonus').value) || 0,
      overtime: parseFloat(document.getElementById('overtime').value) || 0,
      outcome: parseFloat(document.getElementById('outcome').value) || 0,
      property: parseFloat(document.getElementById('property').value) || 0,
      borrow: parseFloat(document.getElementById('borrow').value) || 0,
      years: parseInt(document.getElementById('years').value) || 0,
      months: parseInt(document.getElementById('months').value) || 0
    };
  }

  function calculateAndDisplay() {
    const v = getFormValues();
    const totalIncome = v.income + v.bonus + v.overtime;
    const maxBorrow = totalIncome * 4 - v.outcome;
    const totalMonths = v.years * 12 + v.months;
    const monthlyRepayment = totalMonths > 0 ? v.borrow / totalMonths : 0;

    resultsDiv.innerHTML = `
      <h3>Results:</h3>
      <p><strong>Max Borrowing Capacity:</strong> £${maxBorrow.toFixed(2)}</p>
      <p><strong>Monthly Repayment:</strong> £${monthlyRepayment.toFixed(2)}</p>
      <p><strong>Repayment Period:</strong> ${v.years} years and ${v.months} months</p>
    `;

    if (saveBtn) saveBtn.style.display = 'inline-block';
  }

  function saveQuote() {
    const v = getFormValues();
    const formData = new FormData();
    for (const key in v) {
      formData.append(key, v[key]);
    }

    fetch('save_user_quote.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
        } else {
          alert(data.error || 'Save failed.');
        }
      })
      .catch(error => {
        console.error('Save Error:', error);
        alert('An error occurred while saving.');
      });
  }

  if (calculateBtn) calculateBtn.addEventListener('click', calculateAndDisplay);
  if (saveBtn) saveBtn.addEventListener('click', saveQuote);
});

document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("password");
  const viewButton = document.getElementById("button-view-password");

  viewButton.addEventListener("click", function (event) {
    event.preventDefault();

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      viewButton.textContent = "Hide";
    } else {
      passwordInput.type = "password";
      viewButton.textContent = "View";
    }
  });
});

