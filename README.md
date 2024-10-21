# Sistema de Administración de Tutorías de Docentes

Este proyecto es un sistema web para gestionar las tutorías y documentos relacionados de docentes. Permite a un administrador ver los documentos subidos por cada docente y descargarlos de forma individual o en un archivo comprimido (ZIP). 

## Características

- **Autenticación de usuarios:** Los usuarios deben iniciar sesión para acceder al sistema.
- **Roles de usuario:** El sistema maneja roles de administrador y docente.
- **Gestión de docentes:** El administrador puede ver todos los docentes registrados.
- **Subida de documentos:** Los docentes pueden subir documentos relacionados con sus tutorías.
- **Vista previa de documentos:** Los documentos subidos se pueden visualizar en la página antes de ser descargados.
- **Descarga de documentos:** El administrador puede descargar los documentos de un docente, ya sea de forma individual o en un archivo ZIP que contiene todos los documentos de dicho docente.

## Requisitos

- **PHP** 7.4 o superior
- **MySQL** para la base de datos
- **Servidor web** como Apache o Nginx
- **Git** para control de versiones (opcional)
- **Composer** para la gestión de dependencias (opcional)

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/
Configura el archivo de conexión a la base de datos (db.php) en la carpeta raíz con las credenciales correctas:

php
Copiar código
$conn = new mysqli('host', 'usuario', 'contraseña', 'base_de_datos');
Ejecuta las migraciones de base de datos para crear las tablas necesarias. Puedes hacerlo importando un archivo .sql en tu base de datos.

Asegúrate de que la carpeta uploads tenga permisos de escritura, ya que aquí se almacenan los archivos subidos por los docentes:

bash
Copiar código
chmod -R 755 uploads/
Inicia el servidor local (si estás usando PHP):

bash
Copiar código
php -S localhost:8000
Abre tu navegador y accede a http://localhost:8000.

Estructura de Archivos
index.php: Página principal de inicio de sesión.
admin.php: Panel de administración para gestionar docentes y documentos.
ver_documentos.php: Muestra los documentos subidos por un docente.
db.php: Archivo de configuración para la conexión a la base de datos.
uploads/: Carpeta donde se almacenan los archivos subidos.
header.php: Contiene la barra de navegación y el layout común.
Uso
El administrador debe iniciar sesión para acceder al panel.
En el panel, el administrador puede ver todos los docentes registrados.
Al seleccionar un docente, se muestran los documentos asociados a ese docente.
Los documentos pueden visualizarse directamente o descargarse en formato ZIP.
Contribuciones
Las contribuciones son bienvenidas. Para contribuir:

Haz un fork del proyecto.
Crea una nueva rama (git checkout -b feature/nueva-caracteristica).
Realiza tus cambios y haz un commit (git commit -m 'Añadir nueva característica').
Haz push a la rama (git push origin feature/nueva-caracteristica).
Abre un Pull Request.
