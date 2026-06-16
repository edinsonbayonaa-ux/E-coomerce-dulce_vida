<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Iniciar Sesión</h2>

<form method="POST" action="../controllers/UsuarioController.php">

    <input
        type="email"
        name="email"
        placeholder="Correo"
        required>

    <br><br>

    <input
        type="password"
        name="clave"
        placeholder="Contraseña"
        required>

    <br><br>

    <button
        type="submit"
        name="login">

        Ingresar

    </button>

</form>

</body>
</html>