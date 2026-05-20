<?php
include("getconex.php");
session_start();

date_default_timezone_set('America/Monterrey');

if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['usuario_id'];
    $accion = $_POST['accion'];
    $hoy = date('Y-m-d');
    $ahora = date('Y-m-d H:i:s'); 

    if ($accion == "entrada") {
        $tsql = "INSERT INTO Asistencia (id_usuario, fecha, hora_entrada) VALUES (?, ?, ?)";
        $params = array($id_usuario, $hoy, $ahora);
    } 
    elseif ($accion == "salida") {
        $tsql = "UPDATE Asistencia SET hora_salida = ? WHERE id_usuario = ? AND fecha = ? AND hora_salida IS NULL";
        $params = array($ahora, $id_usuario, $hoy);
    }

    $stmt = sqlsrv_query($conectar, $tsql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: index.php");
    exit();
}
?>