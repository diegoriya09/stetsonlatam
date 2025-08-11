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
                    <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Featured Products</h2>
                    <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
                        <div class="flex items-stretch p-4 gap-3">
                            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                                <div
                                    class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDRy-iWpxjuMsTym_9FgPFPhk1IDl5Rop0_YOLLpoJQHepoBXXd3HBLtge2ZwkMcMdsSUC-4bSlHkzEYvcYvdh289Zl5aS7tuqxK0_EzalFFAeGesS5eY0mBjkR8o1eKP7BXsYsPj7YSU-CsHs4LYoNFL4SSKICZV5N80Q_seI1hMGxewvq8g-QRUr_ic0j7OWzl8t-TgXhfxoM3Ush07-rItcKzcXv_ZBWKsHrKCtvEdh3WlrhKx6dKtlikPTxDeyM64iDVeth0Wvh");'></div>
                                <div>
                                    <p class="text-[#181411] text-base font-medium leading-normal">The Classic Stetson</p>
                                    <p class="text-[#887563] text-sm font-normal leading-normal">Our iconic hat, a symbol of American craftsmanship.</p>
                                </div>
                            </div>
                            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                                <div
                                    class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD_4bSW7T4MHdOUEUFfsiJ1H8T21Fwlhg5sEnxWw6vVyaj6vy5xs20lpEfrucnndQxFANjgs2x_1IPa5QJlla9uY6JZRPWkSkQoxsAHj2NYONDyWuO_aIZCdxuq3ybq1MI74ezCbCNZ4KQblWdb9aAV1aNNlHQKttdl1I3ZpMkGMrHGDQN-6mHWmhkm_AG40W4wSgHqYc2pxogYh_iMbdEAtiq7Xx7ezAPpHeSRzd5zyHoo8rhSpxOCV9OUZ-eF5CWNjnAGgQsvQA37");'></div>
                                <div>
                                    <p class="text-[#181411] text-base font-medium leading-normal">Handcrafted Leather Belt</p>
                                    <p class="text-[#887563] text-sm font-normal leading-normal">Premium leather belt, perfect for any occasion.</p>
                                </div>
                            </div>
                            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                                <div
                                    class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuApy6Xh2jT-iLodzlzuvygsLbMV2FE0Aj1487807LZJY5QsNPiXJhmD0B2zbfumWqTo5GpjEgXbs795tYARjrIE6SPEhfq-FQUKaY9Y62JOf2OI4FWK8E9wup_VokXuhMIETR-g6czEkOGHfafTbZGy18iY8GYNdeHQzE3dAY007OAamMCIkguBEr_Tix73lXKThDbEooqQO482rOoJ4smjIMhLWUJ-ifCEG0WXC3umfrWwVyfpjYp9W02uf9tZ3d8ZwrFq1QlcoTWb");'></div>
                                <div>
                                    <p class="text-[#181411] text-base font-medium leading-normal">Western Style Shirt</p>
                                    <p class="text-[#887563] text-sm font-normal leading-normal">Embrace the spirit of the West with our stylish shirts.</p>
                                </div>
                            </div>
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
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA-uiEsmZ1f-GhLno4ZXFPnmv0NUQozj_GI9imMshIMapgKyGmOWFDAmiW7ipS3P2ScjyvDJg-T1P_blq-q6GjDUMmxWi3qRZiNGgICjamEXc6UzSSFs-i1_tJsS5-EsgSrYXRc8FGxpXJ47QfGWZM_cx95JfuSpXGg-97Ekha4RsXLm-pmf7_fQZZoJkIASI_AvNZvFt5BIkTHQ-t8JZ7ng2My2xHc5ys-Oi-bIgES_alj2hiU2aNbROK10VCUWH74DfKV49bMXrEf");'></div>
                            <div>
                                <p class="text-[#181411] text-base font-medium leading-normal">The Heritage Series</p>
                                <p class="text-[#887563] text-sm font-normal leading-normal">Celebrating our rich history with classic styles.</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 pb-3">
                            <div
                                class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBz5mD-6bflPGIqNfcO7EUyf9k9p7iyyVauQFflGMMyHJN4RVRRU_1wk_mqQDvbExzvukaAeRI-jgKi_OLbG64WtQvWooUdqd8ysO1XcJvd-0zuc4aOfr20UnjKK9FEW4KD8-v2BoH4DTo5_KRcRCR3tZrqOFS4wS_wTBATIEJ4uBl75ivV4gXI3xrE3WPqTnEhlOkJwXKEcqxCb-NaxxySHvRa4CyDvuDvC7aqTXrkrCmWWPJHs-5sky8NcBiUFDd6Bn3CZtkzcBU8");'></div>
                            <div>
                                <p class="text-[#181411] text-base font-medium leading-normal">The Urban Cowboy</p>
                                <p class="text-[#887563] text-sm font-normal leading-normal">A contemporary take on Western fashion.</p>
                            </div>
                        </div>
                    </div>
                    <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Our Story</h2>
                    <div class="p-4 @container">
                        <div class="flex flex-col items-stretch justify-start rounded-lg @xl:flex-row @xl:items-start">
                            <div
                                class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBngfkD4V0yl4Wz2667alQUjTgSwfa7Eou7sOg_EueCU6QRC8qypC8ER2CMSDwiwhxC8NTz7HyuEFSxsNeeXoM6yHnv74heJg_GTWKQyrCPp45G4IxeugJatQkanVwfdsmjaf_eTZPMP3qthlBK8CNayUiE0f5oLxpBRTUPe5pIWKywC6YBnws9LQ8JERv_a8MWaPbMRU2LBO6XtmO9t7iXe1rx95-wDCjaWcKFYd67qrjdCKdeKqsmicf_tB75N6jTVXXaSPk9zimh");'></div>
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
                    </div>
                    <div class="@container">
                        <div class="flex flex-col justify-end gap-6 px-4 py-10 @[480px]:gap-8 @[480px]:px-10 @[480px]:py-20">
                            <div class="flex flex-col gap-2 text-center">
                                <h1
                                    class="text-[#181411] tracking-light text-[32px] font-bold leading-tight @[480px]:text-4xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em] max-w-[720px]">
                                    Join the Stetson Community
                                </h1>
                                <p class="text-[#181411] text-base font-normal leading-normal max-w-[720px">Stay up-to-date on the latest news, exclusive offers, and new product releases.</p>
                            </div>
                            <div class="flex flex-1 justify-center">
                                <div class="flex justify-center">
                                    <button
                                        class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#e68019] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em] grow">
                                        <span id="open-user-modal">Sign Up</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="flex justify-center">
                <div class="flex max-w-[960px] flex-1 flex-col">
                    <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
                        <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">About Us</a>
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Contact Us</a>
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Customer Service</a>
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Privacy Policy</a>
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Terms of Service</a>
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">FAQs</a>
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Shipping &amp; Returns</a>
                        </div>
                        <div class="flex flex-wrap justify-center gap-4">
                            <a href="#">
                                <div class="text-[#887563]" data-icon="FacebookLogo" data-size="24px" data-weight="regular">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                                        <path
                                            d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"></path>
                                    </svg>
                                </div>
                            </a>
                            <a href="#">
                                <div class="text-[#887563]" data-icon="InstagramLogo" data-size="24px" data-weight="regular">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                                        <path
                                            d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path>
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
                        <p class="text-[#887563] text-base font-normal leading-normal">© 2025 Stetson Latam. All rights reserved.</p>
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