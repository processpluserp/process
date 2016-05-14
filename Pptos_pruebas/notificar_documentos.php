<!DOCTYPE html>
	<html>
	
<?php
	require("Controller/Conexion.php");
	
//$message = file_get_contents("index.html");

	$mensaje = "<html>
		
					<body>";

	
	//$cabecera = "From: damian.mosquera@grupolaestacion.com" . "\r\n";
	$cabecera = "From: soporte.processplues@gmail.com" . "\r\n";
	$cabecera .= 'Cc: <damian.mosquera@grupolaestacion.com>' . "\r\n";
	$cabecera .= "MIME-Version: 1.0\r\n";
	$cabecera .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	
	$num_correos = 0;
	$sql = mysql_query("select codigo_documento,nombre_documento from tipodoc");
	$sql_empresa = mysql_query("select cod_interno_empresa from empresa where estado = '1'");
	//while($emp = mysql_fetch_array($sql_empresa)){
	
		
		while($row = mysql_fetch_array($sql)){
			$sql2 = mysql_query("select consecutivo,fvencimiento,pk_empresa from  documentos_legales_entidades where pk_tdocumento = '".$row['codigo_documento']."' order by pk_empresa asc");			
			if(mysql_num_rows($sql2) != 0){
				while($doc = mysql_fetch_array($sql2)){
					$datetime1 = new DateTime(date("Y-m-d"));
					$datetime2 = new DateTime($doc['fvencimiento']);
					$interval = $datetime1->diff($datetime2);
					
					if($interval->format('%a') < 15){
						$sql3 = mysql_query("select empleado from notificaciones where codigo = '".$row['codigo_documento']."' and empresa = '".$doc['pk_empresa']."' and tipo = 'DOC'");
						$num_correos = mysql_num_rows($sql3);
						if(mysql_num_rows($sql3) > 0){
							$i = 0;
							$para = "";
							while($cor = mysql_fetch_array($sql3)){
								$i++;
								if($i < $num_correos){
									$para .=$cor['empleado'].",";
								}else{
									$para .=$cor['empleado'];
								}
							}
						//$para = "damian.mosquera@grupolaestacion.com";
						$asunto = "VENCIMIENTO ".strtoupper($row['nombre_documento']);
						$mensaje = utf8_decode("<h3>Buenos días</h3>
						<strong><p style = 'font-size:1.1em;'>PROCESS le informa que el documento ").( ($row['nombre_documento'])).utf8_decode(" <span style = 'color:red;'>vence el  ".$doc['fvencimiento'].".</span>
						Por favor, diríjase al módulo de Gestión y actualice este documento.</p></body></html>");
						if(mail($para, $asunto, $mensaje, $cabecera)) {
							echo 'Correo enviado</BR>';
						}else {
							echo 'Error al enviar mensaje</BR>';
							}
						}
					}else{
						echo utf8_decode( "EL DOCUMENTO ".strtoupper($row['nombre_documento'])." ESTÁ AL DÍA, SE VENCE  EL DÍA ".$doc['fvencimiento']."; ES DECIR EN ".$interval->format('%a')." DÍAS</BR>");
					}		
				}
			}
		}
		echo "FECHA DE EJECUCIÓN ".date("Y-m-d H:m:s");
	//}
	
	$mensaje = "<html>
		
					<body>";

	
	$cabecera = "From: SOPORTE PROCESS PLUS<soporte.processplues@gmail.com>" . "\r\n";
	$cabecera .= 'Cc: Damian Mosquera<damian.mosquera@grupolaestacion.com>' . "\r\n";
	$cabecera .= "MIME-Version: 1.0\r\n";
	$cabecera .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	
	$num_correos = 0;
	$asunto = utf8_decode("CUMPLEAÑOS ".date("Y-m-d"));
	$sql = mysql_query("select nombre_empleado,fecha_nacimiento from empleado where month(fecha_nacimiento) = month(SYSDATE()) AND day(fecha_nacimiento) = day(SYSDATE())");
	$texto = "<h3>Hola ! Qué tal ?</h3>
						<strong><p style = 'font-size:1.1em;'>Un día como hoy nacieron las siguientes personas:</strong> </p></br>"."
						<ul>";
	while($row = mysql_fetch_array($sql)){
		//echo $row['nombre_empleado']." - ".$row['fecha_nacimiento']."</br>";
		$texto .="<li>
					<span style = 'color:red;'>".$row['nombre_empleado']."</span>
				</li>";
	}
	$para = "Martin Pinzon <martin.pinzon@toro-love.com>,Martin Pinzon <martin.pinzon@grupolaestacion.com>";
	$sql = mysql_query("select nombre_empleado,fecha_nacimiento from empleado where month(fecha_nacimiento) = month(SYSDATE()) AND day(fecha_nacimiento) = day(SYSDATE())");
	if(mysql_num_rows($sql) != 0){
		if(mail($para, $asunto, utf8_decode($texto."</ul>"), $cabecera)) {
			echo 'Correo enviado</BR>';
		}
	}
	else {
		echo 'Nadie Cumple Años hoy !!';
	}
	
	
?>
</html>
