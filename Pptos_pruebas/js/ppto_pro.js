var id_items = [];
var formatNumber = {
	separador: ",", // separador para los miles
	sepDecimal: '.', // separador para los decimales
	formatear:function (num){
	num +='';
	var splitStr = num.split(',');
	var splitLeft = splitStr[0];
	var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
	var regx = /(\d+)(\d{3})/;
	while (regx.test(splitLeft)) {
		splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
	}
	return this.simbol + splitLeft  +splitRight;
	 },
	new:function(num, simbol){
	  this.simbol = simbol ||'';
	 return this.formatear(num);
	 }
}

function mostrar_cabecera(){
	$(".encabezado").toggle();
}

function limpiar_costo_interno(id){
	var val = $("#valor_interno"+id).val();
	if(val != ""){
		var valor = val.split(",");
		var val_final = "";
		for(var i = 0;i < valor.length; i++){
			val_final += ""+valor[i];
		}
		if($.isNumeric(val_final) == true){
			$("#valor_interno"+id).val(formatNumber.new(val_final));
			$("#h_valor_interno"+id).text(val_final);
		}else{
			alert("Solo se aceptan Números !");
			$("#valor_interno"+id).val(0);
			$("#h_valor_interno"+id).text(0);
		}
	}
}

function formatear_valor_costo_unitario(id){
	var val = $("#valor_interno"+id).val();
	if(val != ""){
		var valor = val.split(",");
		var val_final = "";
		for(var i = 0;i < valor.length; i++){
			val_final += ""+valor[i];
		}
		$("#valor_interno"+id).val(formatNumber.new(val_final));
		$("#h_valor_interno"+id).text(val_final);
	}
}

function formatear_valor_num_dias(id){
	var val = $("#dias"+id).val();
	$("#h_dias"+id).text(val);
	
}

function formatear_valor_num_cant(id){
	var val = $("#cant"+id).val();
	
	//$("#cant"+id).val((val));
	$("#h_cant"+id).text(val);
	
}

function limpiar_valor_costo_cliente(id){
	var val = $("#valor_costo_unitario_cliente"+id).val();
	if(val != ""){
		var valor = val.split(",");
		var val_final = "";
		for(var i = 0;i < valor.length; i++){
			val_final += ""+valor[i];
		}
		if($.isNumeric(val_final) == true){
			$("#valor_costo_unitario_cliente"+id).val(formatNumber.new(val_final));
			$("#val_cliente_interno"+id).text(val_final);
		}else{
			alert("Solo se aceptan Números !");
			$("#valor_costo_unitario_cliente"+id).val(0);
			$("#val_cliente_interno"+id).text(0);
		}
	}
}

function formatear_valor_costo_cliente(id){
	var val = $("#valor_costo_unitario_cliente"+id).val();
	if(val != ""){
		var valor = val.split(",");
		var val_final = "";
		for(var i = 0;i < valor.length; i++){
			val_final += ""+valor[i];
		}
		$("#valor_costo_unitario_cliente"+id).val(formatNumber.new(val_final));
		$("#val_cliente_interno"+id).text(val_final);
	}
}

function formatear_valor_por_vol(id){
	var val = $("#vol"+id).val();
	var valor = val.split(",");
	var val_final = "";
	for(var i = 0;i < valor.length; i++){
		val_final += ""+valor[i];
	}
	$("#vol"+id).val(formatNumber.new(val_final));
	$("#h_vol"+id).text(val_final);
}

function abrir_form_ant(){
	
}

