async function likePost(postId) {
  try {
    const url = baseUrl + "controllers/post/likePost.controller.php";
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ post_id: postId }),
    });

    const data = await response.json();
    if (!response.ok) {
      return { success: false, code: data.code, message: data.message };
    }

    return { success: true, code: data.code, message: data.message };
  } catch (error) {
    console.error("An error occurred:", error);
    return { success: false, code: data.code, message: error.message };
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const home = document.getElementById("home");

  if (home) {
    home.addEventListener("click", async (e) => {
      const target = e.target;

      const likeBtn = target.closest(".likeBtn");
      const unlikeBtn = target.closest(".unlikeBtn");

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
          const likeCount = btn.closest(".actions").querySelector(".likeCount");
          const currentLikes = parseInt(likeCount.textContent);

          if (likeBtn) {
            img.src = baseUrl + "assets/unlikeBtn.svg";
            likeBtn.classList.remove("likeBtn");
            likeBtn.classList.add("unlikeBtn");
            likeCount.textContent = currentLikes + 1 + " likes";
          } else {
            img.src = baseUrl + "assets/likeBtn.svg";
            unlikeBtn.classList.remove("unlikeBtn");
            unlikeBtn.classList.add("likeBtn");
            likeCount.textContent = currentLikes - 1 + " likes";
          }
        }
      }
    });
  }
});
