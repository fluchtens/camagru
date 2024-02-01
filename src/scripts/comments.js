document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("commentsModal");
  const closeBtn = document.getElementById("closeCommentsModalBtn");

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
    window.history.back();
  });
});
