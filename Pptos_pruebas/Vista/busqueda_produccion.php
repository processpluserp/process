<?php
	session_start();
	include("../Controller/Conexion.php");
	require("../Modelo/cabecera_presupuesto.php");
	require("../Modelo/ppto_produccion.php");
	require("../Modelo/ordenes.php");
	require("../Modelo/proveedor.php");
	
	
	$usuario = $_SESSION["codigo_usuario"];
	$fecha = date("Y-m-d h:i:s");
	$cp = new cabecera_presupuesto();
	$turno = $_POST['turno'];
	$impx = "<option value = '...'>...</option>";
	if($turno == 1){
		$_SESSION['cod_empresa'] =$_POST['emp'];
		$emp = $_POST['emp'];
		$usu = $_POST['usu'];
		$consulta_empresa = "select distinct e.codigo_interno_cliente, e.nombre_comercial_cliente from clientes e, pcliepro c where e.estado = 1 and 
		c.cod_cliente = e.codigo_interno_cliente and c.pk_empresa = '$emp' and c.cod_usuario = '$usu' order by e.nombre_comercial_cliente asc";
		$result2 = mysql_query($consulta_empresa);
		while($row = mysql_fetch_array($result2)){
			$impx .= "<option value = ".$row['codigo_interno_cliente'].">".$row['nombre_comercial_cliente']."</option>";
		}
		echo $impx;
	}
	else if($turno == 2){
		$emp = $_POST['emp'];
		$clie = $_POST['clie'];
		$consulta_empresa = "select codigo_ot,referencia from cabot where pk_nit_empresa_ot ='$emp' and producto_clientes_pk_clientes_nit_procliente = '$clie'";
		$result2 = mysql_query($consulta_empresa);
		while($row = mysql_fetch_array($result2)){
			$impx .= "<option value = ".$row['codigo_ot'].">".$row['codigo_ot']." - ".strtoupper($row['referencia'])."</option>";
		}
		echo $impx;
	}
	else if($turno == 3){
		$emp = $_POST['emp'];
		$select = "select observacion from empresa where cod_interno_empresa = '$emp'";
		$r = mysql_query($select);
		while($row = mysql_fetch_array($r)){
			echo ($row['observacion']);
		}
	}
	
	//Inserto el nuevo ppto
	else if($turno == 4){
		$cp->consulta_pptos_existentes($_POST['emp'],$_POST['clie']);
		$cp->set_referencia_cabecera_presupuesto($_POST['ref']);
		$cp->set_vigencia_final_cabecera_presupuesto($_POST['vf']);
		$cp->set_vigencia_inicial_cabecera_presupuesto($_POST['vi']);
		$cp->set_nota_cabecera_presupuesto($_POST['nota']);
		$cp->set_tipo_comision_cabecera_presupuesto($_POST['comision']);
		$cp->set_ciudad_cabecera_presupuesto($_POST['ciudad']);
		$cp->set_estado_cabecera_presupuesto(1);
		$cp->set_empresa_cabecera_presupuesto($_POST['emp']);
		$cp->set_cliente_cabecera_presupuesto($_POST['clie']);
		$cp->set_codigoot_cabecera_presupuesto($_POST['ot']);
		$cp->set_tipo($_POST['tipo']);
		$cp->set_num_apro($_POST['n_apro']);
		$cp->insert_cabecera_presupuesto($usuario,$fecha,$_POST['ceco']);
		$_SESSION['codigo_cliente_ppto'] = $_POST['clie'];
		$_SESSION['num_ppto'] = $cp->consulta_consecutivo_ppto($_POST['emp'],$_POST['clie'],$cp->get_numero_cabecera_presupuesto());
		$id_vnc = "select codigo_int_celula from cecula_ppto_interno where pk_ppto_interno = '$ppto'";
		$r = mysql_query($id_vnc);
		$xxx = 0;
		while($ro = mysql_fetch_array($r)){
			$xxx++;
		}
		$x = "VALORES NO COMISIONABLES";
		$xxx++;
		$ppto = $_SESSION['num_ppto'];
		$version = 1;
			mysql_query("insert into versiones_presup(ppto,version,fecha,user,versionc) values('$ppto','$version','".date("Y-m-d h:i:s")."','".$usuario."','".$version."')");
			mysql_query("insert into itempresup (proveedor,ppto,vi,vc) values ('272','$ppto','$version','$version')");
		mysql_query("COMMIT");
		echo $_POST['tipo'];
		
	}
	
	//Datos formulario de Producción.
	else if($turno == 5){
		$ppto = $_SESSION['num_ppto'];
		$id_vnc = "select codigo_int_celula from cecula_ppto_interno where pk_ppto_interno = '$ppto'";
		$r = mysql_query($id_vnc);
		$xxx = 0;
		while($ro = mysql_fetch_array($r)){
			$xxx++;
		}
		$x = $_POST['celula'];
		$ppto = $_POST['ppto'];
		$xxx++;
		$consult = "INSERT INTO cecula_ppto_interno(nombre_celula,pk_ppto_interno,consecutivo) 
		values('".$x."','".$ppto."','".$xxx."')";
		$result = mysql_query($consult);
		
		
		$consulta2 = "select codigo_int_celula, nombre_celula from cecula_ppto_interno where pk_ppto_interno = '$ppto'";
		$result2 = mysql_query($consulta2);
		$celulas = "";
		$sub_celulas = "";
		while($row = mysql_fetch_array($result2)){
			$o = $row['codigo_int_celula'];
			$celulas .="<option value = ".$row['codigo_int_celula'].">".$row['nombre_celula']."</option>";
			$sub_celulas .="<table id =".$row['codigo_int_celula'].">
				<tr>
					<th colspan = '34' class = 'encabezado'>".$row['nombre_celula']."</th>
					<th class = 'encabezado'><a href = '#' id = 'a".$row['codigo_int_celula']."' >+</a></th>
				</tr>
			</table>";
		}
		echo $sub_celulas;
	}
	
	//Agregar Items
	else if($turno == 6){
		$celula = "";
		$item = "";
		$proveedor = "";
		$descripcion = "";
		$dias = 0;
		$cantidad = 0;
		$celula = $_POST['celula'];
		$item = $_POST['item'];
		$proveedor = $_POST['proveedor'];
		$descripcion = $_POST['descripcion'];
		$dias = $_POST['dias'];
		$cantidad = intval($_POST['cantidad']);
		#Anticipo
		$por_anticipo = ($_POST['por_anticipo']);
		$fecha_anticipo = $_POST['fecha_anticipo'];
		#Valor Cotizacion
		$vcotizacion = ($_POST['vcotizacion']);
		#Porcentaje de Negocación
		$negociacion = ($_POST['negociacion']);
		#Valor Venta ($)
		$v_venta = ($_POST['v_venta']);
		#Comision Venta (%)
		$c_venta =($_POST['c_venta']);
		#Operaciones
		$total2 = $cantidad*$dias*$vcotizacion;
		$negociacion_valor = $total2 * ($negociacion/100);
		#Total Valor Compra
		$total_valor_compra = $total2 - $negociacion_valor;
		#Valor Unitario Compra
		$valor_unitario_compra = $total_valor_compra/$cantidad/$dias;
		#Valor Anticipo:
		$valor_anticipo = $total_valor_compra * ($por_anticipo/100);
		#Total valor venta:
		$total_valor_venta = $v_venta*$cantidad*$dias;
		#valor comision:
		$valor_comision = $total_valor_venta-$total2;
		#Valor Comision Venta:
		$valor_comision_venta = $negociacion_valor + $valor_comision;
		$total_por_comisiones = $negociacion + $c_venta;
		$pk = $_SESSION["num_ppto"];
		$consult = "INSERT INTO dppto_interno(item,descripcion,proveedor,cantidad,dias,valor_anticipo,por,fecha,
		unitario1,total1,unitario2,total2,valor,por_valor,unitario3,total3,valor2,por_valor2,valor_final,por_final,pk_cod_ppto_interno,codigo_celuda) 
		VALUES('".$item."','".$descripcion."','".$proveedor."','".$cantidad."','".$dias."','".$valor_anticipo."','".$por_anticipo."','".$fecha_anticipo."','".
		$valor_unitario_compra."','".$total_valor_compra."','".$vcotizacion."','".$total2."','".$negociacion_valor."','".$negociacion."','".
		$v_venta."','".$total_valor_venta."','".$valor_comision."','".$c_venta."','".$valor_comision_venta."','".$total_por_comisiones."','".$pk."','".$celula."')";
		mysql_query($consult);
	}
	
	//Modificar Items
	else if($turno == 7){
		$celula = "";
		$item = "";
		$proveedor = "";
		$descripcion = "";
		$dias = 0;
		$cantidad = 0;
		$celula = $_POST['celula'];
		$item = $_POST['item'];
		$proveedor = $_POST['proveedor'];
		$descripcion = $_POST['descripcion'];
		$dias = $_POST['dias'];
		$cantidad = intval($_POST['cantidad']);
		#Anticipo
		$por_anticipo = ($_POST['por_anticipo']);
		$fecha_anticipo = $_POST['fecha_anticipo'];
		#Valor Cotizacion
		$vcotizacion = ($_POST['vcotizacion']);
		#Porcentaje de Negocación
		$negociacion = ($_POST['negociacion']);
		#Valor Venta ($)
		$v_venta = ($_POST['v_venta']);
		#Comision Venta (%)
		$c_venta =($_POST['c_venta']);
		#Operaciones
		$total2 = $cantidad*$dias*$vcotizacion;
		$negociacion_valor = $total2 * ($negociacion/100);
		#Total Valor Compra
		$total_valor_compra = $total2 - $negociacion_valor;
		#Valor Unitario Compra
		$valor_unitario_compra = $total_valor_compra/$cantidad/$dias;
		#Valor Anticipo:
		$valor_anticipo = $total_valor_compra * ($por_anticipo/100);
		#Total valor venta:
		$total_valor_venta = $v_venta*$cantidad*$dias;
		#valor comision:
		$valor_comision = $total_valor_venta-$total2;
		#Valor Comision Venta:
		$valor_comision_venta = $negociacion_valor + $valor_comision;
		$total_por_comisiones = $negociacion + $c_venta;
		$id = $_POST['cod'];
		$pk = $_SESSION["num_ppto"];
		$consult = "UPDATE dppto_interno SET item='$item', descripcion = '$descripcion',proveedor = '$proveedor', cantidad = '$cantidad', dias = '$dias',
		valor_anticipo = '$valor_anticipo', por = '$por_anticipo', fecha = '$fecha_anticipo', unitario1 = '$valor_unitario_compra', total1 = '$total_valor_compra',
		unitario2 = '$vcotizacion', total2 = '$total2',valor = '$negociacion_valor', por_valor = '$negociacion', unitario3 = '$v_venta', total3 = '$total_valor_venta',
		valor2='$valor_comision', por_valor2 = '$c_venta', valor_final = '$valor_comision_venta',por_final = '$total_por_comisiones',pk_cod_ppto_interno = '$pk',codigo_celuda = '$celula'
		 where codigo_interno_item = '$id'";
		mysql_query($consult);
		
	}
	
	//Busqueda de Pptos
	else if($turno == 8){
		$ppto = new ppto_produccion();
		$ppto->buscar_pptos($_POST['ot']);
	}
	
	//Cargue ppto
	else if($turno == 9){
		$_SESSION["num_ppto"] = $_POST['ppto'];
		$_SESSION['codigo_cliente_ppto'] = $_POST['clie'];
	}
	else if($turno == 10){
		$ppto = new ppto_produccion();
		echo $ppto->listar_grupos_tarifario($_POST['name']);
	}
	else if($turno == 11){
		$ppto = new ppto_produccion();
		$ppto->insertar_grupo_ppto($_POST['grupo'],$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	
	else if($turno == 12){
		$ppto = new ppto_produccion();
		echo $ppto->listado_items_x_grupo($_POST['name'],$_POST['grupo'],$_POST['celula']);
	}
	
	else if($turno == 13){
		$ppto = new ppto_produccion();
		$num = $ppto->contar_items_por_ppto($_POST['ppto']);
		$ppto->set_pk_item($_POST['item']);
		$ppto->set_ppto($_POST['ppto']);
		$prove = $ppto->consultar_proveedor_item($_POST['item']);
		$ppto->set_val_item($ppto->consultar_valor_item($_POST['item']));
		$ppto->set_valor_desde($ppto->consultar_valor_desde($_POST['item']));
		$ppto->set_por_desde($ppto->consultar_valor_por_desde($_POST['item']));
		$ppto->insert_item_ppto($num,$fecha,$usuario,$_POST['celula'],$prove);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 14){
		$ppto = new ppto_produccion();
		$ppto->cambiar_dias($_POST['dias'],$_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	
	else if($turno == 15){
		$ppto = new ppto_produccion();
		$ppto->cambiar_q($_POST['q'],$_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 16){
		$ppto = new ppto_produccion();
		$ppto->cambiar_descripcion($_POST['q'],$_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 17){
		$ppto = new ppto_produccion();
		$ppto->cambiar_cliente($_POST['q'],$_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 18){
		$ppto = new ppto_produccion();
		$ppto->cambiar_fecha($_POST['q'],$_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 19){
		$ppto = new ppto_produccion();
		$ppto->cambiar_por_ant($_POST['q'],$_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 20){
		$ppto = new ppto_produccion();
		$ppto->eliminar_item($_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 21){
		$ppto = new ppto_produccion();
		$ppto->eliminar_grupo($_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 22){
		$ppto = new ppto_produccion();
		echo$ppto->listado_items_valores_no_comisionables($_POST['name'],$_POST['celula']);
	}
	else if($turno == 23){
		$ppto = new ppto_produccion();
		$ppto->modificar_imprevisto($_POST['q'],$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 24){
		$ppto = new ppto_produccion();
		$ppto->modificar_gasto($_POST['q'],$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 25){
		$ppto = new ppto_produccion();
		echo $ppto->listar_proveedores_ppto($_POST['ppto']);
	}
	else if($turno == 26){
		$ppto = new ppto_produccion();
		echo $ppto->productos_proveedor_ppto($_POST['prov'],$_POST['ppto']);
	}
	else if($turno == 27){
		$ppto = new ppto_produccion();
		echo $ppto->nota_op_empresa($_POST['ppto']);
	}
	
	else if($turno == 28){
		$orden = new ordenes();
		$numero = $orden->consultar_consecutivo_orden($_POST['prov'],date("Y"));
		$orden->set_proveedor($_POST['prov']);
		$orden->set_fentrega($_POST['fecha_entrega_op']);
		$orden->set_fradicacion($_POST['fecha_radicacion_op']);
		$orden->set_vinicial($_POST['vigencia_inicial_op']);
		$orden->set_vfinal($_POST['vigencia_final_op']);
		$orden->set_nota($_POST['nota_op']);
		$orden->set_fpago($_POST['fpago']);
		$orden->set_ppto($_POST['ppto']);
		$orden->insert_ordenes($numero,date("Y"),$fecha,$usuario);
		$num = $orden->consultar_ultima_orden();
		$items = $_POST['items'];
		$items = explode(",",$items);
		for($i = 0;$i < count($items);$i++){
			$orden->consultar_valores_item($items[$i],$num);
			//$orden->insert_detalle_orden($num,$items[$i]);
		}
		
		echo $num;
	}
	else if($turno == 29){
		$ppto = new ppto_produccion();
		$ppto->update_comision_pedro($_POST['val'],$_POST['x']);
	}
	else if($turno == 30){
		$consulta = "SELECT cod_interno_ceco,  nombre from ceco where codigo_empresa ='".$_POST['emp']."' and estado = 1";
		$result = mysql_query($consulta);
		while($row = mysql_fetch_array($result)){
			echo "<option value=".$row['cod_interno_ceco'].">".utf8_encode($row['nombre'])."</option>";
		}
	}
	
	else if($turno == 31){
		$pro =new proveedor();
		$pro->listar_proveedores_nombre($_POST['name'],$_POST['id'],$_POST['id_real']);
	}
	else if($turno == 32){
		$pro = new proveedor();
		$pro->update_proveedor_tarifario_item($_POST['id'],$_POST['pro']);
	}
	else if($turno == 33){
		$pro = new proveedor();
		$pro->update_valor_item_ppto_cambio($_POST['estado'],$_POST['id_item'],$_POST['id_real'],$_POST['valor_original'],$_POST['valor_n']);
	}
	else if($turno == 34){
		$ppto = new ppto_produccion();
		$ppto->modificar_factoring_impuestos($_POST['q'],$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 35){
		$ppto = new ppto_produccion();
		$ppto->modificar_ant_int_ban_impuestos($_POST['q'],$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 36){
		$ppto = new ppto_produccion();
		$ppto->modificar_pro_int_ban_impuestos($_POST['q'],$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 37){
		$ppto = new ppto_produccion();
		$ppto->modificar_pro_int_ter_impuestos($_POST['q'],$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 38){
		$ppto = new ppto_produccion();
		$ppto->modificar_cheques_ppto($_POST['num'],$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 39){
		$ppto = new ppto_produccion();
		$ppto->cambiar_volumen_item($_POST['vol'],$_POST['id']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 40){
		$clie = $_POST['clie'];
		$imp = "<option value = '0'>[SELECCIONE]</option>";
		$sql = mysql_query("select consecutivo,uaai,tipo,pago from condiciones_cliente where cliente = '$clie'");
		while($row = mysql_fetch_array($sql)){
			if($row['tipo'] == 1){
				$imp.="<option value = '".$row['consecutivo']."'>COMISION: ".$row['uaai']." -- DIVIDIDA -- PAGO A ".$row['pago']." DÍAS";
			}else{
				$imp.="<option value = '".$row['consecutivo']."'>COMISION: ".$row['uaai']." -- MULTIPLICADA -- PAGO A ".$row['pago']." DÍAS";
			}
		}
		echo $imp;
	}
	else if($turno == 41){
		$name_grupo = $_POST['grupo'];
		$sql =mysql_query("select id from grupo_tarifario where name = '$name_grupo'");
		$id = "";
		while($row = mysql_fetch_array($sql)){
			$id = $row['id'];
		}
		$ppto = new ppto_produccion();
		$ppto->insertar_grupo_ppto($id,$_POST['ppto']);
		echo $ppto->estructura($_POST['ppto']);
	}
	else if($turno == 42){
		$ppto = new ppto_produccion();
		$name_grupo = $_POST['name'];
		$sql =mysql_query("select id from item_tarifario where name = '$name_grupo'");
		$id = "";
		while($row = mysql_fetch_array($sql)){
			$id = $row['id'];
		}
		$ppto->set_pk_item($id);
		$ppto->set_ppto($_POST['ppto']);
		$prove = 1;
		$ppto->set_val_item(0);
		$ppto->set_valor_desde(0);
		$ppto->set_por_desde(0);
		$ppto->insert_item_ppto($fecha,$usuario,$_POST['celula'],$prove);
	}
	else if($turno == 43){
		$ppto = new ppto_produccion();
		echo $ppto->listar_grupos_tarifario_m($_POST['name'],$_POST['id']);
	}
	else if($turno == 44){
		$ppto = new ppto_produccion();
		$ppto->modificar_grupo_celula($_POST['id'],$_POST['grupo']);
	}
	else if($turno == 45){
		$ppto = new ppto_produccion();
		$ppto->copiar_celula_ppto_items($_POST['celula'],$_POST['ppto'],$usuario,$fecha);
	}
	else if($turno == 46){
		$sql = mysql_query("select item from detalle_orden where noorden = '".$_POST['noorden']."'");
		$val = 0;
		if(mysql_num_rows($sql) == 0){
			//echo "ORDEN NO ENCONTRADA";
		}else{
			
			while($row = mysql_fetch_array($sql)){
				$sql2 = mysql_query("select dias,q,val_item from itempresup where id = '".$row['item']."'");
				while($r2 = mysql_fetch_array($sql2)){
					$val+= (floatval($r2['val_item'])*$r2['dias']*$r2['q']);
				}
			}
			//echo "<p>LA ORDEN ESTÁ POR VALOR DE ".number_format($val)."</p></br><span class = 'botton_verde' onclick = 'abrir_fac_prove(".$_POST['noorden'].")'>REGISTRAR FACTURA</span>";
			
		}
		$orden = new ordenes();
		$orden->estructura_orden_recepcion_facturas($orden->sql_orden_busqueda($_POST['noorden']),$_POST['noorden']);
	}
	
	else if($turno == 47){
		$orden = new ordenes();
		$orden->update_info_orden_proveedor($fecha,$usuario,$_POST['t'],$_POST['d'],$_POST['f'],$_POST['fv'],$_POST['val'],$_POST['ival'],$_POST['noo'],$fecha,$usuario);
	}

	else if($turno == 48){
		$emp = $_POST['emp'];
		$clie = $_POST['clie'];
		$pro = $_POST['pro'];
		$consulta_empresa = "select codigo_ot,referencia from cabot where pk_nit_empresa_ot ='$emp' and producto_clientes_pk_clientes_nit_procliente = '$clie' and producto_clientes_codigo_PRC = '$pro'";
		$result2 = mysql_query($consulta_empresa);
		while($row = mysql_fetch_array($result2)){
			$impx .= "<option value = ".$row['codigo_ot'].">".$row['codigo_ot']." - ".strtoupper($row['referencia'])."</option>";
		}
		echo $impx;
	}

	else if($turno == 49 ){
		$presup = new cabecera_presupuesto();
		$presup->listar_pptos_select($_POST['emp'],$_POST['clie'],$_POST['ot']);
	}

	else if($turno == 50 ){
		$presup = new cabecera_presupuesto();
		$presup->facturar_ppto($_POST['ppto'],$_POST['factura'],$_POST['fecha'],$_POST['valor'],$fecha,$usuario);
	}

	else if($turno == 51){
		$presup = new cabecera_presupuesto();
		$presup->buscar_factura_pago($_POST['factura']);
	}
	
	else if($turno == 52){
		$presup = new cabecera_presupuesto();
		$presup->buscar_facturas_empresa_cliente($_POST['emp'],$_POST['clie']);
	}

	else if($turno == 53){
		$presup = new cabecera_presupuesto();
		$presup->buscar_factura_pago($_POST['fact']);
	}
	else if($turno == 54){
		$presup = new cabecera_presupuesto();
		$presup->descargar_pago_cliente($_POST['tipo'],$_POST['fact'],$_POST['ppto'],$_POST['valor'],$fecha,$usuario);
		echo "PAGO REGISTRADO !!!";
	}

	else if($turno == 55){
		$orden = new ordenes();
		$orden->buscar_num_oc_pago_proveedor($_POST['oc']);
	}
	else if($turno == 56){
		$ppto = new ppto_produccion();
		$ppto->update_item_onclick($_POST['id'],$_POST['desc'],$_POST['q'],$_POST['d'],$_POST['vcotizacion'],$_POST['iva'],$_POST['vol'],$_POST['vexterna'],$_POST['fa'],$_POST['pfa']);
	}
	else if($turno == 57){
		$ppto = new ppto_produccion();
		$ppto->est_asiciacion($_POST['ppto']);
	}
	else if($turno == 58){
		$ppto = new ppto_produccion();
		$ppto->list_diferente_asoc($_POST['ppto'],$_POST['item']);
	}
	else if($turno == 59){
		$ppto = new ppto_produccion();
		$items = ($_POST['items']);
		if($items == "x"){
			$items = "x";
		}else{
			$items = $_POST['items'];
		}
		
		$ppto->items_seleccionados_asoc($_POST['principal'],$_POST['ppto'],$items);
	}
	else if($turno == 60){
		$ppto = new ppto_produccion();
		mysql_query("update itempresup set asoc = '0' where ppto = '".$_POST['ppto']."' and asoc = '".$_POST['principal']."'");
		$items = $_POST['items'];
		for($i = 0; $i < count($items);$i++){
			$ppto->guardar_asociacion_items($_POST['principal'],$_POST['ppto'],$items[$i]);
		}		
	}
	else if($turno == 61){
		$ppto = new ppto_produccion();
		echo $ppto->listado_items_x_nuevo_ppto($_POST['name'],$_POST['i']);
	}
	else if($turno == 62){
		$ppto = new ppto_produccion();
		$ppto->cancelar_oc($_POST['orden'],$_POST['ppto']);
	}
	else if($turno == 63){
		$ppto = new ppto_produccion();
		$ppto->ordenes_produccion($_POST['ppto']);
	}
	else if($turno == 64){
		$ppto = new ppto_produccion();
		$contendor = $_POST['contenido'];
		for($i = 0; $i < count($contendor); $i++){
			$ii = $i+1;
			$ppto->update_item_nuevo_ppto($contendor[$i][0],$contendor[$i][1],$contendor[$i][2],$contendor[$i][3],$contendor[$i][4],$contendor[$i][5],$contendor[$i][6],$contendor[$i][7],$contendor[$i][8],$contendor[$i][9],$contendor[$i][10],$contendor[$i][11],$contendor[$i][12],$ii);
			
		}
		
		//var_dump($contendor);
	}
?>
