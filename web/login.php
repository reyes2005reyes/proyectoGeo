
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
            animation: aparecer 0.8s ease;
        }

        @keyframes aparecer{
        from{
        opacity: 0;
        transform: translateY(40px);
        }
        to{
        opacity: 1;
        transform: translateY(0);
        }
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
                    <input type="text" class="form-control" id="" name="numero_documento" required placeholder="Ingrese su documento">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="contrasena" required placeholder="Ingrese su contraseña">
                </div>

                <?php
                if(isset($_SESSION['error'])){
                    echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error']."</div>";
                    unset($_SESSION['error']);
                }
                ?>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                <div class="text-center mt-3">
                    <a href="">¿Olvidaste tu contraseña?</a>
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