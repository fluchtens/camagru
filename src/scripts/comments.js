async function commentPost(postId, comment) {
  try {
    const postData = { post_id: postId, comment: comment };

    const response = await fetch("../controllers/commentPost.controller.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(postData),
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
  const modal = document.getElementById("commentsModal");
  const closeBtn = document.getElementById("closeCommentsModalBtn");
  const form = document.getElementById("commentForm");

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
    window.history.back();
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const postId = form.getAttribute("data-post-id");
    const formData = new FormData(e.target);
    const comment = formData.get("comment");

    const res = await commentPost(postId, comment);
    console.log(res);
  });
});
