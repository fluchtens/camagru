async function submitData(controller, formData) {
  try {
    const url = baseUrl + "controllers/auth/" + controller;
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
  const registerForm = document.getElementById("registerForm");
  const loginForm = document.getElementById("loginForm");
  const registerErrMsg = document.getElementById("registerErrMsg");
  const registerErrMsgText = document.getElementById("registerErrMsgText");

  if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      const req = await submitData("signup.controller.php", formData);
      registerErrMsg.style.display = "block";
      registerErrMsgText.textContent = req.message;
      if (!req.success) {
        registerErrMsg.style.background = "#ffebe8";
        registerErrMsg.style.border = "1px solid #ffc1bf";
      } else {
        registerErrMsg.style.background = "#e8f8e8";
        registerErrMsg.style.border = "1px solid #b1eab5";
      }
    });
  }

  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      const req = await submitData("login.controller.php", formData);
      if (!req.success) {
        document.getElementById("loginErrMsg").style.display = "block";
        document.getElementById("loginErrMsgText").textContent = req.message;
      } else {
        window.location.href = "/";
      }
    });
  }
});
