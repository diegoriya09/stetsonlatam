<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /');
    exit;
}

require '../php/conexion.php';

// Si llega por POST, procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim(strip_tags($_POST['name'] ?? ''));
    $description = trim(strip_tags($_POST['description'] ?? ''));
    $price = floatval($_POST['price'] ?? 0);
    $category = trim(strip_tags($_POST['category'] ?? ''));

    if (!$id || !$name || !$description || !$price || !$category) {
        die("All fields are required.");
    }

    // Procesar imagen si se subió
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('prod_') . '.' . $ext;
        $path = "../img/" . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
            $image = "img/" . $fileName;
        }
    }

    if ($image) {
        $stmt = $conn->prepare("UPDATE productos SET name=?, description=?, price=?, category=?, image=? WHERE id=?");
        $stmt->bind_param("ssdssi", $name, $description, $price, $category, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE productos SET name=?, description=?, price=?, category=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $category, $id);
    }

    if ($stmt->execute()) {
        header("Location: admin.php?msg=Producto+editado");
        exit;
    } else {
        die("Error while editing product: " . $stmt->error);
    }
}

// Si llega por GET, mostrar el formulario con los datos actuales
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("ID invalid.");
}
$stmt = $conn->prepare("SELECT name, description, price, category, image FROM productos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $description, $price, $category, $image);
if (!$stmt->fetch()) {
    die("Product not found.");
}
$stmt->close();
?>

<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f7f7f7;
    margin: 0;
    padding: 0;
}
.edit-container {
    background: #fff;
    max-width: 420px;
    margin: 40px auto 0 auto;
    padding: 32px 28px 24px 28px;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(60,55,55,0.10);
}
.edit-container h2 {
    text-align: center;
    color: #3c3737;
    margin-bottom: 24px;
}
.edit-container label {
    display: block;
    margin-bottom: 16px;
    color: #3c3737;
    font-weight: 500;
}
.edit-container input[type="text"],
.edit-container input[type="number"],
.edit-container select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 6px;
    font-size: 1em;
}
.edit-container input[type="file"] {
    margin-top: 6px;
}
.edit-container img {
    display: block;
    margin: 8px 0 0 0;
    border-radius: 6px;
    border: 1px solid #eee;
}
.edit-container button {
    width: 100%;
    background: #3c3737;
    color: #fff;
    border: none;
    padding: 12px 0;
    border-radius: 5px;
    font-weight: bold;
    font-size: 1em;
    cursor: pointer;
    margin-top: 10px;
    transition: background 0.2s;
}
.edit-container button:hover {
    background: #5a2323;
}
</style>
<div class="edit-container">
    <h2>Edit Product</h2>
    <form action="edit_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label>Name: <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required></label><br>
        <label>Description: <input type="text" name="description" value="<?php echo htmlspecialchars($description); ?>" required></label><br>
        <label>Price: <input type="number" name="price" value="<?php echo $price; ?>" required></label><br>
        <label>Category:
            <select name="category" required>
                <option value="">Select</option>
                <option value="hats" <?php if($category=='hats') echo 'selected'; ?>>hats</option>
            </select>
        </label><br>
        <label>Current image:
            <?php if ($image) { ?>
                <img src="../<?php echo $image; ?>" style="max-width:60px;max-height:60px;">
            <?php } else { echo "-"; } ?>
        </label><br>
        <label>New image: <input type="file" name="image"></label><br>
        <button type="submit">Save changes</button>
    </form>
</div>