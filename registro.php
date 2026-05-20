<?php
include("getconex.php");

$sql_puestos = "SELECT * FROM Puestos";
$stmt_puestos = sqlsrv_query($conectar, $sql_puestos);

if ($stmt_puestos === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Empleados</title>
    <link rel="shortcut icon" href="favicon.png"> <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; background-color: #f0f2f5; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 400px; box-sizing: border-box; margin: 20px 0; }
        h2 { text-align: center; color: #1c1e21; margin-top: 0; }
        label { font-size: 14px; color: #1c1e21; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin: 6px 0 14px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { width: 100%; background-color: #28a745; color: white; border: none; font-weight: bold; cursor: pointer; padding: 10px; border-radius: 4px; }
        .btn-submit:hover { background-color: #218838; }
        .link-back { display: block; text-align: center; margin-top: 15px; color: #0866ff; text-decoration: none; font-size: 14px; }
        .link-back:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="card">
    <h2>Registro de Trabajador</h2>
    <form action="proceso_registro.php" method="POST">
        <label>Nombre de Usuario:</label>
        <input type="text" name="username" required>

        <label>Nombre(s):</label>
        <input type="text" name="nombre" required>

        <label>Apellido Paterno:</label>
        <input type="text" name="apellido_paterno" required>

        <label>Apellido Materno:</label>
        <input type="text" name="apellido_materno" required>

        <label>Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" required>

        <label>Correo Electrónico:</label>
        <input type="email" name="email" required>

        <label>Puesto de Trabajo:</label>
        <select name="id_puesto" required>
            <option value="">-- Seleccione un puesto --</option>
            <?php while($puesto = sqlsrv_fetch_array($stmt_puestos, SQLSRV_FETCH_ASSOC)): ?>
                <option value="<?php echo $puesto['id_puesto']; ?>">
                    <?php echo htmlspecialchars($puesto['nombre_puesto']); ?>
                </option>
            <?php endwhile; ?> </select>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Registrar Empleado" class="btn-submit">
    </form>
    <a href="login.php" class="link-back">Regresar al Login</a>
</div>

</body>
</html>