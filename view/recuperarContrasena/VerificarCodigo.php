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
    <h4 class="text-center mb-2">Verificar código</h4>

    <?php if(isset($_SESSION['msg_recuperacion'])): ?>
        <div class="alert alert-info">
            <?php echo $_SESSION['msg_recuperacion']; unset($_SESSION['msg_recuperacion']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_verificacion'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error_verificacion']; unset($_SESSION['error_verificacion']); ?>
        </div>
    <?php endif; ?>


    <form action="<?php echo getUrl('usuarios', 'usuarios', 'validarCodigo', false, 'ajax'); ?>" method="POST">

        <div class="mb-3">
            <label class="text-center mb-2">Código de 6 dígitos</label>
            <input type="text" class="form-control text-center" name="codigo" required maxlength="6" pattern="[0-9]{6}" placeholder="000000"oninvalid="this.setCustomValidity('Ingresa el código de 6 dígitos')" oninput="this.setCustomValidity('')">
        </div>

        <button type="submit" class="btn btn-primary w-100"
            onclick="this.disabled=true; this.form.submit();">
            Verificar código
        </button>

        <div class="text-center mt-3">
            <a href="../../view/recuperarContrasena/SolicitarCodigo.php">Solicitar nuevo código</a>
        </div>
    </form>
</div>


</script>
<script src="../../web/assets/js/core/bootstrap.bundle.min.js"></script>
</body>
</html>