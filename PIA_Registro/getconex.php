<?php
	$serverName = 'laptop-akire0v7';   
	$connectionInfo = array( 'Database'=>'db_Registros', 'UID'=>'LSBDUSER', 'PWD'=>'C0ntr4s3n4*#','CharacterSet' => 'UTF-8'); 
	$conectar = sqlsrv_connect( $serverName, $connectionInfo);
	if ($conectar){
		echo 'OK';
	}else{
		echo 'Hubo un error al conectarse a la base de datos, a continuación los erores: <br><br>'; 
		die( print_r(sqlsrv_errors(),true)); 
	}
?>