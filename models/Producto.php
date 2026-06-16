<?php

class Producto {

    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }

    public function listar()
    {
        $sql = "SELECT * FROM producto";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_producto)
    {
        $sql = "SELECT * FROM producto
                WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$id_producto]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function descontarStock($id_producto, $cantidad)
    {
        $sql = "UPDATE producto
                SET stock_producto = stock_producto - ?
                WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $cantidad,
            $id_producto
        ]);
    }

}
