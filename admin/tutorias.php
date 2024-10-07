<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Tutorías de Docentes</title>
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
        .docente-card {
            transition: transform 0.3s ease-in-out;
        }
        .docente-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include '../db.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        header("Location: ../login.php");
        exit();
    }

    $sql = "SELECT id, nombre FROM usuarios WHERE role = 'docente'";
    $result = $conn->query($sql);
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../LOGOTIPO 3.png" alt="Logo" class="d-inline-block align-text-top">
                Panel de Administración
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-content">
        <h1 class="mb-4">Docentes Registrados</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php while ($docente = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card h-100 docente-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($docente['nombre']); ?></h5>
                                <p class="card-text">ID: <?php echo $docente['id']; ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="ver_documentos.php?docente_id=<?php echo $docente['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-folder-open"></i> Ver Documentos
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay docentes registrados.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>