function calcular_interno(i){
	
	//Cantidad.
	var cantidad = $("#h_cant"+i).text();
	
	//Número de días.
	var dias = $("#h_dias"+i).text();
	
	//Costo Interno
	var costo_interno = $("#h_valor_interno"+i).text();
	
	//Calculo el costo interno.
	var total_costo_interno = costo_interno*dias*cantidad;
	
	
	
	$("#valor_subtotal_item"+i).html(formatNumber.new(total_costo_interno));
	$("#h_subtotal_item"+i).html((total_costo_interno));
	
	var iva = (total_costo_interno*$("#iva"+i).text())/100;
	var vol = $("#h_vol"+i).text();
	var costo_vol = (total_costo_interno*vol)/100;
	
	var total_real_interno = total_costo_interno-costo_vol+iva;
	$("#costo_interno"+i).html(formatNumber.new(Math.round(total_real_interno*Math.pow(10,2))/Math.pow(10,2)));
	$("#h_costo_interno"+i).html((total_real_interno));
	
	var costo_cliente = $("#val_cliente_interno"+i).text();
	var total_item_cliente = costo_cliente*dias*cantidad;
	$("#costo_cliente"+i).html(formatNumber.new(total_item_cliente));
	$("#h_costo_cliente"+i).html((total_item_cliente));
	
	//Porcentaje de Rentabilidad sobre el item.
	var porcentaje = 0;
	if(total_item_cliente != 0){
		porcentaje = (total_item_cliente - total_real_interno)/total_item_cliente;
	}else{
		porcentaje = -1;
	}
	var flotante = parseFloat(porcentaje)*100;
	var resultado = Math.round(flotante*Math.pow(10,2))/Math.pow(10,2);
	$("#valor_rentabilidad_item"+i).html((total_item_cliente - total_real_interno));
	$("#valor_rent_item"+i).html(formatNumber.new(Math.round((total_item_cliente - total_real_interno)*Math.pow(10,2))/Math.pow(10,2)));
	
	if(resultado < 0){
		$("#por_rent_item"+i).html(((resultado)) + " %").css({"color":"red","font-wieght":"bold"});
	}else{
		$("#por_rent_item"+i).html(((resultado)) + " %").css({"color":"green","font-wieght":"bold"});
	}
	
	
	var total_costo_interno_sum = 0;
	$(".cost_interno_x").each(function(index){
		if($(this).text() == ""){
			
		}else{
			total_costo_interno_sum += parseFloat($(this).text());
		}
	});
	$("#sum_total_costo_interno").html(formatNumber.new(total_costo_interno_sum));
	
	var total_costo_interno_sum2 = 0;
	$(".externo_cliente").each(function(index){
		if($(this).text() == ""){
			
		}else{
			total_costo_interno_sum2 += parseFloat($(this).text());
		}
	});
	$("#sum_total_costo_externo").html(formatNumber.new(total_costo_interno_sum2));
	$("#valores_comisionables").html(formatNumber.new(total_costo_interno_sum2));
	
	
	var sum_subtotal = 0;
	$(".subtotal_items").each(function(index){
		if($(this).text() == ""){
			
		}else{
			sum_subtotal += parseFloat($("#h_subtotal_item"+index).text());
		}
		
	});
	$("#sum_subtotal_costo_interno").html(formatNumber.new(sum_subtotal));
	
	//TOTAL COMISIONES POR DESCUENTOS:
	var total_comisiones_por_descuentos = 0;
	$(".rentabilidad_item").each(function(index){
		if($(this).text() == ""){
			
		}else{
			total_comisiones_por_descuentos += parseFloat($(this).text());
		}
	});
	
	$("#total_comisiones_por_descuentos").html(formatNumber.new(total_comisiones_por_descuentos));
	
	
	
	//CUADRO DE IMPUESTOS
	//Valores No comisionables
	var cantidad = 0;
	
	//Número de días.
	var dias = 0;
	
	var costo_cliente = 0;
	var valores_no_comisionables = 0;
	$(".grupos").each(function(i){
		if($("#h_cant"+i).text() == ""){
			
		}else{
			//Cantidad.
			cantidad = $("#h_cant"+i).text();
			
			//Número de días.
			dias = $("#h_dias"+i).text();
			
			costo_cliente = $("#valor_costo_unitario_cliente"+i).text();
			valores_no_comisionables += costo_cliente*dias*cantidad;
		}
	});
	var total_costos_ejecucion = total_costo_interno_sum2 + valores_no_comisionables;
	$("#total_costos_ejecucion").html(formatNumber.new(total_costos_ejecucion));
	
	
	
	//Calcular Comision:
	var por_comi = parseFloat($("#val_comision").text());
	var operacion_comi = 0;
	if($("#tipo_comision").text() == 1){
		var temp = (100-por_comi)/100;
		operacion_comi = (total_costo_interno_sum2/temp)-total_costo_interno_sum2;
	}else{
		operacion_comi = 0;
	}
	$("#valor_comision_agencia").html(formatNumber.new( Math.round(operacion_comi*Math.pow(10,2))/Math.pow(10,2) ));
	$("#valor_comision_agencia2").html(formatNumber.new( Math.round(operacion_comi*Math.pow(10,2))/Math.pow(10,2) ));
	
	
	//Calculo de Imprevistos del ppto
	var por_imprevistos = $("#porcentaje_imprevistos").val();
	if(por_imprevistos == ""){
		por_imprevistos = 0;
	}
	var imprevistos_ppto = (total_costos_ejecucion*por_imprevistos)/100;
	$("#total_por_imprevistos").html(formatNumber.new(imprevistos_ppto));
	
	//Calculo Gastos Administrativos
	var gastos_admin = $("#por_gastos_admin").val();
	if(gastos_admin == ""){
		gastos_admin = 0;
	}
	var gastos_administrativos = (total_costos_ejecucion*gastos_admin)/100;
	
	
	//Total de la Actividad Parcial
	var total_actividad = ( gastos_administrativos + imprevistos_ppto + total_costos_ejecucion + operacion_comi);
	$("#total_actividad_inicial").html(formatNumber.new(  Math.round(( gastos_administrativos + imprevistos_ppto + total_costos_ejecucion + operacion_comi)*Math.pow(10,2))/Math.pow(10,2)  ));
	$("#valor_sin_iva_total").html(formatNumber.new(  Math.round(total_actividad*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//Utilidad Comercial:
	$("#utilidad_comercial").html(formatNumber.new(  Math.round(( operacion_comi + valores_no_comisionables + total_comisiones_por_descuentos)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//Volumen
	var volumen = ( operacion_comi + valores_no_comisionables + total_comisiones_por_descuentos)/( gastos_administrativos + imprevistos_ppto + total_costos_ejecucion + operacion_comi);
	$("#vol_ppto_vol").html(formatNumber.new(  Math.round((volumen*100)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	
	//RETENCION EN LA FUENTE
	var rete_fuente = parseFloat($("#por_rete_fuente").text());
	var valor_rete_fuente = ((total_actividad*rete_fuente)/100);
	$("#retencion_fuente").html(formatNumber.new(  Math.round(((total_actividad*rete_fuente)/100)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//IMPUESTOS ADICIONALES
	var por_impuestos_adicionales = parseFloat($("#por_impuestos_adicionales").text());
	var valor_impuestos_add = ((total_actividad*por_impuestos_adicionales)/100);
	$("#impuestos_adicionales").html(formatNumber.new(  Math.round((valor_impuestos_add)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//ICA
	var por_ica = parseFloat($("#por_ica_val").text())/1000;
	var valor_ica = ((total_actividad*por_ica));
	$("#val_ica").html(formatNumber.new(  Math.round((valor_ica)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//CREE
	var por_cree = parseFloat($("#por_cree_val").text());
	var valor_cree = ((total_actividad*por_cree)/100);
	$("#val_cree").html(formatNumber.new(  Math.round((valor_cree)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//4 x 1000
	var por_cuatro_por_mil = 4/1000;
	var valor_cuatro_por_mil = ((total_actividad*por_cuatro_por_mil));
	$("#cuatro_por_mil").html(formatNumber.new(  Math.round((valor_cuatro_por_mil)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	
	
	//Cheques y Transferencias:
	var num_proveedores = 0;
	$(".proveedores select").each(function(index){
		if($(this).val() != 0){
			num_proveedores++;
		}
	});
	$("#num_chueques").html(num_proveedores);
	var valor_uni_cheque = parseFloat($("#uni_valor_cheques").text());
	var valor_total_cheques = valor_uni_cheque*num_proveedores;
	$("#cheques_val").html(formatNumber.new( valor_total_cheques ));
	
	//Por Factoring
	var por_factoring = parseFloat($("#por_factoring").val())
	var valor_factoring = ((total_actividad*por_factoring)/100);
	$("#val_factoring").html(formatNumber.new(  Math.round((valor_factoring)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//Intereses Bancarios
	var dias_c = parseFloat($("#pago_a_cliente").text()) * 0.043;
	$("#por_ant_banca").html( formatNumber.new(  Math.round((dias_c)*Math.pow(10,2))/Math.pow(10,2)  ) );
	var ant_clie = ((total_actividad*dias_c)/100);
	$("#val_ant_int_banc").html(formatNumber.new(  Math.round((ant_clie)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//DEL PROYECTO INTERESES BANCARIOS
	var por_pib = parseFloat($("#dpib").val())
	var valor_pib = ((total_actividad*por_pib)/100);
	$("#val_por_pro_banc").html(formatNumber.new(  Math.round((valor_pib)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//DEL PROYECTO INTERESES A 3ROS
	var por_dpi3 = parseFloat($("#dpi3").val())
	var valor_dpi3= ((total_actividad*por_dpi3)/100);
	$("#val_por_com_ter").html(formatNumber.new(  Math.round((valor_dpi3)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//TOTAL COSTOS FINANCIEROS E IMPUESTOS
	var total_costos_financiertos_impuestos = valor_dpi3+ valor_pib + ant_clie +  valor_factoring + valor_total_cheques + valor_cuatro_por_mil + valor_cree +  valor_ica + valor_impuestos_add + valor_rete_fuente;
	$("#total_cost_finan_imp").html(formatNumber.new(  Math.round((total_costos_financiertos_impuestos)*Math.pow(10,2))/Math.pow(10,2)  ));
	
	$("#utilidad_final").html(formatNumber.new(  Math.round(( ( ( operacion_comi + valores_no_comisionables + total_comisiones_por_descuentos)  ) - (total_costos_financiertos_impuestos))*Math.pow(10,2))/Math.pow(10,2)  ));
	
	//Porcentaje Utilidad
	var xc =  (( ( operacion_comi + valores_no_comisionables + total_comisiones_por_descuentos)  - (total_costos_financiertos_impuestos) ) / total_actividad)*100;
	if(total_actividad == 0){
		$("#por_utilidad").html("<table height = '100%' width = '100%'><tr><td style = 'vertical-align:middle;font-size:100%;' align = 'center'>" + formatNumber.new(  Math.round((0)*Math.pow(10,2))/Math.pow(10,2)  ) + " %</td></tr></table>");
	}else{
		if(parseFloat($("#por_min_val_apro").text()) > xc){
			$("#por_utilidad").html("<table height = '100%' width = '100%'><tr><td style = 'vertical-align:middle;font-size:100%;' align = 'center'>" + formatNumber.new(  Math.round((xc)*Math.pow(10,2))/Math.pow(10,2)  ) + " %</td></tr></table>").css({"color":"red"});
		}else{
			$("#por_utilidad").html("<table height = '100%' width = '100%'><tr><td style = 'vertical-align:middle;font-size:100%;' align = 'center'>" + formatNumber.new(  Math.round((xc)*Math.pow(10,2))/Math.pow(10,2)  ) + " %</td></tr></table>").css({"color":"green"});
		}
		
	}
	$("#por_real_utlidad").html(formatNumber.new(  Math.round((xc)*Math.pow(10,2))/Math.pow(10,2)  ));
	operacion_asoc(i);
	
	
}

function calcular_impuestos_ppto(){
	var total_costo_interno_sum2 = 0;
	$(".externo_cliente").each(function(index){
		if($(this).text() == ""){
			
		}else{
			total_costo_interno_sum2 += parseFloat($(this).text());
		}
	});
	$("#sum_total_costo_externo").html(formatNumber.new(total_costo_interno_sum2));
	$("#valores_comisionables").html(formatNumber.new(total_costo_interno_sum2));
	
	//Valores No comisionables
	var cantidad = 0;
	
	//Número de días.
	var dias = 0;
	
	var costo_cliente = 0;
	var valores_no_comisionables = 0;
	$(".grupos").each(function(i){
		if($("#h_cant"+i).text() == ""){
			
		}else{
			//Cantidad.
			cantidad = $("#h_cant"+i).text();
			
			//Número de días.
			dias = $("#h_dias"+i).text();
			
			costo_cliente = $("#val_cliente_interno"+i).text();
			valores_no_comisionables += costo_cliente*dias*cantidad;
		}
	});
	
	//CUADRO DE IMPUESTOS
	var total_costos_ejecucion = total_costo_interno_sum2 + 0;
	$("#total_costos_ejecucion").html(formatNumber.new(total_costos_ejecucion));
	
	//Calculo de Imprevistos del ppto
	var por_imprevistos = $("#porcentaje_imprevistos").val();
	if(por_imprevistos == ""){
		por_imprevistos = 0;
	}
	var imprevistos_ppto = (total_costos_ejecucion*por_imprevistos)/100;
	$("#total_por_imprevistos").html(formatNumber.new(imprevistos_ppto));
	
	//Calculo Gastos Administrativos
	var gastos_admin = $("#por_gastos_admin").val();
	if(gastos_admin == ""){
		gastos_admin = 0;
	}
	var gastos_administrativos = (total_costos_ejecucion*gastos_admin)/100;
	$("#total_gastos_administrativos").html(formatNumber.new(gastos_administrativos));
	
	
	calcular_interno();
}

var sumaFecha = function(d, fecha)
{
 var Fecha = new Date();
 var sFecha = fecha ||  (Fecha.getFullYear() + "-" + (Fecha.getMonth() +1) +"-"+Fecha.getDate()) ;
 var sep = sFecha.indexOf('-') != -1 ? '-' : '/'; 
 var aFecha = sFecha.split(sep);
 var fecha = aFecha[2]+'-'+aFecha[1]+'-'+aFecha[0];
 fecha= new Date(fecha);
 fecha.setDate(fecha.getDate()+parseInt(d));
 var anno=fecha.getFullYear();
 var mes= fecha.getMonth()+1;
 var dia= fecha.getDate();
 mes = (mes < 10) ? ("0" + mes) : mes;
 dia = (dia < 10) ? ("0" + dia) : dia;
 var fechaFinal = dia+sep+mes+sep+anno;
 return (fechaFinal);
 }
function calcular_valor_anticipo(id){
	//valoranticipodisponible
	var anticipo = $("#contValueAntDisponible"+id).text() * parseFloat($("#inpuTextPorAnt"+id).val());
	$("#calculoAntSolicitado"+id).html(anticipo);
}
$(document).ready(function(){
	
	$( "#tabs_aprobaciones" ).tabs();
	
	$.datepicker.regional['es'] = {
		 closeText: 'Cerrar',
		 prevText: '<Ant',
		 nextText: 'Sig>',
		 currentText: 'Hoy',
		 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
		 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
		 weekHeader: 'Sm',
		 firstDay: 1,
		 isRTL: false,
		 showMonthAfterYear: false,
		 yearSuffix: ''
		 };
	$.datepicker.setDefaults($.datepicker.regional['es']);
	function nonWorkingDates(date){
		var day = date.getDay(), Sunday = 0, Monday = 1, Tuesday = 2, Wednesday = 3, Thursday = 4, Friday = 5, Saturday = 6;
		var closedDays = [[Saturday], [Sunday]];
		return [true];
	}
	$("#fecha_entrega_anticipo,#vigencia_inicial_op,#vigencia_final_op").datepicker({ dateFormat: 'yy-mm-dd',beforeShowDay: nonWorkingDates,	numberOfMonths: 1,	minDate: '0',firstDay: 1  });
	
	$("#fecha_entrega_anticipo").on('change',function(){
		$("#fecha_maxima_legalizacion").text(sumaFecha(8,$("#fecha_entrega_anticipo").val()));
	});
	$("body").on('load',function(){
		$(".proveedores ").each(function(index){
			calcular_interno(index);
		});
	});
	$(".proveedores ").each(function(index){
		calcular_interno(index);
	});
	$(".ui-dialog-titlebar").hide();
	$(".ui-widget-overlay").css({
		position: 'fixed',
		top: '0'
	});

	//get the current popup position of the dialog box
	pos = $(".ui-dialog").position();

	//adjust the dialog box so that it scrolls as you scroll the page
	$(".ui-dialog").css({
		position: 'fixed'
	});
	var alto = $(window).height();
	var ancho_x = $(window).width();
	var ancho_por = (ancho_x*100)/100;
	var ancho_por2 = (ancho_x*99)/100;
	var x = (alto*98)/100;
	var alto_h = (alto*60)/100;
	
	var alto_hh = (alto*99)/100;
	var alto_hhh = (alto*35)/100;
	$("#ventana_ordenes,#visual_ppto,#ventana_aprobaciones,#ventana_form_op").dialog({
	  autoOpen: false,
      height: x,
      width: ancho_por,
	  open: function () {
        $(".ui-widget-overlay").css({
            opacity: 1,
            filter: "Alpha(Opacity=100)",
            backgroundColor: "black"
        });
		$(".ui-draggable").css({
        	top:"2px"
        });
    },
	  modal:true,
	  resizable: false
    });
	
	$("#v_generar_op,#anticipos_ventana,#visual_ppto_version").dialog({
      autoOpen: false,
      height: x,
      width: ancho_por,
	  open: function () {
        $(".ui-widget-overlay").css({
            opacity: 1,
            filter: "Alpha(Opacity=100)",
            backgroundColor: "black"
        });
        $(".ui-draggable").css({
        	top:"2px"
        });
		},
      modal: true,
	  resizable: false
    });
	$("#ventana_versiones,#comentarios_ppto,#comentarios_anticipos").dialog({
      autoOpen: false,
      height: "450",
      width: "500",
	  open: function () {
        $(".ui-widget-overlay").css({
            opacity: 1,
            filter: "Alpha(Opacity=100)",
            backgroundColor: "black"
        });
		},
      modal: true,
	  resizable: false
    });
	$("#ventana_observaciones_bloqueo_ppto").dialog({
      autoOpen: false,
      height: "450",
      width: "600",
	  open: function () {
        $(".ui-widget-overlay").css({
            opacity: 1,
            filter: "Alpha(Opacity=100)",
            backgroundColor: "black"
        });
		},
      modal: true,
	  resizable: false
    });
	$("#generar_op").on('click',function(){
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:25,ppto:$("#codigo_ppto").text()},
			type:'post',
			success:function(data){
				$("#listado_proveedores").html(data);
			}
		});
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:27,ppto:$("#codigo_ppto").text()},
			type:'post',
			success:function(data){
				$("#nota_op").html(data);
			}
		});
		$(".scroll").css({"overflow-y":"hidden"});
		$(".tabla_gen_orden").css({"display":"block"});
		$("#v_generar_op").dialog('open');
	});
	
	$("#abrir_ant").on('click',function(){
		$(".scroll").css({"overflow":"hidden"});
		$("#name_cliente_ant").text($(".nombre_legal_cliente_ppto").text());
		$("#num_ppto_ant").text("AG: " + $(".ppto_ag").text() + " - CL: "+ $(".ppto_cl").text() );
		$("#unidad_negocio_ant").text($(".ceco_ppto").text());
		$("#anticipos_ventana").dialog('open');
	});
	
	$(".ui-dialog-titlebar").hide();
});

function cancelar_oc(orden){
	if (confirm("Realmente desea cancelar la orden "+orden+"?")) {
		// Respuesta afirmativa...
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:62,orden:orden,ppto:$("#codigo_ppto").text()},
			type:'post',
			success:function(d){
				alert("ORDEN ELIMINADA");
				location.reload();
			}
		});
	}else{
		alert("no");
	}
}

function generar_pdf_externo(ppto,version,version_externa){
	var url = "pdf_ppto.php?ppto="+ppto;
	if(confirm("Usted está trabajando en la versión de Cliente # "+version_externa+" del Presupuesto Interno # "+ppto+",¿Desea generar una nueva versión para el Cliente?")){
		$.ajax({
			url:'version_cliente.php',
			data:{ppto:ppto,version:version,versionc:version_externa,user:$("#codigo_usuario").text()},
			type:'post',
			success:function(d){
				window.open(url,'_blank');
				location.reload();
			}
		});
	}else{
		window.open(url,'_blank');
	}
}

function cargar_versiones_ppto(ppto,version){
	/*$.ajax({
		url:'visual_presupuesto_version.php',
		data:{ppto:ppto,version:version},
		type:'post',
		success:function(d){
			$("#visual_ppto_version").html(d);
			$("#visual_ppto_version").dialog('open');
		}
	});*/
	$.ajax({
		url:'visual_presupuesto_version.php',
		data:{ppto:ppto,version:$('input:radio[name=num_version_ppto]:checked').val()},
		type:'post',
		success:function(d){
			location.reload();
		}
	});
}

function generar_nueva_version(ppto,version,versionc){
	
	if(confirm("Usted está trabajando en la Versión "+version+" del Presupuesto Interno # "+ppto+",¿Desea generar una nueva Versión de este Presupuesto? ")){
		$.ajax({
			url:'versiones_ppto.php',
			data:{ppto:ppto,version:version,user:$("#codigo_usuario").text(),versionc:version},
			type:'post',
			success:function(d){
				alert("Nueva versión generada! "+d);
				location.reload();
			}
		});
	}else{
		
	}
}
function mostrar_versiones_ppto(ppto,version){
	$(".scroll").css({"overflow":"hidden"});
	$.ajax({
		url:'muestra_versiones.php',
		data:{ppto:ppto,version:version},
		type:'post',
		success:function(d){
			$("#ventana_versiones").html(d);
		}
	});
	$("#ventana_versiones").dialog('open');
}


function mostrar_versiones_cliente_ppto(ppto,version){
	$(".scroll").css({"overflow":"hidden"});
	$.ajax({
		url:'muestra_versiones_cl.php',
		data:{ppto:ppto,version:version},
		type:'post',
		success:function(d){
			$("#ventana_versiones").html(d);
		}
	});
	$("#ventana_versiones").dialog('open');
}

function buscar_ordenes(){
	$.ajax({
		url:'busqueda_produccion.php',
		data:{turno:63,ppto:$("#codigo_ppto").text()},
		type:'post',
		success:function(d){
			$("#contenedor_list_ordenes").html(d).show("fast");
		}
	});
}


function cerrar_ventana_abierta(id_ventana){
	//$("#ventana_versiones").dialog('close');
	$("#"+id_ventana).dialog('close');
}

//Envio de Aprobación de Ppto
function  enviar_aprobacion_ppto(ppto,vi,vc,ppto_ext){
	if(confirm("¿Desea agregar comentarios sobre este presupuesto antes de ser enviado a aprobación?")){
		$(".scroll").css({"overflow":"hidden"});
		$("#comentarios_ppto").dialog('open');
		
	}else{
		if(confirm("Tenga en cuenta que se enviará su presupuesto a aprobación y mientras esté en este proceso usted no podrá modificar dicho presupuesto.\n¿Está seguro de enviarlo?")){
			enviar_aprobacion_ppto_text();
		}else{
			
		}
		
	}
	/*if(parseFloat($("#por_min_val_apro").text()) > parseFloat($("#por_real_utlidad").text()) ){
		if(confirm("¿Su Presupuesto está por debajo del "+$("#por_min_val_apro").text()+", desea agregar comentarios ?")){
			$("#comentarios_ppto").dialog('open');
		}else{
			
		}
	}else{
		if(confirm("¿Desea agregar comentarios ?")){
			$("#comentarios_ppto").dialog('open');
		}else{
			
		}
	}*/
	
}


function nueva_version_por_bloqueo(ppto,vi,vc){
	alert("Se generará una nueva versión !");
	$.ajax({
		url:'nversion_bloquedo.php',
		data:{turno:1,ppto:ppto,vi:vi,vc:vc,user:$("#codigo_usuario").text()},
		type:'post',
		success:function(d){
			location.reload();
		}
	});
}

function confirm_rechazo(ppto,vi,vc,id){
	alert("Especifíque las razones por las cuales no aprueba este presupuesto");
	$("#num_ppto").text(ppto);
	$("#id_aprohisto").text(vi);
	$("#vc_ppto").text(vc);
	$("#vi_ppto").text(vi);
	$("#comentarios_ppto").dialog('open');
}

function alert_item_ordenado(estado){
	if(estado == 3){
		alert("Este item se encuentra bloqueado mientras está en proceso de aprobación !");
	}else if(estado == 100){
		alert("Debido a que su presupuesto fue rechazado, este item se encuentra bloqueado !");
	}else if(estado == 6){
		alert("Este item se encuentra ordenado !");
	}else if(estado == 4){
		alert("Este presupuesto se encuentra en proceso de aprobación por parte del Cliente.\nMientras no esté aprobado no podrá modificarlo.");
	}
	
}

function enviar_aprobacion_ppto_text(){
	var contenido_ppto = [];
	var contenido_item = [];
	var contador = 0;
	$(".codigo_item").each(function(index){
		var id = $(this).text();
		contenido_item.push(id);
		contenido_item.push($("#grupo"+id).val());
		contenido_item.push($("#itemppto"+id).val());
		contenido_item.push($("#descripcionitem"+id).val());
		contenido_item.push($("#proveedoritem"+id).val());
		contenido_item.push($(".diasitem"+id).text());
		contenido_item.push($(".cantidadiem"+id).text());
		
		contenido_item.push($(".valorinternoitem"+id).text());
		
		contenido_item.push($(".ivaitem"+id).val());
		contenido_item.push($(".volumenproveedor"+id).text());
		
		
		contenido_item.push($(".valorinternocliente"+id).text());
		
		contenido_item.push($("#info_vi_text").text());
		contenido_item.push($("#info_vc_text").text());
		
		contenido_ppto.push(contenido_item);
		contenido_item = [];
		
	});
	/*for(var i = 0; i < contenido_ppto.length; i++){
		for(var x = 0; x < contenido_ppto[i].length; x++){
			//console.log(contenido_ppto[i][x]);
		}
		//console.log("</br>");
	}*/
	$.ajax({
		url:'busqueda_produccion.php',
		data:{contenido:contenido_ppto,turno:64},
		type:'post',
		success:function(d){
			$.ajax({
				url:'notificar_list_aprobacion.php',
				data:{ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text(),user:$("#codigo_usuario").text(),ppto_ext:$("#info_vc_ext").text(),porcentaje:$("#por_real_utlidad").text(),descc:$("#comentarios_ppto_text").val()},
				type:'post',
				success:function(d){
					$("#comentarios_ppto_text").val("");
					if(d == true){
						alert("Su presupuesto fue enviado a aprobación ! \nRecuerde que mientras esté en este proceso no podrá modificarlo.");
					}else{
						alert("Ha ocurrido un error de comunicación, por favor comuníquese con el administrador de la red.");
					}
					location.reload();
				}
			});
		}
	});
}



function abrir_visual_ppto(id){
	$.ajax({
		url:'visual_ppto_apro.php',
		data:{id:id},
		type:'post',
		success:function(d){
			$("#visual_ppto").html(d);
			$(".scroll").css({"overflow":"hidden"});
			$(".proveedores ").each(function(index){
				calcular_interno(index);
			});
			$("#visual_ppto").dialog('open');
		}
		
	});
}

function abrir_visual_anticipo(id,pen){
	$.ajax({
		url:'visual_apro_anticipos.php',
		data:{id:id,pen:pen},
		type:'post',
		success:function(d){
			$("#visual_ppto").html(d);
			$(".scroll").css({"overflow":"hidden"});
			$("#visual_ppto").dialog('open');
		}
	});
}

function cerrar_ventana_gen(){
	$("#visual_ppto").dialog('close');
}

//APROBAR Ppto
function aprobar_ppto(ppto,vi,vc,id,up_bottom){
	$.ajax({
		url:'aprobaciones_ppto.php',
		data:{ppto:ppto,vi:vi,vc:vc,estado:1,id:id,user:$("#user_html").text(),up_bottom:up_bottom},
		type:'post',
		success:function(d){
			alert(d);
			$("#visual_ppto").dialog('close');
			$.ajax({
				url:'estructura_mod_aprobaciones.php',
				data:{user:$("#user_html").text()},
				type:'post',
				success:function(d){
					$("#ventana_aprobaciones").html(d);
				}
			});
		}
	});
}

function abrir_ventana_aprobaciones(){
	$.ajax({
		url:'estructura_mod_aprobaciones.php',
		data:{user:$("#user_html").text(),perfil:$("#perfil_usuario").text()},
		type:'post',
		success:function(d){
			$("#ventana_aprobaciones").html(d);
		}
	});
	$("#ventana_aprobaciones").dialog("open");
	$(".scroll").css({"overflow":"hidden"});
}

function cerrar_ventana_comentarios_aprobaciones(){
	$("#comentarios_ppto_text").val("");
	$("#comentarios_ppto").dialog('close');
}


function alert_razon_bloqueo(mens){
	$.ajax({
		url:'mensaje_bloqueo_ppto.php',
		data:{ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text()},
		type:'post',
		success:function(d){
			$("#ventana_observaciones_bloqueo_ppto").html(d);
			$("#ventana_observaciones_bloqueo_ppto").dialog('open');
			
		}
	});
}
function no_aprobar_ppto_text(){
	$.ajax({
		url:'noaprobaciones_ppto.php',
		data:{ppto:$("#num_ppto").text(),vi:$("#vi_ppto").text(),vc:$("#vc_ppto").text(),estado:0,id:$("#id_aprohisto").text(),user:$("#user_html").text(),descc:$("#comentarios_ppto_text").val()},
		type:'post',
		success:function(d){
			$("#comentarios_ppto_text").val("");
			$("#comentarios_ppto").dialog('close');
			$("#visual_ppto").dialog('close');
			alert(d);
			$.ajax({
				url:'estructura_mod_aprobaciones.php',
				data:{user:$("#user_html").text()},
				type:'post',
				success:function(d){
					$("#ventana_aprobaciones").html(d);
				}
			});
		}
	});
}

function abrir_list_oc(ppto){
		$(".tabla_ventana_ordenes").css({"display":"block"});
		$("#ventana_ordenes").dialog('open');
		$(".scroll").css({"overflow":"hidden"});
		buscar_ordenes();
	}
function abrir_lista_items(i){
	
	if($("#nombre_item"+i).val() == ""){
		$("#listado_items"+i).html("");
		$("#listado_items"+i).css({'width':"0%","height":"0x","overflow":"hidden"});
	}else{
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:61,name:$("#nombre_item"+i).val(),i:i},
			type:'post',
			success:function(data){
				$("#listado_items"+i).css({'width':"100%","height":"150px","overflow":"scroll","font-size":"12px"});
				$("#listado_items"+i).html(data);
			}
		});
	}
}	
function guardar_array_id(id,i){
	
	$("#nombre_item"+i).val($("#xxnameitem"+id).text());
	
	$("#listado_items"+i).css({'width':"0px","height":"0px","overflow":"hidden","background-color":"red"});
	//$("#listado_items"+i).html("");
	
}


function operacion_asoc(i){
	var cantidad = $("#h_cant"+i).text();
	
	//Número de días.
	var dias = $("#h_dias"+i).text();
	
	//Costo Interno
	var costo_interno = $("#h_valor_interno"+i).text();
	
	
	
	
	//Calculo el costo interno.
	var total_costo_interno = costo_interno*dias*cantidad;
	var iva = (total_costo_interno*$("#iva"+i).val())/100;
	var vol = $("#h_vol"+i).text();
	var costo_vol = (total_costo_interno*vol)/100;
	
	var total_real_interno = total_costo_interno;
	$("#asocitem"+i).html("<td colspan = '6'></td><td colspan = '5'><img src = '../images/iconos/bolsa.png' width = '25px' onclick = 'adicionar_items_asoc("+i+")' /><td></td><td align = 'right'style = 'padding-right:5px;'>SALDO</td><td class = 'border_table'><table width = '100%'><tr><td>$</td><td align = 'right' id = 'saldo_item_format"+i+"'>"+formatNumber.new(total_real_interno)+"</td></tr></table><span class = 'hidde' id = 'xsaldo_itemp"+i+"'>"+total_real_interno+"</span></td>");
}
function mostrar_asoc_items(i,v){
	if(v == 1){
		alert("No se pueden asociar items mientras estén ordenados.");
	}else{
		var cantidad = $("#h_cant"+i).text();
	
		//Número de días.
		var dias = $("#h_dias"+i).text();
		
		//Costo Interno
		var costo_interno = $("#h_valor_interno"+i).text();
			
		//Calculo el costo interno.
		var total_costo_interno = costo_interno*dias*cantidad;
		var costo_vol = total_costo_interno;
		
		var total_real_interno = total_costo_interno;
		$("#asocitem"+i).html("<td colspan = '6'></td><td><span >Adicionar<img src = '../images/iconos/bolsa.png' width = '25px' onclick = 'adicionar_items_asoc("+i+")' /></span><td colspan = '6' align = 'right'style = 'padding-right:5px;'>SALDO</td><td class = 'border_table'><table width = '100%'><tr><td>$</td><td align = 'right' id = 'saldo_item_format"+i+"'>"+formatNumber.new(total_real_interno)+"</td></tr></table><span class = 'hidde' id = 'xsaldo_itemp"+i+"'>"+total_real_interno+"</span></td>").toggle();
		recalcular_bolsa(i);
		$(".hijos_asoc"+i).toggle();
		
	}
	
	
}

function formatear_valor_num_dias_asoc(id,temp){
	var val = $("#"+temp+"dias_asoc"+id).val();
	
	$("#"+temp+"dias_asoc"+id).val(val);
	$("#"+temp+"h_dias_asoc"+id).text(val);
}

function formatear_valor_num_cant_asoc(id,temp){
	var val = $("#"+temp+"cant_asoc"+id).val();
	
	$("#"+temp+"cant_asoc"+id).val(val);
	$("#"+temp+"h_cant_asoc"+id).text(val);
}

function limpiar_valor_unitario_asoc(id,temp){
	var val = $("#"+temp+"valor_interno_asoc"+id).val();
	if(val != ""){
		var valor = val.split(",");
		var val_final = "";
		for(var i = 0;i < valor.length; i++){
			val_final += ""+valor[i];
		}
		if($.isNumeric(val_final) == true){
			$("#"+temp+"valor_interno_asoc"+id).val(formatNumber.new(val_final));
			$("#"+temp+"h_valor_interno_asoc"+id).text(val_final);
		}else{
			alert("Solo se aceptan Números !");
			$("#"+temp+"valor_interno_asoc"+id).val(0);
			$("#"+temp+"h_valor_interno_asoc"+id).text(0);
		}
	}
}

function formatear_valor_num_interno_asoc(id,temp){
	var val = $("#"+temp+"valor_interno_asoc"+id).val();
	$("#"+temp+"valor_interno_asoc"+id).val(val);
	$("#"+temp+"h_valor_interno_asoc"+id).text(val);
}

function calcular_asociados(i,t){
	$("tr[data-dep='"+i+"'] img").each(function(index){
		var d = $("#"+t+"h_dias_asoc"+i).text();
		var c = $("#"+t+"h_cant_asoc"+i).text();
		var v = $("#"+t+"h_valor_interno_asoc"+i).text();
		var total = v*c*d;
		$("#"+t+"h_costo_interno_asoc"+i).html(total);
		$("#"+t+"costo_interno_asoc"+i).html(formatNumber.new(total));
	});
	recalcular_bolsa(i);
}

function recalcular_bolsa(i){
	var bolsa = ($("#xsaldo_itemp"+i).text());
	var acum = 0;
	$("tr[data-dep='"+i+"'] img").each(function(index){
		t = index+1;
		var d = $("#"+t+"h_dias_asoc"+i).text();
		var c = $("#"+t+"h_cant_asoc"+i).text();
		var v = $("#"+t+"h_valor_interno_asoc"+i).text();
		acum+=(v*c*d);
	});
	var total = bolsa-(acum);
	if(total < 0){
		$("#saldo_item_format"+i).css({"color":"red","font-wieght":"bold"});
	}else{
		$("#saldo_item_format"+i).css({"color":"green","font-wieght":"bold"});
	}
	$("#saldo_item_format"+i).html(formatNumber.new(total));
	console.log(acum);
	$("#total_asociado_grupo"+i).html(formatNumber.new(acum));
	
	var total_asociados = 0;
	$(".proveedores ").each(function(index){
		var ii = index;
		$("tr[data-dep='"+ii+"'] img").each(function(indexx){
			var t = indexx+1;
			var d = $("#"+t+"h_dias_asoc"+i).text();
			var c = $("#"+t+"h_cant_asoc"+i).text();
			var v = $("#"+t+"h_valor_interno_asoc"+i).text();
			total_asociados+=(v*c*d);
		});
	});
	$("#sobrante_asoc").html(formatNumber.new(total_asociados));
}

function adicionar_items_asoc(i){
	var temp = 0;
	var id = $("#olditem"+i).text();
	//$("tr[data-dep='"+i+"'] img").each(function(index){
	$("tr[data-dep='"+i+"'] img").each(function(index){
		temp++;
	});
	
	if(temp == 0){
		temp = 1;
		$.ajax({
			url:'nuevo_item_asoc.php',
			data:{i:i,aux:temp,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text(),id:id},
			type:'post',
			success:function(d){
				var text = "<tr class = 'hijos_asoc"+i+"' data-dep='"+i+"' ><td colspan = '13' align = 'right'><strong>TOTAL</strong></td><td style = 'font-weight:bold;'><table width = '100%'><tr><td>$</td><td align = 'right' id = 'total_asociado_grupo"+i+"'>0</td></tr></table></td></tr>";
				$("#asocitem"+i).after("<tr><th colspan = '7'></th><th>Nombre Item</th><th>Descripción</th><th>Proveedor</th><th>Días</th><th>Cant.</th><th>$ Valor Unt.</th></tr>"+d+text);
			}
		});
		recalcular_bolsa(i);
	}else{
		temp++;
		var aux = temp-1;
		$.ajax({
			url:'nuevo_item_asoc.php',
			data:{i:i,aux:temp,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text(),id:id},
			type:'post',
			success:function(d){
				$("#"+aux+"hijo"+i).after(d);
			}
		});
		
	}
	$("#provee"+i).hide("fast");
	
}

function eliminar_asoc(i,temp){
	$("#"+temp+"hijo"+i).remove();
	var x = 0;
	$("tr[data-dep='"+i+"'] img").each(function(index){
		x++;
	})
	if(x > 0){
		$("#provee"+i).hide("fast");
	}else{
		$("#provee"+i).show("fast");
	}
}

function eliminar_item_creado(){
	var uno = 0;
	var valor_id = 0;
	$("input[name='grupo_sel']:checked").each(function(){
		uno++;
		console.log($(this).val());
		valor_id = $(this).val();
	});
	var id = "";
	if(uno != 1){
		alert("Solo debe seleccionar un elemento para este función!");
	}else{
		id = $("#olditem"+valor_id).text();
		$.ajax({
			url:'eliminar_item_selected.php',
			data:{id:id,turno:1},
			type:'post',
			success:function(d){
				$("."+id).remove();
				$(".proveedores ").each(function(index){
					calcular_interno(index);
				});
			}
		});
	}
	
}

function eliminar_item_asoc_bd(id){
	$.ajax({
			url:'eliminar_item_selected.php',
			data:{id:id,turno:2},
			type:'post',
			success:function(d){
				$("."+id).remove();
				$(".proveedores").each(function(index){
					calcular_interno(index);
				});
			}
		});
}

function eliminar_item_by_id(id,i){
	var i = 0;
	$(".items_ppto_pro").each(function(index){
		i++;
	});
	if(i == 1){
		alert("Un presupuesto mínimo debe tener un item.\nPara eliminar este, adicione otro item para poder eliminarlo.");
	}else{
		if(confirm("¿Está seguro de eliminar este item ?")){
			$.ajax({
				url:'eliminar_item_ppto.php',
				data:{id:id},
				type:'post',
				success:function(d){
					$("."+id).remove();
					$("#asocitem"+i).remove();
				}
			});
		}else{
			limpiar_checkbox();
		}
	}
	
}

function addicionar_nuevo_item(){
	var uno = 0;
	var valor_id = 0;
	$("input[name='grupo_sel']:checked").each(function(){
		uno++;
		valor_id = $(this).val();
	});
	
	limpiar_checkbox();
	
	var val = 0;
	$(".proveedores").each(function(index){
		val++;
	});
	var temp = val;
	
	
	if (uno == 0){
		var val = 0;
		var indice = 0;
		$(".proveedores").each(function(index){
			val++;
			indice = index;
			console.log($("#olditem"+index).text());
		});
		var temp = val-1;
		$.ajax({
			url:'nuevo_item.php',
			data:{i:val,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text()},
			type:'post',
			success:function(est){
				$(".totalizador").before(est);
				limpiar_checkbox();
			}
		});
	}else if(uno == 1){
		var id = $("#olditem"+valor_id).text();
		$.ajax({
			url:'nuevo_item.php',
			data:{i:val,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text()},
			type:'post',
			success:function(est){
				$("."+id).after(est);
				limpiar_checkbox();
			}
		});
	}
	
	limpiar_checkbox();
}
/*
function add_nuevo_item(){
	var val = 0;
	$(".proveedores ").each(function(index){
		val++;
	});
	var temp = val-1;
	val++;
	var id = $("#olditem"+temp).text();
	
	$.ajax({
		url:'nuevo_item.php',
		data:{i:val,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text()},
		type:'post',
		success:function(est){
			$("."+id).after(est);
		}
	});
}*/


function sel_vnc_ppto(){
	var uno = 0;
	var valor_id = 0;
	var vnc = [];
	$("input[name='grupo_sel']:checked").each(function(){
		uno++;
		valor_id = $(this).val();
		vnc.push($("#olditem"+valor_id).text());
	});
	if(uno >= 1){
		for(var x = 0; x < vnc.length; x++){
			$("."+vnc[x]+" td:nth-child(4)").html("<table width = '100%'><tr><td>VNC</td><td><img src = '../images/iconos/eliminar.png' width = '20px' onclick = 'uncheck_item_vnc("+vnc[x]+")'/></td></tr></table>");
			$("."+vnc[x]+" td:nth-child(4)").addClass("vnc_ppto");
		}
		$(".proveedores ").each(function(index){
			calcular_interno(index);
		});
		$.ajax({
			url:'busqueda_item.php',
			data:{turno:3,vnc:vnc},
			type:'post',
			success:function(d){
			}
		});
		limpiar_checkbox();
	}else{
		alert("Para ejecutar este función usted debe seleccionar al menos un item.");
	}
}

function limpiar_checkbox(){
	$(".radio").each(function(index){
		$("#grupo"+index).prop( "checked", false );
	});
}

function uncheck_item_vnc(id){
	$.ajax({
		url:'busqueda_item.php',
		data:{turno:4,id:id},
		type:'post',
		success:function(d){
			alert("Se ha habilitado el item para ser un valor comisionable !");
		}
	});
	limpiar_checkbox();
	$("."+id+" td:nth-child(4)").html("");
	$("."+id+" td:nth-child(4)").removeClass("vnc_ppto");
}


function cerrar_modulo_aprobaciones(){
	$("#ventana_aprobaciones").html();
	$("#ventana_aprobaciones").dialog('close');
	$(".scroll").css({"overflow":"scroll"});
}

function dubplicar_item_selected(){
	guardar_informacion_ppto($("#info_vi_text").text(),$("#info_vc_text").text());
	var uno = 0;
	var valor_id = 0;
	$("input[name='grupo_sel']:checked").each(function(){
		uno++;
		console.log($(this).val());
		valor_id = $(this).val();
	});
	var id = "";
	if(uno > 1){
		alert("Solo debe seleccionar un elemento para este función !");
		limpiar_checkbox();
	}else if(uno == 0){
		alert("Usted debe seleccionar un solo item para esta función!");
	}else{
		id = $("#olditem"+valor_id).text();
		var val = 0;
		$(".proveedores").each(function(index){
			val++;
		});
		$.ajax({
			url:'duplicar_item.php',
			data:{id:id,i:val},
			type:'post',
			success:function(d){
				$("."+id).after(d);
				calcular_interno(val);
			}
		});
		$(".proveedores").each(function(index){
			calcular_interno(index);
		});
		limpiar_checkbox();
	}
	limpiar_checkbox();
}

function copiar_grupo_completo(){
	var uno = 0;
	var valor_id = 0;
	$("input[name='grupo_sel']:checked").each(function(){
		uno++;
	});
	var id = "";
	if(uno > 1){
		alert("Solo debe seleccionar un elemento para este función !");
		limpiar_checkbox();
	}else if(uno == 0){
		alert("No ha seleccionado ningún item.\nSeleccione un item para esta función !");
	}else{
		if(confirm("¿Esta seguro de copiar todo este grupo?")){
			var uno = 0;
			var valor_id = 0;
			$("input[name='grupo_sel']:checked").each(function(){
				uno++;
				valor_id = $(this).val();
			});
			var id = "";
			var val = 0;
			$(".proveedores ").each(function(index){
				val++;
			});
			var temp = val-1;
			var idx = $("#olditem"+temp).text();
			
			id = $("#olditem"+valor_id).text();
			var i_actual = 0;
			$(".proveedores").each(function(index){
				i_actual++;
			});
			
			$.ajax({
				url:'copiar_grupo_ppto.php',
				data:{id:id,i_actual:i_actual},
				type:'post',
				success:function(d){
					$("."+idx).after(d);
					//$(".item_total"+i_actual).after(d);
					limpiar_checkbox();
				}
			});
			
		}else{
			limpiar_checkbox();
		}
	}
	
}

function guardar_informacion_ppto(vi,vc){
	var contenido_ppto = [];
	var contenido_item = [];
	var contador = 0;
	$(".codigo_item").each(function(index){
		var id = $(this).text();
		contenido_item.push(id);
		contenido_item.push($("#grupo"+id).val());
		contenido_item.push($("#itemppto"+id).val());
		contenido_item.push($("#descripcionitem"+id).val());
		contenido_item.push($("#proveedoritem"+id).val());
		contenido_item.push($(".diasitem"+id).text());
		contenido_item.push($(".cantidadiem"+id).text());
		
		contenido_item.push($(".valorinternoitem"+id).text());
		
		contenido_item.push($(".ivaitem"+id).val());
		contenido_item.push($(".volumenproveedor"+id).text());
		
		
		contenido_item.push($(".valorinternocliente"+id).text());
		
		contenido_item.push(vi);
		contenido_item.push(vc);
		
		contenido_ppto.push(contenido_item);
		contenido_item = [];
		
	});
	for(var i = 0; i < contenido_ppto.length; i++){
		for(var x = 0; x < contenido_ppto[i].length; x++){
			console.log(contenido_ppto[i][x]);
		}
		console.log("</br>");
	}
	$.ajax({
		url:'busqueda_produccion.php',
		data:{contenido:contenido_ppto,turno:64},
		type:'post',
		success:function(d){
			location.reload();
		}
	});
}

function cerrar_ventana_general(obj){
	var id = $(obj).attr("id");
	var id = $("#"+id).parents('div').attr("id");
	$(".scroll").css({"overflow":"scroll"});
	$("#"+id).dialog('close');
}

function buscar_items_proveedores_op(){
	$.ajax({
		url:'busqueda_item.php',
		data:{turno:1,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text(),prove:$("#listado_proveedores_op").val()},
		type:'post',
		success:function(d){
			$("#contenedor_listado_proveedor_items").html(d);
		}
	});
}

function form_nuevo_op(){
	$.ajax({
		url:'form_op.php',
		data:{ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text()},
		type:'post',
		success:function(d){
			$("#ventana_form_op").html(d);
			$(".scroll").css({"overflow":"hidden"});
			$("#ventana_form_op").dialog('open');
		}
	});
}


function generar_orden_op(){
	var contador = 0;
	var list_item_op = [];
	$("input[name='itemop[]']:checked").each(function(){
		list_item_op.push($(this).val());
		contador++;
	});
	
	if(contador == 0){
		alert("Para ejecutar este función, usted debe seleccionar al menos un item!");
	}else{
		if($("#fpago_op").val() != 0){
			if($("#lugar_op").val().length != 0){
				if($("#vigencia_inicial_op").val() != ""){
					if($("#vigencia_final_op").val() != ""){
						$.ajax({
							url:'busqueda_item.php',
							data:{turno:2,items:list_item_op,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),
							vc:$("#info_vc_text").text(),prove:$("#listado_proveedores_op").val(),vfi:$("#vigencia_inicial_op").val(),vff:$("#vigencia_final_op").val(),lugar:$("#lugar_op").val(),fpago:$("#fpago_op").val(),
							ot:$(".ot_ppto_encabezado").text(),user:$("#codigo_usuario").text(),nota:$("#nota_orden_op").val()},
							type:'post',
							success:function(d){
								alert("Se ha generado la OP # "+d);
								
								$("#ventana_form_op").html("");
								$("#ventana_form_op").dialog('close');
								/*for(var x = 0; x < list_item_op.length; x++){
									$(".orden_item"+list_item_op[x]).html("OP # "+d);
								}*/
								window.open("pdf_op.php?op="+d+"",'_blank');
								location.reload();
							}
						});
					}else{
						alert("Debe especificar la vigencia final de la Orden de Producción !");
					}
				}else{
					alert("Debe especificar la vigencia inicial de la Orden de Producción !");
				}
			}else{
				alert("Debe ingresar el lugar de entrega !");
			}
		}else{
			alert("Debe especificar una forma de pago !");
		}
	}
}

function validar_fechas_op(){
	var f1 = $("#vigencia_inicial_op").val();
	var f2 = $("#vigencia_final_op").val();
	if(f2 < f1){
		alert("La fecha final no puede ser menor a la inicial !");
		$("#vigencia_final_op").val("");
	}
}


function generar_orden_oc(){
	var contador = 0;
	var list_item_op = [];
	$("input[name='itemop[]']:checked").each(function(){
		list_item_op.push($(this).val());
		contador++;
	});
	
	if(contador == 0){
		alert("Para ejecutar este función, usted debe seleccionar al menos un item!");
	}else{
		if($("#fecha_entrega_ordenc").val().length > 4){
			if($("#fecha_radicacion_ordenc").val().length > 4){
				if($("#vigencia_inicial_op").val().length > 4){
					if($("#vigencia_inicial_op").val().length > 4){
						if($("#fpago_op").val() != 0){
							if($("#lugar_op").val().length > 1){
								$.ajax({
									url:'busqueda_item.php',
									data:{turno:6,items:list_item_op,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),
									vc:$("#info_vc_text").text(),prove:$("#listado_proveedores_op").val(),vfi:$("#vigencia_inicial_op").val(),vff:$("#vigencia_final_op").val(),lugar:$("#lugar_op").val(),fpago:$("#fpago_op").val(),
									ot:$(".ot_ppto_encabezado").text(),user:$("#codigo_usuario").text(),fradicacion:$("#fecha_radicacion_ordenc").val(),
									fentrega:$("#fecha_entrega_ordenc").val(),nota:$("#nota_orden_oc").val()},
									type:'post',
									success:function(d){
										alert("Se ha generado la OC # "+d);
										$("#ventana_form_op").html("");
										$("#ventana_form_op").dialog('close');
										/*for(var x = 0; x < list_item_op.length; x++){
											$(".orden_item"+list_item_op[x]).html("OC # "+d);
										}*/
										window.open("pdf_op.php?op="+d+"",'_blank');
										location.reload();
									}
								});
							}else{
								alert("Debe ingresar el lugar donde se va a entregar lo solicitado en la Orden de Compra !");
							}
						}else{
							alert("Debe seleeccionar una forma de pago para la Orden de Compra !");
						}
					}else{
						alert("Debe seleccionar la fecha de vigencia final de la Orden de Compra !");
					}
				}else{
					alert("Debe seleccionar la vigencia inicial de la Orden de Compra !");
				}
			}else{
				alert("Debe seleccionar la fecha en que se va a radicar la Orden de Compra !");
			}
		}else{
			alert("Debe seleccionar la fecha de Entrega de la Orden de Compra !");
		}
	}
}

function cargar_historico_aprobaciones_ppto(){
	$.ajax({
		url:'aprobados_ppto.php',
		data:{user:$("#user_html").text()},
		type:'post',
		success:function(d){
			$(".histo_ppto_aprobaciones").html(d);
		}
	});
}

function sel_del_all_formop(){
	if($("#sel_todo_form_op").val() == 0){
		$(".radioformop").prop( "checked", true );
		$("#sel_todo_form_op").prop( "checked", true );
		$("#sel_todo_form_op").val(1);
	}else if($("#sel_todo_form_op").val() == 1){
		$(".radioformop").prop( "checked", false );
		$("#sel_todo_form_op").prop( "checked", false );
		$("#sel_todo_form_op").val(0);
	}
	
}

function historico_item(){
	var uno = 0;
	var valor_id = 0;
	$("input[name='grupo_sel']:checked").each(function(){
		uno++;
	});
	var id = "";
	if(uno > 1){
		alert("Para esta función solo debe estar seleccionado un item !");
	}else if(uno == 0){
		alert("Usted debe seleccionar un item para esta función !");
	}else if(uno == 1){
		
	}
}

function form_oc_ppto(){
	$.ajax({
		url:'form_oc.php',
		data:{ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text()},
		type:'post',
		success:function(d){
			$("#ventana_form_op").html(d);
			$(".scroll").css({"overflow":"hidden"});
			$("#ventana_form_op").dialog('open');
		}
	});
}
function buscar_info_histo_ordenes(){
	if($("#buscador_histo_ordenes").val() == "PROV"){
		if($("#ordenes_histo").val() != 0){
			
		}
	}else{
		if($("#ordenes_histo").val().length != 0){
			$.ajax({
				url:'busqueda_item.php',
				data:{valor:$("#ordenes_histo").val(),turno:7,tipo:$("#buscador_histo_ordenes").val(),ppto:$("#info_ppto_text").text()},
				type:'post',
				success:function(d){
					$("#contenedor_list_ordenes").html(d);
				}
			});
		}else{
			alert("Para ejecutar esta función debe digitar un número de orden !");
		}
	}
}

function cancelacion_orden_produccion(num){
	if(confirm("¿Está seguro de cancelar la OP # "+num+" ?")){
		$.ajax({
			url:'form_cancelacion_op.php',
			data:{num_op:num},
			type:'post',
			success:function(d){
				$("#ventana_versiones").html(d);
				$("#ventana_versiones").dialog('open');
			}
		});
	}else{
		
	}
}

function cancelacion_orden_compra(num){
	if(confirm("¿Está seguro de cancelar la OC # "+num+" ?")){
		$.ajax({
			url:'form_cancelacion_oc.php',
			data:{num_op:num},
			type:'post',
			success:function(d){
				$("#ventana_versiones").html(d);
				$("#ventana_versiones").dialog('open');
			}
		});
	}else{
		
	}
}

function cerrar_cancelacion_op(){
	$("#ventana_versiones").html("");
	$("#ventana_versiones").dialog('close');
}

function generar_cancelacion_op(num){
	if($("#razon_cancelacion_op").val().length > 0){
		$.ajax({
			url:'busqueda_item.php',
			data:{turno:8,razon:$("#razon_cancelacion_op").val(),op:num,user:$("#codigo_usuario").text()},
			type:'post',
			success:function(d){
				var ppto = $("#info_ppto_text").text();
				window.open("fcancelacionop.php?op="+num+"&ppto="+ppto+"",'_blank');
				location.reload();
			}
		});
	}else{
		alert("Para realizar una cancelación usted debe especificar la razón correspondiente !");
	}
}

function generar_cancelacion_oc(num){
	if($("#razon_cancelacion_oc").val().length > 0){
		$.ajax({
			url:'busqueda_item.php',
			data:{turno:9,razon:$("#razon_cancelacion_oc").val(),oc:num,user:$("#codigo_usuario").text()},
			type:'post',
			success:function(d){
				var ppto = $("#info_ppto_text").text();
				window.open("fcancelacionoc.php?oc="+num+"&ppto="+ppto+"",'_blank');
				location.reload();
			}
		});
	}else{
		alert("Para realizar una cancelación usted debe especificar la razón correspondiente !");
	}
}


function solicitar_anticipo_item(id){
	var anticipo = [];
	anticipo.push(id);
	$.ajax({
		url:'form_anticipos.php',
		data:{tipo:1,item:anticipo,ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text()},
		type:'post',
		success:function(d){
			$("#ventana_form_op").html(d);
			$("#ventana_form_op").dialog('open');
		}
	});
}

function sumar_fechas_php(){
	if($("#fecha_anticipo").val().length > 4){
		$.ajax({
			url:'sum_fechas.php',
			data:{fecha:$("#fecha_anticipo").val()},
			type:'post',
			success:function(d){
				$("#fecha_max_legal_anticipo").val(d);
			}
		});
	}else{
		alert("Debe ingresar una fecha valida !");
	}
}

function validar_porcentaje_libre_item_anticipo(id){
	if($("#por_ant"+id).val().length > 3){
		alert("Ha sobrepasado el límite de caracteres para este campo !");
		$("#por_ant"+id).val(0);
	}else{
		var dias = $("#ant_dias"+id).text();
		var q = $("#ant_q"+id).text();
		var valor_unitario =  $("#ant_val_unitario"+id).text();
		var porcentaje = $("#por_ant"+id).val();
		var valor = ((valor_unitario*dias*q)*porcentaje)/100;
		
		$("#total_solicitado"+id).text(formatNumber.new(valor));
	}
}

function generar_anticipo_ppto(){
	var item_anticipos = [];
	var contador = 0;
	var valores_cero = [];
	$("input[name='item_anticipo[]']:checked").each(function(){
		item_anticipos.push($(this).val());
		valores_cero.push($("#por_ant"+$(this).val()).val());
		contador++;
	});
	if(contador == 0){
		alert("Para generar un anticipo debe seleccionar un Item !");
	}else{
		if($("#fecha_anticipo").val().length > 4){
			$.ajax({
				url:'generar_anticipo.php',
				data:{ppto:$("#info_ppto_text").text(),vi:$("#info_vi_text").text(),vc:$("#info_vc_text").text(),user:$("#codigo_usuario").text(),
				finicial:$("#fecha_anticipo").val(),flega:$("#fecha_max_legal_anticipo").val(),items:item_anticipos,valores:valores_cero},
				type:'post',
				success:function(d){
					alert("Se ha generado el Anticipo # "+d);
					location.reload();
				}
			});
		}else{
			alert("Debe seleccionar la fecha para cuando requiere el anticipo !");
		}
	}
}


function actualizar_listado_anticipos_pendientes(){
	$.ajax({
		url:'anticipos_pendientes.php',
		data:{user:$("#user_html").text()},
		type:'post',
		success:function(d){
			$(".pendientes_anticipos").html(d);
		}
	});
}
function aprobacion_anticipo(id,pen){
	if(confirm("¿Está seguro de aprobar este anticipo ?")){
		$.ajax({
			url:'aprobar_anticipo.php',
			data:{id:id,user:$("#user_html").text(),pen:pen,estado:1},
			type:'post',
			success:function(d){
				$("#visual_ppto").dialog('close');
				$("#visual_ppto").html('');
				actualizar_listado_anticipos_pendientes();
				alert("Se ha aprobado el Anticipo # "+id);
			}
		});
	}else{
		
	}
}

function noaprobar_anticipo(id,pen){
	alert("Especifique las razones por las cuales no aprueba este Anticipo.");
	$("#comentarios_anticipos").append("<span class = 'hidde' id = 'numanticipo'>"+id+"</span>");
	$("#comentarios_anticipos").append("<span class = 'hidde' id = 'numpendiente'>"+pen+"</span>");
	$("#comentarios_anticipos").dialog('open');
}

function noaprobar_anticipo_fin(){
	if($("#comentarios_ant_text").val().length > 4){
		$.ajax({
			url:'aprobar_anticipo.php',
			data:{id:$("#numanticipo").text(),user:$("#user_html").text(),pen:$("#numpendiente").text(),estado:0,observ:$("#comentarios_ant_text").val()},
			type:'post',
			success:function(d){
				$("#visual_ppto").dialog('close');
				$("#visual_ppto").html('');
				actualizar_listado_anticipos_pendientes();
				alert("Anticipo # "+$("#numanticipo").text()+" no aprobado !");
			}
		});
	}else{
		alert("Debe especificar las razones !");
	}
}

function cerrar_ventana_generalx(){
	
}