async function updateProfile(formData) {
  try {
    const response = await fetch("controllers/updateProfile.controller.php", {
      method: "POST",
      body: formData,
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

async function updateAvatar(formData) {
  try {
    const response = await fetch("controllers/updateAvatar.controller.php", {
      method: "POST",
      body: formData,
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

document
  .getElementById("settingsForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    const profileRes = await updateProfile(formData);
    if (!profileRes.success) {
      document.getElementById("errMsg").style.display = "block";
      document.getElementById("errMsgText").textContent = profileRes.message;
    } else {
      document.getElementById("errMsg").style.display = "none";
    }

    const avatarFile = formData.get("avatarToUpload");
    if (avatarFile && avatarFile.size > 0) {
      const avatarRes = await updateAvatar(formData);
      if (!avatarRes.success) {
        document.getElementById("errMsg").style.display = "block";
        document.getElementById("errMsgText").textContent = avatarRes.message;
      } else {
        document.getElementById("errMsg").style.display = "none";
      }
    }
  });
