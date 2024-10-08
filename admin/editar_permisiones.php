<?php
// Verificar si ya hay una sesión iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario es admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
require 'db.php';

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_permissions = $_POST['permissions'] ?? [];

    // Actualizar permisos para cada usuario
    foreach ($user_permissions as $user_id => $permissions) {
        // Convertir permisos a formato JSON
        $permissions_json = json_encode($permissions);
        $stmt = $conn->prepare("UPDATE usuarios SET permissions = ? WHERE id = ?");
        $stmt->bind_param("si", $permissions_json, $user_id);
        $stmt->execute();
    }

    header("Location: ../admin.php"); // Redirige después de guardar
    exit();
}

// Obtener todos los usuarios que no son admin
$resultado = $conn->query("SELECT * FROM usuarios WHERE role != 'admin'");
$usuarios = $resultado->fetch_all(MYSQLI_ASSOC);

// Módulos disponibles
$modulos = [
    'tutorias' => 'Tutorías',
    'asesorias' => 'Asesorías',
    'educacion_dual' => 'Educación Dual',
    'residencia_profesional' => 'Residencia Profesional',
    'instrumentacion_didactica' => 'Instrumentación Didáctica',
    'trayectoria_escolar' => 'Trayectoria Escolar',
    'atributos_egreso' => 'Atributos de Egreso',
    'investigacion' => 'Investigación',
    'acuerdos_academia' => 'Acuerdos de Academia'
];

$conn->close(); // Cerrar la conexión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Permisos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Permisos</h2>
        <form method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <?php foreach ($modulos as $modulo_key => $modulo_name): ?>
                            <th><?php echo htmlspecialchars($modulo_name); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['estado']); ?></td>
                            <?php 
                                // Obtener permisos actuales
                                $permissions = json_decode($usuario['permissions'], true) ?? [];
                            ?>
                            <?php foreach ($modulos as $modulo_key => $modulo_name): ?>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[<?php echo $usuario['id']; ?>][<?php echo $modulo_key; ?>]" value="1" <?php echo isset($permissions[$modulo_key]) && $permissions[$modulo_key] == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="admin.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
