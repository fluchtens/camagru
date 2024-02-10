async function getPosts(page) {
  try {
    const url = `${baseUrl}controllers/post/getAllPosts.controller.php?page=${page}`;
    const response = await fetch(url, {
      method: "GET",
    });

    const data = await response.json();
    if (!response.ok) {
      return null;
    }

    return data;
  } catch (error) {
    console.error("An error occurred:", error);
    return null;
  }
}

async function likePost(postId) {
  try {
    const url = baseUrl + "controllers/likePost.controller.php";
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

function addPostToFeed(data) {
  const feed = document.getElementById("feed");

  const post = document.createElement("li");
  post.classList.add("post");

  const user = document.createElement("a");
  user.classList.add("user");
  user.href = `/${data.user_username}`;

  const avatar = document.createElement("img");
  if (data.user_avatar) {
    avatar.src = `${baseUrl}assets/uploads/avatars/${data.user_avatar}`;
    avatar.alt = data.user_avatar;
  } else {
    avatar.src = `${baseUrl}assets/noavatar.png`;
    avatar.alt = "noavatar.png";
  }

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

  user.appendChild(avatar);
  user.appendChild(texts);

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

    const unlikeSvg = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "svg"
    );
    unlikeSvg.setAttribute("aria-label", "Unlike");
    unlikeSvg.setAttribute("fill", "currentColor");
    unlikeSvg.setAttribute("width", "24");
    unlikeSvg.setAttribute("height", "24");
    unlikeSvg.setAttribute("role", "img");
    unlikeSvg.setAttribute("viewBox", "0 0 48 48");
    unlikeSvg.innerHTML =
      '<title>Unlike</title><path d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path>';

    unlikeBtn.appendChild(unlikeSvg);
    buttons.appendChild(unlikeBtn);
  } else {
    const likeBtn = document.createElement("button");
    likeBtn.classList.add("likeBtn");
    likeBtn.setAttribute("data-post-id", data.id);

    const likeSvg = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "svg"
    );
    likeSvg.setAttribute("aria-label", "Like");
    likeSvg.setAttribute("fill", "currentColor");
    likeSvg.setAttribute("width", "24");
    likeSvg.setAttribute("height", "24");
    likeSvg.setAttribute("role", "img");
    likeSvg.setAttribute("viewBox", "0 0 24 24");
    likeSvg.innerHTML =
      '<title>Like</title><path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.072-2.652 4.959-5.197 7.222-2.512 2.243-3.865 3.469-4.303 3.752-.477-.309-2.143-1.823-4.303-3.752C5.141 14.072 2.5 12.167 2.5 9.122a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 5.827 5.015 7.97.283.246.569.494.853.747l1.027.918a44.998 44.998 0 0 0 3.518 3.018 2 2 0 0 0 2.174 0 45.263 45.263 0 0 0 3.626-3.115l.922-.824c.293-.26.59-.519.885-.774 2.334-2.025 4.98-4.32 4.98-7.94a6.985 6.985 0 0 0-6.708-7.218Z"></path>';

    likeBtn.appendChild(likeSvg);
    buttons.appendChild(likeBtn);
  }

  const commentLink = document.createElement("a");
  commentLink.href = `/c/${data.id}`;
  commentLink.classList.add("comment");

  const commentSvg = document.createElementNS(
    "http://www.w3.org/2000/svg",
    "svg"
  );
  commentSvg.setAttribute("aria-label", "Comment");
  commentSvg.classList.add("x1lliihq", "x1n2onr6", "x5n08af");
  commentSvg.setAttribute("fill", "currentColor");
  commentSvg.setAttribute("height", "24");
  commentSvg.setAttribute("role", "img");
  commentSvg.setAttribute("viewBox", "0 0 24 24");
  commentSvg.innerHTML =
    '<title>Comment</title><path d="M20.656 17.008a9.993 9.993 0 1 0-3.59 3.615L22 22Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"></path>';

  commentLink.appendChild(commentSvg);
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

  feed.appendChild(post);
}

document.addEventListener("DOMContentLoaded", () => {
  const loading = document.getElementById("loadingIcon");
  const likeBtns = document.querySelectorAll(".likeBtn, .unlikeBtn");
  let page = 1;
  let lastPage = false;
  let loadingInProgress = false;

  async function loadMorePosts() {
    const data = await getPosts(page);
    if (!data || !data.length) {
      lastPage = true;
    }
    if (data && data.length > 0) {
      for (const post of data) {
        addPostToFeed(post);
      }
    }
  }

  window.addEventListener("scroll", () => {
    if (
      window.innerHeight + window.scrollY >=
      document.body.offsetHeight - 100
    ) {
      if (!lastPage && !loadingInProgress) {
        loading.style.display = "block";
        loadingInProgress = true;
        page++;
        setTimeout(async () => {
          await loadMorePosts();
          loading.style.display = "none";
          loadingInProgress = false;
        }, 1000);
      }
    }
  });

  loadMorePosts();

  feed.addEventListener("click", async (event) => {
    const target = event.target;

    const likeBtn = target.closest(".likeBtn");
    const unlikeBtn = target.closest(".unlikeBtn");

    if (likeBtn || unlikeBtn) {
      const postId = likeBtn || unlikeBtn;
      const postIdValue = postId.getAttribute("data-post-id");

      const req = await likePost(postIdValue);
      if (req.success) {
        const svg = postId.querySelector("svg");
        const path = postId.querySelector("path");
        const likeCount = postId
          .closest(".actions")
          .querySelector(".likeCount");
        const currentLikes = parseInt(likeCount.textContent);

        if (likeBtn) {
          svg.setAttribute("viewBox", "0 0 48 48");
          path.setAttribute(
            "d",
            "M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"
          );
          likeBtn.classList.remove("likeBtn");
          likeBtn.classList.add("unlikeBtn");
          likeCount.textContent = currentLikes + 1 + " likes";
        } else {
          svg.setAttribute("viewBox", "0 0 24 24");
          path.setAttribute(
            "d",
            "M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.072-2.652 4.959-5.197 7.222-2.512 2.243-3.865 3.469-4.303 3.752-.477-.309-2.143-1.823-4.303-3.752C5.141 14.072 2.5 12.167 2.5 9.122a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 5.827 5.015 7.97.283.246.569.494.853.747l1.027.918a44.998 44.998 0 0 0 3.518 3.018 2 2 0 0 0 2.174 0 45.263 45.263 0 0 0 3.626-3.115l.922-.824c.293-.26.59-.519.885-.774 2.334-2.025 4.98-4.32 4.98-7.94a6.985 6.985 0 0 0-6.708-7.218Z"
          );
          unlikeBtn.classList.remove("unlikeBtn");
          unlikeBtn.classList.add("likeBtn");
          likeCount.textContent = currentLikes - 1 + " likes";
        }
      } else {
        if (req.code === 401) {
          window.location.href = "/login";
        }
      }
    }
  });
});
