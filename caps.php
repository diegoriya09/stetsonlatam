<?php
require_once 'php/conexion.php';

// Obtener colores disponibles para sombreros
$sql_colores = "SELECT DISTINCT c.id, c.name, c.hex 
                FROM colors c 
                INNER JOIN product_colors pc ON c.id = pc.color_id 
                INNER JOIN productos p ON pc.product_id = p.id 
                WHERE p.category = 'caps'";
$result_colores = $conn->query($sql_colores);
$colores = [];
while ($row = $result_colores->fetch_assoc()) {
  $colores[] = $row;
}

// Obtener tallas disponibles para sombreros
$sql_tallas = "SELECT DISTINCT s.id, s.name 
               FROM sizes s
               INNER JOIN product_sizes ps ON s.id = ps.size_id
               INNER JOIN productos p ON ps.product_id = p.id
               WHERE p.category = 'caps'";
$result_tallas = $conn->query($sql_tallas);
$tallas = [];
while ($row = $result_tallas->fetch_assoc()) {
  $tallas[] = $row;
}

// Obtener filtros del GET
$color_ids = $_GET['colors'] ?? [];
$talla_ids = $_GET['sizes'] ?? [];

// Construir consulta con filtros
$sql = "SELECT DISTINCT p.* FROM productos p";
$joins = [];
$where = ["p.category = 'caps'"];
$params = [];
$types = '';

if (!empty($color_ids)) {
  $joins[] = "INNER JOIN product_colors pc ON p.id = pc.product_id";
  $placeholders = implode(',', array_fill(0, count($color_ids), '?'));
  $where[] = "pc.color_id IN ($placeholders)";
  $types .= str_repeat('i', count($color_ids));
  $params = array_merge($params, $color_ids);
}

if (!empty($talla_ids)) {
  $joins[] = "INNER JOIN product_sizes ps ON p.id = ps.product_id";
  $placeholders = implode(',', array_fill(0, count($talla_ids), '?'));
  $where[] = "ps.size_id IN ($placeholders)";
  $types .= str_repeat('i', count($talla_ids));
  $params = array_merge($params, $talla_ids);
}

