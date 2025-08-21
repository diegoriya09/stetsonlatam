<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>Shipping & Returns | Stetson LATAM</title>
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">

   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div
      class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden"
      style='--checkbox-tick-svg: url(&apos;data:image/svg+xml,%3csvg viewBox=%270 0 16 16%27 fill=%27rgb(21,21,20)%27 xmlns=%27http://www.w3.org/2000/svg%27%3e%3cpath d=%27M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z%27/%3e%3c/svg%3e&apos;); font-family: "Work Sans", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
         <?php include "header.php" ?>
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
   <?php include "modal.php" ?>
   <script src="js/auth.js?v=<? echo time(); ?>"></script>
   <script src="js/index.js?v=<? echo time(); ?>"></script>
</body>

</html>