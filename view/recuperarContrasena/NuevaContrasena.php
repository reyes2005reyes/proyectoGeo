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
<link rel="stylesheet" href="../../web/assets/css/recuperarContrasena.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<div class="auth-card">

    <div class="auth-header">
        <img src="../../web/assets/img/logo.png" alt="logo">
        <p>Restablecer contraseña</p>
    </div>
    <div class="auth-body">

        <h2>Nueva contraseña</h2>
        <?php if(isset($_SESSION['error_nueva'])): ?>
            <div class="auth-alert auth-alert-error">
                <?php echo htmlspecialchars($_SESSION['error_nueva']); unset($_SESSION['error_nueva']); ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo getUrl('usuarios','usuarios','guardarContrasena',false,'ajax'); ?>" method="POST">

            <div class="input-group-icon auth-password-toggle">
                <label>Nueva contraseña</label>
                <i class="fa fa-lock icon"></i>
                <input type="password" class="auth-input auth-input-password" name="nueva_contrasena" id="pass1" required minlength="8" maxlength="20" placeholder="Mínimo 8 caracteres">

                <i class="fa fa-eye auth-eye" onclick="togglePass('pass1', this)"></i>
            </div>
            <div class="input-group-icon auth-password-toggle">
                <label>Confirmar contraseña</label>
                <i class="fa fa-lock icon"></i>

                <input type="password" class="auth-input auth-input-password" name="confirmar_contrasena" id="pass2" required nminlength="8" maxlength="20" placeholder="Repite la contraseña">
                <i class="fa fa-eye auth-eye" onclick="togglePass('pass2', this)"></i>
            </div>

            <button type="submit" class="auth-btn">
                Guardar contraseña
            </button>

        </form>

    </div>

</div>

<script>
function togglePass(id, icon){
    const input = document.getElementById(id);

    if(input.type === "password"){
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }else{
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>