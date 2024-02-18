async function publishPhotos() {
  try {
    const url = baseUrl + "controllers/post/publishPhotos.controller.php";
    const response = await fetch(url, {
      method: "POST",
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
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

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const msg = document.getElementById("msg");
  const msgText = document.getElementById("msgText");
  const captureVideo = document.getElementById("captureVideo");
  const captureFilter = document.getElementById("captureFilter");
  const canvasPreview = document.getElementById("photoPreview");
  const importPreview = document.getElementById("importPreview");
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
  let uploadedImageData = null;

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

  const importFile = (e) => {
    const uploadedImage = e.target.files[0];

    if (uploadedImage) {
      const reader = new FileReader();

      reader.onload = () => {
        uploadedImageData = reader.result;

        captureVideo.style.display = "none";
        captureFilter.style.display = "none";
        stopWebcam();

        importPreview.style.display = "block";
        importPreview.src = uploadedImageData;
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
      };

      reader.readAsDataURL(uploadedImage);
    }

    e.currentTarget.value = null;
  };

  const filterBtnClick = (btn) => {
    const filterSrc =
      baseUrl + "assets/filters/" + btn.getAttribute("data-file");

    captureFilter.style.display = "block";
    captureFilter.src = filterSrc;
    previewFilter.src = filterSrc;
    takePhotoBtn.disabled = false;
    filterBtns.forEach((btn) => {
      btn.classList.remove("selected");
      btn.style.backgroundColor = "transparent";
    });
    btn.classList.add("selected");
    btn.style.backgroundColor = "#dbdbdb";
    selectedFilter = btn.getAttribute("data-id");
  };

  const takePhotoBtnClick = () => {
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
  };

  const cancelBtnClick = () => {
    msg.style.display = "none";

    startWebcam();
    captureVideo.style.display = "block";
    captureFilter.style.display = "block";

    canvasPreview.style.display = "none";
    importPreview.style.display = "none";
    importPreview.src = "";
    uploadedImageData = null;
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
  };

  const saveBtnClick = async () => {
    const image = uploadedImageData || canvasPreview.toDataURL("image/png");

    const req = await savePhoto(image, selectedFilter);
    if (!req.success) {
      msg.style.display = "block";
      msgText.textContent = req.message;
    } else {
      window.location.href = "/create";
    }
  };

  const publishBtnClick = async () => {
    const req = await publishPhotos();
    if (!req.success) {
      msg.style.display = "block";
      msgText.textContent = req.message;
    } else {
      window.location.href = "/";
    }
  };

  startWebcam();
  importInput.addEventListener("change", (e) => importFile(e));
  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => filterBtnClick(btn));
  });
  takePhotoBtn.addEventListener("click", takePhotoBtnClick);
  cancelBtn.addEventListener("click", cancelBtnClick);
  saveBtn.addEventListener("click", saveBtnClick);
  publishBtn.addEventListener("click", publishBtnClick);
});
