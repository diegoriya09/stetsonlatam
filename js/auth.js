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

            localStorage.setItem('jwt', data.token);

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
                    window.location.href = 'admin'; // Redirige al admin
                } else {
                    window.location.reload(); // Recarga la página para el usuario normal
                }
            });

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
    const token = localStorage.getItem('jwt');
    const modal = document.getElementById('user-modal');
    const logoutBtn = document.getElementById('logout-btn');

    // --- Lógica del Modal (Cerrar y Cambiar Pestañas) ---
    if (modal) {
        const closeBtn = modal.querySelector('.close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => modal.style.display = 'none');
        }
        window.addEventListener('click', (e) => {
            if (e.target == modal) {
                modal.style.display = 'none';
            }
        });

        const loginFormSection = document.getElementById('login-form');
        const registerFormSection = document.getElementById('register-form');
        const switchToRegister = document.getElementById('switch-to-register');
        const switchToLogin = document.getElementById('switch-to-login');

        if (switchToRegister) {
            switchToRegister.addEventListener('click', (e) => {
                e.preventDefault();
                loginFormSection.style.display = 'none';
                registerFormSection.style.display = 'block';
            });
        }
        if (switchToLogin) {
            switchToLogin.addEventListener('click', (e) => {
                e.preventDefault();
                registerFormSection.style.display = 'none';
                loginFormSection.style.display = 'block';
            });
        }
    }

    // --- Lógica de Formularios ---
    const loginForm = document.getElementById('login-form-inner');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            try {
                // RUTA CORREGIDA Y ESTANDARIZADA
                const response = await fetch('/php/login', { method: 'POST', body: formData });
                const result = await response.json();

                if (result.token) {
                    localStorage.setItem('jwt', result.token);
                    const payload = JSON.parse(atob(result.token.split('.')[1]));
                    const userRole = payload.data.role;

                    Swal.fire({
                        title: 'Bienvenido de nuevo!', text: 'Inicio de sesión exitoso',
                        icon: 'success', timer: 1500, showConfirmButton: false
                    }).then(() => {
                        if (userRole === 'admin') {
                            window.location.href = 'admin';
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire("Error", result.message || "No se pudo iniciar sesión", "error");
                }
            } catch (error) {
                Swal.fire("Error", "Error de conexión con el servidor", "error");
            }
        });
    }

    const registerForm = document.getElementById('register-form-inner');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            try {
                // RUTA CORREGIDA Y ESTANDARIZADA
                const response = await fetch('/php/register', { method: 'POST', body: formData });
                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire({
                        title: '¡Registro exitoso!', html: result.message || 'Ahora puedes iniciar sesión.',
                        icon: 'success', confirmButtonText: 'Iniciar sesión'
                    }).then(() => {
                        openAuthModal(false); // Abrir el modal en la pestaña de login
                        registerForm.reset();
                    });
                } else {
                    Swal.fire("Error", result.message || "No se pudo registrar", "error");
                }
            } catch (error) {
                Swal.fire("Error", "Error de conexión con el servidor", "error");
            }
        });
    }

    // --- Lógica de Sesión (Logout, Mostrar/Ocultar, etc.) ---
    if (logoutBtn) {
        logoutBtn.style.display = token ? 'inline-block' : 'none';
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('jwt');
            // Idealmente, también llamar a un script de logout en el backend
            // await fetch('/php/auth/logout', { method: 'POST' });
            Swal.fire({
                title: 'Sesión cerrada', icon: 'success', timer: 1500, showConfirmButton: false
            }).then(() => {
                window.location.href = '/';
            });
        });
    }

    const notificationWrapper = document.getElementById('notification-wrapper');
    if (token && notificationWrapper) {
        notificationWrapper.style.display = 'block';
    }

    // Verificación de token expirado
    function isTokenExpired(token) {
        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
            return (payload.exp * 1000) < Date.now();
        } catch (e) { return true; }
    }

    if (token && isTokenExpired(token)) {
        localStorage.removeItem('jwt');
        Swal.fire({
            title: 'Sesión expirada', text: 'Por favor, inicia sesión de nuevo.',
            icon: 'warning'
        }).then(() => {
            openAuthModal();
        });
    }
});
