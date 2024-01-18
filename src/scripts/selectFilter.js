document.addEventListener("DOMContentLoaded", function () {
  const filterBtns = document.querySelectorAll(".filterBtn");
  const captureFilter = document.getElementById("captureFilter");
  const previewFilter = document.getElementById("previewFilter");
  const takePhotoBtn = document.getElementById("takePhotoBtn");

  filterBtns.forEach((button) => {
    button.addEventListener("click", () => {
      const filterSrc = "assets/filters/" + button.getAttribute("data-file");
      captureFilter.style.display = "block";
      captureFilter.src = filterSrc;
      previewFilter.src = filterSrc;
      takePhotoBtn.disabled = false;
    });
  });
});
