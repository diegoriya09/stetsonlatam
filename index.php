<html>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
        rel="stylesheet"
        as="style"
        onload="this.rel='stylesheet'"
        href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Serif%3Awght%40400%3B500%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" />

    <title>Stetson LATAM - Original Stetson Hats for All Latin America</title>
    <meta name="description" content="In Stetson LATAM, in alliance with DINALSOM, find legendary hats with shipments to all Latin America. Shop online with confidence." />
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
                    <div class="@container">
                        <div class="@[480px]:p-4">
                            <div
                                class="flex min-h-[480px] flex-col gap-6 bg-cover bg-center bg-no-repeat @[480px]:gap-8 @[480px]:rounded-lg items-center justify-center p-4"
                                style='background-image: linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.4) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuCd80OGI4JXRLedBHT0VsDto2YJDIpmDPFLEQjs1ZQzHfK5rOKlmIDxbgzfNYpY2jOCLsLEet7hOqoa3no39I6mZfYSIcztgxHL5y8GqG1YqkiJTyNZhdE9MCqI5waHw3JcAw-VQQjUB13p39-96pEUdRzGcrZ5vu6A8XK49H0yCmJ8zhLKJF-gww9JBuSrdu3iIEi0WOH7lwmFLgnv32eHPju6NdZi3BQWk2r_6oa6YTtzOT5nl5bqFJr_Gxjlf9vsKBffyHdRAN-t");'>
                                <div class="flex flex-col gap-2 text-center">
                                    <h1
                                        class="text-white text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]">
                                        Authentic Style, Timeless Legacy
                                    </h1>
                                    <h2 class="text-white text-sm font-normal leading-normal @[480px]:text-base @[480px]:font-normal @[480px]:leading-normal">
                                        Explore our curated collection of premium hats and accessories, crafted with the spirit of the American West.
                                    </h2>
                                </div>
                                <button
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#e68019] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em]">
                                    <span class="truncate">Shop Now</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                    require_once 'php/conexion.php';

                    // Traer productos destacados (is_featured = 1)
                    $sql = "SELECT id, name, description, image FROM productos WHERE is_featured = 1 LIMIT 3";
                    $result = $conn->query($sql);
                    ?>

                    <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Featured Products</h2>
                    <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        <div class="flex items-stretch p-4 gap-3">
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                                        <div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                                            style='background-image: url("<?php echo htmlspecialchars($row["image"]); ?>");'>
                                        </div>
                                        <div>
                                            <p class="text-[#181411] text-base font-medium leading-normal">
                                                <?php echo htmlspecialchars($row["name"]); ?>
                                            </p>
                                            <p class="text-[#887563] text-sm font-normal leading-normal">
                                                <?php echo htmlspecialchars($row["description"]); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-gray-500">No featured products found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">New Arrivals</h2>
                    <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3 p-4">
                        <div class="flex flex-col gap-3 pb-3">
                            <div
                                class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC0n9UT8RSB4SMQtGhKgvB7Ci_kElF7dDsjGbS6QjSBPMU562cBfYpecr3uyRNPvGgOSUgOb80LulYE-_OsFFjvOarkHiMK2UP-apbjz1UgXvOq14p2_gcYaH15qGOwh9Qrorrtc2yvzfG5WHPx6LkSaBXTpGXusQti-795A_MgVwt-N4YFFsCigQ1EnWEO2JCc0vx7X3KGSHu7ItM1ZFGgWEWt2Ir6PhmCR7ngGWgpXCn-N9sS6fX0WlUihywA6Aqc9x7-i67e1BeQ");'></div>
                            <div>
                                <p class="text-[#181411] text-base font-medium leading-normal">The Outlaw Collection</p>
                                <p class="text-[#887563] text-sm font-normal leading-normal">Bold and rugged designs for the modern adventurer.</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 pb-3">
                            <div
                                class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg"
                                style='background-image: url("img/lobocowboy.webp");'></div>
                            <div>
                                <p class="text-[#181411] text-base font-medium leading-normal">The Heritage Series</p>
                                <p class="text-[#887563] text-sm font-normal leading-normal">Celebrating our rich history with classic styles.</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 pb-3">
                            <div
                                class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg"
                                style='background-image: url("img/kenton10xstraw.webp");'></div>
                            <div>
                                <p class="text-[#181411] text-base font-medium leading-normal">The Urban Cowboy</p>
                                <p class="text-[#887563] text-sm font-normal leading-normal">A contemporary take on Western fashion.</p>
                            </div>
                        </div>
                    </div>
                    <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Our Story</h2>
                    <div class="p-4 @container">
                        <a href="aboutUs.php">
                            <div class="flex flex-col items-stretch justify-start rounded-lg @xl:flex-row @xl:items-start">
                                <div
                                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg"
                                    style='background-image: url("img/story1.webp");'></div>
                                <div class="flex w-full min-w-72 grow flex-col items-stretch justify-center gap-1 py-4 @xl:px-4">
                                    <p class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]">A Legacy of Craftsmanship</p>
                                    <div class="flex items-end gap-3 justify-between">
                                        <p class="text-[#887563] text-base font-normal leading-normal">
                                            For over 150 years, Stetson has been synonymous with quality and authenticity. Our products are crafted with meticulous attention to detail, using the finest
                                            materials to ensure lasting style and durability.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div id="signup-section" class="@container">
                        <div class="flex flex-col justify-end gap-6 px-4 py-10 @[480px]:gap-8 @[480px]:px-10 @[480px]:py-20">
                            <div class="flex flex-col gap-2 text-center">
                                <h1
                                    class="text-[#181411] tracking-light text-[32px] font-bold leading-tight @[480px]:text-4xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em] max-w-[720px]">
                                    Join the Stetson Community
                                </h1>
                                <p class="text-[#181411] text-base font-normal leading-normal max-w-[720px]">Stay up-to-date on the latest news, exclusive offers, and new product releases.</p>
                            </div>
                            <div class="flex flex-1 justify-center">
                                <div class="flex justify-center">
                                    <button
                                        id="open-user-modal"
                                        class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#e68019] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em] grow">
                                        <span id="truncate">Sign Up</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <?php include 'modal.php'; ?>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
</body>

</html>