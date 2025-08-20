<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>Shipping & Returns | Stetson LATAM</title>
   <link rel="icon" href="../img/logo.webp" type="image/x-icon" loading="lazy">

   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div
      class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden"
      style='--checkbox-tick-svg: url(&apos;data:image/svg+xml,%3csvg viewBox=%270 0 16 16%27 fill=%27rgb(21,21,20)%27 xmlns=%27http://www.w3.org/2000/svg%27%3e%3cpath d=%27M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z%27/%3e%3c/svg%3e&apos;); font-family: "Work Sans", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
         <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f4f2f0] px-10 py-3">
            <div class="flex items-center gap-8">
               <div class="flex items-center gap-4 text-[#181411]">
                  <div class="size-4">
                     <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor"></path>
                     </svg>
                  </div>
                  <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="../index.php">Stetson Latam</a></h2>
               </div>
               <div class="flex items-center gap-9">
                  <a class="text-[#181411] text-sm font-medium leading-normal" href="../hats.php">Hats</a>
                  <a class="text-[#181411] text-sm font-medium leading-normal" href="../caps.php">Caps</a>
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
                  style='background-image: url("../img/logo.webp");'></div>
            </div>
         </header>
         <div class="px-40 flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
               <div class="flex flex-wrap justify-between gap-3 p-4">
                  <p class="text-[#151514] tracking-light text-[32px] font-bold leading-tight min-w-72">Shipping &amp; Returns</p>
               </div>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Shipping Information</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  We offer a variety of shipping options to meet your needs. Shipping costs and delivery times vary depending on your location and the selected shipping method. Please
                  see below for more details.
               </p>
               <div class="px-4 py-3 @container">
                  <div class="flex overflow-hidden rounded-lg border border-[#e2e1df] bg-white">
                     <table class="flex-1">
                        <thead>
                           <tr class="bg-white">
                              <th class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 px-4 py-3 text-left text-[#151514] w-[400px] text-sm font-medium leading-normal">
                                 Shipping Method
                              </th>
                              <th class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 px-4 py-3 text-left text-[#151514] w-[400px] text-sm font-medium leading-normal">
                                 Delivery Time
                              </th>
                              <th class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 px-4 py-3 text-left text-[#151514] w-[400px] text-sm font-medium leading-normal">Cost</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr class="border-t border-t-[#e2e1df]">
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 h-[72px] px-4 py-2 w-[400px] text-[#151514] text-sm font-normal leading-normal">
                                 Standard Shipping
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 h-[72px] px-4 py-2 w-[400px] text-[#7a7671] text-sm font-normal leading-normal">
                                 5-7 business days
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 h-[72px] px-4 py-2 w-[400px] text-[#7a7671] text-sm font-normal leading-normal">
                                 Free on orders over $50, otherwise $7.99
                              </td>
                           </tr>
                           <tr class="border-t border-t-[#e2e1df]">
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 h-[72px] px-4 py-2 w-[400px] text-[#151514] text-sm font-normal leading-normal">
                                 Express Shipping
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 h-[72px] px-4 py-2 w-[400px] text-[#7a7671] text-sm font-normal leading-normal">
                                 2-3 business days
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 h-[72px] px-4 py-2 w-[400px] text-[#7a7671] text-sm font-normal leading-normal">$14.99</td>
                           </tr>
                           <tr class="border-t border-t-[#e2e1df]">
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 h-[72px] px-4 py-2 w-[400px] text-[#151514] text-sm font-normal leading-normal">
                                 International Shipping
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 h-[72px] px-4 py-2 w-[400px] text-[#7a7671] text-sm font-normal leading-normal">
                                 7-14 business days
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 h-[72px] px-4 py-2 w-[400px] text-[#7a7671] text-sm font-normal leading-normal">
                                 Calculated at checkout
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <style>
                     @container(max-width:120px) {
                        .table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 {
                           display: none;
                        }
                     }

                     @container(max-width:240px) {
                        .table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 {
                           display: none;
                        }
                     }

                     @container(max-width:360px) {
                        .table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 {
                           display: none;
                        }
                     }
                  </style>
               </div>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">International Shipping</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  We ship to select countries internationally. Shipping costs and delivery times will be calculated at checkout based on your location and the selected shipping method.
                  Please note that international orders may be subject to customs duties and taxes, which are the responsibility of the recipient.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Returns &amp; Exchanges</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  We want you to be completely satisfied with your purchase. If you are not happy with your order, you can return it within 30 days of the delivery date for a full
                  refund or exchange, subject to the following conditions:
               </p>
               <div class="px-4">
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#151514] text-base font-normal leading-normal">Items must be returned in their original condition, unworn, unwashed, and with all tags attached.</p>
                  </label>
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#151514] text-base font-normal leading-normal">Returns must be accompanied by the original packing slip or order confirmation.</p>
                  </label>
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#151514] text-base font-normal leading-normal">Certain items, such as personalized or final sale items, are not eligible for return or exchange.</p>
                  </label>
               </div>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">To initiate a return or exchange, please follow these steps:</p>
               <div class="px-4">
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#151514] text-base font-normal leading-normal">
                        Contact our customer support team at support@stetson.com to request a return authorization and receive a return shipping label.
                     </p>
                  </label>
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#151514] text-base font-normal leading-normal">
                        Pack the item securely in its original packaging, if possible, and include the packing slip or order confirmation.
                     </p>
                  </label>
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#151514] text-base font-normal leading-normal">
                        Affix the return shipping label to the package and drop it off at the designated carrier's location.
                     </p>
                  </label>
               </div>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Once we receive your return, we will inspect the item and process your refund or exchange within 5-7 business days. Refunds will be issued to the original payment
                  method. Exchanges are subject to product availability.
               </p>
               <h3 class="text-[#151514] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Contact Us</h3>
               <p class="text-[#151514] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  If you have any questions or need further assistance, please contact our customer support team at support@stetson.com or call us at (555) 123-4567. We are available
                  Monday to Friday, 9 AM to 5 PM (GMT-5).
               </p>
               <p class="text-[#7a7671] text-sm font-normal leading-normal pb-3 pt-1 px-4">Last updated: October 26, 2024</p>
            </div>
         </div>
      </div>
   </div>
   <?php include "../modal.php" ?>
   <script src="../js/auth.js?v=<? echo time(); ?>"></script>
   <script src="../js/index.js?v=<? echo time(); ?>"></script>
</body>

</html>