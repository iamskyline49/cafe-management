let currentCouponId = null;

function showAddCouponModal() {
  currentCouponId = null;
  document.getElementById("couponModalTitle").textContent = "Add New Coupon";
  document.getElementById("couponForm").reset();
  document.getElementById("couponModal").style.display = "block";
}

function editCoupon(id, couponString, percentage, active) {
  currentCouponId = id;
  document.getElementById("couponModalTitle").textContent = "Edit Coupon";
  document.getElementById("couponString").value = couponString;
  document.getElementById("percentage").value = percentage;
  document.getElementById("couponActive").value = active ? "1" : "0";

  document.getElementById("couponModal").style.display = "block";
}

function deleteCoupon(id) {
  if (
    confirm(
      "Are you sure you want to delete this coupon? This action cannot be undone."
    )
  ) {
    const formData = new FormData();
    formData.append("action", "delete_coupon");
    formData.append("id", id);

    fetch("coupon_actions.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          location.reload();
        } else {
          alert(data.error || "Failed to delete coupon");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Failed to delete coupon");
      });
  }
}

function closeCouponModal() {
  document.getElementById("couponModal").style.display = "none";
}
document.getElementById("couponForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);

  if (currentCouponId) {
    formData.append("action", "update_coupon");
    formData.append("id", currentCouponId);
  } else {
    formData.append("action", "add_coupon");
  }

  fetch("coupon_actions.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        location.reload();
      } else {
        alert(data.error || "Operation failed");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Operation failed");
    });
});
window.onclick = function (event) {
  const modal = document.getElementById("couponModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
