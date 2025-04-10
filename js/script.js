document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("password");
  const viewButton = document.getElementById("button-view-password");

  if (passwordInput && viewButton) {
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
  }
});

function limitSelection(checkbox) {
  const alreadySavedCount = window.alreadySavedCount || 0;
  const selected = document.querySelectorAll('input[name="ids[]"]:checked');
  const maxAllowed = 3 - alreadySavedCount;

  if (selected.length > maxAllowed) {
    alert(`You can only save ${maxAllowed} more product${maxAllowed !== 1 ? 's' : ''}.`);
    checkbox.checked = false;
    return false;
  }
  return true;
}