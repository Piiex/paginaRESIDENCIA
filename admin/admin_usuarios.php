<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$error_message = '';
$success_message = '';

// Editar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Comprobar si el checkbox 'estado' está marcado o no
    $estado = isset($_POST['estado']) ? 'deshabilitado' : 'activo';

    // Actualizar información del usuario
    if (!empty($password)) {
        // Cambiar contraseña si se proporciona una nueva
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, role = ?, clave = ?, estado = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nombre, $correo, $role, $password_hashed, $estado, $user_id);
    } else {
        // Si no se proporciona una contraseña nueva, solo actualiza otros campos
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, role = ?, estado = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $correo, $role, $estado, $user_id);
    }

    if ($stmt->execute()) {
        $success_message = "Usuario actualizado correctamente.";
    } else {
        $error_message = "Error al actualizar el usuario.";
    }
}

// Obtener todos los usuarios
$result = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuarios - Panel de Administración</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../admin.php">
            <img src="losgotipo 3.png" alt="Logo" class="d-inline-block align-text-top">
            Panel de Administración
        </a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="../admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Administrar Usuarios</h1>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <!-- Tabla de usuarios -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($user['correo']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo htmlspecialchars($user['estado']); ?></td>
                        <td>
                            <button class="btn btn-warning" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['nombre']); ?>', '<?php echo htmlspecialchars($user['correo']); ?>', '<?php echo htmlspecialchars($user['role']); ?>', '<?php echo htmlspecialchars($user['estado']); ?>')">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Formulario de edición de usuario -->
    <div class="mt-5">
        <h2>Editar Usuario</h2>
        <form method="POST" action="">
            <input type="hidden" id="user_id" name="user_id">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">admin</option>
                    <option value="docente">docente</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nueva Contraseña (Dejar en blanco para no cambiar)</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password">
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePasswordVisibility()">Mostrar</button>
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="estado" name="estado">
                <label class="form-check-label" for="estado">
                    Deshabilitar cuenta
                </label>
            </div>
            <button type="submit" name="edit_user" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</div>

<script>
    function editUser(id, nombre, correo, role, estado) {
        document.getElementById('user_id').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('correo').value = correo;
        document.getElementById('role').value = role;
        document.getElementById('estado').checked = (estado === 'deshabilitado');
    }

    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleButton.textContent = "Ocultar";
        } else {
            passwordField.type = "password";
            toggleButton.textContent = "Mostrar";
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
