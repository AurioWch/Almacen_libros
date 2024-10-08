<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login_index.php");
    exit();
}

$cnx = connection();

// Manejar la eliminación del libro
if (isset($_GET['delete_id'])) {
    $isbn_to_delete = $_GET['delete_id'];
    $sql_delete = "DELETE FROM libros WHERE isbn = :isbn";
    $stmt = $cnx->prepare($sql_delete);
    $stmt->bindParam(':isbn', $isbn_to_delete, PDO::PARAM_STR);
    if ($stmt->execute()) {
        // Redirigir para evitar reenvíos del formulario
        header("Location: libros.php?mensaje=Libro eliminado con éxito");
        exit();
    } else {
        $error_mensaje = "Error al eliminar el libro";
    }
}



$sql = "SELECT titulo, autor, fecha_publicacion  , isbn, precio  , 
        descripcion  , stock , editorial ,  imagen_portada FROM libros";
$resultado = $cnx->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Libros - Almacén de Libros</title>
    <link rel="stylesheet" href="css/estilo_home.css">
    <link rel="stylesheet" href="css/estilo_libros.css">
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
            <span class="bienvenido">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
        </header>
        <main class="container">
            <h1>Listado de Libros</h1>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Stock</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
    <?php while ($row = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['titulo']); ?></td>
            <td><?php echo htmlspecialchars($row['autor']); ?></td>
            <td><?php echo htmlspecialchars($row['isbn']); ?></td>
            <td><?php echo htmlspecialchars($row['stock']); ?></td>
            <td>
                <button onclick="mostrarDetalles('<?php echo htmlspecialchars(json_encode($row)); ?>')" class="btn-detalles">Detalles</button>
                <a href="editar_libro.php?isbn=<?php echo htmlspecialchars($row['isbn']); ?>" class="btn-editar">Editar</a>
                <a href="?delete_id=<?php echo htmlspecialchars($row['isbn']); ?>" 
                   class="btn-eliminar"
                   onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?');">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>
            </table>
        </main>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Detalles del Libro</h2>
            <p id="modal-details"></p>
        </div>
    </div>

    <script>
    function confirmarCierreSesion() {
        if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
            window.location.href = 'cerrar_sesion.php';
        }
    }

    var modal = document.getElementById("modal");
    var span = document.getElementsByClassName("close")[0];

    function mostrarDetalles(datos) {
    var libro = JSON.parse(datos);
    var detalles = "Título: " + libro.titulo + "<br>" +
                   "Autor: " + libro.autor + "<br>" +
                   "Fecha de Publicación: " + libro.fecha_publicacion + "<br>" +
                   "ISBN: " + libro.isbn + "<br>" +     
                   "Precio: " + libro.precio + "<br>" +                                     
                   "Stock: " + libro.stock + "<br>" + 
                   "Editorial: " + libro.editorial + "<br>" +                     
                   "Descripción: " + libro.descripcion + "<br><br>" + 
                   "<img src='" + libro.imagen_portada + "' alt='Portada del libro' style='max-width: 200px; max-height: 300px;'>";

    document.getElementById("modal-details").innerHTML = detalles;
    modal.style.display = "block";
}

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
</body>
</html>