/* -------------------------------------------------------------------------- */
/*                                    Post                                    */
/* -------------------------------------------------------------------------- */

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
  } catch (error) {}
}

/* -------------------------------------------------------------------------- */
/*                                  Comments                                  */
/* -------------------------------------------------------------------------- */

async function getComments(postId) {
  try {
    const url = baseUrl + "controllers/post/getComments.controller.php";
    const response = await fetch(`${url}?post_id=${postId}`, {
      method: "GET",
    });

    const data = await response.json();
    if (!response.ok) {
      return null;
    }

    return data;
  } catch (error) {
    return null;
  }
}

async function addComment(postId, comment) {
  try {
    const url = baseUrl + "controllers/post/addComment.controller.php";
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ post_id: postId, comment: comment }),
    });

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}
