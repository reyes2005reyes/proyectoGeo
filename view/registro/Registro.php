<?php
// Error 5: Error al cargar la interfaz de registro por pérdida de conexión o recursos del sistema.
if (!file_exists('../../lib/helpers.php') || !file_exists('../../view/partials/header.php')) {
?>
<!DOCTYPE html>
<html>
<head><title>Error</title></head>
<body style="display:flex;align-items:center;justify-content:center;height:100vh;flex-direction:column;">
    <h3>No fue posible cargar la página</h3>
    <p>Hay un problema con los recursos del sistema.</p>
    <button onclick="location.reload()" class="btn btn-primary">Reintentar</button>
</body>
</html>
<?php
    exit;
}
?>

<?php
    include_once '../../lib/helpers.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../../web/assets/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 0;
        }
        .card {
            width: 100%;
            max-width: 550px;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: #1572e8;
            border: none;
        }
    </style>
</head>
<body>

<div class="card">
    <img src="../../web/assets/img/logoGeo.png" alt="logo" class="navbar-brand d-block mx-auto mb-3" height="100">
    <h3 class="text-center mb-4">SIAV - Crear cuenta</h3>
    <form action="<?php echo getUrl('registroUsuario','registroUsuario','postRegistrar', false, '../../web/ajax'); ?>" method="POST">

        <div class="mb-3">
            <label class="form-label">Tipo de documento</label>
            <select class="form-select" name="id_tipo_documento" required oninvalid="this.setCustomValidity('Selecciona un tipo de documento')" oninput="this.setCustomValidity('')">
                <option value="">Seleccione...</option>
                <option value="1">Cédula de Ciudadanía</option>
                <option value="2">Cédula de Extranjería</option>
                <option value="3">Pasaporte</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Número de identificación</label>
            <input type="number" class="form-control" name="numero_documento" required min="1"max="9999999999" placeholder="Ej: 1023456789" oninvalid="this.setCustomValidity('Ingresa un número de identificación válido')" oninput="this.setCustomValidity('')">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Primer nombre</label>
                <input type="text" class="form-control" name="primer_nombre" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Segundo nombre "Opcional"</label>
                <input type="text" class="form-control" name="segundo_nombre">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Primer apellido</label>
                <input type="text" class="form-control" name="primer_apellido" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Segundo apellido "Opcional"</label>
                <input type="text" class="form-control" name="segundo_apellido">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="correo" required placeholder="juan@correo.com">
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="number" class="form-control" name="telefono" required min="1000000000" max="9999999999" placeholder="3117893769.." oninvalid="this.setCustomValidity('Ingresa un número de teléfono válido')" oninput="this.setCustomValidity('')">
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección de residencia</label>
            <input type="text" class="form-control" name="direccion" required placeholder="Carrera 46 #20-20.." minlength="5" maxlength="50" oninvalid="this.setCustomValidity('Ingresa una dirección válida')" oninput="this.setCustomValidity('')">
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="contrasena" required minlength="8" maxlength="20" placeholder="Mínimo 8 caracteres" oninvalid="this.setCustomValidity('La contraseña debe tener mínimo 8 caracteres')" oninput="this.setCustomValidity('')">
        </div>
        <?php
        if(isset($_SESSION['error_registro'])){
            echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error_registro']."</div>";
            unset($_SESSION['error_registro']);
        }
        
        ?>

        <button type="submit" class="btn btn-primary w-100 mt-2">Registrarse</button>

        <div class="text-center mt-3">
            <a href="../../web/login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>

    </form>
</div>

<script src="../../web/assets/js/core/bootstrap.bundle.min.js"></script>
</body>
</html>