<?php

require_once '../config/database.php';
require_once '../models/Usuario.php';

if(isset($_POST['registrar']))
{
    $db = new Database();

    $conexion = $db->conectar();

    $usuario = new Usuario($conexion);

    if ($_POST['clave'] !== $_POST['confirmar_clave']) {

    header("Location: ../views/home.php?registro=clave");
    exit;
    }

    $usuario->registrar(

        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['email'],
        $_POST['clave'],
        $_POST['telefono']

    );

    header("Location: ../views/home.php?registro=ok");
    exit;
}
if(isset($_POST['login']))
{
    session_start();

    $db = new Database();

    $conexion = $db->conectar();

    $usuario = new Usuario($conexion);

    $datos = $usuario->login($_POST['email']);

if(!$datos)
{
    header("Location: ../views/home.php?login=correo");
    exit;
}

if(!password_verify(
        $_POST['clave'],
        $datos['clave_usuarios']
))
{
    header("Location: ../views/home.php?login=clave");
    exit;
}

$_SESSION['id_usuario'] =
    $datos['id_usuarios'];

$_SESSION['nombre'] =
    $datos['nombres_usuarios'];

$rol = $usuario->obtenerRol(
    $datos['id_usuarios']
);

$_SESSION['rol'] =
    $rol['nombre_roles'];

header("Location: ../views/home.php");
exit;
}