<?php

    session_start();

    $mensaje = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Tienda');
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $accion = $_POST['accion'];

        if($accion === 'crear_cuenta') {
            $password = $_POST['password'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $fecha = $_POST['fecha'];
            $direccion = $_POST['direccion'];
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt_check = $conexion->prepare("SELECT id_usuario FROM Usuarios WHERE correo = ?");
            $stmt_check->bind_param("s", $correo);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $mensaje = "<div class='alert alert-danger mb-4'>El correo ya existe. Por favor elige otro.</div>";
            } else {
                $stmt = $conexion->prepare("INSERT INTO Usuarios ( contrasena, nombre, correo, fecha_nacimiento, direccion_postal) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $password_hashed, $nombre, $correo, $fecha, $direccion);
                
                if ($stmt->execute()) {
                    $mensaje = "<div class='alert alert-success mb-4'>Cuenta creada exitosamente. Ahora puedes iniciar sesión.</div>";
                } else {
                    $mensaje = "<div class='alert alert-danger mb-4'>Error al crear la cuenta. Inténtalo de nuevo.</div>";
                }
                $stmt->close();          
            }
            $stmt_check->close();
                
        } elseif($accion === 'iniciar_sesion') {
            $correo = $_POST['correo'];
            $password = $_POST['password'];
            
            $stmt = $conexion->prepare("SELECT id_usuario, nombre, contrasena FROM Usuarios WHERE correo = ?");
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $stmt->store_result();            

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id_usuario, $nombre_usuario, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['id_usuario'] = $id_usuario;
                    $_SESSION['nombre_usuario'] = $nombre_usuario;
                    
                    header("Location: Inicio.php");
                    exit();
                } else {
                    $mensaje = "<div class='alert alert-danger mb-4'>Contraseña incorrecta. Inténtalo de nuevo.</div>";
                }
            } else {
                $mensaje = "<div class='alert alert-danger mb-4'>No se encontró una cuenta con ese correo electrónico.</div>";
            }
            $stmt->close();
        }
        
        $conexion->close();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creacion de cuenta</title>
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
                    <li class="nav-item"><a class="nav-link" href="Inicio.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="Registro.php">Registro</a></li>
                    <li class="nav-item"><a class="nav-link" href="Consulta.php">Consulta</a></li>
                    <li class="nav-item"><a class="nav-link" href="Carrito.php">Carrito</a></li>
                    <li class="nav-item"><a class="nav-link" href="Informacion.php">Información</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h1>Creación de cuenta</h1>
        <p>En esta sección puedes crear una nueva cuenta para acceder a nuestra tienda o ingresar a una ya existente.</p>
        <?php echo $mensaje; ?>
    </div>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <form method="POST" action="Cuenta_Nueva.php" class="was-validated">
            <input type="hidden" name="accion" value="crear_cuenta">
            <h2 class="mb-4">Crear cuenta</h2>

            <div class="mb-4">
                <label for="correo" class="form-label">Correo electrónico:</label>
                <input type="email" class="form-control" id="correo" placeholder="Ingresa tu correo electrónico" name="correo" required>
                <div class="valid-feedback">Correo válido.</div>
                <div class="invalid-feedback" id="correo-feedback">Por favor ingresa un correo electrónico.</div>
            </div>
            
            <div class="mb-4">
                <label for="password_reg" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password_reg" placeholder="Crea una contraseña" name="password" required>
                <div class="valid-feedback">Contraseña válida.</div>
                <div class="invalid-feedback">Por favor ingresa una contraseña.</div>
            </div>
            
            <div class="mb-4">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" placeholder="Ingresa tu nombre" name="nombre" required>
                <div class="valid-feedback">Nombre válido.</div>
                <div class="invalid-feedback">Por favor ingresa un nombre.</div>
            </div> 

            <div class="mb-4">
                <label for="fecha" class="form-label">Fecha de nacimiento:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
                <div class="valid-feedback">Fecha válida.</div>
                <div class="invalid-feedback">Debes ser mayor de 18 años para registrarte</div>
            </div>

            <div class="mb-4">
                <label for="direccion" class="form-label">Dirección postal:</label>
                <input type="number" class="form-control" id="direccion" placeholder="Ingresa tu dirección postal" name="direccion" required>
                <div class="valid-feedback">Dirección válida.</div>
                <div class="invalid-feedback">Por favor ingresa una dirección.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
        </form>
    </div>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <form method="POST" action="Cuenta_Nueva.php" class="was-validated">
            <input type="hidden" name="accion" value="iniciar_sesion">
            <h2 class="mb-4">Iniciar sesión</h2>
            
            <div class="mb-3">
                <label for="usuario_log" class="form-label">Correo electrónico:</label>
                <input type="email" class="form-control" id="usuario_log" placeholder="Tu correo electrónico" name="correo" required>
                <div class="valid-feedback">Correo válido.</div>
                <div class="invalid-feedback">Por favor ingresa un correo electrónico.</div>
            </div>
            
            <div class="mb-4">
                <label for="password_log" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password_log" placeholder="Tu contraseña" name="password" required>
                <div class="valid-feedback">Contraseña válida.</div>
                <div class="invalid-feedback">Por favor ingresa una contraseña.</div>
            </div>

            <button type="submit" class="btn btn-success w-100">Iniciar sesión</button>
        </form>
    </div>

</body>
</html>