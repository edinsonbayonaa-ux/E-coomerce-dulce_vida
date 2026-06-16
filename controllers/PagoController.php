<?php

session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Pago.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit;
}

$database = new Database();
$conn = $database->conectar();

$pagoModel = new Pago($conn);

// Este controlador queda disponible para futuras consultas
// o actualizaciones del estado de pago (ej. confirmaciones
// de pasarelas de pago). El flujo de creación del pago
// se realiza en PedidoController.php al confirmar la compra.

header("Location: ../views/home.php");
exit;
