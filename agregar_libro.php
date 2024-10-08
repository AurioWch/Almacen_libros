<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login_index.php");
    exit();
}

$cnx = connection();

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
    
    // Manejo de la imagen
    $imagen_portada = '';
    if(isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] == 0) {
        $imagen_nombre = $_FILES['imagen_portada']['name'];
        $imagen_tmp = $_FILES['imagen_portada']['tmp_name'];
        $imagen_destino = 'img/' . $imagen_nombre;
        move_uploaded_file($imagen_tmp, $imagen_destino);
        $imagen_portada = $imagen_destino;
    }
    
    $sql = "INSERT INTO libros (titulo, autor, isbn, stock, fecha_publicacion, precio, editorial, descripcion, imagen_portada, categoria_id) 
            VALUES (:titulo, :autor, :isbn, :stock, :fecha_publicacion, :precio, :editorial, :descripcion, :imagen_portada, :categoria_id)";
    
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':fecha_publicacion', $fecha_publicacion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':editorial', $editorial);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':imagen_portada', $imagen_portada);
    $stmt->bindParam(':categoria_id', $categoria_id);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Libro añadido con éxito');
                window.location.href = 'libros.php';
              </script>";
        exit();
    } else {
        echo "Error al añadir el libro.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Libro - Almacén de Libros</title>
    <link rel="stylesheet" href="css/estilo_home.css">
    <link rel="stylesheet" href="css/estilo_libros.css">
    <link rel="stylesheet" href="css/estilo_agregar_libro.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Nuevo Libro</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="form-container">
                <div class="form-column">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required>
                    
                    <label for="autor">Autor:</label>
                    <input type="text" id="autor" name="autor" required>
                    
                    <label for="isbn">ISBN:</label>
                    <input type="text" id="isbn" name="isbn" required>
                    
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" required>
                    
                    <label for="fecha_publicacion">Fecha de Publicación:</label>
                    <input type="date" id="fecha_publicacion" name="fecha_publicacion" required>
                </div>
                <div class="form-column">
                    <label for="precio">Precio:</label>
                    <input type="number" id="precio" name="precio" step="0.01" required>
                    
                    <label for="editorial">Editorial:</label>
                    <input type="text" id="editorial" name="editorial" required>
                    
                    <label for="categoria_id">Categoría:</label>
                    <select id="categoria_id" name="categoria_id" required>
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre_categoria']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>
                    
                    <label for="imagen_portada">Imagen de Portada:</label>
                    <input type="file" id="imagen_portada" name="imagen_portada" accept="image/*">
                </div>
            </div>
            <input type="submit" value="Agregar Libro">
        </form>
    </div>
</body>
</html>