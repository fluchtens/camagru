async function loginUser(formData) {
  try {
    const response = await fetch("controllers/loginUser.controller.php", {
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

document.getElementById("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);

  const request = await loginUser(formData);
  if (!request.success) {
    document.getElementById("loginErrMsg").style.display = "block";
    document.getElementById("loginErrMsgText").textContent = request.message;
  } else {
    window.location.href = "/";
  }
});
