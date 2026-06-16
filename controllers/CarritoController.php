<?php

session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Carrito.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$database = new Database();
$conn = $database->conectar();

$carritoModel = new Carrito($conn);

$accion = $_GET['accion'] ?? ($_POST['accion'] ?? '');

// ===========================
// Agregar producto al carrito
// ===========================
if ($accion === 'agregar' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_producto = (int) ($_POST['id_producto'] ?? 0);

    if ($id_producto > 0) {
        $id_carrito = $carritoModel->obtenerCarritoActivoOCrear($id_usuario);
        $carritoModel->agregarItem($id_carrito, $id_producto);
    }

    header("Location: ../views/home.php");
    exit;
}

// ===========================
// Eliminar item del carrito
// ===========================
if ($accion === 'eliminar') {

    $id_item = (int) ($_GET['id'] ?? 0);

    if ($id_item > 0) {
        $carritoModel->eliminarItem($id_item, $id_usuario);
    }

    header("Location: ../views/home.php");
    exit;
}

// ===========================
// Vaciar carrito
// ===========================
if ($accion === 'vaciar') {

    $carritoModel->vaciarCarrito($id_usuario);

    header("Location: ../views/home.php");
    exit;
}

// Acción no reconocida
header("Location: ../views/home.php");
exit;
