<?php
session_start();
require_once(__DIR__ . "/../php/conexion.php");

// Si no hay sesión, redirigir a home
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?route=home");
    exit;
}

// Obtener datos frescos del usuario desde la BD (no solo de sesión)
$stmt = $conexion->prepare("SELECT id, nombre_completo, correo, sexo FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';

// Si es admin, obtener lista de usuarios (excluyéndose a sí mismo)
if ($esAdmin) {
    $stmtUsuarios = $conexion->prepare("
        SELECT id, nombre_completo, correo, sexo, rol
        FROM usuarios
        WHERE id != ?
        ORDER BY id ASC
    ");
    $stmtUsuarios->execute([$_SESSION['usuario_id']]);
    $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="app-base" content="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>">
    <title>Perfil</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
    <link href="css/cartStyle.css" rel="stylesheet">
    <link href="css/estilo_perfil.css" rel="stylesheet">
    <link href="css/estilo-cine.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="img/logo_invisible_butaca.png">
    <script src="js/templates-js/templates-loader.js"></script>
</head>
<body>
    <main>
        <?php include(__DIR__ . "/../templates/header.php"); ?>

        <!-- Cabecera del perfil -->
        <section class="mt-5">
            <div class="row">
                <div class="col col-md">
                    <div class="d-flex justify-content-center align-items-end gap-3">
                        <img src="img/icono_perfil.webp" alt="Foto de perfil" class="border rounded-circle" style="width: 12%;">
                        <div>
                            <p id="nomUsuario" class="mb-1 fs-5"><?= htmlspecialchars($usuario['nombre_completo']) ?></p>
                            <p class="mb-1 text-muted small"><?= htmlspecialchars($usuario['correo']) ?></p>
                            <button
                                class="btn-modificar btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarPerfil">
                                <i class="bi bi-pencil me-1"></i> Editar perfil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php if (isset($_GET['ok'])): ?>
            <div class="alert alert-success mx-5 mt-3">Datos actualizados correctamente.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger mx-5 mt-3">Error: <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <!-- Panel admin: gestión de usuarios -->
        <?php if ($esAdmin): ?>
        <section class="mt-5 px-5">
            <h3 class="text-white mb-3"><i class="bi bi-people me-2"></i>Gestión de usuarios</h3>
            <div class="linea-centrada mb-4"></div>
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover align-middle">
                <thead class="table-warning text-black">
                    <tr>
                        <th class="d-none d-md-table-cell">#</th>
                        <th>Nombre completo</th>
                        <th>Correo</th>
                        <th class="d-none d-md-table-cell">Sexo</th>
                        <th class="d-none d-md-table-cell">Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                        <td class="d-none d-md-table-cell"><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($u['correo']) ?></td>
                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($u['sexo'] ?? '—') ?></td>
                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($u['rol'] ?? 'usuario') ?></td>
                            <td>
                                <button
                                    class="btn-modificar btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarUsuario"
                                    data-id="<?= $u['id'] ?>"
                                    data-nombre="<?= htmlspecialchars($u['nombre_completo']) ?>"
                                    data-correo="<?= htmlspecialchars($u['correo']) ?>"
                                    data-sexo="<?= htmlspecialchars($u['sexo'] ?? '') ?>"
                                    data-rol="<?= htmlspecialchars($u['rol'] ?? 'usuario') ?>">
                                    <i class="bi bi-pencil me-1"></i> Editar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <?php endif; ?>

        <!-- Actividad reciente -->
        <section class="mt-5">
            <h3 class="ms-5 text-white">Actividad reciente</h3>
            <div class="linea-centrada mb-4"></div>
            <div class="container-fluid px-5">
                <div class="row g-4 justify-content-center">
                    <div class="col-6 col-md-3"><img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded"></div>
                    <div class="col-6 col-md-3"><img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded"></div>
                    <div class="col-6 col-md-3"><img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded"></div>
                    <div class="col-6 col-md-3"><img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded"></div>
                </div>
            </div>
        </section>

        <!-- Amigos -->
        <section class="my-4">
            <div class="linea-centrada mb-4"></div>
            <h3 class="ms-5 mb-4 text-white">Amigos</h3>
            <div class="container-fluid">
                <div class="row g-4 justify-content-center mb-4">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                    <div class="col-4 col-md-4">
                        <div class="d-flex justify-content-center">
                            <img src="img/icono_perfil.webp" alt="Foto de perfil de amigo"
                                class="rounded-circle img-fluid"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </section>

        <?php include(__DIR__ . "/../templates/footer.html"); ?>
    </main>

    <!-- Modal editar MI perfil -->
    <div class="modal fade" id="modalEditarPerfil" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <form method="POST" action="php/actualizar_perfil.php">
                    <div class="modal-header border-warning">
                        <h5 class="modal-title">Editar perfil</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" name="nombre_completo"
                                value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo"
                                value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sexo</label>
                            <select class="form-select" name="sexo">
                                <option value="">— Sin especificar —</option>
                                <option value="masculino"         <?= $usuario['sexo'] === 'masculino'         ? 'selected' : '' ?>>Masculino</option>
                                <option value="femenino"          <?= $usuario['sexo'] === 'femenino'          ? 'selected' : '' ?>>Femenino</option>
                                <option value="prefiero-no-decir" <?= $usuario['sexo'] === 'prefiero-no-decir' ? 'selected' : '' ?>>Prefiero no decir</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                            <input type="password" class="form-control" name="contrasenia" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer border-warning">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal editar usuario (solo admin) -->
    <?php if ($esAdmin): ?>
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <form method="POST" action="php/actualizar_usuario.php">
                    <div class="modal-header border-warning">
                        <h5 class="modal-title">Editar usuario</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" name="nombre_completo" id="edit-nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo" id="edit-correo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sexo</label>
                            <select class="form-select" name="sexo" id="edit-sexo">
                                <option value="">— Sin especificar —</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="prefiero-no-decir">Prefiero no decir</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select class="form-select" name="rol" id="edit-rol">
                                <option value="usuario">Usuario</option>
                                <option value="administrador">Administrador</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                            <input type="password" class="form-control" name="contrasenia" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer border-warning">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="recursos/bootstrap.bundle.min.js"></script>
    <script type="module" src="js/main.js"></script>
    <script>
        const modalEditarUsuario = document.getElementById('modalEditarUsuario');
        if (modalEditarUsuario) {
            modalEditarUsuario.addEventListener('show.bs.modal', function (e) {
                const btn = e.relatedTarget;
                document.getElementById('edit-id').value     = btn.dataset.id;
                document.getElementById('edit-nombre').value = btn.dataset.nombre;
                document.getElementById('edit-correo').value = btn.dataset.correo;
                document.getElementById('edit-sexo').value   = btn.dataset.sexo;
                document.getElementById('edit-rol').value    = btn.dataset.rol;
            });
        }
    </script>
</body>
</html>