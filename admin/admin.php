<?php
session_start();
// Si usas JWT, decodifica y verifica el rol aquí
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /'); // Redirige si no es admin
    exit;
}
?>

<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f1eeea;
    margin: 0;
    padding: 0;
}
h1 {
    text-align: center;
    color: #3c3737;
    margin-top: 30px;
}
form {
    background: #fff;
    padding: 20px 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(60,55,55,0.08);
    max-width: 500px;
    margin: 30px auto 10px auto;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
}
form input[type="text"],
form input[type="number"],
form select,
form input[type="file"] {
    flex: 1 1 180px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
form button {
    background: #3c3737;
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
}
form button:hover {
    background: #5a2323;
}
table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 2px 8px rgba(60,55,55,0.08);
}
th, td {
    padding: 12px 10px;
    border-bottom: 1px solid #eee;
    text-align: center;
}
th {
    background: #3c3737;
    color: #fff;
    font-weight: 600;
}
tr:hover {
    background: #f1eeea;
}
tbody tr:last-child td {
    border-bottom: none;
}
</style>

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