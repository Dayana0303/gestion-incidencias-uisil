# Sistema de Gestión de Incidencias Tecnológicas

Evaluación práctica de **Programación Avanzada** – UISIL, Facultad de Ingeniería de Sistemas.
**Estudiante:** Dayana Murillo.

Aplicación web hecha con **PHP (mysqli)**, **MySQL**, **HTML/CSS** y **Bootstrap 5** para registrar y dar seguimiento a las fallas tecnológicas de los laboratorios de computación.

## Funcionalidades

- Registrar incidencias (quien reporta, laboratorio, equipo, descripción y prioridad).
- Listado en tabla con la fecha de registro de cada incidencia.
- Filtrar por laboratorio, estado o prioridad.
- Editar incidencias.
- Cambiar el estado: Pendiente, En proceso o Resuelta.
- Eliminar con confirmación.
- Sección administrativa con inicio de sesión: solo usuarios autenticados pueden editar, eliminar o cambiar el estado.
- Validación de campos obligatorios (servidor), consultas preparadas, contraseñas con `password_hash` y token CSRF.

## Requisitos

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL/MariaDB + PHP 7.4 o superior).
- Conexión a Internet para cargar Bootstrap desde CDN.

## Instalación y ejecución

1. Copie la carpeta del proyecto a `C:\xampp\htdocs\incidencias` (en Linux/Mac: `/opt/lampp/htdocs/incidencias`).
2. Abra el panel de XAMPP e inicie **Apache** y **MySQL**.
3. Abra `http://localhost/phpmyadmin`, vaya a la pestaña **Importar**, seleccione el archivo `database.sql` y presione **Continuar**. Esto crea la base de datos `incidencias_db`, las tablas y el usuario administrador.
4. Si su MySQL tiene usuario o contraseña distintos a `root` / vacío, edite `config/conexion.php`.
5. Abra en el navegador: `http://localhost/incidencias/`

## Credenciales de administrador

| Usuario | Contraseña |
|---------|------------|
| `admin` | `admin123` |

## Estructura del proyecto

```
incidencias/
├── database.sql            # Estructura de la BD + datos de ejemplo
├── config/conexion.php     # Conexión mysqli y catálogos
├── includes/
│   ├── auth.php            # Sesión, login requerido, CSRF, helpers
│   ├── header.php          # Encabezado y menú
│   ├── footer.php
│   ├── form_incidencia.php # Formulario compartido (registrar/editar)
│   └── validar.php         # Validación de campos
├── assets/css/style.css
├── index.php               # Listado + filtros
├── registrar.php           # Crear incidencia
├── editar.php              # Editar incidencia (requiere login)
├── cambiar_estado.php      # Cambiar estado (requiere login)
├── eliminar.php            # Eliminar con confirmación (requiere login)
├── login.php / logout.php
└── README.md
```

## Base de datos

- `usuarios`: id, usuario, nombre, password_hash, creado_en.
- `incidencias`: id, reportante, laboratorio, equipo, descripcion, prioridad, estado, fecha_registro.
