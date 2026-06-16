<?php

session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Carrito.php";
require_once __DIR__ . "/../models/Producto.php";
require_once __DIR__ . "/../models/Pedido.php";
require_once __DIR__ . "/../models/Pago.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/home.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$direccion     = trim($_POST['direccion'] ?? '');
$ciudad        = trim($_POST['ciudad'] ?? '');
$departamento  = trim($_POST['departamento'] ?? '');
$codigo_postal = trim($_POST['codigo_postal'] ?? '');
$metodo_pago   = trim($_POST['metodo_pago'] ?? '');

if ($direccion === '' || $ciudad === '' || $departamento === '' || $metodo_pago === '') {
    die("Faltan datos obligatorios para procesar la compra.");
}

$database = new Database();
$conn = $database->conectar();

$sql = "INSERT INTO direccion_envio (
            id_usuario_direccion_envio,
            calle_direccion_envio,
            ciudad_direccion_envio,
            departamento_direccion_envio,
            codigo_postal_direccion_envio,
            es_principal_direccion_envio
        )
        VALUES (?, ?, ?, ?, ?, 0)";

$stmt = $conn->prepare($sql);
$stmt->execute([
    $id_usuario,
    $direccion,
    $ciudad,
    $departamento,
    $codigo_postal
]);

$id_direccion = $conn->lastInsertId();

$carritoModel  = new Carrito($conn);
$productoModel = new Producto($conn);
$pedidoModel   = new Pedido($conn);
$pagoModel     = new Pago($conn);

// 1. Obtener carrito activo
$carrito = $carritoModel->obtenerCarritoActivo($id_usuario);

if (!$carrito) {
    die("No tienes un carrito activo.");
}

$id_carrito = $carrito['id_carrito'];

// 2. Obtener items del carrito
$items = $carritoModel->obtenerItems($id_usuario);

if (empty($items)) {
    die("Tu carrito está vacío.");
}

// 3. Calcular total
$total = 0;

foreach ($items as $item) {
    $total += $item['subtotal'];
}

try {

    $conn->beginTransaction();

    // 4. Crear pedido
    // NOTA: este proyecto no maneja una tabla de direcciones separada,
    // por lo que id_direccion_pedidos se guarda como NULL.
    // Si se agrega una tabla de direcciones, registrar/buscar el id aquí.
    $id_pedido = $pedidoModel->crearPedido(
        $id_usuario,
        $id_direccion,
        $total,
        $metodo_pago
    );

    // 5. Crear detalle del pedido y descontar stock
    foreach ($items as $item) {

        $pedidoModel->agregarDetalle(
            $id_pedido,
            $item['id_producto'],
            $item['cantidad'],
            $item['precio_producto'],
            $item['subtotal']
        );

        $productoModel->descontarStock(
            $item['id_producto'],
            $item['cantidad']
        );
    }

    // 6. Registrar pago
    $referencia_pago = 'PAG-' . $id_pedido . '-' . time();

    $pagoModel->registrarPago(
        $id_pedido,
        $total,
        $metodo_pago,
        $referencia_pago
    );

    // 7. Marcar carrito como convertido
    $carritoModel->marcarConvertido($id_carrito);

    $conn->commit();

    header("Location: ../views/home.php?compra=exitosa");
    exit;

} catch (Exception $e) {

    $conn->rollBack();

    die("Error al procesar la compra: " . $e->getMessage());
}
