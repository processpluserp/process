<?php
	include("../Controller/Conexion.php");
	
	$sql = mysql_query("select name_grupo from itempresup where id = '".$_POST['id']."'");
	$grupo = "";
	while($row = mysql_fetch_array($sql)){
		$grupo = $row['name_grupo'];
	}
	$sql_grupos = mysql_query("select id from itempresup where name_grupo = '$grupo'");
	
	$cont = array();
	$ixx = 0;
	while($row = mysql_fetch_array($sql_grupos)){
		$cont[$ixx] = $row['id'];
		$ixx++;
	}
	
	$i_actual = $_POST['i_actual'];
	$nueva_estructura_grupos = "";
	
	for($i = 0; $i < count($cont); $i++){
		$inicio = $i;
		mysql_query("START TRANSACTION");
			mysql_query("insert into itempresup(name_item,proveedor,name_grupo,dias,q,descripcion,val_item,iva_item,
			cliente,val_desde_item,por_prov,usuario,fecha_registro,ppto,vi,vc)
			select name_item,proveedor,name_grupo,dias,q,descripcion,val_item,iva_item,
			cliente,val_desde_item,por_prov,usuario,fecha_registro,ppto,vi,vc
			from itempresup where id = '".$cont[$i]."'");
			$sql_consult = mysql_query("SELECT @@identity AS id");
			$id = "";
			while($row = mysql_fetch_row($sql_consult)){
				$id = $row[0];
			}
			
			$consulta = mysql_query("select * from itempresup where id = '".$id."'");
			while($row = mysql_fetch_array($consulta)){
				$sql_prove = mysql_query("select codigo_interno_proveedor, nombre_comercial_proveedor 
				from proveedores where estado = '1'");
				$prov = "";
				while($rowx = mysql_fetch_array($sql_prove)){
					if($rowx['codigo_interno_proveedor'] == 272){
						$prov.="<option value = '".$rowx['codigo_interno_proveedor']."' selected>".$rowx['nombre_comercial_proveedor']."</option>";
					}else{
						$prov.="<option value = '".$rowx['codigo_interno_proveedor']."'>".$rowx['nombre_comercial_proveedor']."</option>";
					}
				}
				if($i == 0){
					$i = $i_actual + 1;
				}else{
					$i = $i_actual + $i + 1;
				}
				
				$nueva_estructura_grupos.= "<tr class = '$id informacion_guardada_bd item_total$i items_ppto_pro'>
									<td  nowrap  class = 'campos border_table'>
										<img src = '../images/iconos/eliminar.png' width = '20px' title = 'Eliminar Item' onclick = 'eliminar_item_by_id($id,$i)'/>
									</td>
									<td align = 'center'  class = 'campos border_table' nowrap >
										<span class = 'hidde nuevos_grupos'>$i</span>
										<span class = 'codigo_item hidde' id = 'olditem$i'>$id</span>
										<div>
											<input type = 'checkbox' id = 'grupo$i' name = 'grupo_sel' value = '$i' onclick = 'grupo_selected()' class = 'radio'/>
											<label for='grupo$i'><span><span></span></span></label>
										</div>
									</td>
									<td  nowrap  class = 'campos border_table'>
										<img src = '../images/iconos/anticipo.png' width = '20px' title = 'Pedir Anticipo sobre el Item'/>
									</td>
									<td  nowrap  class = 'campos border_table'>
									
									</td>
									<td  nowrap  class = 'campos border_table orden_item$id'>
										
									</td>
									<td  nowrap  class = 'campos border_table'>
										
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<table width = '100%'>
											<tr>
												<td style = 'vertical-align:top;'>
													<span class = 'botton_verde'>Asociados<img src = '../images/iconos/Engra.png'  width = '23px' id = 'add_asoc$i' onclick = 'mostrar_asoc_items($i,0)'/></span>
												</td>
												<td>
													<input type = 'text' value = '".($row['name_grupo'])."' id = 'grupo$id' />
												</td>
											</tr>
										</table>
									</td>
									<td class = 'border_table fondo_td' align = 'left' style = 'padding-left:5px;padding-right:5px;' nowrap>
										<input type = 'text' value = '".$row['name_item']."' id = 'itemppto$id' />
									</td>
									<td class = 'border_table fondo_td' align = 'left' nowrap width = '250px' style = 'padding-left:5px;padding-right:5px;'>
										<textarea cols = '35' rows = '2' id = 'descripcionitem$id'>".($row['descripcion'])."</textarea>
									</td>
									<td class = 'border_table campos proveedores' align = 'left' nowrap style = 'padding-left:5px;padding-right:5px;width:180px;'>
										<select style = 'width:180px;' id = 'proveedoritem$id' >
											$prov
										</select>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap style = 'padding-left:5px;padding-right:5px;'>
										<input type = 'number' class = 'entrada_bordes' max = '15'value = '".$row['dias']."' id = 'dias$i' style = 'width:55px;' onkeyup = 'formatear_valor_num_dias($i);calcular_interno($i);'/>
										<span id = 'h_dias$i' class = 'hidde diasitem$id'>".$row['dias']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap style = 'padding-left:5px;padding-right:5px;'>
										<input type = 'number' class = 'entrada_bordes' max = '15'value = '".$row['q']."' id = 'cant$i' style = 'width:55px;' onkeyup = 'formatear_valor_num_cant($i);calcular_interno($i);'/>
										<span id = 'h_cant$i' class = 'hidde cantidadiem$id'>".$row['q']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<input type = 'text' class = 'entrada_bordes' max = '15' style = 'width:80px;' id = 'valor_interno$i' onblur = 'limpiar_costo_interno($i);' onkeyup = 'formatear_valor_costo_unitario($i);calcular_interno($i);' value = '".number_format($row['val_item'])."'/>
										<span id = 'h_valor_interno$i' class = 'hidde valorinternoitem$id'>".$row['val_item']."</span>
									</td>
									<td class = 'border_table subtotal' align = 'center' nowrap>
										<table width = '100%'>
											<tr>
												<td>$</td>
												<td align = 'right' id = 'valor_subtotal_item$i'></td>
											</tr>
										</table>
										<span class = 'subtotal_items hidde' id = 'h_subtotal_item$i' ></span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										".$row['por_ant']." %
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<input type = 'text' class = 'entrada_bordes ivaitem$id' style = 'width:40px;' id = 'iva$i' value = '16' onkeyup = 'calcular_interno($i);' />
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<input type = 'text' class = 'entrada_bordes' style = 'width:40px;' id = 'vol$i' onkeyup = 'formatear_valor_por_vol($i);calcular_interno($i);' value = '".$row['por_prov']."'/>
										<span id = 'h_vol$i' class = 'hidde volumenproveedor$id'>".$row['por_prov']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<table width = '100%'>
											<tr>
												<td>$</td>
												<td align = 'right'><span id = 'costo_interno$i'></span></td>
											</tr>
										</table>
										<span id = 'h_costo_interno$i' class = 'hidde cost_interno_x'></span>
									</td>
									<td width = '300px'></td>
									<td class = 'border_table' align = 'center' style = 'background-color:#EF8B8B;color:white;' nowrap>
										<table width = '100%'>
											<tr>
												<td>$</td>
												<td align = 'right'><input type = 'text' style = 'width:80px;' class = 'entrada_bordes' onblur = 'limpiar_valor_costo_cliente($i);' value = '".number_format($row['cliente'])."' id = 'valor_costo_unitario_cliente$i' onkeyup = 'formatear_valor_costo_cliente($i);calcular_interno($i);'/></td>
											</tr>
										</table>
										<span id = 'val_cliente_interno$i' class = 'hidde valorinternocliente$id'>".($row['cliente'])."</span>
									</td>
									<td class = 'border_table' style = 'background-color:#EF8B8B;color:white;' nowrap>
										<table width = '100%'>
											<tr>
												<td>$</td>
												<td align = 'right'><span id = 'costo_cliente$i'></span></td>
											</tr>
										</table>
										<span id = 'h_costo_cliente$i' class = 'hidde externo_cliente'></span>					
									</td>
									<td width = '300px'></td>
									<td align = 'center' class = 'border_table fondo_td' id = 'por_rent_item$i' nowrap>
										
									</td>
									<td align = 'center' class = 'border_table fondo_td'   nowrap>
										<table width = '100%'>
											<tr>
												<td>$</td>
												<td align = 'right' id = 'valor_rent_item$i'>0</td>
											</tr>
										</table>
										<span id ='valor_rentabilidad_item$i' class = 'hidde rentabilidad_item'></span>
									</td>
								</tr>
								<tr id = 'asocitem$i' style = 'display:none;'>
											
								</tr>";
			}
			mysql_query("COMMIT");
			$i = $inicio;
	}
	
	echo $nueva_estructura_grupos;
	//echo count($cont);
?>