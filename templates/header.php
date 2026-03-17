<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav id="encabezado" class="navbar navbar-expand-lg navbar-dark bg-black py-2">
    <div class="container d-flex justify-content-center">
        <a class="navbar-brand mx-auto" href="/proyecto-agile-intermodular/index.php">
            <img id="logo-encabezado" src="/proyecto-agile-intermodular/img/LOGO LABUTACASOCIAL.webp" alt="logo la butaca social">
        </a>

        <!-- LUPA MOVIL -->
        <div id="contenedor-lupa-movil" class="d-lg-none d-flex"></div>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav text-center fs-5 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="/proyecto-agile-intermodular/views/movies.php">Películas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="/proyecto-agile-intermodular/views/listas.php">Lista</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="#">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="#">Contacto</a>
                </li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning">Hola, <?php echo $_SESSION['nombre_completo'] ?? ''; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link texto-cta text-white" href="/proyecto-agile-intermodular/php/logout.php">Cerrar sesión</a>
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
<!-- Modal Login -->
<div class="modal fade" id="modalLogin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Iniciar sesión</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" method="POST" action="/proyecto-agile-intermodular/php/login.php">
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="text" id="correoLogin" name="correo" class="form-control item-form-login" placeholder="Correo" required>
                        <small id="errCorreoLogin" class="text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" id="contrasenaLogin" name="password" class="form-control item-form-login" placeholder="Contraseña" required>
                        <small id="errContrasenaLogin" class="text-danger"></small>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" id="crear" name="crear" class="form-check-input">
                        <label class="form-check-label">Aceptar política de cookies</label>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-outline-warning" type="submit" name="login">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>