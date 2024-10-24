<?
header("Access-Control-Allow-Origin: * ");
set_time_limit (3600);
ini_set("memory_limit","256M");
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
	    $nombrefinal="ventas-$unixtime.$extension";		
	    $ruta="archivos/ventas/";
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
		ini_set("memory_limit","256M");
		$errores='';
		$respuesta='OK';
		$fecha_carga="$fechabase";
		$procesa_big='SI';
		ini_set("memory_limit","256M");

		require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
		require('spreadsheet-reader-master/SpreadsheetReader.php');
		$data = new SpreadsheetReader('archivos/ventas/'.$nombrefinal);
		$Sheets = $data -> Sheets();
		$data -> ChangeSheet(1);





		$con=0;
		$item=1;		
		$num_val=1;
		$totalregistros=sizeof($data);
		$i=1;
		$contador=0;
		foreach ($data as $Key => $Row) 
		{
			$contador++;
			$num=1;
			$start_time=microtime();
			$texto1=trim("$Row[0]");
            $texto2=trim("$Row[1]");
            $texto3=trim("$Row[2]");
            $texto4=trim("$Row[3]");
            $texto5=trim("$Row[4]");
            $texto6=trim("$Row[5]");
            $texto7=trim("$Row[6]");

			
			
            
            $buscar=array("'","´",".",",");
            $cambiar=array("","","",".");
			
            $texto1=str_replace($buscar,$cambiar,utf8_encode($texto1));
            $texto2=str_replace($buscar,$cambiar,utf8_encode($texto2));
            $texto3=str_replace($buscar,$cambiar,utf8_encode($texto3));
            $texto4=str_replace($buscar,$cambiar,utf8_encode($texto4));
            $texto5=str_replace($buscar,$cambiar,utf8_encode($texto5));
            $texto6=str_replace($buscar,$cambiar,utf8_encode($texto6));
            $texto7=str_replace($buscar,$cambiar,utf8_encode($texto7));
			$buscar_fec=array(".");
            $cambiar_fec=array("-");

			if ($Key==1)
			{
				if ($texto1!='Diario')
				{    
					$status='error';
					$respuesta="1 El campo 'Num Articulo' no es valido o no esta escrito correctamente. # $texto1 #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto2!='Num Articulo')
				{    
					$status='error';
					$respuesta="El campo 'Num Articulo' no es valido o no esta escrito correctamente. # $texto2 #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto3!='Desc Art 1')
				{
					$status='error';
					$respuesta="El campo <b>'UPC'</b> no es valido o no esta escrito correctamente. # $texto3 #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto4!='UPC')
				{
					$status='error';
					$respuesta=" El campo 'Cantidad Actual en Existentes de la tienda' no es valido o no esta escrito correctamente. # $texto4 #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto5!='Cnt POS')
				{
					$status='error';
					$respuesta=" El campo 'Cantidad Actual en Existentes de la tienda' no es valido o no esta escrito correctamente. # $texto5 #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto6!='Sku Interno')
				{
					$status='error';
					$respuesta=" El campo 'Sku Interno' no es valido o no esta escrito correctamente. # $texto6 #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
				elseif ($texto7!='Lote')
				{
					$status='error';
					$respuesta=" El campo 'Lote' no es valido o no esta escrito correctamente. # $texto7 #";
					$datos = array('respuesta' => $respuesta);
					echo json_encode($datos);	
					exit;
				}
			}
			elseif($Key>=2)
			{
				if($texto2!='')
				{

					if ($texto3=='')
					{
						$errores.="Error en la línea $Key del campo <b>'Desc Art 1'.</b> El campo está vacio. <br>";
						$error='SI';
					}
					elseif ($texto4=='')
					{
						$errores.="Error en la línea $Key del campo <b>'UPC'.</b> El campo está vacio. <br>";
						$error='SI';
					}
					elseif ($texto5=='')
					{
						$errores.="Error en la línea $Key del campo <b>'Cnt POS'.</b> El campo está vacio. <br>";
						$error='SI';
					}
					else
					{
						$nro_producto=$texto2;
						$cnt_pos=$texto5;
						$lote=$texto7;
						$result1 = $mysqli->query("select id from comercial_ventas where numero_tienda='$id_local' and numero_articulo='$nro_producto' and cnt_pos!='$cnt_pos' and fecha_carga='$fecha_carga' and lote='$lote'");
						if($row1 = $result1->fetch_row())
						{
							$id_tabla=$row1[0];
							$mysqli->query("UPDATE comercial_ventas SET cnt_pos='$cnt_pos' WHERE id='$id_tabla'");

						}
						else
						{
							$mysqli->query("INSERT INTO comercial_ventas(numero_tienda, nombre_tienda, diario, numero_articulo, desc_art_1, upc, cnt_pos, sku_interno, fecha_carga, subido_por,lote) 
							VALUES($id_local, '$nombre_local', '$texto1', '$texto2', '$texto3', '$texto4', $texto5, '$texto6', '$fecha_carga', '$nombre_usuario','$lote')");
						}
					}
				}
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