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
        $query = "SELECT id_producto, nombre, stock FROM Productos WHERE id_producto = $id_producto";
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
        <h1>Editar Stock del Producto</h1>
        <form method="POST">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
            <div class="mb-3">
                <h3 class="text-info">Producto: <?php echo htmlspecialchars($producto['nombre']); ?></h3>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock actual:</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" min="0" required>
            </div>
            <a href="Registro.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Stock</button>
        </form>
    </div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_producto = (int)$_POST['id_producto'];
            $nuevo_stock = (int)$_POST['stock'];

            $query_update = "UPDATE Productos SET stock = $nuevo_stock WHERE id_producto = $id_producto";
            if ($conexion->query($query_update) === TRUE) {
                $_SESSION['mensaje'] = "Stock actualizado exitosamente.";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el stock: " . $conexion->error;
            }
            echo "<script>window.location.href = 'Registro.php';</script>";
            exit();
        }
        ?>
</body>
</html>