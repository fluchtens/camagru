function createPostHeader(post, data) {
  const user = document.createElement("div");
  user.className = "user";

  const infos = document.createElement("a");
  infos.href = `/${data.user_username}`;
  infos.className = "infos";

  const avatar = document.createElement("img");
  if (data.user_avatar) {
    avatar.src = `${baseUrl}assets/uploads/avatars/${data.user_avatar}`;
    avatar.alt = data.user_avatar;
  } else {
    avatar.src = `${baseUrl}assets/noavatar.png`;
    avatar.alt = "noavatar.png";
  }

  const texts = document.createElement("div");
  texts.className = "texts";

  const username = document.createElement("p");
  username.className = "username";
  username.textContent = data.user_username;

  const timeDiff = document.createElement("span");
  timeDiff.className = "time-diff";
  timeDiff.textContent = `• ${formatElapsedTime(data.time_diff)}`;

  infos.appendChild(avatar);
  texts.appendChild(username);
  texts.appendChild(timeDiff);
  infos.appendChild(texts);
  user.appendChild(infos);

  if (data.deletable) {
    const deleteBtn = document.createElement("button");
    deleteBtn.className = "delete-btn";
    deleteBtn.onclick = () => deletePost(data.id);

    const deleteIcon = document.createElement("img");
    deleteIcon.src = baseUrl + "assets/deleteIcon.png";

    deleteBtn.appendChild(deleteIcon);
    user.appendChild(deleteBtn);
  }

  post.appendChild(user);
}

function createPostImage(post, data) {
  const image = document.createElement("img");
  image.src = `${baseUrl}assets/uploads/posts/${data.file}`;
  image.alt = data.file;
  post.appendChild(image);
}

function createPostActions(post, data) {
  const actions = document.createElement("div");
  actions.className = "actions";

  const buttons = document.createElement("div");
  buttons.className = "buttons";

  if (data.liked) {
    const unlikeBtn = document.createElement("button");
    unlikeBtn.className = "unlike-btn";
    unlikeBtn.setAttribute("data-post-id", data.id);

    const unlikeImg = document.createElement("img");
    unlikeImg.src = baseUrl + "assets/unlikeBtn.svg";
    unlikeBtn.appendChild(unlikeImg);

    buttons.appendChild(unlikeBtn);
  } else {
    const likeBtn = document.createElement("button");
    likeBtn.className = "like-btn";
    likeBtn.setAttribute("data-post-id", data.id);

    const likeImg = document.createElement("img");
    likeImg.src = baseUrl + "assets/likeBtn.svg";
    likeBtn.appendChild(likeImg);

    buttons.appendChild(likeBtn);
  }

  const commentBtn = document.createElement("button");
  commentBtn.className = "comment";
  commentBtn.onclick = async () => {
    const authUser = await getUser();
    if (!authUser) {
      window.location.href = "/accounts/login";
    } else {
      displayComments(data.id);
    }
  };

  const commentImg = document.createElement("img");
  commentImg.src = baseUrl + "assets/commentBtn.svg";

  commentBtn.appendChild(commentImg);
  buttons.appendChild(commentBtn);

  actions.appendChild(buttons);

  const likeCount = document.createElement("p");
  likeCount.className = "like-count";
  likeCount.textContent = `${data.like_count} likes`;
  actions.appendChild(likeCount);

  const commentCount = document.createElement("button");
  commentCount.className = "comment-count";
  if (data.comment_count) {
    commentCount.textContent = `View all ${data.comment_count} comments`;
  } else {
    commentCount.textContent = "Add a comment..";
  }
  commentCount.onclick = () => displayComments(data.id);

  actions.appendChild(commentCount);
  post.appendChild(actions);
}

function createPost(data) {
  const post = document.getElementById("post");
  createPostHeader(post, data);
  createPostImage(post, data);
  createPostActions(post, data);
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
