<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div id="user-modal" class="modal">
    <div class="modal-content form-wrapper">
        <span class="close">&times;</span>
        <!-- Login Form -->
        <div id="login-form" class="form-section">
            <h2>Login</h2>
            <form action="php/login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <div class="password-wrapper">
                    <input type="password" id="password-field" class="form-control" />
                    <div class="toggle-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon">
                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Enter</button>
        </form>
        <p>Don't have an account? <a href="#" id="switch-to-register">Create account</a></p>
    </div>
    <!-- Register Form (oculto al inicio) -->
    <div id="register-form" class="form-section" style="display: none;">
        <h2>Create Account</h2>
        <form id="registerForm" action="/php/register.php" method="POST">
            <input type="text" name="name" placeholder="Full name" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <!-- Campo oculto CSRF aquí -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit">Create</button>
        </form>
        <p>Already have an account? <a href="#" id="switch-to-login">Login</a></p>
    </div>
</div>
</div>