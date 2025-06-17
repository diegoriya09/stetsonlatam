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
                const response = await fetch('/php/login.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.token) {
                    localStorage.setItem('jwt', result.token);
                    Swal.fire({
                        title: 'Bienvenido',
                        text: 'Inicio de sesión exitoso',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "index.html"; // Redirigir al index
                    });
                } else {
                    Swal.fire("Error", result.error || "No se pudo iniciar sesión", "error");
                }
            } catch (error) {
                Swal.fire("Error", "Error del servidor", "error");
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
        timer: 2000, // ⏳ duración de 2 segundos
        timerProgressBar: true
    }).then(() => {
        localStorage.removeItem('jwt');
        location.reload();
    });
});

});
