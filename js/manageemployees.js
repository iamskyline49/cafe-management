let currentEmployeeId = null;

function showAddEmployeeModal() {
  currentEmployeeId = null;
  document.getElementById("modalTitle").textContent = "Add New Employee";
  document.getElementById("employeeForm").reset();
  document.getElementById("employeePassword").required = true;
  document.querySelector(".password-group").style.display = "block";
  document.getElementById("employeeModal").style.display = "block";
}

function editEmployee(id) {
  currentEmployeeId = id;
  document.getElementById("modalTitle").textContent = "Edit Employee";
  document.getElementById("employeePassword").required = false;
  document.querySelector(".password-group").style.display = "none";

  fetch("employee_actions.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `action=get_employees`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const employee = data.data.find((emp) => emp.id == id);
        if (employee) {
          document.getElementById("employeeName").value = employee.name;
          document.getElementById("employeeEmail").value = employee.email;
          document.getElementById("dutyFrom").value = employee.dutyFrom;
          document.getElementById("dutyTo").value = employee.dutyTo;
        }
      }
      document.getElementById("employeeModal").style.display = "block";
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to fetch employee data");
    });
}

function deleteEmployee(id) {
  if (confirm("Are you sure you want to delete this employee?")) {
    fetch("employee_actions.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=delete_employee&id=${id}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          location.reload();
        } else {
          alert(data.error || "Failed to delete employee");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Failed to delete employee");
      });
  }
}

function closeModal() {
  document.getElementById("employeeModal").style.display = "none";
}

document
  .getElementById("employeeForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    if (currentEmployeeId) {
      formData.append("action", "update_employee");
      formData.append("id", currentEmployeeId);
    } else {
      formData.append("action", "add_employee");
    }

    fetch("employee_actions.php", {
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
  const modal = document.getElementById("employeeModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
