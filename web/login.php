<?php
// Error 5: verificar que los recursos del sistema están disponibles
if (!file_exists('../lib/helpers.php') || !file_exists('../view/partials/header.php')) {
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
    include_once '../lib/helpers.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        body{
            background: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container{
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login{
            width: 320px;
            padding: 30px;
            background: white;
            border-radius: 12px;
        }


    </style>
</head>
<body>
    <div class="login">
        <img src="assets/img/logoGeo.png" alt="logo" class="navbar-brand d-block mx-auto mb-3" height="100">
        <h3 class="text-center mb-4">SIAV</h3>
        <div class="card-body">
            <h3 class="text-center mb-4">Iniciar sesión</h3>
            <form action="<?php echo getUrl("acceso","acceso", "login", false, "ajax"); ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" class="form-control" id="" name="numero_documento" required placeholder="Ingrese su documento" oninvalid="this.setCustomValidity('Por favor ingresa tu número de documento')"oninput="this.setCustomValidity('')">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="contrasena" required placeholder="Ingrese su contraseña" oninvalid="this.setCustomValidity('Por favor ingresa tu contraseña')" oninput="this.setCustomValidity('')">
                </div>

                <?php
                if(isset($_SESSION['error'])){
                    echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error']."</div>";
                    unset($_SESSION['error']);
                }
                if(isset($_SESSION['exito_registro'])) {
                    echo "<div class='alert alert-success' role='alert'>".$_SESSION['exito_registro']."</div>";
                    unset($_SESSION['exito_registro']);
                }
                if(isset($_SESSION['exito_login'])) {
                    echo "<div class='alert alert-success' role='alert'>".$_SESSION['exito_login']."</div>";
                    unset($_SESSION['exito_login']);
                }
                ?>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                <div class="text-center mt-3">
                    <a href="../view/recuperarContraseña/SolicitarCodigo.php">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="text-center mt-2">
                    <a href="../view/registro/Registro.php">¿No tienes una cuenta? Regístrate</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>