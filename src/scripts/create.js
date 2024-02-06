async function publishPhotos() {
  try {
    const url = baseUrl + "controllers/publishPhotos.controller.php";
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
    const url = baseUrl + "controllers/takePhoto.controller.php";
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
  const publishBtn = document.getElementById("publishPhotoBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  const saveBtn = document.getElementById("saveBtn");
  let selectedFilter = "fire";

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

  startWebcam();

  filterBtns.forEach((button) => {
    button.addEventListener("click", () => {
      const filterSrc = "assets/filters/" + button.getAttribute("data-file");
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
    waiting.style.display = "none";
    takePhotoBtn.style.display = "none";
    publishBtn.style.display = "none";
    saveBtn.style.display = "inline-block";
    cancelBtn.style.display = "inline-block";
  });

  cancelBtn.addEventListener("click", () => {
    captureVideo.style.display = "block";
    captureFilter.style.display = "block";
    canvasPreview.style.display = "none";
    previewFilter.style.display = "none";
    filters.style.display = "flex";
    waiting.style.display = "flex";
    takePhotoBtn.style.display = "inline-block";
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
