<header>
    <div class="container">
        <a class="main-link" href="/">
            <img src="./assets/camagru.png" alt="camagru.png">
        </a>
        <div class="links-container">
            <?php if (!isset($_SESSION['id'])): ?>
                <a class="login-btn" href="/login">
                    <button>Log In</button>
                </a>
                <a class="register-btn" href="/register">
                    <button>Sign Up</button>
                </a>
            <?php else: ?>
                <a href="/logout">
                    <button>Log out</button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
