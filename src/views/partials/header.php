<?php
$isAuth = false;
if (isAuth()) {
    $isAuth = true;
    $user = getUserById($db, $userId);
    $avatar = $user['avatar'] ? $baseUrl . "uploads/avatars/" . $user['avatar'] : null;
    $profilePath = "/" . $user['username'];
}
?>

<header>
    <div class="container">
        <a class="main-link" href="/">
            <img src="<?= $baseUrl . "assets/camagru.png"; ?>" alt="camagru.png">
        </a>
        <?php if ($isAuth): ?>
            <button id="mobile-menu-btn" class="mobileMenuBtn">
                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 7L4 7" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/><path d="M20 12L4 12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/><path d="M20 17L4 17" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/></svg>
            </button>
            <div id="auth-links" class="auth">
                <a class="link" href="/">
                    <svg aria-label="Home" class="x1lliihq x1n2onr6 x5n08af" fill="currentColor" height="20" role="img" viewBox="0 0 24 24" width="20"><title>Home</title><path d="M22 23h-6.001a1 1 0 0 1-1-1v-5.455a2.997 2.997 0 1 0-5.993 0V22a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V11.543a1.002 1.002 0 0 1 .31-.724l10-9.543a1.001 1.001 0 0 1 1.38 0l10 9.543a1.002 1.002 0 0 1 .31.724V22a1 1 0 0 1-1 1Z"></path></svg>
                    <p>Home</p>
                </a>
                <!-- <a class="link" href="/">
                    <svg aria-label="Search" class="x1lliihq x1n2onr6 x5n08af" fill="currentColor" height="20" role="img" viewBox="0 0 24 24" width="20"><title>Search</title><path d="M19 10.5A8.5 8.5 0 1 1 10.5 2a8.5 8.5 0 0 1 8.5 8.5Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="16.511" x2="22" y1="16.511" y2="22"></line></svg>
                    <p>Search</p>
                </a> -->
                <!-- <a class="link" href="/">
                    <svg aria-label="Notifications" class="x1lliihq x1n2onr6 x5n08af" fill="currentColor" height="20" role="img" viewBox="0 0 24 24" width="20"><title>Notifications</title><path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.072-2.652 4.959-5.197 7.222-2.512 2.243-3.865 3.469-4.303 3.752-.477-.309-2.143-1.823-4.303-3.752C5.141 14.072 2.5 12.167 2.5 9.122a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 5.827 5.015 7.97.283.246.569.494.853.747l1.027.918a44.998 44.998 0 0 0 3.518 3.018 2 2 0 0 0 2.174 0 45.263 45.263 0 0 0 3.626-3.115l.922-.824c.293-.26.59-.519.885-.774 2.334-2.025 4.98-4.32 4.98-7.94a6.985 6.985 0 0 0-6.708-7.218Z"></path></svg>
                    <p>Notifications</p>
                </a> -->
                <a class="link" href="/create">
                    <svg aria-label="New post" class="x1lliihq x1n2onr6 x5n08af" fill="currentColor" height="20" role="img" viewBox="0 0 24 24" width="20"><title>New post</title><path d="M2 12v3.45c0 2.849.698 4.005 1.606 4.944.94.909 2.098 1.608 4.946 1.608h6.896c2.848 0 4.006-.7 4.946-1.608C21.302 19.455 22 18.3 22 15.45V8.552c0-2.849-.698-4.006-1.606-4.945C19.454 2.7 18.296 2 15.448 2H8.552c-2.848 0-4.006.699-4.946 1.607C2.698 4.547 2 5.703 2 8.552Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="6.545" x2="17.455" y1="12.001" y2="12.001"></line><line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="12.003" x2="12.003" y1="6.545" y2="17.455"></line></svg>
                   <p>Create</p>
                </a>
                <a class="link" href=<?= $profilePath; ?>>
                    <?php if ($avatar): ?>
                        <img src="<?= $avatar; ?>" alt="avatar">
                    <?php else: ?>
                        <img src="<?= $baseUrl . "assets/noavatar.png"; ?>" alt="avatar">
                    <?php endif; ?>
                    <p>Profile</p>
                </a>
                <a class="link" href="/logout">
                    <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 492.5 492.5" xml:space="preserve"><g><path d="M184.646,0v21.72H99.704v433.358h31.403V53.123h53.539V492.5l208.15-37.422v-61.235V37.5L184.646,0z M222.938,263.129c-6.997,0-12.67-7.381-12.67-16.486c0-9.104,5.673-16.485,12.67-16.485s12.67,7.381,12.67,16.485C235.608,255.748,229.935,263.129,222.938,263.129z"/></g></svg>
                    <p>Log out</p>
                </a>
            </div>
            <script src="<?= $baseUrl . "scripts/mobileHeader.js" ?>"></script>
        <?php else: ?>
            <div class="guest">
                <a class="login" href="/login">Log In</a>
                <a class="register" href="/register">Sign Up</a>
            </div>
        <?php endif; ?>
    </div>
</header>
