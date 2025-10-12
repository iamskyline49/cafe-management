const modal = document.getElementById("passwordModal");
const openBtn = document.getElementById("openPasswordModal");
const closeBtn = document.getElementById("closePasswordModal");
openBtn.onclick = function () {
  modal.style.display = "block";
};
closeBtn.onclick = function () {
  modal.style.display = "none";
};
window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.display = "none";
  }
};
