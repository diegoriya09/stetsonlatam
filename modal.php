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
                <div class="password-wrapper" style="position:relative;">
                    <input type="password" name="password" id="login-password" placeholder="Password" required class="form-control" />
                    <span class="toggle-button" id="toggle-login-password" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon" width="22" height="22">
                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
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
                <div class="password-wrapper" style="position:relative;">
                    <input type="password" name="password" id="register-password" placeholder="Password" required class="form-control" />
                    <span class="toggle-button" id="toggle-register-password" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon" width="22" height="22">
                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit">Create</button>
            </form>
            <p>Already have an account? <a href="#" id="switch-to-login">Login</a></p>
        </div>
    </div>
</div>
<script>
// Show/hide password for login and register with animated eye icon
document.addEventListener('DOMContentLoaded', function() {
    const eyeIcons = {
        open: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd"/></svg>',
        closed: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon"><path d="M3.53 2.47a.75.75 0 00-1.06 1.06l18 18a.75.75 0 101.06-1.06l-18-18zM22.676 12.553a11.249 11.249 0 01-2.631 4.31l-3.099-3.099a5.25 5.25 0 00-6.71-6.71L7.759 4.577a11.217 11.217 0 014.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113z"/><path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0115.75 12zM12.53 15.713l-4.243-4.244a3.75 3.75 0 004.243 4.243z"/><path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 00-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 016.75 12z"/></svg>'
    };

    function setupToggle(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        if (toggle && input) {
            toggle.innerHTML = eyeIcons.open;
            toggle.style.transition = 'color 0.2s';
            toggle.addEventListener('mousedown', function(e) {
                e.preventDefault(); // Evita perder el foco
            });
            toggle.addEventListener('click', function() {
                const isVisible = input.type === 'text';
                input.type = isVisible ? 'password' : 'text';
                toggle.innerHTML = isVisible ? eyeIcons.open : eyeIcons.closed;
                toggle.classList.toggle('eye-closed', !isVisible);
                input.focus(); // Mantiene el foco en el input
            });
        }
    }
    setupToggle('toggle-login-password', 'login-password');
    setupToggle('toggle-register-password', 'register-password');
});
// CSS para animar el ojo y la rayita
const style = document.createElement('style');
style.innerHTML = `
.eye-icon {
  transition: all 0.2s;
}
.toggle-button.eye-closed .eye-icon {
  opacity: 0.7;
  /* Puedes agregar más animación si lo deseas */
}
`;
document.head.appendChild(style);
</script>