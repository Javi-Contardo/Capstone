<?php include("puerta_principal.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);*/
?>
<html lang="es"><head>
    <?php include("scripts-header.php");?>
    <script>
		var intento=1;
		var grafico_update='';
        $(document).ready(function() {
            $("#act-dashboard").addClass("mm-active");
            $("#titulo-cabecera").text("Dashboard");
            $("#descripcion-cabecera").text("Reportería");
            //buscar();no activar
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
			
			listado_datos();
			productos_proximos_vencer();
			
			
        });

		
		
		function graficado_general(codigo_reatil_grafico){ 
			
			document.getElementById('div_grafico').style.display = 'block';
			console.log("mensaje: "+codigo_reatil_grafico);
			if(codigo_reatil_grafico!=''){
			$.post("dashboard_ajax_stock.php", {proceso:'DATOS_GRAFICADO', codigo_reatil_grafico:codigo_reatil_grafico}, 
				function(result) 
				{
				 console.log(result);
                    var obj = JSON.parse(result);
					console.log("on_hand data:", obj.on_hand);
					console.log("ventas_semana_actual data:", obj.ventas_semana_actual);
					console.log("nombres_locales_array data:", obj.nombres_locales_array);

					var grafico_inicial = {
						colors : ['#ADD500', '#0071CE'],
						chart: {
							height: 397,
							type: 'line',
							toolbar:{
								show:false
							}
						},
						series: [{
							name: 'On Hand(Diario)',
							type: 'bar',
							data: obj.on_hand
						},{
							name: 'Ventas',
							type: 'line',
							data: obj.ventas_semana_actual
						}],
							plotOptions: {
								bar: {
									horizontal: false,
									/*endingShape: 'rounded',
									columnWidth: '55%',*/
									borderRadius: 5,
									borderRadiusApplication: 'end',
									borderRadiusWhenStacked: 'last',
								},
							},
						dataLabels: {
								enabled: false
							},
                        stroke: {
                            show: true,
                            width: 2/*,
                            colors: ['#ADD500']*/
                        },
						// labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
						labels: obj.nombres_locales_array,
						xaxis: {
							type: 'text'
						}
					};
				
					console.log("intentox:"+intento)
					if (grafico_update)
						{
							grafico_update.destroy();
							grafico_update = new ApexCharts(document.querySelector("#dashboard-sparklines-primary"), grafico_inicial);
							setTimeout(function () {
						if (document.getElementById('dashboard-sparklines-primary')) {
							grafico_update.render();
						}
					}, 1000);
						}
					else
						{
							grafico_update = new ApexCharts(document.querySelector("#dashboard-sparklines-primary"),
								grafico_inicial
							);

							setTimeout(function () {
									if (document.getElementById('dashboard-sparklines-primary')) {
										grafico_update.render();
										intento=2;
									}
								 }, 1000);
							document.getElementById('titulo_grafico1').style.display ='block';

						}
				}
				   );
			}
		}
		
		function abrir_detalle_lote(codigo_retail, numero_local, nombre_local) {
			/*if (grafico_update) {
						// Destruir el gráfico si ya existe
						grafico_update.destroy();
						$("#titulo_grafico").html('');
						document.getElementById('titulo_grafico1').style.display ='none'
					}*/
			$('#modal_detalles_lotes').modal('show');
			detalle_datatable_lote(codigo_retail,numero_local);
			console.log("codigo de retail: "+codigo_retail);
			console.log(" y el numero del local: "+numero_local);
			/*$.post("conciliacion_comercial_walmart_ajax.php"), {proceso:'DATOS_GRAFICO', codigo_retail:codigo_retail, numero_local:numero_local},
			function(datos) 
			{
				console.log("estos son los datos de vueltos: "+datos);
			}*/
			/*$('#ctd_existencia_cd1').html(ctd_existencia_cd);*/
			$('#modal_detalle_lotes_titulo_stock').html(codigo_retail+"-"+nombre_local+"-STOCK");
			
		}
		
		function abrir_detalle_lote_venta(codigo_retail, numero_local, nombre_local) {
			/*if (grafico_update) {
						// Destruir el gráfico si ya existe
						grafico_update.destroy();
						$("#titulo_grafico").html('');
						document.getElementById('titulo_grafico1').style.display ='none'
					}*/
			$('#modal_detalles_lotes_venta').modal('show');
			detalle_datatable_lote_venta(codigo_retail,numero_local);
			console.log("codigo de retail: "+codigo_retail);
			console.log(" y el numero del local: "+numero_local);
			/*$.post("conciliacion_comercial_walmart_ajax.php"), {proceso:'DATOS_GRAFICO', codigo_retail:codigo_retail, numero_local:numero_local},
			function(datos) 
			{
				console.log("estos son los datos de vueltos: "+datos);
			}*/
			/*$('#ctd_existencia_cd1').html(ctd_existencia_cd);*/
			$('#modal_detalle_lotes_titulo_ventas').html(codigo_retail+"-"+nombre_local+"-VENTAS");
			
		}
		
		function abrir_detalle(codigo_retail,  descripcion, numero_local) {
			/*if (grafico_update) {
						// Destruir el gráfico si ya existe
						grafico_update.destroy();
						$("#titulo_grafico").html('');
						document.getElementById('titulo_grafico1').style.display ='none'
					}*/
			document.getElementById('listado_general').style.display = 'none';
			document.getElementById('listado_detalle').style.display = 'block';
			detalle_datatable(codigo_retail);
			console.log("codigo de retail: "+codigo_retail);
			console.log(" y el numero del local: "+numero_local);
			/*$.post("conciliacion_comercial_walmart_ajax.php"), {proceso:'DATOS_GRAFICO', codigo_retail:codigo_retail, numero_local:numero_local},
			function(datos) 
			{
				console.log("estos son los datos de vueltos: "+datos);
			}*/
			/*$('#ctd_existencia_cd1').html(ctd_existencia_cd);*/
			$('#titulo_grafico_detalle').html(codigo_retail+"-"+descripcion);
			$('#codigo_retail').val(codigo_retail);
			
		}
		
		
		function detalle_datatable_lote(codigo_retatil,numero_local){
			if(codigo_retatil==undefined)
			{
				codigo_retatil=document.getElementById('codigo_retail').value;
			}
			console.log("codigo de retail: "+codigo_retatil+" numero_localaso: "+numero_local);
			
			
			
            $('#lista_oc2').DataTable().clear().destroy();
            $('#lista_oc2').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_datatable_stock_lote.php?var1="+codigo_retatil+"&var2="+numero_local,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
				"orderMulti": true,
				"dom": '<"toolbar2">frtip',
                "order": [
                    [3, "desc"]
                ],
				"oLanguage": {
							   "sSearch": "Buscar"
							 },
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-left",
                        "width": "1%"
                    },
					{
                        "targets": 1,
                        "className": "text-left",
                        "width": "20%"
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 3,
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 4,
                        "className": "text-center",
                        "width": "10%"
                    }
					
					
                ],
            });
			//document.querySelector('div.toolbar').innerHTML = '<div class="col-lg-2 float-left"><button type="button" class="btn btn-primary justify-content-end form-control-sm float-right" onClick="descargar_conciliacion()">Descargar</button></div><div class="col-lg-2 float-left"><button type="button" class="btn btn-primary justify-content-end form-control-sm float-right" onClick="descargar_conciliacion_detalle()">Descargar Detalle</button></div>';
			//document.querySelector('div.toolbar').style.position='absolute';

			
		}
		
		function detalle_datatable_lote_venta(codigo_retatil,numero_local){
			if(codigo_retatil==undefined)
			{
				codigo_retatil=document.getElementById('codigo_retail').value;
			}
			console.log("codigo de retail: "+codigo_retatil+" numero_localaso: "+numero_local);
			
			
			
            $('#lista_oc3').DataTable().clear().destroy();
            $('#lista_oc3').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_datatable_venta_lote.php?var1="+codigo_retatil+"&var2="+numero_local,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
				"orderMulti": true,
				"dom": '<"toolbar2">frtip',
                "order": [
                    [3, "desc"]
                ],
				"oLanguage": {
							   "sSearch": "Buscar"
							 },
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-left",
                        "width": "1%"
                    },
					{
                        "targets": 1,
                        "className": "text-left",
                        "width": "20%"
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 3,
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 4,
                        "className": "text-center",
                        "width": "10%"
                    }
					
					
                ],
            });
			//document.querySelector('div.toolbar').innerHTML = '<div class="col-lg-2 float-left"><button type="button" class="btn btn-primary justify-content-end form-control-sm float-right" onClick="descargar_conciliacion()">Descargar</button></div><div class="col-lg-2 float-left"><button type="button" class="btn btn-primary justify-content-end form-control-sm float-right" onClick="descargar_conciliacion_detalle()">Descargar Detalle</button></div>';
			//document.querySelector('div.toolbar').style.position='absolute';

			
		}
		
		
		function detalle_datatable(codigo_retatil){
			if(codigo_retatil==undefined)
			{
				codigo_retatil=document.getElementById('codigo_retail').value;
			}
			console.log("codigo de retail: "+codigo_retatil);
			
			
			
            $('#lista_oc1').DataTable().clear().destroy();
            $('#lista_oc1').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_datatable_stock.php?var1="+codigo_retatil,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
				"orderMulti": true,
				"dom": '<"toolbar2">frtip',
                "order": [
                    [3, "desc"]
                ],
				"oLanguage": {
							   "sSearch": "Buscar"
							 },
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-left",
                        "width": "1%"
                    },
					{
                        "targets": 1,
                        "className": "text-left",
                        "width": "20%"
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 3,
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 4,
                        "className": "text-center",
                        "width": "10%"
                    }
					
					
                ],
            });
			//document.querySelector('div.toolbar').innerHTML = '<div class="col-lg-2 float-left"><button type="button" class="btn btn-primary justify-content-end form-control-sm float-right" onClick="descargar_conciliacion()">Descargar</button></div><div class="col-lg-2 float-left"><button type="button" class="btn btn-primary justify-content-end form-control-sm float-right" onClick="descargar_conciliacion_detalle()">Descargar Detalle</button></div>';
			//document.querySelector('div.toolbar').style.position='absolute';

			
		}
		
		function generar_tabla_general() 
        {
        	$('#modal-overlay2').modal({backdrop: 'static',keyboard: false})
        	$('#modal-overlay2').modal('show')
            
        	document.getElementById('modal-overlay-texto2').innerHTML="Procesando Conciliación";
        	console.log("Espere un momento por favor... ");	
            
            $.post("dashboard_ajax_stock.php",{proceso:'GENERAR_CONCILIACION'},
            function(result) 
                {
                console.log(result);
                console.log('generar conciliacion');
                var obj = JSON.parse(result);
                if (obj.respuesta=='OK')
                    {
                    console.log("ok")
        			listado_datos();
        			productos_proximos_vencer();
        			setTimeout(function (){
        			$('#modal-overlay2').modal('hide')
        			},500);
        			}
                
                else
                    {
                        console.log("error:"+obj.respuesta)
                        alertaGeneral("Error inesperado","Se ha producido el siguiente error: "+obj.respuesta,"error");
        			    setTimeout(function (){
        			    	$('#modal-overlay2').modal('hide')
        		        },500);
        			    clearInterval(intervalo);	
                    }
                
                });
        	}
		
		function cancelar_carga()
        	{
        			$('#modal-overlay2').modal('hide')
                  
        	}
		
        function listado_datos() {
            $('#lista_oc').DataTable().clear().destroy();
            $('#lista_oc').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_datatable_stock_general.php",
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-center",
                        "width": "12%"
                    },
                    {
                        "targets": 1,
                        "className": "text-left",
                        "width": "50%"
                    },
                    {
                        "targets": 2,
                        "className": "text-left",
                        "width": "15%"
                    },
                    {
                        "targets": 3,
                        "className": "text-left",
                        "width": "15%"
                    },
                    {
                        "targets": 4,
                        "className": "text-left",
                        "width": "10%"
                    }<? if($labor=='OWNER'&&$id_local=='0'){ ?>,
                    {
                        "targets": 5,
                        "className": "text-left",
                        "width": "1%"
                    },
                    {
                        "targets": 6,
                        "className": "text-left",
                        "width": "1%"
                    }
					<? } ?>
                ]

            });
			
			
	
			
        }
        
		
		function productos_proximos_vencer() {
			
            $('#lista_proximo_vencer').DataTable().clear().destroy();
            $('#lista_proximo_vencer').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_datatable_stock_proximo_vencer.php",
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
				"dom": '<"toolbar2">frtip',
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-left",
                        "width": "2%"
                    },
                    {
                        "targets": 1,
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 2,
                        "className": "text-left",
                        "width": "10%"
                    },
                    {
                        "targets": 3,
                        "className": "text-left",
                        "width": "40%"
                    },
                    {
                        "targets": 4,
                        "className": "text-left",
                        "width": "5%"
                    },
                    {
                        "targets": 5,
                        "className": "text-left",
                        "width": "5%"
                    },
                    {
                        "targets": 6,
                        "className": "text-left",
                        "width": "10%"
                    },
                    {
                        "targets": 7,
                        "className": "text-left",
                        "width": "3%"
                    },
                    {
                        "targets": 8,
                        "className": "text-center",
                        "width": "10%",
						"orderable": false
                    }
                ]

            });
			document.querySelector('div.toolbar2').innerHTML = '<div class="col-lg-8 row float-left"><label for="filtro"><button type="button" class="btn btn-primary justify-content-end form-control-sm float-right" onClick="window.open(`dashboard_descarga_productos_proximo_vencer.php`,`_blank`);">Descargar</button></div>';
        }
		
		
		function cerrar_detalle() {
			document.getElementById('listado_detalle').style.display = 'none';
			document.getElementById('listado_general').style.display = 'block';
		}
		
		function cerrar_detalle_lote() {
			document.getElementById('listado_detalle_lote').style.display = 'none';
			document.getElementById('listado_detalle').style.display = 'block';
		}

		function generar_info_rangos(id_local){
			var anio = document.getElementById('anio').value;
			var mes_desde = document.getElementById('mes_desde').value;
			var mes_hasta = document.getElementById('mes_hasta').value;
			console.log('mes_desde: '+mes_desde+" mes_hasta: "+mes_hasta+" anio: "+anio)
			
			if(mes_desde==0)
			{
				alertaGeneral("Error","Debes seleccionar un mes 'Desde'","error");
			}
			else
			{
				if(mes_hasta==0)
				{
					alertaGeneral("Error","Debes seleccionar un mes 'Hasta'","error");
				}
				else
				{
					if(mes_desde>mes_hasta)
					{
						alertaGeneral("Error","El mes 'Hasta' debe ser mayor al mes 'Desde'","error");
					}
					else
					{
						carga_data_rango(anio,mes_desde,mes_hasta,id_local)
					}
				}
			}
		}
		
		function abrir_detalle_local_rango(numero_local,nombre_local,anio,fecha_desde,fecha_hasta){
			
			document.getElementById('listado_general_locales').style.display = 'none';
			document.getElementById('listado_detalle_locales').style.display = 'block';
			detalle_local_rango_datatable(numero_local,nombre_local,anio,fecha_desde,fecha_hasta);
			console.log("numero_local: "+numero_local);
			$('#titulo_detalle_local_rango').html(nombre_local+"-"+anio+"-"+fecha_desde+"-"+fecha_hasta);
			
		}
		
		function cerrar_detalle_local_rango(){
			document.getElementById('listado_detalle_locales').style.display = 'none';
			document.getElementById('listado_general_locales').style.display = 'block';
		}
		
		function detalle_local_rango_datatable(numero_local,nombre_local,anio,fecha_desde,fecha_hasta){
			$('#lista_oc6').DataTable().clear().destroy();
				$('#lista_oc6').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_detalle_local_rango_datatable.php?var1="+numero_local+"&var2="+nombre_local+"&var3="+anio+"&var4="+fecha_desde+"&var5="+fecha_hasta,
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
				"dom": '<"toolbar2">frtip',
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-center",
                        "width": "20%"
                    },
                    {
                        "targets": 1,
                        "className": "text-left",
                        "width": "75%"
                    },
                    {
                        "targets": 2,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    },
                    {
                        "targets": 3,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    },
                    {
                        "targets": 4,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    },
                    {
                        "targets": 5,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    }
                ]

            });
		}
		
		function generar_tabla_rango(anio,mes_desde,mes_hasta,id_local){
			if(id_local=='0'){
				$('#lista_oc5').DataTable().clear().destroy();
				$('#lista_oc5').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_datatable_rango_locales_general.php?var1="+anio+"&var2="+mes_desde+"&var3="+mes_hasta,
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
				"dom": '<"toolbar2">frtip',
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-center",
                        "width": "20%"
                    },
                    {
                        "targets": 1,
                        "className": "text-left",
                        "width": "75%"
                    },
                    {
                        "targets": 2,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    }
                ]

            });
			}
			if(id_local!='0'){
				$('#titulo_detalle_local_rango').html(id_local+"-"+anio+"-"+mes_desde+"-"+mes_hasta);
				document.getElementById('listado_general_locales').style.display = 'none';
				document.getElementById('listado_detalle_locales').style.display = 'block';
				$('#lista_oc6').DataTable().clear().destroy();
				$('#lista_oc6').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_detalle_local_rango_datatable.php?var1="+id_local+"&var3="+anio+"&var4="+mes_desde+"&var5="+mes_hasta,
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
				"dom": '<"toolbar2">frtip',
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-center",
                        "width": "20%"
                    },
                    {
                        "targets": 1,
                        "className": "text-left",
                        "width": "75%"
                    },
                    {
                        "targets": 2,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    },
                    {
                        "targets": 3,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    },
                    {
                        "targets": 4,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    },
                    {
                        "targets": 5,
                        "className": "text-left",
						"orderable": false,
                        "width": "1%"
                    }
                ]

            });
				
			}
	}
		
		
	function carga_data_rango(anio,mes_desde,mes_hasta,id_local)
	{
		var formData = new FormData();
		formData.append("anio",anio);
		formData.append("mes_desde",mes_desde);
		formData.append("mes_hasta",mes_hasta);
		$.ajax({
			type: 'POST',
			url: 'dashboard_ajax_rango.php',
			data: formData,
			processData: false,
			contentType: false,
			success: function (mensaje) 
			{
				$("#contenedor_data_rango").html(mensaje);
				generar_tabla_rango(anio,mes_desde,mes_hasta,id_local);
			}
		})
		.fail(function(mensaje)
		{
			console.log("ERROR DE COMUNICACION");	
		}); 
	}	
    </script>
    <style>
        @media (max-width: 768px)
        {
            .botones
            {
                margin-top:5px;
                margin-bottom:5px;
                margin-left:10px;

            }
        }
        small{
            font-size:65%!important;
        }
        .click{
            transition: font-weight .3s;
            cursor: pointer;
        }
        .click:hover{
            font-weight: bold;
        }
        .modal-larga{
            max-width: 1000px!important;
        }
    </style>
    <title>Gungastore</title>
