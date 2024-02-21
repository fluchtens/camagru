function captureDisplay(display) {
  const captureVideo = document.getElementById("captureVideo");
  const captureFilter = document.getElementById("captureFilter");

  if (display) {
    captureVideo.style.display = "block";
    captureFilter.style.display = "block";
  } else {
    captureVideo.style.display = "none";
    captureFilter.style.display = "none";
  }
}

function previewDisplay(display) {
  const canvasPreview = document.getElementById("photoPreview");
  const importPreview = document.getElementById("importPreview");
  const previewFilter = document.getElementById("previewFilter");

  if (display) {
    canvasPreview.style.display = "block";
    previewFilter.style.display = "block";
  } else {
    canvasPreview.style.display = "none";
    importPreview.style.display = "none";
    importPreview.src = "";
    previewFilter.style.display = "none";
  }
}

function importPreviewDisplay(uploadedImageData) {
  const importPreview = document.getElementById("importPreview");
  const previewFilter = document.getElementById("previewFilter");

  importPreview.style.display = "block";
  importPreview.src = uploadedImageData;
  previewFilter.style.display = "block";
}

function filtersDisplay(display) {
  const filters = document.getElementById("filters");

  if (display) {
    filters.style.display = "flex";
  } else {
    filters.style.display = "none";
  }
}

function waitingDisplay(display) {
  const waiting = document.getElementById("waiting");

  if (display) {
    waiting.style.display = "flex";
  } else {
    waiting.style.display = "none";
  }
}

function captureBtnDisplay(display) {
  const takePhotoBtn = document.getElementById("takePhotoBtn");
  const importBtn = document.getElementById("importBtn");
  const publishBtn = document.getElementById("publishPhotoBtn");

  if (display) {
    takePhotoBtn.style.display = "inline-block";
    importBtn.style.display = "inline-block";
    publishBtn.style.display = "inline-block";
  } else {
    takePhotoBtn.style.display = "none";
    importBtn.style.display = "none";
    publishBtn.style.display = "none";
  }
}

function previewBtnDisplay(display) {
  const cancelBtn = document.getElementById("cancelBtn");
  const saveBtn = document.getElementById("saveBtn");

  if (display) {
    saveBtn.style.display = "inline-block";
    cancelBtn.style.display = "inline-block";
  } else {
    cancelBtn.style.display = "none";
    saveBtn.style.display = "none";
  }
}

async function updateWaitingPosts() {
  const waiting = document.getElementById("waiting");
  waiting.innerHTML = "";

  const posts = await getWaitingPosts();
  if (!posts) {
    waiting.style.display = "none";
  } else {
    waiting.style.display = "flex";
    posts.forEach((post) => {
      const img = document.createElement("img");
      img.src = baseUrl + "assets/uploads/posts/" + post.file;
      img.alt = post.file;
      waiting.appendChild(img);
    });
  }
}

document.addEventListener("DOMContentLoaded", async () => {
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
        captureDisplay(false);
        stopWebcam();
        importPreviewDisplay(uploadedImageData);

        const selectedFilterBtn = document.querySelector(".filterBtn.selected");
        if (!selectedFilterBtn) {
          const firstBtn = document.querySelector(".filterBtn[data-id='1']");
          if (firstBtn) {
            firstBtn.click();
          }
        }

        waitingDisplay(false);
        captureBtnDisplay(false);
        previewBtnDisplay(true);
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

    captureDisplay(false);
    previewDisplay(true);
    filtersDisplay(false);
    waitingDisplay(false);
    captureBtnDisplay(false);
    previewBtnDisplay(true);
  };

  const cancelBtnClick = async () => {
    msg.style.display = "none";
    startWebcam();
    captureDisplay(true);
    previewDisplay(false);
    uploadedImageData = null;
    filtersDisplay(true);
    await updateWaitingPosts();
    captureBtnDisplay(true);
    previewBtnDisplay(false);
  };

  const saveBtnClick = async () => {
    const image = uploadedImageData || canvasPreview.toDataURL("image/png");

    const req = await savePhoto(image, selectedFilter);
    if (!req.success) {
      msg.style.display = "block";
      msgText.textContent = req.message;
    } else {
      startWebcam();
      captureDisplay(true);
      previewDisplay(false);
      uploadedImageData = null;
      filtersDisplay(true);
      await updateWaitingPosts();
      captureBtnDisplay(true);
      previewBtnDisplay(false);
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
  await updateWaitingPosts();
  importInput.addEventListener("change", (e) => importFile(e));
  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => filterBtnClick(btn));
  });
  takePhotoBtn.addEventListener("click", takePhotoBtnClick);
  cancelBtn.addEventListener("click", cancelBtnClick);
  saveBtn.addEventListener("click", saveBtnClick);
  publishBtn.addEventListener("click", publishBtnClick);
});
