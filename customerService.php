<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>Customer Service | Stetson LATAM</title>
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">

   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>
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
               <div class="flex flex-wrap justify-between gap-3 p-4">
                  <p class="text-[#151514] tracking-light text-[32px] font-bold leading-tight min-w-72">Customer Service</p>
               </div>
               <div class="px-4 py-3">
                  <label class="flex flex-col min-w-40 h-12 w-full">
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
                           placeholder="Search for help"
                           class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#151514] focus:outline-0 focus:ring-0 border-none bg-[#f3f2f2] focus:border-none h-full placeholder:text-[#7a7671] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                           value="" />
                     </div>
                  </label>
               </div>
               <h2 class="text-[#151514] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Help topics</h2>
               <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3 p-4">
                  <div class="flex flex-1 gap-3 rounded-lg border border-[#e2e1df] bg-white p-4 flex-col">
                     <div class="text-[#151514]" data-icon="Question" data-size="24px" data-weight="regular">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                           <path
                              d="M140,180a12,12,0,1,1-12-12A12,12,0,0,1,140,180ZM128,72c-22.06,0-40,16.15-40,36v4a8,8,0,0,0,16,0v-4c0-11,10.77-20,24-20s24,9,24,20-10.77,20-24,20a8,8,0,0,0-8,8v8a8,8,0,0,0,16,0v-.72c18.24-3.35,32-17.9,32-35.28C168,88.15,150.06,72,128,72Zm104,56A104,104,0,1,1,128,24,104.11,104.11,0,0,1,232,128Zm-16,0a88,88,0,1,0-88,88A88.1,88.1,0,0,0,216,128Z"></path>
                        </svg>
                     </div>
                     <div class="flex flex-col gap-1">
                        <a href="faqs.php">
                           <h2 class="text-[#151514] text-base font-bold leading-tight">FAQs</h2>
                           <p class="text-[#7a7671] text-sm font-normal leading-normal">Find answers to common questions</p>
                        </a>
                     </div>
                  </div>
                  <div class="flex flex-1 gap-3 rounded-lg border border-[#e2e1df] bg-white p-4 flex-col">
                     <div class="text-[#151514]" data-icon="Envelope" data-size="24px" data-weight="regular">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                           <path
                              d="M224,48H32a8,8,0,0,0-8,8V192a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A8,8,0,0,0,224,48Zm-96,85.15L52.57,64H203.43ZM98.71,128,40,181.81V74.19Zm11.84,10.85,12,11.05a8,8,0,0,0,10.82,0l12-11.05,58,53.15H52.57ZM157.29,128,216,74.18V181.82Z"></path>
                        </svg>
                     </div>
                     <div class="flex flex-col gap-1">
                        <a href="contactUs.php">
                           <h2 class="text-[#151514] text-base font-bold leading-tight">Contact Us</h2>
                           <p class="text-[#7a7671] text-sm font-normal leading-normal">Get in touch with our support team</p>
                        </a>
                     </div>
                  </div>
                  <div class="flex flex-1 gap-3 rounded-lg border border-[#e2e1df] bg-white p-4 flex-col">
                     <div class="text-[#151514]" data-icon="Truck" data-size="24px" data-weight="regular">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                           <path
                              d="M247.42,117l-14-35A15.93,15.93,0,0,0,218.58,72H184V64a8,8,0,0,0-8-8H24A16,16,0,0,0,8,72V184a16,16,0,0,0,16,16H41a32,32,0,0,0,62,0h50a32,32,0,0,0,62,0h17a16,16,0,0,0,16-16V120A7.94,7.94,0,0,0,247.42,117ZM184,88h34.58l9.6,24H184ZM24,72H168v64H24ZM72,208a16,16,0,1,1,16-16A16,16,0,0,1,72,208Zm81-24H103a32,32,0,0,0-62,0H24V152H168v12.31A32.11,32.11,0,0,0,153,184Zm31,24a16,16,0,1,1,16-16A16,16,0,0,1,184,208Zm48-24H215a32.06,32.06,0,0,0-31-24V128h48Z"></path>
                        </svg>
                     </div>
                     <div class="flex flex-col gap-1">
                        <a href="shippingReturns.php">
                           <h2 class="text-[#151514] text-base font-bold leading-tight">Shipping &amp; Returns</h2>
                           <p class="text-[#7a7671] text-sm font-normal leading-normal">Learn about our shipping and return policies</p>
                        </a>
                     </div>
                  </div>
                  <div class="flex flex-1 gap-3 rounded-lg border border-[#e2e1df] bg-white p-4 flex-col">
                     <div class="text-[#151514]" data-icon="ShieldCheck" data-size="24px" data-weight="regular">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                           <path
                              d="M208,40H48A16,16,0,0,0,32,56v58.78c0,89.61,75.82,119.34,91,124.39a15.53,15.53,0,0,0,10,0c15.2-5.05,91-34.78,91-124.39V56A16,16,0,0,0,208,40Zm0,74.79c0,78.42-66.35,104.62-80,109.18-13.53-4.51-80-30.69-80-109.18V56H208ZM82.34,141.66a8,8,0,0,1,11.32-11.32L112,148.68l50.34-50.34a8,8,0,0,1,11.32,11.32l-56,56a8,8,0,0,1-11.32,0Z"></path>
                        </svg>
                     </div>
                     <div class="flex flex-col gap-1">
                        <a href="privacyPolicy.php">
                           <h2 class="text-[#151514] text-base font-bold leading-tight">Privacy Policy</h2>
                           <p class="text-[#7a7671] text-sm font-normal leading-normal">Read our privacy policy</p>
                        </a>
                     </div>
                  </div>
                  <div class="flex flex-1 gap-3 rounded-lg border border-[#e2e1df] bg-white p-4 flex-col">
                     <div class="text-[#151514]" data-icon="FileText" data-size="24px" data-weight="regular">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                           <path
                              d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Zm-32-80a8,8,0,0,1-8,8H96a8,8,0,0,1,0-16h64A8,8,0,0,1,168,136Zm0,32a8,8,0,0,1-8,8H96a8,8,0,0,1,0-16h64A8,8,0,0,1,168,168Z"></path>
                        </svg>
                     </div>
                     <div class="flex flex-col gap-1">
                        <a href="termsOfService.php">
                           <h2 class="text-[#151514] text-base font-bold leading-tight">Terms of Service</h2>
                           <p class="text-[#7a7671] text-sm font-normal leading-normal">View our terms of service</p>
                        </a>
                     </div>
                  </div>
               </div>
               <h2 class="text-[#151514] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Contact Us</h2>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  If you have any questions or need assistance, please contact us using the information below:
               </p>
               <div class="p-4 grid grid-cols-[20%_1fr] gap-x-6">
                  <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#e2e1df] py-5">
                     <p class="text-[#7a7671] text-sm font-normal leading-normal">Email</p>
                     <p class="text-[#151514] text-sm font-normal leading-normal">support@stetson.com</p>
                  </div>
                  <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#e2e1df] py-5">
                     <p class="text-[#7a7671] text-sm font-normal leading-normal">Phone</p>
                     <p class="text-[#151514] text-sm font-normal leading-normal">+1 (555) 123-4567</p>
                  </div>
                  <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#e2e1df] py-5">
                     <p class="text-[#7a7671] text-sm font-normal leading-normal">Address</p>
                     <p class="text-[#151514] text-sm font-normal leading-normal">123 Main Street, Anytown, USA</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php include "modal.php" ?>
   <script src="js/auth.js?v=<? echo time(); ?>"></script>
   <script src="js/index.js?v=<? echo time(); ?>"></script>
</body>

</html>