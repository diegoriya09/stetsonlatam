<?php
require_once 'php/conexion.php';

$product_id = $_GET['id'] ?? null;
$producto = null;

if ($product_id) {
  $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $producto = $result->fetch_assoc();
  $stmt->close();
}

$user_id = $_SESSION['user_id'] ?? null;
if ($user_id && $producto) {
  $stmt = $conn->prepare("INSERT INTO user_visits (user_id, product_id, visited_at) VALUES (?, ?, NOW())");
  $stmt->bind_param("ii", $user_id, $product_id);
  $stmt->execute();
  $stmt->close();
}

$sizes = [];
$colors = [];

if ($product_id) {
  // Tallas disponibles
  $stmt = $conn->prepare("
    SELECT s.id, s.name 
    FROM product_sizes ps
    JOIN sizes s ON ps.size_id = s.id
    WHERE ps.product_id = ?
  ");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $sizes[] = $row;
  }
  $stmt->close();

  // Colores disponibles
  $stmt = $conn->prepare("
    SELECT c.id, c.name, c.hex 
    FROM product_colors pc
    JOIN colors c ON pc.color_id = c.id
    WHERE pc.product_id = ?
  ");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $colors[] = $row;
  }
  $stmt->close();
}

$conn->close();
?>

<html>

<head>
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
  <link
    rel="stylesheet"
    as="style"
    onload="this.rel='stylesheet'"
    href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Serif%3Awght%40400%3B500%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" />

  <title><?php echo htmlspecialchars($producto['name']); ?></title>
  <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />
  <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Noto Serif", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
      <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f4f2f0] px-10 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-4 text-[#181411]">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor"></path>
              </svg>
            </div>
            <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="index.php">Stetson Latam</a></h2>
          </div>
          <div class="flex items-center gap-9">
            <a class="text-[#181411] text-sm font-medium leading-normal" href="hats.php">Hats</a>
            <a class="text-[#181411] text-sm font-medium leading-normal" href="caps.php">Caps</a>
          </div>
        </div>
        <div class="flex flex-1 justify-end gap-8">
          <label class="flex flex-col min-w-40 !h-10 max-w-64">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
              <div
                class="text-[#887563] flex border-none bg-[#f4f2f0] items-center justify-center pl-4 rounded-l-lg border-r-0"
                data-icon="MagnifyingGlass"
                data-size="24px"
                data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                </svg>
              </div>
              <input
                placeholder="Search"
                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#181411] focus:outline-0 focus:ring-0 border-none bg-[#f4f2f0] focus:border-none h-full placeholder:text-[#887563] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                value="" />
            </div>
          </label>
          <div class="flex gap-2">
            <button
              id="logout-btn"
              style="display:none;"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#181411]" data-icon="SignOut" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M216,128a8,8,0,0,1-8,8H104v16a8,8,0,0,1-13.66,5.66l-32-32a8,8,0,0,1,0-11.32l32-32A8,8,0,0,1,104,104v16h104A8,8,0,0,1,216,128ZM128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Z"></path>
                </svg>
              </div>
            </button>
            <button
              id="user-btn"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#181411]" data-icon="User" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
                </svg>
              </div>
            </button>
            <button
              id="cart-btn"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#181411]" data-icon="ShoppingBag" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
                </svg>
              </div>
            </button>
          </div>
          <div
            class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
            style='background-image: url("img/logo.webp");'></div>
        </div>
      </header>
      <div class="px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div class="flex flex-wrap gap-2 p-4">
            <span class="text-[#887563] text-base font-medium leading-normal">/</span>
            <a class="text-[#887563] text-base font-medium leading-normal" href="hats.php">Hats</a>
            <span class="text-[#887563] text-base font-medium leading-normal">/</span>
            <span class="text-[#181411] text-base font-medium leading-normal"><?php echo htmlspecialchars($producto['name']); ?></span>
          </div>
          <div class="flex w-full grow bg-white @container p-4">
            <div class="w-full gap-1 overflow-hidden bg-white @[480px]:gap-2 aspect-[3/2] rounded-lg grid grid-cols-[2fr_1fr_1fr]">
              <div
                class="w-full bg-center bg-no-repeat bg-cover aspect-auto rounded-none row-span-2"
                style='background-image: url("<?php echo htmlspecialchars($producto["image"]); ?>");'></div>
              <!-- Si tienes más imágenes, puedes mostrarlas aquí usando $producto['images'] -->
            </div>
          </div>
          <div class="px-4 pb-3 pt-5">
            <h1 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] text-left">
              <?php echo htmlspecialchars($producto['name']); ?>
            </h1>
            <div class="flex items-center gap-4">
              <p class="text-[#7a7671] text-sm font-normal leading-normal">
                $<?php echo number_format($producto['price'], 2); ?>
              </p>
              <span class="text-[#887563] text-sm font-normal leading-normal">
                SKU: <?php echo htmlspecialchars($producto['id']); ?>
              </span>
            </div>
            <p class="text-[#181411] text-base font-normal leading-normal pt-2">
              <?php echo htmlspecialchars($producto['description']); ?>
            </p>
          </div>
          <h3 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Size</h3>
          <div class="flex flex-wrap gap-3 p-4">
            <?php foreach ($sizes as $size): ?>
              <label
                class="text-sm font-medium leading-normal flex items-center justify-center rounded-lg border border-[#e5e0dc] px-4 h-11 text-[#181411] has-[:checked]:border-[3px] has-[:checked]:px-3.5 has-[:checked]:border-[#e68019] relative cursor-pointer">
                <?php echo htmlspecialchars($size['name']); ?>
                <input type="radio" class="invisible absolute" name="size" value="<?php echo $size['id']; ?>" />
              </label>
            <?php endforeach; ?>
          </div>
          <h3 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Color</h3>
          <div class="flex flex-wrap gap-3 p-4">
            <?php foreach ($colors as $color): ?>
              <label class="flex items-center gap-2 cursor-pointer">
                <span class="inline-block w-6 h-6 rounded-full border border-[#e5e0dc]" style="background-color: <?php echo htmlspecialchars($color['hex']); ?>;" title="<?php echo htmlspecialchars($color['name']); ?>"></span>
                <input type="radio" class="invisible absolute" name="color" value="<?php echo $color['id']; ?>" />
                <span class="text-sm font-medium leading-normal text-[#181411]"><?php echo htmlspecialchars($color['name']); ?></span>
              </label>
            <?php endforeach; ?>
          </div>
          <div class="flex px-4 py-3 justify-start">
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#e68019] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em]">
              <span class="truncate">Add to Cart</span>
            </button>
          </div>
          <h3 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Customer Reviews</h3>
          <div class="flex flex-wrap gap-x-8 gap-y-6 p-4">
            <div class="flex flex-col gap-2">
              <p class="text-[#181411] text-4xl font-black leading-tight tracking-[-0.033em]">4.5</p>
              <div class="flex gap-0.5">
                <div class="text-[#181411]" data-icon="Star" data-size="18px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="18px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="18px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="18px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="18px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M239.2,97.29a16,16,0,0,0-13.81-11L166,81.17,142.72,25.81h0a15.95,15.95,0,0,0-29.44,0L90.07,81.17,30.61,86.32a16,16,0,0,0-9.11,28.06L66.61,153.8,53.09,212.34a16,16,0,0,0,23.84,17.34l51-31,51.11,31a16,16,0,0,0,23.84-17.34l-13.51-58.6,45.1-39.36A16,16,0,0,0,239.2,97.29Zm-15.22,5-45.1,39.36a16,16,0,0,0-5.08,15.71L187.35,216v0l-51.07-31a15.9,15.9,0,0,0-16.54,0l-51,31h0L82.2,157.4a16,16,0,0,0-5.08-15.71L32,102.35a.37.37,0,0,1,0-.09l59.44-5.14a16,16,0,0,0,13.35-9.75L128,32.08l23.2,55.29a16,16,0,0,0,13.35,9.75L224,102.26S224,102.32,224,102.33Z"></path>
                  </svg>
                </div>
              </div>
              <p class="text-[#181411] text-base font-normal leading-normal">125 reviews</p>
            </div>
            <div class="grid min-w-[200px] max-w-[400px] flex-1 grid-cols-[20px_1fr_40px] items-center gap-y-3">
              <p class="text-[#181411] text-sm font-normal leading-normal">5</p>
              <div class="flex h-2 flex-1 overflow-hidden rounded-full bg-[#e5e0dc]">
                <div class="rounded-full bg-[#181411]" style="width: 40%;"></div>
              </div>
              <p class="text-[#887563] text-sm font-normal leading-normal text-right">40%</p>
              <p class="text-[#181411] text-sm font-normal leading-normal">4</p>
              <div class="flex h-2 flex-1 overflow-hidden rounded-full bg-[#e5e0dc]">
                <div class="rounded-full bg-[#181411]" style="width: 30%;"></div>
              </div>
              <p class="text-[#887563] text-sm font-normal leading-normal text-right">30%</p>
              <p class="text-[#181411] text-sm font-normal leading-normal">3</p>
              <div class="flex h-2 flex-1 overflow-hidden rounded-full bg-[#e5e0dc]">
                <div class="rounded-full bg-[#181411]" style="width: 15%;"></div>
              </div>
              <p class="text-[#887563] text-sm font-normal leading-normal text-right">15%</p>
              <p class="text-[#181411] text-sm font-normal leading-normal">2</p>
              <div class="flex h-2 flex-1 overflow-hidden rounded-full bg-[#e5e0dc]">
                <div class="rounded-full bg-[#181411]" style="width: 10%;"></div>
              </div>
              <p class="text-[#887563] text-sm font-normal leading-normal text-right">10%</p>
              <p class="text-[#181411] text-sm font-normal leading-normal">1</p>
              <div class="flex h-2 flex-1 overflow-hidden rounded-full bg-[#e5e0dc]">
                <div class="rounded-full bg-[#181411]" style="width: 5%;"></div>
              </div>
              <p class="text-[#887563] text-sm font-normal leading-normal text-right">5%</p>
            </div>
          </div>
          <div class="flex flex-col gap-8 overflow-x-hidden bg-white p-4">
            <div class="flex flex-col gap-3 bg-white">
              <div class="flex items-center gap-3">
                <div
                  class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCjWN5LXiCDDUQ55l6Q4U1ENzEMqCeGDG8j09kQshxVRygyOX6Wq5WGqXExYFilVLz9l19cqNuMir6p4AzHs6m5ALYg9HW1p-9sUpAf6mfz7TIgc1TOzLeo_yfpbMlqiX2A4dEc8GzV6GLnwSlAjtY1BZFdfh6o7F9vUPDXFPjsHhsYTwSkhNsM0ZRsvdRf5OXMTaBBDlBfbJj_OKYr6djhzSSpDnNZnOmQyFK1WNz0lFr1ehI7AP8sasx3RTHWzRsA8kQfyHT6mAvH");'></div>
                <div class="flex-1">
                  <p class="text-[#181411] text-base font-medium leading-normal">Ethan Vargas</p>
                  <p class="text-[#887563] text-sm font-normal leading-normal">2023-08-15</p>
                </div>
              </div>
              <div class="flex gap-0.5">
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
              </div>
              <p class="text-[#181411] text-base font-normal leading-normal">
                This hat is amazing! The quality is top-notch, and it fits perfectly. I've received so many compliments on it. Highly recommend!
              </p>
              <div class="flex gap-9 text-[#887563]">
                <button class="flex items-center gap-2">
                  <div class="text-inherit" data-icon="ThumbsUp" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234,80.12A24,24,0,0,0,216,72H160V56a40,40,0,0,0-40-40,8,8,0,0,0-7.16,4.42L75.06,96H32a16,16,0,0,0-16,16v88a16,16,0,0,0,16,16H204a24,24,0,0,0,23.82-21l12-96A24,24,0,0,0,234,80.12ZM32,112H72v88H32ZM223.94,97l-12,96a8,8,0,0,1-7.94,7H88V105.89l36.71-73.43A24,24,0,0,1,144,56V80a8,8,0,0,0,8,8h64a8,8,0,0,1,7.94,9Z"></path>
                    </svg>
                  </div>
                  <p class="text-inherit">25</p>
                </button>
                <button class="flex items-center gap-2">
                  <div class="text-inherit" data-icon="ThumbsDown" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M239.82,157l-12-96A24,24,0,0,0,204,40H32A16,16,0,0,0,16,56v88a16,16,0,0,0,16,16H75.06l37.78,75.58A8,8,0,0,0,120,240a40,40,0,0,0,40-40V184h56a24,24,0,0,0,23.82-27ZM72,144H32V56H72Zm150,21.29a7.88,7.88,0,0,1-6,2.71H152a8,8,0,0,0-8,8v24a24,24,0,0,1-19.29,23.54L88,150.11V56H204a8,8,0,0,1,7.94,7l12,96A7.87,7.87,0,0,1,222,165.29Z"></path>
                    </svg>
                  </div>
                  <p class="text-inherit">5</p>
                </button>
              </div>
            </div>
            <div class="flex flex-col gap-3 bg-white">
              <div class="flex items-center gap-3">
                <div
                  class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBSYVz1vll2dyADzJorwBoHzhmOZIsnGnod1QCxNhPbivs3njaDibNVQO46KCTUuP48DnR9lCfamgj2hXakaH0Ux2VFlqis1JQK1vFGECyYuYDFj_jLbjPpJjLMbp1dysKO5OzNufS2zQofm6V4NnFXVquRsqgbGuMrimXBd-2bksyiiaOqUAj8BU1zKwkbqdwHxdYvN2zT8AFNu4rkpKUYQdLVrabVOhIUs7iV1Pt--LJPs67AUz08pKTSLXQYCtSxlNDQ_zy77LNE");'></div>
                <div class="flex-1">
                  <p class="text-[#181411] text-base font-medium leading-normal">Sophia Costa</p>
                  <p class="text-[#887563] text-sm font-normal leading-normal">2023-07-22</p>
                </div>
              </div>
              <div class="flex gap-0.5">
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#181411]" data-icon="Star" data-size="20px" data-weight="fill">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"></path>
                  </svg>
                </div>
                <div class="text-[#cec4bb]" data-icon="Star" data-size="20px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M239.2,97.29a16,16,0,0,0-13.81-11L166,81.17,142.72,25.81h0a15.95,15.95,0,0,0-29.44,0L90.07,81.17,30.61,86.32a16,16,0,0,0-9.11,28.06L66.61,153.8,53.09,212.34a16,16,0,0,0,23.84,17.34l51-31,51.11,31a16,16,0,0,0,23.84-17.34l-13.51-58.6,45.1-39.36A16,16,0,0,0,239.2,97.29Zm-15.22,5-45.1,39.36a16,16,0,0,0-5.08,15.71L187.35,216v0l-51.07-31a15.9,15.9,0,0,0-16.54,0l-51,31h0L82.2,157.4a16,16,0,0,0-5.08-15.71L32,102.35a.37.37,0,0,1,0-.09l59.44-5.14a16,16,0,0,0,13.35-9.75L128,32.08l23.2,55.29a16,16,0,0,0,13.35,9.75L224,102.26S224,102.32,224,102.33Z"></path>
                  </svg>
                </div>
              </div>
              <p class="text-[#181411] text-base font-normal leading-normal">
                Great hat, but the brim is a bit wider than I expected. Still, it's a stylish and well-made product.
              </p>
              <div class="flex gap-9 text-[#887563]">
                <button class="flex items-center gap-2">
                  <div class="text-inherit" data-icon="ThumbsUp" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234,80.12A24,24,0,0,0,216,72H160V56a40,40,0,0,0-40-40,8,8,0,0,0-7.16,4.42L75.06,96H32a16,16,0,0,0-16,16v88a16,16,0,0,0,16,16H204a24,24,0,0,0,23.82-21l12-96A24,24,0,0,0,234,80.12ZM32,112H72v88H32ZM223.94,97l-12,96a8,8,0,0,1-7.94,7H88V105.89l36.71-73.43A24,24,0,0,1,144,56V80a8,8,0,0,0,8,8h64a8,8,0,0,1,7.94,9Z"></path>
                    </svg>
                  </div>
                  <p class="text-inherit">18</p>
                </button>
                <button class="flex items-center gap-2">
                  <div class="text-inherit" data-icon="ThumbsDown" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M239.82,157l-12-96A24,24,0,0,0,204,40H32A16,16,0,0,0,16,56v88a16,16,0,0,0,16,16H75.06l37.78,75.58A8,8,0,0,0,120,240a40,40,0,0,0,40-40V184h56a24,24,0,0,0,23.82-27ZM72,144H32V56H72Zm150,21.29a7.88,7.88,0,0,1-6,2.71H152a8,8,0,0,0-8,8v24a24,24,0,0,1-19.29,23.54L88,150.11V56H204a8,8,0,0,1,7.94,7l12,96A7.87,7.87,0,0,1,222,165.29Z"></path>
                    </svg>
                  </div>
                  <p class="text-inherit">3</p>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="flex justify-center">
        <div class="flex max-w-[960px] flex-1 flex-col">
          <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
            <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
              <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Contact Us</a>
              <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">FAQs</a>
              <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Shipping &amp; Returns</a>
              <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Privacy Policy</a>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
              <a href="#">
                <div class="text-[#887563]" data-icon="InstagramLogo" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path>
                  </svg>
                </div>
              </a>
              <a href="#">
                <div class="text-[#887563]" data-icon="FacebookLogo" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"></path>
                  </svg>
                </div>
              </a>
              <a href="#">
                <div class="text-[#887563]" data-icon="TwitterLogo" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M247.39,68.94A8,8,0,0,0,240,64H209.57A48.66,48.66,0,0,0,168.1,40a46.91,46.91,0,0,0-33.75,13.7A47.9,47.9,0,0,0,120,88v6.09C79.74,83.47,46.81,50.72,46.46,50.37a8,8,0,0,0-13.65,4.92c-4.31,47.79,9.57,79.77,22,98.18a110.93,110.93,0,0,0,21.88,24.2c-15.23,17.53-39.21,26.74-39.47,26.84a8,8,0,0,0-3.85,11.93c.75,1.12,3.75,5.05,11.08,8.72C53.51,229.7,65.48,232,80,232c70.67,0,129.72-54.42,135.75-124.44l29.91-29.9A8,8,0,0,0,247.39,68.94Zm-45,29.41a8,8,0,0,0-2.32,5.14C196,166.58,143.28,216,80,216c-10.56,0-18-1.4-23.22-3.08,11.51-6.25,27.56-17,37.88-32.48A8,8,0,0,0,92,169.08c-.47-.27-43.91-26.34-44-96,16,13,45.25,33.17,78.67,38.79A8,8,0,0,0,136,104V88a32,32,0,0,1,9.6-22.92A30.94,30.94,0,0,1,167.9,56c12.66.16,24.49,7.88,29.44,19.21A8,8,0,0,0,204.67,80h16Z"></path>
                  </svg>
                </div>
              </a>
            </div>
            <p class="text-[#887563] text-base font-normal leading-normal">© 2024 Stetson Latin America. All rights reserved.</p>
          </footer>
        </div>
      </footer>
    </div>
  </div>
  <?php include 'modal.php'; ?>
  <script src="js/index.js?v=<?php echo time(); ?>"></script>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
</body>

</html>