<?php
session_start();

if(!isset($_SESSION['id_usuario'])) {
    header("Location: Cuenta_Nueva.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica 10</title>
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
        <h1>Bienvenido a tu historial de compras <?php echo isset($_SESSION['nombre_usuario']) ? htmlspecialchars($_SESSION['nombre_usuario']) : 'Usuario'; ?>!</h1>
       
    </div>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2>Aqui puede ver su historial de compras:</h2>

    <?php 
        $conexion = new mysqli("mysql", "root", "Luis28052005", "Tienda");
          $query_carrito = "SELECT h.id_producto, h.fecha_compra, h.cantidad, p.nombre AS nombre_producto, p.precio
                      FROM Historial_Compras h
                      INNER JOIN Productos p ON h.id_producto = p.id_producto 
                      WHERE h.id_usuario = " . $_SESSION['id_usuario'];
        $resultado = $conexion->query($query_carrito);

        $total = 0;
        if($resultado->num_rows > 0) {
            echo "<table class='table table-dark table-striped'>";
            echo "<thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Fecha de Compra</th><th>Total por Producto</th></tr></thead>";
            echo "<tbody>";
            while($row = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombre_producto']) . "</td>";
                echo "<td>$" . number_format($row['precio'], 2, '.', ',') . "</td>";
                echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_compra']) . "</td>";
                $total_producto = $row['precio'] * $row['cantidad'];
                echo "<td>$" . number_format($total_producto, 2, '.', ',') . "</td>";
                echo "</tr>";
                $total += $total_producto;
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Aún no has realizado ninguna compra.</p>";
        }
    ?>

    <h4 class="mt-4">Total Gastado: $<span id="totalGastado"><?php echo number_format($total, 2, '.', ','); ?></span></h4>
    <h3 class="mt-4">¡Gracias por comprar con nosotros!</h3>
    </div>

</body>
</html>