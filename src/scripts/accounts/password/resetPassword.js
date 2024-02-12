async function resetPassword(formData) {
  try {
    const url = baseUrl + "controllers/account/resetPassword.controller.php";
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
  const form = document.getElementById("resetPasswordForm");
  const msg = document.getElementById("msg");
  const msgText = document.getElementById("msgText");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get("token");
    formData.append("token", token);

    const req = await resetPassword(formData);
    msg.style.display = "block";
    msgText.textContent = req.message;
    if (!req.success) {
      msg.style.background = "#ffebe8";
      msg.style.border = "1px solid #ffc1bf";
    } else {
      msg.style.background = "#e8f8e8";
      msg.style.border = "1px solid #b1eab5";
    }
  });
});
