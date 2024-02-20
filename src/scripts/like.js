document.addEventListener("DOMContentLoaded", () => {
  const home = document.getElementById("home");

  home.addEventListener("click", async (e) => {
    const target = e.target;

    const likeBtn = target.closest(".like-btn");
    const unlikeBtn = target.closest(".unlike-btn");

    if (likeBtn || unlikeBtn) {
      const btn = likeBtn || unlikeBtn;
      const postId = btn.getAttribute("data-post-id");

      const req = await likePost(postId);
      if (!req.success) {
        if (req.code === 401) {
          window.location.href = "/accounts/login";
        }
      } else {
        const img = btn.querySelector("img");
        const likeCount = btn.closest(".actions").querySelector(".like-count");
        const currentLikes = parseInt(likeCount.textContent);

        if (likeBtn) {
          img.src = baseUrl + "assets/unlikeBtn.svg";
          likeBtn.classList.remove("like-btn");
          likeBtn.classList.add("unlike-btn");
          likeCount.textContent = currentLikes + 1 + " likes";
        } else {
          img.src = baseUrl + "assets/likeBtn.svg";
          unlikeBtn.classList.remove("unlike-btn");
          unlikeBtn.classList.add("like-btn");
          likeCount.textContent = currentLikes - 1 + " likes";
        }
      }
    }
  });
});
