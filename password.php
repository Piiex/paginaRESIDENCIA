<?php
include 'db.php'; // Asegúrate de que este archivo contenga la conexión a la base de datos

// Generar hash de una nueva contraseña
$clave = 'docentepass1'; // Aquí colocas la contraseña en texto plano que deseas hashear
$hash_clave = password_hash($clave, PASSWORD_DEFAULT); // Generar hash

// Inserción en la base de datos
$nombre = 'Juan';
$correo = 'juan@correo.com';
$role = 'docente'; // Puedes cambiar esto según sea necesario

$stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, clave, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $correo, $hash_clave, $role);

if ($stmt->execute()) {
    echo "Usuario registrado con éxito.";
} else {
    echo "Error al registrar el usuario: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
