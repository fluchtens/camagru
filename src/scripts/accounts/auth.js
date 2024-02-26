async function signupFormManager(e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const signupBtn = document.getElementById("signupBtn");
  const msg = document.getElementById("signupMsg");
  const msgText = document.getElementById("signupMsgText");

  signupBtn.disabled = true;
  const req = await signup(formData);
  signupBtn.disabled = false;
  msg.style.display = "block";
  msgText.textContent = req.message;
  if (!req.success) {
    msg.style.background = "#ffebe8";
    msg.style.border = "1px solid #ffc1bf";
  } else {
    msg.style.background = "#e8f8e8";
    msg.style.border = "1px solid #b1eab5";
  }
}

async function loginFormManager(e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const msg = document.getElementById("loginMsg");
  const msgText = document.getElementById("loginMsgText");

  const req = await login(formData);
  if (!req.success) {
    msg.style.display = "block";
    msgText.textContent = req.message;
    msg.style.background = "#ffebe8";
    msg.style.border = "1px solid #ffc1bf";
  } else {
    window.location.href = "/";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("registerForm");
  const loginForm = document.getElementById("loginForm");

  if (registerForm) {
    registerForm.addEventListener("submit", signupFormManager);
  }

  if (loginForm) {
    loginForm.addEventListener("submit", loginFormManager);
  }
});
