  document.getElementById('sort-select').addEventListener('change', function () {
  const sortValue = this.value;
  const container = document.querySelector('.card-grid');
  const items = Array.from(container.querySelectorAll('.card-item'));

  items.sort((a, b) => {
    const nameA = a.dataset.name.toLowerCase();
    const nameB = b.dataset.name.toLowerCase();
    const priceA = parseFloat(a.dataset.price);
    const priceB = parseFloat(b.dataset.price);

    switch (sortValue) {
      case 'name-asc': return nameA.localeCompare(nameB);
      case 'name-desc': return nameB.localeCompare(nameA);
      case 'price-asc': return priceA - priceB;
      case 'price-desc': return priceB - priceA;
    }
  });

  container.innerHTML = '';
  items.forEach(item => container.appendChild(item));
});