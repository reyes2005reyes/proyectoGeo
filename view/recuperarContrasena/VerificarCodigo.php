<?php
include_once '../../lib/helpers.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verificar código</title>

<link rel="stylesheet" href="../../web/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../../web/assets/css/recuperarContrasena.css">
<link rel="icon" type="image/png" href="../../web/assets/img/logo-64.png">
</head>

<body>

<div class="auth-card">

    <!-- HEADER -->
    <div class="auth-header">
        <img src="../../web/assets/img/logo.png" alt="logo">
        <h1>SIAV</h1>
        <p>Verificación de seguridad</p>
    </div>

    <!-- BODY -->
    <div class="auth-body">

        <h2>Verificar código</h2>

        <?php if(isset($_SESSION['msg_recuperacion'])): ?>
            <div class="auth-alert auth-alert-info">
                <?php echo $_SESSION['msg_recuperacion']; unset($_SESSION['msg_recuperacion']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_verificacion'])): ?>
            <div class="auth-alert auth-alert-error">
                <?php echo $_SESSION['error_verificacion']; unset($_SESSION['error_verificacion']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo getUrl('usuarios','usuarios','validarCodigo',false,'ajax'); ?>" method="POST">

            <label class="auth-label">Código de 6 dígitos</label>

            <input type="text" class="auth-input" name="codigo" required maxlength="6" pattern="[0-9]{6}" placeholder="000000"b oninvalid="this.setCustomValidity('Ingresa el código de 6 dígitos')"
            oninput="this.setCustomValidity('')">

            <button type="submit" class="auth-btn" onclick="this.disabled=true; this.form.submit();"> Verificar código</button>

        </form>

        <div class="auth-link">
            <a href="../../view/recuperarContrasena/SolicitarCodigo.php">
                Solicitar nuevo código
            </a>
        </div>

    </div>

</div>

</body>
</html>