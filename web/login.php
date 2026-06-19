<?php
if (!file_exists('../lib/helpers.php') || !file_exists('../view/partials/header.php')) {
?>
<!DOCTYPE html>
<html>
<head><title>Error</title></head>
<body style="display:flex;align-items:center;justify-content:center;height:100vh;flex-direction:column;">
    <h3>No fue posible cargar la página</h3>
    <p>Hay un problema con los recursos del sistema.</p>
    <button onclick="location.reload()">Reintentar</button>
</body>
</html>
<?php
    exit;
}
?>
<?php include_once '../lib/helpers.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAV Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../web/assets/css/login.css">
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <img class="logo" src="assets/img/logo.png" alt="Logo SIAV">
        <p>Sistema de Información de Accidentalidad Vial</p>
    </div>
    <div class="login-body">
        <h2>Iniciar sesión</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alerta alerta-error">
                <i class="fas fa-circle-exclamation"></i>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['exito_registro'])): ?>
            <div class="alerta alerta-success">
                <i class="fas fa-circle-check"></i>
                <?php echo htmlspecialchars($_SESSION['exito_registro']); unset($_SESSION['exito_registro']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['exito_login'])): ?>
            <div class="alerta alerta-success">
                <i class="fas fa-circle-check"></i>
                <?php echo htmlspecialchars($_SESSION['exito_login']); unset($_SESSION['exito_login']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo getUrl('acceso','acceso','login', false, 'ajax'); ?>" method="POST" novalidate >

            <div class="input-group-icon">
                <label for="numero_documento">Número de documento <i class="text-danger">*</i></label>
                <i class="fas fa-id-card icon"></i>
                <input type="text" id="numero_documento" name="numero_documento" placeholder="Ingrese su documento" required oninvalid="this.setCustomValidity('Por favor ingresa tu número de documento')" oninput="this.setCustomValidity('')">
            </div>

            <div class="input-group-icon">
                <label for="password">Contraseña <i class="text-danger">*</i></label>
                <i class="fas fa-lock icon"></i>
                <input type="password" id="password" name="contrasena" placeholder="Ingrese su contraseña" required oninvalid="this.setCustomValidity('Por favor ingresa tu contraseña')" oninput="this.setCustomValidity('')">
                <button type="button" class="toggle-pass" onclick="togglePassword()" aria-label="Mostrar u ocultar contraseña">
                    <i class="fas fa-eye" id="iconOjo"></i>
                </button>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-right-to-bracket me-2"></i>Ingresar
            </button>
        </form>

        <hr class="divider">

        <div class="login-links">
            <a href="../view/recuperarContrasena/SolicitarCodigo.php"> <i class="fas fa-key me-1"></i>¿Olvidaste tu contraseña?</a>
            <a href="../view/registro/Registro.php"><i class="fas fa-user-plus me-1"></i>¿No tienes cuenta? Regístrate
            </a>
        </div>
    </div>

    <div class="login-footer">
        <p>Secretaría de Movilidad, Todos los derechos reservados</p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('iconOjo');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

</body>
</html>