</head>
<body>
	<div class="app-container" style="background: linear-gradient(to bottom, #6a0dad, #87cefa);">
        <div class="app-main">
            <?php include("sidebar-header.php");?>
            <div class="app-inner-layout app-inner-layout-page">
                <div class="app-inner-layout__content">
                    <div class="tab-content">
                        <div class="container-fluid">
                        <div class="form-group row col-12">	
							
                            <div class="col-12 card">
								<div class="card-header">
									<ul class="nav nav-justified">
										<li class="nav-item"><a data-toggle="tab"
																href="#tab-eg7-0"
																class="active nav-link">Datos Diarios</a></li>
										<li class="nav-item"><a data-toggle="tab"
																href="#tab-eg7-1"
																class="nav-link">Datos por rango fecha</a>
										</li>
									</ul>
								</div>
								<div class="card-body">
									<div class="tab-content">
										<div class="tab-pane active" id="tab-eg7-0"
											 role="tabpanel">
											<div class="row">
												<div class="col-12">
													<div class="main-card col-12 card" id="listado_general">
														<div class="card-body">
															<h5 class="card-title">Stock actual</h5>
															<a href="#" class="justify-content-end" onClick="generar_tabla_general()">
																	<button class="mb-2 mr-2 btn btn-primary form-control-sm">Generar informacion Diaria</button>
																</a>
															<table  id="lista_oc" class="table table-hover table-striped table-bordered">
																<thead>
																	<tr>
																		<th>Codigo Retail</th>
																		<th>Descripcion</th>
																		<th>Inventario actual</th>
																		<th>Ventas Diario</th>
																		<th>Dias desde ultima venta</th>
																		<? if($labor=='OWNER'&&$id_local=='0'){ ?>
																		<th></th>
																		<th></th>
																		<? }  ?>

																	</tr>
																</thead>
																<tbody>

																</tbody>
															</table>
														</div>
													</div>
													<div class="main-card col-12 card" id="listado_detalle" style="display: none">
														<div class="card-header text-white bg-primary">
															<span id="titulo_grafico_detalle"></span>
                                                            <div class="btn-actions-pane-right">
                                                                <button class="btn btn-light btn-sm" onClick="cerrar_detalle()">Cerrar</button>
                                                            </div>
                                                        </div>
														<div class="card-body">
															<div class="tab-content">
																<table  id="lista_oc1"
																class="table table-hover table-striped table-bordered">
																	<thead>
																		<tr>
																			<th>Num. Local</th>
																			<th>Nombre Local</th>
																			<th>Venta sem. actual</th>
																			<th>Dias ultima venta</th>
																			<th>Inv. en gondola<h6 class="mb-0 text-muted"><small><cite title="Source Title">(Diario)</cite></small></h6></th>
																		 </tr>
																	</thead>
																	<tbody>
																		<!-- Acá van los datos. -->
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="padding-top: 15px">
												<div class="col-md-12">
													<? if($labor=='OWNER'){ ?>
												<div class="col-md-12" id="div_grafico" style="display: none">
													<div class="main-card mb-3 card" >

													<div class="card-body" id="carta_grafico">
														<h5 class="card-title">Stock/Ventas</h5>
															<div id="dashboard-sparklines-primary"></div>
														</div>
													</div>
												</div>
												<? } ?>
												</div>
											</div>
											<div class="row" style="padding-top: 15px">
												<div class="col-md-12">
													<div class="main-card mb-3 card" id="listado_proximo_vencer">
														<div class="card-body">
															<h5 class="card-title">Stock proximo a vencer</h5>
															<table  id="lista_proximo_vencer"
																class="table table-hover table-striped table-bordered">
																<thead>
																	<tr>
																		<th>Numero local</th>
																		<th>Nombre local</th>
																		<th>Codigo Retail</th>
																		<th>Descripcion</th>
																		<th>Inventario actual</th>
																		<th>Lote</th>
																		<th>Fecha Vencimiento</th>
																		<th>Dias para perdida</th>
																		<th>Estado</th>
																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab-eg7-1"
											 role="tabpanel">
											<div class="row">
												<div class="col-12">
													<div class="main-card col-12 card" id="listado_general">
														<div class="card-body">
															<h5 class="card-title">Rango de datos </h5>
															<div class="d-flex align-items-center mb-12">
																<!-- Select para los años -->
																<div class="me-3 d-flex flex-column align-items-start" style="padding-left: 5px">
																	<label for="anio" class="form-label mb-1 fw-bold">Año: </label>
																</div>
																<div class="me-3 d-flex flex-column align-items-center" style="padding-left: 5px">
																	<select id="anio" class="form-control" style="width: 100px;">
																		<option value="" selected disabled>-------------</option>
																		<script>
																			const currentYear = new Date().getFullYear();
																			for (let year = 2023; year <= currentYear; year++) {
																				document.write(`<option value="${year}">${year}</option>`);
																			}
																		</script>
																	</select>
																</div>

																<!-- Select para los meses -->
																<div class="d-flex flex-column align-items-start" style="padding-left: 5px">
																	<label for="mes_desde" class="form-label mb-1 fw-bold">Desde: </label>
																</div>
																<div class="d-flex flex-column align-items-center" style="padding-left: 5px">
																	<select id="mes_desde" class="form-control form-select-lg border-primary shadow-sm" style="width: 150px;" >
																		<option value="0" selected disabled>---------</option>
																		<option value="01">Enero</option>
																		<option value="02">Febrero</option>
																		<option value="03">Marzo</option>
																		<option value="04">Abril</option>
																		<option value="05">Mayo</option>
																		<option value="06">Junio</option>
																		<option value="07">Julio</option>
																		<option value="08">Agosto</option>
																		<option value="09">Septiembre</option>
																		<option value="10">Octubre</option>
																		<option value="11">Noviembre</option>
																		<option value="12">Diciembre</option>
																	</select>
																</div>
																<div class="d-flex flex-column align-items-start" style="padding-left: 5px">
																	<label for="mes_hasta" class="form-label mb-1 fw-bold">Hasta: </label>
																</div>
																<div class="d-flex flex-column align-items-center" style="padding-left: 5px">
																	<select id="mes_hasta" class="form-control form-select-lg border-primary shadow-sm" style="width: 150px;">
																		<option value="0" selected disabled>-------------</option>
																		<option value="01">Enero</option>
																		<option value="02">Febrero</option>
																		<option value="03">Marzo</option>
																		<option value="04">Abril</option>
																		<option value="05">Mayo</option>
																		<option value="06">Junio</option>
																		<option value="07">Julio</option>
																		<option value="08">Agosto</option>
																		<option value="09">Septiembre</option>
																		<option value="10">Octubre</option>
																		<option value="11">Noviembre</option>
																		<option value="12">Diciembre</option>
																	</select>
																</div>
																<div class="d-flex flex-column align-items-center" style="padding-left: 5px">
																	<a href="#" class="justify-content-end" onClick="generar_info_rangos('<?=$id_local;?>')">
																		<button class="mb-2 mr-2 btn btn-primary form-control-sm">Generar informacion</button>
																	</a>
																</div>
															</div>
															<div id="contenedor_data_rango">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- Incluir scripts. -->
    <?php include("scripts.php");?>

