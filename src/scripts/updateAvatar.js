const form = document.getElementById("profileSettings");
form.addEventListener("submit", async (event) => {
  event.preventDefault();
  updateAvatar();
});

async function updateAvatar() {
  try {
    const avatarInput = document.getElementById("avatar");
    const avatarData = avatarInput.toDataURL("image/png");
    console.log(avatarData);

    // const response = await fetch("controllers/updateAvatar.php", {
    //   method: "POST",
    //   body: formData,
    // });

    // if (!response.ok) {
    //   throw new Error(`HTTP error! Status: ${response.status}`);
    // }

    // const data = await response.text();
    // console.log("Image saved on server:", data);
  } catch (error) {
    console.error("Error capturing or saving image:", error);
  }
}
