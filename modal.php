<div id="user-modal" class="modal">
        <div class="modal-content form-wrapper">
            <span class="close">&times;</span>
            <!-- Login Form -->
            <div id="login-form" class="form-section">
                <h2>Login</h2>
                <form action="php/login.php" method="POST">
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
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
                    <button type="submit">Create</button>
                </form>
                <p>Already have an account? <a href="#" id="switch-to-login">Login</a></p>
            </div>
        </div>
</div>