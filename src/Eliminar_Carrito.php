<?php
    session_start();

    if(isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
    } else {
        header("Location: Cuenta_Nueva.php");
        exit();
    }

    if(isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
        $id_producto = (int)$_POST['id_producto'];
        $cantidad = (int)$_POST['cantidad'];
        $id_usuario = $_SESSION['id_usuario'];

        $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $query_carrito = "SELECT cantidad FROM Carrito WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
        $resultado_carrito = $conexion->query($query_carrito);


        if ($fila = $resultado_carrito->fetch_assoc()) {
            $cantidad_actual = $fila['cantidad'];

            $query_devolver = "UPDATE Productos SET stock = stock + $cantidad_actual WHERE id_producto = $id_producto";
            $conexion->query($query_devolver);

            $nueva_cantidad = $cantidad_actual - $cantidad;

            if ($nueva_cantidad > 0) {
                $query_update = "UPDATE Carrito SET cantidad = $nueva_cantidad WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
                $conexion->query($query_update);
                $_SESSION['mensaje'] = "Se regresaron las unidades al stock.";
            } else {
                $query_delete = "DELETE FROM Carrito WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
                $conexion->query($query_delete);
                $_SESSION['mensaje'] = "Producto eliminado del carrito y se regresaron las unidades al stock.";
            }
           
        }
        $conexion->close();
    }

    header("Location: Carrito.php");
    exit();
?>
