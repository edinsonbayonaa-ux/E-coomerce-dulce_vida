<?php

session_start();

if(
    !isset($_SESSION['rol']) ||
    $_SESSION['rol'] != 'Administrador'
){
    header("Location: home.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Panel Administrador</title>
</head>
<body>

<h1>Panel de Administración</h1>

<p>
Bienvenido,
<?php echo htmlspecialchars($_SESSION['nombre']); ?>
</p>

<p>
Rol:
<?php echo htmlspecialchars($_SESSION['rol']); ?>
</p>

<p>
Módulo de administración disponible para futuras funcionalidades.
</p>

<a href="home.php">Volver al inicio</a>

</body>
</html>