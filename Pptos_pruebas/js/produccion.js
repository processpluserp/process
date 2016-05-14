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


function formatear_valor(e,x,y){
	var val = $("#"+x).val();
	var valor = val.split(",");
	var val_final = "";
	for(var i = 0;i < valor.length; i++){
		val_final += ""+valor[i];
	}
	$("#"+x).val(formatNumber.new(val_final));
	$("#"+y).text(val_final);
	
}
$(document).ready(function(){
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
	var contenedor_fest = [];
	var festivos = [];
	$.ajax({
		url:'gestion_all2.php',
		data:{t:116},
		type:'post',
		success:function(data){
			var contenedor = data;
			contenedor_fest = contenedor.split("***");							
			for(var i = 1; i < contenedor_fest.length; i++){
				var aux = contenedor_fest[i].split(",");
				festivos[i] = [ [aux[0]],[aux[1]],[aux[2]] ];
			}
		}
	});
	
	function nonWorkingDates(date){
		var day = date.getDay(), Sunday = 0, Monday = 1, Tuesday = 2, Wednesday = 3, Thursday = 4, Friday = 5, Saturday = 6;
		var closedDays = [[Saturday], [Sunday]];
		/*for (var i = 0; i < closedDays.length; i++) {
			if (day == closedDays[i][0]) {
				return [false];
			}
		}*/

		for (i = 1; i < festivos.length; i++) {
			if (date.getMonth() == festivos[i][0] - 1 &&
				date.getDate() == festivos[i][1] &&
				date.getFullYear() == festivos[i][2]) {
					return [false];

				}
			}
		return [true];
	}


	function actualizar_tareas(){
		$("#spinner").bind("ajaxSend", function() {
	        $(this).show();
	    }).bind("ajaxStop", function() {
	        $(this).show();
	    }).bind("ajaxError", function() {
	        $(this).show();
	    });
		
		
		$.ajax({
			url:'busqueda_trafico.php',
			data:{turno:32,usuario:$("#codigo_usuario").text()},
			type:'POST',
			success:function(data){
				if(data != 0){
					alertify.log("TIENES "+data + " TAREA(S) NUEVA(S)"); 
					return false;
				}
			}
		});
		$.ajax({
			url:'busqueda_trafico.php',
			data:{turno:38,usu:$("#codigo_usuario").text()},
			type:'POST',
			success:function(data){
				$("#title_alertas_tareas").title = 'TIENES '+data;
				$("#alerta_tareas").text(data);
			}
		});
	}
	//setInterval(actualizar_tareas, 200000);
	
	var ancho_cuadro = $( "#v_recepcion_fact" ).width();

	var alto_menu_financiero = (x*62)/100;
	$("#panel_opciones").css({'height':alto_menu_financiero,"overflow":"scroll"});
	var alto_xxx = $( "#panel_opciones" ).height();
	var super_alto = (alto_xxx*98)/100;
	$("#contenedor_opciones").css({'height':alto_xxx,"overflow":"scroll"});


	var alto = $(window).height();
	var ancho_x = $(window).width();
	var ancho_por = (ancho_x*100)/100;
	var ancho_por2 = (ancho_x*70)/100;
	var x = (alto*100)/100;
	var alto_h = (alto*60)/100;
	

	var alto_hh = (alto*80)/100;
	var alto_hhh = (alto*35)/100;
	
	$(".ui-draggable").css({
	    top:"2px"
	});
	$("#v_inicial,#fecha_doc_fact_prov,#fechav_doc_fact_prov").datepicker({ dateFormat: 'yy-mm-dd',beforeShowDay: nonWorkingDates,	numberOfMonths: 1,	minDate: '0',firstDay: 1  });
	$("#fecha_factura_ppto,#fecha_pago_cliente_tesoreria,#fecha_pago_num_fact_proveedor").datepicker({ dateFormat: 'yy-mm-dd',beforeShowDay: nonWorkingDates });
	
	$("#pptos_realizados").css({"height":(x*62)/100});

	$("#v_final").datepicker({ dateFormat: 'yy-mm-dd',beforeShowDay: nonWorkingDates,	numberOfMonths: 1,	minDate: '0',firstDay: 1  });
	//Formulario para crear ppto
	$("#formato_nuevo_ppto,#v_recepcion_fact,#vetana_registrar_fac_pro,#reportes_produccion").dialog({
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
	
	$("#guardar_fac_prov_orden").on('click',function(){
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:47,t:$("#tipo_doc_prov").val(),d:$("#num_doc_pro").val(),f:$("#fecha_doc_fact_prov").val(),
			fv:$("#fechav_doc_fact_prov").val(),val:$("#valor_fact_prov").val(),ival:$("#iva_fact_prov").val(),noo:$("#num_orden_b_rf").val()},
			type:'post',
			success:function(data){
				alert(data);
				$(".scroll").css({"overflow-y":"scroll"});
				$("#vetana_registrar_fac_pro").dialog('close');
				$("#vetana_registrar_fac_pro input").val("");
			}
		});
	});
	
	$("#buscar_orden").on('click',function(){
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:46,emp:$("#empresa_b_rf").val(),noorden:$("#num_orden_b_rf").val()},
			type:'post',
			success:function(data){
				$("#contenedor_result_facturas").html(data);
			}
		});
	});
	
	$("#cerrar_ventana_crear_ppto").on('click',function(){
		$("#formato_nuevo_ppto select").val("0");
		$("#formato_nuevo_ppto input").val("");
		$("#formato_nuevo_ppto textarea").val("");
		$(".scroll").css({"overflow-y":"scroll"});
		$("#formato_nuevo_ppto").dialog("close");
	});
	
	//Formulario para cargar un ppto.
	$("#fomr_carga_ppto").dialog({
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
	
	$("#cancelar").on('click',function(){
		$("#formato_nuevo_ppto input").val("");
		$("#formato_nuevo_ppto textarea").val("");
		$("#formato_nuevo_ppto select").val("");
		$(".scroll").css({"overflow-y":"scroll"});
		$("#formato_nuevo_ppto").dialog('close');
	});
	//Abre el formulario de ppto.	
	$("#nuevo_ppto").on('click',function(){
		$(".scroll").css({"overflow-y":"hidden"});
		$("#formato_nuevo_ppto textarea").val("");
		$(".scroll").css({"overflow-y":"hidden"});
		$("#formato_nuevo_ppto").dialog('open');
	});
	
	//Abre el formulario PARA BUSCAR UN PPTO
	$("#cargar_ppto").on('click',function(){
		$("#fomr_carga_ppto textarea").val("");
		$(".scroll").css({"overflow-y":"hidden"});
		$("#fomr_carga_ppto").dialog('open');
	});
	
	$(".ui-dialog-titlebar").hide();
	
	//Cambio de EmpresA: Seleccion Cliente
	$("#grupo_empresas").on('change',function(){
		var emp = $("#grupo_empresas").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:1,emp:emp,usu:usu},
			type:'POST',
			success:function(data){
				$("#cliente").html("");
				$("#cliente").append(data);
			}
		});
		$.ajax({
			url:'gestion_all2.php',
			data:{t:4,emp:emp},
			type:'POST',
			success:function(data){
				$("#c_costo_fn").html(data);
			}
		});
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:3,emp:emp},
			type:'POST',
			success:function(data){
				$("#nota_ppto").html("");
				$("#nota_ppto").val(data);
			}
		});
	});
	
	//ACCION PARA CARGAR UNA EMPRESA --CARGUE
	$("#empresa_carga").on('change',function(){
		var emp = $("#empresa_carga").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:1,emp:emp,usu:usu},
			type:'POST',
			success:function(data){
				$("#cliente_carga").html("");
				$("#cliente_carga").append(data);
			}
		});
	});
	
	$("#cliente").on('change',function(){
		var emp = $("#grupo_empresas").val();
		var clie = $("#cliente").val();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:2,emp:emp,clie:clie},
			type:'POST',
			success:function(data){
				$("#ot").html("");
				$("#ot").append(data);
			}
		});
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:40,emp:emp,clie:clie},
			type:'POST',
			success:function(data){
				$("#tipo_comision").html("");
				$("#tipo_comision").append(data);
			}
		});
	});
	
	$("#cliente_carga").on('change',function(){
		var emp = $("#empresa_carga").val();
		var clie = $("#cliente_carga").val();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:2,emp:emp,clie:clie},
			type:'POST',
			success:function(data){
				$("#ot_carga").html("");
				$("#ot_carga").append(data);
			}
		});
	});
	
	
	$("#crear_ppto").on('click',function(){
		var emp = $("#grupo_empresas").val();
		var usu = $("#codigo_usuario").text();
		var clie = $("#cliente").val();
		var ot = $("#ot").val();
		//var depto = $("#depto_ct").val();
		var ref = $("#referencia").val();
		var vi = $("#v_inicial").val();
		var vf = $("#v_final").val();
		var nota = $("#nota_ppto").val();
		var n_apro = $("#n_aprobacion").val();
		var tipo = $("#tipo_ppto").val();
		var comision = $("#tipo_comision").val();
		var ciudad = $("#ciudad").val();
		if(comision != 0){
			if($("#grupo_empresas").val().length > 0){
				if($("#cliente").val().length > 0){
					if($("#ot").val().length > 0){
						if($("#referencia").val().length > 0){
							if($("#v_inicial").val().length > 0){
								if($("#v_final").val().length > 0){
									if($("#tipo_ppto").val().length > 0){
										if($("#c_costo_fn").val()){
											$.ajax({
												url:'busqueda_produccion.php',
												data:{turno:4,emp:emp,usu:usu,clie:clie,ot:ot,ref:ref,vi:vi,vf:vf,nota:nota,n_apro:n_apro,tipo:tipo,comision:comision,ciudad:ciudad,ceco:$("#c_costo_fn").val()},
												type:'POST',
												success:function(data){
													if(data == 1){
														location.href = "ppto_fee.php";
													}else{
														location.href = "produccion_ppto.php";
													}
												}
											});
										}else{
											alert("DEBE SELECCIONAR UN CENTRO DE COSTO !");
										}
									}else{
										alert("SELECCIONE EL TIPO DE PPTO !");
									}
								}else{
									alert("INGRESE LA VIGENCIA FINAL DEL PPTO !");
								}
							}else{
								alert("INGRESE LA VIGENCIA INICIAL DEL PPTO !");
							}
						}else{
							alert("INGRESE LA REFERENCIA DEL PPTO !");
						}
					}else{
						alert("DEBE SELECCIONAR UNA OT !");
					}
				}else{
					alert("DEBE SELECCIONAR UN CLIENTE !");
				}
			}else{
				alert("DEBE SELECCIONAR UNA EMPRESA !");
			}
			
		}else{
			alert("SIN COMISIÓN NO SE PUEDE CREAR ESTE PPTO !");
		}
		
	});


	/*EVENTOS FACTURACIÓN*/
	$("#empresa_b_facturacion").on('change',function(){
		var emp = $("#empresa_b_facturacion").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_trafico.php',
			data:{turno:4,emp:emp,usu:usu},
			type:'POST',
			success:function(data){
				$("#cliente_b_facturacion").html(data);
			}
		});
	});

	$("#empresa_b_tesoreria").on('change',function(){
		var emp = $("#empresa_b_tesoreria").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_trafico.php',
			data:{turno:4,emp:emp,usu:usu},
			type:'POST',
			success:function(data){
				$("#cliente_b_tesoreria").html(data);
			}
		});
	});

	$("#cliente_b_tesoreria").on('change',function(){
		var clie = $("#cliente_b_tesoreria").val();
		var emp = $("#empresa_b_tesoreria").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:52,clie:clie,usu:usu,emp:emp},
			type:'POST',
			success:function(data){
				$("#num_factura_tesoreria").html(data);
			}
		});
	});

	$("#cliente_b_facturacion").on('change',function(){
		var clie = $("#cliente_b_facturacion").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_trafico.php',
			data:{turno:5,clie:clie,usu:usu},
			type:'POST',
			success:function(data){
				$("#producto_cliente_b_facturacion").html(data);
			}
		});
	});

	$("#producto_cliente_b_facturacion").on('change',function(){
		var clie = $("#cliente_b_facturacion").val();
		var emp = $("#empresa_b_facturacion").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:48,clie:clie,usu:usu,emp:emp,pro:$("#producto_cliente_b_facturacion").val()},
			type:'POST',
			success:function(data){
				$("#ot_producto_cliente_b_facturacion").html(data);
			}
		});
	});

	$("#ot_producto_cliente_b_facturacion").on('change',function(){
		var clie = $("#cliente_b_facturacion").val();
		var emp = $("#empresa_b_facturacion").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:49,clie:clie,usu:usu,emp:emp,pro:$("#producto_cliente_b_facturacion").val(),ot:$("#ot_producto_cliente_b_facturacion").val()},
			type:'POST',
			success:function(data){
				$("#ppto_ot_producto_cliente_b_facturacion").html(data);
			}
		});
	});

	$("#num_factura_tesoreria").on('change',function(){
		var emp = $("#num_factura_tesoreria").val();
		var usu = $("#codigo_usuario").text();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:53,fact:emp},
			type:'POST',
			success:function(data){
				$("#num_ppto_factura_tesoreria").html(data);
			}
		});
	});

	$("#limpiar_campos_ingresar_factura").on('click',function(){
		$(".hijos_facturacion_pptos select").val("0");
		$(".hijos_facturacion_pptos input").val("0");
		$(".hijos_facturacion_pptos span").text("");
	});
	$("#guardar_factura_ppto").on('click',function(){

		if($("#ppto_ot_producto_cliente_b_facturacion").val() != 0 && $("#num_factura_ppto").val().length != 0 && $("#valor_factura_ppto_real").text().length != 0 && $("#fecha_factura_ppto").val().length != 0){
			$.ajax({
				url:'busqueda_produccion.php',
				data:{turno:50,factura:$("#num_factura_ppto").val(),ppto:$("#ppto_ot_producto_cliente_b_facturacion").val(),valor:$("#valor_factura_ppto_real").text(),fecha:$("#fecha_factura_ppto").val()},
				type:'POST',
				success:function(data){
					alert(data);
					$(".hijos_facturacion_pptos select").val("0");
					$(".hijos_facturacion_pptos input").val("0");
					$(".hijos_facturacion_pptos span").text("");
				}
			});
		}else{
			alert("NO SE PUEDE REALIZAR ESTA ACCIÓN !!! \nRECUERDE QUE PARA PODER INGRESAR LA FACTURA DE UN PPTO, USTED DEBE \n->SELECCIONAR UN PPTO.\n->INGRESAR UN NÚMERO DE FACTURA.\n->INGRESAR EL VALOR DE LA FACTURA.\n->SELECCIONAR LA FECHA DE VENCIMIENTO DE FACTURA.");
		}
		
	});




	$("#guardar_pago_cliente_tesoreria").on('click',function(){
		if($("#num_factura_tesoreria").val() != 0 && $("#num_ppto_factura_tesoreria").val() != 0 && $("#pago_tesoreria_cliente").val() != 0 && $("#valor_tesoreria_ppto_real").text().length != 0){
			$.ajax({
				url:'busqueda_produccion.php',
				data:{turno:54, fact: $("#num_factura_tesoreria").val(), ppto:$("#num_ppto_factura_tesoreria").val(), tipo: $("#pago_tesoreria_cliente").val(), valor:$("#valor_tesoreria_ppto_real").text()},
				type:'post',
				success:function(dat){
					alert(dat);
					$(".hijos_tesoreria_cliente select").val("0")
					$(".hijos_tesoreria_cliente input").val("");
					$("#valor_tesoreria_ppto_real").text("");
				}
			});
		}else{
			alert("NO HA INGRESADO TODOS LOS DATOS !!!");
		}
	});

	$("#num_factura_tesoreria").on('keyup',function(){
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:51,factura:$("#num_factura_tesoreria").val()},
			type:'post',
			success:function(datax){
				$("#num_ppto_factura_tesoreria").val(datax);
			}
		});
	});
	var ancho_cuadro = $( "#v_recepcion_fact" ).width();

	var alto_menu_financiero = (x*62)/100;
	$("#panel_opciones").css({'height':alto_menu_financiero,"overflow":"scroll"});
	var alto_xxx = $( "#panel_opciones" ).height();
	var super_alto = (alto_xxx*110)/100;
	$("#contenedor_opciones").css({'height':super_alto,"overflow":"scroll"});
});
function visualizar_pptos(){
		var resultado = "";
		var clie = $("#cliente_carga").val();
		var porNombre=document.getElementsByName("select_ppto");
		for(var i=0;i<porNombre.length;i++){
			if(porNombre[i].checked)
				resultado=porNombre[i].value;
		}
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:9,ppto:resultado,clie:clie},
			type:'POST',
			success:function(data){
				location.href = "produccion_ppto.php";
			}
		});
}

function abrir_fac_prove(id){
	$(".scroll").css({"overflow-y":"hidden"});
	$("#vetana_registrar_fac_pro").dialog('open');
}

function buscar_ppto_sel_ot(){
	var ot = $("#ot_carga").val();
		$.ajax({
			url:'busqueda_produccion.php',
			data:{turno:8,ot:ot},
			type:'POST',
			success:function(data){
				$("#pptos_realizados").html("");
				$("#pptos_realizados").html(data);
			}
		});
}

function buscar_oc_para_pagar_proveedor(){
	var oc = $("#num_oc_pago_proveedor").val();
	$.ajax({
		url:'busqueda_produccion.php',
		data:{turno:55,oc:oc},
		type:'post',
		success:function(dat){
			$("#num_fact_pago_proveedor").val(dat);
		}
	});
}