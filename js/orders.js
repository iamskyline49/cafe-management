function filterOrders() {
  const filter = document.getElementById("statusFilter").value;
  const rows = document.querySelectorAll("tbody tr");

  rows.forEach((row) => {
    if (filter === "" || row.dataset.status === filter) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}

function updateOrderStatus(orderId, status) {
  if (confirm(`Mark order #${orderId} as ${status}?`)) {
    alert(`Order #${orderId} marked as ${status}!`);
    location.reload();
  }
}

function editOrder(orderId) {
  alert(`Edit order #${orderId}`);
}
