<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Control de Entrada/Salida</title>
    <link rel="shortcut icon" href="favicon.png"> <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f0f2f5; }
        .login-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; color: #1c1e21; margin-top: 0; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: #0866ff; color: white; border: none; font-weight: bold; cursor: pointer; }
        .btn-submit:hover { background-color: #0551cc; }
        .register-link { text-align: center; display: block; margin-top: 15px; font-size: 14px; color: #0866ff; text-decoration: none; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Login</h2>
    <form action="proceso_login.php" method="POST">
        Usuario: <input type="text" name="username" required><br>
        Contraseña: <input type="password" name="password" required><br>
        <input type="submit" value="Entrar" class="btn-submit"><br>
    </form>
    <a href="registro.php" class="register-link">¿No tienes cuenta? Regístrate aquí</a>
</div>

</body>
</html>