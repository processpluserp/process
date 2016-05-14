<?php

/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require 'Mailer/PHPMailerAutoload.php';
include("../Controller/Conexion.php");


$mail = new PHPMailer;

$mail->isSMTP();

$mail->Debugoutput = 'html';


$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;

$mail->SMTPSecure = 'tls';

$mail->SMTPAuth = true;

$mail->Username = "soporteprocessplues@gmail.com";

$mail->Password = "12345678#$#$";


//BUSCO EL NOMBRE DEL CLIENTE
$nombre_cliente = "";

	$sql =mysql_query("select c.nombre_legal_clientes
	from clientes c, cabot t
	where t.codigo_ot = '".$_POST['ot']."' and t.producto_clientes_pk_clientes_nit_procliente = c.codigo_interno_cliente");
	while($row = mysql_fetch_array($sql)){
		$nombre_cliente = $row['nombre_legal_clientes'];
	}


//BUSCO EL CORREO DE QUIEN CREA LA OT
$sql_correo = mysql_query("select e.email_empleado,e.nombre_empleado from empleado e, cabot ot,usuario u
where ot.codigo_ot = '".$_POST['ot']."' and ot.ejecutivo = u.idusuario and u.pk_empleado = e.documento_empleado");
while($row = mysql_fetch_array($sql_correo)){
	$mail->setFrom($row['email_empleado'], $row['nombre_empleado']);
	$mail->addAddress($row['email_empleado'], $row['nombre_empleado']);
	$mail->addReplyTo($row['email_empleado'], $row['nombre_empleado']);
}

/*
//INCLUYO LOS CORREOS DE LAS PERSONAS A LAS CUALES SIEMPRE VA EL CORREO
$usua = $_POST['usu'];
$sql_nn = mysql_query("select emp.cod_interno_empresa as empresax
from  empresa emp, empleado e, usuario u
where u.idusuario = '$usua' and u.pk_empleado = e.documento_empleado and e.pk_empresa = emp.cod_interno_empresa");

$emp_sql = "";
while($row = mysql_fetch_array($sql_nn)){
	$emp_sql = $row['empresax'];
}

$sql_copiados_real = mysql_query("select nombre,correo from informe_entrevista_correos where empresa = '$emp_sql' and estado = '1' order by nombre asc");
while($row = mysql_fetch_array($sql_copiados_real)){
	$mail->AddBCC($row['correo'],$row['nombre']);
}

//NUEVO COPIADOS DEL CLIENTE
if($_POST['num_copiados_c'] > 0){
	$asis_clientex = $_POST['copiados_c'];
	if(count($asis_clientex) > 0){
		for($i = 0;$i < count($asis_clientex);$i++){
			$info = explode(" - ",$asis_clientex[$i]);
			$mail->AddBCC($info[1], $info[0]);
		}
	}
}
*/


//BUSCO LOS CORREOS DE LOS ASISTENTES DE LA AGENCIA
$emp = "";
if($_POST['num_asis_agencia'] > 0){
	$asis_list = $_POST['asis'];
	for($i = 0;$i < count($asis_list);$i++){
		$sql_correo = mysql_query("select e.email_empleado,e.nombre_empleado  
		from empleado e
		where e.email_empleado = '".$asis_list[$i]."'");
		while($row = mysql_fetch_array($sql_correo)){
			$emp.="<li>".$row['nombre_empleado']."</li>";
			$mail->addAddress($row['email_empleado'], $row['nombre_empleado']);
		}
	}
}

//BUSCO LOS CORREOS DE LOS COPIADOS
if($_POST['copi'] > 0){
	$asis_list = $_POST['list_copiados'];
	for($i = 0;$i < count($asis_list);$i++){
		$sql_correo = mysql_query("select e.email_empleado,e.nombre_empleado  
		from empleado e
		where e.email_empleado = '".$asis_list[$i]."'");
		while($row = mysql_fetch_array($sql_correo)){
			$mail->AddBCC($row['email_empleado'], $row['nombre_empleado']);
		}
	}
}



//ASISTENTES DEL CLIENTE
$clie = "";
if($_POST['num_cliente'] > 0){
	$asis_clientex = $_POST['cliente'];
	if(count($asis_clientex) > 0){
		for($i = 0;$i < count($asis_clientex);$i++){
			$info = explode(" - ",$asis_clientex[$i]);
			$clie.="<li>".$info[0]."</li>";
			$mail->addAddress($info[1], $info[0]);
		}
	}
}



//COMPROMISOS AGENCIA
$listad_compromisos_empresa = "";
if($_POST['num_comp_age'] > 0){
	$comp_agencia = $_POST['compromisos_emp'];
	$listad_compromisos_empresa = "<table width = '100%' class = 'tabla_comprimosos_ie'><tr><th>NOMBRE</th><th>FECHA</th><th>COMPROMISO</th></tr>";
	for($i = 0;$i < count($comp_agencia);$i++){
		$temp = $i + 1;
		$info = explode("<***+++>",$comp_agencia[$i]);
		$listad_compromisos_empresa.="<tr>
			<td width = '25%'>$temp. ".$info[1]."</td>
			<td nowrap width = '10%' align = 'center'>".$info[2]."</td>
			<td style = 'text-align:justify;'>".nl2br($info[3])."</td>
		</tr>";
	}
	$listad_compromisos_empresa.="</table>";
}


//COMPROMISOS CLIENTE
$listad_compromisos_cliente = "";
if($_POST['comp_clie_num'] > 0){
	$comp_agencia = $_POST['compromisos_clie'];
	$listad_compromisos_cliente = "<table width = '100%' class = 'tabla_comprimosos_ie'><tr><th>NOMBRE</th><th>FECHA</th><th>COMPROMISO</th></tr>";
	for($i = 0;$i < count($comp_agencia);$i++){
		$temp = $i + 1;
		$info = explode("<***+++>",$comp_agencia[$i]);
		$listad_compromisos_cliente.="<tr>
			<td width = '25%'>$temp. ".$info[0]."</td>
			<td nowrap width = '10%' align = 'center'>".$info[1]."</td>
			<td style = 'text-align:justify;'>".nl2br($info[2])."</td>
		</tr>";
	}
	$listad_compromisos_cliente.="</table>";
}

//TEMAS DE LA REUNIÓN
$list_temas = "";
if($_POST['contar_temas'] > 0){
	$temas = $_POST['temas'];
	$list_temas = "<table width = '100%' class = 'tabla_comprimosos_ies'><tr><td><ul>";
	for($i = 0;$i < count($temas);$i++){
		$temp = $i + 1;
		$info = explode("<***+++>",$temas[$i]);
		$list_temas.="<li style = 'text-align:justify'>$temp. ".nl2br($info[0])."</li>";
	}
	$list_temas.="</ul><td></tr></table>";
}




//ASUNTO DEL CORREO
$mail->Subject = utf8_decode($_POST['asunto']);

//CARGO EL LA IMAGEN DEL SISTEMA
$img = "<img src = '../images/process.png' width = '100'/>";

//BUSCO LA IMAGEN DE LA COMPAÑÍA
$sql = mysql_query("select e.logo from empresa e, cabot ot 
where ot.codigo_ot = '".$_POST['ot']."' and ot.pk_nit_empresa_ot = e.cod_interno_empresa");
$img_logo = "";
while($row = mysql_fetch_array($sql)){
	//$img_logo = "<img src = '../images/logos/".$row['logo']."' height = '80'/>";
	$img_logo = "<img src = 'http:process.toro-love.com:82/Process/images/logos/".$row['logo']."' height = '80'/>";
}

$ht = "
<html>
	<head>
		<link type='text/css' href='../css/tablas.css' rel='stylesheet'>
		<link type='text/css' href='../css/cabecera.css' rel='stylesheet'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		
		<style type = 'text/css'>
			.tabla_comprimosos_ie th{
				border:1px solid black;
				background-color:white;
			}
			.tabla_comprimosos_ie td{
				border:1px solid black;
			}
			.tabla_comprimosos_ie th:first-child{
				border-top-left-radius:0.3em;
				-moz-border-top-left-radius:0.3em;
				-webkit-border-top-left-radius:0.3em;
			}
			.tabla_comprimosos_ie th:last-child{
				border-top-right-radius:0.3em;
				-moz-border-top-right-radius:0.3em;
				-webkit-border-top-right-radius:0.3em;
			}
		</style>
	</head>
<body>
<table width = '100%' style = 'font-color:black;'>
	<tr>
		<td>
			$img_logo
		</td>
	</tr>
	<tr>
		<td>
			<span class = 'mensaje_bienvenida' style = 'font-size:60;color:black;font-weight: 900;'>INFORME DE ENTREVISTA</span>
		</td>
	</tr>
	<tr >
		<td colspan = '5' style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
			<table width = '100%'>
				<tr>
					<td style = 'text-align:left;padding-left:5%;'>
						<strong><p style = 'font-size:30;color:black;'>".("CLIENTE").":</strong> ".utf8_decode($nombre_cliente)."</p>
					</td>
					<td style = 'text-align:left;padding-right:5%;'>
						<strong><p>REFERENCIA:</strong> ".utf8_decode($_POST['asunto'])."</p>
					</td>
				</tr>
				<tr>
					<td style = 'text-align:left;padding-left:5%;'>
						<strong><p style = 'font-size:30;color:black;'>".utf8_decode("FECHA DE REUNIÓN Y LUGAR").":</strong> ".$_POST['fecha_reunion_ie']."; ".utf8_decode($_POST['lugar_reunion_ie'])."</p>
					</td>
					<td style = 'text-align:left;padding-right:5%;'>
						<strong><p>TIPO DE ".utf8_decode("REUNIÓN").":</strong> ".utf8_decode($_POST['tipo_entrevista'])."</p>
					</td>
				</tr>
				<tr>
					<td style = 'text-align:left;padding-left:5%;'>
						<strong><p>HORA DE INICIO:</strong> ".$_POST['hora_inicio_ie'].":".$_POST['minuto_inicio_ie'].":".$_POST['formato_inicio_ie']."</p>
					</td>
					<td style = 'text-align:left;padding-right:5%;'>
						<strong><p>HORA ".utf8_decode("FINALIZACIÓN").":</strong> ".$_POST['hora_fin_ie'].":".$_POST['minuto_fin_ie'].":".$_POST['formato_fin_ie']."</p>
					</td>
				</tr>
				<tr>
					<td style = 'text-align:left;padding-left:5%;'>
						<strong><p>ASISTENTES AGENCIA:</p></strong>
						<ul>$emp</ul>
					</td>
					<td style = 'text-align:left;padding-right:5%;'>
						<strong><p>ASISTENTES CLIENTE:</p></strong>
						<ul>$clie</ul>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td></br></td></tr>
	<tr>
		<td colspan = '5' style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
			<table width = '100%'>
				<tr>
					<td style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
						<strong><p>NOTA</p></strong>
					</td>
				</tr>
				<tr>
					<td>
						<strong><p style = 'text-align:justify;'>".utf8_decode("Después")." de 24 horas de recibir este correo, sino hay respuesta por parte del Cliente, se ".utf8_decode("entenderá")." que no hay ninguna ".utf8_decode("observación")." al respecto.</p></strong>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td></br></td></tr>
	<tr>
		<td colspan = '5' style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
			<table width = '100%'>
				<tr>
					<td style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
						<strong><p>".utf8_decode("INFORMACIÓN")." GENERAL</p></strong>
					</td>
				</tr>
				<tr>
					<td style = 'text-align:justify;padding-left:10px;'>
						".nl2br(utf8_decode($_POST['info_general_ie']))."
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td></br></td></tr>
	<tr>
		<td colspan = '5' style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
			<table width = '100%'>
				<tr>
					<td style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
						<strong><p>TEMAS TRATADOS</p></strong>
					</td>
				</tr>
				<tr>
					<td>
						<p style = 'text-align:justify;'>".utf8_decode($list_temas)."</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td></br></td></tr>
	<tr>
		<td colspan = '5' style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
			<table width = '100%'>
				<tr>
					<td style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
						<strong><p>COMPROMISOS AGENCIA</p></strong>
					</td>
				</tr>
				<tr>
					<td>
						<p style = 'text-align:justify;'>".utf8_decode($listad_compromisos_empresa)."</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td></br></td></tr>
	<tr>
		<td colspan = '5' style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
			<table width = '100%'>
				<tr>
					<td style = 'border:1px solid black;border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;text-align:center;'>
						<strong><p>COMPROMISOS CLIENTES</p></strong>
					</td>
				</tr>
				<tr>
					<td>
						<p style = 'text-align:justify;'>".utf8_decode($listad_compromisos_cliente)."</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>";

//INCLUYO EL HTML DENTRO DEL MAIL
$mail->msgHTML($ht);

$mail->AltBody = 'This is a plain-text message body';

//ARCHIVOS ADJUNTOS
if($_POST['num_archivos'] > 0){
	$ot = $_POST['ot'];
	$v = $_POST['arcx'];
	$xc = explode("<!!>",$v);
	for($t = 0;$t < count($xc);$t++){
		$mail->addAttachment("../Process/OT/$ot/IE/".$xc[$t],$xc[$t]);
	}
}

$vari = 0;
if (!$mail->send()) {
   $vari = 0;
   echo "Error al enviar el mensaje: " . $mail->ErrorInfo;
} else {
    $vari += 1;
}
