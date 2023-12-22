document.addEventListener("DOMContentLoaded", (event) => {
  const video = document.getElementById("captureCamera");
  const button = document.getElementById("captureBtn");

  navigator.mediaDevices
    .getUserMedia({ video: true })
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

      const response = await fetch("posts/save_picture.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ image: imageDataURL }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();
      console.log("Image saved on server:", data);
    } catch (error) {
      console.error("Error capturing or saving image:", error);
    }
  });
});
