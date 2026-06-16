<?php

class Usuario {

    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }

    public function listar()
    {
        $sql = "SELECT * FROM usuarios";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar(
    $nombre,
    $apellido,
    $email,
    $clave,
    $telefono
) {

    $sql = "INSERT INTO usuarios(

                nombres_usuarios,
                apellidos_usuarios,
                email_usuarios,
                clave_usuarios,
                telefono_usuarios,
                estado_usuarios

            )

            VALUES(

                ?,
                ?,
                ?,
                ?,
                ?,
                'activo'

            )";

    $stmt = $this->conn->prepare($sql);

    $resultado = $stmt->execute([

        $nombre,
        $apellido,
        $email,
        password_hash($clave, PASSWORD_DEFAULT),
        $telefono

    ]);

    if($resultado){

        // ID del usuario recién creado
        $idUsuario = $this->conn->lastInsertId();

        // Asignar rol Cliente (id_rol = 2)
        $sqlRol = "INSERT INTO usuarios_roles
                   (id_usuario, id_rol)
                   VALUES (?, 2)";

        $stmtRol = $this->conn->prepare($sqlRol);
        $stmtRol->execute([$idUsuario]);

    }

    return $resultado;
}

public function login($email)
    {
        $sql = "SELECT * FROM usuarios
                WHERE email_usuarios = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerRol($idUsuario)
    {
        $sql = "SELECT r.nombre_roles
                FROM usuarios_roles ur
                INNER JOIN roles r
                    ON ur.id_rol = r.id_roles
                WHERE ur.id_usuario = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idUsuario]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}