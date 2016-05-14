<!--<table width = '100%' class = '' style = 'border-collapse: collapse;'>
				<tr>
					<th colspan = '15' class = 'dil th_principal'>INTERNO</th>
					<th width = '300px'></th>
					<th colspan = '2' class = 'ext th_principal'>EXTERNO</th>
					<th width = '300px'></th>
					<th colspan = '2' class = 'dil th_principal'>RENTABILIDAD PARCIAL</th>
				</tr>
				<tr>
					<td></br></td>
				</tr>
				<tr>
					<th class = 'campos2' nowrap style = 'border-top-left-radius:1em;-moz-border-top-left-radius:1em;-webkit-border-top-left-radius:1em;'></th>
					<th class = 'campos2' nowrap ></th>
					<th class = 'border_table campos2' nowrap style = 'padding:5px;'>VNC</th>
					<th class = 'border_table campos2' nowrap style = 'padding:5px;'>GRUPO</th>
					<th class = 'border_table campos2' nowrap style = 'padding:5px;'>NOMBRE ITEM</th>
					<th class = 'border_table campos2' nowrap style = 'padding:5px;'>DESCRIPCIÓN</th>
					<th class = 'border_table campos' nowrap style = 'padding:5px;'>PROVEEDOR</th>
					<th class = 'border_table campos' nowrap style = 'padding:5px;'>DIAS</th>
					<th class = 'border_table campos' nowrap style = 'padding:5px;'>CANT.</th>
					<th class = 'border_table campos' nowrap style = 'padding:5px;'>VALOR</th>				
					<th class = 'border_table subtotal' nowrap style = 'padding:5px;'>SUBTOTAL</th>
					<th class = 'border_table fondo_td' nowrap style = 'padding:5px;'>ANTICIPO</th>
					<th class = 'border_table fondo_td' nowrap style = 'padding:5px;'>% IVA</th>
					<th class = 'border_table fondo_td' nowrap style = 'padding:5px;'>% VOLUMEN</th>
					<th class = 'border_table fondo_td' nowrap style = 'padding:5px;border-top-right-radius:1em;-moz-border-top-right-radius:1em;-webkit-border-top-right-radius:1em;'>COSTO INTERNO</th>
					<th width = '300px'></th>
					<th class = 'border_table fondo_td' nowrap style = 'padding:5px;border-top-left-radius:1em;-moz-border-top-left-radius:1em;-webkit-border-top-left-radius:1em;'>$ UNITARIO</th>
					<th class = 'border_table fondo_td' nowrap style = 'padding:5px;border-top-right-radius:1em;-moz-border-top-right-radius:1em;-webkit-border-top-right-radius:1em;'>$ TOTAL</th>
					<th width = '300px'></th>
					<th class = 'border_table fondo_td' align = 'center' style = 'padding:5px;border-top-left-radius:1em;-moz-border-top-left-radius:1em;-webkit-border-top-left-radius:1em;'>%</th>
					<th class = 'border_table fondo_td' align = 'center' style = 'padding:5px;border-top-right-radius:1em;-moz-border-top-right-radius:1em;-webkit-border-top-right-radius:1em;'>$</th>
				</tr>-->
				<?php
					
					/*
					$prov = "";
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
					
					$sql = mysql_query("select p.id,p.proveedor,p.dias,p.q,p.descripcion,p.val_item,p.iva_item,p.fecha_ant,p.por_ant,p.cliente,p.por_prov,p.id, i.name,p.editable, pp.nombre_comercial_proveedor,pp.codigo_interno_proveedor,
					g.name as grupo
					
					from itempresup p, item_tarifario i, proveedores pp, grupo_tarifario g, cecula_ppto_interno cp
					
					where p.ppto = '$num_ppto' and p.asoc = '0' and p.pk_item = i.id and p.proveedor = pp.codigo_interno_proveedor 
					and  p.ppto = cp.pk_ppto_interno and p.celula = cp.codigo_int_celula and  cp.nombre_celula = g.id and cp.nombre_celula <> 'VALORES NO COMISIONABLES' and p.vi = '$version_interna' order by cp.codigo_int_celula asc");
					$i = 0;
					while($row = mysql_fetch_array($sql)){
						$sql_prove = mysql_query("select codigo_interno_proveedor, nombre_comercial_proveedor 
						from proveedores where estado = '1'");
						$option = "<option value = '0'>[SELECCIONE]</option>";
						while($rowx = mysql_fetch_array($sql_prove)){
							if($rowx['codigo_interno_proveedor'] == $row['codigo_interno_proveedor']){
								$option.="<option value = '".$rowx['codigo_interno_proveedor']."' selected>".$rowx['nombre_comercial_proveedor']."</option>";
							}else{
								$option.="<option value = '".$rowx['codigo_interno_proveedor']."'>".$rowx['nombre_comercial_proveedor']."</option>";
							}
						}
						$id = $row['id'];
						$sql_asoc = mysql_query("select p.id,p.proveedor,p.dias,p.q,p.descripcion,p.val_item,p.iva_item,p.fecha_ant,p.cliente,p.por_prov,p.id, i.name,p.editable, pp.nombre_comercial_proveedor,pp.codigo_interno_proveedor
						from itempresup p, item_tarifario i, proveedores pp
						where p.ppto = '$num_ppto' and i.id = p.pk_item and p.proveedor = pp.codigo_interno_proveedor and p.asoc = '$id'");
						$ix = 1;
						$asociados = "";
						$total_asoc = 0;
						while($r = mysql_fetch_array($sql_asoc)){
							$idd = $ix."hijo".$i;
							$total_asoc += ($r['val_item']*$r['q']*$r['dias']);
							if($r['editable'] == 1){
								$asociados .= "
									<tr class = 'hijos_asoc$i' id = '$idd' data-dep='$i'  style = 'display:none;'> 
										<td></td>
										<td></td>
										<td></td>
										<td class = 'border_table asoc'>".$r['name']."</td>
										<td class = 'border_table asoc'>".$r['descripcion']."</td>
										<td class = 'border_table asoc'>".$r['nombre_comercial_proveedor']."</td>
										<td class = 'border_table asoc' align = 'center'>".number_format($r['dias'])."</td>
										<td class = 'border_table asoc' align = 'center'>".number_format($r['q'])."</td>
										<td class = 'border_table asoc' align = 'center'>
											<table width = '100%'>
												<tr>
													<td>$</td>
													<td align = 'right' >".number_format($r['val_item'])."</td>
												</tr>
											</table>
										</td>
										<td class = 'border_table asoc' align = 'center'>
											<table width = '100%'>
												<tr>
													<td>$</td>
													<td align = 'right' >".number_format($r['val_item']*$r['q']*$r['dias'])."</td>
												</tr>
											</table>
										</td>
									</tr>";
							}else{
								$asociados .= "
									<tr class = 'hijos_asoc$i' id = '$idd' data-dep='$i'  style = 'display:none;'>
										<td></td>
										<td></td>
										<td></td>
										<td class = 'border_table asoc'>
											<input type ='text' value = '".$r['name']."'/>
										</td>
										<td class = 'border_table asoc'>".$r['descripcion']."</td>
										<td class = 'border_table asoc'>".$r['nombre_comercial_proveedor']."</td>
										<td class = 'border_table asoc' align = 'center'>".number_format($r['dias'])."</td>
										<td class = 'border_table asoc' align = 'center'>".number_format($r['q'])."</td>
										<td class = 'border_table asoc' align = 'center'>
											<table width = '100%'>
												<tr>
													<td>$</td>
													<td align = 'right' >".number_format($r['val_item'])."</td>
												</tr>
											</table>
										</td>
										<td class = 'border_table asoc' align = 'center'>
											<table width = '100%'>
												<tr>
													<td>$</td>
													<td align = 'right' >".number_format($r['val_item']*$r['q']*$r['dias'])."</td>
												</tr>
											</table>
										</td>
									</tr>";
							}
							$ix++;
						}
						$visible = "";
						$descr = "";
						$itemm = "";
						if($ix > 1){
							$visible = "display:none;";
							$descr = nl2br($row['descripcion']);
							$itemm = $row['name'];
							$asociados.="<tr class = 'hijos_asoc$i' data-dep='$i' style = 'display:none;'>
								<td colspan = '9' align = 'right'><strong>TOTAL</strong></td>
								<td style = 'font-weight:bold;'>
									<table width = '100%'>
										<tr>
											<td>$</td>
											<td align = 'right' >".number_format($total_asoc)."</td>
										</tr>
									</table>
								</td>
							</tr>";
						}else{
							$visible = "";
							$descr = nl2br($row['descripcion']);
							$itemm = $row['name'];
						}
						if($row['editable'] == 1){
							echo "<tr class = '$id'>
									<td align = 'center'  class = 'campos border_table'>
										<img src = '../images/iconos/add.png' width = '15px'  onclick = 'addicionar_nuevo_item($i)'/>
									</td>
									<td align = 'center'  class = 'campos border_table'>
										<img src = '../images/iconos/lock.png' width = '15px' />
									</td>
									<td  align = 'center'  class = 'campos border_table'>
										
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<table width = '100%'>
											<tr>
												<td>
													<img src = '../images/iconos/Engra.png'  width = '23px' id = 'add_asoc$i' onclick = 'mostrar_asoc_items($i,".$row['editable'].")'/>
												</td>
												<td>
													".$row['grupo']."
												</td>
											</tr>
										</table>
									</td>
									<td class = 'border_table fondo_td' align = 'left' style = 'padding-left:5px;padding-right:5px;'>
										".($row['name'])."
										<div id = 'listado_items$i'></div>
									</td>
									<td class = 'border_table fondo_td' align = 'left' style = 'padding-left:5px;padding-right:5px;'>
										".nl2br($row['descripcion'])."
									</td>
									<td class = 'border_table campos proveedores' align = 'left' nowrap style = 'padding-left:5px;padding-right:5px;width:180px;'>
										<span $visible>".$row['nombre_comercial_proveedor']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										".$row['dias']." 
										<span id = 'h_dias$i' class = 'hidde'>".$row['dias']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										".$row['q']."
										<span id = 'h_cant$i'  class = 'hidde'>".$row['q']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<table width = '100%'>
											<tr>
												<td>$</td>
												<td align = 'right' >".number_format($row['val_item'])."</td>
											</tr>
										</table>
										<span id = 'h_valor_interno$i' class = 'hidde'>".$row['val_item']."</span>
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
										<span id = 'iva$i'>".$row['iva_item']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										".$row['por_prov']."
										<span id = 'h_vol$i' class = 'hidde'>".$row['por_prov']."</span>
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
												<td align = 'right'><span >".number_format($row['cliente'])."</span></td>
											</tr>
										</table>
										<span id = 'val_cliente_interno$i' class = 'hidde'>".($row['cliente'])."</span>
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
									<td align = 'center' class = 'border_table fondo_td'  nowrap>
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
											
								</tr>
							</tr>";
						}else{
							echo "<tr class = '$id'>
									<td align = 'center'  class = 'campos border_table'>
										<img src = '../images/iconos/add.png' width = '15px' />
									</td>
									<td class = 'campos' class = 'campos border_table'></td>
									<td  nowrap  class = 'campos border_table'>
										<div>
											<input type = 'checkbox' id = 'grupo$i' name = 'grupo_sel' value = '$i' onclick = 'grupo_selected()' class = 'radio'/>
											<label for='grupo$i'><span><span></span></span></label>
										</div>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<table width = '100%'>
											<tr>
												<td>
													<img src = '../images/iconos/Engra.png'  width = '23px' id = 'add_asoc$i' onclick = 'mostrar_asoc_items($i,".$row['editable'].")'/>
												</td>
												<td>
													".$row['grupo']."
												</td>
											</tr>
										</table>
									</td>
									<td class = 'border_table fondo_td' align = 'left' style = 'padding-left:5px;padding-right:5px;' nowrap>
										$itemm
									</td>
									<td class = 'border_table fondo_td' align = 'left' nowrap width = '250px' style = 'padding-left:5px;padding-right:5px;'>
										".$descr."
									</td>
									<td class = 'border_table campos proveedores' align = 'left' nowrap style = 'padding-left:5px;padding-right:5px;width:180px;'>
										<select style = 'width:180px;$visible' id = 'provee$i' >
											$option
										</select>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										".$row['dias']."
										<span id = 'h_dias$i' class = 'hidde'>".$row['dias']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										".$row['q']."
										<span id = 'h_cant$i' class = 'hidde'>".$row['q']."</span>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<input type = 'text' class = 'entrada_bordes' style = 'width:100px;' id = 'valor_interno$i' onkeyup = 'formatear_valor_costo_unitario($i);calcular_interno($i);' value = '".number_format($row['val_item'])."'/>
										<span id = 'h_valor_interno$i' class = 'hidde'>".$row['val_item']."</span>
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
										<input type = 'text' class = 'entrada_bordes' style = 'width:40px;' id = 'iva$i' value = '16'/>
									</td>
									<td class = 'border_table fondo_td' align = 'center' nowrap>
										<input type = 'text' class = 'entrada_bordes' style = 'width:40px;' id = 'vol$i' onkeyup = 'formatear_valor_por_vol($i);calcular_interno($i);' value = '".$row['por_prov']."'/>
										<span id = 'h_vol$i' class = 'hidde'>".$row['por_prov']."</span>
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
												<td align = 'right'><span >".number_format($row['cliente'])."</span></td>
											</tr>
										</table>
										
										<span id = 'val_cliente_interno$i' class = 'hidde'>".($row['cliente'])."</span>
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
											
								</tr>
							</tr>";
						}
						echo $asociados;
						$i++;
					}
					$sqlx = mysql_query("select codigo_interno_proveedor,nombre_comercial_proveedor from proveedores where estado = '1' order by nombre_comercial_proveedor asc");
					$option = "<option value = '0'>[SELECCIONE]</option>";
					
					for($x = 0; $x < 1;$x++){
						$x = $i+1;
						echo "<tr >
								<td align = 'center'  class = 'campos border_table'>
								
								</td>
								<td class = 'campos border_table'></td>
								<td class = 'campos border_table'>
									<div>
										<input type = 'checkbox' id = 'grupo$i' name = 'grupo_sel' value = '$i' onclick = 'grupo_selected()' class = 'radio'/>
										<label for='grupo$i'><span><span></span></span></label>
									</div>
								</td>
								<td class = 'border_table fondo_td' align = 'center'>
									<table width = '100%'>
										<tr>
											<td>
												<img src = '../images/iconos/Engra.png'  width = '23px' id = 'add_asoc$i' onclick = 'mostrar_asoc_items($i,0)'/>
											</td>
											<td>
												<input style = 'width:150px;' id = 'grupo$i' />
											</td>
										</tr>
									</table>
								</td>
								<td class = 'border_table fondo_td' align = 'center' >
									<input type = 'text' class = 'entradas_bordes'  id = 'nombre_item$i' onkeyup = 'abrir_lista_items($i)'/>
									<div id = 'listado_items$i'></div>
								</td>
								<td class = 'border_table fondo_td' align = 'center'>
									<textarea cols = '35' rows = '2'>
										
									</textarea>
								</td>
								<td class = 'border_table campos proveedores' align = 'center'>
									<select style = 'width:180px;' id = 'provee$i'>
										$prov
									</select>
								</td>
								<td class = 'border_table campos' align = 'center'>
									<input type = 'text' class = 'entrada_bordes' style = 'width:60px;' id = 'dias$i' onkeyup = 'formatear_valor_num_dias($i);calcular_interno($i);'/>
									<span id = 'h_dias$i' class = 'hidde'></span>
								</td>
								<td class = 'border_table campos' align = 'center'>
									<input type = 'text' class = 'entrada_bordes' style = 'width:60px;' id = 'cant$i' onkeyup = 'formatear_valor_num_cant($i);calcular_interno($i);'/>
									<span id = 'h_cant$i' class = 'hidde'></span>
								</td>
								<td class = 'border_table campos' align = 'center'>
									<input type = 'text' class = 'entrada_bordes' style = 'width:100px;' id = 'valor_interno$i' onkeyup = 'formatear_valor_costo_unitario($i);calcular_interno($i);'/>
									<span id = 'h_valor_interno$i' class = 'hidde'></span>
								</td>
								<td class = 'border_table subtotal' align = 'center'>
									<table width = '100%'>
										<tr>
											<td>$</td>
											<td align = 'right' id = 'valor_subtotal_item$i'></td>
										</tr>
									</table>
									<span class = 'subtotal_items hidde' id = 'h_subtotal_item$i' ></span>
								</td>
								<td class = 'border_table fondo_td' align = 'center' nowrap>
									
								</td>
								<td class = 'border_table fondo_td' align = 'center'>
									<input type = 'text' class = 'entrada_bordes' style = 'width:40px;' id = 'iva$i' value = '16'/>
								</td>
								<td class = 'border_table fondo_td' align = 'center'>
									<input type = 'text' class = 'entrada_bordes' style = 'width:40px;' id = 'vol$i' onkeyup = 'formatear_valor_por_vol($i);calcular_interno($i);'/>
									<span id = 'h_vol$i' class = 'hidde'></span>
								</td>
								<td class = 'border_table fondo_td' align = 'center'>
									<table width = '100%'>
										<tr>
											<td>$</td>
											<td align = 'right'><span id = 'costo_interno$i'></span></td>
										</tr>
									</table>
									<span id = 'h_costo_interno$i' class = 'hidde cost_interno_x'></span>
								</td>
								<th ></th>
								<td class = 'border_table' align = 'center' style = 'background-color:#EF8B8B;color:white;'>
									<input type = 'text' class = 'entrada_bordes' style = 'width:100px;' id = 'valor_costo_unitario_cliente$i'onkeyup = 'formatear_valor_costo_cliente($i);calcular_interno($i);'/> 
									<span id = 'val_cliente_interno$i' class = 'hidde'></span>
								</td>
								<td class = 'border_table' style = 'background-color:#EF8B8B;color:white;'>
									<table width = '100%'>
										<tr>
											<td>$</td>
											<td align = 'right'><span id = 'costo_cliente$i'></span></td>
										</tr>
									</table>
									<span id = 'h_costo_cliente$i' class = 'hidde externo_cliente'></span>					
								</td>
								<th ></th>
								<td align = 'center' class = 'border_table fondo_td' id = 'por_rent_item$i'>
									
								</td>
								<td align = 'center' class = 'border_table fondo_td'  >
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
										
							</tr>
						
						";
					}
					echo "<tr>
						<th colspan = '10'></th>
						<th class = 'dil resultados subtotal'>
							<table width = '100%'>
								<tr>
									<td>$</td>
									<td align = 'right' id = 'sum_subtotal_costo_interno'></td>
								</tr>
							</table>
						</th>
						<th colspan = '3'></th>
						<th class = 'dil resultados'>
							<table width = '100%'>
								<tr>
									<td>$</td>
									<td align = 'right' id = 'sum_total_costo_interno'></td>
								</tr>
							</table>
						</th>
						<th colspan = '2'></th>
						<th class = 'ext resultados'>
							<table width = '100%'>
								<tr>
									<td>$</td>
									<td align = 'right' id = 'sum_total_costo_externo'></td>
								</tr>
							</table>
						</th>
					</tr>";*/
				?>