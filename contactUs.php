<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>Contáctenos | Stetson LATAM</title>
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
   <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">

   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden">
      <div class="layout-container flex h-full grow flex-col">
         <?php include 'header.php'; ?>
         <div class="px-40 flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
               <div class="flex flex-wrap justify-between gap-3 p-4">
                  <div class="flex min-w-72 flex-col gap-3">
                     <p class="text-[#3c3737] tracking-light text-[32px] font-bold leading-tight">Contáctenos</p>
                     <p class="text-[#3c3737] text-sm font-normal leading-normal">Estamos aquí para ayudar. Contáctenos si tiene alguna pregunta o inquietud.</p>
                  </div>
               </div>
               <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                     <p class="text-[#3c3737] text-base font-medium leading-normal pb-2">Nombre</p>
                     <input
                        placeholder="Ingresa tu nombre"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#3c3737] focus:outline-0 focus:ring-0 border border-[#3f1e1f] bg-white focus:border-[#3f1e1f] h-14 placeholder:text-[#3c3737] p-[15px] text-base font-normal leading-normal"
                        value="" />
                  </label>
               </div>
               <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                     <p class="text-[#3c3737] text-base font-medium leading-normal pb-2">Email</p>
                     <input
                        placeholder="Ingresa tu email"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#3c3737] focus:outline-0 focus:ring-0 border border-[#3f1e1f] bg-white focus:border-[#3f1e1f] h-14 placeholder:text-[#3c3737] p-[15px] text-base font-normal leading-normal"
                        value="" />
                  </label>
               </div>
               <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                     <p class="text-[#3c3737] text-base font-medium leading-normal pb-2">Asunto</p>
                     <input
                        placeholder="Ingresa el asunto"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#3c3737] focus:outline-0 focus:ring-0 border border-[#3f1e1f] bg-white focus:border-[#3f1e1f] h-14 placeholder:text-[#3c3737] p-[15px] text-base font-normal leading-normal"
                        value="" />
                  </label>
               </div>
               <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                     <p class="text-[#3c3737] text-base font-medium leading-normal pb-2">Mensaje</p>
                     <textarea
                        placeholder="Ingresa tu mensaje"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#3c3737] focus:outline-0 focus:ring-0 border border-[#3f1e1f] bg-white focus:border-[#3f1e1f] min-h-36 placeholder:text-[#3c3737] p-[15px] text-base font-normal leading-normal"></textarea>
                  </label>
               </div>
               <div class="flex px-4 py-3 justify-start">
                  <button
                     class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#f1eeea] text-[#3c3737] text-sm font-bold leading-normal tracking-[0.015em]">
                     <span class="truncate">Enviar</span>
                  </button>
               </div>
               <h3 class="text-[#3c3737] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Información de contacto adicional</h3>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">Teléfono: +1 (555) 123-4567</p>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">Dirección: 123 Main Street, Anytown, USA</p>
               <div class="flex px-4 py-3">
                  <div
                     class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg object-cover">
                     <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d15865.44227855455!2d-75.58828815!3d6.2160896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1755633569983!5m2!1ses-419!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php include 'modal.php'; ?>
   <script src="js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>