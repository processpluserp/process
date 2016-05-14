<?php
	include("Controller/Conexion.php");
	
	$codigo_usuario_copiar = 1;
	$nuevo_usuario = 20;	
	
	//Copiar Perfil Acceso Módulos.
	
	/*for($i = 2; $i < 20; $i++){
		$sql = mysql_query("select codigo from permisos_op where usuario = '$codigo_usuario_copiar'");
		while($row = mysql_fetch_array($sql)){
			mysql_query("insert into permisos_op(codigo,usuario) values('".$row['codigo']."','".$i."')");
		}
	}*/
	//echo mysql_num_rows($sql);
	
	//Permisos Empresas:
	/*$sql = mysql_query("select cod_empresa from  pusuemp where cod_usuario = '$codigo_usuario_copiar'");
	while($row = mysql_fetch_array($sql)){
		mysql_query("insert into pusuemp(cod_empresa,cod_usuario,fecha,asignador) values('".$row['cod_empresa']
		."','".$nuevo_usuario."','".date("Y-m-d")."','".$codigo_usuario_copiar."')");
	}*/
	for($i = 2; $i < 20; $i++){
		$sql = mysql_query("select cod_empresa from  pusuemp where cod_usuario = '$codigo_usuario_copiar'");
		while($row = mysql_fetch_array($sql)){
			mysql_query("insert into pusuemp(cod_empresa,cod_usuario,fecha,asignador) values('".$row['cod_empresa']
			."','".$i."','".date("Y-m-d")."','".$codigo_usuario_copiar."')");
		}
	}
	
	
	/*
	//Permisos Cliente Producto:
	$sql = mysql_query("select pk_empresa,cod_cliente,cod_producto from pcliepro where cod_usuario = '$codigo_usuario_copiar'");
	while($row = mysql_fetch_array($sql)){
		mysql_query("insert into pcliepro(pk_empresa,cod_cliente,cod_producto,cod_usuario,fecha,asignador) values('".$row['pk_empresa']."','".$row['cod_cliente']."','".
		$row['cod_producto']."','".$nuevo_usuario."','".date("Y-m-d")."','".$codigo_usuario_copiar."')");
	}
	
	
	//Permisos Depto:
	$sql = mysql_query("select pk_depto,pk_empresa  from pdepto where usuario = '$codigo_usuario_copiar'");
	while($row = mysql_fetch_array($sql)){
		mysql_query("insert into pdepto(pk_depto,pk_empresa,usuario,fecha,asignador) values('".$row['pk_depto']."','".$row['pk_empresa']."','".
		$nuevo_usuario."','".date("Y-m-d")."','".$codigo_usuario_copiar."')");
	}
	
	//Permisos Asignados:
	$sql = mysql_query("select pk_depto,pk_empresa,asignado from prespon where usuario = '$codigo_usuario_copiar'");
	while($row = mysql_fetch_array($sql)){
		mysql_query("insert into prespon(pk_depto,pk_empresa,asignado,usuario,fecha) values('".$row['pk_depto']."','".$row['pk_empresa']."','".
		$row['asignado']."','".$nuevo_usuario."','".date("Y-m-d")."')");
		echo mysql_error();
	}*/
?>