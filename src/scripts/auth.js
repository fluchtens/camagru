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

  if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      const request = await submitData("registerUser.controller.php", formData);
      if (!request.success) {
        document.getElementById("registerErrMsg").style.display = "block";
        document.getElementById("registerErrMsgText").textContent =
          request.message;
      } else {
        window.location.href = "/accounts/login";
      }
    });
  }

  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      const req = await submitData("loginUser.controller.php", formData);
      if (!req.success) {
        document.getElementById("loginErrMsg").style.display = "block";
        document.getElementById("loginErrMsgText").textContent = req.message;
      } else {
        window.location.href = "/";
      }
    });
  }
});
