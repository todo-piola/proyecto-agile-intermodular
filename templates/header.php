<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<script type="module" src="http://localhost:5173/src/main.jsx"></script>

<nav id="encabezado" class="navbar navbar-expand-lg navbar-dark bg-black py-2">
    <div class="container d-flex justify-content-center">
        <a class="navbar-brand mx-auto" href="index.php?route=home">
            <img id="logo-encabezado" src="img/LOGO LABUTACASOCIAL.webp" alt="logo la butaca social">
        </a>

        <!-- LUPA MOVIL -->
        <div id="contenedor-lupa-movil" class="d-lg-none d-flex"></div>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav text-center fs-5 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="index.php?route=movies">Películas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="index.php?route=listas">Lista</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="index.php?route=blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="index.php?route=contacto">Contacto</a>
                </li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning">Hola, <?php echo $_SESSION['nombre_completo'] ?? ''; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link texto-cta text-white" href="php/logout.php">Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link texto-cta text-white" href="#" data-bs-toggle="modal" data-bs-target="#modalLogin">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div id="contenedor-lupa" class="position-relative d-none d-lg-flex align-items-center ms-2"></div>
        </div>

        <button
            id="cartBtn"
            class="btn btn-warning ms-2"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasScrolling"
            aria-controls="offcanvasScrolling">
            <i class="bi bi-cart" id="cart-icon">
                <span id="cart-count">0</span>
            </i>
        </button>
    </div>
</nav>

<?php include(__DIR__ . "/carrito.html"); ?>

<?php if (!isset($_SESSION['usuario_id'])): ?>
    <div id="modal-root"></div>
<?php endif; ?>