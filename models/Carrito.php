<?php

class Carrito {

    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }

    public function obtenerCarritoActivo($id_usuario)
    {
        $sql = "SELECT * FROM carrito
                WHERE id_usuario_carrito = ?
                AND estado = 'activo'
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$id_usuario]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearCarrito($id_usuario)
    {
        $sql = "INSERT INTO carrito(
                    id_usuario_carrito,
                    fecha_creacion_carrito,
                    estado
                )
                VALUES(
                    ?,
                    NOW(),
                    'activo'
                )";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$id_usuario]);

        return $this->conn->lastInsertId();
    }

    public function obtenerCarritoActivoOCrear($id_usuario)
    {
        $carrito = $this->obtenerCarritoActivo($id_usuario);

        if ($carrito) {
            return $carrito['id_carrito'];
        }

        return $this->crearCarrito($id_usuario);
    }

    public function buscarItem($id_carrito, $id_producto)
    {
        $sql = "SELECT * FROM item_carrito
                WHERE id_carrito = ?
                AND id_producto = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            $id_carrito,
            $id_producto
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function agregarItem($id_carrito, $id_producto)
    {
        $item = $this->buscarItem($id_carrito, $id_producto);

        if ($item) {

            $nuevaCantidad = $item['cantidad'] + 1;

            $sql = "UPDATE item_carrito
                    SET cantidad = ?
                    WHERE id_item = ?";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                $nuevaCantidad,
                $item['id_item']
            ]);

        } else {

            $sql = "INSERT INTO item_carrito(
                        id_carrito,
                        id_producto,
                        cantidad
                    )
                    VALUES(
                        ?,
                        ?,
                        1
                    )";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                $id_carrito,
                $id_producto
            ]);
        }
    }

    public function obtenerItems($id_usuario)
    {
        $sql = "SELECT ic.id_item,
                       ic.id_producto,
                       ic.cantidad,
                       p.nombre_producto,
                       p.precio_producto,
                       p.imagen_url_producto,
                       (ic.cantidad * p.precio_producto) AS subtotal
                FROM carrito c
                INNER JOIN item_carrito ic
                    ON c.id_carrito = ic.id_carrito
                INNER JOIN producto p
                    ON ic.id_producto = p.id_producto
                WHERE c.id_usuario_carrito = ?
                AND c.estado = 'activo'";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$id_usuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarItems($id_usuario)
    {
        $sql = "SELECT COALESCE(SUM(ic.cantidad), 0) AS total
                FROM carrito c
                INNER JOIN item_carrito ic
                    ON c.id_carrito = ic.id_carrito
                WHERE c.id_usuario_carrito = ?
                AND c.estado = 'activo'";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$id_usuario]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fila['total'];
    }

    public function eliminarItem($id_item, $id_usuario)
    {
        // Verificar que el item pertenezca a un carrito activo del usuario
        $sql = "SELECT ic.id_item
                FROM item_carrito ic
                INNER JOIN carrito c
                    ON ic.id_carrito = c.id_carrito
                WHERE ic.id_item = ?
                AND c.id_usuario_carrito = ?
                AND c.estado = 'activo'
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            $id_item,
            $id_usuario
        ]);

        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            return false;
        }

        $sqlDelete = "DELETE FROM item_carrito
                      WHERE id_item = ?";

        $stmtDelete = $this->conn->prepare($sqlDelete);

        return $stmtDelete->execute([$id_item]);
    }

    public function vaciarCarrito($id_usuario)
    {
        $carrito = $this->obtenerCarritoActivo($id_usuario);

        if (!$carrito) {
            return false;
        }

        $sql = "DELETE FROM item_carrito
                WHERE id_carrito = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$carrito['id_carrito']]);
    }

    public function marcarConvertido($id_carrito)
    {
        $sql = "UPDATE carrito
                SET estado = 'convertido'
                WHERE id_carrito = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id_carrito]);
    }

}
