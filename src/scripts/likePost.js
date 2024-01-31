async function likePost(postId) {
  try {
    const postData = { post_id: postId };

    const response = await fetch("controllers/likePost.controller.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(postData),
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
  const likeBtns = document.querySelectorAll(".likeBtn, .unlikeBtn");

  likeBtns.forEach((btn) => {
    btn.addEventListener("click", async () => {
      const postId = btn.getAttribute("data-post-id");

      const request = await likePost(postId);
      if (request.success) {
        const svg = btn.querySelector("svg");
        const path = btn.querySelector("path");
        const likeCount = btn.closest(".actions").querySelector(".likeCount");
        const currentLikes = parseInt(likeCount.textContent);

        if (btn.classList.contains("likeBtn")) {
          svg.setAttribute("viewBox", "0 0 48 48");
          path.setAttribute(
            "d",
            "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"
          );
          btn.classList.remove("likeBtn");
          btn.classList.add("unlikeBtn");
          likeCount.textContent = currentLikes + 1 + " likes";
        } else {
          svg.setAttribute("viewBox", "0 0 24 24");
          path.setAttribute(
            "d",
            "M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.072-2.652 4.959-5.197 7.222-2.512 2.243-3.865 3.469-4.303 3.752-.477-.309-2.143-1.823-4.303-3.752C5.141 14.072 2.5 12.167 2.5 9.122a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 5.827 5.015 7.97.283.246.569.494.853.747l1.027.918a44.998 44.998 0 0 0 3.518 3.018 2 2 0 0 0 2.174 0 45.263 45.263 0 0 0 3.626-3.115l.922-.824c.293-.26.59-.519.885-.774 2.334-2.025 4.98-4.32 4.98-7.94a6.985 6.985 0 0 0-6.708-7.218Z"
          );
          btn.classList.remove("unlikeBtn");
          btn.classList.add("likeBtn");
          likeCount.textContent = currentLikes - 1 + " likes";
        }
      } else {
        if (request.code === 401) {
          window.location.href = "/login";
        }
      }
    });
  });
});
