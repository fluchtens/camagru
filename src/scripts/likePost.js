async function likePost(formData) {
  try {
    const response = await fetch("controllers/likePost.controller.php", {
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
  const likeBtn = document.getElementById("like-btn");

  likeBtn.addEventListener("click", async () => {
    const postId = likeBtn.getAttribute("data-post-id");
    const formData = new FormData();
    formData.append("post_id", postId);

    const request = await likePost(formData);
    console.log(request);
  });
});
