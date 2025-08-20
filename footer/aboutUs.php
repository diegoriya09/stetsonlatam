<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>About Us | Stetson LATAM</title>
   <link rel="icon" href="../img/logo.webp" type="image/x-icon" loading="lazy">

   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
         <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f3f2f2] px-10 py-3">
            <div class="flex items-center gap-4 text-[#151514]">
               <div class="size-4">
                  <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor"></path>
                  </svg>
               </div>
               <h2 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="../index.php">Stetson Latam</a></h2>
            </div>
            <div class="flex flex-1 justify-end gap-8">
               <div class="flex items-center gap-9">
                  <a class="text-[#151514] text-sm font-medium leading-normal" href="../index.php">Home</a>
                  <a class="text-[#151514] text-sm font-medium leading-normal" href="../hats.php">Hats</a>
                  <a class="text-[#151514] text-sm font-medium leading-normal" href="../caps.php">Caps</a>
               </div>
               <div class="flex gap-2">
                  <button
                     class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f3f2f2] text-[#151514] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                     <div class="text-[#151514]" data-icon="MagnifyingGlass" data-size="20px" data-weight="regular">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                           <path
                              d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                        </svg>
                     </div>
                  </button>
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
                  <div
                     class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                     style='background-image: url("../img/logo.webp");'></div>
               </div>
            </div>
         </header>
         <div class="px-40 flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
               <div class="flex flex-wrap justify-between gap-3 p-4">
                  <p class="text-[#151514] tracking-light text-[32px] font-bold leading-tight min-w-72">About Us</p>
               </div>
               <div class="@container">
                  <div class="@[480px]:px-4 @[480px]:py-3">
                     <div
                        class="bg-cover bg-center flex flex-col justify-end overflow-hidden bg-white @[480px]:rounded-lg min-h-80"
                        style='background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 25%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuAvLgkprb9dTyGQma3k3PTcIwodh8WN8uGLl4kWTKaddSkv6NT5zUTitvtRo8oFC5faE99l_ODUn6WfaGEXCvmvnF2BAFD-7n2LL9rgc5WmpAY2TYJn0P9OdpvoQ0ILJdg_uf5mX0y2_FudphUj4lnv8XqDIZz5nre2wgdwp8j1ULAGhE6qjoX2VnYQ9zkZHtUWHo9dsLLGDD44BM083WLIn_kYWhSU4So_Zr2O84vymOSP7PJN5oI2xmcY4QofhX-ehgEjbdEEEsMv");'>
                        <div class="flex p-4">
                           <p class="text-white tracking-light text-[28px] font-bold leading-tight">Our Legacy</p>
                        </div>
                     </div>
                  </div>
               </div>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Since 1865, Stetson has been synonymous with quality, craftsmanship, and the spirit of the American West. Our journey began with a vision to create hats that could
                  withstand the rigors of frontier life, and it has evolved into a legacy of iconic style and enduring quality. Today, Stetson continues to represent the same values of
                  authenticity, innovation, and timeless design.
               </p>
               <div class="@container">
                  <div class="@[480px]:px-4 @[480px]:py-3">
                     <div
                        class="bg-cover bg-center flex flex-col justify-end overflow-hidden bg-white @[480px]:rounded-lg min-h-80"
                        style='background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 25%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuCFjoUJDUDhMTRgv4ogW7tGsDxFpKSN_y5MvYBHMyQafLQqhT9IEUrvumF2okEjTe_FMOa-5UQu8wKPehpyZrqIKgIx2NgFHxAyhxxLCoPUGau5TRo0ypWikbL7u9G4j8FhqBV_o8DrkFG6FjI360juuSC06rbl__hFxVlMHQKPXdWmfHljvcFWgpGFF5sAxTFWwpSF8GohPn2P2hVUKf5KfYS2-1TWxvxAnEQbSU5lXmH2lRpsQAefb5A7ordH69ObIlDVK6D4fr0c");'>
                        <div class="flex p-4">
                           <p class="text-white tracking-light text-[28px] font-bold leading-tight">Our Mission</p>
                        </div>
                     </div>
                  </div>
               </div>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  At Stetson, our mission is to inspire confidence and individuality through products that embody the spirit of adventure and self-expression. We are committed to
                  upholding our heritage of quality and craftsmanship while embracing innovation to meet the evolving needs of our customers. Our goal is to create products that are
                  not just accessories, but symbols of personal style and enduring quality.
               </p>
               <div class="@container">
                  <div class="@[480px]:px-4 @[480px]:py-3">
                     <div
                        class="bg-cover bg-center flex flex-col justify-end overflow-hidden bg-white @[480px]:rounded-lg min-h-80"
                        style='background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 25%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuBcVIzDl0dV8-vJLf1TD2m-FAtYlNXYpRbOyKeiaINLzcPXJWBx63yC5ApdqsXrX9V_Zzc-e5VjYdD-LrG8d2IuQ1HFBDRhO2w5QILiRuqDuGEW_I1mEHXnnd0FI-4_c__Y7zPQBdJMDu7AXaeStamvYLqTsqPUTBd4CzyZXm8SkthcwGVGzNzYFtNgHMFySW-e0btBxiu1PH4wTGn9316Q4EBkyvBppoaGMikWHYxm24dITRZxJ1ODouEPs48jArBlixK3kDHb7syC");'>
                        <div class="flex p-4">
                           <p class="text-white tracking-light text-[28px] font-bold leading-tight">Our Values</p>
                        </div>
                     </div>
                  </div>
               </div>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Our core values guide everything we do at Stetson. We believe in authenticity, integrity, and respect for our heritage. We are dedicated to quality, craftsmanship,
                  and innovation, ensuring that every product meets the highest standards. We value our customers and strive to provide exceptional service and experiences that reflect
                  our commitment to excellence.
               </p>
            </div>
         </div>
      </div>
   </div>
   <?php include '../modal.php'; ?>
   <script src="../js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="../js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>