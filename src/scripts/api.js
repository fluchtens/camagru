/* -------------------------------------------------------------------------- */
/*                                  Account                                   */
/* -------------------------------------------------------------------------- */

async function login(formData) {
  try {
    const url = baseUrl + "controllers/auth/login.controller.php";
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

async function signup(formData) {
  try {
    const url = baseUrl + "controllers/auth/signup.controller.php";
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

async function getUser() {
  try {
    const url = baseUrl + "controllers/account/getUser.controller.php";
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

async function editProfile(formData) {
  try {
    const url = baseUrl + "controllers/account/editProfile.controller.php";
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

async function updatePassword(formData) {
  try {
    const url = baseUrl + "controllers/account/editPassword.controller.php";
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

async function sendResetPasswordRequest(formData) {
  try {
    const url = baseUrl + "controllers/account/forgotPassword.controller.php";
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

async function resetPassword(formData) {
  try {
    const url = baseUrl + "controllers/account/resetPassword.controller.php";
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

/* -------------------------------------------------------------------------- */
/*                                    Post                                    */
/* -------------------------------------------------------------------------- */

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
    return { success: false, message: error.message };
  }
}

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

async function getWaitingPosts() {
  try {
    const url = `${baseUrl}controllers/post/getWaitingPosts.controller.php`;
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

    if (response.status === 413) {
      return false;
    }

    if (!response.ok) {
      return false;
    }

    return true;
  } catch (error) {
    return false;
  }
}

async function deleteWaitingPost(postId) {
  try {
    const url = baseUrl + "controllers/post/deleteWaitingPost.controller.php";
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ post_id: postId }),
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

async function publishPhotos() {
  try {
    const url = baseUrl + "controllers/post/publishPhotos.controller.php";
    const response = await fetch(url, {
      method: "POST",
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

async function savePhoto(image, filter) {
  try {
    const url = baseUrl + "controllers/post/takePhoto.controller.php";
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ image: image, filter: filter }),
    });

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}

/* -------------------------------------------------------------------------- */
/*                                    Likes                                   */
/* -------------------------------------------------------------------------- */

async function likePost(postId) {
  try {
    const url = baseUrl + "controllers/post/likePost.controller.php";
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ post_id: postId }),
    });

    if (response.status === 413) {
      return {
        success: false,
        code: response.status,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, code: response.status, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, code: response.status, message: error.message };
  }
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

    if (response.status === 413) {
      return {
        success: false,
        message: response.statusText,
      };
    }

    const data = await response.json();
    if (!response.ok) {
      return { success: false, message: data.message };
    }

    return { success: true, message: data.message };
  } catch (error) {
    return { success: false, message: error.message };
  }
}
