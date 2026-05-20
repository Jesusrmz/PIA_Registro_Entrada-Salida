<?php
include("getconex.php");
session_start();

date_default_timezone_set('America/Monterrey');

if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != true) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['logged_user'];
$id_usuario = $_SESSION['usuario_id']; 
$puesto_usuario = $_SESSION['id_puesto'];
$hoy = date('Y-m-d');

$tsql = "SELECT hora_entrada, hora_salida FROM Asistencia WHERE id_usuario = ? AND fecha = ?";
$params = array($id_usuario, $hoy);
$stmt = sqlsrv_query($conectar, $tsql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
$registro = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Asistencia - Control de Entrada/Salida</title>
    <link rel="shortcut icon" href="favicon.png"> <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f0f2f5; display: flex; flex-direction: column; align-items: center; min-height: 100vh; }
        .navbar { width: 100%; background-color: #ffffff; padding: 10px 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; }
        .navbar .user-info { font-size: 16px; color: #1c1e21; }
        .navbar .logout-btn { color: #dc3545; text-decoration: none; font-weight: bold; font-size: 14px; }
        .navbar .logout-btn:hover { text-decoration: underline; }

        .card { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 350px; text-align: center; margin-top: 10vh; }
        h2 { color: #1c1e21; margin-bottom: 5px; }
        .date-display { color: #65676b; font-size: 14px; margin-bottom: 25px; }
        
        .btn { width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 4px; font-weight: bold; font-size: 15px; cursor: pointer; transition: background 0.2s; }
        .btn-entrada { background-color: #0866ff; color: white; }
        .btn-entrada:hover { background-color: #0551cc; }
        .btn-salida { background-color: #28a745; color: white; }
        .btn-salida:hover { background-color: #218838; }
        
        .btn-admin { display: block; background-color: #212529; color: white; text-decoration: none; padding: 12px; margin: 15px 0 5px 0; border-radius: 4px; font-weight: bold; font-size: 15px; text-align: center; box-sizing: border-box; }
        .btn-admin:hover { background-color: #000000; }

        .status-blocked { background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; border: 1px solid #ffeeba; font-size: 14px; margin: 15px 0; }
        .link-history { display: inline-block; margin-top: 20px; color: #0866ff; text-decoration: none; font-size: 14px; }
        .link-history:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="user-info">
        Usuario: <strong><?php echo htmlspecialchars($usuario); ?></strong>
    </div>
    <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
</div>

<div class="card">
    <h2>Control de Asistencia</h2>
    <div class="date-display">Fecha de hoy: <strong><?php echo date('d/m/Y'); ?></strong></div>

    <h3 style="color: #1c1e21; font-size: 18px; margin-top: 10px; margin-bottom: 20px; font-weight: normal;">
        ¿Qué desea hacer?
    </h3>

    <?php if (!$registro): ?>
        <form method="POST" action="proceso_asistencia.php">
            <input type="hidden" name="accion" value="entrada">
            <button type="submit" class="btn btn-entrada">Registrar hora de entrada</button>
        </form>
    <?php elseif ($registro['hora_entrada'] && !$registro['hora_salida']): ?>
        <p style="color: #28a745;">Entrada registrada a las: <strong><?php echo $registro['hora_entrada']->format('H:i:s'); ?></strong></p>
        <form method="POST" action="proceso_asistencia.php">
            <input type="hidden" name="accion" value="salida">
            <button type="submit" class="btn btn-salida">Registrar hora de salida</button>
        </form>
    <?php else: ?>
        <div class="status-blocked">
            <strong>Jornada Completada</strong><br>
            Ya has registrado tu entrada y tu salida hoy.
        </div>
    <?php endif; ?>

    <?php if ($puesto_usuario == 3): ?>
        <a href="admin_rh.php" class="btn-admin"> Ir al Panel de RH</a>
    <?php endif; ?>

    <a href="historial.php" class="link-history">Consultar mis registros anteriores</a>
</div>

</body>
</html>