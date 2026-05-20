<?php
include("getconex.php");
session_start();

// CAPA DE SEGURIDAD MÁXIMA: Si no hay sesión o el puesto NO es Recursos Humanos (ID = 3), se le bota
if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != true || $_SESSION['id_puesto'] != 3) {
    header("Location: login.php");
    exit();
}

// Consultar los registros de toda la fábrica
$tsql = "SELECT A.id_registro, U.username, U.nombre, U.apellido_paterno, A.fecha, A.hora_entrada, A.hora_salida 
         FROM Asistencia A 
         INNER JOIN Usuarios U ON A.id_usuario = U.id_usuario 
         ORDER BY A.fecha DESC, U.username ASC";
         
$stmt = sqlsrv_query($conectar, $tsql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Recursos Humanos</title>
    <link rel="shortcut icon" href="favicon.png"> 
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; margin: 0; padding: 20px; }
        .container { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 950px; margin: 3vh auto; }
        
        .header-box { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e4e6eb; padding-bottom: 15px; margin-bottom: 20px; }
        h2 { color: #1c1e21; margin: 0; }
        
        .nav-buttons { display: flex; gap: 10px; }
        
        .btn-back { background-color: #6c757d; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px; transition: background 0.2s; }
        .btn-back:hover { background-color: #5a6268; }

        /* Botón para cerrar sesión */
        .btn-logout { background-color: #dc3545; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px; transition: background 0.2s; }
        .btn-logout:hover { background-color: #bd2130; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: center; font-size: 14px; }
        th { background-color: #1c1e21; color: white; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        
        .btn-edit { background-color: #ffc107; color: #212529; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 13px; display: inline-block; }
        .btn-edit:hover { background-color: #e0a800; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-box">
        <div>
            <h2>Panel de Recursos Humanos</h2>
            <span style="color: #65676b; font-size: 14px;">Monitoreo global de entradas y salidas de la fábrica</span>
        </div>
        <div class="nav-buttons">
            <a href="index.php" class="btn-back">← Volver al Inicio</a>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Nombre del Trabajador</th>
                <th>Fecha</th>
                <th>Hora de Entrada</th>
                <th>Hora de Salida</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['nombre'] . " " . $row['apellido_paterno']); ?></td>
                <td><?php echo $row['fecha']->format('d/m/Y'); ?></td>
                <td><?php echo $row['hora_entrada'] ? $row['hora_entrada']->format('H:i:s') : '<span style="color: #dc3545; font-weight:bold;">---</span>'; ?></td>
                <td><?php echo $row['hora_salida'] ? $row['hora_salida']->format('H:i:s') : '<span style="color: #ffc107; font-weight:bold;">Pendiente</span>'; ?></td>
                <td>
                    <a href="editar_registro.php?id=<?php echo $row['id_registro']; ?>" class="btn-edit">Modificar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>