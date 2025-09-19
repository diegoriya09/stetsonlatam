<?php
// reset-password.php
$token = $_GET['token'] ?? '';
if (empty($token)) {
    die("Token no proporcionado.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Restablecer Contraseña</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
<body>
    <div class="reset-container">
        <h2>Crea tu nueva contraseña</h2>
        <form id="reset-password-form">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label>Nueva Contraseña:</label>
            <input type="password" name="password" required minlength="6">
            <button type="submit">Guardar Contraseña</button>
        </form>
    </div>

    <script>
        document.getElementById('reset-password-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            const res = await fetch('/php/update_password', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            
            if (data.success) {
                Swal.fire('¡Éxito!', data.message, 'success').then(() => {
                    // Redirigir al inicio para que pueda abrir el modal de login
                    window.location.href = '/'; 
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    </script>
</body>
</html>