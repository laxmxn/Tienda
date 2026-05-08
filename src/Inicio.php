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
            <a class="nav-link" href="Editar_Perfil.php">Editar perfil</a>
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
        <h1>Bienvenido a la Tienda en línea</h1>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <p class="lead">¡Qué bueno verte, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</p>
        <?php endif; ?>
        <p>En esta tienda encontrarás una amplia variedad de productos para todos los gustos.</p>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo '<div class="alert alert-info mt-3">' . htmlspecialchars($_SESSION['mensaje']) . '</div>';
            unset($_SESSION['mensaje']);
        }
        ?>
    </div>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2>¿Qué ofrecemos?</h2>
        <p>Ofrecemos una amplia gama de productos, incluyendo:</p>
        <ul>
            <li>Computadoras y laptops</li>
            <li>Teléfonos inteligentes</li>
            <li>Tabletas</li>
            <li>Dispositivos electrónicos</li>
        </ul>
    </div>
</body>
</html>