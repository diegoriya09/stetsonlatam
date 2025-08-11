<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<html>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
        rel="stylesheet"
        as="style"
        onload="this.rel='stylesheet'"
        href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

    <title>Stitch Design</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>

<body>
    <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-40 flex flex-1 justify-center py-5">
                <div class="layout-content-container flex flex-col w-[512px] max-w-[512px] py-5 max-w-[960px] flex-1">
                    <h1 class="text-[#151514] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 text-center pb-3 pt-5">Welcome to Stetson</h1>
                    <div class="pb-3">
                        <div class="flex border-b border-[#e2e1df] px-4 gap-8">
                            <a class="flex flex-col items-center justify-center border-b-[3px] border-b-[#151514] text-[#151514] pb-[13px] pt-4" href="#">
                                <p class="text-[#151514] text-sm font-bold leading-normal tracking-[0.015em]">Login</p>
                            </a>
                            <a class="flex flex-col items-center justify-center border-b-[3px] border-b-transparent text-[#7a7671] pb-[13px] pt-4" href="#">
                                <p class="text-[#7a7671] text-sm font-bold leading-normal tracking-[0.015em]">Register</p>
                            </a>
                        </div>
                    </div>
                    <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                        <label class="flex flex-col min-w-40 flex-1">
                            <input
                                placeholder="Email"
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#151514] focus:outline-0 focus:ring-0 border border-[#e2e1df] bg-white focus:border-[#e2e1df] h-14 placeholder:text-[#7a7671] p-[15px] text-base font-normal leading-normal"
                                value="" />
                        </label>
                    </div>
                    <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                        <label class="flex flex-col min-w-40 flex-1">
                            <input
                                placeholder="Password"
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#151514] focus:outline-0 focus:ring-0 border border-[#e2e1df] bg-white focus:border-[#e2e1df] h-14 placeholder:text-[#7a7671] p-[15px] text-base font-normal leading-normal"
                                value="" />
                        </label>
                    </div>
                    <p class="text-[#7a7671] text-sm font-normal leading-normal pb-3 pt-1 px-4 underline">Forgot Password?</p>
                    <div class="flex px-4 py-3">
                        <button
                            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 flex-1 bg-[#f1eeea] text-[#151514] text-sm font-bold leading-normal tracking-[0.015em]">
                            <span class="truncate">Login</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>