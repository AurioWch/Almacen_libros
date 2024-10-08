<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login_index.php");
    exit();
}

$cnx = connection();

$isbn = $_GET['isbn'] ?? '';
$mensaje = '';

// Obtener categorías de la base de datos
$sql_categorias = "SELECT id, nombre_categoria FROM categorias";
$stmt_categorias = $cnx->prepare($sql_categorias);
$stmt_categorias->execute();
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $isbn = $_POST['isbn'];
    $stock = $_POST['stock'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $precio = $_POST['precio'];
    $editorial = $_POST['editorial'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria_id'];
    
    $sql = "UPDATE libros SET titulo = :titulo, autor = :autor, stock = :stock, 
            fecha_publicacion = :fecha_publicacion, precio = :precio, 
            editorial = :editorial, descripcion = :descripcion, categoria_id = :categoria_id
            WHERE isbn = :isbn";
    
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':fecha_publicacion', $fecha_publicacion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':editorial', $editorial);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':categoria_id', $categoria_id);
    
    if ($stmt->execute()) {
        $mensaje = "Libro actualizado con éxito";
    } else {
        $mensaje = "Error al actualizar el libro";
    }
}

if ($isbn) {
    $sql = "SELECT * FROM libros WHERE isbn = :isbn";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->execute();
    $libro = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Almacén de Libros</title>
    <link rel="stylesheet" href="css/estilo_home.css">
    <link rel="stylesheet" href="css/estilo_libros.css">
    <link rel="stylesheet" href="css/estilo_agregar_libro.css">
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
            <span class="bienvenido">Editar Libro</span>
        </header>
        <main class="container">
            <h1>Editar Libro</h1>
            <?php if ($mensaje): ?>
                <p><?php echo $mensaje; ?></p>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-container">
                    <div class="form-column">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>
                        
                        <label for="autor">Autor:</label>
                        <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
                        
                        <label for="isbn">ISBN:</label>
                        <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($libro['isbn']); ?>" readonly>
                        
                        <label for="stock">Stock:</label>
                        <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($libro['stock']); ?>" required>
                        
                        <label for="fecha_publicacion">Fecha de Publicación:</label>
                        <input type="date" id="fecha_publicacion" name="fecha_publicacion" value="<?php echo htmlspecialchars($libro['fecha_publicacion']); ?>" required>
                    </div>
                    <div class="form-column">
                        <label for="precio">Precio:</label>
                        <input type="number" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($libro['precio']); ?>" required>
                        
                        <label for="editorial">Editorial:</label>
                        <input type="text" id="editorial" name="editorial" value="<?php echo htmlspecialchars($libro['editorial']); ?>" required>
                        
                        <label for="categoria_id">Categoría:</label>
                        <select id="categoria_id" name="categoria_id" required>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['id']; ?>" <?php echo ($categoria['id'] == $libro['categoria_id']) ? 'selected' : ''; ?>>
                                    <?php echo $categoria['nombre_categoria']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($libro['descripcion']); ?></textarea>
                    </div>
                </div>
                <input type="submit" value="Actualizar Libro">
            </form>
        </main>
    </div>

    <script>
    function confirmarCierreSesion() {
        if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
            window.location.href = 'cerrar_sesion.php';
        }
    }
    </script>
</body>
</html>