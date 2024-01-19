async function registerUser(formData) {
  try {
    const response = await fetch("controllers/registerUser.controller.php", {
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
  .getElementById("registerForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    const request = await registerUser(formData);
    if (!request.success) {
      document.getElementById("registerErrMsg").style.display = "block";
      document.getElementById("registerErrMsgText").textContent =
        request.message;
    } else {
      window.location.href = "/login";
    }
  });
