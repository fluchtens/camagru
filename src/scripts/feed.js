function createPost() {
  const post = document.createElement("li");
  post.className = "post";
  return post;
}

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
    deleteBtn.onclick = async () => {
      const req = await deletePost(data.id);
      if (req) {
        window.history.back();
      }
    };

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

function addPostToFeed(data) {
  const feed = document.getElementById("feed");

  const post = createPost();
  createPostHeader(post, data);
  createPostImage(post, data);
  createPostActions(post, data);

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
        loadingImg.className = "loading";
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
