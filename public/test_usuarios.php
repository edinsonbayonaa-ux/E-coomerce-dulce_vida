<?php

require_once '../config/database.php';
require_once '../models/Usuario.php';

$db = new database();

$conexion = $db->conectar();

$usuario = new Usuario($conexion);

$usuarios = $usuario->listar();

echo "<pre>";
print_r($usuarios);
echo "</pre>";