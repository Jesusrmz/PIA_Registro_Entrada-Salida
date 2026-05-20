<?php
include("getconex.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['username'];
    $password_ingresada = $_POST['password'];

    $tsql = "{call sp_LoginUsuario(?)}"; 
    $params = array(array($usuario, SQLSRV_PARAM_IN));
    
    $stmt = sqlsrv_query($conectar, $tsql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        
        if (password_verify($password_ingresada, $row['password_hash'])) {
            session_start();
            $_SESSION['valid_user'] = true;
            $_SESSION['usuario_id'] = $row['id_usuario']; 
            $_SESSION['logged_user'] = $row['username'];
            
            $_SESSION['id_puesto'] = $row['id_puesto']; 
            
            header("Location: index.php"); 
            exit();
        } else {
            header("Location: acceso_error.php");
            exit();
        }
    } else {
        header("Location: acceso_error.php");
        exit();
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conectar);
}
?>