<nav class="main-nav"> 
        <div class="nav-left">
            <a href="index.php">
                <h2 class="logo">STETSON LATAM<span class="reg">&reg;</sup></h2>
            </a>
        </div>
        <div class="mobile-menu" id="mobile-menu">
            <div class="mobile-search">
                <input type="text" placeholder="Buscar..." />
            </div>
            <ul>
                <li><a href="hats.php">Hats</a></li>
                <li><a href="index.php#stories">Stories</a></li>
            </ul>
        </div>
        <!-- Enlaces centrados -->
        <ul class="nav-center">
            <li><a href="hats.php">Hats</a></li>
            <li><a href="index.php#stories">Stories</a></li>
        </ul>
        <!-- Íconos a la derecha -->
        <div class="nav-right">
            <!-- Visible siempre -->
            <a href="#" id="open-user-modal"><img src="/img/user.png" alt="User" class="icon" /></a>
            <!-- Hamburguesa visible solo en móvil -->
            <div class="hamburger" id="hamburger">&#9776;</div>
            <!-- Solo para escritorio -->
            <div class="desktop-icons">
                <a id="logout-btn" style="display: none;"><img src="/img/logout.png" alt="Logout" class="icon"></a>
                <a href="#"><img src="/img/search.png" alt="Search" class="icon" /></a>
                <a href="wishlist.php"><i class="fas fa-heart"></i></a>
                <div class="cart-wrapper">
                    <a id="btn-carrito"><img src="/img/cart.png" alt="Cart" class="icon" /></a>
                </div>
            </div>
        </div>
</nav>