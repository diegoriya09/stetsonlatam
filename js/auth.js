document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.querySelector('#login-form form');
    const userIcon = document.getElementById('open-user-modal');
    const logoutBtn = document.getElementById('logout-btn');

    // LOGIN
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);

            try {
                const response = await fetch('php/login.php', {
                    method: 'POST',
                    body: formData,
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

                    // ✅ 6. Redirigir con alerta
                    Swal.fire({
                        title: 'Bienvenido',
                        text: 'Inicio de sesión exitoso',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        loadCart(true); // Cargar el carrito actualizado
                        window.location.href = 'index.php'; // Redirigir a la página principal
                    });

                } else {
                    Swal.fire("Error", result.error || "No se pudo iniciar sesión", "error");
                }

            } catch (error) {
                Swal.fire("Error", "Error en la conexión al servidor", "error");
            }
        });

    }

    // MOSTRAR/OCULTAR elementos según sesión activa
    const token = localStorage.getItem('jwt');

    if (token) {
        if (userIcon) userIcon.style.display = 'none';
        if (logoutBtn) logoutBtn.style.display = 'inline-block';
    } else {
        if (logoutBtn) logoutBtn.style.display = 'none';
    }

    // LOGOUT
    logoutBtn.addEventListener('click', () => {
        Swal.fire({
            title: 'Sesión cerrada',
            text: 'Has cerrado sesión exitosamente',
            icon: 'success',
            showConfirmButton: false,
            timer: 5000,
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

});
