<?php
// reset-password.php
$token = $_GET['token'] ?? '';
// Validar que el token parezca un token válido (64 caracteres hexadecimales)
if (empty($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
    // Es más seguro redirigir al home que mostrar un error.
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña | Stetson LATAM</title>
    <link rel="icon" href="/img/logo.webp" type="image/x-icon">
    <link href="/css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f1eeea;
        }
        .reset-container {
            animation: fade-in 0.3s ease-out;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="flex items-center justify-center min-h-screen">
        <div class="reset-container w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl p-8">
            <h1 class="text-[#3c3737] text-2xl font-extrabold text-center mb-6">Crea tu nueva contraseña</h1>
            <p class="text-center text-gray-600 mb-6">Por favor, introduce una nueva contraseña que tenga al menos 6 caracteres.</p>
            <form id="reset-password-form" class="space-y-4">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div>
                    <label for="password" class="sr-only">Nueva Contraseña</label>
                    <input id="password" name="password" type="password" placeholder="Nueva Contraseña" required minlength="6" class="w-full rounded-lg border border-[#e2e1df] bg-white px-4 py-3 text-base text-[#3c3737] placeholder-[#3c3737] focus:border-[#3f1e1f] focus:ring-2 focus:ring-[#3f1e1f] transition" />
                </div>

                <button type="submit" class="w-full rounded-lg bg-[#3f1e1f] text-white font-bold py-3 mt-2 hover:bg-[#2c1516] transition">Guardar Nueva Contraseña</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('reset-password-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const res = await fetch('/php/update_password', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Contraseña Actualizada!',
                        text: data.message,
                        confirmButtonText: 'Iniciar Sesión'
                    }).then(() => {
                        window.location.href = '/'; // Redirigir al inicio para abrir el modal de login
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Ocurrió un problema de conexión. Por favor, intenta de nuevo.', 'error');
            }
        });
    </script>
</body>
</html>