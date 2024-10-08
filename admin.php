<?php

// Verificar si ya hay una sesión iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Resto de tu código aquí



// Verificar si el usuario es admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}



    // Función para generar URLs de admin
    function getAdminModuleUrl($moduleName) {
        return "admin/{$moduleName}.php";
    }
    

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Sistema de Gestión Educativa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .main-content {
            margin-top: 2rem;
        }
        .module-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .module-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logotipo 3.png" alt="Logo" class="d-inline-block align-text-top">
                Panel de Administración
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user-shield"></i> Perfil Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-content">
        <h1 class="mb-4 text-center">Bienvenido al Panel de Administración</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    $modules = [
        ['tutorias', 'Tutorías', 'fa-chalkboard-teacher', 'bg-info'],
        ['asesorias', 'Asesorías', 'fa-users', 'bg-success'],
        ['educacion_dual', 'Educación Dual', 'fa-graduation-cap', 'bg-primary'],
        ['residencia_profesional', 'Residencia Profesional', 'fa-building', 'bg-warning'],
        ['instrumentacion_didactica', 'Instrumentación Didáctica', 'fa-book', 'bg-danger'],
        ['trayectoria_escolar', 'Trayectoria Escolar', 'fa-chart-line', 'bg-secondary'],
        ['atributos_egreso', 'Atributos de Egreso', 'fa-award', 'bg-info'],
        ['investigacion', 'Investigación', 'fa-microscope', 'bg-success'],
        ['acuerdos_academia', 'Acuerdos de Academia', 'fa-handshake', 'bg-primary'],
        // Nuevas opciones para la administración de usuarios
        ['admin_usuarios', 'Administrar Usuarios', 'fa-users-cog', 'bg-info'],
        ['editar_permisiones', 'Editar Permisos', 'fa-user-shield', 'bg-success'],
    ];

    foreach ($modules as $module):
    ?>
        <div class="col">
            <div class="card h-100 module-card">
                <div class="card-body text-center">
                    <i class="fas <?php echo $module[2]; ?> module-icon <?php echo $module[3]; ?> text-white p-3 rounded-circle mb-3"></i>
                    <h5 class="card-title"><?php echo $module[1]; ?></h5>
                    <a href="<?php echo getAdminModuleUrl($module[0]); ?>" class="btn btn-outline-primary mt-2">Gestionar</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>