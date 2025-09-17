<html>

<head>
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
   <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

   <title>Envíos y devoluciones | Stetson LATAM</title>
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
   <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">

   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div
      class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden"
      style='--checkbox-tick-svg: url(&apos;data:image/svg+xml,%3csvg viewBox=%270 0 16 16%27 fill=%27rgb(21,21,20)%27 xmlns=%27http://www.w3.org/2000/svg%27%3e%3cpath d=%27M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z%27/%3e%3c/svg%3e&apos;);'>
      <div class="layout-container flex h-full grow flex-col">
         <?php include "header.php" ?>
         <div class="px-40 flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
               <div class="flex flex-wrap justify-between gap-3 p-4">
                  <p class="text-[#3c3737] tracking-light text-[32px] font-bold leading-tight min-w-72">Envíos y devoluciones</p>
               </div>
               <h3 class="text-[#3c3737] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Información de Envío</h3>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Ofrecemos una variedad de opciones de envío para satisfacer tus necesidades. Los costos de envío y los tiempos de entrega varían según tu ubicación y el método de envío seleccionado. Por favor consulta a continuación para más detalles.
               </p>
               <div class="px-4 py-3 @container">
                  <div class="flex overflow-hidden rounded-lg border border-[#e2e1df] bg-white">
                     <table class="flex-1">
                        <thead>
                           <tr class="bg-white">
                              <th class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 px-4 py-3 text-left text-[#3c3737] w-[400px] text-sm font-medium leading-normal">
                                    
                              </th>
                              <th class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 px-4 py-3 text-left text-[#3c3737] w-[400px] text-sm font-medium leading-normal">
                                 Tiempo de Entrega
                              </th>
                              <th class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 px-4 py-3 text-left text-[#3c3737] w-[400px] text-sm font-medium leading-normal">Costo</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr class="border-t border-t-[#e2e1df]">
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">
                                 Envío estándar
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">
                                 5-7 días hábiles
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">
                                 Gratis en pedidos superiores a $50, de lo contrario $7.99
                              </td>
                           </tr>
                           <tr class="border-t border-t-[#e2e1df]">
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">
                                 Envío exprés
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">
                                 2-3 días hábiles
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">$14.99</td>
                           </tr>
                           <tr class="border-t border-t-[#e2e1df]">
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-120 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">
                                 Envío internacional
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-240 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">
                                 7-14 días hábiles
                              </td>
                              <td class="table-83e42b49-6c1c-4ff1-8b3d-ff90f6019251-column-360 h-[72px] px-4 py-2 w-[400px] text-[#3c3737] text-sm font-normal leading-normal">
                                 Calculado en el proceso de pago
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
               <h3 class="text-[#3c3737] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Envío internacional</h3>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Realizamos envíos internacionales a determinados países. Los gastos de envío y los plazos de entrega se calcularán al finalizar la compra en función de su ubicación y del método de envío seleccionado. Tenga en cuenta que los pedidos internacionales pueden estar sujetos a derechos de aduana e impuestos, que correrán a cargo del destinatario.
               </p>
               <h3 class="text-[#3c3737] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Devoluciones y cambios</h3>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Queremos que esté completamente satisfecho con su compra. Si no está contento con su pedido, puede devolverlo dentro de los 30 días posteriores a la fecha de entrega para obtener un reembolso completo o un cambio, sujeto a las siguientes condiciones:
               </p>
               <div class="px-4">
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#3c3737] text-base font-normal leading-normal">Los artículos deben devolverse en su estado original, sin usar, sin lavar y con todas las etiquetas puestas.</p>
                  </label>
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#3c3737] text-base font-normal leading-normal">Las devoluciones deben ir acompañadas del albarán de envío original o de la confirmación del pedido.</p>
                  </label>
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#3c3737] text-base font-normal leading-normal">Ciertos artículos, como los personalizados o en liquidación, no son elegibles para devolución o cambio.</p>
                  </label>
               </div>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">Para iniciar una devolución o cambio, siga estos pasos:</p>
               <div class="px-4">
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#3c3737] text-base font-normal leading-normal">
                        Contacte a nuestro equipo de atención al cliente en support@stetson.com para solicitar una autorización de devolución y recibir una etiqueta de envío de devolución.
                     </p>
                  </label>
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#3c3737] text-base font-normal leading-normal">
                        Empaque el artículo de forma segura en su embalaje original, si es posible, e incluya el albarán de envío o la confirmación del pedido.
                     </p>
                  </label>
                  <label class="flex gap-x-3 py-3 flex-row">
                     <input
                        type="checkbox"
                        class="h-5 w-5 rounded border-[#e2e1df] border-2 bg-transparent text-[#f1eeea] checked:bg-[#f1eeea] checked:border-[#f1eeea] checked:bg-[image:--checkbox-tick-svg] focus:ring-0 focus:ring-offset-0 focus:border-[#e2e1df] focus:outline-none" />
                     <p class="text-[#3c3737] text-base font-normal leading-normal">
                        Pegue la etiqueta de envío de devolución en el paquete y déjelo en la ubicación designada del transportista.
                     </p>
                  </label>
               </div>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Una vez que recibamos su devolución, inspeccionaremos el artículo y procesaremos su reembolso o cambio dentro de 5-7 días hábiles. Los reembolsos se emitirán al método de pago original.
                  method. Exchanges are subject to product availability.
               </p>
               <h3 class="text-[#3c3737] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Contáctenos</h3>
               <p class="text-[#3c3737] text-base font-normal leading-normal pb-3 pt-1 px-4">
                  Si tiene alguna pregunta o necesita más ayuda, comuníquese con nuestro equipo de atención al cliente en support@stetson.com o llámenos al (555) 123-4567. Estamos disponibles
                  de lunes a viernes, de 9 a. m. a 5 p. m. (GMT-5).
               </p>
            </div>
         </div>
      </div>
   </div>
   <?php include "modal.php" ?>
</body>

</html>