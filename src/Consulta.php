<?php
    $conexion = new mysqli('mysql', 'root', 'Luis28052005', 'Libreria');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexion->prepare("SELECT portada, tipo_portada FROM biblioteca WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        header("Content-Type: " . $fila['tipo_portada']);
        echo $fila['portada'];
    }
    $stmt->close();
    $conexion->close();
    exit; 

    $query = "SELECT id, autor, titulo, fecha_publicacion FROM biblioteca ORDER BY id DESC";
    $resultado = $conexion->query($query);
}

$imagenes = $conexion->query("SELECT id, autor, titulo, fecha_publicacion FROM biblioteca ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta</title>
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
    <h1>Consulta de libros</h1>
    <p>En esta sección puedes consultar los libros registrados en nuestra librería.</p>
</div>

<div class="container">
    <h2 class="text-center mb-4">Catálogo de Libros</h2>

    <div class="row">
        <?php while ($fila = $imagenes->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="Consulta.php?id=<?php echo $fila['id']; ?>" class="card-img-top" alt="<?php echo $fila['titulo']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $fila['titulo']; ?></h5>
                        <p class="card-text">Autor: <?php echo $fila['autor']; ?></p>
                        <p class="card-text">Fecha de Publicación: <?php echo $fila['fecha_publicacion']; ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php $conexion->close(); ?>
</body>
</html>