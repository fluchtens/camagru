async function commentPost(postId, comment) {
  try {
    const postData = { post_id: postId, comment: comment };

    const response = await fetch("../controllers/commentPost.controller.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(postData),
    });

    const data = await response.text();
    console.log(data);
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    console.error("An error occurred:", error);
    return { success: false, message: error.message };
  }
}

async function getPostComments(postId) {
  try {
    const url = baseUrl + "controllers/getPostComments.controller.php";
    const response = await fetch(`${url}?post_id=${postId}`, {
      method: "GET",
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

async function loadPostComments(postId) {
  const formatElapsedTime = (timeDiff) => {
    const timeComponents = timeDiff.split(":");
    const hours = parseInt(timeComponents[0], 10);
    const minutes = parseInt(timeComponents[1], 10);
    const seconds = parseInt(timeComponents[2], 10);

    const weeks = Math.floor(hours / 24 / 7);
    const days = Math.floor(hours / 24) % 7;

    if (weeks > 0) {
      return weeks + "w";
    } else if (days > 0) {
      return days + "d";
    } else if (hours > 0) {
      return hours + "h";
    } else if (minutes > 0) {
      return minutes + "min";
    } else if (seconds > 0) {
      return seconds + "s";
    } else {
      return "Now";
    }
  };

  const req = await getPostComments(postId);
  if (req.success) {
    const commentsContainer = document.getElementById("comments");
    commentsContainer.innerHTML = "";

    req.message.forEach((comment) => {
      const commentElement = document.createElement("div");
      commentElement.classList.add("comment");
      commentElement.innerHTML = `
      <img src="${
        comment.user_avatar
          ? baseUrl + "assets/uploads/avatars/" + comment.user_avatar
          : baseUrl + "assets/noavatar.png"
      }" alt="avatar">
        <div class="texts">
            <div class="title">
                <span class="username">${comment.user_username}</span>
                <span class="time-diff">• ${formatElapsedTime(
                  comment.time_diff
                )}</span>
            </div>
            <p class="message">${comment.comment}</p>
        </div>
      `;
      commentsContainer.appendChild(commentElement);
    });
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  const modal = document.getElementById("commentsModal");
  const closeBtn = document.getElementById("closeCommentsModalBtn");
  const form = document.getElementById("commentForm");
  const postId = modal.getAttribute("data-post-id");

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
    window.history.back();
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const comment = formData.get("comment");

    const req = await commentPost(postId, comment);
    if (req.success) {
      await loadPostComments(postId);
    }
    form.reset();
  });
});