if (!empty($joins)) {
  $sql .= ' ' . implode(' ', $joins);
}
if (!empty($where)) {
  $sql .= ' WHERE ' . implode(' AND ', $where);
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$productos = [];
while ($row = $result->fetch_assoc()) {
  $productos[] = $row;
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
    href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

  <title>Caps | Stetson Latam</title>
  <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
      <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f3f2f2] px-10 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-4 text-[#151514]">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor"></path>
              </svg>
            </div>
            <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="index.php">Stetson Latam</a></h2>
          </div>
          <div class="flex items-center gap-9">
            <a class="text-[#151514] text-sm font-medium leading-normal" href="hats.php">Hats</a>
            <a class="text-[#151514] text-sm font-medium leading-normal" href="caps.php">Caps</a>
          </div>
        </div>
        <div class="flex flex-1 justify-end gap-8">
          <label class="flex flex-col min-w-40 !h-10 max-w-64">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
              <div
                class="text-[#7a7671] flex border-none bg-[#f3f2f2] items-center justify-center pl-4 rounded-l-lg border-r-0"
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
                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#151514] focus:outline-0 focus:ring-0 border-none bg-[#f3f2f2] focus:border-none h-full placeholder:text-[#7a7671] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
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
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f3f2f2] text-[#151514] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#151514]" data-icon="User" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
                </svg>
              </div>
            </button>
            <button
              id="cart-btn"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f3f2f2] text-[#151514] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#151514]" data-icon="ShoppingBag" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
                </svg>
              </div>
            </button>
          </div>
        </div>
      </header>
      <div class="px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div class="flex flex-wrap gap-2 p-4">
            <a class="text-[#7a7671] text-base font-medium leading-normal" href="index.php">Home</a>
            <span class="text-[#7a7671] text-base font-medium leading-normal">/</span>
            <span class="text-[#151514] text-base font-medium leading-normal">Hats</span>
          </div>
          <div class="flex flex-wrap justify-between gap-3 p-4">
            <p class="text-[#151514] tracking-light text-[32px] font-bold leading-tight min-w-72">Hats</p>
          </div>
          <div class="flex gap-3 p-3 flex-wrap pr-4">
            <button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-[#f3f2f2] pl-4 pr-2">
              <p class="text-[#151514] text-sm font-medium leading-normal">Category</p>
              <div class="text-[#151514]" data-icon="CaretDown" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                </svg>
              </div>
            </button>
            <button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-[#f3f2f2] pl-4 pr-2">
              <p class="text-[#151514] text-sm font-medium leading-normal">Size</p>
              <div class="text-[#151514]" data-icon="CaretDown" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                </svg>
              </div>
            </button>
            <button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-[#f3f2f2] pl-4 pr-2">
              <p class="text-[#151514] text-sm font-medium leading-normal">Price</p>
              <div class="text-[#151514]" data-icon="CaretDown" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                </svg>
              </div>
            </button>
          </div>
          <h2 class="text-[#151514] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Recommended for You</h2>
          <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
            <div class="flex items-stretch p-4 gap-3">
              <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                <div
                  class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBLF3OpOUwEo6XqLl-W6k1VAuyuWmA-nM3PVz4bG61K73qeAN_lJukhrVgtuqY2zyoc4ReH1xOfqzGt-H7h1oAhKfjnSMb64_zYZ81b7xrBn36tvsibpdjLl2w8Pm5g6ocyxJY0jPdK4JLynV5PNgeJQ0j4fcb96wX9k3xxeex8MMcSMxEoTrc5wivlHZ4diClCrBv3PotQz9zV-UkDO3uYXA4OmPdT5nPKB_nfXxwnWp_g1XStSQ30y2V2-y0h3Ig47dZi8NN7l6gn");'></div>
                <div>
                  <p class="text-[#151514] text-base font-medium leading-normal">The Classic Fedora</p>
                  <p class="text-[#7a7671] text-sm font-normal leading-normal">$120</p>
                </div>
              </div>
              <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                <div
                  class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBfRQFhcn2sPrHJdg51hp2OKZtzgxsjmQ8xAFqRvDCnV8jklWh_pLQgl7v43BBVSWvHP3Hq0VrnvAiyPrVGIXeJCP6q66O0IZ_aN7lZrfeGYBy87HafnyTzjuutANGV2iz0WGtHtlhoKM9fFBTFNFf4k5CfhzDXm9k4HMcvz6g_zQEqo5TV4jZbA2h1GPLaG_MC-1x-T1KeDYKhrUi0uLe82M3R54-Acx6TGqQEQ46dz-l5IWPtLCiDsU-VqdsX-KUler70jZULLoAe");'></div>
                <div>
                  <p class="text-[#151514] text-base font-medium leading-normal">The Outback Hat</p>
                  <p class="text-[#7a7671] text-sm font-normal leading-normal">$90</p>
                </div>
              </div>
              <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                <div
                  class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCiXLKGurA3K7yB_vNRFffAf17kL79vErTXVhCOIIvLUkCmiGxKImuMfisiNh_x9KuEjEaWvao72bnfPZz-g4zTBx6-FsUFg5Sf5M8DF3NET20SJ3nwU6OauSfr9EDaBRpc9wIdCCT6I4wNPztUanNkWKSy4MZLUEL2TXRADGMUrrNROWf9LWiir7nrbWagGROMe7HlBTNh-77XRvas4KsogeoSU3N9gbn_2mCFpj8PKroOj-9V6T90UhE7wAVoN0UwzH-m3d6zMTaV");'></div>
                <div>
                  <p class="text-[#151514] text-base font-medium leading-normal">The Newsboy Cap</p>
                  <p class="text-[#7a7671] text-sm font-normal leading-normal">$50</p>
                </div>
              </div>
              <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                <div
                  class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBwTnuWyqc0iIevjfWcdVP7bvTfPLnU4LPIF1xwUljjHiFaQBv5nm09q5M96myPl18JRX5tF9WD3AyTo2GSCS_883PUF9SV_HowGXEDE2GKqFCi5Q8KM36gn7XuDHf7sSQatEYuv21l-w2Te9BfNEwcNjnPdbsoa23dhdSR_Y0EpBt6_BpBgLRodVDJToHl6-QSWUFvBj9sHLGC9r4p8m11CK04LhLtzYobtLIJ9KjF1hC8wuKyhA-kL-2dZ0gmJKAicavB75J5vCMl");'></div>
                <div>
                  <p class="text-[#151514] text-base font-medium leading-normal">The Panama Hat</p>
                  <p class="text-[#7a7671] text-sm font-normal leading-normal">$150</p>
                </div>
              </div>
              <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                <div
                  class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDKzs_6xCR7ySKOC6yBPfDb3C5TQpVWwQ_2XPFHzUGMe9DOCa-3tDhGwBDcaBUQlVWUxLMdjMX-jZne7GJUvs8UyCGlSUv5c2eLnb5IisXUkKa5fnESHfSnfKOQLzID3B8Z7uK92TALac5a_NtPSxPB83uPLFPU-q1Z4LJRUim6DigaXOTtMvOhmg_W5qFALokn5At9PYcQeCerIdVRciBeO8oI89Cdv0fwzDfISJnO_Ztvj2y6luxd3MrzGLTKego6bkxLgXYk0-t-");'></div>
                <div>
                  <p class="text-[#151514] text-base font-medium leading-normal">The Cowboy Hat</p>
                  <p class="text-[#7a7671] text-sm font-normal leading-normal">$100</p>
                </div>
              </div>
              <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                <div
                  class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDwoTA-7IwowGAi_spRDKhm4qaRyKNR4Q0cOqGlA2K8r5vH1WmwKIaUPSNH6EnSAOOs7xVKcopZTxnV3zoTBWYCM86hT8T2lKQKKBxqdoYzqMnLjeywUsDyH1X3F2M-tRKtZ_-vW2GwA_hEYpYr4tmiZ2d2mFcFy9tx0k2Ey4ZtoqAXS4SJ4AU9AqMmiNMQgLZN_-_T5ioc97lYJ8McxieJjoUIXC2HEHzKnvVWH20J_7N8-X-YBjKrQ9ilIsu_mDN-oi-hSvcstj_d");'></div>
                <div>
                  <p class="text-[#151514] text-base font-medium leading-normal">The Beanie</p>
                  <p class="text-[#7a7671] text-sm font-normal leading-normal">$40</p>
                </div>
              </div>
            </div>
          </div>
          <div class="flex justify-between gap-2 px-4 py-3">
            <div class="flex gap-2">
              <button class="p-2 text-[#151514]">
                <div class="text-[#151514]" data-icon="SortAscending" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M128,128a8,8,0,0,1-8,8H48a8,8,0,0,1,0-16h72A8,8,0,0,1,128,128ZM48,72H184a8,8,0,0,0,0-16H48a8,8,0,0,0,0,16Zm56,112H48a8,8,0,0,0,0,16h56a8,8,0,0,0,0-16Zm125.66-21.66a8,8,0,0,0-11.32,0L192,188.69V112a8,8,0,0,0-16,0v76.69l-26.34-26.35a8,8,0,0,0-11.32,11.32l40,40a8,8,0,0,0,11.32,0l40-40A8,8,0,0,0,229.66,162.34Z"></path>
                  </svg>
                </div>
              </button>
            </div>
          </div>
          <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3 p-4">
            <div class="flex flex-col gap-3 pb-3">
              <div
                class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCsKTk0C50uonTaF0PAO1hfMgXjDT7kyu4QVZUmojg_Xy4kGL-7FdOxIddnkIcJktpCpQ8VfnPbmKpdkxTYmlNNi9zDWO8P4CDPDo57IoQ35MQlJvDQG7_2XhV4nW0FSC66WNLXpy7fpEso6WwS59O0zxoKHyiWCzH0sXKSn87IDlapkq-RKNTzKA1uWt4bxIAC_TTQcxe8huhs-1gJR3_iMHu8N3tfrmoG_hKuKfYwSP_ufpVi8DKtUcABgwpbFEbKotbXt4PMVmTH");'></div>
              <div>
                <p class="text-[#151514] text-base font-medium leading-normal">The Classic Fedora</p>
                <p class="text-[#7a7671] text-sm font-normal leading-normal">$120</p>
              </div>
            </div>
            <div class="flex flex-col gap-3 pb-3">
              <div
                class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBKzfMOcs7y-692QI4wt9H9j8QRZ-cgEfK5LTp2d36KMasVA4UX5aoVsiilc2Jx474QLwZgj5XqguCNQR8WJdNICyesqXA9scbdXvT7-14zmT22cnZENZQROzi5Talwjo1CirtE13VTDvtIg43bXKawv_dyEOq6IY0nB3rrZSTHNQoe5ebKnVgILlop0XRn3LtQgnmWvRrCOHjFV5gsknIuz_aJlmANSaogLq0rcfLfKrmr6tcEQfnAY7b87yiiw7hBA1ddFjKTOLlJ");'></div>
              <div>
                <p class="text-[#151514] text-base font-medium leading-normal">The Outback Hat</p>
                <p class="text-[#7a7671] text-sm font-normal leading-normal">$90</p>
              </div>
            </div>
            <div class="flex flex-col gap-3 pb-3">
              <div
                class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAp0J06MZ-MdsynSsewAlSF0ICaa-aGjjaYrzQKJ5EZvFSH7bUG7iIvGT2OnkFtBWMdXhnHW5oQ-Pi9xjuLOneHt9VAuNMED647j-LdEkiD6W_czYDb_3muSY8PlXfrxKKxNcUrMdpAuta5v7rzZle1UFdhAlOW6xW4s9mlpzPFtfIf7RngNNOmkZaT6KM8ci4xuS00T1TEuRco4CLX6IZdDPQJL0Pb5OqmtLsnkbeOhrXZQnzzSH1UiNqrj8p9zzgKM6pZ1LSEhpNz");'></div>
              <div>
                <p class="text-[#151514] text-base font-medium leading-normal">The Newsboy Cap</p>
                <p class="text-[#7a7671] text-sm font-normal leading-normal">$50</p>
              </div>
            </div>
            <div class="flex flex-col gap-3 pb-3">
              <div
                class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCSOilY4tb42dQHDWt50zXfsXIchXsCL6CLofSxd8AgMDFa4zQroslDtej_vpVuN2YwOfQIU-HAJz_yG6dM9j2SPWownrLXJNaPg3pXnhWfznA4f-N_d2loYR-qZDxLQpj-GIOmovsDpdhvyEuJhDSaNs9R0PxbBCK3FzJSkDt4b_Jctj276ZvmvcA021kHUVMfx4bgQMDW514ArRB3QksGFGKedAljwu1sw4PY5zidj1Y41Y6nK8WaU5ywoVZlykBbpifue-6jJEpJ");'></div>
              <div>
                <p class="text-[#151514] text-base font-medium leading-normal">The Panama Hat</p>
                <p class="text-[#7a7671] text-sm font-normal leading-normal">$150</p>
              </div>
            </div>
            <div class="flex flex-col gap-3 pb-3">
              <div
                class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBhU7uJmteNSzB_BcvUQ41V-_3fTFGCy956orV_Fn3TEc_vxr4-35IzrvmsbMKHfDGI1w_a9EDlWaaJSdpnqo7ca1AiA1nFA2mpq8okuLBOT8hAK1t4U_KRrKuaajyRzJnfspRIg1qHfxxquprMJI3HGoVxdP6AnZSHtcdL7EEEr3Ca8r7E2-Toe_Om40-fpfYm8DDcrQQRnMi3bXogrtmAX7amHgyg9hkGwSnUHt7R7EEWGCxCvZOkDoYYxRqGa5p4HR72y3nYdTyg");'></div>
              <div>
                <p class="text-[#151514] text-base font-medium leading-normal">The Cowboy Hat</p>
                <p class="text-[#7a7671] text-sm font-normal leading-normal">$100</p>
              </div>
            </div>
            <div class="flex flex-col gap-3 pb-3">
              <div
                class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA1LfUhgO9vQYVZ_2wDXlxkbkT6pG4weuu1-bPlbocFh1WtTNo0WcjTVZ1HlL2AfcYXZqgMtPzqzW3e_ahEBis0NplnKI_1G2GSd2y_MCU8cr79fXjPoALyMlr2zKjKhBBQldsT_Do9dYfUjF6viHs0FBL52xAhl-0OSjqo9N-PnjgZv3S5yw7Tm3Kp6Dc3Br6AquwpzIQ6LEEZs7fNN3lwgdgLDcdytb8Jj8ShfsqFDC1JldRkfVu-5DuWGFYlnHfIoE6BHl3lCUaU");'></div>
              <div>
                <p class="text-[#151514] text-base font-medium leading-normal">The Beanie</p>
                <p class="text-[#7a7671] text-sm font-normal leading-normal">$40</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="flex justify-center">
        <div class="flex max-w-[960px] flex-1 flex-col">
          <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
            <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
              <a class="text-[#7a7671] text-base font-normal leading-normal min-w-40" href="#">About</a>
              <a class="text-[#7a7671] text-base font-normal leading-normal min-w-40" href="#">Contact</a>
              <a class="text-[#7a7671] text-base font-normal leading-normal min-w-40" href="#">FAQ</a>
              <a class="text-[#7a7671] text-base font-normal leading-normal min-w-40" href="#">Shipping &amp; Returns</a>
              <a class="text-[#7a7671] text-base font-normal leading-normal min-w-40" href="#">Privacy Policy</a>
              <a class="text-[#7a7671] text-base font-normal leading-normal min-w-40" href="#">Terms of Service</a>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
              <a href="#">
                <div class="text-[#7a7671]" data-icon="TwitterLogo" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M247.39,68.94A8,8,0,0,0,240,64H209.57A48.66,48.66,0,0,0,168.1,40a46.91,46.91,0,0,0-33.75,13.7A47.9,47.9,0,0,0,120,88v6.09C79.74,83.47,46.81,50.72,46.46,50.37a8,8,0,0,0-13.65,4.92c-4.31,47.79,9.57,79.77,22,98.18a110.93,110.93,0,0,0,21.88,24.2c-15.23,17.53-39.21,26.74-39.47,26.84a8,8,0,0,0-3.85,11.93c.75,1.12,3.75,5.05,11.08,8.72C53.51,229.7,65.48,232,80,232c70.67,0,129.72-54.42,135.75-124.44l29.91-29.9A8,8,0,0,0,247.39,68.94Zm-45,29.41a8,8,0,0,0-2.32,5.14C196,166.58,143.28,216,80,216c-10.56,0-18-1.4-23.22-3.08,11.51-6.25,27.56-17,37.88-32.48A8,8,0,0,0,92,169.08c-.47-.27-43.91-26.34-44-96,16,13,45.25,33.17,78.67,38.79A8,8,0,0,0,136,104V88a32,32,0,0,1,9.6-22.92A30.94,30.94,0,0,1,167.9,56c12.66.16,24.49,7.88,29.44,19.21A8,8,0,0,0,204.67,80h16Z"></path>
                  </svg>
                </div>
              </a>
              <a href="#">
                <div class="text-[#7a7671]" data-icon="InstagramLogo" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path>
                  </svg>
                </div>
              </a>
              <a href="#">
                <div class="text-[#7a7671]" data-icon="FacebookLogo" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"></path>
                  </svg>
                </div>
              </a>
            </div>
            <p class="text-[#7a7671] text-base font-normal leading-normal">© 2025 Stetson Latam. All rights reserved.</p>
          </footer>
        </div>
      </footer>
    </div>
  </div>
  <? include "modal.php" ?>
  <script src="js/auth.js?v=<? echo time(); ?>"></script>
  <script src="js/index.js?v=<? echo time(); ?>"></script>
</body>

</html>