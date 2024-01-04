<?php
if (!isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}

function checkUsername($username) {
    if (strlen($username) < 3 || strlen($username) > 16) {
        return ['success' => false, 'message' => "Username must be between 3 and 16 characters long."];
    }
    if (!ctype_alpha($username[0])) {
        return ['success' => false, 'message' => "Username must start with a letter."];
    }
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        return ['success' => false, 'message' => "Username can only contain letters, digits, underscores & hyphens."];
    }
    return ['success' => true, 'message' => null];
}

function submitData($username) {
    try {
        $db = connectToDatabase();
        $id = $_SESSION['id'];
    
        $usernameCheck = checkUsername($username);
        if (!$usernameCheck['success']) {
            return ['code' => 400, 'message' => $usernameCheck['message']];
        }
    
        if (getUserByUsername($db, $username)) {
            return ['code' => 409, 'message' => "This username is already taken."];
        }
    
        updateUsername($db, $id, $username);
        return ['code' => 200, 'message' => "User succesfully created."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $newUsername = htmlspecialchars($_POST['username']);
    // $response = submitData($newUsername);
    // http_response_code($response['code']);
    // if ($response['code'] !== 200) {
    //     $error = $response['message'];
    // }
}
?>

<div class="settings">
    <h1>Edit profile</h1>
    <?php if ($error): ?>
        <div class="auth-err-msg">
            <p><?php echo $error; ?></p>
        </div>
        <?php endif; ?>
        <form class="options" id="profileSettings" method="POST" action="" onsubmit="updateAvatar()">
            <div class="data">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="Username" autocomplete="off">
            </div>
            <div class="data">
                <label for="avatar">Avatar</label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit">Submit</button>
        </form> 
        <script src="scripts/updateAvatar.js"></script>
</div>

