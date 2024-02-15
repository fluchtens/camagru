async function publishPhotos() {
  try {
    const url = baseUrl + "controllers/post/publishPhotos.controller.php";
    const response = await fetch(url, {
      method: "POST",
    });

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    console.error("An error occurred:", error);
    return { success: false, message: error.message };
  }
}

async function savePhoto(image, filter) {
  try {
    const url = baseUrl + "controllers/post/takePhoto.controller.php";
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ image: image, filter: filter }),
    });

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    console.error("An error occurred:", error);
    return { success: false, message: error.message };
  }
}

document.addEventListener("DOMContentLoaded", (e) => {
  const captureVideo = document.getElementById("captureVideo");
  const captureFilter = document.getElementById("captureFilter");
  const canvasPreview = document.getElementById("photoPreview");
  const previewFilter = document.getElementById("previewFilter");
  const filters = document.getElementById("filters");
  const filterBtns = document.querySelectorAll(".filterBtn");
  const waiting = document.getElementById("waiting");
  const takePhotoBtn = document.getElementById("takePhotoBtn");
  const importInput = document.getElementById("importInput");
  const importBtn = document.getElementById("importBtn");
  const publishBtn = document.getElementById("publishPhotoBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  const saveBtn = document.getElementById("saveBtn");
  let selectedFilter = "1";

  let isDragging = false;
  let currentX, currentY;

  captureFilter.addEventListener("mousedown", function (e) {
    isDragging = true;
    currentX = e.clientX;
    currentY = e.clientY;
  });

  document.addEventListener("mousemove", function (e) {
    e.preventDefault();

    if (isDragging) {
      const dx = e.clientX - currentX;
      const dy = e.clientY - currentY;
      const style = window.getComputedStyle(captureFilter);
      const left = parseInt(style.left) + dx;
      const top = parseInt(style.top) + dy;
      captureFilter.style.left = `${left}px`;
      captureFilter.style.top = `${top}px`;
      currentX = e.clientX;
      currentY = e.clientY;
    }
  });

  document.addEventListener("mouseup", function (e) {
    if (isDragging) {
      isDragging = false;
      let style = window.getComputedStyle(captureFilter);
      let left = parseInt(style.left);
      let top = parseInt(style.top);
    }
  });

  const startWebcam = async () => {
    const constraints = {
      audio: false,
      video: { width: 468, height: 585, facingMode: "user" },
    };

    try {
      const stream = await navigator.mediaDevices.getUserMedia(constraints);
      captureVideo.srcObject = stream;
    } catch (error) {
      console.error("Error accessing webcam:", error);
    }
  };

  const stopWebcam = () => {
    const stream = captureVideo.srcObject;
    if (stream) {
      const tracks = stream.getTracks();
      tracks.forEach((track) => track.stop());
      captureVideo.srcObject = null;
    }
  };

  startWebcam();

  importInput.addEventListener("change", (e) => {
    const uploadedImage = e.target.files[0];

    if (uploadedImage) {
      const reader = new FileReader();
      reader.onload = (e) => {
        stopWebcam();
        captureVideo.style.display = "none";
        captureFilter.style.display = "none";
        canvasPreview.style.display = "block";
        previewFilter.style.display = "block";

        const selectedFilterBtn = document.querySelector(".filterBtn.selected");
        if (!selectedFilterBtn) {
          const firstBtn = document.querySelector(".filterBtn[data-id='1']");
          if (firstBtn) {
            firstBtn.click();
          }
        }

        if (waiting) {
          waiting.style.display = "none";
        }

        takePhotoBtn.style.display = "none";
        importBtn.style.display = "none";
        publishBtn.style.display = "none";
        saveBtn.style.display = "inline-block";
        cancelBtn.style.display = "inline-block";

        const img = new Image();
        img.onload = () => {
          canvasPreview.width = img.width;
          canvasPreview.height = img.height;
          canvasPreview
            .getContext("2d")
            .drawImage(img, 0, 0, canvasPreview.width, canvasPreview.height);
        };
        img.src = e.target.result;
      };

      reader.readAsDataURL(uploadedImage);
    }
  });

  filterBtns.forEach((button) => {
    button.addEventListener("click", () => {
      const filterSrc =
        baseUrl + "assets/filters/" + button.getAttribute("data-file");
      captureFilter.style.display = "block";
      captureFilter.src = filterSrc;
      previewFilter.src = filterSrc;
      takePhotoBtn.disabled = false;
      filterBtns.forEach((btn) => {
        btn.classList.remove("selected");
        btn.style.backgroundColor = "transparent";
      });
      button.classList.add("selected");
      button.style.backgroundColor = "#dbdbdb";
      selectedFilter = button.getAttribute("data-id");
    });
  });

  takePhotoBtn.addEventListener("click", () => {
    canvasPreview.width = captureVideo.videoWidth;
    canvasPreview.height = captureVideo.videoHeight;
    canvasPreview
      .getContext("2d")
      .drawImage(captureVideo, 0, 0, canvasPreview.width, canvasPreview.height);

    captureVideo.style.display = "none";
    captureFilter.style.display = "none";
    canvasPreview.style.display = "block";
    previewFilter.style.display = "block";
    filters.style.display = "none";
    if (waiting) {
      waiting.style.display = "none";
    }
    takePhotoBtn.style.display = "none";
    importBtn.style.display = "none";
    publishBtn.style.display = "none";
    saveBtn.style.display = "inline-block";
    cancelBtn.style.display = "inline-block";
  });

  cancelBtn.addEventListener("click", () => {
    startWebcam();
    captureVideo.style.display = "block";
    captureFilter.style.display = "block";
    canvasPreview.style.display = "none";
    previewFilter.style.display = "none";
    filters.style.display = "flex";
    if (waiting) {
      waiting.style.display = "flex";
    }
    takePhotoBtn.style.display = "inline-block";
    importBtn.style.display = "inline-block";
    publishBtn.style.display = "inline-block";
    saveBtn.style.display = "none";
    cancelBtn.style.display = "none";
  });

  saveBtn.addEventListener("click", async () => {
    const image = canvasPreview.toDataURL("image/png");
    const request = await savePhoto(image, selectedFilter);
    if (!request.success) {
      console.error(request.message);
    } else {
      window.location.href = "/create";
    }
  });

  publishBtn.addEventListener("click", async () => {
    const request = await publishPhotos();
    if (!request.success) {
      console.error(request.message);
    } else {
      window.location.href = "/";
    }
  });
});
