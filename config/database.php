<?php

class Database {

    private $host = "localhost";
    private $dbname = "drappsco_dulce_vida";
    private $user = "root";
    private $pass = "edinson123A-";

    public function conectar() {

        try {

            $conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->user,
                $this->pass
            );

            return $conexion;

        } catch(PDOException $e) {

            die("Error: " . $e->getMessage());

        }
    }
}