// La función de callback de Google DEBE ser global, así que la dejamos aquí fuera.
async function handleGoogleCredentialResponse(response) {
    try {
        const res = await fetch('/php/google_signin', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ credential: response.credential })
        });
        const data = await res.json();

        if (data.success && data.token) {
            // ¡Éxito! El backend nos devolvió NUESTRO propio token JWT
            localStorage.setItem('jwt', data.token);

            // --- CÓDIGO AÑADIDO: Lógica de decodificación y redirección ---
            // (Copiada de tu función de login normal)
            const jwt = data.token;
            const payload = JSON.parse(atob(jwt.split('.')[1]));
            const userRole = payload.data.role;

            Swal.fire({
                title: '¡Bienvenido!',
                text: 'Inicio de sesión con Google exitoso.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                timerProgressBar: true
            }).then(() => {
                // Redirigir según el rol
                if (userRole === 'admin') {
                    window.location.href = '../admin/admin'; // Redirige al admin
                } else {
                    window.location.reload(); // Recarga la página para el usuario normal
                }
            });
            // --- FIN DEL CÓDIGO AÑADIDO ---
            
        } else {
            Swal.fire('Error', data.message || 'No se pudo iniciar sesión con Google.', 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Ocurrió un problema de conexión.', 'error');
    }
}

function openAuthModal(showRegister = false) {
    const modal = document.getElementById('user-modal');
    if (!modal) return;

    modal.style.display = 'block';

    const loginTab = document.getElementById('switch-to-login');
    const registerTab = document.getElementById('switch-to-register');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    // Lógica para mostrar la pestaña correcta (login o registro)
    if (showRegister) {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        registerTab.classList.add('border-b-[#3c3737]', 'text-[#3c3737]');
        loginTab.classList.remove('border-b-[#3c3737]', 'text-[#3c3737]');
    } else {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        loginTab.classList.add('border-b-[#3c3737]', 'text-[#3c3737]');
        registerTab.classList.remove('border-b-[#3c3737]', 'text-[#3c3737]');
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById('user-modal');
    if (!modal) return; // Si no hay modal en la página, no hacer nada

    const closeBtn = modal.querySelector('.close');
    // Alternar entre login y registro
    const loginFormSection = document.getElementById('login-form');
    const registerFormSection = document.getElementById('register-form');
    const switchToRegister = document.getElementById('switch-to-register');
    const switchToLogin = document.getElementById('switch-to-login');

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }
    window.addEventListener('click', (e) => {
        if (e.target == modal) {
            modal.style.display = 'none';
        }
    });

    if (switchToRegister && loginFormSection && registerFormSection) {
        switchToRegister.addEventListener('click', (e) => {
            e.preventDefault();
            loginFormSection.style.display = 'none';
            registerFormSection.style.display = 'block';
            switchToRegister.classList.add('border-b-[#3c3737]', 'text-[#3c3737]');
            switchToLogin.classList.remove('border-b-[#3c3737]', 'text-[#3c3737]');
        });
    }
    if (switchToLogin && loginFormSection && registerFormSection) {
        switchToLogin.addEventListener('click', (e) => {
            e.preventDefault();
            registerFormSection.style.display = 'none';
            loginFormSection.style.display = 'block';
            switchToLogin.classList.add('border-b-[#3c3737]', 'text-[#3c3737]');
            switchToRegister.classList.remove('border-b-[#3c3737]', 'text-[#3c3737]');
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
                const response = await fetch('/php/login', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();


                if (result.token) {
                    localStorage.setItem('jwt', result.token);
                    const jwt = result.token;

                    // 🔎 Decodificar payload del JWT
                    const payload = JSON.parse(atob(jwt.split('.')[1]));
                    const userRole = payload.data.role;

                    // ✅ Mostrar logout, cerrar modal y redirigir con alerta
                    if (logoutBtn) logoutBtn.style.display = 'inline-block';
                    const userModal = document.getElementById('user-modal');
                    if (userModal) userModal.style.display = 'none';

                    // ✅ Alerta y redirección
                    Swal.fire({
                        title: 'Bienvenido de nuevo!',
                        text: 'Inicio de sesión exitoso',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (userRole === 'admin') {
                            window.location.href = '../admin/admin'; // 👈 admin redirigido
                        } else {
                            location.reload(); // 👈 usuario normal se queda en index
                        }
                    });
                } else {
                    Swal.fire("Error", result.error || "No se pudo iniciar sesión", "error");
                }

            } catch (error) {
                Swal.fire("Error", "Error de conexión con el servidor", "error");
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
                const response = await fetch('/php/register', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Mostrar Swal para registro exitoso y redirigir a login
                    Swal.fire({
                        title: '¡Registro exitoso!',
                        text: 'Ahora puedes iniciar sesión con tu cuenta.',
                        icon: 'success',
                        confirmButtonText: 'Iniciar sesión',
                        allowOutsideClick: false
                    }).then(() => {
                        // Cambiar a la pestaña de login y limpiar el form de registro
                        const loginTab = document.getElementById('switch-to-login');
                        const registerTab = document.getElementById('switch-to-register');
                        const loginFormSection = document.getElementById('login-form');
                        const registerFormSection = document.getElementById('register-form');
                        if (loginTab && registerTab && loginFormSection && registerFormSection) {
                            loginTab.classList.add('border-[#3c3737]', 'text-[#3c3737]');
                            loginTab.classList.remove('border-transparent', 'text-[#7a7671]');
                            registerTab.classList.remove('border-[#3c3737]', 'text-[#3c3737]');
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
                    Swal.fire("Error", result.message || "No se pudo registrar", "error");
                }
            } catch (error) {
                Swal.fire("Error", "Error de conexión con el servidor", "error");
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
                title: 'Sesión cerrada',
                text: 'Has cerrado sesión exitosamente',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true
            }).then(async () => {
                // Llamada al backend para destruir la sesión
                await fetch('/php/logout', { method: 'POST' });

                // Limpiar JWT del localStorage
                localStorage.removeItem('jwt');

                // Redirigir
                window.location.href = 'https://stetsonlatam.com/';
            });
        });
    }

    const signupSection = document.getElementById('signup-section');

    if (signupSection) {
        if (token) {
            signupSection.style.display = 'none'; // ✅ ocultar si ya hay sesión
        } else {
            signupSection.style.display = 'block'; // ✅ mostrar si NO hay sesión
        }
    }

    function isTokenExpired(token) {
        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
            const currentTime = Math.floor(Date.now() / 1000);
            return payload.exp < currentTime;
        } catch (error) {
            console.error('Error al decodificar el token:', error);
            return true; // Si hay un error, asumimos que el token está expirado
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        const token = localStorage.getItem('jwt');

        if (token && isTokenExpired(token)) {
            Swal.fire({
                title: 'Sesión expirada',
                text: 'Por favor, inicia sesión nuevamente para continuar.',
                icon: 'warning',
                confirmButtonText: 'Iniciar sesión'
            }).then(() => {
                localStorage.removeItem('jwt');
                window.location.href = 'https://stetsonlatam.com/';
            });
        }
    });

    const notificationWrapper = document.getElementById('notification-wrapper');
    if (token && notificationWrapper) { // Reutilizamos la variable 'token' que ya está definida en este archivo
        notificationWrapper.style.display = 'block';
    }
});
