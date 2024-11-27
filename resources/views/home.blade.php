<!-- resources/views/home.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .wrapper {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
        }
        .sidebar a {
            color: #ddd;
            text-decoration: none;
            padding: 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .header {
            padding: 10px 20px;
            background-color: #6c757d;
            color: #fff;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>Panel Administrativo del Hotel</h1>
        
        <!-- Cerrar Sesión -->
        @auth
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-light">Cerrar Sesión</button>
            </form>
        @endauth
    </div>
    
    <div class="wrapper">
        <!-- Menú Lateral -->
        <nav class="sidebar">
    <h4 class="p-3">Menú</h4>
    <!-- <a href="/disponible">Disponibles</a> -->
    <a href="/reserva">Reservas</a>
    <a href="/contactos">Contacto</a>
</nav>
        <!-- Contenido Principal -->
        <main class="content">
            <div class="container">
                <h2>Bienvenido al Panel Administrativo</h2>
                @yield('tablas')
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
