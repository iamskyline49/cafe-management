function searchUsers() {
  const input = document.getElementById("searchUsers");
  const filter = input.value.toLowerCase();
  const tbody = document.getElementById("usersTableBody");
  const rows = tbody.getElementsByTagName("tr");

  for (let i = 0; i < rows.length; i++) {
    const nameCell = rows[i].getElementsByTagName("td")[1];
    const emailCell = rows[i].getElementsByTagName("td")[2];

    if (nameCell && emailCell) {
      const nameText = nameCell.textContent || nameCell.innerText;
      const emailText = emailCell.textContent || emailCell.innerText;

      if (
        nameText.toLowerCase().indexOf(filter) > -1 ||
        emailText.toLowerCase().indexOf(filter) > -1
      ) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
}

function deleteUser(id) {
  if (
    confirm(
      "Are you sure you want to delete this user? This will also delete all their orders and cart items."
    )
  ) {
    fetch("user_actions.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=delete_user&id=${id}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("User deleted successfully!");
          location.reload();
        } else {
          alert(data.error || "Failed to delete user");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Failed to delete user");
      });
  }
}
