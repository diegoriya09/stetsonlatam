// js/index.js (CÓDIGO LIMPIO Y COMPLETO)

document.addEventListener('DOMContentLoaded', () => {
    // Este archivo ahora solo se encarga de los botones del header
    const userBtn = document.getElementById('user-btn');
    const cartBtn = document.getElementById('cart-btn');

    // El botón de usuario ahora simplemente llama a la función global para abrir el modal
    if (userBtn) {
        userBtn.addEventListener('click', () => {
            const jwt = localStorage.getItem('jwt');
            if (jwt) {
                window.location.href = 'profile'; // O a la página de perfil del usuario
            } else {
                // Llama a la función global que definiremos en auth.js
                if (typeof openAuthModal === 'function') {
                    openAuthModal(); 
                }
            }
        });
    }

    if (cartBtn) {
        cartBtn.addEventListener('click', () => {
            window.location.href = 'cart';
        });
    }
    
});