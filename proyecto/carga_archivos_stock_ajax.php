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

if ($area == 'PROCESA_ARCHIVO') {
    $nombrefinal = $_POST['nombre_archivo'];
    ini_set("memory_limit", "256M");
    $errores = '';
    $respuesta = 'OK';
    $fecha_carga = "$fechabase";
    $procesa_big = 'SI';

    require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
    require('spreadsheet-reader-master/SpreadsheetReader.php');

    $data = new SpreadsheetReader('archivos/stock/' . $nombrefinal);
    $Sheets = $data->Sheets();
    $data->ChangeSheet(1);

    $con = 0;
    $contador = 0;
    $totalregistros = 0;

    // Contar las filas válidas (ignorar filas vacías)
    foreach ($data as $Row) {
        if (!empty(trim(implode('', $Row)))) { // Fila no vacía
            $totalregistros++;
        }
    }


    $i = 1;

    foreach ($data as $Key => $Row) {
        if (empty(trim(implode('', $Row)))) { // Ignorar filas vacías
            continue;
        }

        $contador++;

        $texto1 = trim("$Row[0]");
        $texto2 = trim("$Row[1]");
        $texto3 = trim("$Row[2]");
        $texto4 = trim("$Row[3]");
        $texto5 = trim("$Row[4]");
        $texto6 = trim("$Row[5]");
        $texto7 = trim("$Row[6]");

        $buscar = ["'", "´", ".", ","];
        $cambiar = ["", "", "", "."];
        
        $texto1 = str_replace($buscar, $cambiar, utf8_encode($texto1));
        $texto2 = str_replace($buscar, $cambiar, utf8_encode($texto2));
        $texto3 = str_replace($buscar, $cambiar, utf8_encode($texto3));
        $texto4 = str_replace($buscar, $cambiar, utf8_encode($texto4));
        $texto5 = str_replace($buscar, $cambiar, utf8_encode($texto5));
        $texto6 = str_replace($buscar, $cambiar, utf8_encode($texto6));
        $texto7 = str_replace($buscar, $cambiar, utf8_encode($texto7));

        if ($Key == 1) { // Verificar encabezados en la primera fila
            if ($texto1 != 'Núm Articulo' && $texto1 != 'Num Articulo') {
                $respuesta = "El campo 'Num Articulo' no es válido o está mal escrito. # $texto1 #";
                echo json_encode(['respuesta' => $respuesta]);
                exit;
            } elseif ($texto2 != 'Desc Art 1') {
                $respuesta = "El campo 'Desc Art 1' no es válido o está mal escrito. # $texto2 #";
                echo json_encode(['respuesta' => $respuesta]);
                exit;
            } elseif ($texto3 != 'UPC') {
                $respuesta = "El campo 'UPC' no es válido o está mal escrito. # $texto3 #";
                echo json_encode(['respuesta' => $respuesta]);
                exit;
            } elseif ($texto4 != 'Cantidad Actual en Existentes de la tienda') {
                $respuesta = "El campo 'Cantidad Actual en Existentes de la tienda' no es válido o está mal escrito. # $texto4 #";
                echo json_encode(['respuesta' => $respuesta]);
                exit;
            } elseif ($texto5 != 'Fecha expiracion') {
                $respuesta = "El campo 'Fecha expiracion' no es válido o está mal escrito. # $texto5 #";
                echo json_encode(['respuesta' => $respuesta]);
                exit;
            } elseif ($texto6 != 'Sku Interno') {
                $respuesta = "El campo 'Sku Interno' no es válido o está mal escrito. # $texto6 #";
                echo json_encode(['respuesta' => $respuesta]);
                exit;
            } elseif ($texto7 != 'Lote') {
                $respuesta = "El campo 'Lote' no es válido o está mal escrito. # $texto7 #";
                echo json_encode(['respuesta' => $respuesta]);
                exit;
            }
        } elseif ($Key >= 2) { // Procesar filas de datos
            if (!empty($texto1)) {
                if (empty($texto3)) {
                    $errores .= "Error en la línea $Key del campo 'UPC'. El campo está vacío.<br>";
                } elseif (empty($texto4)) {
                    $errores .= "Error en la línea $Key del campo 'Cantidad Actual en Existentes de la tienda'. El campo está vacío.<br>";
                } elseif (empty($texto5)) {
                    $errores .= "Error en la línea $Key del campo 'Fecha expiracion'. El campo está vacío.<br>";
                } elseif (empty($texto6)) {
                    $errores .= "Error en la línea $Key del campo 'Sku Interno'. El campo está vacío.<br>";
                } elseif (empty($texto7)) {
                    $errores .= "Error en la línea $Key del campo 'Lote'. El campo está vacío.<br>";
                } else {
                    // Procesar datos en la base de datos
                    $nro_producto = $texto1;
                    $cantidad_existente_tienda = $texto4;
                    $lote = $texto7;
                    $result1 = $mysqli->query("SELECT id, cantidad_existente_tienda FROM comercial_stock WHERE numero_tienda='$id_local' AND numero_articulo='$nro_producto' AND cantidad_existente_tienda!='$cantidad_existente_tienda' AND fecha_carga='$fecha_carga' AND lote='$lote'");
                    if ($row1 = $result1->fetch_row()) {
                        $id_tabla = $row1[0];
                        $mysqli->query("UPDATE comercial_stock SET cantidad_existente_tienda='$cantidad_existente_tienda' WHERE id='$id_tabla'");
                    } else {
						$result2 = $mysqli->query("SELECT id FROM comercial_stock WHERE numero_tienda='$id_local' AND numero_articulo='$nro_producto' AND cantidad_existente_tienda='$cantidad_existente_tienda' AND fecha_carga='$fecha_carga' AND lote='$lote'");
						if ($row2 = $result2->fetch_row()) {
							$errores .= "Esta numero de producto '$nro_producto' no tuvo ninguna modificacion porque no hay cambios en el archivo<br>";
                    		$error = 'SI';
						}
						else {
                        	$mysqli->query("INSERT INTO comercial_stock(numero_tienda, nombre_tienda, numero_articulo, desc_art_1, upc, cantidad_existente_tienda, fecha_carga, subido_por, sku_interno, lote, fecha_vencimiento) VALUES('$id_local', '$nombre_local', '$texto1', '$texto2', '$texto3', '$texto4', '$fecha_carga', '$nombre_usuario', '$texto6', '$texto7', '$texto5')");
                    	}
							
                    } 
                }
            }
        }
    }

    unset($_SESSION['progress']);
    header_remove('Set-Cookie');
    echo json_encode(['respuesta' => $respuesta, 'errores' => $errores]);
}



?>