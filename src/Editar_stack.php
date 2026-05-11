<?php
    session_start();
    if($_SESSION['id_usuario'] != 1) {
        $_SESSION['mensaje'] = "Acceso denegado. Solo el administrador puede acceder a esta sección.";
        header("Location: Inicio.php");
        exit();
    }
    $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }  


    if(isset($_GET['id'])) {
        $id_producto = (int)$_GET['id'];
        $query = "SELECT id_producto, nombre, descripcion, foto, precio, stock, origen, fabricante FROM Productos WHERE id_producto = $id_producto";
        $resultado = $conexion->query($query);

        if($resultado && $resultado->num_rows > 0) {
            $producto = $resultado->fetch_assoc();
        } else {
            $_SESSION['mensaje'] = "Producto no encontrado.";
            header("Location: Registro.php");
            exit();
        }
    } else {
        $_SESSION['mensaje'] = "ID de producto no especificado.";
        header("Location: Registro.php");
        exit();
    }

    $fila = $producto;

    if (!empty($fila['foto'])) {
                            $finfo = new finfo(FILEINFO_MIME_TYPE);
                            $tipoMime = $finfo->buffer($fila['foto']); 
                            $imagenBase64 = base64_encode($fila['foto']);
                            $src = 'data:' . $tipoMime . ';base64,' . $imagenBase64;
                        } else {
                            $src = 'https://via.placeholder.com/300x200?text=Sin+Imagen';
                        }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Editar Stock</title>
</head>
<body>
    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h1>Editar Producto</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
            <div class="mb-3">
                <h3 class="text-info">Producto: <?php echo htmlspecialchars($producto['nombre']); ?></h3>
            </div>
            <div class="mb-3">
                <label for="Nombre" class="form-label">Nombre actual:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>

                <label for="stock" class="form-label">Stock actual:</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" min="0" required>

                <label for="descripcion" class="form-label">Descripción actual:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>

                <label for="precio" class="form-label">Precio actual:</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" min="0" required>

                <label for="origen" class="form-label">Origen actual:</label>
                <input type="text" class="form-control" id="origen" name="origen" value="<?php echo htmlspecialchars($producto['origen']); ?>" required>

                <label for="fabricante" class="form-label">Fabricante actual:</label>
                <input type="text" class="form-control" id="fabricante" name="fabricante" value="<?php echo htmlspecialchars($producto['fabricante']); ?>" required>
            
                <h3 class="text-info mt-4">Actualizar foto del producto:</h3>
                <p>Foto actual del producto:</p>
                <img src="<?php echo $src; ?>" alt="Foto del producto" class="img-fluid mb-3">
                <input type="file" class="form-control" id="foto" name="foto" accept=".png, .jpg, .jpeg">
                <label for="foto">Foto del producto en PNG, JPG o JPEG</label>
            </div>
            <a href="Registro.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        </form>
    </div>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = (int)$_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $fabricante = $_POST['fabricante'];
    $origen = $_POST['origen'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($mime, $allowed)) {
            $_SESSION['mensaje'] = "Archivo no permitido. Solo PNG o JPG.";
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['mensaje'] = "El archivo es demasiado grande (máx 2MB).";
        } else {
            $data = file_get_contents($file['tmp_name']);
            $stmt = $conexion->prepare("UPDATE Productos SET nombre=?, descripcion=?, precio=?, stock=?, fabricante=?, origen=?, foto=? WHERE id_producto=?");
            $null = NULL;
            $stmt->bind_param("ssdissbi", $nombre, $descripcion, $precio, $stock, $fabricante, $origen, $null, $id_producto);
            $stmt->send_long_data(6, $data);
            
            ejecutarYRedirigir($stmt);
        }
    } else {
        $stmt = $conexion->prepare("UPDATE Productos SET nombre=?, descripcion=?, precio=?, stock=?, fabricante=?, origen=? WHERE id_producto=?");
        $stmt->bind_param("ssdissi", $nombre, $descripcion, $precio, $stock, $fabricante, $origen, $id_producto);
        
        ejecutarYRedirigir($stmt);
    }
}

function ejecutarYRedirigir($stmt) {
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Producto actualizado exitosamente.";
        echo "<script>window.location.href = 'Registro.php';</script>";
        exit();
    } else {
        $_SESSION['mensaje'] = "Error al ejecutar: " . $stmt->error;
    }
    $stmt->close();
}
?>
</body>
</html>