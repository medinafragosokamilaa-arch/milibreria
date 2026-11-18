
<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contacto.html");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "contactos");

if ($conexion->connect_error) {
    die("Error al conectar con la base de datos. Verifica la configuración de MySQL.");
}

$conexion->set_charset("utf8mb4");

// Recibir los datos del formulario
$nombre = trim($_POST["nombre"] ?? "");
$email = trim($_POST["email"] ?? "");
$numero = trim($_POST["numero"] ?? "");
$edad = filter_var($_POST["edad"] ?? "", FILTER_VALIDATE_INT);
$fecha = trim($_POST["fecha_nacimiento"] ?? "");
$genero = trim($_POST["genero"] ?? "");
$usuario = trim($_POST["usuario"] ?? "");
$clave = $_POST["contrasena"] ?? "";

// Validar campos
if (
    $nombre === "" ||
    $email === "" ||
    $numero === "" ||
    $edad === false ||
    $edad < 1 ||
    $edad > 120 ||
    $fecha === "" ||
    $genero === "" ||
    $usuario === "" ||
    $clave === ""
) {
    die("Error: completa correctamente todos los campos.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error: escribe un correo electrónico válido.");
}

if (strlen($clave) < 8) {
    die("Error: la contraseña debe tener al menos 8 caracteres.");
}

// Nunca guardar la contraseña en texto plano
$contrasena = password_hash($clave, PASSWORD_DEFAULT);

// Preparar la consulta para insertar los datos
$sql = "INSERT INTO contactos
(nombre, email, numero, edad, fecha_nacimiento, genero, usuario, contrasena)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar el registro. Verifica que la tabla y las columnas existan.");
}

$stmt->bind_param(
    "sssissss",
    $nombre,
    $email,
    $numero,
    $edad,
    $fecha,
    $genero,
    $usuario,
    $contrasena
);

if ($stmt->execute()) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Registro exitoso</title>
        <link rel='stylesheet' href='css/contacto.css'>
    </head>
    <body>
        <main class='contact-section'>
            <header class='form-header'>
                <img src='img/snoopy.jpg'
                     alt='Snoopy leyendo'
                     class='logo'>
                <h1>Snoopy's Library</h1>
                <h2>¡Registro exitoso!</h2>
                <p>Tu cuenta se guardó correctamente.</p>
            </header>

            <a class='home-link' href='Ej4.html'>
                ⌂ Volver a la biblioteca
            </a>
        </main>
    </body>
    </html>";
} elseif ($stmt->errno == 1062) {
    echo "Ese nombre de usuario o correo ya está registrado.";
    echo "<p><a href='contacto.html'>Volver al formulario</a></p>";
} else {
    echo "No se pudo guardar el registro. Verifica la estructura de la base de datos.";
}

$stmt->close();
$conexion->close();
?>