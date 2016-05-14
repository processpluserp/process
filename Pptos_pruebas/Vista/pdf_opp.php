<?php
	include("../Controller/Conexion.php");
	require('../mpdf/mpdf.php');
	require('../Modelo/cabecera_ot.php');
	
	$otn = new cabecera_ot();
	$op = $_GET['op'];
	$sql = mysql_query("select ox.codigo_interno_op, p.ot, p.referencia, ox.fecha_orden, e.nombre_empleado as ejecutivo, p.empresa_nit_empresa,emp.logo,emp.nombre_legal_empresa,emp.nit_empresa,
	cl.codigo_interno_cliente, cl.nombre_legal_clientes, pr.nombre_producto, p.numero_presupuesto, emp.nota_orden_c, proo.nombre_legal_proveedor,proo.nit_proveedor, fpa.name_forma_pago, ox.fecha_entrega
	
	from cabpresup p, detalle_orden do, orproduccion ox, empleado e, usuario u, empleado e2, usuario u2, cabot ot, empresa emp, clientes cl, producto_clientes pr, proveedores proo,
	fpago fpa
	where ox.ppto = p.codigo_presup and do.noorden = ox.codigo_interno_op and ot.codigo_ot = p.ot and ot.ejecutivo = u.idusuario and u.pk_empleado = e.documento_empleado and
	p.usuario = u2.idusuario and u2.pk_empleado = e2.documento_empleado and ox.codigo_interno_op = '$op'
	and p.empresa_nit_empresa = emp.cod_interno_empresa and p.pk_clientes_nit_cliente = cl.codigo_interno_cliente and ot.producto_clientes_codigo_PRC = pr.id_procliente
	and ox.proveedor = proo.codigo_interno_proveedor and ox.fpago = fpa.codigo_interno");
	$logo = "";
	$nit_empresa = "";
	$fecha_registro = "";
	$cliente = "";
	$producto = "";
	$cod_cliente = "";
	$referencia="";
	$ot = "";
	$nombre_emp = "";
	$numero_ppto="";
	$observaci = "";
	$comision = 0;
	$proveedor = "";
	$nit_prove = "";
	$pago_a = "";
	$fecha_entrega = "";
	/*
	$empresa = $_GET['e'];
	$logo_empresa = mysql_query("select logo,nombre_legal_empresa,nit_empresa from empresa where cod_interno_empresa = '$empresa'");
	
	$nombre_empresa = "";
	
	$id = $_GET['i'];
	*/
	//$op = $_GET['op'];
	while($row  = mysql_fetch_array($sql)){
		$logo = $row['logo'];
		$cod_cliente = $row['codigo_interno_cliente'];
		$pago_a = $row['name_forma_pago'];
		$fecha_entrega = $row['fecha_entrega'];
		//$nombre_empresa = $row['nombre_legal_empresa'];
		$nit_empresa = $row['nit_empresa'];
		$fecha_registro = $row['fecha_orden'];
		$cliente = $row['nombre_legal_clientes'];
		$producto = $row['nombre_producto'];
		$referencia = $row['referencia'];
		$ot = $row['ot'];
		$numero_ppto = $row['numero_presupuesto'];
		$nombre_emp = $row['ejecutivo'];
		$observaci = $row['nota_orden_c'];
		$proveedor = $row['nombre_legal_proveedor'];
		$nit_prove = $row['nit_proveedor'];
	}
	$t='';		
	$acum = 0;
		
	$sql = mysql_query("select id,item,descx,d,q,valor,iva,vol from detalle_orden where noorden = '$op'");
	$acum_x = 0;
	$iva_cum = 0;
	$antes_iva = 0;
	while($row = mysql_fetch_array($sql)){
		$temp = 0;
		if($cod_cliente == 1){
			$acum_x +=  (($row['iva']*$row['valor']*$row['d']*$row['q'])/100) + ($row['valor']*$row['q']*$row['d']); //
			//$xx += $row['valor']*$row['d']*$row['q'];
			$antes_iva += $row['valor']*$row['d']*$row['q'];
			$acum += $row['valor']*$row['q']*$row['d'];
			$iva_cum +=($row['iva']*$row['valor']*$row['d']*$row['q'])/100;
		}else{
			$temp = ($row['valor']*$row['vol'])/100;
			$acum_x +=  (($row['iva']*($row['valor']-$temp)*$row['d']*$row['q'])/100) + (($row['valor']-$temp)*$row['q']*$row['d']); //
			//$xx += $row['valor']*$row['d']*$row['q'];
			$antes_iva += ($row['valor']-$temp)*$row['d']*$row['q'];
			$acum +=($row['valor']-$temp)*$row['q']*$row['d'];
			$iva_cum +=($row['iva']*($row['valor']-$temp)*$row['d']*$row['q'])/100;
		}
		
		$t.='<tr>
			<td align = "center">'.$row['d']*$row['q'].'</td>
			<td>'.strtr(strtoupper($row['descx']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ").'</td>
			<td>
				<table width = "100%" class ="internos">
					<tr>
						<td align = "left" >$</td><td align = "right">'.number_format($row['valor']-$temp,2,'.',',').'</td>
					</tr>
				</table>
			</td>
			<td align = "center">'.number_format($row['iva']).'</td>
			<td>
				<table width = "100%" class ="internos">
					<tr>
						<td align = "left" >$</td><td align = "right">'.number_format(($row['iva']*($row['valor']-$temp)*$row['d']*$row['q'])/100,2,'.',',').'</td>
					</tr>
				</table>
			</td>
			<td>
				<table width = "100%" class ="internos">
					<tr>
						<td align = "left" width = "2%">$</td><td align = "right">'.number_format( (($row['iva']*($row['valor']-$temp)*$row['d']*$row['q'])/100)+(($row['valor']-$temp)*$row['d']*$row['q']),2,'.',',').'</td>
					</tr>
				</table>
			</td>
		</tr>';
	}
		$t.='
		<tr class = "salto">
			<td colspan = "4"></td>
			<th >TOTAL</th>
			<td style = "border:1px solid black;">
				<table width = "100%" class ="internos" style = "font-weight:bold;">
					<tr>
						<td align = "left" width = "2%">$</td><td align = "right">'.number_format($acum_x,2,'.',',').'</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr class = "salto"><td ></br></td></tr>
		
		<tr class = "salto"><td ></br></td></tr>
		';
	
	
	
	$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>PPTO</title>

				<style type="text/css">
					
					body{
						font-family:"Arial";
					}
					#titulo{
						font-size:26px;
					}
					#tabla_central{
						border:1px solid black;
					}
					.tabla_items th{
						border:1px solid black;
						border-radius:3em;
						border-radius:3mm;
						border-radius:3px;
						background-color:#0090FF;
						color:white;
						text-align:center;
						font-size:11px;
					}
					.tabla_items td{
						border:1px solid black;
						font-size:10px;
					}
					.internos td{
						border:0px solid white;
						
					}
					.internos2{
						border:1px solid black;
					}
					.salto td{
						border:0px solid tranparent;
					}
					@page *{
						margin-top: 2.54cm;
						margin-bottom: 2.54cm;
						margin-left: 3.175cm;
						margin-right: 3.175cm;
					}
				</style>
			</head>
			<body>
			<table id = "tabla_central" width = "100%">
				<tr>
					<th align = "center" style ="padding-left:5px;vertical-align:top;">
						<img src = "../images/Untitled-1-01.png" height = "60px" />
					</th>
					<th width = "92%" align = "center" style = "vertical-align:middle;" >
						<span id = "titulo">ORDEN DE PRODUCCIÓN # '.$op.'</span>
					</th>
					<th align = "center" style ="padding-right:5px;vertical-align:top;">
						<img src = "../images/logos/'.$logo.'" height = "60px" />
						<p id = "nit" align = "center">NIT: '.$nit_empresa.'</p>
					</th>
				</tr>
				<tr>
					<td style ="padding-left:5px;" colspan = "2">
						<table width = "100%">
							<tr>
								<td><strong>OT</strong>: </td>
								<td style = "padding-left:10px;padding:5px;">'.$ot.'</td>
							</tr>
							<tr>
								<td><strong>PRESUPUESTO</strong>: </td>
								<td style = "padding-left:10px;padding:5px;">'.$numero_ppto.' - '.strtr(strtoupper($referencia),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ").'</td>
								
								<td><strong>CLIENTE</strong>: </td>
								<td style = "padding-left:10px;">'.strtoupper($cliente).'</td>
							</tr>
							<tr>
								<td><strong>PRODUCTO</strong>: </td>
								<td style = "padding-left:10px;padding:5px;">'.strtoupper($producto).'</td>
								
								<td><strong>NIT:</strong>: </td>
								<td style = "padding-left:10px;">'.$nit_prove.'</td>
							</tr>
							<tr>
								<td><strong>CREADO POR</strong>: </td>
								<td style = "padding-left:10px;">OREXIS MONCADA</td>
								
								<td><strong>PROVEEDOR:</strong>: </td>
								<td style = "padding-left:10px;">'.$proveedor.'</td>
							</tr>
						</table>
					</td>
					<td>
						<table width = "100%">
							<tr>
								<td nowrap><strong>Fecha de Impresión</strong>: </td>
								<td style = "padding-left:10px;padding:5px;">'.date("Y-m-d").'</td>
							</tr>
							<tr>
								<td nowrap><strong>Fecha de Creación</strong>: </td>
								<td style = "padding-left:10px;">'.date("Y-m-d").'</td>
							</tr>
							<tr>
								
							</tr>
						</table>
					</td>
				</tr>
				<tr>
				</tr>
			</table>
			</br>
			</br>
			<table class = "tabla_items">
				<tr>
					<th colspan = "2">CONDICIONES DE ENTREGA</th>
				</tr>
				<tr>
					<td>
						<strong>FORMA DE PAGO</strong>
					</td>
					<td>
						'.$pago_a.'
					</td>
				</tr>
				<tr>
					<td>
						<strong>FECHA DE ENTREGA</strong>
					</td>
					<td>
						'.$fecha_entrega.'
					</td>
				</tr>
				<tr>
					<td>
						<strong>LUGAR</strong>
					</td>
					<td>
					
					</td>
				</tr>
				<tr class = "salto"><td ></br></td></tr>
				
				<tr class = "salto"><td ></br></td></tr>
			</table>
			<table width = "100%" class = "tabla_items">			
				<tr>
					<th>CANTIDAD</th>
					<th>DESCRIPCIÓN</th>
					<th>VALOR UNIDAD</th>
					<th>IVA %</th>
					<th>VALOR IVA</th>
					<th>VALOR TOTAL</th>
				</tr>
				<tr></tr>
				'.$t.'
				
				<tr class = "salto"><td ></br></td></tr>
				
				<tr class = "salto"><td ></br></td></tr>
				<tr class = "salto">
					<td colspan = "4"></td>
					
					<th style = "border:1px solid black;" >
						<strong>SUBTOTAL</strong>
					</th>
					<td style = "border:1px solid black;">
						<table width = "100%">
							<tr>
								<td align = "left" width = "2%">$</td>
								<td align = "right"><strong>'.number_format($antes_iva).'</strong></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class = "salto">
					<td colspan = "4"></td>
					
					<th style = "border:1px solid black;" >
						<strong>IVA</strong>
					</th>
					<td style = "border:1px solid black;">
						<table width = "100%">
							<tr>
								<td align = "left" width = "2%">$</td>
								<td align = "right"><strong>'.number_format($iva_cum).'</strong></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class = "salto">
					<td colspan = "4"></td>
					
					<th style = "border:1px solid black;" >
						<strong>TOTAL</strong>
					</th>
					<td style = "border:1px solid black;">
						<table width = "100%">
							<tr>
								<td align = "left" width = "2%">$</td>
								<td align = "right"><strong>'.number_format($antes_iva+$iva_cum).'</strong></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<tr class = "salto">
				<td>
					<table  style = "font-size:12px;">
						<tr>
							<td style = "padding-bottom:20px;padding-right:10px;">DIRECTOR DE COMPRAS</td>
							<td style = "vertical-align:top;">_________________________________________________</td>
						</tr>
						<tr></tr>
						<tr>
							<td style = "padding-bottom:20px;padding-right:10px;">ACEPTADO PROVEEDOR</td>
							<td>_________________________________________________</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr class = "salto"><td ></br></td></tr>
			<tr class = "salto"><td ></br></td></tr>
			<tr class = "salto">
				<td colspan = "6" style = "text-align:justify;font-weight:bold;font-size:8px;">'.$observaci.'</td>
			</tr>
			
			</table>
			</body>
		</html>';
	$pdf = new mPDF('utf-8', array(279,210));
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(true, 12);
 
	$pdf->AddPage("P");
	$pdf->SetFont('Arial','B',10);
	$stylesheet = file_get_contents('ppto.css');
	$pdf->WriteHTML($stylesheet,1);
	$pdf->WriteHTML($html);
	//$mpdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="a" suppress="off" />');

	$pdf->Output('OC 1.pdf', 'I');
?>