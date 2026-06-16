<?php

class Pago {

    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }

    public function registrarPago(
        $id_pedido,
        $monto,
        $metodo_pago,
        $referencia
    ) {

        $sql = "INSERT INTO pago(
                    id_pedido_pago,
                    fecha_pago,
                    monto_pago,
                    metodo_pago,
                    estado_pago,
                    referencia_pago
                )
                VALUES(
                    ?,
                    NOW(),
                    ?,
                    ?,
                    'pendiente',
                    ?
                )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $id_pedido,
            $monto,
            $metodo_pago,
            $referencia
        ]);
    }

}
