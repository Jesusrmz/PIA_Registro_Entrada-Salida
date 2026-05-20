<?php
include("getconex.php");
session_start();

if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != true) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Requisito #15: Sentencia preparada contra SQL Injection
$tsql = "SELECT fecha, hora_entrada, hora_salida FROM Asistencia WHERE id_usuario = ? ORDER BY fecha DESC";
$params = array($id_usuario);
$stmt = sqlsrv_query($conectar, $tsql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Historial de Asistencia</title>
    <link rel="shortcut icon" href="favicon.png"> <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .card-wide { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 600px; box-sizing: border-box; }
        h2 { color: #1c1e21; text-align: center; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #0866ff; color: white; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .btn-back { display: inline-block; background-color: #6c757d; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; margin-bottom: 15px; font-size: 14px; }
        .btn-back:hover { background-color: #5a6268; }
    </style>
</head>
<body>

<div class="card-wide">
    <a href="index.php" class="btn-back">← Volver al Panel</a>
    <h2>Mis Registros de Asistencia</h2>
    
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora de Entrada</th>
                <th>Hora de Salida</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['fecha']->format('d/m/Y'); ?></td>
                <td><?php echo $row['hora_entrada'] ? $row['hora_entrada']->format('H:i:s') : '---'; ?></td>
                <td><?php echo $row['hora_salida'] ? $row['hora_salida']->format('H:i:s') : '---'; ?></td>
            </tr>
            <?php endwhile; ?> </tbody>
    </table>
</div>

</body>
</html>