<?php

/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that

require("../Controller/Conexion.php");
date_default_timezone_set('Etc/UTC');

require 'PHPMailerAutoload.php';



$mail = new PHPMailer;

$mail->isSMTP();

$mail->Debugoutput = 'html';


$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;

$mail->SMTPSecure = 'tls';

$mail->SMTPAuth = true;

$mail->Username = "soporteprocessplues@gmail.com";

$mail->Password = "12345678#$#$";

$mail->addAddress("ooodam15@gmail.com","damian");

$mail->Subject = "Cumple !!!".date("h:i:s");


$ht = "
<!DOCTYPE html>
	<html lang='es'>
		<head>
			<title>:: PROCESS + ::</title>
			<meta charset='utf-8' />
		</head>
		<body>
			<div class = 'contenedor_principal' style = 'background-image: url(http:process.toro-love.com:82/Process/images/cumple/fondo.jpg);background-repeat: no-repeat;width:800px;height:800px;'>
				<div class = 'tarjeta' style = 'background-image: url(http:process.toro-love.com:82/Process/images/cumple/tarjeta.png);background-repeat:no-repeat;width:800px;height:800px;z-index:100;position:absolute;left:400px;top:400px;''>
					<div class = 'contenido' style = 'height:300px;	width:500px;z-index:150;padding-left:250px;padding-top:100px;'>
							<table width = '100%'>";
							
							$sql = mysql_query("select e.nombre_empleado,e.fecha_nacimiento,e.foto,emp.logo,e.cargo_empleado
							from empleado e, empresa emp
							where  month(e.fecha_nacimiento) = month(SYSDATE()) AND (day(e.fecha_nacimiento)) = (day(SYSDATE())-1) and e.pk_empresa = emp.cod_interno_empresa");
							$i = 0;
							while($row = mysql_fetch_array($sql)){
								if($i == 3){
									$i = 0;
									for($x = 0;$x < 7;$x++){
										$ht.="<tr><td colspan = '4' style = 'color:transparent;'>$i</td></tr>";
									}
								}else{
									$ht.= "<tr>
										<td rowspan = '4'>
											<img src = 'http:process.toro-love.com:82/Process/Process/EMPLEADO/".$row['foto']."' width = '150px' height = '150px' title = '".$row['nombre_empleado']."' style ='border-radius:1em;'/>
										</td>
									</tr>
									<tr>
										<td style = 'vertical-aling:top;'><strong>".utf8_decode($row['nombre_empleado'])."</strong></td>
									</tr>
									<tr>
										<td style = 'vertical-aling:top;'><strong>CARGO: ".utf8_decode($row['cargo_empleado'])."</strong></td>
									</tr>
									<tr>
										<td style = 'vertical-aling:top;'>
											<img src = 'http:process.toro-love.com:82/Process/images/logos/".$row['logo']."' width = '250px' height = 'auto' />
										</td>
									</tr>";
									$i++;
								}
								
							}
						
					$ht.="</table>
					</div>
				</div>
			</div>
		</body>
	</html>";


$mail->msgHTML($ht);
//$mail->msgHTML(file_get_contents('tarjeta.php'));

$mail->AltBody = 'This is a plain-text message body';


if (!$mail->send()) {
   $vari = 0;
   echo "Error al enviar el mensaje: " . $mail->ErrorInfo;
} else {
    echo "Enviado".date("Y-m-d h:i:s");
}

