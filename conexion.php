<?php
$servidor = "127.0.0.1";
$usuario = "root";
$password = "";
$bd = "dat";

// Habilitar reporte de errores de MySQLi para depurar
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli($servidor, $usuario, $password, $bd);
    $conexion->set_charset("utf8");
} catch (Exception $e) {
    die("¡Error crítico al conectar a la base de datos!: " . $e->getMessage());
}
?>