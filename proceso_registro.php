<?php
include("getconex.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $nombre = $_POST['nombre'];
    $ap_paterno = $_POST['apellido_paterno'];
    $ap_materno = $_POST['apellido_materno'];
    $fecha_nac = $_POST['fecha_nacimiento'];
    $email = $_POST['email'];
    $puesto = $_POST['id_puesto']; 
    $pass_plana = $_POST['password'];

    $pass_cifrada = password_hash($pass_plana, PASSWORD_BCRYPT);

    $tsql = "INSERT INTO Usuarios (username, nombre, apellido_paterno, apellido_materno, email, password_hash, id_puesto, fecha_nacimiento) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $params = array($user, $nombre, $ap_paterno, $ap_materno, $email, $pass_cifrada, $puesto, $fecha_nac);
    $stmt = sqlsrv_query($conectar, $tsql, $params);

    if ($stmt === false) {
        echo "<h2 style='color: red;'>Error al registrar el usuario:</h2>";
        die(print_r(sqlsrv_errors(), true));
    }

    echo "
    <div style='font-family: Arial, sans-serif; text-align: center; margin-top: 10vh;'>
        <h2 style='color: #28a745;'>¡Registro Exitoso!</h2>
        <p>El trabajador se ha guardado correctamente.</p>
        <a href='login.php' style='color: #0866ff; text-decoration: none; font-weight: bold;'>Volver al Login</a>
    </div>";

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conectar);
}
?>