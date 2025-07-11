<?php
// Si necesitas código PHP aquí, colócalo antes del HTML
?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <title>Contact | Stetson LATAM</title>
   <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
</head>

<body>
   <?php include 'navbar.php'; ?>
   <?php include 'modal.php'; ?>
   <?php include 'cart.php'; ?>
   <?php include 'checkout.php'; ?>


   <section class="contacto">
      <h1>Contact us</h1>
      <p>Email: contacto@stetsonlatam.com</p>
      <p>Phone: +52 123 456 7890</p>
      <p>Address: Calle Ejemplo 123, Ciudad, País</p>
      <form id="contact-form" action="php/contact/send_contact.php" method="POST" style="max-width:400px;margin:auto;">
         <label>Name:<input type="text" name="nombre" required></label><br>
         <label>Email:<input type="email" name="correo" required></label><br>
         <label>Subject:<input type="text" name="asunto" required></label><br>
         <label>Message:<textarea name="mensaje" required></textarea></label><br>
         <button type="submit">Send</button>
      </form>
      <div class="mapa">
         <!-- Mapa embebido de Google Maps -->
         <iframe src="https://www.google.com/maps?q=Medellin&output=embed" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
   </section>

   <?php include 'footer.php'; ?>
   <script>
      document.getElementById('contact-form').addEventListener('submit', function(e) {
         const nombre = this.nombre.value.trim();
         const correo = this.correo.value.trim();
         const asunto = this.asunto.value.trim();
         const mensaje = this.mensaje.value.trim();
         if (!nombre || !correo || !asunto || !mensaje) {
            alert('All fields are required.');
            e.preventDefault();
         }
         // You can add more validations here
      });
   </script>
   <script src="js/index.js?v=<?php echo time(); ?>"></script>
   <script src="js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="js/cart.js?v=<?php echo time(); ?>"></script>
   <script src="js/wishlist.js?v=<?php echo time(); ?>"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>