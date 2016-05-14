<?php

	include("../Controller/Conexion.php");
	include_once("../Modelo/gestion_cabecera.php");
	include_once("../Modelo/Empresa.php");
	session_start();
	
	$gestion = new cabecera_pagina();
	
	$emp = new Empresa();
	
	$ppto = $_POST['ppto'];
	$vi = $_POST['vi'];
	$vc = $_POST['vc'];
	$tipo = $_POST['tipo'];
	$item = "";
	$est_anticipo = "<table width = '100%' style = 'border-collapse:collapse;'>
						<tr>
							<th></th>
							<th>Grupo</th>
							<th>Nombre Item</th>
							<th>Dias</th>
							<th>Cantidad</th>
							<th>$ Unitario</th>
							<th>% Ant</th>
							<th>$$$</th>
						</tr>";
	
	
	if($tipo == 1){
		$estructura_historico = "<table width = '100%' style = 'border-collapse:collapse;' class = 'tablas_muestra_datos_tablas_trafico'>
								<tr>
									<th></th>
									<th>Fecha Solicitud</th>
									<th>Solicitado Por</th>
									<th>Revisado por</th>
									<th>Estado</th>
									<th>Fecha Aprobación</th>
								</tr>";
		$item = $_POST['item'];
		
		$sql_histo_anticipos = mysql_query("select e.nombre_empleado,estant.fecha_estado, cant.fecha, e2.nombre_empleado as empleado2,estant.estado
		from cuerpo_anticipo cp, anticipos_ppto cant, estatus_anticipos estant, usuario u, empleado e, usuario u2, empleado e2
		where cp.pk_item = '$item[0]' and cp.pk_anticipo = cant.id and cant.id = estant.pk_anticipo
		and estant.useraprobado = u.idusuario and u.pk_empleado = e.documento_empleado and
		cant.user = u2.idusuario and u2.pk_empleado = e2.documento_empleado");
		$ii = 1;
		while($row = mysql_fetch_array($sql_histo_anticipos)){
			$estado = "";
			if($row['estado'] == 1){
				$estado = "APROBADO";
			}else{
				$estado = "NO APROBADO";
			}
			$estructura_historico.="<tr>
				<td>$ii</td>
				<td>".$row['fecha']."</td>
				<td>".utf8_decode($row['empleado2'])."</td>
				<td>".utf8_decode($row['nombre_empleado'])."</td>
				<td>".utf8_decode($row['empleado2'])."</td>
				<td>".$estado."</td>
				<td>".$row['fecha_estado']."</td>
			</tr>";
			$ii++;
		}
		$estructura_historico.="</table>";
		
		$sql_item = mysql_query("select dias,q,name_item,name_grupo,asoc,val_item,iva_item
		from itempresup where id = '$item[0]'");
		while($row = mysql_fetch_array($sql_item)){
			$por_anticipos_solicitados = 0;
			$sql_ant = mysql_query("select ca.porcentaje
			from anticipos_ppto ap, cuerpo_anticipo ca
			where ap.ppto = '$ppto' and ap.vi = '$vi' and ap.vc = '$vc' and ap.id = ca.pk_anticipo 
			and ca.pk_item = '$item[0]'");
			while($xrow = mysql_fetch_array($sql_ant)){
				$por_anticipos_solicitados+=$xrow['porcentaje'];
			}
			$libre = 100-$por_anticipos_solicitados;
			$maxlength = 0;
			if($libre == 100){
				$maxlength = 2;
			}else{
				$maxlength = 1;
			}
			$est_anticipo.="<tr>
				<td style = 'border:1px solid black;'>
					<div>
						<input type = 'checkbox' checked id = 'item_anticipo$item[0]' name = 'item_anticipo[]' value = '$item[0]' class = 'radio'/>
						<label for='item_anticipo$item[0]'><span><span></span></span></label>
					</div>
				</td>
				<td style = 'border:1px solid black;'>".$row['name_grupo']."</td>
				<td style = 'border:1px solid black;'>".$row['name_item']."</td>
				<td style = 'border:1px solid black;'>".$row['dias']."<span class = 'hidde' id = 'ant_dias$item[0]'>".$row['dias']."</span></td>
				<td style = 'border:1px solid black;'>".$row['q']."<span class = 'hidde' id = 'ant_q$item[0]'>".$row['q']."</span></td>
				<td style = 'border:1px solid black;'>".number_format($row['val_item'])."<span class = 'hidde' id = 'ant_val_unitario$item[0]'>".$row['val_item']."</span></td>
				<td style = 'border:1px solid black;'>
					<input type = 'number' min = '1' max = '$libre' value = '1' id = 'por_ant$item[0]'maxlength = '$maxlength'  onkeyup = 'validar_porcentaje_libre_item_anticipo($item[0])' onchange = 'validar_porcentaje_libre_item_anticipo($item[0])' />
				</td>
				<td style = 'border:1px solid black;'>
					<span id = 'total_solicitado$item[0]'></span>
				</td>
			</tr>";
		}
		$est_anticipo."</table>";
	}else{
		
	}
	
	
	$estructura = "
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
								<span class = 'mensaje_bienvenida' >ANTICIPOS</span>
							</td>
						</tr>
					</table>
				</td>
				<td align = 'right' >
					<table width = '100%'>
						<tr>
							<td align = 'center'>
								<img onclick = 'cerrar_ventana_generalx();' src = '../images/iconos/icon-18.png' class = 'iconos_opciones mano'/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
	</table>
	<table width = '100%' style = 'padding-left:50px;padding-right:50px;border-collapse:collapse;'>
		<tr>
			<th align = 'left' >HISTÓRICO</th>
			<td class = 'separator'></td>
			<th align = 'left' >NUEVO ANTICIPO</th>
		</tr>
		<tr>
			<td class = 'historicos' width = '49%' style = 'background-color:white;vertical-align:top;'>
				$estructura_historico
			</td>
			<td class = 'separator'></td>
			<td class = 'form_anticipos' width = '49%' style = 'vertical-align:top;'>
				<table width = '100%' style = 'padding-left:10px;padding-right:10px;' >
					<tr>
						<td >
							<p>Seleccione la Fecha del anticipo:</p>
							<input type = 'text' class = 'fechas_ant entradas_bordes' id = 'fecha_anticipo' onchange = 'sumar_fechas_php();' />
						</td>
						<td class = 'separator'></td>
						<td>
							<p>Fecha de Legalización:</p>
							<input type = 'text' readonly id = 'fecha_max_legal_anticipo'/>
						</td>
					</tr>
					<tr><td></br></td></tr>
					<tr>
						<td colspan = '3'>
							<p>Ingrese los porcentajes correspondiente de anticipos:</p>
							$est_anticipo
						</td>
					</tr>
					<tr><td></br></td></tr>
					<tr>
						<td colspan = '3' align = 'center'>
							<span class = 'botton_verde' onclick = 'generar_anticipo_ppto()'>GENERAR ANTICIPO</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<script type = 'text/javascript'>
		function nonWorkingDates(date){
			var day = date.getDay(), Sunday = 0, Monday = 1, Tuesday = 2, Wednesday = 3, Thursday = 4, Friday = 5, Saturday = 6;
			var closedDays = [[Saturday], [Sunday]];
			return [true];
		}
		$('.fechas_ant').datepicker({ dateFormat: 'yy-mm-dd',beforeShowDay: nonWorkingDates,	numberOfMonths: 1,	minDate: '0',firstDay: 1  });
	</script>
	";
	echo $estructura;
?>