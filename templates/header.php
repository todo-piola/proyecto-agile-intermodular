<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<script type="module" src="http://localhost:5173/src/main.jsx"></script>

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
                    <a class="nav-link texto-cta text-white" href="/proyecto-agile-intermodular/views/blog.php">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link texto-cta text-white" href="/proyecto-agile-intermodular/views/about_contacto.php">Contacto</a>
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

                <div class="text-center mt-3">
                    <a href="#"
                        class="text-warning text-decoration-none small"
                        data-bs-dismiss="modal"
                        data-bs-toggle="modal"
                        data-bs-target="#modal">
                        ¿No tienes cuenta? ¡Regístrate en pocos pasos!
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Registro-->
<div class="modal fade" id="modal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content bg-dark text-white">
    <div class="modal-header border-warning">
        <h4 class="modal-title">Regístrate en pocos pasos</h4>
        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form id="form-envio-1">
            <div class="mb-3 field">
                <label for="nombreApellido" class="form-label">Nombre y Apellido <span class="asterisco">*</span></label>
                <input type="text" class="form-control" id="nombreApellido" required>
                <p id = "errNombre" class="error"></p>
            </div>

            <!--Correo-->
            <div class="mb-3 field">
                <label for="correo" class="form-label">Email<span class="asterisco">*</span></label>
                <input type="text" class="form-control" id="correo" required>
                <p id = "errCorreo" class="error"></p>
            </div>

            <!-- Contraseña -->
            <div class="mb-3 field">
                <label for="contrasena" class="form-label">
                    Contraseña<span class="asterisco">*</span>
                    <i class="bi bi-eye-fill toggle-pass"></i>
                </label>
                <input type="password" class="form-control" id="contrasena" required>
                <p id="errContrasena" class="error"></p>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-3 field">
                <label for="confirmarContrasena" class="form-label">Confirmar Contraseña<span class="asterisco">*</span></label>
                <input type="password" class="form-control" id="confirmarContrasena" required>
                <p id = "errConfirmarContrasena" class="error"></p>
            </div>

            <!-- Pais -->
            <div class="mb-3 field">
                <!-- 195 main countries -->
                <!-- Code and Name -->
                <select class="form-select" autocomplete="country" id="pais" name="pais">
                    <option>Pais de residencia </option>
                    <option value="AF">Afghanistan</option>
                    <option value="AL">Albania</option>
                    <option value="DZ">Algeria</option>
                    <option value="AD">Andorra</option>
                    <option value="AO">Angola</option>
                    <option value="AG">Antigua and Barbuda</option>
                    <option value="AR">Argentina</option>
                    <option value="AM">Armenia</option>
                    <option value="AU">Australia</option>
                    <option value="AT">Austria</option>
                    <option value="AZ">Azerbaijan</option>
                    <option value="BS">Bahamas</option>
                    <option value="BH">Bahrain</option>
                    <option value="BD">Bangladesh</option>
                    <option value="BB">Barbados</option>
                    <option value="BY">Belarus</option>
                    <option value="BE">Belgium</option>
                    <option value="BZ">Belize</option>
                    <option value="BJ">Benin</option>
                    <option value="BT">Bhutan</option>
                    <option value="BO">Bolivia (Plurinational State of)</option>
                    <option value="BA">Bosnia and Herzegovina</option>
                    <option value="BW">Botswana</option>
                    <option value="BR">Brazil</option>
                    <option value="BN">Brunei Darussalam</option>
                    <option value="BG">Bulgaria</option>
                    <option value="BF">Burkina Faso</option>
                    <option value="BI">Burundi</option>
                    <option value="CV">Cabo Verde</option>
                    <option value="KH">Cambodia</option>
                    <option value="CM">Cameroon</option>
                    <option value="CA">Canada</option>
                    <option value="CF">Central African Republic</option>
                    <option value="TD">Chad</option>
                    <option value="CL">Chile</option>
                    <option value="CN">China</option>
                    <option value="CO">Colombia</option>
                    <option value="KM">Comoros</option>
                    <option value="CG">Congo</option>
                    <option value="CD">Congo, Democratic Republic of the</option>
                    <option value="CR">Costa Rica</option>
                    <option value="HR">Croatia</option>
                    <option value="CU">Cuba</option>
                    <option value="CY">Cyprus</option>
                    <option value="CZ">Czech Republic</option>
                    <option value="CI">Côte d'Ivoire</option>
                    <option value="DK">Denmark</option>
                    <option value="DJ">Djibouti</option>
                    <option value="DM">Dominica</option>
                    <option value="DO">Dominican Republic</option>
                    <option value="EC">Ecuador</option>
                    <option value="EG">Egypt</option>
                    <option value="SV">El Salvador</option>
                    <option value="GQ">Equatorial Guinea</option>
                    <option value="ER">Eritrea</option>
                    <option value="EE">Estonia</option>
                    <option value="SZ">Eswatini (Swaziland)</option>
                    <option value="ET">Ethiopia</option>
                    <option value="FJ">Fiji</option>
                    <option value="FI">Finland</option>
                    <option value="FR">France</option>
                    <option value="GA">Gabon</option>
                    <option value="GM">Gambia</option>
                    <option value="GE">Georgia</option>
                    <option value="DE">Germany</option>
                    <option value="GH">Ghana</option>
                    <option value="GR">Greece</option>
                    <option value="GD">Grenada</option>
                    <option value="GT">Guatemala</option>
                    <option value="GN">Guinea</option>
                    <option value="GW">Guinea-Bissau</option>
                    <option value="GY">Guyana</option>
                    <option value="HT">Haiti</option>
                    <option value="HN">Honduras</option>
                    <option value="HU">Hungary</option>
                    <option value="IS">Iceland</option>
                    <option value="IN">India</option>
                    <option value="ID">Indonesia</option>
                    <option value="IR">Iran</option>
                    <option value="IQ">Iraq</option>
                    <option value="IE">Ireland</option>
                    <option value="IL">Israel</option>
                    <option value="IT">Italy</option>
                    <option value="JM">Jamaica</option>
                    <option value="JP">Japan</option>
                    <option value="JO">Jordan</option>
                    <option value="KZ">Kazakhstan</option>
                    <option value="KE">Kenya</option>
                    <option value="KI">Kiribati</option>
                    <option value="KP">Korea, North</option>
                    <option value="KR">Korea, South</option>
                    <option value="KW">Kuwait</option>
                    <option value="KG">Kyrgyzstan</option>
                    <option value="LA">Lao People's Democratic Republic</option>
                    <option value="LV">Latvia</option>
                    <option value="LB">Lebanon</option>
                    <option value="LS">Lesotho</option>
                    <option value="LR">Liberia</option>
                    <option value="LY">Libya</option>
                    <option value="LI">Liechtenstein</option>
                    <option value="LT">Lithuania</option>
                    <option value="LU">Luxembourg</option>
                    <option value="MK">Macedonia North</option>
                    <option value="MG">Madagascar</option>
                    <option value="MW">Malawi</option>
                    <option value="MY">Malaysia</option>
                    <option value="MV">Maldives</option>
                    <option value="ML">Mali</option>
                    <option value="MT">Malta</option>
                    <option value="MH">Marshall Islands</option>
                    <option value="MR">Mauritania</option>
                    <option value="MU">Mauritius</option>
                    <option value="MX">Mexico</option>
                    <option value="FM">Micronesia</option>
                    <option value="MD">Moldova</option>
                    <option value="MC">Monaco</option>
                    <option value="MN">Mongolia</option>
                    <option value="ME">Montenegro</option>
                    <option value="MA">Morocco</option>
                    <option value="MZ">Mozambique</option>
                    <option value="MM">Myanmar (Burma)</option>
                    <option value="NA">Namibia</option>
                    <option value="NR">Nauru</option>
                    <option value="NP">Nepal</option>
                    <option value="NL">Netherlands</option>
                    <option value="NZ">New Zealand</option>
                    <option value="NI">Nicaragua</option>
                    <option value="NE">Niger</option>
                    <option value="NG">Nigeria</option>
                    <option value="NO">Norway</option>
                    <option value="OM">Oman</option>
                    <option value="PK">Pakistan</option>
                    <option value="PW">Palau</option>
                    <option value="PA">Panama</option>
                    <option value="PG">Papua New Guinea</option>
                    <option value="PY">Paraguay</option>
                    <option value="PE">Peru</option>
                    <option value="PH">Philippines</option>
                    <option value="PL">Poland</option>
                    <option value="PT">Portugal</option>
                    <option value="QA">Qatar</option>
                    <option value="RO">Romania</option>
                    <option value="RU">Russian Federation</option>
                    <option value="RW">Rwanda</option>
                    <option value="KN">Saint Kitts and Nevis</option>
                    <option value="LC">Saint Lucia</option>
                    <option value="VC">Saint Vincent and the Grenadines</option>
                    <option value="WS">Samoa</option>
                    <option value="SM">San Marino</option>
                    <option value="ST">Sao Tome and Principe</option>
                    <option value="SA">Saudi Arabia</option>
                    <option value="SN">Senegal</option>
                    <option value="RS">Serbia</option>
                    <option value="SC">Seychelles</option>
                    <option value="SL">Sierra Leone</option>
                    <option value="SG">Singapore</option>
                    <option value="SK">Slovakia</option>
                    <option value="SI">Slovenia</option>
                    <option value="SB">Solomon Islands</option>
                    <option value="SO">Somalia</option>
                    <option value="ZA">South Africa</option>
                    <option value="SS">South Sudan</option>
                    <option value="ES">Spain</option>
                    <option value="LK">Sri Lanka</option>
                    <option value="SD">Sudan</option>
                    <option value="SR">Suriname</option>
                    <option value="SE">Sweden</option>
                    <option value="CH">Switzerland</option>
                    <option value="SY">Syria</option>
                    <option value="TW">Taiwan</option>
                    <option value="TJ">Tajikistan</option>
                    <option value="TZ">Tanzania</option>
                    <option value="TH">Thailand</option>
                    <option value="TL">Timor-Leste</option>
                    <option value="TG">Togo</option>
                    <option value="TO">Tonga</option>
                    <option value="TT">Trinidad and Tobago</option>
                    <option value="TN">Tunisia</option>
                    <option value="TR">Turkey (Türkiye)</option>
                    <option value="TM">Turkmenistan</option>
                    <option value="TV">Tuvalu</option>
                    <option value="UG">Uganda</option>
                    <option value="UA">Ukraine</option>
                    <option value="AE">United Arab Emirates</option>
                    <option value="GB">United Kingdom</option>
                    <option value="US">United States</option>
                    <option value="UY">Uruguay</option>
                    <option value="UZ">Uzbekistan</option>
                    <option value="VU">Vanuatu</option>
                    <option value="VA">Vatican City Holy See</option>
                    <option value="VE">Venezuela</option>
                    <option value="VN">Vietnam</option>
                    <option value="YE">Yemen</option>
                    <option value="ZM">Zambia</option>
                    <option value="ZW">Zimbabwe</option>
                </select>
                <!-- total - 195 -->
            </div>

            <!-- Tarjeta de crédito -->
            <div class="mb-3 field">
                <label for="tarjeta" class="form-label">Tarjeta bancaria</label>
                <input type="text" class="form-control" id="tarjeta" name="tarjeta">
                <p id = "errTarjeta" class="error"></p>
            </div>

            <!-- Sexo (Radio buttons) -->
            <fieldset class="form-check" aria-label="Botones selectores de sexo">
                <label class="form-check-label">
                    Sexo
                </label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexo" id="masculino" value="masculino">
                    <label class="form-check-label" for="masculino"> Masculino </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexo" id="femenino" value="femenino">
                    <label class="form-check-label" for="femenino"> Femenino </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexo" id="prefiero-no-decir" value="prefiero-no-decir">
                    <label class="form-check-label" for="prefiero-no-decir"> Prefiero no decir </label>
                </div>
            </fieldset>

            <!-- Fecha de Nacimiento -->
            <div class="mb-3">
                <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento">
                <p id = "errNacimiento" class="error"></p>
            </div>

            <!-- Activar Notificaciones -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="notificaciones" name="notificaciones">
                <label class="form-check-label" for="notificaciones">
                    Activar notificaciones
                </label>
            </div>

            <!-- Recibir Revista Digital -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="revistaDigital" name="revista">
                <label class="form-check-label" for="revistaDigital">
                    Recibir revista digital
                </label>
                <p class="exito" id="mensajeExito"></p>
            </div>
            <div class="modal-footer border-warning">
                <button type="submit" class="btn btn-warning" id="btn-envio">Enviar formulario</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<?php endif; ?>