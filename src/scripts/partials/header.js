document.addEventListener("DOMContentLoaded", () => {
  const mobileBtn = document.getElementById("mobile-menu-btn");
  const authLinks = document.getElementById("auth-links");

  window.addEventListener("resize", () => {
    const windowWidth = window.innerWidth;
    authLinks.style.visibility = windowWidth >= 768 ? "visible" : "hidden";
  });

  mobileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    const computedStyle = window.getComputedStyle(authLinks);
    const visibility = computedStyle.getPropertyValue("visibility");
    authLinks.style.visibility = visibility === "hidden" ? "visible" : "hidden";
  });

  document.addEventListener("click", (e) => {
    const windowWidth = window.innerWidth;
    if (windowWidth < 768) {
      const isClickInsideMenu = authLinks.contains(e.target);
      if (!isClickInsideMenu) {
        authLinks.style.visibility = "hidden";
      }
    }
  });
});
