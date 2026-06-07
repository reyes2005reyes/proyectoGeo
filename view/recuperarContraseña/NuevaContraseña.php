<?php
    include_once '../../lib/helpers.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña</title>
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
    <h4 class="text-center mb-4">Nueva contraseña</h4>

    <?php if(isset($_SESSION['error_nueva'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error_nueva']; unset($_SESSION['error_nueva']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo getUrl('usuarios', 'usuarios', 'guardarContrasena', false, 'ajax'); ?>" method="POST">

        <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control" name="nueva_contrasena" required minlength="8" maxlength="20" placeholder="Mínimo 8 caracteres" oninvalid="this.setCustomValidity('La contraseña debe tener mínimo 8 caracteres')"oninput="this.setCustomValidity('')">
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" name="confirmar_contrasena" required minlength="8" maxlength="20" placeholder="Repite la contraseña" oninvalid="this.setCustomValidity('Confirma tu contraseña')" oninput="this.setCustomValidity('')">
        </div>

        <button type="submit" class="btn btn-primary w-100">Guardar contraseña</button>
    </form>
</div>
<script src="../../web/assets/js/core/bootstrap.bundle.min.js"></script>
</body>
</html>