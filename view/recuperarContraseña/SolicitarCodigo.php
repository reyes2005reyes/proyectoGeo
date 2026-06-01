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
    <style>
        body {
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            width: 100%;
            max-width: 450px;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="card">
    <img src="../../web/assets/img/logoGeo.png" alt="logo" class="d-block mx-auto mb-3" height="80">
    <h4 class="text-center mb-2">Recuperar contraseña</h4>
    <p class="text-center text-muted mb-4">Ingresa tu número de documento y correo electrónico registrado.</p>

    <?php if(isset($_SESSION['error_recuperacion'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error_recuperacion']; unset($_SESSION['error_recuperacion']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo getUrl('recuperarContrañera', 'RecuperarContraseña', 'enviarCodigo', false, 'ajax'); ?>" method="POST">

        <div class="mb-3">
            <label class="form-label">Número de documento</label>
            <input type="number" class="form-control" name="numero_documento" required
                min="1" max="9999999999"
                placeholder="Ej: 1023456789"
                oninvalid="this.setCustomValidity('Ingresa tu número de documento')"
                oninput="this.setCustomValidity('')">
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="correo" required
                placeholder="juan@correo.com"
                oninvalid="this.setCustomValidity('Ingresa tu correo electrónico')"
                oninput="this.setCustomValidity('')">
        </div>

        <!-- Error 2: botón se bloquea tras primer clic -->
        <button type="submit" class="btn btn-primary w-100"
            onclick="this.disabled=true; this.form.submit();">
            Enviar código
        </button>

        <div class="text-center mt-3">
            <a href="/proyectoGeo/web/login.php">Volver al inicio de sesión</a>
        </div>
    </form>
</div>
<script src="../../web/assets/js/core/bootstrap.bundle.min.js"></script>
</body>
</html>