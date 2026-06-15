<?php
$host = "localhost";
$db   = "dulce_vida";
$user = "root";
$pass = "";  // En XAMPP por defecto no tiene contraseña

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}
?>