<?php
	include("../Controller/Conexion.php");
	include_once("../Modelo/gestion_cabecera.php");
	include_once("../Modelo/Empresa.php");
	session_start();
	$gestion = new cabecera_pagina();
	
	$emp = new Empresa();
	
	$user = $_POST['user'];
	
	//SQL PPTOS PENDIENTES DE APROBACION
	$sql_pptos_pendientes_apro = mysql_query("select histo.id,histo.ppto,histo.porcentaje, histo.up_bottom, histo.vi, histo.vc, p.referencia, u.nick, 
	e.nombre_empleado, histo.fecha, p.numero_presupuesto
	from apropresup_histo histo, pendientes_aprobacion  pen, usuario u, empleado e, cabpresup p
	where pen.estado = '1' and pen.user = '$user' and  histo.ppto = p.codigo_presup and histo.user = u.idusuario and u.pk_empleado = e.documento_empleado 
	and histo.id = pen.pk_id ");
	
	$table = "<table width = '100%' class = 'tablas_muestra_datos_tablas_trafico'>
		<tr>
			<th></th>
			<th># PPTO INT. - V</th>
			<th># PPTO EXT. - V</th>
			<th>REFERENCIA</th>
			<th>ENVIADO POR</th>
			<th>FECHA</th>
		</tr>
	";
	while($row = mysql_fetch_array($sql_pptos_pendientes_apro)){
		$id_x = $row['id'];
		$table.="<tr>
			<td>
				<input type = 'radio'  name = 'select_ppto_x_apro' id = 'ppto_t".$row['id']."' value = '".$row['id']."'  class = 'radio mano' onclick = 'abrir_visual_ppto($id_x)'/>
				<label for='tareaa".$row['id']."' ><span ><span ></span></span></label>
			</td>
			<td>".$row['ppto']." - ".$row['vi']."</td>
			<td>".$row['numero_presupuesto']." - ".$row['vc']."</td>
			<td>".html_entity_decode($row['referencia'])."</td>
			<td>".$row['nombre_empleado']."</td>
			<td>".$row['fecha']."</td>
		</tr>";
	}
	$table.="</table>";
	
	$perfil = $_POST['perfil'];
	$pestana = "";
	$div_pestana = "";
	
	/*
		PERFILES:
		1 - > ADMIN FINANCIERO
		7 - > CONTABILIDAD
		9 - > ADMINISTRADOR
		12 ->  FACTURACIÓN
		13 -> TESORERÍA
	*/
	if($perfil == 1 || $perfil == 7 || $perfil == 9 || $perfil == 12 || $perfil == 13){
		$pestana = "<li class = 'pestanas_menu' onclick = 'listar_desembolsos()'><a href='#tabs-5'>Desembolsos</a></li>";
		$div_pestana = "<div id='tabs-5' style = 'padding-left:50px;'></div>";
	}
	$est = "
		<table width = '100%' style = 'padding-left:50px;padding-right:50px;'>
			<tr>
				<td width = '96%' align = 'left'>
					<table width = '100%'>
						<tr>
							<td align = 'left'>
								".$emp->mostrar_logo_empresa($gestion->mostrar_empresa_empleado())."
							</td>
						</tr>
						<tr>
							<td align = 'left' >
								<span class = 'mensaje_bienvenida' >APROBACIONES</span>
							</td>
						</tr>
					</table>
				</td>
				<td align = 'right' >
					<table width = '100%'>
						<tr>
							<td align = 'center'>
								<img onclick = 'cerrar_modulo_aprobaciones()' src = '../images/iconos/icon-18.png' class = 'iconos_opciones mano' />
							</td>
						</tr>
					</table>
				</td>
			</tr>
	</table>
	<div id='tabs_aprobaciones' >
		<ul style = 'padding-left:50px;'>
			<li class = 'pestanas_menu' id = 'submod_presupuestos'><a href='#tabs-1'>Presupuestos</a></li>
			<li class = 'pestanas_menu' id = 'submod_anticipos' onclick = 'actualizar_listado_anticipos_pendientes();'><a href='#tabs-2'>Anticipos</a></li>
			<li class = 'pestanas_menu' id = 'submod_histo_ppto' onclick = 'cargar_historico_aprobaciones_ppto()'><a href='#tabs-3'>Historico Aprobaciones Pptos</a></li>
			<li class = 'pestanas_menu' id = 'submod_histo_ant'><a href='#tabs-4'>Historico Aprobaciones Anticipos</a></li>
			$pestana
		</ul>
		<div id='tabs-1' style = 'padding-left:50px;'>
			$table
		</div>
		<div id='tabs-2' style = 'padding-left:50px;' class = 'pendientes_anticipos' >
			
		</div>
		<div id='tabs-3' style = 'padding-left:50px;' class = 'histo_ppto_aprobaciones'>
		
		</div>
		<div id='tabs-4' style = 'padding-left:50px;'>
		
		</div>
		$div_pestana
	</div>
	<script type = 'text/javascript'>
		$('#tabs_aprobaciones').tabs();
	</script>";
	echo $est;
?>