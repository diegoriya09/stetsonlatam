<?php
session_start();
// Si usas JWT, decodifica y verifica el rol aquí
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /'); // Redirige si no es admin
    exit;
}
?>

<h1>Management Panel</h1>

<!-- Formulario para agregar producto -->
<form id="add-product-form" action="add_product.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre del producto" required>
    <input type="number" name="precio" placeholder="Precio" required>
    <select name="categoria" required>
        <option value="">Categories</option>
        <option value="sombreros">Hats</option>
    </select>
    <input type="file" name="imagen" accept="image/*">
    <button type="submit">Add product</button>
</form>

<!-- Filtro por categoría -->
<form method="GET" style="margin-top:20px;">
    <select name="categoria" onchange="this.form.submit()">
        <option value="">All categories</option>
        <option value="sombreros">Hats</option>
        <option value="accesorios">Accessories</option>
    </select>
</form>

<!-- Tabla de productos -->
<table border="1" cellpadding="8" style="margin-top:20px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>