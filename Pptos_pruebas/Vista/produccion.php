<?php
	include("../Controller/Conexion.php");
	require("../Modelo/gestion_cabecera.php");
	require("../Modelo/Empresa.php");
	session_start();
	if($_SESSION["codigo_usuario"] == ""){
		header("location:../logeo.php");
	}
	$usuario_actual = $_SESSION["codigo_usuario"];
	$nombre_usuario = $_SESSION["nombre_usuario"];
	$gestion = new cabecera_pagina();
	$emp = new empresa();
	
	$codigo_usuario_real = $_SESSION["codigo_usuario"];
	$empresa_final = $gestion->mostrar_empresa_empleado();
?>
<!DOCTYPE html>
	<html lang="es">
		<head>
			<title>:: PROCESS + ::</title>
			<meta charset="utf-8" />
			<link type="text/css" href="../css/smoothness/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
			
			<script type="text/javascript" src="../js/jquery1_10_2.js"></script>
			<script type="text/javascript" src="../css_jquery/css_logeo.js"></script>
			<!--<link type="text/css" href="../css/barra_navegacion2.css" rel="stylesheet" />-->
			
			<script type="text/javascript" src="../js/produccion.js"></script>
			<script type="text/javascript" src="../js/resize.js"></script>
			<script type="text/javascript" src="../js/ocultar.js"></script>
			<link rel="stylesheet" href="../css/jquery-ui.css">
			<style >
				.estilos_barra td:nth-child(4){
					background-color:#EF8C14;
				}
			</style>
			<link type="text/css" href="../css/tablas.css" rel="stylesheet" />
			<link type="text/css" href="../css/cabecera.css" rel="stylesheet" />
			<link type="text/css" href="../css/produccion.css" rel="stylesheet" />

			
			<script type="text/javascript" src="../js/jquery_ui/jquery-ui.js"></script>
			<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
			
		</head>
		<body class = 'scroll'>
			<?php include('cabecera.php'); echo $imprimir;?>
			
			
			<span id = "codigo_usuario" class = 'hidde'><?php echo $_SESSION["codigo_usuario"]; ?></span>
			<div id="spinner" class="spinner" style="display:none;">
				<img id="img-spinner" src="../images/spinner.gif" alt="Cargando..."/>
			</div>
			
			
			<div id = "formato_nuevo_ppto" title="Create new user">
				<table width = '100%' style = 'padding-left:50px;padding-right:50px;'>
					<tr>
						<td width = '96%' align = 'left'>
							<table width = '100%' >
								<tr>
									<td align = 'left'>
										<?php echo $emp->mostrar_logo_empresa($gestion->mostrar_empresa_empleado()); ?>
									</td>
								</tr>
								<tr>
									<td align = 'left' >
										<span class = 'mensaje_bienvenida'>CREAR PPTO</span>
									</td>
								</tr>
							</table>
						</td>
						<td align = 'right' >
							<img id = 'cerrar_ventana_crear_ppto' src = '../images/iconos/icon-19.png' class = 'iconos_opciones mano' title = 'Cerrar Ventana'/>
						</td>
					</tr>
				</table>
				<div class ="scroll_nueva_ventana2">
					<table class = "tabla_nuevos_datos2" width = '100%' style = 'padding-left:50px;padding-right:50px;'>
						<tr>
							<td width = '49%'>
								<p>Seleccione una Empresa:</p>
								<select class = "entradas_bordes" id = "grupo_empresas" name = "grupo_empresas">
									<option value = "...">...</option>
								<?php
									$usu = $_SESSION["codigo_usuario"];
									$select_emp = "select distinct e.cod_interno_empresa, e.nombre_comercial_empresa from empresa e, pusuemp p where 
									p.cod_usuario = '$usu' and p.cod_empresa = e.cod_interno_empresa order by e.nombre_comercial_empresa asc";
									$result = mysql_query($select_emp);
									while($row = mysql_fetch_array($result)){
										echo "<option value ='".$row['cod_interno_empresa']."'>".$row['nombre_comercial_empresa']."</option>";
									}
								?>
								</select>
							</td>
							<td class = 'separator' width = '2%'></td>
							<td width = '49%'>
								<p>Seleccione un Cliente:</p>
								<select class = "entradas_bordes" id ="cliente" name = "cliente"></select>
							</td>
						</tr>
						<tr>
							<td width = '49%'>
								<p>Seleccione una OT:</p>
								<select id = "ot" name = "ot"></select>
							</td>
							<td class = 'separator' width = '2%'></td>
							<td width = '49%'>
								<p>Seleccione un Centro de Costo:</p>
								<select id = 'c_costo_fn' name = 'c_costo_fn'>
									<option>...</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<p>Ingrese la Referencia del Ppto:</p>
								<textarea class = "entradas_bordes" rows = "5" cols = "60" id = "referencia">
								</textarea>
							</td>
							<td class = 'separator' width = '2%'></td>
							<td>
								<p>Seleccione la Vigencia Inicial:</p>
								<input type = "text" name = "v_inicial" id = "v_inicial" />
							</td>
						</tr>
						<tr>
							<td>
								<p>Seleccione la Vigencia Final:</p>
								<input type = "text" name = "v_final" id = "v_final" />
							</td>
							<td class = 'separator' width = '2%'></td>
							<td>
								<p>Ingrese la nota del Ppto:</p>
								<textarea id = "nota_ppto" class = "entradas_bordes" rows = "5" cols = "60">
								</textarea>
							</td>
						</tr>
						<tr>
							<td id = "colpatria">
								<p>Número de Aprobación</p>
								<input type = "text" name = "n_aprobacion" id = "n_aprobacion" />
							</td>
							<td class = 'separator' width = '2%'></td>
							<td >
								<p>Seleccione una Ciudad:</p>
								<select id = "ciudad" name = "ciudad">
									<option>...</option>
									<?php
										$select = "select c.codigo_ciudad, c.nombre_ciudad from ciudad c where
										c.departamento_pais_codigo_pais = 1 order by c.nombre_ciudad";
										$r = mysql_query($select);
										while($row = mysql_fetch_array($r)){
											echo "<option value = '".$row['codigo_ciudad']."'>".$row['nombre_ciudad']."</option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<p>Seleccione el Tipo de Ppto:</p>
								<select id = "tipo_ppto" name = "tipo_ppto">
									<option>...</option>
									<?php
										$select = "select consecutivo, nombre from tipo_cuenta_ppto where estado = 1";
										$r = mysql_query($select);
										while($row = mysql_fetch_array($r)){
											echo "<option value = '".$row['consecutivo']."'>".utf8_encode($row['nombre'])."</option>";
										}
									?>
								</select>
							</td>
							<td class = 'separator' width = '2%'></td>
							<td>
								<p>Seleccione el Tipo Comision:</p>
								<select id = "tipo_comision">
									<option value = "..."></option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan = "3" align = 'center'>
								<img src = '../images/iconos/guardar_2.png' class = 'mano iconos_guardar' id = "cancelar" style = 'position:relative;'>
								<img src = '../images/iconos/guardar_1.png' class = 'iconos_guardar_x'   style = 'position:relative;top:45px;left:-50px;z-index:1;'>
								<img src = '../images/iconos/guardar_3.png' class = 'mano iconos_guardar' id = "crear_ppto"  style = 'position:relative;left:-110px;'>
							</td>
						</tr>
					</table>
				</div>
			</div>
			
			<!--FORMULARIO PARA CARGAR UN PPTO -->
			<div id ="fomr_carga_ppto" class = 'ventana'>
				<table width = '100%' style = 'padding-left:50px;padding-right:50px;'>
					<tr>
						<td width = '96%' align = 'left'>
							<table width = '100%' >
								<tr>
									<td align = 'left'>
										<?php echo $emp->mostrar_logo_empresa($gestion->mostrar_empresa_empleado()); ?>
									</td>
								</tr>
								<tr>
									<td align = 'left' >
										<span class = 'mensaje_bienvenida'>BUSCAR PPTO</span>
									</td>
								</tr>
							</table>
						</td>
						<td align = 'right'>
							<img onclick = '$("#fomr_carga_ppto").dialog("close");$("#fomr_carga_ppto select").val("0");$(".scroll").css({"overflow-y":"scroll"});'src = '../images/iconos/icon-19.png' class = 'iconos_opciones mano' title = 'Cerrar Ventana'/>
						</td>
					</tr>
				</table>
				<table class = "barra_busqueda2" style = 'padding-left:50px;padding-right:50px;' width = '100%'>
					<tr>
						<td>
							<p>Seleccione una Empresa:</p>
							<select id = "empresa_carga">
								<option value = "0">[SELECCIONE]</option>
								<?php
									$usu = $_SESSION["codigo_usuario"];
									$select_emp = "select distinct e.cod_interno_empresa, e.nombre_comercial_empresa from empresa e, pusuemp p where 
									p.cod_usuario = '$usu' and p.cod_empresa = e.cod_interno_empresa";
									$result = mysql_query($select_emp);
									while($row = mysql_fetch_array($result)){
										echo "<option value ='".$row['cod_interno_empresa']."'>".$row['nombre_comercial_empresa']."</option>";
									}
								?>
							</select>
						</td>
						<td style = 'padding-left:20px;'>
							<p>Seleccione un Cliente:</p>
							<select id = "cliente_carga"></select>
						</td>
						<td style = 'padding-left:20px;'>
							<p>OT</p>
							<select id = "ot_carga"></select>
						</td>
						<td style = 'vertical-align:bottom;' align = 'right'>
							<a href = "#"id = "bus" onclick = "buscar_ppto_sel_ot()">
								<img src = '../images/iconos/lupa_naranja.png' class = 'botones_opciones mano' title = 'Buscar OTs' />
							</a>
						</td>
					</tr>
					<tr>
						<td></br></td>
					</tr>
					<tr>
						<td colspan = '4' style = ''>
							<div id = "pptos_realizados" style = 'overflow:scroll;background-color:rgb(221, 221, 221);border-radius:0.3em;-webkit-border-radius:0.3em;-moz-border-radius:0.3em;'></div>
						</td>
					</tr>
				</table>
				
			</div>
			
			<div id = "cuerpo_pagina">
				<table width = '100%'>
					<tr>
						<td align = 'right'>
							<table width = '100%'>
								<tr>
									<td align = 'right'>
										<table >
											<tr>
												<td>Regresar</td>
												<td>
													<?php
														echo "<a class = 'links_barra_ubicacion' href = 'bienvenida.php'>
															<img src = '../images/iconos/icon-16.png' width = '45px' height = '45px'/>
														</a>";
													?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<?php
					include('empresa_defect.php');
					$gestion->menu_produccion_perfil($_SESSION["codigo_usuario"]);
				?>


				<div  id = 'reportes_produccion' class = 'ventana'>
					<table width= '100%' style = 'padding-left:50px;padding-right:50px;'>
						<tr>
							<td width = '96%'>
								<table width = '100%' >
								<tr>
									<td align = 'left'>
										<?php echo $emp->mostrar_logo_empresa($gestion->mostrar_empresa_empleado()); ?>
									</td>
								</tr>
								<tr>
									<td align = 'left' >
										<span class = 'mensaje_bienvenida'>REPORTES PRODUCCIÓN</span>
									</td>
								</tr>
							</table>
							</td>
							<td align = 'right'>
								<img src = '../images/iconos/icon-19.png' onclick = "$('#reportes_produccion').dialog('close');$('.scroll').css({'overflow-y':'scroll'});" class = 'iconos_opciones mano' />
							</td>
						</tr>
					</table>
					<div style = 'overflow:scroll;width:100%;height:70%;'>
					<table width = '100%' style = 'padding-left:50px;padding-right:50px;'>
						<tr>
							<td width = '100%'>
								<table width = '100%' >
									<tr >
										<th align = 'left' >
											<img src = '../images/iconos/barras-52.png' class = 'img_menu_desplieg mano' id = 'consultar_ppto' onclick = 'resaltar_imagen_seleccionada("consultar_ppto");mostrar_barra_reporte("hijos_reporte_ot");'/>
										</th>
										<th align = 'left' >
											<img src = '../images/iconos/barras-53.png' onclick = 'ocultar_submenus_nomina_cuadros("img_nomina_cuadro_f");mostrar_barra_reporte("hijos_reporte_tareas");' id = 'img_nomina_cuadro_f' class = 'img_menu_desplieg mano' />
										</th>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width = '100%'>
								<div   style = 'background-color:#dadada;vertical-align:middle;width:100%;'>
									<table width = '100%' style = 'display:none;' class = 'hijos_reporte_ot' >
										<tr >
											<td>
												<table width = '100%' class = 'barra_busqueda2' style = ''>
													<tr>
														<td style = 'padding-left:20px;'>
															<p>Empresa:</p>
															<select class = "entradas_bordes" id = "empresa_rot"  >
																<option value = 0>Todas las empresas</option>
																<?php
																	$consulta = "SELECT e.nombre_comercial_empresa, e.cod_interno_empresa from empresa e, pusuemp p
																	where e.cod_interno_empresa = p.cod_empresa and p.cod_usuario = '$usuario_actual'";
																	$result = mysql_query($consulta);
																	while($row = mysql_fetch_array($result)){
																		echo "<option value=".$row['cod_interno_empresa'].">".utf8_encode($row['nombre_comercial_empresa'])."</option>";
																	}
																?>
															</select>
														</td>
														<td style = 'padding-left:20px;'>
															<p>Cliente:</p>
															<select class = "entradas_bordes" id = "cliente_rot" style = 'min-width:200px;'>
															</select>
														</td>
														<td style = 'padding-left:20px;'>
															<p>Desde</p>
															<input type = 'text'  id = 'fdesde_ot' class = "entradas_bordes" value = "<?php echo date('Y-m-d'); ?>"/>
														</td>
														<td style = 'padding-left:20px;'>
															<p>Hasta</p>
															<input type = 'text'  id = 'fhasta_ot' class = "entradas_bordes" value = "<?php echo date('Y-m-d'); ?>"/>
														</td>
														<td style = 'padding-left:20px;vertical-align:center;'>
															<img id = "generar_reporte_ot" src = "../images/iconos/icon-20.png" class = 'mano botones_opciones'/>
														</td>
														<td style = 'padding-left:20px;vertical-align:center;'>
															<a href = "download_reporte_ot.php" id = 'link_bajar_excel_ots'><img title = 'Exportar a Excel'src = "../images/iconos/icon-27.png" class = 'botones_opciones mano'/></a>
														</td>
														<!--<td style = 'padding-left:20px;'>
															<a target = '_blank'><img src = "../images/iconos/pdf.png" title = 'Exportar a PDF'class = 'mano'width = '50px' height = '50px'/></a>
														</td>-->
													</tr>
													<tr class = 'hijos_reporte_ot' style = 'display:none;padding-right:50px;'>
														<td colspan = '6' >
															<table width = '100%'>
																<tr></tr>
																<tr>
																	<td width = '49%' style = 'padding-left:20px;'>
																		<div id = 'chart_div' class = 'contenedor_scroll_reportes' style = 'overflow-y:hidden;overflow:hidden;background-color:rgb(232, 232, 232);border-radius:0.3em;-moz-border-radius:0.3em;-webkit-border-radius:0.3em;'></div>
																	</td>
																	<td class = 'separator'></td>
																	<td width = '49%'>
																		<div id = "canvas_rots" class = 'contenedor_scroll_reportes' style = 'overflow:hidden;background-color:rgb(232, 232, 232);border-radius:0.3em;-moz-border-radius:0.3em;-webkit-border-radius:0.3em;'></div>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<table width = '100%' class = 'hijos_reporte_tareas' style = 'display:none;padding-left:20px;padding-right:50px;'>
										<tr  >
											<td>
												<table width = '100%' class = 'barra_busqueda2'>
													<tr>
														<td >
															<p>Empresa:</p>
															<select class = "entradas_bordes" id = "empresa_rtareas"  >
																<option value = 0>Todas las empresas</option>
																<?php
																	$consulta = "SELECT e.nombre_comercial_empresa, e.cod_interno_empresa from empresa e, pusuemp p
																	where e.cod_interno_empresa = p.cod_empresa and p.cod_usuario = '$usuario_actual'";
																	$result = mysql_query($consulta);
																	while($row = mysql_fetch_array($result)){
																		echo "<option value=".$row['cod_interno_empresa'].">".utf8_encode($row['nombre_comercial_empresa'])."</option>";
																	}
																?>
															</select>
														</td>
														<td>
															<p>Cliente:</p>
															<select class = "entradas_bordes" id = "cliente_rtareas" >
															</select>
														</td>
														<td>
															<p>Departamento:</p>
															<select class = "entradas_bordes" id = "depto_tareas"></select>
														</td>
														<td>
															<p>Tareas:</p>
															<select class = "entradas_bordes" id = "tareas_tareas">
																<option value = "2">TODAS</option>
																<option value = "1">CONTESTADAS</option>
																<option value = "0">PENDIENTES</option>
															</select>
														</td>
														<td>
															<p>Desde:</p>
															<?php echo "<input style = 'width:110px;' type = 'text' class = 'entradas_bordes' name = 'fdesde_tareas' value = '".date('Y-m-d')."' id = 'fdesde_tareas' />"?>
														</td>
														<td>
															<p>Hasta:</p>
															<?php echo "<input style = 'width:110px;' type = 'text' name = 'fhasta_tareas' value = '".date('Y-m-d')."' id = 'fhasta_tareas' />"?>
														</td>
														<td>
															<img id = "generar_reporte_tareas" src = "../images/iconos/icon-20.png" class = 'mano botones_opciones'/>
														</td>
														<td >
															<a href = "download_reporte_tareas.php"><img src = "../images/iconos/icon-27.png" class = 'mano botones_opciones'/></a>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr >
											<td width = '100%'>
												<div id = "contenedor_rrporte_tareas" class = 'contenedor_scroll_reportes' style = 'overflow:scrollheight:300px;width:100%;padding-right:50px;border-radius:0.3em;-moz-border-radius:0.3em;-webkit-border-radius:0.3em;'></div>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>

				</div>

				<div id = 'v_recepcion_fact' class = 'ventana' style = 'padding-right:50px;background-color:white;border-radius:0.5em;-moz-border-radius:0.5em;-webkit-border-radius:0.5em;'>
					<table width= '100%' style = 'padding-left:50px;padding-right:50px;'>
						<tr>
							<td width = '96%'>
								<table width = '100%' >
								<tr>
									<td align = 'left'>
										<?php echo  $emp->mostrar_logo_empresa($gestion->mostrar_empresa_empleado()); ?>
									</td>
								</tr>
								<tr>
									<td align = 'left' >
										<span class = 'mensaje_bienvenida'>FACTURACIÓN</span>
									</td>
								</tr>
							</table>
							</td>
							<td align = 'right'>
								<img src = '../images/iconos/icon-19.png' onclick = "$('#v_recepcion_fact').dialog('close');$('.scroll').css({'overflow-y':'scroll'});" class = 'iconos_opciones mano' />
							</td>
						</tr>
					</table>
					</br>
					<div style = 'overflow:scroll;width:100%;height:70%;'>
						<table width = '100%'>
							<tr>
								<td width = '40%' height='100%'>
									<table width = '100%' id = 'panel_opciones' >
										<tr >
											<th align = 'left' style = 'vertical-align:top;' id = 'izquierda_panel_cf' class = 'mano'>
												<img src = '../images/iconos/barras-48.png' class = 'img_menu_desplieg'  id = 'recepcion_fact_img' onclick = 'resaltar_imagen_seleccionada("recepcion_fact_img");$(".todo_fact").hide();$(".hijos_recepcion_facturacion").toggle();'/>
											</th>
										</tr>
										<tr >
											<th align = 'left' class = 'mano'>
												<img src = '../images/iconos/barras-49.png' onclick = 'resaltar_imagen_seleccionada("img_nomina_cuadro_fxx");$(".todo_fact").hide();$(".hijos_facturacion_pptos").toggle();' id = 'img_nomina_cuadro_fxx' class = 'img_menu_desplieg' />
											</th>
										</tr>
										<tr >
											<th align = 'left' class = 'mano'>
												<img src = '../images/iconos/barras-50.png' onclick = 'resaltar_imagen_seleccionada("img_nomina_cuadro_fx");$(".todo_fact").hide();$(".hijos_tesoreria_cliente").toggle();' id = 'img_nomina_cuadro_fx' class = 'img_menu_desplieg' />
											</th>
										</tr>
										
										<tr>
											<th align = 'left'  style = 'vertical-align:bottom;' class = 'mano'>
												<img src = '../images/iconos/barras-51.png' onclick = 'ocultar_submenus_ppto_cuadros("img_sub_menu_pptos_cuadros");$(".todo_fact").hide();$(".hijos_pagos_proveedores_pagos").toggle();'  class = 'img_menu_desplieg' id = 'img_sub_menu_pptos_cuadros' />
											</th>
										</tr>
									</table>
								</td>
								<td width = '100%'>
									<div  id = 'contenedor_opciones' style = 'background-color:#dadada;vertical-align:middle;width:90%;'>
										<table width = '100%' class = 'hijos_recepcion_facturacion todo_fact' style = 'display:none;'>
											<tr >
												<td width = '100%' style = 'padding-left:20px;'>
													<table class = 'tabla_nuevos_datos'>
														<tr>
															<td style = 'padding-left:15px;'>
																<p>Ingrese el Número de OC:</p>
																<input type = 'text' id = 'num_orden_b_rf' class = 'entradas_bordes'/>
															</td>
															<td style = 'padding-left:20px;vertical-align:bottom;'>
																<img src = '../images/iconos/lupa_naranja.png' class = 'botones_opciones mano' id = 'buscar_orden'/>
															</td>
														</tr>
													</table>
													</br>
													<div id = 'contenedor_result_facturas'style = 'min-height:300px;border-radius:0.3em;-moz-border-radius:0.3em;-webkit-border-radius:0.3em;'>								
													</div>
												</td>
											</tr>
										</table>
										<table width = '100%' class = 'hijos_facturacion_pptos todo_fact' style = 'display:none;'>
											<tr >
												<td width = '100%' >
													<div width = '100%' style = 'border-radius:0.3em;-moz-border-radius:0.3em;-webkit-border-radius:0.3em;'>
														<table width = '100%' class = 'tabla_nuevos_datos2' style = 'padding-left:10%;padding-right:10%;'>
															<tr>
																<td width = '49%'>
																	<p>Seleccione una Empresa:</p>
																	<select class = "entradas_bordes" id = "empresa_b_facturacion" name = 'empresa_b_facturacion'  >
																		<option value = 0>Todas las empresas</option>
																		<?php
																			$consulta = "SELECT e.nombre_comercial_empresa, e.cod_interno_empresa from empresa e, pusuemp p
																			where e.cod_interno_empresa = p.cod_empresa and p.cod_usuario = '$usuario_actual'";
																			$result = mysql_query($consulta);
																			while($row = mysql_fetch_array($result)){
																				echo "<option value=".$row['cod_interno_empresa'].">".utf8_encode($row['nombre_comercial_empresa'])."</option>";
																			}
																		?>
																	</select>
																</td>
																<td class = 'separator' width = '2%'></td>
																<td width = '49%'>
																	<p>Seleccione un Cliente:</p>
																	<select id = 'cliente_b_facturacion' name = 'cliente_b_facturacion'>
																		<option value = '0'>[SELECCIONE]</option>
																	</select>
																</td>
															</tr>
															<tr>
																<td>
																	<p>Seleccione un Producto:</p>
																	<select id = 'producto_cliente_b_facturacion' name = 'producto_cliente_b_facturacion'>
																		<option value = '0'>[SELECCIONE]</option>
																	</select>
																</td>
																<td class = 'separator' width = '2%'></td>
																<td>
																	<p>Seleccione una OT:</p>
																	<select id = 'ot_producto_cliente_b_facturacion' name = 'ot_producto_cliente_b_facturacion'>
																		<option value = '0'>[SELECCIONE]</option>
																	</select>
																</td>
															</tr>
															<tr>
																<td>
																	<p>Seleccione un Prespupuesto:</p>
																	<select id = 'ppto_ot_producto_cliente_b_facturacion' name = 'ppto_ot_producto_cliente_b_facturacion'>
																		<option value = '0'>[SELECCIONE]</option>
																	</select>
																</td>
																<td class = 'separator' width = '2%'></td>
																<td>
																	<p>Ingrese el Número de Factura:</p>
																	<input type = 'text' class = 'entradas_bordes' id = 'num_factura_ppto' name = 'num_factura_ppto'  />
																</td>
															</tr>
															<tr>
																<td>
																	<p>Ingrese el Valor de la Factura:</p>
																	<input type = 'text' class = 'entradas_bordes' id = 'valor_factura_ppto' name = 'valor_factura_ppto'  onkeyup = 'formatear_valor(event,"valor_factura_ppto","valor_factura_ppto_real")'/>
																	<span class = 'hidde' id = 'valor_factura_ppto_real'></span>
																</td>
																<td class = 'separator' width = '2%'></td>
																<td>
																	<p>Ingrese la Fecha de la Factura:</p>
																	<input type = 'text' class = 'entradas_bordes' id = 'fecha_factura_ppto' name = 'fecha_factura_ppto'  />
																</td>
															</tr>
															<tr>
																<td colspan = '3' align = 'center'>
																	<img src = '../images/iconos/guardar_2.png' class = 'mano iconos_guardar' id = "limpiar_campos_ingresar_factura" style = 'position:relative;'>
																	<img src = '../images/iconos/guardar_1.png' class = 'iconos_guardar_x'   style = 'position:relative;top:45px;left:-50px;z-index:1;'>
																	<img src = '../images/iconos/guardar_3.png' class = 'mano iconos_guardar' id = "guardar_factura_ppto"  style = 'position:relative;left:-110px;'>
																</td>
															</tr>
														</table>
													</div>
													
												</td>
											</tr>	
										</table>
										<table width = '100%' class = 'hijos_tesoreria_cliente todo_fact' style = 'display:none;' >
											<tr >
												<td width = '100%' style = 'padding-left:20px;'>
													<div  style = 'border-radius:0.3em;-moz-border-radius:0.3em;-webkit-border-radius:0.3em;' width = '100%'>
														<table width = '100%' class = 'tabla_nuevos_datos2' style = 'padding-left:10%;padding-right:10%;'>
															<tr>
																<td width = '49%'>
																	<p>Seleccione una Empresa:</p>
																	<select class = "entradas_bordes" id = "empresa_b_tesoreria" name = 'empresa_b_tesoreria'  >
																		<option value = 0>Todas las empresas</option>
																		<?php
																			$consulta = "SELECT e.nombre_comercial_empresa, e.cod_interno_empresa from empresa e, pusuemp p
																			where e.cod_interno_empresa = p.cod_empresa and p.cod_usuario = '$usuario_actual'";
																			$result = mysql_query($consulta);
																			while($row = mysql_fetch_array($result)){
																				echo "<option value=".$row['cod_interno_empresa'].">".utf8_encode($row['nombre_comercial_empresa'])."</option>";
																			}
																		?>
																	</select>
																</td>
																<td class = 'separator' width = '2%'></td>
																<td width = '49%'>
																	<p>Seleccione un Cliente:</p>
																	<select id = 'cliente_b_tesoreria' name = 'cliente_b_tesoreria'>
																		<option value = '0'>[SELECCIONE]</option>
																	</select>
																</td>
															</tr>
															<tr>
																<td>
																	<p>Seleccione el Número de Factura:</p>
																	<select type = 'text' class = 'entradas_bordes' id = 'num_factura_tesoreria' name = 'num_factura_tesoreria' >
																		<option value = '0'>[SELECCIONE]</option>
																	</select>
																</td>
																<td class = 'separator' width = '2%'></td>
																<td>
																	<p>Número de Ppto - REFERENCIA</p>
																	<select class = 'entradas_bordes' id = 'num_ppto_factura_tesoreria' name = 'num_ppto_factura_tesoreria' >
																		<option value = '0'>[SELECCIONE]</option>
																	</select>
																</td>
															</tr>
															<tr>
																<td>
																	<p>Seleccione la Fecha de Pago:</p>
																	<input type = 'text' class = 'entradas_bordes' id = 'fecha_pago_cliente_tesoreria' name = 'fecha_pago_cliente_tesoreria' />
																</td>
																<td class = 'separator' width = '2%'></td>
																<td>
																	<p>Seleccione el Tipo de Pago:</p>
																	<select id = 'pago_tesoreria_cliente'>
																		<option value = '0'>[SELECCIONE]</option>
																		<option value = '1'>Parcial</option>
																		<option value = '2'>Total</option>
																	</select>
																</td>
															</tr>
															<tr>
																<td>
																	<p>Ingrese el Valor del Pago:</p>
																	<input type = 'text' class = 'entradas_bordes' name = 'valor_pago_cliente_factura' id = 'valor_pago_cliente_factura' onkeyup = 'formatear_valor(event,"valor_pago_cliente_factura","valor_tesoreria_ppto_real")'/>
																	<span class = 'hidde' id = 'valor_tesoreria_ppto_real'></span>
																</td>
															</tr>
															<tr>
																<td colspan = '3' align = 'center'>
																	<img src = '../images/iconos/guardar_2.png' class = 'mano iconos_guardar' id = "limpiar_campos_pago_clientes_tesoreria" style = 'position:relative;'>
																	<img src = '../images/iconos/guardar_1.png' class = 'iconos_guardar_x'   style = 'position:relative;top:45px;left:-50px;z-index:1;'>
																	<img src = '../images/iconos/guardar_3.png' class = 'mano iconos_guardar' id = "guardar_pago_cliente_tesoreria"  style = 'position:relative;left:-110px;'>
																</td>
															</tr>
														</table>
													</div>
												</td>
											</tr>
										</table>
										<table width = '100%' class = 'hijos_pagos_proveedores_pagos todo_fact' style = 'display:none;' >
											<tr >
												<td width = '100%'>
													<div style = 'border-radius:0.3em;-moz-border-radius:0.3em;-webkit-border-radius:0.3em;' width = '100%'>
														<table width = '100%' class = 'tabla_nuevos_datos2' style = 'padding-left:10%;padding-right:10%;'>
															<tr>
																<td>
																	<p>Ingres el Número de OC:</p>
																	<input type = 'text' class = 'entradas_bordes' id = 'num_oc_pago_proveedor' name = 'num_oc_pago_proveedor' onkeyup = 'buscar_oc_para_pagar_proveedor()' />
																</td>
																<td class = 'separator' width = '2%'></td>
																<td>
																	<p>Número de Factura Proveeodr:</p>
																	<input type = 'text' class = 'entradas_bordes' id = 'num_fact_pago_proveedor' name = 'num_fact_pago_proveedor' readonly/>
																</td>
															</tr>
															<tr>
																<td>
																	<p>Seleccione la Fecha de Pago:</p>
																	<input type = 'text' class = 'entradas_bordes' id = 'fecha_pago_num_fact_proveedor' name = 'fecha_pago_num_fact_proveedor' />
																</td>
																<td class = 'separator' width = '2%'></td>
																<td>
																	<p>Ingrese el Valor Pagado:</p>
																	<input type = 'text' class = 'entradas_bordes' id = 'valor_pago_num_fact_proveedor' name = 'valor_pago_num_fact_proveedor' readonly/>
																</td>
															</tr>
															<tr>
																<td colspan = '3' align = 'center'>
																	<img src = '../images/iconos/guardar_2.png' class = 'mano iconos_guardar' id = "limpiar_pago_facturas_proveedor" style = 'position:relative;'>
																	<img src = '../images/iconos/guardar_1.png' class = 'iconos_guardar_x'   style = 'position:relative;top:45px;left:-50px;z-index:1;'>
																	<img src = '../images/iconos/guardar_3.png' class = 'mano iconos_guardar' id = "guardar_pago_facturas_proveedor"  style = 'position:relative;left:-110px;'>
																</td>
															</tr>
														</table>
													</div>
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<!--<table width = '100%'>
						<tr>
							<th align = 'left' onclick = '$(".hijos_recepcion_facturacion").toggle();' class ='mano submenus_facturacion' >RECEPCIÓN DE FACTURAS</th>
						</tr>
						
						<tr>
							<th align = 'left'  onclick = '$(".hijos_facturacion_pptos").toggle();' class ='mano submenus_facturacion'>FACTURACIÓN DE PPTOS</th>
						</tr>
						
						<tr>
							<th align = 'left' onclick = '$(".hijos_tesoreria_cliente").toggle();' class ='mano submenus_facturacion'>PAGOS DE CLIENTES</th>
						</tr>
						
						<tr>
							<th align = 'left' onclick = '$(".hijos_pagos_proveedores_pagos").toggle();' class ='mano submenus_facturacion'>PAGOS A PROVEEDORES</th>
						</tr>
						
					</table>-->					
				</div>
				
				<div id = 'vetana_registrar_fac_pro' style = 'padding-left:50px;padding-right:50px;background-color:white;border-radius:0.5em;-webkit-border-radius:0.5em;'>
					<table width = '100%'>
						<tr>
							<td width = '96%'>
								<span class = 'mensaje_bienvenida'>REGISTRO DE FACTURAS PROVEDOR</span>
							</td>
							<td align = 'right'>
								<img src = '../images/iconos/cerrar.png' onclick = "$('#vetana_registrar_fac_pro').dialog('close');" class = 'iconos_opciones' />
							</td>
						</tr>
					</table>
					</br>
					
					<table width = '100%' class = 'tabla_nuevos_datos'>
						<tr>
							<td>
								<p>Tipo de Documento:</p>
								<select id = 'tipo_doc_prov'>
									<option value = '1'>Factura</option>
									<option value = '2'>Cuenta de Cobro</option>
									<option value = '3'>Poliza</option>
									<option value = '4'>Legalización</option>
									<option value = '5'>Reembolso</option>
									<option value = '6'>Anticipo</option>
								</select>
							</td>
							<td class = 'separator' style = 'padding-left:20px;'></td>
							<td>
								<p>Número de Documento:</p>
								<input type = 'text' id = 'num_doc_pro' />
							</td>
						</tr>
						<tr>
							<td>
								<p>Fecha de Documento:</p>
								<input type = 'text' id = 'fecha_doc_fact_prov'/>
							</td>
							<td class = 'separator' style = 'padding-left:20px;'></td>
							<td>
								<p>Fecha de Vencimiento Documento:</p>
								<input type = 'text' id = 'fechav_doc_fact_prov'/>
							</td>
						</tr>
						<tr>
							<td>
								<p>Valor Documento:</p>
								<input type = 'text' id = 'valor_fact_prov'/>
							</td>
							<td class = 'separator' style = 'padding-left:20px;'></td>
							<td>
								<p>Iva Documento:</p>
								<input type = 'text' id = 'iva_fact_prov'/>
							</td>
						</tr>
						<tr>
							<td></br></td>
						</tr>
						<tr>
							<td></br></td>
						</tr>
						<tr>
							<td colspan = '3' align = 'center'>
								<span class = 'botton_verde' onclick ="$('#vetana_registrar_fac_pro').dialog('close');$('#vetana_registrar_fac_pro input').val('');">CANCELAR</span>
								<span class = 'botton_verde' ID = 'guardar_fac_prov_orden'>Guardar</span>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</body>
		