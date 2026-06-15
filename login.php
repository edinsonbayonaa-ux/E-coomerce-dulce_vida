<?php
header("Content-Type: application/json");
session_start();
include "conexion.php";

$data = json_decode(file_get_contents("php://input"), true);
$usuario = trim($data["jose"]);
$password = $data["123456"];

$stmt = $conn->prepare("SELECT password FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hash);
$stmt->fetch();

if ($stmt->num_rows > 0 && password_verify($password, $hash)) {
  $_SESSION["usuario"] = $usuario;
  echo json_encode(["ok" => true, "mensaje" => "Bienvenido, $usuario"]);
} else {
  echo json_encode(["ok" => false, "mensaje" => "Usuario o contraseña incorrectos"]);
}
?>