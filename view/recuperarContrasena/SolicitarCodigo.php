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

<div class="login-card">
    <div class="login-header">
        <img src="../../web/assets/img/logo.png" alt="logo">
        <p>Recuperación de contraseña</p>
    </div>
    <div class="login-body">

        <h2>Recuperar acceso</h2>

        <?php if(isset($_SESSION['error_recuperacion'])): ?>
            <div class="alerta alerta-error">
                <?php echo htmlspecialchars($_SESSION['error_recuperacion']); unset($_SESSION['error_recuperacion']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo getUrl('usuarios','usuarios','enviarCodigo',false,'ajax'); ?>" method="POST">

            <!-- DOCUMENTO -->
            <div class="input-group-icon">
                <label>Número de documento</label>
                <i class="fa fa-id-card icon"></i>
                <input type="text" name="numero_documento" required minlength="6" maxlength="12" pattern="[0-9]+" inputmode="numeric" placeholder="Ej: 1023456789">
            </div>

            <!-- CORREO -->
            <div class="input-group-icon">
                <label>Correo electrónico</label>
                <i class="fa fa-envelope icon"></i>
                <input type="email" name="correo" required placeholder="correo@ejemplo.com">
            </div>

            <button type="submit" class="btn-login">
                Enviar código
            </button>

        </form>

        <div class="login-links">
            <a href="../../web/login.php">
                ← Volver al inicio de sesión
            </a>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="login-footer">
        <p>Secretaría de Movilidad - Todos los derechos reservados</p>
    </div>

</div>

</body>
</html>