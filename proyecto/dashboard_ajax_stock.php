<?
//error_reporting(E_ALL);
//ini_set('display_errors', '1');


	header("Access-Control-Allow-Origin: *");
	set_time_limit (3600);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
	include("puerta_principal.php");
	$proceso=isset($_POST['proceso'])?$_POST['proceso']:null;
	$filtro=isset($_POST['filtro'])?$_POST['filtro']:null;
	if ($proceso=='ACT_OC')
	{
		/*
		###############################
        ###############################
                    FILTROS
        ###############################
        ###############################	
		*/ 
		$resultado=$mysqli->query("SELECT id_acceso FROM acceso WHERE email='$email'");
		if ($sacar_id=$resultado->fetch_row())
		{
			$id_acceso=$sacar_id[0];
		}
		$busca_filtro="";
		$busca_filtro1="";
		$busca_filtro2="";
		$busca_filtro3="";
		$busca_filtro4="";
		$busca_filtro5="";
		$busca_filtro6="";
		$busca_filtro7="";
		$j=0;
		$resultlist=$mysqli->query("SELECT * FROM conciliacion_general_filtro WHERE id_acceso='$id_acceso' ORDER BY id_filtro ASC ");
		while ($row=$resultlist->fetch_row())
		{
			if ($row[1]=='CADENA')
				{
					if ($busca_filtro1!=''){$busca_filtro1.=" or ";}
					$busca_filtro1.=" cod_b2b='$row[2]'";
				}
			if ($row[1]=='OC')
				{
					if ($busca_filtro2!=''){$busca_filtro2.=" or ";}
					$busca_filtro2.=" oc='$row[2]'";
				}
			if ($row[1]=='ESTADO')
				{
					if ($busca_filtro3!=''){$busca_filtro3.=" or ";}
					$busca_filtro3.=" estado='$row[2]'";
				}
			if ($row[1]=='PERIODO')
				{
				$fecha_hoy="$fechabase";
				$fecha_5dias=date("Y-m-d",strtotime("-5 days",strtotime("$fechabase")));
				$fecha_30dias=date("Y-m-d",strtotime("-30 days",strtotime("$fechabase")));
				$fecha_60dias=date("Y-m-d",strtotime("-60 days",strtotime("$fechabase")));
				$fecha_365dias=date("Y-m-d",strtotime("-365 days",strtotime("$fechabase")));
				
				
					if ($busca_filtro4!=''){$busca_filtro4.=" or ";}
				if($row[2]=='0 a 5'){$busca_filtro4.=" fecha_1ra_factura BETWEEN '$fecha_5dias' AND '$fecha_hoy'";}
				if($row[2]=='5 a 30'){$busca_filtro4.=" fecha_1ra_factura BETWEEN '$fecha_30dias' AND '$fecha_5dias'";}
				if($row[2]=='30 a 60'){$busca_filtro4.=" fecha_1ra_factura BETWEEN '$fecha_60dias' AND '$fecha_30dias'";}
				if($row[2]=='Más de 60'){$busca_filtro4.=" fecha_1ra_factura BETWEEN '$fecha_365dias' AND '$fecha_60dias'";}
					
				}
			$j++;
		}
		if ($busca_filtro1!=''){$busca_filtro.=" and ($busca_filtro1)";	}
		if ($busca_filtro2!=''){$busca_filtro.=" and ($busca_filtro2)";	}
		if ($busca_filtro3!=''){$busca_filtro.=" and ($busca_filtro3)";	}
		if ($busca_filtro4!=''){$busca_filtro.=" and ($busca_filtro4)";	}
		if ($busca_filtro5!=''){$busca_filtro.=" and ($busca_filtro5)";	}
		if ($busca_filtro6!=''){$busca_filtro.=" and ($busca_filtro6)";	}
		if ($busca_filtro7!=''){$busca_filtro.=" and ($busca_filtro7)";	}
		//Sumar total OC.
		$respuesta_proc1 = $mysqli->query("SELECT count(id_conciliacion) FROM conciliacion_general WHERE id_cliente='$id_cliente' $busca_filtro");
		if($suma_oc = $respuesta_proc1->fetch_row())
		{
			$total_oc=$suma_oc[0];
		}
		else{
			$total_oc=0;
		}
		//Sumar total OC conciliadas.
		$respuesta_proc = $mysqli->query("SELECT count(id_conciliacion) FROM conciliacion_general WHERE id_cliente='$id_cliente' AND estado='CONCILIADO' $busca_filtro");
		if($suma_pr = $respuesta_proc->fetch_row())
		{
			$oc_conciliadas=$suma_pr[0];
		}
		else{
			$oc_conciliadas=0;
		}
/*		Sumar total OC conciliadas x unidad.
		$respuesta_proc2 = $mysqli->query("SELECT count(estado) FROM conciliacion_general WHERE id_cliente='$id_cliente' AND estado='NO CONCILIADO X $$' $busca_filtro");
		if($suma_pr = $respuesta_proc2->fetch_row())
		{
			$oc_conciliadas_x_dinero=$suma_pr[0];
		}
		else{
			$oc_conciliadas_x_dinero=0;
		} 	

		Sumar total OC conciliadas x $$.
		$respuesta_proc3 = $mysqli->query("SELECT count(estado) FROM conciliacion_general WHERE id_cliente='$id_cliente' AND estado='NO CONCILIADO X UNI' $busca_filtro");
		if($suma_rp = $respuesta_proc3->fetch_row())
		{
			$oc_conciliadas_x_uni=$suma_rp[0];
		}
		else{
			$oc_conciliadas_x_uni=0;
		}*/
		//Sumar total OC no conciliadas..
		$respuesta_proc4 = $mysqli->query("SELECT count(id_conciliacion) FROM conciliacion_general WHERE id_cliente='$id_cliente' AND estado='ESPERANDO B2B' $busca_filtro");
		if($suma_rc = $respuesta_proc4->fetch_row())
		{
			$oc_esperando_b2b=$suma_rc[0];
		}
		else{
			$oc_esperando_b2b=0;
		}
		$respuesta_proc5 = $mysqli->query("SELECT count(id_conciliacion) FROM conciliacion_general WHERE id_cliente='$id_cliente' AND estado='OC CON DIFERENCIA' $busca_filtro");
		if($suma_pr = $respuesta_proc5->fetch_row())
		{
			$oc_con_diferencia=$suma_pr[0];
		} 
		//Sumar total OC conciliadas x $$. +  x unidad.
		else{
			$oc_con_diferencia=0;
		}
		
		$respuesta_proc5 = $mysqli->query("SELECT count(id_conciliacion) FROM conciliacion_general WHERE id_cliente='$id_cliente' AND estado='CONCILIADO CON DIFERENCIA' $busca_filtro");
		if($suma_pr = $respuesta_proc5->fetch_row())
		{
			$conciliadas_diferencia=$suma_pr[0];
		} 
		//Sumar total OC conciliadas x $$. +  x unidad.
		else{
			$conciliadas_diferencia=0;
		}
		
		$datos = array('total_oc' => $total_oc,'oc_conciliadas' => $oc_conciliadas,'oc_esperando_b2b' => $oc_esperando_b2b, 'oc_con_diferencia' => $oc_con_diferencia, 'conciliadas_diferencia' => $conciliadas_diferencia);
		echo json_encode($datos);	
	}

	if($proceso=='GENERAR_CONCILIACION')
	{
		$respuesta='OK';
		$fechaInicioSemana = new DateTime();
		$fechaInicioSemana->setISODate($anoguia, $semcalendario);
		$fechaInicioSemana = $fechaInicioSemana->format('Y-m-d');

		$fecha_actual=new DateTime($fechabase);;
		$fecha_actual = $fecha_actual->format('Y-m-d');

		$fechaInicioSemana11 = new DateTime($fecha_actual);
		$fechaInicioSemana11->modify('-6 months');
		$fechaInicioSemana11 = $fechaInicioSemana11->format('Y-m-d');

		$fechaTerminoSemana = new DateTime($fechaInicioSemana);
		$fechaTerminoSemana->modify('+6 days');
		$fechaTerminoSemana = $fechaTerminoSemana->format('Y-m-d');

		$fechaInicioSemana2 = new DateTime($fechaInicioSemana);
		$fechaInicioSemana2->modify('-7 days');
		$fechaInicioSemana2 = $fechaInicioSemana2->format('Y-m-d');

		$fechaTerminoSemana2 = new DateTime($fechaInicioSemana2);
		$fechaTerminoSemana2->modify('+6 days');
		$fechaTerminoSemana2 = $fechaTerminoSemana2->format('Y-m-d');

		$fechaInicioSemana3 = new DateTime($fechaInicioSemana);
		$fechaInicioSemana3->modify('-14 days');
		$fechaInicioSemana3 = $fechaInicioSemana3->format('Y-m-d');

		$fechaTerminoSemana3 = new DateTime($fechaInicioSemana3);
		$fechaTerminoSemana3->modify('+6 days');
		$fechaTerminoSemana3 = $fechaTerminoSemana3->format('Y-m-d');

		$fechaInicioSemana4 = new DateTime($fechaInicioSemana);
		$fechaInicioSemana4->modify('-21 days');
		$fechaInicioSemana4 = $fechaInicioSemana4->format('Y-m-d');

		$fechaTerminoSemana4 = new DateTime($fechaInicioSemana4);
		$fechaTerminoSemana4->modify('+6 days');
		$fechaTerminoSemana4 = $fechaTerminoSemana4->format('Y-m-d');
		
		$result1 = $mysqli->query("select * from homologacion");
		while ($row1=$result1->fetch_assoc())
		{
			$id_homologacion=$row1['id_homologacion'];
			$descripcion=$row1['descripcion'];
			$codigo_retail=$row1['codigo_retail'];
			
			$result2 = $mysqli->query("select max(diario) as ultima_venta from comercial_ventas where numero_articulo='$codigo_retail' order by diario");
			if ($row2=$result2->fetch_assoc())
			{
				$ultima_venta=$row2['ultima_venta'];
			}
			
			
			

			// Define las fechas
			$fecha1 = new DateTime("$ultima_venta");
			$fecha2 = new DateTime("$fechabase");

			// Calcula la diferencia
			$diferencia = $fecha2->diff($fecha1);
			$diferencia1=$diferencia->days;
			// Muestra la diferencia en días
			$sumacnt=0;
			$result3 = $mysqli->query("select sum(cnt_pos) as suma from comercial_ventas where numero_articulo='$codigo_retail' and diario='$fecha_actual' ");
				if ($row3=$result3->fetch_assoc())
				{
					$sumacnt=$row3['suma'];
				}
				$result31 = $mysqli->query("select coalesce(sum(cnt_pos),0) as suma from comercial_ventas where numero_articulo='$codigo_retail' and diario between '$fechaInicioSemana2' and '$fechaTerminoSemana2'  ");
				if ($row31=$result31->fetch_assoc())
				{
					$sumacnt2=$row31['suma'];
				}
				$result32 = $mysqli->query("select coalesce(sum(cnt_pos),0) as suma from comercial_ventas where numero_articulo='$codigo_retail' and diario between '$fechaInicioSemana3' and '$fechaTerminoSemana3'  ");
				if ($row32=$result32->fetch_assoc())
				{
					$sumacnt3=$row32['suma'];
				}
				$result33 = $mysqli->query("select coalesce(sum(cnt_pos),0) as suma from comercial_ventas where numero_articulo='$codigo_retail' and diario between '$fechaInicioSemana4' and '$fechaTerminoSemana4'  ");
				if ($row33=$result33->fetch_assoc())
				{
					$sumacnt4=$row33['suma'];
				}

				$sumacntpromedio=($sumacnt+$sumacnt2+$sumacnt3+$sumacnt4)/4;

			$on_hand_general=0;
			$fecha_ultima_carga=$fechabase;
			$result5 = $mysqli->query("select fecha_carga from comercial_stock where numero_articulo='$codigo_retail'  order by fecha_carga desc");
			if ($row5=$result5->fetch_assoc())
			{
				$fecha_ultima_carga=$row5['fecha_carga'];
			}
			$result5 = $mysqli->query("select sum(cantidad_existente_tienda) as caet from comercial_stock where numero_articulo='$codigo_retail'  and fecha_carga='$fecha_ultima_carga'");
			if ($row5=$result5->fetch_assoc())
			{
				$on_hand_general=$row5['caet'];
			}

			if($sumacnt==null)
			{
				$sumacnt=0;
			}
			$cantidad_actual_existente_tienda=0;
			$result4 = $mysqli->query("select cantidad_existente_tienda as cantidad from comercial_stock where numero_articulo='$codigo_retail' and fecha_carga='$fechabase'");
			while ($row4=$result4->fetch_assoc())
			{
				$cantidad_actual_existente_tienda+=$row4['cantidad'];
			}
			
			if($cantidad_actual_existente_tienda==null)
			{
				$cantidad_actual_existente_tienda=0;
			}
			
			$nombre_local='General';
			$numero_local='0';
			$result7 = $mysqli->query("select * from comercial_stock_out where numero_semana='$semcalendario' and codigo_retail='$codigo_retail' and numero_local='0' ");
			if ($row7=$result7->fetch_assoc())
			{
				$id_registro=$row7['id'];
				
				/*print("UPDATE  comercial_stock_out SET ventas_semana_actual='$sumacnt' ,ultima_venta='$ultima_venta' , dias_desde_ultima_venta='$diferencia1', on_hand='$cantidad_actual_existente_tienda' where id='$id_registro'");*/
				$mysqli->query("UPDATE  comercial_stock_out SET ventas_semana_actual='$sumacnt' ,ultima_venta='$ultima_venta' , dias_desde_ultima_venta='$diferencia1', on_hand='$cantidad_actual_existente_tienda' where id='$id_registro'");
			}
			else
			{
				/*print("INSERT INTO comercial_stock_out(id_homologacion,sku,codigo_retail,descripcion,numero_local,nombre_local,numero_semana,ventas_semana_actual,ultima_venta,dias_desde_ultima_venta, on_hand,fecha_subida) VALUES('$id_homologacion','$sku','$codigo_retail','$descripcion','0','$nombre_local','$semcalendario','$sumacnt','$ultima_venta','$diferencia1','$cantidad_actual_existente_tienda','$fecha_actual')");*/
				$mysqli->query("INSERT INTO comercial_stock_out(id_homologacion,codigo_retail,descripcion,numero_local,nombre_local,numero_semana,ventas_semana_actual,ultima_venta,dias_desde_ultima_venta, on_hand,fecha_subida) VALUES('$id_homologacion','$codigo_retail','$descripcion','0','$nombre_local','$semcalendario','$sumacnt','$ultima_venta','$diferencia1','$cantidad_actual_existente_tienda','$fecha_actual')");
			}

			$result6 = $mysqli->query("select * from locales where id!='0'");
			while ($row6=$result6->fetch_assoc())
			{
				
				$nombre_local=$row6['nombre_local'];
				$numero_local=$row6['id'];
				
					$result5 = $mysqli->query("select sum(cantidad_existente_tienda) as caet from comercial_stock where numero_articulo='$codigo_retail' and numero_tienda='$numero_local' and fecha_carga='$fecha_ultima_carga'");
					if ($row5=$result5->fetch_assoc())
					{
						$on_hand_general=$row5['caet'];
					}

					if($on_hand_general==null)
					{
						$on_hand_general=0;
					}



					$ultima_venta='0001-01-01';
					$result2 = $mysqli->query("select max(diario) as ultima_venta from comercial_ventas where numero_articulo='$codigo_retail' and numero_tienda='$numero_local'  order by diario");
					if ($row2=$result2->fetch_assoc())
					{
						$ultima_venta=$row2['ultima_venta'];
					}

					$cantidad_actual_existente_tienda=0;
					$result2 = $mysqli->query("select round(avg(cantidad_existente_tienda),0) as promediostock from comercial_stock where numero_articulo='$codigo_retail' and numero_tienda='$numero_local' and fecha_carga between '$fechaInicioSemana11' and '$fecha_actual'");
					if ($row2=$result2->fetch_assoc())
					{
						$cantidad_actual_existente_tienda=$row4['promediostock'];
					}
					if($cantidad_actual_existente_tienda==null)
					{
						$cantidad_actual_existente_tienda=0;
					}


					$cantidad_actual_existente_tienda_ultimo=0;

					$result2 = $mysqli->query("select cantidad_existente_tienda from comercial_stock where numero_articulo='$codigo_retail' and numero_tienda='$numero_local'  order by fecha_carga desc");
					if ($row2=$result2->fetch_assoc())
					{
						$cantidad_actual_existente_tienda_ultimo=$row4['cantidad_existente_tienda'];
					}


					// Define las fechas
					$fecha1 = new DateTime("$ultima_venta");
					$fecha2 = new DateTime("$fechabase");

					// Calcula la diferencia
					$diferencia = $fecha2->diff($fecha1);
					$diferencia1=$diferencia->days;
					// Muestra la diferencia en días

					$sumacnt=0;
					/// pendiente a revisar
					$result3 = $mysqli->query("select cnt_pos as suma from comercial_ventas where numero_articulo='$codigo_retail' and diario='$fecha_actual' and numero_tienda='$numero_local' ");
					if ($row3=$result3->fetch_assoc())
					{
						$sumacnt=$row3['suma'];
					}
					$result31 = $mysqli->query("select coalesce(sum(cnt_pos),0) as suma from comercial_ventas where numero_articulo='$codigo_retail' and diario between '$fechaInicioSemana2' and '$fechaTerminoSemana2' and numero_tienda='$numero_local'");
					if ($row31=$result31->fetch_assoc())
					{
						$sumacnt2=$row31['suma'];
					}
					$result32 = $mysqli->query("select coalesce(sum(cnt_pos),0) as suma from comercial_ventas where numero_articulo='$codigo_retail' and diario between '$fechaInicioSemana3' and '$fechaTerminoSemana3' and numero_tienda='$numero_local'");
					if ($row32=$result32->fetch_assoc())
					{
						$sumacnt3=$row32['suma'];
					}
					$result33 = $mysqli->query("select coalesce(sum(cnt_pos),0) as suma from comercial_ventas where numero_articulo='$codigo_retail' and diario between '$fechaInicioSemana4' and '$fechaTerminoSemana4' and numero_tienda='$numero_local'");
					if ($row33=$result33->fetch_assoc())
					{
						$sumacnt4=$row33['suma'];
					}

					$sumacntpromedio=($sumacnt+$sumacnt2+$sumacnt3+$sumacnt4)/4;




					if($sumacnt==null)
					{
						$sumacnt=0;
					}

					$sem_inventario='0';
					if($sumacntpromedio>0){
					$sem_inventario=$cantidad_actual_existente_tienda_ultimo/$sumacntpromedio;
					$sem_inventario=number_format($sem_inventario,1,'.','');
					}



					$result7 = $mysqli->query("select * from comercial_stock_out where fecha_subida='$fechabase' and codigo_retail='$codigo_retail' and numero_local='$numero_local' ");
					if ($row7=$result7->fetch_assoc())
					{
						$id_registro=$row7['id'];

						$mysqli->query("UPDATE  comercial_stock_out SET ventas_semana_actual='$sumacnt' ,ultima_venta='$ultima_venta' , dias_desde_ultima_venta='$diferencia1', on_hand='$on_hand_general'  where id='$id_registro'");
					}
					else
					{
						$mysqli->query("INSERT INTO comercial_stock_out(id_homologacion,sku,codigo_retail,descripcion,numero_local,nombre_local,numero_semana,ventas_semana_actual,ultima_venta,dias_desde_ultima_venta, on_hand,fecha_subida) VALUES('$id_homologacion','$sku','$codigo_retail','$descripcion','$numero_local','$nombre_local','$semcalendario','$sumacnt','$ultima_venta','$diferencia1','$on_hand_general','$fechabase')");
					}
				

			}
		}
		
		
		$datos = array('respuesta' => $respuesta);
		echo json_encode($datos);
	}


	if($proceso=='DATOS_GRAFICADO')
	{					
		$codigo_reatil_grafico=$_POST['codigo_reatil_grafico'];
		$nombres_locales_array=array();
		$on_hand=array();
		$ventas_semana_actual=array();
		
		
		$fechaInicioSemana = $fechabase;
			$fecha_semana_nueva2= date("W",strtotime($fechaInicioSemana));
		
		$resultlist=$mysqli->query("SELECT nombre_local,on_hand,ventas_semana_actual FROM comercial_stock_out WHERE codigo_retail='$codigo_reatil_grafico' and fecha_subida='$fechabase' and numero_local!='0'");
		while ($row4=$resultlist->fetch_assoc())
		{
			$nombres_locales_array[]=$row4['nombre_local'];
			$on_hand[]=$row4['on_hand'];
			$ventas_semana_actual[]=$row4['ventas_semana_actual'];
		}

		$fechaInicioSemana=date("Y-m-d",strtotime("-7 days", strtotime("$fechaInicioSemana")));
		
		$nombres_locales_array = array_reverse($nombres_locales_array);
		$on_hand = array_reverse($on_hand);
		$ventas_semana_actual = array_reverse($ventas_semana_actual);
		$datos = array('nombres_locales_array' => $nombres_locales_array,'on_hand'=>$on_hand,'ventas_semana_actual'=>$ventas_semana_actual);
		echo json_encode($datos);
	}

	if($proceso=='TABLA_PROMEDIO_4_SEMANAS')
	{		
		$codigo_reatil_validador=isset($_POST['codigo_reatil_validador'])?$_POST['codigo_reatil_validador']:null;
		$num_tienda=isset($_POST['num_tienda'])?$_POST['num_tienda']:null;
		?>
		<h5 class="card-title"></h5>
		<table class="mb-0 table">
			<thead>
				<tr>
					<th style="width: 50%">Numero semana</th>
					<th style="width: 50%">Promedio</th>
				</tr>
			</thead>
			<tbody>
				<?
					$fechaInicioSemana = new DateTime();
					$fechaInicioSemana->setISODate($anoguia, $semcalendario);

					// Clona el objeto de fecha para no modificar $fechaInicioSemana
					$fechaTerminoSemana = clone $fechaInicioSemana;
					$fechaTerminoSemana->modify('+6 days');
					$semcalendario1=$semcalendario;
					for($i = 1; $i <= 4; $i++) 
					{
						$sumacnt=0;
						// Convierte las fechas a formato string solo para la consulta SQL
						$fechaInicio = $fechaInicioSemana->format('Y-m-d');
						$fechaTermino = $fechaTerminoSemana->format('Y-m-d');
						$result3 = $mysqli->query("SELECT coalesce(SUM(cnt_pos),0) AS suma FROM comercial_ventas 
												   WHERE numero_articulo='$codigo_reatil_validador' 
												   AND diario BETWEEN '$fechaInicio' AND '$fechaTermino' 
												    and numero_tienda='$num_tienda'");

						if ($row3 = $result3->fetch_assoc()) {
							$sumacnt = $row3['suma'];
							$sumacnt=round($sumacnt,0);
						}
						if($sumacnt==''){$sumacnt='SIN DATA';}
						?>
					<tr onClick="mostrar_detalle('<?php echo $semcalendario1; ?>')" style="cursor: pointer; background: #00DC61;">
						<th scope="row" style="color: white;">W<?php echo $semcalendario1; ?></th>
						<td style="color: white;"><?php echo $sumacnt; ?></td>
					</tr>
						<tr style="display: none" id="cabecera_<?=$semcalendario1?>">
							<th scope="row">Dia venta</th>
							<td>Unidades vendidas</td>
						</tr>
						<?php
						$result3 = $mysqli->query("SELECT * FROM comercial_ventas 
												   WHERE numero_articulo='$codigo_reatil_validador' 
												   AND diario BETWEEN '$fechaInicio' AND '$fechaTermino' 
												    and numero_tienda='$num_tienda' order by diario desc");
						
						while ($row3 = $result3->fetch_assoc()) 
						{	
							$diario = $row3['diario'];
							$cnt_pos = $row3['cnt_pos'];
							?>
								<tr style="display: none" id="detalle_<?=$semcalendario1?>" class="detalle_<?=$semcalendario1?>" >
									<th scope="row"><?php echo $diario; ?></th>
									<td><?php echo $cnt_pos; ?></td>
								</tr>
							<?php
						}
						?>
				</div>
        </div> <?
						// Resta 7 días a cada objeto DateTime para la próxima semana
						$fechaInicioSemana->modify('-7 days');
						$fechaTerminoSemana->modify('-7 days');
						$semcalendario1=$semcalendario1-1;
					}

				?>
			</tbody>
		</table>
<?
	}

	if($proceso=='VER_CASO')
	{		
		$orden_compra=$_POST['orden_compra'];
		
		$resultlist=$mysqli->query("SELECT id_caso FROM casos WHERE orden_compra='$orden_compra' ");
		if ($row2=$resultlist->fetch_assoc())
		{
			$id_caso=$row2['id_caso'];
			
			$datos = array('status' => '200', 'mensaje' => 'Orden de compra', 'id_caso' => $id_caso );
			echo json_encode($datos);
		}
	}
	

	if ($proceso=='ELIMINAR_OC')
    	{
		//error_reporting(E_ALL);
		//ini_set('display_errors', '1');
		    $oc=$_POST['oc'];
			$b2b=$_POST['b2b'];
    	    ini_set("memory_limit","256M");
			//CONCILIACIÓN GENERAL.
			
			$mysqli->query("SET FOREIGN_KEY_CHECKS=0");
        	$mysqli->query("UPDATE conciliacion_general SET id_cliente='99999' where oc='$oc' and cod_b2b='$b2b' and id_cliente='$id_cliente'");
           	$mysqli->query("UPDATE conciliacion_general_detalle SET id_cliente='99999' where orden_compra='$oc' and cod_b2b='$b2b' and id_cliente='$id_cliente'");
            //HISTORIAL DE LAS CONCILIACIONES.
            $mysqli->query("UPDATE conciliacion_general_historial SET id_cliente='99999' where oc='$oc' and cod_b2b='$b2b' and id_cliente='$id_cliente' ");
			$mysqli->query("SET FOREIGN_KEY_CHECKS=1");
        	
			$respuesta='OK';
			$errores='';
	        $datos = array('respuesta' => $respuesta, 'errores' =>$errores);
            echo json_encode($datos);
      	
	
		}
	if ($proceso=='REPROCESA_FACTURA')
    	{
		//error_reporting(E_ALL);
		//ini_set('display_errors', '1');
		    $oc=$_POST['oc'];
			$b2b=$_POST['b2b'];
    	    ini_set("memory_limit","256M");
		
		
			$result_lista = $mysqli->query("SELECT * FROM archivos_xml WHERE id_cliente='$id_cliente' and orden_compra like '%$oc' and b2b='$b2b' ");
            while($rowl = $result_lista->fetch_row())
                {
				$id_xml=$rowl[0];
				$tipo_dte=$rowl[1];
				$folio=$rowl[2];
				$Fecha_emision=$rowl[3];
				$rut_emisor= $rowl[5];
            	$id_cliente= $rowl[27];
                $rut_rec=$rowl[13];
				$razon_rec=$rowl[15];
				$ref_folio=$rowl[30];
				//$ref_num_linea=$xml->Documento->Referencia->NroLinRef;
				$ref_tipo_doc=$rowl[29];
				//$ref_fecha=$xml->Documento->Referencia->FchRef;
				//print "<br>TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT<br>";
				###### Documento->Encabezado->IdDoc
			
		
				/// VALIDAOS QUE EL DOCUMENTO CORRESPONDA AL EMISOR
				$val_emi = $mysqli->query("SELECT rut FROM cliente WHERE id_cliente='$id_cliente' ");
				if($val = $val_emi->fetch_row())
					{
					$rut_cli= $val[0];
					}


				/*$busquedatienda = $mysqli->query("select * from archivos_xml where id_cliente='$id_cliente' and folio='$folio' and tipo_dte='$tipo_dte' ");
				if ($row=$busquedatienda->fetch_row())
					{
					$respuesta='FOLIO YA EXISTE';
					}*/
				if($rut_cli!=$rut_emisor)
					{
					$respuesta='RUT EMISOR NO APLICA';
					}
				else
					{


					/// validamos que el rut receptor existen en la tabla de b2b_rut
					$resp = $mysqli->query("SELECT b2b FROM b2b_rut WHERE rut='$rut_rec' ");
					if($proc = $resp->fetch_row())
						{
						$b2b= $proc[0];
						}
					else
						{
						if(!$mysqli->query("INSERT INTO b2b_rut_nodetectados(b2b,rut) VALUES ('','$rut_rec')"))
							{
								$errores="Error en el insert hacia tabla conciliacion_general. Error: ".$mysqli -> error;
							}
						$b2b='';
						$status='error';
						$respuesta.="El receptor del documento $razon_rec rut '$rut_rec' no existe en la tabla de b2b_rut contactar a soporte para incorporar este valor <br>";
						$error='SI';
						$datos = array('respuesta' => $respuesta,'errores'=>$errores);
						echo json_encode($datos);	
						exit;
						}

				/// verificamos que la OC cumpla con los parametros
				/// verificamos que la OC cumpla con los parametros
				/// verificamos que la OC cumpla con los parametros
				if ($tipo_dte=='33')
					{
					//$ref_folio=$xml->Documento->Referencia->FolioRef;
					$orden_compra="$ref_folio";


					$resultado_tol='NO DETECTADA';
					$mayor='';
					$busquedatienda = $mysqli->query("select * from tolerancia_oc where id_cliente='$id_cliente' and b2b='$b2b' ");
					while ($row=$busquedatienda->fetch_row())
						{
						$tol=$row[3];

						similar_text($tol, $orden_compra, $porcentaje);
						$porcentaje=round($porcentaje);
						$l_tol=strlen($tol);
						$l_oc=strlen($orden_compra);
						$ini_tol="$tol[0]$tol[1]";
						$ini_orc="$orden_compra[0]$orden_compra[1]";
						$ini_tol4="$tol[0]$tol[1]$tol[2]$tol[3]";
						$ini_orc4="$orden_compra[0]$orden_compra[1]$orden_compra[2]$orden_compra[3]";
						if (($porcentaje>=50&&$resultado_tol!='APLICA'&&$ini_orc==$ini_tol))
							{
							if ($orden_compra>=$tol&&$resultado_tol!='APLICA')
								{
								$resultado_tol='APLICA';
								}
							else
								{
								$resultado_tol='NO APLICA';
								}

							}
						elseif (($porcentaje>=30&&$resultado_tol!='APLICA'&&$ini_orc4==$ini_tol4))
							{
							if ($orden_compra>=$tol&&$resultado_tol!='APLICA')
								{
								$resultado_tol='APLICA';
								}
							else
								{
								$resultado_tol='NO APLICA';
								}

							}
						elseif ($orden_compra>=$tol&&$resultado_tol=='NO DETECTADA'&&$mayor!='NO')
							{
							$mayor='SI';
							}
						else
							{
							$mayor='NO';
							}
						}
					if ($resultado_tol=='NO APLICA')
						{
						$respuesta.="El DOCUMENTO no aplica <br>";
						$error='SI';
						//$datos = array('respuesta' => $respuesta,'errores'=>$errores);
						//return json_encode($datos);	
						}
					if ($mayor=='SI'&&$resultado_tol=='NO DETECTADA')
						{
						$resultado_tol='NO DETECTADA MAYOR';
						}
					if ($mayor=='NO'&&$resultado_tol=='NO DETECTADA')
						{
						$resultado_tol='NO DETECTADA MENOR';
						}

					}	
				###########################################################
				###########################################################
				###########################################################	


				###### Documento->Referencia
				/// este código es para determinar la acción en las notas de crédito si las unidades son tomadas en cuenta o no
				$accion_oc=0;
				$accion_refactura=0;	
				$resp = $mysqli->query("SELECT accion_nc,accion_refactura FROM conectores_erp WHERE id_cliente='$id_cliente' and b2b='$b2b' ");
				if($proc = $resp->fetch_row())
					{
					$accion_oc= $proc[0];
					$accion_refactura= $proc[1];
					}	



				$folio_dte=$folio;	
				if ($tipo_dte=='33'){$orden_compra="$ref_folio";}	
				if ($tipo_dte=='61'||$tipo_dte=='56')
					{
						$resp = $mysqli->query("SELECT orden_compra FROM archivos_xml WHERE folio='$ref_folio' and id_cliente='$id_cliente' ");
						if($proc = $resp->fetch_row())
							{
							$orden_compra= $proc[0];
							$resultado_tol='APLICA';
							}
						else
							{
							$orden_compra='Indeterminada';
							$resultado_tol='FAC. NO ENCONTRADA';
							}

					}	

					$mysqli->query("UPDATE archivos_xml SET 
				orden_compra='$orden_compra',
				tolerancia='$resultado_tol'
				 where id_xml='$id_xml'");
					//print "$resultado_tol!='NO APLICA'&&$resultado_tol!='FAC. NO ENCONTRADA'&&$resultado_tol!='NO DETECTADA MENOR' --- oc $oc xml $id_xml";
				################################## PROCESO 2 PARA AGREGAR ESTOS DATOS A ARCHIVOX ERP
				if ($resultado_tol!='NO APLICA'&&$resultado_tol!='FAC. NO ENCONTRADA'&&$resultado_tol!='NO DETECTADA MENOR')	
					{
					if(!$mysqli->query("INSERT INTO carga_registro(fecha_creacion,id_cliente,usuario)  VALUES ('$fechabase $horabase','$id_cliente','$email')"))
						{
							echo ("Error en el insert hacia tabla carga_registro. Error: ".$mysqli -> error);
						}
						else
						{
							$id_carga=$mysqli->insert_id;
						}
					$folio_dte=$folio;	
					if ($tipo_dte=='33'){$folio_dte="FEA$folio";}	
					if ($tipo_dte=='61'){$folio_dte="NCE$folio";}	
					if ($tipo_dte=='56'){$folio_dte="NDE$folio";}	
					$mysqli->query("delete from carga_archivos_erp_sap_r3 where doc_sii='$folio_dte' and id_carga!='$id_carga' and id_cliente='$id_cliente' ");
					//Insert de id_carga para validar la duplicidad de los datos.
					###### Documento->Detalle
					$detalle_xml = $mysqli->query("select * from archivos_xml_detalle where id_xml='$id_xml' ");
					while($det = $detalle_xml->fetch_row())
						{

								//print "<br>########################################################<br>";
								$num_linea=$det[2];
								$valor_codigo[1]=$det[4];
								$nombre_item=$det[9];
								$cantidad_item=$det[10];
								$monto_item=$det[21];

								if ($tipo_dte=='61'||$tipo_dte=='56')
									{
									$cantidad_item=$cantidad_item*-1;
									$monto_item=$monto_item*-1;
									}

								//// código para convertir cantidad de factura en cantidad uxc uxc_factura
								$result_uxc = $mysqli->query("select * from homologacion where id_cliente='$id_cliente' and sku='$valor_codigo[1]' and b2b='$b2b' order by sku desc");
								if($row33 = $result_uxc->fetch_assoc())
								{
									$uxc_factura=$row33['uxc_factura'];
								}
								else
								{
									$uxc_factura=1;
								}
								//// código para convertir cantidad de factura en cantidad uxc uxc_factura
								$cantidad_item=$cantidad_item*$uxc_factura;
								//// código para convertir cantidad de factura en cantidad uxc uxc_factura


								///// en caso de que nota de credito sea 1 en cantidad el sistema deja la cantidad como 0
								if ($accion_oc==1&&$tipo_dte!='33'){$cantidad_item=0;}
								//0: el sistema asume la cantidad del producto como la cantidad real para cuadratura.
								//1: el sistema omite la cantidad del producto y solo considera los montos. 

								/// en caso de que factura no tenga código de producto
								if ($accion_refactura==0&&$tipo_dte=='33'&&$valor_codigo[1]==''){$cantidad_item=0;}

								$cadena_buscada   = 'refactura';
								$coincidencia = strpos(strtoupper($nombre_item), strtoupper($cadena_buscada));

								if ($accion_refactura==1&&$tipo_dte=='33'&&$coincidencia>=1){$cantidad_item=0;}
								if ($accion_refactura==2&&$tipo_dte=='33'&&($valor_codigo[1]==''||$coincidencia>=1)){$cantidad_item=0;}
								//0: el sistema asume refactura si el producto no trae código .
								//1: el sistema asume refactura si la descripcion del producto incluye refactura.
								//2: el sistema asume refactura si el producto no trae codigo o la descripción incluye refactura 
								

								$mysqli->query("INSERT INTO carga_archivos_erp_sap_r3(kam,nombre_kam,cod_solicitante,solicitante,pagador,destinatario,pedido,orden_compra,entrega,factura,documento,fecha_factura,doc_sii,motivo,tipo_venta,sku_petrizzio,descripcion,marca,q_facturado,monto_facturado,estado,id_cliente,fecha_ingreso,id_carga) 
					VALUES('XML','XML','','$razon_rec','$razon_rec','$razon_rec','0','$ref_folio','0','$folio','$tipo_dte','$Fecha_emision','$folio_dte','','','$valor_codigo[1]','$nombre_item','','$cantidad_item','$monto_item','ACTUALIZADO','$id_cliente','$fechabase $horabase','$id_carga')");


								//print "----$valor---------";
								//print "<br>########################################################<br>";
						}





					//Insertar la OC a la tabla de conciliación general para las validaciones respectivas.
					$buscar_oc = $mysqli->query("select * from conciliacion_general where oc='$orden_compra' and id_cliente='$id_cliente' and cod_b2b='$b2b'");
					if($resp_oc = $buscar_oc->fetch_row())
						{
						$fecha_guardada=$resp_oc[12];
						$id_concilia=$resp_oc[0];

						//$fecha_excel = DateTime::createFromFormat("d.m.Y", $texto[12]);
						//$fecha_formateada = $fecha_excel->format("Y-m-d");
						$fecha_formateada=$Fecha_emision;
						if($fecha_guardada>$fecha_formateada||$fecha_guardada<='2001-01-01')
							{
								$mysqli->query("UPDATE conciliacion_general set fecha_1ra_factura='$fecha_formateada' WHERE id_conciliacion = '$id_concilia' ");
							}
						else
							{

							}
						}
					else
						{
						//validar si el texto 13 contiene NCE para no hacer el insert.
							$resp = $mysqli->query("SELECT b2b FROM b2b_rut WHERE rut='$rut_rec' ");
							if($proc = $resp->fetch_row())
								{
								$b2b= $proc[0];
								}
							else
								{
								$b2b='';
								$status='error';
								$respuesta.="El cliente rut '$rut_rec' no existe en la tabla de b2b_rut contactar a soporte para incorporar este valor <br>";
								$error='SI';
								$datos = array('respuesta' => $respuesta,'errores'=>$errores);
								echo json_encode($datos);	
								exit;
								}
							//Insert de los datos para hacer el cálculo en conciliación general.
							if(!$mysqli->query("INSERT INTO conciliacion_general(oc,id_cliente,cod_b2b,estado,fecha_1ra_factura)
							VALUES ('$orden_compra',
									'$id_cliente',
									'$b2b',
									'NO CONCILIADO',
									'$Fecha_emision')"))
								{
									$errores="Error en el insert hacia tabla conciliacion_general. Error: ".$mysqli -> error;
								}
						}
					########################## SE TERMINA EL PROCEOSO DE ARCHIVO EWRP               

					}







				$respuesta=$resultado_tol;
				}
			
				}

    		
			    //$errores = substr("$errores", 200);
		
				sleep(5);
		
                $datos = array('respuesta' => $respuesta, 'errores' =>$errores);
                //clearstatcache(); <-------- NO FUNCIONÓ
	            echo json_encode($datos);
            
    	}
	

	//FILTROS.
	if ($filtro=='CADENA')
	{
		$listado=array();
		$resultlist=$mysqli->query("SELECT b2b FROM b2b order by b2b");
		while ($row2=$resultlist->fetch_row())
			{
				$listado[]="$row2[0]";
			}
		$datos = array('listado' => $listado);
		echo json_encode($datos);
	}

	if ($filtro=='OC')
	{
		$listado=array();
		$resultlist=$mysqli->query("SELECT oc FROM conciliacion_general WHERE id_cliente='$id_cliente' GROUP BY oc");
		while ($row2=$resultlist->fetch_row())
			{
				$listado[]="$row2[0]";
			}
		$datos = array('listado' => $listado);
		echo json_encode($datos);	
	
	}

	if ($filtro=='ESTADO')
	{
	
		$listado=array();
		/*$resultlist=$mysqli->query("SELECT estado FROM conciliacion_general WHERE id_cliente='$id_cliente' GROUP BY estado");
		while ($row2=$resultlist->fetch_row())
			{
				$listado[]="$row2[0]";
			}*/
		$listado[]="ESPERANDO B2B";
		$listado[]="NO CONCILIADO";
		$listado[]="CONCILIADO";
		$listado[]="OC CON DIFERENCIA";
		$listado[]="CONCILIADO CON DIFERENCIA";
		$listado[]="CONCILIADO FINANCIERO";
		$datos = array('listado' => $listado);
		echo json_encode($datos);	
	
	}
	if ($filtro=='PERIODO')
	{
	
		$listado=array();
		$listado[]="0 a 5";
		$listado[]="5 a 30";
		$listado[]="30 a 60";
		$listado[]="Más de 60";
		
		$datos = array('listado' => $listado);
		echo json_encode($datos);	
	
	}

	if ($filtro=='LOGISTICA')
	{
		$listado=array();
		$resultlist=$mysqli->query("SELECT nombre FROM acceso WHERE nombre_labor='LOGISTICA' and id_cliente='$id_cliente' ");
		while ($row2=$resultlist->fetch_row())
			{
				$listado[]="$row2[0]";
			}
		$datos = array('listado' => $listado);
		echo json_encode($datos);
	}

	if ($filtro=='COMERCIAL')
	{
		$listado=array();
		$resultlist=$mysqli->query("SELECT nombre FROM acceso WHERE nombre_labor='COMERCIAL' and id_cliente='$id_cliente' ");
		while ($row2=$resultlist->fetch_row())
			{
				$listado[]="$row2[0]";
			}
		$datos = array('listado' => $listado);
		echo json_encode($datos);
	}
?>