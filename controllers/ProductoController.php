<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Producto.php";

$database = new Database();
$conn = $database->conectar();

$productoModel = new Producto($conn);

$accion = $_GET['accion'] ?? '';

if ($accion === 'listar') {

    header('Content-Type: application/json');
    echo json_encode($productoModel->listar());
    exit;
}

if ($accion === 'detalle') {

    $id_producto = (int) ($_GET['id'] ?? 0);

    header('Content-Type: application/json');
    echo json_encode($productoModel->obtenerPorId($id_producto));
    exit;
}

header("Location: ../views/home.php");
exit;
