function openAddModal() {
  document.getElementById("addModal").style.display = "block";
}

function openEditModal(product) {
  if (!product || !product.id) {
    alert("Invalid product data.");
    return;
  }

  document.getElementById("edit_id").value = product.id;
  document.getElementById("edit_name").value = product.name || "";
  document.getElementById("edit_price").value = product.price || "";
  document.getElementById("edit_description").value = product.description || "";
  document.getElementById("edit_category").value = product.category || "";
  document.getElementById("edit_current_photo").value = product.image || "";

  const preview = document.getElementById("current_photo_preview");
  if (product.image) {
    preview.innerHTML = `<img src="../../resources/${product.image}" alt="Current photo" style="max-width: 100px; margin-top: 10px; border-radius: 4px;">`;
  } else {
    preview.innerHTML =
      '<p style="margin-top: 10px; color: #666;">No current photo</p>';
  }

  document.getElementById("editModal").style.display = "block";
}

function confirmDelete(id, name) {
  document.getElementById("delete_id").value = id;
  document.getElementById("delete_product_name").textContent = name;
  document.getElementById("deleteModal").style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

window.onclick = function (event) {
  const modals = ["addModal", "editModal", "deleteModal"];
  modals.forEach((modalId) => {
    const modal = document.getElementById(modalId);
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
};

document.addEventListener("DOMContentLoaded", function () {
  const successMessage = document.querySelector(".success-message");
  if (successMessage) {
    setTimeout(() => {
      successMessage.style.opacity = "0";
      setTimeout(() => {
        successMessage.remove();
      }, 300);
    }, 3000);
  }
});
