document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("forgotPasswordForm");
  const emailInput = document.getElementById("emailInput");
  const submitBtn = document.getElementById("submitBtn");
  const msg = document.getElementById("msg");
  const msgText = document.getElementById("msgText");

  emailInput.addEventListener("input", () => {
    if (emailInput.validity.valid) {
      submitBtn.removeAttribute("disabled");
    } else {
      submitBtn.setAttribute("disabled", true);
    }
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const req = await sendResetPasswordRequest(formData);
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
