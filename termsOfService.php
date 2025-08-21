<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>Terms of Service | Stetson LATAM</title>
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">

   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
         <?php include "header.php" ?>
         <div class="px-40 flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
               <div class="flex flex-wrap gap-2 p-4">
                  <a class="text-[#7a7671] text-base font-medium leading-normal" href="index.php">Home</a>
                  <span class="text-[#7a7671] text-base font-medium leading-normal">/</span>
                  <span class="text-[#151514] text-base font-medium leading-normal">Terms of Service</span>
               </div>
               <div class="flex flex-wrap justify-between gap-3 p-4">
                  <p class="text-[#151514] tracking-light text-[32px] font-bold leading-tight min-w-72">Terms of Service</p>
               </div>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">1. Acceptance of Terms</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  By accessing or using the Stetson Latin America e-commerce site, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not
                  use our services.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">2. Account Terms</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  You are responsible for maintaining the confidentiality of your account information and password. You agree to accept responsibility for all activities that occur
                  under your account.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">3. Acceptable Use</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  You agree not to use the site for any unlawful purpose or in any way that could harm, disable, overburden, or impair the site. This includes not interfering with the
                  security features of the site or attempting to gain unauthorized access to any part of the site.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">4. Product Information</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  We strive to provide accurate product descriptions and images. However, we do not warrant that product descriptions or other content on the site are accurate,
                  complete, reliable, current, or error-free.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">5. Pricing and Payment</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Prices for products are subject to change without notice. We reserve the right to refuse or cancel any orders placed for products listed at the incorrect price.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">6. Shipping and Returns</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Please review our Shipping and Returns policy for information on shipping procedures and return policies.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">7. Disclaimers</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  The site and its content are provided on an 'as is' and 'as available' basis. We make no representations or warranties of any kind, express or implied, as to the
                  operation of the site or the information, content, materials, or products included on the site.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">8. Limitation of Liability</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  We will not be liable for any damages of any kind arising from the use of this site, including, but not limited to direct, indirect, incidental, punitive, and
                  consequential damages.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">9. Changes to Terms</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  We reserve the right to make changes to these Terms of Service at any time. Your continued use of the site following any changes constitutes your acceptance of the
                  new terms.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">10. Governing Law</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  These Terms of Service shall be governed by and construed in accordance with the laws of the jurisdiction in which Stetson Latin America operates.
               </p>
            </div>
         </div>
      </div>
   </div>
   <?php include "modal.php" ?>
   <script src="js/auth.js?v=<? echo time(); ?>"></script>
   <script src="js/index.js?v=<? echo time(); ?>"></script>
</body>

</html>