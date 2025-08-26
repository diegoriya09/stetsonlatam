<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>Acerca de nosotros | Stetson LATAM</title>
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
                  <p class="text-[#3c3737] tracking-light text-[32px] font-bold leading-tight min-w-72">Acerca de nosotros</p>
               </div>
               <div class="@container">
                  <div class="@[480px]:px-4 @[480px]:py-3">
                     <div
                        class="bg-cover bg-center flex flex-col justify-end overflow-hidden bg-white @[480px]:rounded-lg min-h-80"
                        style='background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 25%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuAvLgkprb9dTyGQma3k3PTcIwodh8WN8uGLl4kWTKaddSkv6NT5zUTitvtRo8oFC5faE99l_ODUn6WfaGEXCvmvnF2BAFD-7n2LL9rgc5WmpAY2TYJn0P9OdpvoQ0ILJdg_uf5mX0y2_FudphUj4lnv8XqDIZz5nre2wgdwp8j1ULAGhE6qjoX2VnYQ9zkZHtUWHo9dsLLGDD44BM083WLIn_kYWhSU4So_Zr2O84vymOSP7PJN5oI2xmcY4QofhX-ehgEjbdEEEsMv");'>
                        <div class="flex p-4">
                           <p class="text-[#f1eeea] tracking-light text-[28px] font-bold leading-tight">Our Legacy</p>
                        </div>
                     </div>
                  </div>
               </div>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Al igual que en 1865, Stetson encarna hoy en día el espíritu estadounidense ingenioso, auténtico y diverso. Rica en historia, la trayectoria de Stetson continúa al compás del viaje de Estados Unidos, en constante evolución y atemporal.
               </p>
               <div class="@container">
                  <div class="@[480px]:px-4 @[480px]:py-3">
                     <div
                        class="bg-cover bg-center flex flex-col justify-end overflow-hidden bg-white @[480px]:rounded-lg min-h-80"
                        style='background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 25%), url("img/aboutUs.webp");'>
                        <div class="flex p-4">
                           <p class="text-[#f1eeea] tracking-light text-[28px] font-bold leading-tight">Our Mission</p>
                        </div>
                     </div>
                  </div>
               </div>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  En Stetson, nuestra misión es inspirar confianza e individualidad a través de productos que encarnan el espíritu de aventura y la expresión personal. Nos comprometemos a mantener nuestra tradición de calidad y artesanía, al tiempo que adoptamos la innovación para satisfacer las necesidades cambiantes de nuestros clientes. Nuestro objetivo es crear productos que no sean solo accesorios, sino símbolos de estilo personal y calidad duradera.
               </p>
               <div class="@container">
                  <div class="@[480px]:px-4 @[480px]:py-3">
                     <div
                        class="bg-cover bg-center flex flex-col justify-end overflow-hidden bg-white @[480px]:rounded-lg min-h-80"
                        style='background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 25%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuBcVIzDl0dV8-vJLf1TD2m-FAtYlNXYpRbOyKeiaINLzcPXJWBx63yC5ApdqsXrX9V_Zzc-e5VjYdD-LrG8d2IuQ1HFBDRhO2w5QILiRuqDuGEW_I1mEHXnnd0FI-4_c__Y7zPQBdJMDu7AXaeStamvYLqTsqPUTBd4CzyZXm8SkthcwGVGzNzYFtNgHMFySW-e0btBxiu1PH4wTGn9316Q4EBkyvBppoaGMikWHYxm24dITRZxJ1ODouEPs48jArBlixK3kDHb7syC");'>
                        <div class="flex p-4">
                           <p class="text-[#f1eeea] tracking-light text-[28px] font-bold leading-tight">Our Values</p>
                        </div>
                     </div>
                  </div>
               </div>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Nuestros valores fundamentales guían todo lo que hacemos en Stetson. Creemos en la autenticidad, la integridad y el respeto por nuestra herencia. Nos dedicamos a la calidad, la artesanía y la innovación, asegurándonos de que cada producto cumpla con los más altos estándares. Valoramos a nuestros clientes y nos esforzamos por brindar un servicio y experiencias excepcionales que reflejen nuestro compromiso con la excelencia.
               </p>
            </div>
         </div>
      </div>
   </div>
   <?php include 'modal.php'; ?>
   <script src="js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>