<?php
  session_start();
  if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
  } else {
    header("Location: Cuenta_Nueva.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compras</title>
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
          <a class="nav-link" href="Consulta.php">Consulta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Carrito.php">Carrito</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Informacion.php">Información</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="Historial.php">Historial de Compras</a>
          </li>
      <?php if (isset($_SESSION['id_usuario'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="Cerrar_Sesion.php">Cerrar sesión</a>
          </li>
      <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="Cuenta_Nueva.php">Iniciar sesión</a>
          </li>
      <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h1>Carrito de compras</h1>
        <p>En esta sección puedes ver los productos que has agregado a tu carrito.</p>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<div class='alert alert-info mb-4'>{$_SESSION['mensaje']}</div>";
            unset($_SESSION['mensaje']);
        }
        ?>
    </div>

    <div class="container">
      <div class="row">
    <?php
      $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
      if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
      }
    $id_usuario = $_SESSION['id_usuario'];

    $query = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.foto, c.cantidad FROM Carrito c JOIN Productos p ON c.id_producto = p.id_producto WHERE c.id_usuario = $id_usuario";
    $resultado = $conexion->query($query);

    if ($resultado && $resultado->num_rows > 0) {

      while ($fila = $resultado->fetch_assoc()){
        $nombre = $fila['nombre'];
        $descripcion = $fila['descripcion'];
        $precio = $fila['precio'];
        $foto = $fila['foto'];
        $cantidad = $fila['cantidad'];

      $finfo = new finfo(FILEINFO_MIME_TYPE);
      $tipoMime = $finfo->buffer($fila['foto']); 
      $imagenBase64 = base64_encode($fila['foto']);
      $src = 'data:' . $tipoMime . ';base64,' . $imagenBase64;

        echo "<div class='col-md-4 mb-4'>";
        echo "<div class='card h-100'>";

        echo "<img src='$src' class='card-img-top' alt='$nombre' style='height: 200px; object-fit: cover;'>";

        echo "<div class='card-body d-flex flex-column'>";
        echo "<h5 class='card-title'>$nombre</h5>";
        echo "<p class='card-text'>$descripcion</p>";
        echo "<p class='card-text'><Strong>Precio: $" . number_format($precio, 2, '.', ',') . "</Strong></p>";
        echo "<p class='card-text'>Cantidad:<Strong> $cantidad</Strong></p>";

        echo "<form method='POST' action='Eliminar_Carrito.php' class='mt-3'>";
        echo "<input type='hidden' name='id_producto' value='{$fila['id_producto']}'>";

        echo "<div class='input-group mt-3'>";
        echo "<input type='number' name='cantidad' class='form-control' value='1' min='1' max='$cantidad' required>";
        echo "<button type='submit' class='btn btn-danger'>Eliminar</button>";
        echo "</div>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
      }

        echo "</div>";  

        echo "<div class='row mt-4 mb-5'>";
        echo "<div class='col-12 text-end'>";
        echo "<a href='Pagar.php' class='btn btn-success btn-lg'>Proceder al pago</a>";
        echo "</div>";
        echo "</div>";  
    } else {
        echo "<div class='col-12'>";
        echo "<p class='alert alert-warning text-center'>Tu carrito está vacío.</p>";
        echo "</div>";
        echo "</div>";
    }
    $conexion->close();
    ?>
      </div></body>
</html>