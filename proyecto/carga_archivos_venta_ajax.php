<?
header("Access-Control-Allow-Origin: * ");
set_time_limit (3600);
error_reporting(E_ERROR);
ini_set('display_errors', '1');

include("puerta_principal.php");
	
$area=$_POST['area'];

if ($area=='SUBE_ARCHIVO')
	{
    $file=$_FILES["archivo"];
    $nombre =$file["name"];
		$largo=strlen($nombre);
		$extension = pathinfo($nombre, PATHINFO_EXTENSION);
		$tipo =$file["type"];
	    $ruta_provisional = $file["tmp_name"];
	    $respuesta=$ruta_provisional;
	    $unixtime=strtotime("$fechabase $horabase");
	    $nombrefinal="Cargatesteando-$unixtime.$extension";		
	    $ruta="/archivos/ventas/";
	    $src = $ruta.$nombrefinal;
	    if (file_exists($ruta)) 
	    	{
	    	if (move_uploaded_file($ruta_provisional, $src))
	    		{
	    		    $respuesta='OK';
	    		}
	    	else
	    		{
	    		    $respuesta="El archivo no se ha subido debido al siguiente error: #".$_FILES["archivo"]["error"];
	    		}
	    	}
	    else
	    	{
	    		$ruta="archivos/walmart/";
	    		$src = $ruta.$nombrefinal;
	    	if (move_uploaded_file($ruta_provisional, $src))
	    		{
	    		    $respuesta='OK';
	    		}
	    	else
	    		{
	    		    $respuesta="El archivo no se ha subido debido al siguiente error: #".$_FILES["archivo"]["error"];
	    		}
	    	}
	    $datos = array('respuesta' => $respuesta,'nombre_archivo' => $nombrefinal);
	    echo json_encode($datos);
	}

