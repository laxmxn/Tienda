# G-tec E-commerce System

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000f?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

**G-tec** es una plataforma de comercio electrónico full-stack desarrollada para la gestión de productos tecnológicos. El sistema permite un flujo completo de compra, desde el registro de usuarios y gestión administrativa de inventario, hasta el procesamiento de pagos y auditoría de transacciones.

## Características Principales

- **Gestión de Inventario (CRUD):** Los administradores pueden crear, editar y eliminar productos con soporte para imágenes (almacenadas como `LONGBLOB`).
- **Sistema de Carrito Dinámico:** Lógica de reservación de stock en tiempo real (el stock disminuye al agregar y aumenta al eliminar del carrito).
- **Autenticación Segura:** Manejo de sesiones y contraseñas protegidas.
- **Historial de Compras:** Registro persistente de transacciones realizadas para consulta del usuario y administrador.
- **Validación de Pagos:** Verificación de métodos de pago registrados antes de procesar la orden.

## Stack Tecnológico

- **Backend:** PHP 8.x
- **Base de Datos:** MySQL
- **Frontend:** HTML5, CSS3 (Custom), Bootstrap 5
- **Infraestructura:** Docker & Docker Compose (Nginx + PHP-FPM + MySQL)

## Estructura del Proyecto

- `src/`: Contiene toda la lógica PHP de la aplicación.
- `img/`: Recursos visuales estáticos.
- `nginx.conf`: Configuración del servidor web.
- `docker-compose.yml`: Orquestación de contenedores para despliegue local.
- `style.css`: Estilos personalizados que extienden Bootstrap.

## Instalación y Configuración (Docker)

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/tu-usuario/nombre-repositorio.git](https://github.com/tu-usuario/nombre-repositorio.git)
   cd nombre-repositorio