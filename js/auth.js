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

                    // 🔁 1. Obtener carrito del backend
                    const serverCart = await fetch('php/cart/get_cart.php', {
                        headers: {
                            'Authorization': 'Bearer ' + jwt
                        }
                    }).then(r => r.json());

                    const serverIds = serverCart.map(p => p.id);

                    // 🔁 2. Obtener carrito local
                    const localCart = JSON.parse(localStorage.getItem('carrito')) || [];

                    // ✅ 3. Filtrar los productos que NO están en el backend
                    const itemsToSync = localCart.filter(item => !serverIds.includes(item.id));

                    // 🔁 4. Enviar al backend solo los productos nuevos
                    if (itemsToSync.length > 0) {
                        await Promise.all(itemsToSync.map(item =>
                            fetch('php/cart/add_to_cart.php', {
                                method: 'POST',
                                headers: {
                                    'Authorization': 'Bearer ' + jwt,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ producto_id: item.id, quantity: item.quantity })
                            })
                        ));
                    }

                    // ✅ 5. Limpiar el carrito local para evitar futuros duplicados
                    localStorage.removeItem('carrito');

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
                        window.location.href = 'index.php';
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
                if (jwt) {
                    // Guarda el carrito actual en localStorage antes de cerrar sesión
                    const carrito = await fetch('php/cart/get_cart.php', {
                        headers: { 'Authorization': 'Bearer ' + jwt }
                    }).then(r => r.json());
                    localStorage.setItem('carrito', JSON.stringify(carrito));
                }
                localStorage.removeItem('jwt');
                location.reload();
            });
        });
    }

});
