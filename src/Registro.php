<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
    <body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container-fluid">
    <p class="navbar-brand">Libreria</p>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="Inicio.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Registro.php">Registro</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Consulta.php">Consulta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Carrito.php">Carrito</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Informacion.php">Información</a>
        </li>
      </ul>
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="Cerrar_Sesion.php">Cerrar sesión</a>
          </li>
        </ul>
      <?php else: ?>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="Cuenta_Nueva.php">Iniciar sesión</a>
          </li>
        </ul>
      <?php endif; ?>
      
    </div>
  </div>
</nav>

        <div class="container p-5 my-5 bg-dark text-white rounded">
            <h1>Registro de productos</h1>
            <p>En esta sección puedes registrar nuevos productos en nuestra tienda.</p>
        </div>

        <form method="post" class="was-validated container p-5 my-5 border rounded" enctype="multipart/form-data">
            <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" id="nombre" placeholder="Ingresa el nombre del producto" name="nombre" required>
                <label for="nombre">Nombre del producto</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe estar lleno.</div>
            </div>

            <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" id="descripcion" placeholder="Ingresa la descripción" name="descripcion" required>
                <label for="descripcion">Ingresa una descripción</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe estar lleno.</div>
            </div>


            <div class="form-floating mb-3 mt-3">
                <input type="file" class="form-control" id="foto"  name="foto" accept=".png, .jpg, .jpeg" required>
                <label for="foto">Foto del producto en PNG, JPG o JPEG</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe contener un archivo válido.</div>
            </div>

            <div class="form-floating mb-3 mt-3">
                <input type="number" class="form-control" id="precio" placeholder="Ingresa el precio" name="precio" required>
                <label for="precio">Precio en pesos mexicanos</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe estar lleno.</div>
            </div>

             <div class="form-floating mb-3 mt-3">
                <input type="number" class="form-control" id="stock" placeholder="Ingresa el stock" name="stock" required>
                <label for="stock">Stock disponible del producto</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe estar lleno.</div>
            </div>

             <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" id="fabricante" placeholder="Ingresa el fabricante" name="fabricante" required>
                <label for="fabricante">Fabricante</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe estar lleno.</div>
            </div>

             <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" id="origen" placeholder="Ingresa el origen" name="origen" required>
                <label for="origen">Origen</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe estar lleno.</div>
            </div>
                
            <button type="submit" class="btn btn-primary">Agregar producto</button>
        </form>
        <?php
            $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
            if ($conexion->connect_error) {
                die("Conexión fallida: " . $conexion->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $nombre = $_POST["nombre"];
                $descripcion = $_POST["descripcion"];
                $precio = $_POST["precio"];
                $stock = $_POST["stock"];
                $fabricante = $_POST["fabricante"];
                $origen = $_POST["origen"];

                $file = $_FILES['foto'];

                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $mensaje = "Error al subir el archivo : . {$file['error']})";  
                } else {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

                    if (!in_array($mime, $allowed)) {
                        $mensaje = "Archivo no permitido. Solo se permiten PNG, JPG o JPEG tipo detectado: $mime";
                    } elseif ($file['size'] > 2 * 1024 * 1024) {
                        $mensaje = "Archivo demasiado grande. El tamaño máximo permitido es de 2 MB.";
                    }else{
                        $data = file_get_contents($file['tmp_name']);
                        $stmt = $conexion->prepare("INSERT INTO Productos (nombre, descripcion, precio, stock, fabricante, origen, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        if(!$stmt) {
                            $mensaje = "Error al preparar: " . $conexion->error;
                        }else{
                            $null = NULL;
                            $stmt->bind_param("ssdiisb", $nombre, $descripcion, $precio, $stock, $fabricante, $origen, $null);
                            $stmt->send_long_data(6, $data);
                            if ($stmt->execute()) {
                                $mensaje = "Producto registrado exitosamente.";
                            } else {
                                $mensaje = "Error al ejecutar: " . $stmt->error;
                            }
                            $stmt->close();
                        }
                    }
                }
                echo "<div class='container p-3 my-3 alert alert-info'>$mensaje</div>";
            }
            $conexion->close();
        ?>
<div class="container p-5 my-5 bg-dark text-white rounded">
            <h1> Eliminar producto</h1>
            <p>En esta sección puedes eliminar productos de nuestra tienda.</p>
        </div>

        <div class="container">
            <?php
                $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
                if ($conexion->connect_error) {
                    die("Conexión fallida: " . $conexion->connect_error);
                }

                if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])){
                    $id = (int)$_GET['id'];
                    
                    $conexion->query("DELETE FROM Carrito WHERE id_producto = $id");
                    $conexion->query("DELETE FROM Historial_Compras WHERE id_producto = $id");

                    $stmt = $conexion->prepare("DELETE FROM Productos WHERE id_producto = ?");
                    
                    if(!$stmt) {
                        $mensaje = "Error al preparar: " . $conexion->error;
                    }else{
                        $stmt->bind_param("i", $id);
                        if ($stmt->execute()) {
                            echo "<script>window.location.href='Registro.php?mensaje=eliminado';</script>";
                            exit();
                        } else {
                            $mensaje = "Error al ejecutar: " . $stmt->error;
                            echo "<div class='container p-3 my-3 alert alert-danger'>$mensaje</div>";
                        }
                        $stmt->close();
                    }
                }

                if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado') {
                    echo "<div class='container p-3 my-3 alert alert-success'>Producto eliminado exitosamente.</div>";
                }

                $query = "SELECT id_producto, nombre, descripcion, precio, foto FROM Productos ORDER BY id_producto DESC";
                $productos = $conexion->query($query);
            ?>

            <div class="table-responsive">
                <table class="table table-dark table-striped table-bordered table-hover align-middle">
                    <tbody>
                    <?php
                    if($productos->num_rows > 0){
                        while ($fila = $productos->fetch_assoc()) {
                            if (!empty($fila['foto'])) {
                                $finfo = new finfo(FILEINFO_MIME_TYPE);
                                $tipoMime = $finfo->buffer($fila['foto']); 
                                $imagenBase64 = base64_encode($fila['foto']);
                                $src = 'data:' . $tipoMime . ';base64,' . $imagenBase64;
                            } else {
                                $src = 'https://via.placeholder.com/300x200?text=Sin+Imagen';
                            }

                            echo "<tr>";
                            echo "<td class='text-center'><img src='$src' class='img-thumbnail' alt='{$fila['nombre']}' style='max-width: 150px;'></td>";
                            echo "<td>
                                    <h5>{$fila['nombre']}</h5>
                                    <p>{$fila['descripcion']}</p>
                                    <p class='text-success fw-bold'>Precio: $" . number_format($fila['precio'], 2) . " MXN</p>
                                  </td>";
                            echo "<td class='text-center'>
                                    <a href='?id={$fila['id_producto']}' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\");'>Eliminar</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No hay productos registrados.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <?php
                $conexion->close();
            ?>
        </div>

    </body>
</html>