if ($area=='PROCESA_ARCHIVO')
	{
	  $nombrefinal=$_POST['nombre_archivo'];
		if (isset($_POST['registro'])) {$registro=$_POST['registro'];} else {$registro=0;}
		ini_set("memory_limit","256M");
  
    $array_tolerancia=[];
    $result_to=$mysqli->query("select cod_walmart,cod_sap from tolerancia_walmart_sap");
    while($row_to = $result_to->fetch_array())
      {
      $array_tolerancia[$row_to[0]]=$row_to[1];
      }
  
		$respuesta='OK';
		$fecha_carga="$fechabase $horabase";
		$totalregistros=0;
		$total=$totalregistros-2;
	
		if ($totalregistros>=2000){$procesa_big='SI';}
	
		/*$mysqli->query("UPDATE carga_archivos set estado='EN PROCESO'  ");*/
		$con=0;
		$item=1;		
		$contador=0;
        
		if ($registro<=10)
			{
				$mysqli->query("INSERT INTO notificaciones(mensaje,categoria,id_cliente) VALUES('PROCESANDO ARCHIVO','info','$id_cliente')"); 
			}
	
		require_once 'Excel/reader.php';
    $data = new Spreadsheet_Excel_Reader();
    /*$data->setOutputEncoding('CP1251');*/
    $data->setOutputEncoding('UTF-8');
    $data->read('archivos/walmart/'.$nombrefinal);
  /*$data->read('archivos/Carga-1668090360.XLS');*/
    //error_reporting(E_ALL ^ E_NOTICE);		
  
		function reemplazarValores($value) 
      { 
      $buscar=array("'","´",",");
      $cambiar=array("","",".");
	  	
		// permite revisar la codifcación de los acentos
		/*$codificacion=mb_detect_encoding($value);
    	if ($codificacion=='UTF-8'||$codificacion=='')
        {
        
        }*/
		$value = trim($value);
		$value=utf8_decode($value);
		$value = strtoupper($value);
		$value = str_replace($buscar,$cambiar,$value);   
      }
		/*$mysqli->query("UPDATE carga_archivos set estado='EN PROCESO'  ");*/
		$mysqli->query("INSERT INTO notificaciones(mensaje,categoria,id_cliente) VALUES('PROCESANDO ARCHIVO','info','$id_cliente')"); 
        
		$result3 = $mysqli->query("select * from homologacion where id_cliente='$id_cliente' and b2b='WALMART' ORDER BY sku  ");
			  while($row3 = $result3->fetch_row())
			  {
				  $cod_retail = $row3[3];
				  $cod_sku2 = $row3[1];
				  $cod_uxc = $row3[6];
				  $cod_descripcion = $row3[2];
				  $uxc_b2b = $row3[10];
				  $homologacion[$cod_retail] = $cod_sku2;
				  $homologacion_desc[$cod_retail] = $cod_descripcion;
				  $homologacion_uxc[$cod_retail] = $cod_uxc;
				  $homologacion_uxc_b2b[$cod_retail] = $uxc_b2b;
			  }
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
  
    /*$respuesta='OK';*/
		$fecha_carga="$fechabase $horabase";
		$tot=$data->sheets[0];
		$totalregistros=0;
		foreach($tot['cells'] as $total_regis) { $totalregistros++; }
		/*$totalregistros = $data->sheets[0]['numRows']; */
		$total=$totalregistros-2;
		$con=0;
		$item=1;		
		$contador=0;
		$memo_orden_compra = 0;
  
		$existe=0;
		for ($i = 19; $i <= 39; $i++) 
		{
			$contador++;
			$num=1;
			$start_time=microtime();
			for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
			{
				$buscar=array("°");
				$cambiar=array("");

				//print "&&& $value &&&&";

				$texto[$num]= str_replace($buscar,$cambiar,$data->sheets[0]["cells"][$i][$j]);   

				$num=$num+1;
			}

			array_walk($texto, 'reemplazarValores');

			if ($i==18)
			{
				if ($texto[1]!='Diario (Sólo POS)'&& $texto[1]!='DIARIO (SÓLO POS)')
				{    
					$status='error';
					$respuesta="1 El campo 'Diario (Sólo POS)' no es válido o no está escrito correctamente. # $texto[1] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[2]!='Núm de Tienda')
				{    
					$status='error';
					$respuesta="El campo 'Núm de Tienda' no es válido o no está escrito correctamente. # $texto[2] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[3]!='Nombre de Tienda')
				{
					$status='error';
					$respuesta="El campo <b>'Nombre de Tienda'</b> no es válido o no está escrito correctamente. # $texto[3] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[4]!='Núm Artículo'&&$texto[4]!='NÚM ARTICULO')
				{
					$status='error';
					$respuesta=" El campo 'Núm Artículo' no es válido o no está escrito correctamente. # $texto[4] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[5]!='Desc Art 1')
				{
					$status='error';
					$respuesta="El campo 'Desc Art 1' no es válido o no está escrito correctamente. # $texto[5] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[6]!='UPC')
				{
					$status='error';
					$respuesta="El campo 'UPC' no es válido o no está escrito correctamente. # $texto[6] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[7]!='Venta POS')
				{
					$status='error';
					$respuesta="El campo 'Venta POS' no es válido o no está escrito correctamente. # $texto[7] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[8]!='Costo POS')
				{
					$status='error';
					$respuesta="El campo 'Costo POS' no es válido o no está escrito correctamente. # $texto[8] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[9]!='Cnt POS')
				{
					$status='error';
					$respuesta="1 El campo 'Cnt POS' no es válido o no está escrito correctamente. # $texto[9] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
			}
			else
			{
				$cod_retail=$texto[4];

				$result3 = $mysqli->query("select * from homologacion where id_cliente='$id_cliente' and cod_retail='$cod_retail' and b2b='WALMART'");
				if($row3 = $result3->fetch_row())
				{
					$existe++;
				}

			}
		}
		if ($existe<=5)
			{
				$status='error';
				$respuesta="Atención, el archivo que intenta cargar al parecer no corresponde al cliente seleccionado";
				$datos = array('respuesta' => $respuesta);
				echo json_encode($datos);	
				exit;		
			}
	else
	{
		$totalregistros=$totalregistros+10;


		$numero_columnas=$data->sheets[0]['numCols'];

		/*foreach($tot['numCols'] as $total_regis) { $numero_columnas++; }*/
		/*foreach ($tot['cells'] as $total_col) {     $numero_columnas = max($numero_columnas, count($tot)); }*/


		for ($i = 1; $i <= $totalregistros; $i++) 
		{
			$contador++;
			$num=1;
			$start_time=microtime();
			for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
			{
				$buscar=array("°","/");
				$cambiar=array("","-");

				//print "&&& $value &&&&";

				$texto[$num]= str_replace($buscar,$cambiar,$data->sheets[0]["cells"][$i][$j]);   
				$texto[$num]= utf8_encode($texto[$num]);
				$num=$num+1;
			}

			array_walk($texto, 'reemplazarValores');
			if ($i==18)
			{
				if ($texto[1]!='Diario (Sólo POS)'&& $texto[1]!='DIARIO (SÓLO POS)')
				{    
					$status='error';
					$respuesta="1 El campo 'Diario (Sólo POS)' no es válido o no está escrito correctamente. # $texto[1] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[2]!='Núm de Tienda')
				{    
					$status='error';
					$respuesta="El campo 'Núm de Tienda' no es válido o no está escrito correctamente. # $texto[2] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[3]!='Nombre de Tienda')
				{
					$status='error';
					$respuesta="El campo <b>'Nombre de Tienda'</b> no es válido o no está escrito correctamente. # $texto[3] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[4]!='Núm Artículo'&&$texto[4]!='NÚM ARTICULO')
				{
					$status='error';
					$respuesta=" El campo 'Núm Artículo' no es válido o no está escrito correctamente. # $texto[4] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[5]!='Desc Art 1')
				{
					$status='error';
					$respuesta="El campo 'Desc Art 1' no es válido o no está escrito correctamente. # $texto[5] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[6]!='UPC')
				{
					$status='error';
					$respuesta="El campo 'UPC' no es válido o no está escrito correctamente. # $texto[6] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[7]!='Venta POS')
				{
					$status='error';
					$respuesta="El campo 'Venta POS' no es válido o no está escrito correctamente. # $texto[7] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[8]!='Costo POS')
				{
					$status='error';
					$respuesta="El campo 'Costo POS' no es válido o no está escrito correctamente. # $texto[8] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[9]!='Cnt POS')
				{
					$status='error';
					$respuesta="1 El campo 'Cnt POS' no es válido o no está escrito correctamente. # $texto[9] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
			}
			elseif($i>=19)
			{
				if($texto[1]!='')
				{

					if ($texto[3]=='')
					{
						$errores.="Error en la línea $i del campo <b>'Nombre de Tienda'.</b> El campo está vacio. <br>";
						$error='SI';
					}
					elseif ($texto[4]=='')
					{
						$errores.="Error en la línea $i del campo <b>'Núm Artículo'.</b> El campo está vacio. <br>";
						$error='SI';
					}
					elseif ($texto[6]=='')
					{
						$errores.="Error en la línea $i del campo <b>'UPC'.</b> El campo está vacio. <br>";
						$error='SI';
					}
					else
					{
						$nro_producto=$texto[4];
						$cod_proveedor= $homologacion[$nro_producto];
						$fecha_diario=$texto[1];
						$costo_pos_corregido=$texto[8]*100;
						$venta_pos_corregido=$texto[7]*100;
						if ($fecha_diario<$fechabase)
							{
								$result1 = $mysqli->query("select cnt_pos,id from comercial_ventas_walmart where diario='$texto[1]' and sku_interno='$cod_proveedor' and numero_tienda='$texto[2]'");
								if($row1 = $result1->fetch_row())
								{
									$cnt_pos_tabla=$row1[0];
									$id_tabla=$row1[1];
									if($texto[9]>$cnt_pos_tabla)
									{
										$mysqli->query("UPDATE comercial_ventas_walmart SET venta_pos='$venta_pos_corregido' ,costo_pos='$costo_pos_corregido',cnt_pos='$texto[9]' WHERE id='$id_tabla'");
									}
								}
								else
								{
									$mysqli->query("INSERT INTO comercial_ventas_walmart(diario,numero_tienda,nombre_tienda,numero_articulo,desc_art_1,upc,venta_pos,costo_pos,cnt_pos, id_cliente, fecha_carga, subido_por, sku_interno) VALUES('$texto[1]','$texto[2]','$texto[3]','$texto[4]','$texto[5]','$texto[6]','$venta_pos_corregido','$costo_pos_corregido','$texto[9]','$id_cliente','$fecha_carga', '$nombre_usuario','$cod_proveedor')");
									$result5 = $mysqli->query("select * from comercial_locales where id_cliente='$id_cliente' and b2b='WALMART' and numero_local='$texto[2]'");
									if($row5 = $result5->fetch_row())
									{
									}
									else
									{
										$mysqli->query("INSERT INTO comercial_locales(numero_local,nombre_local,b2b,id_cliente) VALUES('$texto[2]','$texto[3]','WALMART','$id_cliente')");
									}
								}
							}
						
					}
				}
				//if ($texto[1]=='')
				//						{
				//							$errores.="Error en la línea $i del campo <b>'NÚMERO DE DOCUMENTO COMPRAS'.</b> El campo está vacio. <br>";
				//							$error='SI';
				//						}

			}
			$end_time=microtime();
			if ($contador==10||$i<=3)
			{
				$tiempo_t=$end_time-$start_time;
				if ($tiempo_t>=0)
				{
					$tiempo_final=round($tiempo_t,3)*($totalregistros-$i);
				}
				//if($tiempo_final>60){$tiempo_final=round($tiempo_final/60,1)."MIN";}
				//elseif ($tiempo_final<0){}
				//else {$tiempo_final=$tiempo_final."SEG";}
				$contador=0;
			}
			$end_time=microtime();
			//usleep(50000);
			session_start();
			$count=round((100*$i)/$totalregistros);
			$_SESSION['progress'] = $count;
			$_SESSION['progresstime'] = $tiempo_final;
			$_SESSION['parametro'] = "time: $tiempo_t , total reg: $totalregistros , i: $i ";
			$_SESSION['total'] = 100;
			session_write_close();
			//$i++;
			////progress bar////////
		}
	}
		//Se hace un array para recorrer todas las órdenes de compra de cencopsud
        unset($_SESSION['progress']); 
		header_remove('Set-Cookie');// permite quitar el error : ERR_RESPONSE_HEADERS_TOO_BIG
		$datos = array('respuesta' => $respuesta, 'errores' =>$errores);
		echo json_encode($datos);	
	}

?>