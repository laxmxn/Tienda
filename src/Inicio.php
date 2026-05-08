<?php
session_start();
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



    <div class="container-fluid p-0">
            <div id="carruselProductos" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carruselProductos" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#carruselProductos" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#carruselProductos" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#carruselProductos" data-bs-slide-to="3"></button>
                </div>

                <div class="carousel-inner shadow-lg">
                    <div class="carousel-item active">
                        <img src="img/celulares.jpeg" alt="Celulares" class="d-block w-100">
                        <div class="carousel-caption">
                            <h3>Los mejores celulares</h3>
                            <p>Descubre nuestra selección de los últimos modelos.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="img/computadoras.webp" alt="Computadoras" class="d-block w-100">
                        <div class="carousel-caption">
                            <h3>Computadoras</h3>
                            <p>Descubre nuestra selección de las últimas computadoras.</p>
                        </div> 
                    </div>
                    <div class="carousel-item">
                        <img src="img/tabletas.jpg" alt="Tabletas" class="d-block w-100">
                        <div class="carousel-caption">
                            <h3>Tabletas</h3>
                            <p>Explora nuestra colección de las mejores tabletas.</p>
                        </div>  
                    </div>
                    <div class="carousel-item">
                        <img src="img/perifericos.webp" alt="Periféricos" class="d-block w-100">
                        <div class="carousel-caption">
                            <h3>Periféricos</h3>
                            <p>Descubre nuestra selección de los mejores periféricos.</p>
                        </div>  
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carruselProductos" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carruselProductos" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
    </div>


            <div class="container p-5 my-5 bg-dark text-white rounded shadow">
        <h1>Bienvenido a la Tienda en línea</h1>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <p class="lead text-primary">¡Qué bueno verte, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</p>
        <?php endif; ?>
        
        <p>En esta tienda encontrarás una amplia variedad de productos para todos los gustos.</p>
        
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo '<div class="alert alert-info mt-3">' . htmlspecialchars($_SESSION['mensaje']) . '</div>';
            unset($_SESSION['mensaje']);
        }
        ?>
    </div>

    <div class="container p-5 my-5 bg-dark text-white rounded shadow">
        <h2>¿Qué ofrecemos?</h2>
        <p class="mb-4">Ofrecemos una amplia gama de productos, incluyendo:</p>
        <ul class="list-group list-group-flush text-bg-dark">
            <li class="list-group-item bg-transparent text-white border-secondary">Computadoras y laptops</li>
            <li class="list-group-item bg-transparent text-white border-secondary">Teléfonos inteligentes</li>
            <li class="list-group-item bg-transparent text-white border-secondary">Tabletas</li>
            <li class="list-group-item bg-transparent text-white border-secondary">Dispositivos electrónicos</li>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>