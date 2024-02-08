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

async function updatePassword(formData) {
  try {
    const url = baseUrl + "controllers/account/editPassword.controller.php";
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
  const editProfileForm = document.getElementById("editProfileForm");
  const editProfileMsg = document.getElementById("editProfileMsg");
  const editProfileMsgText = document.getElementById("editProfileMsgText");
  const editPasswordForm = document.getElementById("editPasswordForm");
  const editPasswordMsg = document.getElementById("editPasswordMsg");
  const editPasswordMsgText = document.getElementById("editPasswordMsgText");

  editProfileForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    const profileRes = await updateProfile(formData);
    if (!profileRes.success) {
      editProfileMsg.style.display = "block";
      editProfileMsgText.textContent = profileRes.message;
    } else {
      editProfileMsg.style.display = "none";
    }

    const avatarFile = formData.get("avatarToUpload");
    if (avatarFile && avatarFile.size > 0) {
      const avatarRes = await updateAvatar(formData);
      if (!avatarRes.success) {
        editProfileMsg.style.display = "block";
        editProfileMsgText.textContent = avatarRes.message;
      } else {
        editProfileMsg.style.display = "none";
      }
    }
  });

  editPasswordForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const req = await updatePassword(formData);
    editPasswordMsg.style.display = "block";
    editPasswordMsgText.textContent = req.message;
    if (!req.success) {
      editPasswordMsg.style.background = "#ffebe8";
      editPasswordMsg.style.border = "1px solid #ffc1bf";
    } else {
      editPasswordMsg.style.background = "#e8f8e8";
      editPasswordMsg.style.border = "1px solid #b1eab5";
    }
  });
});
