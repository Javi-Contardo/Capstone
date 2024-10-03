<?php include("puerta_principal.php");?>
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
			
			
			
			
        });

        
        
		

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
                                <div class="col-md-3">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">Inicio pendiente</h5>
                                            <canvas id="grafico_casos"></canvas>
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
        <h4 class="modal-title" id="modal-overlay-texto2">Procesando Archivo</h4><i class="fas fa-2x fa-sync fa-spin"></i> 
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