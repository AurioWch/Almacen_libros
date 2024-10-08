<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];

    $conexion = connection();
    
    $sql = "SELECT id, username, password, rol FROM usuarios WHERE username = :username";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($password === $row['password']) { // Cambiado a una comparación directa
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_nombre'] = $row['username'];
            $_SESSION['usuario_rol'] = $row['rol'];
            header("Location: home.php");
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
    
    if (isset($error)) {
        $_SESSION['error'] = $error;
        header("Location: login_index.php");
        exit();
    }
}
?>