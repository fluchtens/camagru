document.addEventListener("DOMContentLoaded", function () {
  const filterBtns = document.querySelectorAll(".filterBtn");
  const captureFilter = document.getElementById("captureFilter");
  const previewFilter = document.getElementById("previewFilter");
  const takePhotoBtn = document.getElementById("takePhotoBtn");

  filterBtns.forEach(function (button) {
    button.addEventListener("click", function () {
      const selectedFilter = "assets/filters/" + this.getAttribute("data-file");
      captureFilter.style.display = "block";
      captureFilter.src = selectedFilter;
      previewFilter.src = selectedFilter;
      takePhotoBtn.disabled = false;
    });
  });
});
