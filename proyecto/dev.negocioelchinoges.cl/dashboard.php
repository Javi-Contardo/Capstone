<?php include("puerta_principal.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
?>
<html lang="es"><head>
    <?php include("scripts-header.php");?>
    <script>
        $(document).ready(function() {
            $("#act-dashboard").addClass("mm-active");
            $("#titulo-cabecera").text("Dashboard");
            $("#descripcion-cabecera").text("Reportería");
            //buscar();no activar
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
			
			listado_datos();
			
			
        });

		
		function abrir_detalle(codigo_retail, sku, descripcion, numero_local) {
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
			console.log("sku: "+sku);
			/*$.post("conciliacion_comercial_walmart_ajax.php"), {proceso:'DATOS_GRAFICO', codigo_retail:codigo_retail, numero_local:numero_local},
			function(datos) 
			{
				console.log("estos son los datos de vueltos: "+datos);
			}*/
			/*$('#ctd_existencia_cd1').html(ctd_existencia_cd);*/
			$('#titulo_grafico_detalle').html(sku+"-"+descripcion);
			$('#codigo_retail').val(codigo_retail);
			
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
                    },
                    {
                        "targets": 5,
                        "className": "text-left",
                        "width": "1%"
                    }
                ]

            });
			
			
	
			
        }
        
		function cerrar_detalle() {
			document.getElementById('listado_detalle').style.display = 'none';
			document.getElementById('listado_general').style.display = 'block';
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
    <div class="app-container app-theme-gray">
        <div class="app-main">
            <?php include("sidebar-header.php");?>
            <div class="app-inner-layout app-inner-layout-page">
                <div class="app-inner-layout__content">
                    <div class="tab-content">
                        <div class="container-fluid">
                        <!--<div class="form-group row col-lg-12">	
					        <label for="filtro">Buscar por:</label>
					        <div class="col-lg-2">
					            <select name="filtro" id="filtro" onChange="busca_filtro();" class="select2 mb-2 form-control-sm form-control" style="width: 100%;" >
					            	<option value="" selected disabled> Seleccione una opción</option>
					            	<option value="B2B">B2B</option>
					            </select>
                            </div>
					        <div class="col-lg-2">
					            <select name="listado" id="listado" class="select2 mb-2 form-control-sm form-control" style="width: 100%;">
                                    <option value="" selected disabled> Seleccione una opción</option>
					            </select>
                            </div>
					        <div class="col-lg-2">
					            <input type="button" name="filtrar" id="filtrar" value="Filtrar" class="boton boton--verde mb-2 form-control-sm form-control" onClick="buscar('filtrar')">
					        </div>
                            <div class="col-lg-12" id="resultadoFiltro"></div>
						</div>-->
							<!-- ****************DATATABLE DE LA LISTA DE OC*************** -->
							<div class="row">
                                <div class="col-md-12">
                                    <div class="main-card mb-3 card" id="listado_general">
                                        <div class="card-body">
                                            <h5 class="card-title">Stock actual</h5>
											<a href="#" class="justify-content-end" onClick="generar_tabla_general()">
                                                    <button class="mb-2 mr-2 btn btn-primary form-control-sm">Generar informacion Diaria</button>
                                                </a>
                                            <table  id="lista_oc"
                                                class="table table-hover table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SKU</th>
                                                        <th>Descripcion</th>
                                                        <th>Inventario actual</th>
                                                        <th>Ventas Semana Actual</th>
                                                        <th>Dias desde ultima venta</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Acá van los datos. -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
									<div class="main-card col-12 card" id="listado_detalle" style="display: none">
														<div class="card-header text-white bg-primary"><span id="titulo_grafico_detalle"></span>
                                                            <div class="btn-actions-pane-right">
                                                                <button class="btn btn-light btn-sm" onClick="cerrar_detalle()">Cerrar</button>
                                                            </div>
                                                        </div>
														<div class="card-body">
															<div class="tab-content pb-3" >
																<div class="card col-12 widget-content bg-happy-green">
																	<div class="widget-content-wrapper text-white">
																		<div class="widget-content-left">
																			<div class="widget-heading">Stock CD</div>
																			<div class="widget-subheading">Stock disponible en centros de distribución</div>
																		</div>
																		<div class="widget-content-right">
																			<div class="widget-numbers text-white"><span id="ctd_existencia_cd1"></span></div>
																		</div>
																	</div>
																</div>
																<div class="tab-pane fade active show" id="sales-tab-1">
																	<div class="text-center">
																		<h5 class="menu-header-title" id="titulo_grafico"></h5>
																		<h6 class="menu-header-subtitle opacity-6" style="display: none" id="titulo_grafico1">Resultado de las últimas 10 semanas</h6>
																	</div>
																	<div id="dashboard-sparklines-primary"></div>
																</div>
																<div class="tab-pane fade" id="sales-tab-2">
																	<div class="text-center">
																		<h5 class="menu-header-title">Tabbed Content</h5>
																		<h6 class="menu-header-subtitle opacity-6">Example of
																			various options built with KeroUI</h6>
																	</div>
																	<div class="card-hover-shadow-2x widget-chart widget-chart2 bg-premium-dark text-left mt-3 card">
																		<div class="widget-chart-content text-white">
																			<div class="widget-chart-flex">
																				<div class="widget-title">Sales</div>
																				<div class="widget-subtitle opacity-7">Monthly
																					Goals
																				</div>
																			</div>
																			<div class="widget-chart-flex">
																				<div class="widget-numbers text-success">
																					<small>$</small>
																					<span>976</span>
																					<small class="opacity-8 pl-2">
																						<i class="fa fa-angle-up"></i>
																					</small>
																				</div>
																				<div class="widget-description ml-auto opacity-7">
																					<i class="fa fa-angle-up"></i>
																					<span class="pl-1">175%</span>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="text-center mt-3">
																		<button class="btn-pill btn-shadow btn-wide fsize-1 btn btn-success btn-lg">
																			<span class="mr-2 opacity-7">
																				<i class="icon icon-anim-pulse ion-ios-analytics-outline"></i>
																			</span>
																			<span class="mr-1">View Complete Report</span>
																		</button>
																	</div>
																</div>
															</div>
															<div class="tab-content">
																<table  id="lista_oc1"
																class="table table-hover table-striped table-bordered">
																<thead>
																	<tr>
																		<th>Num. Local</th>
																		<th>Nombre Local</th>
																		<th>Venta sem. actual</th>
																		<th>Dias ultima venta</th>
																		<th>Inv. en gondola<h6 class="mb-0 text-muted"><small><cite title="Source Title">(Prom. ult. 7 dias)</cite></small></h6></th>
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
							
							
							
							
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--DRAWER START-->
    <div class="app-drawer-overlay d-none animated fadeIn"></div>
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
<div class="modal fade bd-example-modal-xl" id="modal_detalles_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Detalle de producto(s) no conciliado(s) </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Descripción</th>
                            <th>Factura (Uni.)</th>
							<th>NC (Uni.)</th>
                            <th>Recepción (Uni.)</th>
                            <th>Dif. (Uni.)</th>
							<th>Factura ($$)</th>
							<th>NC ($$)</th>
                            <th>Recepción ($$)</th>
                            <th>Dif. ($$)</th>
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