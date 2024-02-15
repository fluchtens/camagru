async function getPosts(page) {
  try {
    const url = `${baseUrl}controllers/post/getPosts.controller.php?page=${page}`;
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

async function deletePost(postId) {
  try {
    const url = baseUrl + "controllers/post/deletePost.controller.php";
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ post_id: postId }),
    });

    if (response.ok) {
      window.history.back();
    }
  } catch (error) {
    console.error("An error occurred:", error);
  }
}

function addPostToFeed(data) {
  const feed = document.getElementById("feed");

  const post = document.createElement("li");
  post.classList.add("post");

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

    if (data.deletable) {
      const deleteBtn = document.createElement("button");
      deleteBtn.classList.add("delete-btn");
      deleteBtn.onclick = () => deletePost(data.id);

      const deleteIcon = document.createElement("img");
      deleteIcon.src = baseUrl + "assets/deleteIcon.png";

      deleteBtn.appendChild(deleteIcon);
      user.appendChild(deleteBtn);
    }

    post.appendChild(user);
  };

  const createImage = () => {
    const image = document.createElement("img");
    image.src = `${baseUrl}assets/uploads/posts/${data.file}`;
    image.alt = data.file;

    post.appendChild(image);
  };

  const createActions = () => {
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

    actions.appendChild(likeCount);

    const commentCount = document.createElement("a");
    commentCount.href = `/c/${data.id}`;
    commentCount.classList.add("commentCount");
    if (data.comment_count) {
      commentCount.textContent = `View all ${data.comment_count} comments`;
    } else {
      commentCount.textContent = "Add a comment..";
    }

    actions.appendChild(commentCount);

    post.appendChild(actions);
  };

  createUser();
  createImage();
  createActions();

  feed.appendChild(post);
}

document.addEventListener("DOMContentLoaded", () => {
  const home = document.getElementById("home");
  let loading = undefined;
  let page = 1;
  let firstLoad = true;
  let loadingInProgress = false;
  let lastPage = false;

  async function loadMorePosts() {
    const data = await getPosts(page);

    if (firstLoad) {
      if (!data) {
        const title = document.createElement("h1");
        title.textContent = "No Posts Yet";
        home.appendChild(title);
      } else {
        const feed = document.createElement("ul");
        feed.id = "feed";
        home.appendChild(feed);

        const loadingImg = document.createElement("img");
        loadingImg.id = "loadingIcon";
        loadingImg.classList.add("loading");
        loadingImg.src = baseUrl + "assets/loading.gif";
        home.appendChild(loadingImg);
        loading = document.getElementById("loadingIcon");
      }
      firstLoad = false;
    }

    if (!data) {
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
      if (!loadingInProgress && !lastPage) {
        if (!firstLoad) {
          loading.style.display = "block";
        }
        loadingInProgress = true;
        page++;
        setTimeout(async () => {
          await loadMorePosts();
          if (!firstLoad) {
            loading.style.display = "none";
          }
          loadingInProgress = false;
        }, 1000);
      }
    }
  });

  loadMorePosts();
});
