<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6F" crossorigin="anonymous">
    <style>
        body{
            background: #f0f2f5;
            height: 100vh;
            display: flex;
            align-self: center;
            justify-content: center;
        }
        .login-container{
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <div class="card login-card">
        
        <div class="card-body">
            <h3 class="text-center mb-4">Iniciar sesión</h3>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" class="form-control" id="" name="" required placeholder="Ingrese su documento">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                <div class="text-center mt-3">
                    <a href="">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>