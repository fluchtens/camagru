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

    const req = await editProfile(formData);
    editProfileMsg.style.display = "block";
    editProfileMsgText.textContent = req.message;
    if (!req.success) {
      editProfileMsg.style.background = "#ffebe8";
      editProfileMsg.style.border = "1px solid #ffc1bf";
    } else {
      editProfileMsg.style.background = "#e8f8e8";
      editProfileMsg.style.border = "1px solid #b1eab5";
    }

    const updatedUser = await getUser();
    if (updatedUser) {
      const fullNameInput = document.querySelector("input[name='fullname']");
      const usernameInput = document.querySelector("input[name='username']");
      const bioInput = document.querySelector("input[name='bio']");
      const enableNotifs = document.querySelector('input[value="enable"]');
      const disableNotifs = document.querySelector('input[value="disable"]');

      fullNameInput.value = updatedUser.full_name;
      usernameInput.value = updatedUser.username;
      bioInput.value = updatedUser.bio;
      if (updatedUser.email_notifs === 1) {
        enableNotifs.checked = true;
        disableNotifs.checked = false;
      } else {
        enableNotifs.checked = false;
        disableNotifs.checked = true;
      }

      const headerProfileLink = document.getElementById("headerProfileLink");
      const headerAvatarImg = document.getElementById("headerAvatarImg");

      headerProfileLink.setAttribute("href", "/" + updatedUser.username);
      if (updatedUser.avatar) {
        const avatar = updatedUser.avatar;
        headerAvatarImg.src = baseUrl + "assets/uploads/avatars/" + avatar;
      } else {
        headerAvatarImg.src = baseUrl + "assets/noavatar.png";
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
