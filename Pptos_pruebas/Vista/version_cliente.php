<?php
	include("../Controller/Conexion.php");
	
	if($_POST['ppto'] == 0){
		echo "NO SE HA ENCONTRADO NINGÚN NÚMERO DE PRESUPUESTO, REINICIE SU SESIÓN !";
	}else{
		$version = $_POST['version'];
		$versionc = $_POST['versionc'];
		
		
		$ppto = $_POST['ppto'];
		
		$contador = mysql_query("select count(ppto) as numero from versiones_presup where ppto = '$ppto'");
		$c = 0;
		while($row = mysql_fetch_array($contador)){
			$c = $row['numero'];
		}
		$version_nueva = $c + 1;
		
		$sql = mysql_query("select versionc from versiones_presup where ppto = '$ppto' and version = '$version'");
		$v = 0;
		while($row = mysql_fetch_array($sql)){
			$v = $row['versionc'];
		}
		$versioncn = $v + 1;
		
		$usuario = $_POST['user'];
		$fecha = date("Y-m-d h:i:s");
		mysql_query("START TRANSACTION");
			mysql_query("insert into versiones_presup(ppto,version,fecha,user,versionc) values('$ppto','$version_nueva','$fecha','$usuario',$versionc)");
			mysql_query("update cabpresup set vi = '$version_nueva', vc = $versioncn where codigo_presup = '$ppto'");
			$slq_items = mysql_query("select pk_item,proveedor,celula,dias,q,descripcion,val_item,iva_item,fecha_ant,por_ant,cliente,val_desde_item,por_prov,usuario,fecha_registro,ppto,asoc,num_interno,vc
			from itempresup where pk_orden = '0' and ppto = '$ppto' and vi = '$version' and vc = '$versionc'");
			while($row = mysql_fetch_array($slq_items)){
				mysql_query("insert into itempresup(pk_item,proveedor,celula,dias,q,descripcion,val_item,iva_item,fecha_ant,por_ant,cliente,val_desde_item,por_prov,usuario,fecha_registro,ppto,asoc,num_interno,vi,vc) values
				('".$row['pk_item']."','".$row['proveedor']."','".$row['celula']."','".$row['dias']."','".$row['q']."','".$row['descripcion']."','".$row['val_item']."','".$row['iva_item']."','".$row['fecha_ant']."','".$row['por_ant'].
				"','".$row['cliente']."','".$row['val_desde_item']."','".$row['por_prov']."','".$usuario."','".date("Y-m-d h:i:s")."','".$row['ppto']."','".$row['asoc']."','".$row['num_interno']."','".$version_nueva."','".$versioncn."')");
				
			}
		mysql_query("COMMIT");
	}
?>
