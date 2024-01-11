async function sendPicture(imageDataURL) {
  try {
    const response = await fetch("controllers/createPost.controller.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ image: imageDataURL }),
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
  const video = document.getElementById("captureCamera");
  const button = document.getElementById("captureBtn");

  const constraints = {
    audio: false,
    video: true,
  };

  navigator.mediaDevices
    .getUserMedia(constraints)
    .then((stream) => {
      video.srcObject = stream;
    })
    .catch((error) => {
      console.error("Error accessing webcam:", error);
    });

  button.addEventListener("click", async () => {
    const canvas = document.createElement("canvas");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext("2d").drawImage(video, 0, 0, canvas.width, canvas.height);

    const imageDataURL = canvas.toDataURL("image/png");

    const res = await sendPicture(imageDataURL);
    if (!res.success) {
      console.error(res.message);
    } else {
      window.location.href = "/";
    }
  });
});
