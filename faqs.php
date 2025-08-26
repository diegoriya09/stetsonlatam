<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>Preguntas frecuentes | Stetson LATAM</title>
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
   <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">

   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden">
      <div class="layout-container flex h-full grow flex-col">
         <?php include "header.php"; ?>
         <div class="px-40 flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
               <div class="flex flex-wrap justify-between gap-3 p-4">
                  <p class="text-[#3c3737] tracking-light text-[32px] font-bold leading-tight min-w-72">Preguntas frecuentes</p>
               </div>
               <div class="px-4 py-3">
                  <label class="flex flex-col min-w-40 h-12 w-full">
                     <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                        <div
                           class="text-[#3c3737] flex border-none bg-[#f3f2f2] items-center justify-center pl-4 rounded-l-lg border-r-0"
                           data-icon="MagnifyingGlass"
                           data-size="24px"
                           data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                              <path
                                 d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                           </svg>
                        </div>
                        <input
                           placeholder="Buscar una pregunta"
                           class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#3c3737] focus:outline-0 focus:ring-0 border-none bg-[#f3f2f2] focus:border-none h-full placeholder:text-[#3c3737] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                           value="" />
                     </div>
                  </label>
               </div>
               <h2 class="text-[#3c3737] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Pedidos</h2>
               <div class="flex flex-col p-4">
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Cómo realizo un pedido?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Qué métodos de pago aceptan?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Puedo cancelar o modificar mi pedido?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
               </div>
               <h2 class="text-[#151514] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Envíos</h2>
               <div class="flex flex-col p-4">
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Cuáles son los costos de envío?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Cuánto tiempo tarda el envío?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Hacen envíos internacionales?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
               </div>
               <h2 class="text-[#3c3737] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Devoluciones y Cambios</h2>
               <div class="flex flex-col p-4">
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Cuál es su política de devoluciones?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Cómo devuelvo un artículo?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Puedo cambiar un artículo?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
               </div>
               <h2 class="text-[#3c3737] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Productos</h2>
               <div class="flex flex-col p-4">
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Cómo cuido mi sombrero?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Qué tallas están disponibles?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Son auténticos sus productos?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
               </div>
               <h2 class="text-[#3c3737] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Cuenta</h2>
               <div class="flex flex-col p-4">
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Cómo creo una cuenta?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Olvidé mi contraseña, qué debo hacer?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
                  <details class="flex flex-col border-t border-t-[#e2e1df] py-2 group">
                     <summary class="flex cursor-pointer items-center justify-between gap-6 py-2">
                        <p class="text-[#3c3737] text-sm font-medium leading-normal">¿Cómo actualizo la información de mi cuenta?</p>
                        <div class="text-[#3c3737] group-open:rotate-180" data-icon="CaretDown" data-size="20px" data-weight="regular">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                              <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                           </svg>
                        </div>
                     </summary>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal pb-2"></p>
                  </details>
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