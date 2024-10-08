<?php
session_start();
include 'db.php'; // Conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Obtener los permisos del usuario desde la base de datos
$stmt = $conn->prepare("SELECT permissions FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $permissions = json_decode($user['permissions'], true); // Decodificar permisos
} else {
    echo "No se encontraron permisos para este usuario.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Docente</title>
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
            transition: transform 0.3s ease-in-out;
        }
        .module-card:hover {
            transform: translateY(-5px);
        }
        .module-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <img src="LOGOTIPO.png" alt="Logo" class="d-inline-block align-text-top">
                Sistema de Gestión Docente
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="docente/perfil.php"><i class="fas fa-user"></i> Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container main-content">
    <h1 class="mb-4">Bienvenido(a), <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>

    <h2 class="mb-4">Módulos disponibles:</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        $modules = [
            'tutorias' => ['Tutorías', 'fa-chalkboard-teacher'],
            'asesorias' => ['Asesorías', 'fa-users'],
            'educacion_dual' => ['Educación Dual', 'fa-graduation-cap'],
            'residencia_profesional' => ['Residencia Profesional', 'fa-building'],
            'instrumentacion_didactica' => ['Instrumentación Didáctica', 'fa-book'],
            'trayectoria_escolar' => ['Trayectoria Escolar', 'fa-chart-line'],
            'atributos_egreso' => ['Atributos de Egreso', 'fa-award'],
            'investigacion' => ['Investigación', 'fa-microscope'],
            'acuerdos_academia' => ['Acuerdos de Academia', 'fa-handshake']
        ];

        foreach ($modules as $key => $value):
            if (isset($permissions[$key]) && $permissions[$key] == 1):
        ?>
            <div class="col">
                <div class="card h-100 module-card">
                    <div class="card-body text-center">
                        <i class="fas <?php echo $value[1]; ?> module-icon"></i>
                        <h5 class="card-title"><?php echo $value[0]; ?></h5>
                        <!-- Ruta hacia el archivo PHP dentro de la carpeta del docente -->
                        <a href="docente/<?php echo $key; ?>.php" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
        <?php
            endif;
        endforeach;
        ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
