<?php
include_once '../../lib/helpers.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recuperar contraseña</title>

<link rel="stylesheet" href="../../web/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../../web/assets/css/recuperarContrasena.css">
</head>

<body>

<div class="auth-card">

    <div class="auth-header">
        <img src="../../web/assets/img/logo.png" alt="Logo SIAV">
        <p>Recuperación de contraseña</p>
    </div>

    <div class="auth-body">

        <h2>Recuperar acceso a la cuenta</h2>

        <?php if(isset($_SESSION['error_recuperacion'])): ?>
            <div class="auth-alert auth-alert-error">
                <?php
                echo htmlspecialchars($_SESSION['error_recuperacion']);
                unset($_SESSION['error_recuperacion']);
                ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo getUrl('usuarios','usuarios','enviarCodigo',false,'ajax'); ?>" method="POST">

            <div class="input-group-icon">
                <label for="numero_documento" class="auth-label">Número de documento</label>
                <div class="input-wrapper" style="position: relative;">
                    <i class="fa fa-id-card icon" style="position: absolute; left: 10px; top: 10px; color: #94a3b8;"></i>
                    <input type="text" id="numero_documento" name="numero_documento" class="auth-input" style="padding-left: 35px;" required minlength="6" maxlength="12" pattern="[0-9]+" inputmode="numeric" placeholder="Ej: 1023456789">
                </div>
            </div>

            <div class="input-group-icon" style="margin-top: 15px;">
                <label for="correo" class="auth-label">Correo electrónico</label>
                <div class="input-wrapper" style="position: relative;">
                    <i class="fa fa-envelope icon" style="position: absolute; left: 10px; top: 10px; color: #94a3b8;"></i>
                    <input type="email" id="correo" name="correo" class="auth-input" style="padding-left: 35px;" required placeholder="correo@ejemplo.com">
                </div>
            </div>

            <button type="submit" class="auth-btn">
                Enviar código
            </button>
        </form>
        <div class="auth-link">
            <a href="../../web/login.php">
                ← Volver al inicio de sesión
            </a>
        </div>
    </div>
    <div class="auth-footer">
        <p>Secretaría de Movilidad - Todos los derechos reservados</p>
    </div>
</div>

</body>
</html>