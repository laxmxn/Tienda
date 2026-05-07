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
        $cantidad_solicitada = (int)$_POST['cantidad'];
        $id_usuario = $_SESSION['id_usuario'];

        $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $query_stock = "SELECT stock FROM Productos WHERE id_producto = $id_producto";
        $resultado_stock = $conexion->query($query_stock);


        if ($fila_stock = $resultado_stock->fetch_assoc()) {
            $stock_disponible = $fila_stock['stock'];

            $query_carrito = "SELECT cantidad FROM Carrito WHERE id_usuario = $id_usuario AND id_producto = $id_producto";
            $resultado_carrito = $conexion->query($query_carrito);

            $cantidad_en_carrito = 0;
            $existe_en_carrito = false;

            if ($fila_carrito = $resultado_carrito->fetch_assoc()) {
                $cantidad_en_carrito = $fila_carrito['cantidad'];
                $existe_en_carrito = true;
            }

            $nueva_cantidad_total = $cantidad_en_carrito + $cantidad_solicitada;

            if ($nueva_cantidad_total <= $stock_disponible){

                if($existe_en_carrito){
                    $query_update = "UPDATE Carrito SET cantidad = $nueva_cantidad_total WHERE id_usuario = $id_usuario AND id_producto = $id_producto";
                    $conexion->query($query_update);
                } else {
                    $query_insert = "INSERT INTO Carrito (id_usuario, id_producto, cantidad) VALUES ($id_usuario, $id_producto, $nueva_cantidad_total)";
                    $conexion->query($query_insert);
                }

                $query_update_stock = "UPDATE Productos SET stock = stock - $cantidad_solicitada WHERE id_producto = $id_producto";
                $conexion->query($query_update_stock);

                $_SESSION['mensaje'] = "Producto agregado al carrito exitosamente.";
            } else {
                $_SESSION['mensaje'] = "No hay suficiente stock disponible. Stock actual: $stock_disponible unidades.";
            }
        }
        $conexion->close();
    }

    header("Location: Consulta.php");
    exit();
?>

