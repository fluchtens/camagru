function createCommentsModal() {
  document.body.style.overflow = "hidden";
  const modal = document.createElement("div");
  modal.id = "commentsModal";
  modal.className = "modal";
  return modal;
}

function createCommentsPost() {
  const post = document.createElement("div");
  post.className = "comments-post";
  return post;
}

function createCommentsHeader(modal, post) {
  const header = document.createElement("div");
  header.className = "header";

  const title = document.createElement("h1");
  title.textContent = "Comments";

  const closeBtn = document.createElement("button");
  closeBtn.addEventListener("click", () => {
    document.body.style.overflow = "auto";
    modal.parentNode.removeChild(modal);
  });

  const closeImg = document.createElement("img");
  closeImg.src = baseUrl + "assets/btns/cross.png";
  closeImg.alt = "cross.png";

  header.appendChild(title);
  closeBtn.appendChild(closeImg);
  header.appendChild(closeBtn);
  post.appendChild(header);
}

function createCommentsHr(post) {
  const hr = document.createElement("hr");
  post.appendChild(hr);
}

function createComment(comments, com) {
  const comment = document.createElement("div");
  comment.className = "comment";

  const avatar = document.createElement("img");
  if (com.user_avatar) {
    avatar.src = baseUrl + "assets/uploads/avatars/" + com.user_avatar;
    avatar.alt = com.user_avatar;
  } else {
    avatar.src = baseUrl + "assets/noavatar.png";
    avatar.alt = "noavatar.png";
  }

  const texts = document.createElement("div");
  texts.className = "texts";

  const title = document.createElement("div");
  title.className = "title";

  const username = document.createElement("span");
  username.className = "username";
  username.textContent = com.user_username;

  const timeDiff = document.createElement("span");
  timeDiff.className = "time-diff";
  timeDiff.textContent = formatElapsedTime(com.time_diff);

  const message = document.createElement("message");
  message.className = "message";
  message.textContent = com.comment;

  comment.appendChild(avatar);
  title.appendChild(username);
  title.appendChild(timeDiff);
  texts.appendChild(title);
  texts.appendChild(message);
  comment.appendChild(texts);
  comments.appendChild(comment);
}

async function createComments(post, postId) {
  const comments = document.createElement("div");
  comments.className = "comments";
  comments.id = "comments";

  const req = await getComments(postId);
  if (!req) {
    const h2 = document.createElement("h2");
    h2.textContent = "No comments yet";

    const h3 = document.createElement("h3");
    h3.textContent = "Start the conversation.";

    comments.appendChild(h2);
    comments.appendChild(h3);
  } else {
    req.forEach((com) => {
      createComment(comments, com);
    });
  }

  post.appendChild(comments);
  return comments;
}

function createCommentsErrMsg(post) {
  const errMsg = document.createElement("div");
  errMsg.id = "commentsErrMsg";
  errMsg.classList = "err-msg";

  const errMsgText = document.createElement("p");
  errMsgText.id = "commentsErrMsgText";

  errMsg.appendChild(errMsgText);
  post.appendChild(errMsg);
}

async function createForm(post, postId, comments) {
  const form = document.createElement("form");
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const comment = formData.get("comment");
    const errMsg = document.getElementById("commentsErrMsg");
    const errMsgText = document.getElementById("commentsErrMsgText");

    form.reset();
    const req = await addComment(postId, comment);
    if (req.success) {
      const newComments = await getComments(postId);
      if (newComments) {
        errMsg.style.display = "none";
        errMsgText.textContent = "";
        comments.innerHTML = "";
        newComments.forEach((com) => {
          createComment(comments, com);
        });
      }
    } else {
      errMsg.style.display = "block";
      errMsgText.textContent = req.message;
    }
  });

  const senderAvatar = document.createElement("img");
  const sender = await getUser();
  if (sender && sender.avatar) {
    senderAvatar.src = baseUrl + "assets/uploads/avatars/" + sender.avatar;
    senderAvatar.alt = sender.avatar;
  } else {
    senderAvatar.src = baseUrl + "assets/noavatar.png";
    senderAvatar.alt = "noavatar.png";
  }

  const input = document.createElement("input");
  input.type = "text";
  input.name = "comment";
  input.placeholder = "Add a comment..";
  input.autocomplete = "off";
  input.required = true;

  form.appendChild(senderAvatar);
  form.appendChild(input);
  post.appendChild(form);
}

async function displayComments(postId) {
  const home = document.getElementById("home");
  const uniquePost = document.getElementById("post");

  const modal = createCommentsModal();
  const post = createCommentsPost();
  createCommentsHeader(modal, post);
  createCommentsHr(post);
  const comments = await createComments(post, postId);
  createCommentsHr(post);
  createCommentsErrMsg(post);
  await createForm(post, postId, comments);
  modal.appendChild(post);
  if (home) {
    home.appendChild(modal);
  }
  if (uniquePost) {
    uniquePost.appendChild(modal);
  }
}
