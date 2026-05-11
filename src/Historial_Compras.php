<?php
session_start();

$conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$query_historial = "SELECT hc.id_compra, hc.id_usuario, hc.id_producto, hc.cantidad, hc.fecha_compra, hc.total, p.nombre, f.nombre AS nombre_usuario
                    FROM Historial_Compras hc 
                    INNER JOIN Productos p ON hc.id_producto = p.id_producto 
                    INNER JOIN Usuarios f ON hc.id_usuario = f.id_usuario
                    ORDER BY hc.fecha_compra DESC";
$result_historial = $conexion->query($query_historial);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-tec | Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <li class="nav-item"><a class="nav-link" href="Registro.php">Registro</a></li>
                </ul>
            </div>
        </div>
    </nav>

     <div class="container p-5 my-5 bg-dark text-white rounded">
        <h1>Historial de Compras</h1>
        <p>En esta sección puedes ver el historial de compras de todos los usuarios.</p>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<div class='alert alert-info'>" . $_SESSION['mensaje'] . "</div>";
            unset($_SESSION['mensaje']);
        }
        ?>
    </div>

    <div class="container">
        <h2>Detalles del Historial de Compras</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID Compra</th>
                    <th>Usuario</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Fecha de Compra</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_historial->fetch_assoc()): 
                    $total = 0;
                    $total += $row['total'];
                    ?>
                    <tr>
                        <td><?php echo $row['id_compra']; ?></td>
                        <td><?php echo $row['nombre_usuario']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo $row['fecha_compra']; ?></td>
                        <td><?php echo number_format($row['total'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Total General:</td>
                        <td class="fw-bold"><?php echo number_format($total, 2); ?> MXN</td>
                    </tr>   
                </tfoot>
        </table>
    </div>

    </body> 
</html>

