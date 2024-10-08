<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Almacén de Libros</title>
    <link rel="stylesheet" href="css/estilo_login.css">
</head>
<body style="background-image: url('img/wallhaven-oxjeom.jpg'); background-size: cover; background-position: center;">
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form action="validar_login.php" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>
</html>