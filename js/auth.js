document.addEventListener("DOMContentLoaded", () => {

    // Alternar entre login y registro
    const loginFormSection = document.getElementById('login-form');
    const registerFormSection = document.getElementById('register-form');
    const switchToRegister = document.getElementById('switch-to-register');
    const switchToLogin = document.getElementById('switch-to-login');

    if (switchToRegister && loginFormSection && registerFormSection) {
        switchToRegister.addEventListener('click', (e) => {
            e.preventDefault();
            loginFormSection.style.display = 'none';
            registerFormSection.style.display = 'block';
            switchToRegister.classList.add('border-b-[#151514]', 'text-[#151514]');
            switchToLogin.classList.remove('border-b-[#151514]', 'text-[#151514]');
        });
    }
    if (switchToLogin && loginFormSection && registerFormSection) {
        switchToLogin.addEventListener('click', (e) => {
            e.preventDefault();
            registerFormSection.style.display = 'none';
            loginFormSection.style.display = 'block';
            switchToLogin.classList.add('border-b-[#151514]', 'text-[#151514]');
            switchToRegister.classList.remove('border-b-[#151514]', 'text-[#151514]');
        });
    }

    // Referencia global al botón de logout
    const logoutBtn = document.getElementById('logout-btn');

    // LOGIN
    const loginForm = document.getElementById('login-form-inner');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);

            try {
                const response = await fetch('php/login.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();


                if (result.token) {
                    localStorage.setItem('jwt', result.token);
                    const jwt = result.token;

                    // ✅ 6. Mostrar logout, cerrar modal y redirigir con alerta
                    if (logoutBtn) logoutBtn.style.display = 'inline-block';
                    const userModal = document.getElementById('user-modal');
                    if (userModal) userModal.style.display = 'none';
                    Swal.fire({
                        title: 'Welcome back!',
                        text: 'Successful login',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Error", result.error || "Could not log in", "error");
                }

            } catch (error) {
                Swal.fire("Error", "Server connection error", "error");
            }
        });
    }

    // REGISTRO
    const registerForm = document.getElementById('register-form-inner');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);

            try {
                const response = await fetch('php/register.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Mostrar Swal para registro exitoso y redirigir a login
                    Swal.fire({
                        title: 'Registration successful!',
                        text: 'You can now log in with your account.',
                        icon: 'success',
                        confirmButtonText: 'Log in',
                        allowOutsideClick: false
                    }).then(() => {
                        // Cambiar a la pestaña de login y limpiar el form de registro
                        const loginTab = document.getElementById('switch-to-login');
                        const registerTab = document.getElementById('switch-to-register');
                        const loginFormSection = document.getElementById('login-form');
                        const registerFormSection = document.getElementById('register-form');
                        if (loginTab && registerTab && loginFormSection && registerFormSection) {
                            loginTab.classList.add('border-[#181411]', 'text-[#181411]');
                            loginTab.classList.remove('border-transparent', 'text-[#7a7671]');
                            registerTab.classList.remove('border-[#181411]', 'text-[#181411]');
                            registerTab.classList.add('border-transparent', 'text-[#7a7671]');
                            loginFormSection.style.display = 'block';
                            registerFormSection.style.display = 'none';
                        }
                        // Limpiar el formulario de registro
                        const registerForm = document.getElementById('register-form-inner');
                        if (registerForm) registerForm.reset();
                    });
                    // Cerrar modal con la X (siempre funciona)
                    function setupCloseModal() {
                        const closeModalBtn = document.querySelector('#user-modal .close');
                        if (closeModalBtn) {
                            closeModalBtn.onclick = function () {
                                const userModal = document.getElementById('user-modal');
                                if (userModal) userModal.style.display = 'none';
                            };
                        }
                    }
                    setupCloseModal();
                } else {
                    Swal.fire("Error", result.message || "Could not register", "error");
                }
            } catch (error) {
                Swal.fire("Error", "Server connection error", "error");
            }
        });
    }

    // MOSTRAR/OCULTAR elementos según sesión activa
    const token = localStorage.getItem('jwt');
    if (logoutBtn) {
        if (token) {
            logoutBtn.style.display = 'inline-block';
        } else {
            logoutBtn.style.display = 'none';
        }
    }

    // LOGOUT
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            Swal.fire({
                title: 'Closed session',
                text: 'You have successfully logged out',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true
            }).then(async () => {
                const jwt = localStorage.getItem('jwt');
                localStorage.removeItem('jwt');
                window.location.href = 'index.php';
            });
        });
    }

    function isTokenExpired(token) {
        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
            const currentTime = Math.floor(Date.now() / 1000);
            return payload.exp < currentTime;
        } catch (error) {
            console.error('Error decoding token:', error);
            return true; // Si hay un error, asumimos que el token está expirado
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        const token = localStorage.getItem('jwt');

        if (token && isTokenExpired(token)) {
            Swal.fire({
                title: 'Session expired',
                text: 'Please log in again to continue.',
                icon: 'warning',
                confirmButtonText: 'Log in'
            }).then(() => {
                localStorage.removeItem('jwt');
                window.location.href = 'index.php';
            });
        }
    });

});
