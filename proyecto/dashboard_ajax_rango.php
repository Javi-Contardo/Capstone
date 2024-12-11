<?
include("puerta_principal.php");
$anio=isset($_POST['anio'])?$_POST['anio']:null;
$mes_desde=isset($_POST['mes_desde'])?$_POST['mes_desde']:null;
$mes_hasta=isset($_POST['mes_hasta'])?$_POST['mes_hasta']:null;

?>
<script>

</script>


<div class="row" style="padding-top: 20px">
												<div class="col-12">
													<div class="main-card col-12 card" id="listado_general_locales">
														<div class="card-body">
															<? if($id_local!='0'){ ?>
															<h5 class="card-title">Stock promedio/mes</h5>
															<table  id="lista_oc4" class="table table-hover table-striped table-bordered">
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
															<?} if($id_local=='0'){ ?>
															<h5 class="card-title">Seleccione una sucursal</h5>
															<table  id="lista_oc5" class="table table-hover table-striped table-bordered">
																<thead>
																	<tr>
																		<th>Nombre</th>
																		<th>Direccion</th>
																		<th></th>
																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>
															<? }?>
														</div>
													</div>
													<div class="main-card col-12 card" id="listado_detalle_locales" style="display: none">
														<div class="card-header text-white bg-primary">
															<span id="titulo_detalle_local_rango"></span>
															<? if($id_local=='0'){ ?>
                                                            <div class="btn-actions-pane-right">
																
                                                                <button class="btn btn-light btn-sm" onClick="cerrar_detalle_local_rango()">Cerrar</button>
                                                            </div>
															<? } ?>
                                                        </div>
														<div class="card-body">
															<div class="tab-content">
																<table  id="lista_oc6"
																class="table table-hover table-striped table-bordered">
																	<thead>
																		<tr>
																			<th all>Codigo Retail</th>
																			<th all>Nombre producto</th>
																			<th all>Año</th>
																			<th all>Mes</th>
																			<th all>Promedio Ventas</th>
																			<th all>Promedio Stock</th>
																		 </tr>
																	</thead>
																	<tbody>
																		<!-- Acá van los datos. -->
																	</tbody>
																</table>
															</div>
														</div>
													</div>
													<div class="main-card col-12 card" id="listado_detalle_locales" style="display: none">
														<div class="card-body">
															<h5 class="card-title">Stock promedio/mes</h5>
															<table  id="lista_oc4" class="table table-hover table-striped table-bordered">
																<thead>
																	<tr>
																		<th>Codigo Retail</th>
																		<th>Descripcion</th>
																		<th>Inventario actual</th>
																		<th>Ventas Diario</th>
																		<th>Dias desde ultima venta</th>
																		<th></th>
																		<th></th>

																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>