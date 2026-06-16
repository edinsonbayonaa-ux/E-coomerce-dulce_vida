<?php

class Pedido {

    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }

    public function crearPedido(
        $id_usuario,
        $id_direccion,
        $total,
        $metodo_pago
    ) {

        $sql = "INSERT INTO pedidos(
                    id_usuario_pedidos,
                    id_direccion_pedidos,
                    fecha_pedidos,
                    estado_pedidos,
                    total_pedidos,
                    metodo_pago_pedidos
                )
                VALUES(
                    ?,
                    ?,
                    NOW(),
                    'pendiente',
                    ?,
                    ?
                )";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            $id_usuario,
            $id_direccion,
            $total,
            $metodo_pago
        ]);

        return $this->conn->lastInsertId();
    }

    public function agregarDetalle(
        $id_pedido,
        $id_producto,
        $cantidad,
        $precio_unitario,
        $subtotal
    ) {

        $sql = "INSERT INTO detalle_pedido(
                    id_pedido,
                    id_producto_detalle_pedido,
                    cantidad_detalle_pedido,
                    precio_unitario_detalle_pedido,
                    subtotal_detalle_pedido
                )
                VALUES(
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $id_pedido,
            $id_producto,
            $cantidad,
            $precio_unitario,
            $subtotal
        ]);
    }

}
