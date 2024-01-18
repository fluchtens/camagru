async function createPost(imageDataURL, filter) {
  try {
    const response = await fetch("controllers/createPost.controller.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ image: imageDataURL, filter: filter }),
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
  const takePhotoBtn = document.getElementById("takePhotoBtn");
  const canvasPreview = document.getElementById("photoPreview");
  const previewFilter = document.getElementById("previewFilter");
  const cancelBtn = document.getElementById("cancelBtn");
  const submitBtn = document.getElementById("submitBtn");
  const filterButtons = document.querySelectorAll(".filterBtn");
  let selectedFilter = "fire";

  const displayTakeContainer = () => {
    captureVideo.style.display = "block";
    captureFilter.style.display = "block";
    takePhotoBtn.style.display = "inline-block";
  };

  const hideTakeContainer = () => {
    captureVideo.style.display = "none";
    captureFilter.style.display = "none";
    takePhotoBtn.style.display = "none";
  };

  const displayPreviewContainer = () => {
    canvasPreview.style.display = "block";
    previewFilter.style.display = "block";
    submitBtn.style.display = "inline-block";
    cancelBtn.style.display = "inline-block";
  };

  const hidePreviewContainer = () => {
    canvasPreview.style.display = "none";
    previewFilter.style.display = "none";
    submitBtn.style.display = "none";
    cancelBtn.style.display = "none";
  };

  filterButtons.forEach((button) => {
    button.addEventListener("click", () => {
      filterButtons.forEach((btn) => btn.classList.remove("selected"));
      button.classList.add("selected");
      selectedFilter = button.getAttribute("data-name");
    });
  });

  const constraints = {
    audio: false,
    video: {
      width: { min: 468, max: 468 },
      height: { min: 585, max: 585 },
    },
  };

  navigator.mediaDevices
    .getUserMedia(constraints)
    .then((stream) => {
      captureVideo.srcObject = stream;
    })
    .catch((error) => {
      console.error("Error accessing webcam:", error);
    });

  takePhotoBtn.addEventListener("click", () => {
    canvasPreview.width = captureVideo.videoWidth;
    canvasPreview.height = captureVideo.videoHeight;
    canvasPreview
      .getContext("2d")
      .drawImage(captureVideo, 0, 0, canvasPreview.width, canvasPreview.height);

    hideTakeContainer();
    displayPreviewContainer();
  });

  cancelBtn.addEventListener("click", () => {
    displayTakeContainer();
    hidePreviewContainer();
  });

  submitBtn.addEventListener("click", async () => {
    const imageDataURL = canvasPreview.toDataURL("image/png");

    const res = await createPost(imageDataURL, selectedFilter);
    if (!res.success) {
      console.error(res.message);
    } else {
      window.location.href = "/";
    }
  });
});
