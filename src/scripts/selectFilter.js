document.addEventListener("DOMContentLoaded", function () {
  var filterBtns = document.querySelectorAll(".filterBtn");
  var captureFilter = document.getElementById("captureFilter");
  var previewFilter = document.getElementById("previewFilter");

  filterBtns.forEach(function (button) {
    button.addEventListener("click", function () {
      var selectedFilter = "assets/filters/" + this.getAttribute("data-file");
      captureFilter.src = selectedFilter;
      previewFilter.src = selectedFilter;
    });
  });
});
