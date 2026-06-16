<?php

$conexion = new mysqli(
    "localhost",
    "root",
    "edinson123A-",
    "drappsco_dulce_vida"
);

if ($conexion->connect_error) {
    die("Error: " . $conexion->connect_error);
}

echo "Conectado correctamente";