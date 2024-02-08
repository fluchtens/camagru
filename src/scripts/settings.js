async function updateProfile(formData) {
  try {
    const url = baseUrl + "controllers/updateProfile.controller.php";
    const response = await fetch(url, {
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
    const url = baseUrl + "controllers/updateAvatar.controller.php";
    const response = await fetch(url, {
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

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("editProfileForm");
  const msg = document.getElementById("msg");
  const msgText = document.getElementById("msgText");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);

    const profileRes = await updateProfile(formData);
    if (!profileRes.success) {
      msg.style.display = "block";
      msgText.textContent = profileRes.message;
    } else {
      msg.style.display = "none";
    }

    const avatarFile = formData.get("avatarToUpload");
    if (avatarFile && avatarFile.size > 0) {
      const avatarRes = await updateAvatar(formData);
      if (!avatarRes.success) {
        msg.style.display = "block";
        msgText.textContent = avatarRes.message;
      } else {
        msg.style.display = "none";
      }
    }
  });
});
