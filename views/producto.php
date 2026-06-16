<?php

class Producto {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // LISTAR PRODUCTOS
    public function listar() {
        $sql = "SELECT * FROM producto";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // BUSCAR PRODUCTO POR ID (opcional pero útil)
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM producto WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}