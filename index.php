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
            <?php include 'header.php'; ?>
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
                                    Explore our curated collection of premium hats, crafted with the spirit of the American West.
                                </h2>
                            </div>
                            <a href="hats.php">
                                <button
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#e68019] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em]">
                                    <span class="truncate">Shop Now</span>
                                </button>
                            </a>
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
                                <a href="producto.php?id=<?php echo $row['id']; ?>">
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
                                </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-gray-500">No featured products found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                require_once 'php/conexion.php';

                // Últimos 3 productos (ordenados por fecha o id)
                $sql = "SELECT id, name, description, image 
                    FROM productos 
                    ORDER BY id DESC 
                    LIMIT 3";
                $result = $conn->query($sql);
                ?>

                <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">New Arrivals</h2>
                <div class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-6 p-4">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <a href="producto.php?id=<?php echo $row['id']; ?>" class="flex flex-col gap-3 pb-3">
                                <div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg"
                                    style='background-image: url("<?php echo htmlspecialchars($row["image"]); ?>");'></div>
                                <div>
                                    <p class="text-[#181411] text-base font-medium leading-normal">
                                        <?php echo htmlspecialchars($row["name"]); ?>
                                    </p>
                                    <p class="text-[#887563] text-sm font-normal leading-normal">
                                        <?php echo htmlspecialchars($row["description"]); ?>
                                    </p>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-500">No new arrivals found.</p>
                    <?php endif; ?>
                </div>
                <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Our Story</h2>
                <div class="p-4 @container">
                    <a href="aboutUs.php">
                        <div class="flex flex-col items-stretch justify-start rounded-lg @xl:flex-row @xl:items-start">
                            <div
                                class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                                style='background-image: url("img/story.webp");'></div>
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