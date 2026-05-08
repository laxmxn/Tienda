<?php
session_start();
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
        <h1>Aquí puedes editar tu perfil</h1>
        <p>Modifica la información de tu cuenta en cualquier momento.</p>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<div class='alert alert-info mb-4'>{$_SESSION['mensaje']}</div>";
            unset($_SESSION['mensaje']);
        }
        ?>
    </div>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2>Editar información del perfil</h2>

        <?php
           $conexion = new mysqli("mysql", "root", "Luis28052005", "Tienda");
              if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
              }

            $id_usuario = $_SESSION['id_usuario'];
            $sql = "SELECT nombre, fecha_nacimiento, tarjeta_bancaria,direccion_postal FROM Usuarios WHERE id_usuario = $id_usuario";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
            } else {
                echo "<div class='alert alert-danger'>No se encontró el usuario.</div>";
                exit;
            }
        ?>

        <form  method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tarjeta_bancaria" class="form-label">Número de tarjeta bancaria</label>
                <input type="number" class="form-control" id="tarjeta_bancaria" name="tarjeta_bancaria" value="<?php 
                if (isset($usuario['tarjeta_bancaria'])) {
                    echo htmlspecialchars($usuario['tarjeta_bancaria']);
                }else {
                    echo '';
                }
                ?>" >
            </div>
            <div class="mb-3">
                <label for="direccion_postal" class="form-label">Dirección postal</label>
                <input type="text" class="form-control" id="direccion_postal" name="direccion_postal" value="<?php echo htmlspecialchars($usuario['direccion_postal']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar perfil</button>
        </form>

        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $fecha_nacimiento = $_POST['fecha_nacimiento'];
            $tarjeta_bancaria = $_POST['tarjeta_bancaria'];
            $direccion_postal = $_POST['direccion_postal'];

            $sql_update = "UPDATE Usuarios SET nombre='$nombre', fecha_nacimiento='$fecha_nacimiento', tarjeta_bancaria='$tarjeta_bancaria', direccion_postal='$direccion_postal' WHERE id_usuario=$id_usuario";

            if ($conexion->query($sql_update) === TRUE) {
                $_SESSION['nombre_usuario'] = $nombre;
                $_SESSION['mensaje'] = "Perfil actualizado correctamente.";
                echo "<script>window.location.href='Editar_Perfil.php?mensaje=actualizado';</script>";
                exit;
            } else {
                echo "<div class='alert alert-danger'>Error al actualizar el perfil: " . $conexion->error . "</div>";
            }
        }
        $conexion->close();
        ?>


    </div>
</body>
</html>