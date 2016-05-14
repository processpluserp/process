<?php
	$temp_fecha = $_POST['fecha']." 00:00:00";
	//8 días base
	$nuevafecha  = strtotime ( '+8 day' , strtotime ( $temp_fecha ) );
	echo date ( 'Y-m-d' , $nuevafecha );
?>