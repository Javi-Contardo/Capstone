<?
header("Access-Control-Allow-Origin: * ");
set_time_limit (3600);
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
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
	    $ruta="archivos/stock/";
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
	    $datos = array('respuesta' => $respuesta,'nombre_archivo' => $nombrefinal);
	    echo json_encode($datos);
	}

if ($area=='PROCESA_ARCHIVO')
	{
	  $nombrefinal=$_POST['nombre_archivo'];
		/*if (isset($_POST['registro'])) {$registro=$_POST['registro'];} else {$registro=0;}*/
		ini_set("memory_limit","256M");
  
    /*$array_tolerancia=[];
    $result_to=$mysqli->query("select cod_walmart,cod_sap from tolerancia_walmart_sap");
    while($row_to = $result_to->fetch_array())
      {
      $array_tolerancia[$row_to[0]]=$row_to[1];
      }*/
  
		$respuesta='OK';
		$fecha_carga="$fechabase $horabase";
		$totalregistros=0;
		$total=$totalregistros-2;
	
		if ($totalregistros>=2000){$procesa_big='SI';}
	
		/*$mysqli->query("UPDATE carga_archivos set estado='EN PROCESO'  ");*/
		$con=0;
		$item=1;		
		$contador=0;
        
		/*if ($registro<=10)
			{
				$mysqli->query("INSERT INTO notificaciones(mensaje,categoria,id_cliente) VALUES('PROCESANDO ARCHIVO','info','$id_cliente')"); 
			}*/
	
		require_once 'Excel/reader.php';
    $data = new Spreadsheet_Excel_Reader();
    /*$data->setOutputEncoding('CP1251');*/
    $data->setOutputEncoding('UTF-8');
    $data->read('archivos/stock/'.$nombrefinal);
  /*$data->read('archivos/Carga-1668090360.XLS');*/
    //error_reporting(E_ALL ^ E_NOTICE);		
  
		/*function reemplazarValores($value) 
      { 
      $buscar=array("'","´",",");
      $cambiar=array("","",".");*/
	  	
		// permite revisar la codifcación de los acentos
		/*$codificacion=mb_detect_encoding($value);
    	if ($codificacion=='UTF-8'||$codificacion=='')
        {
        
        }*/
		/*$value = trim($value);
		$value=utf8_decode($value);
		$value = strtoupper($value);
		$value = str_replace($buscar,$cambiar,$value);   
      }*/
		/*$mysqli->query("UPDATE carga_archivos set estado='EN PROCESO'  ");*/
		/*$mysqli->query("INSERT INTO notificaciones(mensaje,categoria,id_cliente) VALUES('PROCESANDO ARCHIVO','info','$id_cliente')"); 
        
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
			  }*/
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
		////// código para validar que la data le pertenece a este cliente
  
    $respuesta='OK';
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
			if ($i==1)
			{
				print("#####################################################");
				if ($texto[1]!='Núm de Tienda'&& $texto[1]!='Num de Tienda')
				{    
					$status='error';
					$respuesta="1 El campo 'Núm de Tienda' no es válido o no está escrito correctamente. # $texto[1] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[2]!='Nombre de Tienda')
				{    
					$status='error';
					$respuesta="El campo 'Nombre de Tienda' no es válido o no está escrito correctamente. # $texto[2] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[3]!='Núm Artículo'&& $texto[3]!='Num Artículo')
				{
					$status='error';
					$respuesta="El campo <b>'Núm Artículo'</b> no es válido o no está escrito correctamente. # $texto[3] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[4]!='Desc Art 1')
				{
					$status='error';
					$respuesta=" El campo 'Desc Art 1' no es válido o no está escrito correctamente. # $texto[4] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[5]!='UPC')
				{
					$status='error';
					$respuesta="El campo 'UPC' no es válido o no está escrito correctamente. # $texto[5] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto[6]!='Cantidad Actual en Existentes de la tienda')
				{
					$status='error';
					$respuesta="El campo 'Cantidad Actual en Existentes de la tienda' no es válido o no está escrito correctamente. # $texto[6] #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
			}
			elseif($i>=2)
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
						
						$cod_proveedor= $homologacion[$nro_producto];
						$nro_tienda=$texto[1];
						$nro_producto=$texto[3];
						$cantidad_existente_tienda=$texto[6];
								$result1 = $mysqli->query("select id,cantidad_existente_tienda from comercial_stock where nro_tienda='$nro_tienda' and nro_producto='$nro_producto' and cantidad_existente_tienda!='$cantidad_existente_tienda'");
								if($row1 = $result1->fetch_row())
								{
									$id_tabla=$row1[0];
									$mysqli->query("UPDATE comercial_stock SET cantidad_existente_tienda='$cantidad_existente_tienda' WHERE id='$id_tabla'");
									
								}
								else
								{
									$mysqli->query("INSERT INTO comercial_stock(numero_tienda,nombre_tienda,numero_articulo,desc_art_1,upc,cantidad_existente_tienda, fecha_carga, subido_por) VALUES('$texto[1]','$texto[2]','$texto[3]','$texto[4]','$texto[5]','$texto[6]','$fecha_carga', '$nombre_usuario')");
									$result5 = $mysqli->query("select * from locales where numero_local='$texto[1]'");
									if($row5 = $result5->fetch_row())
									{
									}
									else
									{
										$mysqli->query("INSERT INTO locales(numero_local,nombre_local) VALUES('$texto[1]','$texto[2]')");
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
	
		//Se hace un array para recorrer todas las órdenes de compra de cencopsud
        unset($_SESSION['progress']); 
		header_remove('Set-Cookie');// permite quitar el error : ERR_RESPONSE_HEADERS_TOO_BIG
		$datos = array('respuesta' => $respuesta, 'errores' =>$errores);
		echo json_encode($datos);	
	}

?>