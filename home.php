<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login_index.php");
    exit();
}

$nombre_usuario = $_SESSION['usuario_nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Almacén de Libros</title>
    <link rel="stylesheet" href="css/estilo_home.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Almacén de Libros</h2>
        </div>
        <nav>
            <ul>
                <li><a href="home.php">Inicio</a></li>
                <li><a href="libros.php">Ver Libros</a></li>
                <li><a href="agregar_libro.php">Agregar Libro</a></li>
                <li><a href="perfil.php">Mi Perfil</a></li>
                <li><a href="#" onclick="confirmarCierreSesion()">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <span class="bienvenido">Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></span>
        </header>
        <main class="container">
            <h1>Panel de Control</h1>
           
        </main>
    </div>

    <script>
function confirmarCierreSesion() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        window.location.href = 'cerrar_sesion.php';
    }
}

function abrirVentanaModal(url) {
    window.open(url, 'Agregar Libro', 'width=600,height=800,resizable=yes,scrollbars=yes');
}
</script>

</body>
</html>