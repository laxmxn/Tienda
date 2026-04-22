<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
          <a class="nav-link" href="Registro.php">Registro</a>
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
          <a class="nav-link" href="Cuenta_Nueva.php">Iniciar sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
        <div class="container p-5 my-5 bg-dark text-white rounded">
            <h1>Registro de libros</h1>
            <p>En esta sección puedes registrar nuevos libros en nuestra librería.</p>
        </div>

        <form method="post" class="was-validated container p-5 my-5 border rounded" enctype="multipart/form-data">
            <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" id="autor" placeholder="Ingresa el autor" name="autor" required>
                <label for="autor">Autor</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe estar lleno.</div>
            </div>

            <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" id="titulo" placeholder="Ingresa el título" name="titulo" required>
                <label for="titulo">Título</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe estar lleno.</div>
            </div>

            <div class="form-floating mb-3 mt-3">
                <input type="date" class="form-control" id="fecha" placeholder="Ingresa la fecha" name="fecha" max = "<?php echo date('Y-m-d'); ?>" required>
                <label for="fecha">Fecha</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe tener una fecha válida.</div>
            </div>

            <div class="form-floating mb-3 mt-3">
                <input type="file" class="form-control" id="portada"  name="portada" accept=".png, .jpg, .jpeg" required>
                <label for="portada">Portada en PNG, JPG o JPEG</label>
                <div class="valid-feedback">Valido.</div>
                <div class="invalid-feedback">Este campo debe contener un archivo válido.</div>
            </div>
                
            <button type="submit" class="btn-primary">Agregar libro</button>
        </form>
        <?php
            $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Libreria');
            if ($conexion->connect_error) {
                die("Conexión fallida: " . $conexion->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $autor = $_POST["autor"];
                $titulo = $_POST["titulo"];
                $fecha = $_POST["fecha"];

                $file = $_FILES['portada'];

                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $mensaje = "Error al subir el archivo : . {$file['error']})";  
                } else {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

                    if (!in_array($mime, $allowed)) {
                        $mensaje = "Archivo no permitido. Solo se permiten PNG, JPG o JPEG tipo detectado: $mime";
                    } elseif ($file['size'] > 2 * 1024 * 1024) {
                        $mensaje = "Archivo demasiado grande. El tamaño máximo permitido es de 2 MB.";
                    }else{
                        $data = file_get_contents($file['tmp_name']);
                        $stmt = $conexion->prepare("INSERT INTO biblioteca (autor, titulo, fecha_publicacion, portada, tipo_portada) VALUES (?, ?, ?, ?, ?)");
                        if(!$stmt) {
                            $mensaje = "Error al preparar: " . $conexion->error;
                        }else{
                            $null = NULL;
                            $stmt->bind_param("sssbs", $autor, $titulo, $fecha, $null, $mime);
                            $stmt->send_long_data(3, $data);
                            if ($stmt->execute()) {
                                $mensaje = "Libro registrado exitosamente.";
                            } else {
                                $mensaje = "Error al ejecutar: " . $stmt->error;
                            }
                            $stmt->close();
                        }
                    }
                }
                echo "<div class='container p-3 my-3 alert alert-info'>$mensaje</div>";
            }
            $conexion->close();
        ?>
    </body>
</html>