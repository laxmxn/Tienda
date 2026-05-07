<?php
    session_start();

    if(!isset($_SESSION['id_usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: Cuenta_Nueva.php");
        exit();
    }

    $id_usuario = (int)$_SESSION['id_usuario'];

    $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $query_carrito = "SELECT c.id_producto, c.cantidad, p.precio 
                      FROM Carrito c 
                      INNER JOIN Productos p ON c.id_producto = p.id_producto 
                      WHERE c.id_usuario = $id_usuario";
                      
    $resultado_carrito = $conexion->query($query_carrito);

    if($resultado_carrito && $resultado_carrito->num_rows > 0) {
        
        while($fila = $resultado_carrito->fetch_assoc()) {
            $id_producto = $fila['id_producto'];
            $cantidad = $fila['cantidad'];
            
            $total_producto = $fila['precio'] * $cantidad; 

            $query_insertar = "INSERT INTO Historial_Compras (id_usuario, id_producto, cantidad, fecha_compra, total) 
                               VALUES ($id_usuario, $id_producto, $cantidad, NOW(), $total_producto)";
                               
            $conexion->query($query_insertar);
        }

        $query_vaciar = "DELETE FROM Carrito WHERE id_usuario = $id_usuario";
        $conexion->query($query_vaciar);

        $_SESSION['mensaje'] = "¡Pago procesado con éxito! Tu pedido ha sido guardado en el historial.";
    } else {
        $_SESSION['mensaje'] = "No hay productos en tu carrito para pagar.";
    }

    $conexion->close();

    header("Location: Inicio.php"); 
    exit();
?>