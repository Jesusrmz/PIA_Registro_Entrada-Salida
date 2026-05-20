<?php
include("getconex.php");
session_start();

if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != true || $_SESSION['id_puesto'] != 3) {
    header("Location: login.php");
    exit();
}

$mensaje = "";

if (isset($_GET['id'])) {
    $id_registro = $_GET['id'];

    $tsql_buscar = "SELECT A.id_registro, U.nombre, U.apellido_paterno, A.fecha, A.hora_entrada, A.hora_salida 
                    FROM Asistencia A 
                    INNER JOIN Usuarios U ON A.id_usuario = U.id_usuario 
                    WHERE A.id_registro = ?";
    $params_buscar = array($id_registro);
    $stmt_buscar = sqlsrv_query($conectar, $tsql_buscar, $params_buscar);

    if ($stmt_buscar === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt_buscar, SQLSRV_FETCH_ASSOC);
    if (!$row) {
        die("Registro no encontrado.");
    }
} else {
    header("Location: admin_rh.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_reg_update = $_POST['id_registro'];
    $nueva_entrada = $_POST['hora_entrada'];
    $nueva_salida = $_POST['hora_salida'];

    $param_entrada = !empty($nueva_entrada) ? $nueva_entrada : null;
    $param_salida = !empty($nueva_salida) ? $nueva_salida : null;

    $tsql_update = "UPDATE Asistencia SET hora_entrada = ?, hora_salida = ? WHERE id_registro = ?";
    $params_update = array($param_entrada, $param_salida, $id_reg_update);
    $stmt_update = sqlsrv_query($conectar, $tsql_update, $params_update);

    if ($stmt_update === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $mensaje = "<div style='background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; text-align: center;'>
                    ¡Registro actualizado con éxito!
                </div>";
    
    $row['hora_entrada'] = $param_entrada ? new DateTime($param_entrada) : null;
    $row['hora_salida'] = $param_salida ? new DateTime($param_salida) : null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Registro de Asistencia</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 400px; box-sizing: border-box; }
        h2 { text-align: center; color: #1c1e21; margin-top: 0; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #65676b; font-size: 14px; margin-bottom: 20px; }
        label { font-size: 14px; color: #1c1e21; font-weight: bold; display: block; margin-top: 15px; }
        input[type="text"], input[type="time"] { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; background-color: #f8f9fa; }
        input[type="time"] { background-color: #ffffff; }
        .btn-save { width: 100%; background-color: #0866ff; color: white; border: none; font-weight: bold; cursor: pointer; padding: 12px; border-radius: 4px; margin-top: 20px; font-size: 15px; }
        .btn-save:hover { background-color: #0551cc; }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; color: #65676b; text-decoration: none; font-size: 14px; }
        .btn-cancel:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="card">
    <h2>Modificar Asistencia</h2>
    <div class="subtitle">Empleado: <strong><?php echo htmlspecialchars($row['nombre'] . " " . $row['apellido_paterno']); ?></strong></div>
    
    <?php echo $mensaje; ?>

    <form method="POST" action="editar_registro.php?id=<?php echo $id_registro; ?>">
        <input type="hidden" name="id_registro" value="<?php echo $row['id_registro']; ?>">

        <label>Fecha del Registro (No modificable):</label>
        <input type="text" value="<?php echo $row['fecha']->format('d/m/Y'); ?>" disabled>

        <label>Hora de Entrada:</label>
        <input type="time" name="hora_entrada" step="1" value="<?php echo $row['hora_entrada'] ? $row['hora_entrada']->format('H:i:s') : ''; ?>">

        <label>Hora de Salida:</label>
        <input type="time" name="hora_salida" step="1" value="<?php echo $row['hora_salida'] ? $row['hora_salida']->format('H:i:s') : ''; ?>">

        <input type="submit" value="Guardar Cambios" class="btn-save">
    </form>
    
    <a href="admin_rh.php" class="btn-cancel">Cancelar y Volver</a>
</div>

</body>
</html>