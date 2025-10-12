document
  .getElementById("profile-form")
  .addEventListener("submit", function (e) {
    const newPassword = document.getElementById("new_password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    const currentPassword = document.getElementById("current_password").value;

    if (newPassword || confirmPassword || currentPassword) {
      if (!currentPassword) {
        alert("Please enter your current password to change it.");
        e.preventDefault();
        return;
      }
      if (!newPassword) {
        alert("Please enter a new password.");
        e.preventDefault();
        return;
      }
      if (!confirmPassword) {
        alert("Please confirm your new password.");
        e.preventDefault();
        return;
      }
      if (newPassword !== confirmPassword) {
        alert("New passwords do not match.");
        e.preventDefault();
        return;
      }
      if (newPassword.length < 6) {
        alert("New password must be at least 6 characters long.");
        e.preventDefault();
        return;
      }
    }
  });

setTimeout(function () {
  const messages = document.querySelectorAll(".message");
  messages.forEach(function (message) {
    message.style.opacity = "0";
    setTimeout(function () {
      message.style.display = "none";
    }, 300);
  });
}, 5000);
