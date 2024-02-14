async function getPost(id) {
  try {
    const url = `${baseUrl}controllers/post/getPost.controller.php?id=${id}`;
    const response = await fetch(url, {
      method: "GET",
    });

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, post: data.post };
  } catch (error) {
    console.error("An error occurred:", error);
    return { success: false, message: error.message };
  }
}

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

function createPost(data) {
  const post = document.getElementById("post");

  const createUser = () => {
    const user = document.createElement("div");
    user.classList.add("user");

    const infos = document.createElement("a");
    infos.href = `/${data.user_username}`;
    infos.classList.add("infos");

    const avatar = document.createElement("img");
    if (data.user_avatar) {
      avatar.src = `${baseUrl}assets/uploads/avatars/${data.user_avatar}`;
      avatar.alt = data.user_avatar;
    } else {
      avatar.src = `${baseUrl}assets/noavatar.png`;
      avatar.alt = "noavatar.png";
    }

    infos.appendChild(avatar);

    const texts = document.createElement("div");
    texts.classList.add("texts");

    const username = document.createElement("p");
    username.classList.add("username");
    username.textContent = data.user_username;

    const timeDiff = document.createElement("span");
    timeDiff.classList.add("time-diff");
    timeDiff.textContent = `• ${formatElapsedTime(data.time_diff)}`;

    texts.appendChild(username);
    texts.appendChild(timeDiff);
    infos.appendChild(texts);
    user.appendChild(infos);

    const deleteBtn = document.createElement("button");
    deleteBtn.classList.add("deleteBtn");

    const deleteIcon = document.createElement("img");
    deleteIcon.src = baseUrl + "assets/deleteIcon.png";

    deleteBtn.appendChild(deleteIcon);
    user.appendChild(deleteBtn);

    return user;
  };

  const user = createUser();

  const image = document.createElement("img");
  image.src = `${baseUrl}assets/uploads/posts/${data.file}`;
  image.alt = data.file;

  const actions = document.createElement("div");
  actions.classList.add("actions");

  const buttons = document.createElement("div");
  buttons.classList.add("buttons");

  if (data.liked) {
    const unlikeBtn = document.createElement("button");
    unlikeBtn.classList.add("unlikeBtn");
    unlikeBtn.setAttribute("data-post-id", data.id);

    const unlikeImg = document.createElement("img");
    unlikeImg.src = baseUrl + "assets/unlikeBtn.svg";

    unlikeBtn.appendChild(unlikeImg);
    buttons.appendChild(unlikeBtn);
  } else {
    const likeBtn = document.createElement("button");
    likeBtn.classList.add("likeBtn");
    likeBtn.setAttribute("data-post-id", data.id);

    const likeImg = document.createElement("img");
    likeImg.src = baseUrl + "assets/likeBtn.svg";

    likeBtn.appendChild(likeImg);
    buttons.appendChild(likeBtn);
  }

  const commentLink = document.createElement("a");
  commentLink.href = `/c/${data.id}`;
  commentLink.classList.add("comment");

  const commentImg = document.createElement("img");
  commentImg.src = baseUrl + "assets/commentBtn.svg";

  commentLink.appendChild(commentImg);
  buttons.appendChild(commentLink);

  actions.appendChild(buttons);

  const likeCount = document.createElement("p");
  likeCount.classList.add("likeCount");
  likeCount.textContent = `${data.like_count} likes`;

  const commentCount = document.createElement("a");
  commentCount.href = `/c/${data.id}`;
  commentCount.classList.add("commentCount");
  if (data.comment_count) {
    commentCount.textContent = `View all ${data.comment_count} comments`;
  } else {
    commentCount.textContent = "Add a comment..";
  }

  actions.appendChild(likeCount);
  actions.appendChild(commentCount);

  post.appendChild(user);
  post.appendChild(image);
  post.appendChild(actions);
}

document.addEventListener("DOMContentLoaded", async () => {
  const post = document.getElementById("post");
  const postId = post.dataset.postId;

  const req = await getPost(postId);
  if (req.success) {
    createPost(req.post);
  }

  post.addEventListener("click", async (e) => {
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
});
