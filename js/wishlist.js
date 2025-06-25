document.querySelectorAll('.wishlist-btn').forEach(button => {
    button.addEventListener('click', () => {
        const product = {
            id: button.dataset.id,
            name: button.dataset.name,
            price: button.dataset.price,
            image: button.dataset.image
        };

        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const exists = wishlist.find(p => p.id === product.id);

        if (!exists) {
            wishlist.push(product);
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            alert('Añadido a tu wishlist');
        } else {
            alert('Ya está en tu wishlist');
        }
    });
});

const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
const container = document.getElementById('wishlist-items');

if (wishlist.length === 0) {
    container.innerHTML = "<p>You have no favorite products yet.</p>";
} else {
    wishlist.forEach(p => {
        container.innerHTML += `
        <article class="card-item">
          <img src="${p.image}" alt="${p.name}">
          <h3>${p.name}</h3>
          <p>$${parseFloat(p.price).toLocaleString()}</p>
          <button onclick="removeFromWishlist('${p.id}')">Remove</button>
        </article>`;
    });
}

function removeFromWishlist(id) {
    const newWishlist = wishlist.filter(p => p.id !== id);
    localStorage.setItem('wishlist', JSON.stringify(newWishlist));
    location.reload();
}