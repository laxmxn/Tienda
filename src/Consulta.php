<?php

  session_start();
    $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $query = "SELECT id_producto, nombre, descripcion, precio, foto, stock FROM Productos ORDER BY id_producto DESC";
    $productos = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <p class="navbar-brand mb-0">G-tec</p>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="Inicio.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="Consulta.php">Consulta</a></li>
                    <li class="nav-item"><a class="nav-link" href="Carrito.php">Carrito</a></li>
                    <li class="nav-item"><a class="nav-link" href="Informacion.php">Información</a></li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <li class="nav-item">
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasPerfil">
                                Perfil
                            </button>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary text-white px-3" href="Cuenta_Nueva.php">Iniciar sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['id_usuario'])): ?>
    <div class="offcanvas offcanvas-start text-bg-dark" id="offcanvasPerfil">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title">Perfil</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <h4 class="text-success">¡Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</h4>
            <p class="text-light">Bienvenido a tu perfil. Aquí puedes ver y editar tu información personal.</p>
            
            <div class="d-grid gap-2 mt-4">
                <a class="btn btn-outline-primary" href="Editar_Perfil.php">Editar perfil</a>
                <a class="btn btn-outline-primary" href="Historial.php">Ver historial de compras</a>
                <a class="btn btn-outline-primary" href="Carrito.php">Ver Mi Carrito</a>
                <a class="btn btn-danger" href="Cerrar_Sesion.php">Cerrar sesión</a>
            </div>
            
            <hr class="my-4 border-secondary">
            <p class="text-secondary small">Desde tu perfil puedes gestionar tus pedidos, revisar tu historial de compras y actualizar tu información personal.</p>
        </div>
    </div>
    <?php endif; ?>

<div class="container p-5 my-5 bg-dark text-white rounded">
    <h1>Consulta de productos</h1>
    <p>En esta sección puedes consultar los productos registrados en nuestra tienda.</p>
    <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<div class='alert alert-info mb-4'>{$_SESSION['mensaje']}</div>";
            unset($_SESSION['mensaje']);
        }
    ?>
</div>

<div class="container">
    <h2 class="text-center mb-4">Catálogo de Productos</h2>

    <div class="row">
            <?php
                    $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
                    if ($conexion->connect_error) {
                        die("Conexión fallida: " . $conexion->connect_error);
                    }

                    $query = "SELECT id_producto, nombre, descripcion, precio, foto, stock, origen, fabricante FROM Productos ORDER BY id_producto DESC";
                    $productos = $conexion->query($query);

                    if($productos->num_rows > 0){
                        while ($fila = $productos->fetch_assoc()) {
                            echo "<div class='col-md-4 mb-4'>";
                            echo "<div class='card h-100'>";
                            if (!empty($fila['foto'])) {
                                $finfo = new finfo(FILEINFO_MIME_TYPE);
                                $tipoMime = $finfo->buffer($fila['foto']); 
                                $imagenBase64 = base64_encode($fila['foto']);
                                $src = 'data:' . $tipoMime . ';base64,' . $imagenBase64;
                            } else {
                                $src = 'https://via.placeholder.com/300x200?text=Sin+Imagen';
                            }
                              echo "<img src='$src' class='card-img-top' alt='{$fila['nombre']}'>";

                              echo "<div class='card-body'>";
                              echo "<h5 class='card-title'>{$fila['nombre']}</h5>";
                              echo "<p class='card-text'>{$fila['descripcion']}</p>";
                              echo "<p class='card-text'>Precio: $" . number_format($fila['precio'], 2, '.', ',') . " MXN</p>";
                              echo "<p class='card-text'>Origen: {$fila['origen']}</p>";
                              echo "<p class='card-text'>Fabricante: {$fila['fabricante']}</p>";
                              echo "<p class='card-text'>stock: {$fila['stock']} unidades</p>";

                              echo "<form method='POST' action='Agregar_Carrito.php' class='mt-3'>";
                              $stockDisponible = $fila['stock'];
                              if ($stockDisponible > 0) {

                              echo "<div class='input-group'>";
                              echo "<input type='number' name='cantidad' class='form-control' min='1' max='$stockDisponible' value='1' required>";
                              echo "</div>";
                              echo "<input type='submit' value='Agregar al carrito' class='btn btn-primary mt-2'>";
                              echo "<input type='hidden' name='id_producto' value='{$fila['id_producto']}'>";
                            }else {
                              echo "<p class='text-danger mt-3'>Producto agotado</p>";
                              echo "<button type='button' class='btn btn-secondary w-100' disabled>Sin stock</button>";
                            }
                            echo "</form>";
                              echo "</div>";
                              echo "</div>";
                              echo "</div>";
                        }
                    } else {
                        echo "<div class='col-12'><p class='text-center'>No hay productos registrados.</p></div>";
                    }
                    $conexion->close();
                ?>
  </div>
</div>

</body>
</html>