</body>
</html>
<script>
		// Radial homologacion
function actualiza_graf_hom(porcentaje)
	{
		console.log ("funcion grafico homologacion : "+porcentaje)
		var options_gh = {
			chart: {
				height: 350,
				type: 'radialBar',
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				radialBar: {
					startAngle: -135,
					endAngle: 225,
					hollow: {
						margin: 0,
						size: '70%',
						background: '#fff',
						image: undefined,
						imageOffsetX: 0,
						imageOffsetY: 0,
						position: 'front',
						dropShadow: {
							enabled: true,
							top: 3,
							left: 0,
							blur: 4,
							opacity: 0.24
						}
					},
					track: {
						background: '#fff',
						strokeWidth: '67%',
						margin: 0, // margin is in pixels
						dropShadow: {
							enabled: true,
							top: -3,
							left: 0,
							blur: 4,
							opacity: 0.35
						}
					},

					dataLabels: {
						showOn: 'always',
						name: {
							offsetY: -10,
							show: true,
							color: '#888',
							fontSize: '17px'
						},
						value: {
							formatter: function (val) {
								return parseInt(val)+'%';
							},
							color: '#111',
							fontSize: '36px',
							show: true,
						}
					}
				}
			},
			fill: {
				type: 'gradient',
				gradient: {
					shade: 'dark',
					type: 'horizontal',
					shadeIntensity: 0.5,
					gradientToColors: ['#ABE5A1'],
					inverseColors: true,
					opacityFrom: 1,
					opacityTo: 1,
					stops: [0, 100]
				}
			},
			series: [porcentaje],
			stroke: {
				lineCap: 'round'
			},
			labels: ['Porcentaje'],

	};

		var graf_hom = new ApexCharts
			(
				document.querySelector("#radial_homologacion"),
				options_gh
			);	
		 if (document.getElementById('radial_homologacion')) 
		 	{
				graf_hom.render();
			}		
		
	}
