<?php
if (!file_exists('../../lib/helpers.php') || !file_exists('../../view/partials/header.php')) {
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
<?php exit; } ?>
<?php include_once '../../lib/helpers.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAV Crear cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../web/assets/css/registroUsuario.css">
    
</head>
<body>

<div class="registro-card">
    <div class="registro-header">
        <img src="../../web/assets/img/logo.png" alt="Logo SIAV">
        <h1>SIAV</h1>
        <p>Sistema de Información de Accidentalidad Vial</p>
    </div>

    <div class="registro-body">
        <h2>Crear cuenta</h2>
        <?php if (isset($_SESSION['error_registro'])): ?>
            <div class="alerta-error">
                <i class="fas fa-circle-exclamation"></i>
                <?php echo htmlspecialchars($_SESSION['error_registro']); unset($_SESSION['error_registro']); ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo getUrl('usuarios','usuarios','postRegistrar', false, '../../web/ajax'); ?>" method="POST">
            <p class="seccion-titulo"><i class="fas fa-id-card me-1"></i>Identificación</p>

            <div class="field-wrap no-icon">
                <label for="id_tipo_documento">Tipo de documento <i class="text-danger">*</i></label>
                <select id="id_tipo_documento" name="id_tipo_documento" required oninvalid="this.setCustomValidity('Selecciona un tipo de documento')" oninput="this.setCustomValidity('')">
                    <option value="">Seleccione el tipo de documento</option>
                    <option value="1">Cédula de Ciudadanía</option>
                    <option value="2">Cédula de Extranjería</option>
                    <option value="3">Pasaporte</option>
                </select>
            </div>

            <div class="field-wrap">
                <label for="numero_documento">Número de identificación <i class="text-danger">*</i></label>
                <i class="fas fa-hashtag fi"></i>
                <input type="number" id="numero_documento" name="numero_documento" placeholder="10234567..." required min="100000" max="999999999999" oninvalid="this.setCustomValidity('Ingresa un número de identificación válido')"
                    oninput="this.setCustomValidity('')">
            </div>

            <p class="seccion-titulo"><i class="fas fa-user me-1"></i>Nombres y apellidos</p>
            <div class="row g-3">
                <div class="col-6">
                    <div class="field-wrap">
                        <label for="primer_nombre">Primer nombre <i class="text-danger">*</i></label>
                        <i class="fas fa-user fi"></i>
                        <input type="text" id="primer_nombre" name="primer_nombre" required placeholder="Tu nombre" minlength="2" maxlength="30" oninvalid="this.setCustomValidity('Ingresa tu primer nombre')" oninput="this.setCustomValidity('')">
                    </div>
                </div>
                <div class="col-6">
                    <div class="field-wrap">
                        <label>Segundo nombre <span class="badge-opt">Opcional</span></label>
                        <i class="fas fa-user fi"></i>
                        <input type="text" name="segundo_nombre" placeholder="Tu segundo nombre" minlength="2" maxlength="30" oninvalid="this.setCustomValidity('Ingresa tu segundo nombre')" oninput="this.setCustomValidity('')">
                    </div>
                </div>
                <div class="col-6">
                    <div class="field-wrap">
                        <label for="primer_apellido">Primer apellido <i class="text-danger">*</i></label>
                        <i class="fas fa-user fi"></i>
                        <input type="text" id="primer_apellido" name="primer_apellido" required placeholder="Tu primer apellido" minlength="2" maxlength="30" oninvalid="this.setCustomValidity('Ingresa tu primer apellido')" oninput="this.setCustomValidity('')">
                    </div>
                </div>
                <div class="col-6">
                    <div class="field-wrap">
                        <label>Segundo apellido <span class="badge-opt">Opcional</span></label>
                        <i class="fas fa-user fi"></i>
                        <input type="text" name="segundo_apellido" placeholder="Tu segundo apellido" minlength="2" maxlength="30" oninvalid="this.setCustomValidity('Ingresa tu segundo apellido')" oninput="this.setCustomValidity('')">
                    </div>
                </div>
            </div>

            <p class="seccion-titulo"><i class="fas fa-address-book me-1"></i>Información de contacto</p>

            <div class="field-wrap">
                <label for="correo">Correo electrónico <i class="text-danger">*</i></label>
                <i class="fas fa-envelope fi"></i>
                <input type="email" id="correo" name="correo" required placeholder="tuCorreo@correo.com" oninvalid="this.setCustomValidity('Ingresa un correo electrónico válido')" oninput="this.setCustomValidity('')">
            </div>

            <div class="field-wrap">
                <label for="telefono">Teléfono <i class="text-danger">*</i></label>
                <i class="fas fa-phone fi"></i>
                <input type="number" id="telefono" name="telefono" required min="1000000000" max="9999999999" placeholder="311789...." oninvalid="this.setCustomValidity('Ingresa un número de teléfono válido')"
                    oninput="this.setCustomValidity('')">
            </div>

            <div class="field-wrap">
                <label for="direccion">Dirección de residencia <i class="text-danger">*</i></label>
                <i class="fas fa-map-marker-alt fi"></i>
                <input type="text" id="direccion" name="direccion" required placeholder="Carrera 46 #20-20" minlength="5" maxlength="50" oninvalid="this.setCustomValidity('Ingresa una dirección válida')"
                    oninput="this.setCustomValidity('')">
            </div>

            <p class="seccion-titulo"><i class="fas fa-lock me-1"></i>Seguridad</p>
            <div class="field-wrap">
                <label for="contrasena">Contraseña <i class="text-danger">*</i></label>
                <i class="fas fa-lock fi"></i>
                <input type="password" id="contrasena" name="contrasena" required minlength="8" maxlength="20" placeholder="Mínimo 8 caracteres" oninvalid="this.setCustomValidity('La contraseña debe tener mínimo 8 caracteres')" oninput="this.setCustomValidity('')">
                <button type="button" class="toggle-pass" onclick="togglePass()" aria-label="Mostrar u ocultar contraseña">
                    <i class="fas fa-eye" id="iconOjo"></i>
                </button>
            </div>
            <button type="submit" class="btn-registro">
                <i class="fas fa-user-plus"></i> Crear cuenta
            </button>
        </form>
        <div class="link-login">
            <a href="../../web/login.php"><i class="fas fa-arrow-left me-1"></i>¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
    <div class="registro-footer">
        <p>Secretaría de Movilidad, Todos los derechos reservados</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass() {
    const input = document.getElementById('contrasena');
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