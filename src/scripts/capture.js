document.addEventListener("DOMContentLoaded", (event) => {
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

    try {
      const imageDataURL = canvas.toDataURL("image/png");

      const response = await fetch("controllers/createPost.controller.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ image: imageDataURL }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.text();
      console.log("Image saved on server:", data);
      window.location.href = "/";
    } catch (error) {
      console.error("Error capturing or saving image:", error);
    }
  });
});
