<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos del Docente - Panel de Administración</title>
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
        .document-card {
            transition: transform 0.3s ease-in-out;
        }
        .document-card:hover {
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

    if (!isset($_GET['docente_id'])) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>No se ha seleccionado ningún docente.</div></div>";
        exit();
    }

    $docente_id = $_GET['docente_id'];

    // Obtener el nombre del docente
    $query_docente = "SELECT nombre FROM usuarios WHERE id = ?";
    $stmt_docente = $conn->prepare($query_docente);
    $stmt_docente->bind_param("i", $docente_id);
    $stmt_docente->execute();
    $result_docente = $stmt_docente->get_result();
    $docente = $result_docente->fetch_assoc();

    $query = "SELECT id, file_name, file_type, comment, upload_date FROM tutorias_documentos WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $docente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../logotipo 3.png" alt="Logo" class="d-inline-block align-text-top">
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
        <h1 class="mb-4">Documentos de <?php echo htmlspecialchars($docente['nombre']); ?></h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $file_name = str_replace(' ', '%20', $row['file_name']); ?>
                    <div class="col">
                        <div class="card h-100 document-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['file_name']); ?></h5>
                                <p class="card-text"><strong>Tipo:</strong> <?php echo htmlspecialchars($row['file_type']); ?></p>
                                <p class="card-text"><strong>Comentario:</strong> <?php echo htmlspecialchars($row['comment']); ?></p>
                                <p class="card-text"><small class="text-muted">Subido el: <?php echo $row['upload_date']; ?></small></p>
                            </div>
                            <div class="card-footer">
                                <a href="../uploads/<?php echo $file_name; ?>" class="btn btn-primary" target="_blank" onclick="event.preventDefault(); previewDocument('<?php echo $file_name; ?>')">
                                    <i class="fas fa-eye"></i> Ver documento
                                </a>
                                <a href="../uploads/<?php echo $file_name; ?>" class="btn btn-success" download>
                                    <i class="fas fa-download"></i> Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No se encontraron documentos para este docente.</div>
        <?php endif; ?>
    </div>

    <!-- Modal for Document Preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Vista Previa del Documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="documentPreview" style="width: 100%; height: 600px;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewDocument(fileName) {
            const filePath = '../uploads/' + fileName;
            document.getElementById('documentPreview').src = filePath;
            const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
            previewModal.show();
        }
    </script>
</body>
</html>
