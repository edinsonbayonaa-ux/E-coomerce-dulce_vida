<?php

$host = "localhost";
$usuario = "root";
$password = "edinson123A-";
$base_datos = "drappsco_dulce_vida";

$conn = new mysqli(
    $host,
    $usuario,
    $password,
    $base_datos
);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}