</script>



<div class="modal fade" id="modal-overlay2">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="modal-overlay-texto2">Procesando Archivos</h4><i class="fas fa-2x fa-sync fa-spin"></i> 
        </div>
        <div class="modal-body">
          <p>Estamos procesando el archivo, por favor espere.&hellip;</p>
          <p id="tiempo"></p>
        </div>
		  <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-primary"  onClick="cancelar_carga()">Cerrar</button>
        </div>
    </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade bd-example-modal-xl" id="modal_detalles_lotes" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_detalle_lotes_titulo_stock"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="lista_oc2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>UPC</th>
                            <th>On Stock</th>
							<th>SKU</th>
                            <th>Lote</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-xl" id="modal_detalles_lotes_venta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_detalle_lotes_titulo_ventas"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="lista_oc3" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>UPC</th>
                            <th>Ventas</th>
							<th>SKU</th>
                            <th>Lote</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="modal_b2b_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">B2B</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal_b2b" class="table table-bordered table-striped">
                    <thead>
                        <tr>
						    <th>SKU</th>
                            <th>Descripción</th>
                            <th>Factura (Uni.)</th>
                            <th>Recepción (Uni.)</th>
							<th>Factura ($$)</th>
                            <th>Recepción ($$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="modal_nc_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nota de Crédito (NC)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal_nc" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Factura</th>
                            <th>SKU</th>
                            <th>Descripción</th>
                            <th>NC (Uni)</th>
                            <th>NC ($$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-xl" id="modal_resumen_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Resumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal_resumen" class="table table-bordered table-striped">
                    <thead>
                        <tr>
							<th>Fact. SII</th>
							<th>Destinatario</th>
							<th>Fe#Factura</th>
							<th>NC</th>
							<th>Fact. (Uni)</th>
							<th>Fact. ($$)</th>
							<th>NC (Uni)</th>
							<th>NC ($$)</th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="modal_factura_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Factura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal_detalle" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Factura</th>
                            <th>SKU</th>
                            <th>Descripción</th>
                            <th>Factura (Uni)</th>
                            <th>Factura ($$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg " id="modal_historial_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-larga">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Historial OC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table style="width: 100%;" id="table_modal_historial" class="table table-hover table-striped table-bordered display compact" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Orden Compra</th>
                            <th>Cadena</th>
                            <th>Factura (Uni)</th>
                            <th>Factura ($$)</th>
                            <th>NC (Uni)</th>
                            <th>NC ($$)</th>
                            <th>Recepción (Uni)</th>
                            <th>Recepción ($$)</th>
                            <th>Estado</th>
                            <th>Fecha Conciliación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--        MODAL HREF CASOS    -->
<div class="modal fade" id="modal_casos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ATENCIÓN!!!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Esta orden de compra ya tiene un caso asociado. ¿Desea ver el detalle? </p>
            </div>
            <div class="modal-footer">
  				<button type="button" style="margin-right: auto" class="btn btn-primary" name="btn_redireccion" id="btn_redireccion" >Sí, quiero </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
	









	
        	
$(document).ready(function() {

    setTimeout(function () {

		
		
	 }, 1000);
	
});	

</script>