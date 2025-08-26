<?php
if (session_status() !== PHP_SESSION_ACTIVE)
    session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<html>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link rel="stylesheet" as="style" onload="this.rel='stylesheet'"
        href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

    <title>Iniciar sesión / Registrarse</title>
    <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
    <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>



<div id="user-modal"
    style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.35);">
    <div class="flex items-center justify-center min-h-screen">
        <div class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl p-8 animate-fade-in">
            <button type="button" class="close absolute top-4 right-4 text-3xl text-[#b0a99f] hover:text-[#e68019] transition-colors" style="background:none;border:none;cursor:pointer;z-index:10001;">&times;</button>
            <h1 class="text-[#3c3737] text-2xl font-extrabold text-center mb-2">Bienvenido a Stetson Latam</h1>
                    <div class="flex justify-center mb-6">
                        <button id="switch-to-login" class="px-4 py-2 font-bold border-b-2 border-[#3c3737] text-[#3c3737] focus:outline-none transition-colors">Iniciar sesión</button>
                        <button id="switch-to-register" class="px-4 py-2 font-bold border-b-2 border-transparent text-[#7a7671] focus:outline-none transition-colors">Registrarse</button>
                    </div>
                    <script>
                        // Corrige el subrayado de la pestaña activa
                        document.addEventListener('DOMContentLoaded', function() {
                            const loginTab = document.getElementById('switch-to-login');
                            const registerTab = document.getElementById('switch-to-register');
                            const loginForm = document.getElementById('login-form');
                            const registerForm = document.getElementById('register-form');
                            if (loginTab && registerTab && loginForm && registerForm) {
                                loginTab.addEventListener('click', function() {
                                    loginTab.classList.add('border-[#3c3737]', 'text-[#3c3737]');
                                    loginTab.classList.remove('border-transparent', 'text-[#3c3737]');
                                    registerTab.classList.remove('border-[#3c3737]', 'text-[#3c3737]');
                                    registerTab.classList.add('border-transparent', 'text-[#3c3737]');
                                });
                                registerTab.addEventListener('click', function() {
                                    registerTab.classList.add('border-[#3c3737]', 'text-[#3c3737]');
                                    registerTab.classList.remove('border-transparent', 'text-[#3c3737]');
                                    loginTab.classList.remove('border-[#3c3737]', 'text-[#3c3737]');
                                    loginTab.classList.add('border-transparent', 'text-[#3c3737]');
                                });
                            }
                        });
                    </script>
            <!-- Login Form -->
            <div id="login-form" style="display:block;">
                <form id="login-form-inner" autocomplete="on" class="space-y-4">
                    <input name="email" type="email" placeholder="Email" required class="w-full rounded-lg border border-[#e2e1df] bg-white px-4 py-3 text-base text-[#3c3737] placeholder-[#3c3737] focus:border-[#3f1e1f] focus:ring-2 focus:ring-[#3f1e1f] transition" />
                    <input name="password" type="password" placeholder="Contraseña" required class="w-full rounded-lg border border-[#e2e1df] bg-white px-4 py-3 text-base text-[#3c3737] placeholder-[#3c3737] focus:border-[#3f1e1f] focus:ring-2 focus:ring-[#3f1e1f] transition" />
                    <button type="submit" class="w-full rounded-lg bg-[#3f1e1f] text-white font-bold py-3 mt-2 hover:bg-[#2c1516] transition">Iniciar sesión</button>
                </form>
            </div>
            <!-- Register Form -->
            <div id="register-form" style="display:none;">
                <form id="register-form-inner" autocomplete="on" class="space-y-4">
                    <input name="name" type="text" placeholder="Nombre" required class="w-full rounded-lg border border-[#e2e1df] bg-white px-4 py-3 text-base text-[#3c3737] placeholder-[#3c3737] focus:border-[#3f1e1f] focus:ring-2 focus:ring-[#3f1e1f] transition" />
                    <input name="email" type="email" placeholder="Email" required class="w-full rounded-lg border border-[#e2e1df] bg-white px-4 py-3 text-base text-[#3c3737] placeholder-[#3c3737] focus:border-[#3f1e1f] focus:ring-2 focus:ring-[#3f1e1f] transition" />
                    <input name="password" type="password" placeholder="Contraseña" required class="w-full rounded-lg border border-[#e2e1df] bg-white px-4 py-3 text-base text-[#3c3737] placeholder-[#3c3737] focus:border-[#3f1e1f] focus:ring-2 focus:ring-[#3f1e1f] transition" />
                    <button type="submit" class="w-full rounded-lg bg-[#3f1e1f] text-white font-bold py-3 mt-2 hover:bg-[#2c1516] transition">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

</html>