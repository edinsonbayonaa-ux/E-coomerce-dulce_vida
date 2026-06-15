<?php
header("Content-Type: application/json");
include "conexion.php";

$data = json_decode(file_get_contents("php://input"), true);
$usuario = trim($data["usuario"]);
$password = password_hash($data["password"], PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
$stmt->bind_param("ss", $usuario, $password);

if ($stmt->execute()) {
  echo json_encode(["ok" => true, "mensaje" => "Usuario creado"]);
} else {
  echo json_encode(["ok" => false, "mensaje" => "El usuario ya existe"]);
}
?>