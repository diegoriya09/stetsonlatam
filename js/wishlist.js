document.addEventListener("DOMContentLoaded", () => {
  const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

  document.querySelectorAll('.wishlist-btn').forEach(button => {
    const productId = button.dataset.id;

    if (wishlist.includes(productId)) {
      button.classList.add('active');
    }

    button.addEventListener('click', () => {
      if (wishlist.includes(productId)) {
        // Quitar de la wishlist
        const index = wishlist.indexOf(productId);
        wishlist.splice(index, 1);
        button.classList.remove('active');
      } else {
        // Añadir a wishlist
        wishlist.push(productId);
        button.classList.add('active');
      }

      localStorage.setItem('wishlist', JSON.stringify(wishlist));
    });
  });
});
