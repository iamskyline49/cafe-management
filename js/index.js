document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".order-special-form").forEach(function (form) {
    form.addEventListener("submit", function (e) {
      var confirmed = confirm("Do you want to order this special offer now?");
      if (!confirmed) {
        e.preventDefault();
      }
    });
  });
});
