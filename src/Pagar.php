<?php
session_start();

    if(isset($_SESSION['id_usuario'])) {
        $id_usuario = (int)$_SESSION['id_usuario'];
    } else {
        header("Location: Cuenta_Nueva.php");
        exit();
    }

    $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $query = "SELECT p.nombre, p.precio, c.cantidad FROM Carrito c JOIN Productos p ON c.id_producto = p.id_producto WHERE c.id_usuario = $id_usuario";
    $resultado = $conexion->query($query);

    $total_compra = 0;
    
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
          <a class="nav-link" href="Carrito.php">Volver al carrito</a>
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
        <h2>Resumen de tu Pedido</h2>
        <hr>
        
        <?php if($resultado && $resultado->num_rows > 0): ?>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while($fila = $resultado->fetch_assoc()) {
                            $subtotal = $fila['precio'] * $fila['cantidad'];
                            $total_compra += $subtotal; 
                            
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                            echo "<td>" . $fila['cantidad'] . "</td>";
                            echo "<td>$" . number_format($fila['precio'], 2) . "</td>";
                            echo "<td>$" . number_format($subtotal, 2) . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
            
            <div class="text-end mt-4">
                <h3>Total a Pagar: <span class="text-success">$<?php echo number_format($total_compra, 2); ?></span></h3>

                <form action="Procesar_Pago.php" method="POST" class="mt-3">
                    <a href="Carrito.php" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-success btn-lg">Confirmar y Pagar</button>
                </form>
            </div>
            
        <?php else: ?>
            <p>No hay productos para pagar.</p>
            <a href="Carrito.php" class="btn btn-primary">Volver al carrito</a>
        <?php endif; ?>
        
    </div>

    <?php $conexion->close(); ?>

</body>
</html>