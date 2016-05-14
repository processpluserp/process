<?php
	$imprevistos = 0;
	$gastos_administrativos = 0;
	$valor_comision = 0;
	$tipo_comision_ppto = 0;
	$tabla_encabezado = "";
	$num_cliente = 0;
	$version_interna = "";
	$version_externa = "";
	$estado_ppto = "";
	
	$ppto_rechazado = "";
	$encab = mysql_query("Select e.nombre_comercial_empresa, c.nombre_legal_clientes,p.referencia, p.vigencia_inicial,p.vigencia_final,pr.nombre_producto,p.codigo_presup,p.numero_presupuesto,ot.codigo_ot,tp.nombre,unx.name,
	p.imprevistos,p.gastos_admin, cc.pago,cc.impuestos,cc.refuente,cc.reiva,cc.uaai,cc.tipo, p.vi,p.vc,p.estado_presup
	from cabpresup p, empresa e, clientes c, cabot ot, producto_clientes pr, tipo_cuenta_ppto tp, und unx, condiciones_cliente cc
	where e.cod_interno_empresa = p.empresa_nit_empresa	and c.codigo_interno_cliente = p.pk_clientes_nit_cliente and p.ot = ot.codigo_ot and ot.producto_clientes_codigo_PRC = pr.id_procliente and
	p.codigo_presup = '$num_ppto' and p.tipo = tp.consecutivo and p.ceco = unx.id and p.tipo_comision = cc.consecutivo");
	while($row = mysql_fetch_array($encab)){
		$imprevistos = $row['imprevistos'];
		$gastos_administrativos = $row['gastos_admin'];
		$valor_comision = $row['uaai'];
		$tipo_comision_ppto = $row['tipo'];
		$num_cliente = $row['numero_presupuesto'];
		$version_interna = $row['vi'];
		$version_externa = $row['vc'];
		$estado_ppto = $row['estado_presup'];
		
		
		if($estado_ppto == 100){
			$sql_mysql = mysql_query("select observaciones
			from estatus_aprobaciones
			where ppto = '".$row['codigo_presup']."' and vi = '".$row['vi']."' and vc = '".$row['vc']."' and estado_aprobacion = '0'");
			$mensaje = "";
			while($esttt = mysql_fetch_array($sql_mysql)){
				$mensaje = $esttt['observaciones'];
			}
			$ppto_rechazado = "<span class = 'hidde mensaje_rechazo'>$mensaje</span><span class = 'botton_verde' style = 'background-color:red;color:white;' onclick = 'alert_razon_bloqueo();'>";
		}else{
			$ppto_rechazado = "<span>";
		}
		$tabla_encabezado = "<tr class ='encabezado'>
				<td class = 'concepto '>
					EMPRESA
				</td>
				<td style = 'padding-left:5px;color:#707070;' class = 'nombre_empresa_ppto'>".$row['nombre_comercial_empresa']."</td>
				
				<td class = 'concepto'>
					CLIENTE 
				</td>
				<td style = 'padding-left:5px;color:#707070;' class = 'nombre_legal_cliente_ppto'>".$row['nombre_legal_clientes']."</td>
				
				<td class = 'concepto'>
					PRODUCTO
				</td>
				<td style = 'padding-left:5px;color:#707070;' class = 'ppto_cl'>".$row['nombre_producto']."</td>
				
				<td class = 'concepto'>
					OT
				</td>
				<td style = 'padding-left:5px;color:#707070;' class = 'ot_ppto_encabezado'>".$row['codigo_ot']."</td>
		</tr>
		<tr class ='encabezado'>
			<td class = 'concepto'>
				REFERENCIA
			</td>
			<td style = 'padding-left:5px;color:#707070;' class = 'referencia_ppto'>".$row['referencia']."</td>
			
			<td class = 'concepto'>
				VIGENCIA INICIAL
			</td>
			<td style = 'padding-left:5px;color:#707070;' >".$row['vigencia_inicial']."</td>
			
			<td class = 'concepto'>
				VIGENCIA FINAL
			</td>
			<td style = 'padding-left:5px;color:#707070;'>".$row['vigencia_final']."</td>
			
			<td class = 'concepto'>
				TIPO PPTO
			</td>
			<td style = 'padding-left:5px;color:#707070;'>".$row['nombre']."</td>
		</tr>
		<tr class ='encabezado'>
			<td class = 'concepto'>
				CENTRO DE COSTO
			</td>
			<td style = 'padding-left:5px;color:#707070;' class = 'ceco_ppto'>".$row['name']."</td>
			
			<td class = 'concepto'>
				ESTADO
			</td>
			<td style = 'padding-left:5px;color:#707070;'>$ppto_rechazado".$ppto->estados_ppto($estado_ppto)."</span></td>
		</tr>";
	}
	
	//ENCABEZADO CON NÚMERO DE PPTO Y VERSIONES CORRESPONDIENTES.
	$function_versiones_internas = "";
	$function_versiones_externas = "";
	if($estado_ppto == 1 || $estado_ppto == 2){
		$function_versiones_internas = "onclick = 'mostrar_versiones_ppto($num_ppto,$version_interna)'";
		$function_versiones_externas = "onclick = 'mostrar_versiones_cliente_ppto($num_ppto,$version_externa)'";
	}
	echo "
	<span class = 'hidde' id = 'info_ppto_text'>$num_ppto</span>
	<span class = 'hidde' id = 'info_vi_text'>$version_interna</span>
	<span class = 'hidde' id = 'info_vc_text'>$version_externa</span>
	<span class = 'hidde' id = 'info_vc_ext'>$num_cliente</span>
	<span class = 'hidde' id = 'estado_ppto'>$estado_ppto</span>
	<table width = '100%'>
		<tr>
			<td align = 'center' class = 'titulo_ppto'>
				<span class = 'botton_verde' >PPTO INT:</span> $num_ppto V. $version_interna <span class = 'botton_verde' >PPTO EXT:</span> $num_cliente 	V. $version_externa
			</td>
		</tr>
	</table>";
		
		
	//CONDICIONES PARA MOSTRAR BOTONES DE PPTO.
	
		//EVALÚO EL ESTADO DEL PPTO PARA PODER SABER SI SE PUEDEN GENERAR VERSIONES SOBRE ÉL O NO.
		$generador_versiones = "";
		if($estado_ppto == 1 || $estado_ppto == 2){
			$generador_versiones = "<td align = 'right'><table width = '100%'>
									<tr>
										<td align = 'center'>
											<img src = '../images/iconos/nueva_version.png' width = '45px' title = 'Generar Nueva versión' onclick = 'generar_nueva_version($num_ppto,$version_interna,$version_externa)'/>
										</td>
									</tr>
									<tr>
										<td align = 'center'>Nueva Versión</td>
									</tr>
								</table></td>";
		}
		
		
		//DE ACUERDO EL PERFIL MUESTRO EL LISTADO DE ORDENES DE COMPRA QUE HAY SOBRE EL PPTO.
		$listado_ordenes_compra = ""; 
		if(($_SESSION['perfil'] == 8 || $_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 9) && $estado_ppto > 4 && $estado_ppto != 100){
			$listado_ordenes_compra = "<td align = 'right'><table width = '100%'>
											<tr>
												<td align = 'center'>
													<img src = '../images/iconos/iconos-45.png' onclick = 'form_oc_ppto()' width = '45px' id = 'generar_cliente' title = 'Consultar Ordenes de Compra'/>
												</td>
											</tr>
											<tr>
												<td align = 'center'>Generar OC</td>
											</tr>
										</table></td>";
		}
		
		$listado_ordenes_produccion = ""; 
		if(($_SESSION['perfil'] == 8 || $_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 9) && $estado_ppto > 4 && $estado_ppto != 100){
			$listado_ordenes_produccion = "<td align = 'right'><table width = '100%'>
											<tr>
												<td align = 'center'>
													<img src = '../images/iconos/iconos-45.png' onclick = 'abrir_list_oc($num_ppto)' width = '45px'  title = 'Consultar Ordenes de Produccion'/>
												</td>
											</tr>
											<tr>
												<td align = 'center'>Consultar Histórico Ordenes</td>
											</tr>
										</table></td>";
		}
		
		//GENERAR ORDEN DE PRODUCCION
		$generar_op = ""; 
		if(($_SESSION['perfil'] == 8 || $_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 9) && $estado_ppto > 4 && $estado_ppto != 100){
			$generar_op = "<td align = 'right'><table width = '100%'>
							<tr>
								<td align = 'center'>
									<img src = '../images/iconos/iconos-45.png' onclick = 'form_nuevo_op()' width = '45px'  title = 'Generar Ordenes de Produccion'/>
								</td>
							</tr>
							<tr>
								<td align = 'center'>Generar OP</td>
							</tr>
						</table></td>";
		}
		
		//SI EL PPTO SE ENCUENTRA RECHAZADO
		$botton_ppto_rechazado = "";
		if($estado_ppto == 100){
			$botton_ppto_rechazado = "<td align = 'right'><table width = '100%'>
										<tr>
											<td align = 'center'>
												<img src = '../images/iconos/nueva_version.png' width = '45px' onclick = 'nueva_version_por_bloqueo($num_ppto,$version_interna,$version_externa);'/>
											</td>
										</tr>
										<tr>
											<td align = 'center'>Nueva Versión</td>
										</tr>
									</table></td>";
		}
		
		//GENERACIÓN DE ANTICIPOS
		$boton_anticipos = "";
		if($estado_ppto == 5 || $estado_ppto == 6){
			$boton_anticipos = "<td align = 'right'><table width = '100%'>
									<tr>
										<td align = 'center'>
											<img src = '../images/iconos/anticipo.png' width = '45px' title = 'Generar Anticipo' id = 'abrir_ant'/>
										</td>
									</tr>
									<tr>
										<td align = 'center'>Generar Anticipo</td>
									</tr>
								</table></td>";
		}
		
		
		//ENVIO DE PPTO A APROBACIÓN
		$boton_envio_aprobacion = "";
		if($estado_ppto == 1 || $estado_ppto == 2){
			$boton_envio_aprobacion = "<td align = 'right'><table width = '100%'>
										<tr>
											<td align = 'center'>
												<img src = '../images/iconos/email.png' width = '45px' title = 'Enviar a Aprobación' onclick = 'enviar_aprobacion_ppto($num_ppto,$version_interna,$version_externa,$num_cliente)' />
											</td>
										</tr>
										<tr>
											<td align = 'center'>Enviar a Aprobación</td>
										</tr>
									</table></td>";
		}
		
		//GUARDADO DE PPTO
		$boton_guardar_ppto = "";
		if($estado_ppto == 1 || $estado_ppto == 2 || $estado_ppto == 5 || $estado_ppto == 6){
			$boton_guardar_ppto = "<td align = 'right'><table width = '100%'>
									<tr>
										<td align = 'center'>
											<img src = '../images/iconos/ok_verde.png' width = '45px' title = 'Guardar' onclick = 'guardar_informacion_ppto($version_interna,$version_externa)'/>
										</td>
									</tr>
									<tr>
										<td align = 'center'>Guardar</td>
									</tr>
								</table></td>";
		}
		
		//IMPRIMIR PPTO INTERNO !!!!
		$boton_ppto_interno ="<td align = 'right'><table width = '100%'>
								<tr>
									<td align = 'center'>
										<img src = '../images/iconos/pdf_interno.png' width = '45px' title = 'Imprimir Ppto Consulta'  />
									</td>
								</tr>
								<tr>
									<td align = 'center'>Imprimir Ppto Consulta</td>
								</tr>
							</table></td>";
		
		
		
		//CONDICIONES Y PERFILES QUE IMPRIMEN UN PPTO EXTERNO
		$boton_imprimir_ppto = "";
		if(($_SESSION['perfil'] == 8 || $_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 9) && ($estado_ppto == 4 || $estado_ppto == 5)){
			$boton_imprimir_ppto = "<td align = 'right'><table width = '100%'>
										<tr>
											<td align = 'center'>
												<img src = '../images/iconos/iconos-42.png' width = '45px' title = 'Imprimir Ppto Cliente'  onclick = 'generar_pdf_externo($num_ppto,$version_interna,$version_externa)'/></a>
											</td>
										</tr>
										<tr>
											<td align = 'center'>Imprimir Ppto Cliente</td>
										</tr>
									</table></td>";
		}
		
		//BOTON DE REGRESAR AL MENÚ ANTERIOR
		$boton_regresar_menu = "";
		if($estado_ppto == 1 || $estado_ppto == 2 || $estado_ppto == 5 || $estado_ppto == 6 ||$estado_ppto == 4 ){
			
		}
		$boton_regresar_menu = "<td align = 'right'><table width = '100%'>
									<tr>
										<td align = 'center'>
											<a href = 'produccion.php'>
												<img src = '../images/iconos/icon-16.png' width = '45px' title = 'Volver' />
											</a>
										</td>
									</tr>
									<tr>
										<td align = 'center'>Volver</td>
									</tr>
								</table></td>";
		
		//RECARGAR PÁGINA PPTO PRODUCCION (Recarga tal cual la página)
		$boton_recargar_pagina = "";
		if($estado_ppto != 3 ){
			
		}
		$boton_recargar_pagina = "<td align = 'right'><table width = '100%'>
									<tr>
										<td align = 'center'>
											<img src = '../images/iconos/actualizar_ppto.png' width = '45px' title = 'Actulizar Página' onclick = 'location.reload();'/>
										</td>
									</tr>
									<tr>
										<td align = 'center'>Actualizar</td>
									</tr>
								</table></td>";
			
	//ENCABEZADO ICONOS PPTO
	echo "<table width = '100%'>
		<tr>
			$generador_versiones
			$botton_ppto_rechazado
			$generar_op
			$listado_ordenes_produccion
			$listado_ordenes_compra
			$boton_anticipos
			$boton_envio_aprobacion
			$boton_guardar_ppto
			$boton_imprimir_ppto
			$boton_ppto_interno
			$boton_regresar_menu
			$boton_recargar_pagina
		</tr>
	</table>
	<table width = '100%'>
			<tr>
				<td onclick = 'mostrar_cabecera();' class = 'mano' style = 'font-weight:bold;' colspan = '3'>
					ENCABEZADO
				</td>
			</tr>
			$tabla_encabezado
		</table>";
	
	
?>
