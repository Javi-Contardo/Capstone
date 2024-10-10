<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
	<?
include("puerta_principal.php");	
error_reporting(E_ALL);
ini_set('display_errors', '1');
	

$nombrefinal = "Cargatesteando-1727972988.xls";

	ini_set("memory_limit","256M");
        
  	
	$desde=$_POST['desde'];
	$hasta=$_POST['hasta'];
	
	
	
	$format = 'Y-W';
	$interval = new DateInterval('P1W');
	$realEnd = new DateTime($hasta);
    $realEnd->add($interval);
	$period = new DatePeriod(new DateTime($desde), $interval, $realEnd);
  
	$format2 = 'W';
	$format3 = 'Y';
    // Use loop to store date into array
    foreach($period as $date) { 
		$week = $date->format($format2);
        $periodos[$week] = $date->format($format3); 
    }
	
	
	ini_set("memory_limit","256M");
	
    require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
	require('spreadsheet-reader-master/SpreadsheetReader.php');
	$data = new SpreadsheetReader('archivos/stock/'.$nombrefinal);
	$Sheets = $data -> Sheets();
	$data -> ChangeSheet(1);
	
				
		
	
		
		$con=0;
		$item=1;		
		$num_val=1;
		$totalregistros=sizeof($data);
		$i=1;
		foreach ($data as $Key => $Row)
			{
			$total=$totalregistros-2;
			$num=1;
        /*for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) 
            {
            $num=1;
            for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
                {
                $texto[$num] = $data->sheets[0]["cells"][$i][$j];
                $num=$num+1;
                }*/
		/*	$excel_obj_temp->setActiveSheetIndexByName("Nombre");
			$texto6 = $sheet->getCell("F$i")->getCalculatedValue();*/
			
            $texto1=trim("$Row[0]");
            $texto2=trim("$Row[1]");
            $texto3=trim("$Row[2]");
            $texto4=trim("$Row[3]");
            $texto5=trim("$Row[4]");
            $texto6=trim("$Row[5]");

			
			$texto1=strtoupper("$texto1");
            $texto2=strtoupper("$texto2");
            $texto3=strtoupper("$texto3");
            $texto4=strtoupper("$texto4");
            $texto5=strtoupper("$texto5");
            
            $buscar=array("'","´",".",",");
            $cambiar=array("","","",".");
			
            $texto1=str_replace($buscar,$cambiar,utf8_encode($texto1));
            $texto2=str_replace($buscar,$cambiar,utf8_encode($texto2));
            $texto3=str_replace($buscar,$cambiar,utf8_encode($texto3));
            $texto4=str_replace($buscar,$cambiar,utf8_encode($texto4));
            $texto5=str_replace($buscar,$cambiar,utf8_encode($texto5));
            $texto6=str_replace($buscar,$cambiar,utf8_encode($texto6));
			$buscar_fec=array(".");
            $cambiar_fec=array("-");
			$fecha_carga=$fechabase;
			$nombre_usuario='Ignacio URRUTIA';
			array_walk($texto, 'reemplazarValores');
			if ($Key==1)
			{
							 print "CABECERAS---$texto1--$texto2--$texto3--$texto4--$texto5-- <br>";
			}
			if ($Key==2)
			{
							 print "PRIMERA FILA---$texto1--$texto2--$texto3--$texto4--$texto5-- <br>";
			}
			
			$mysqli->query("INSERT INTO comercial_stock(numero_tienda,nombre_tienda,numero_articulo,desc_art_1,upc,cantidad_existente_tienda, fecha_carga, subido_por) VALUES('$texto1','$texto2','$texto3','$texto4','$texto5','$texto6','$fecha_carga', '$nombre_usuario')");
			

			
			
			
			
			$i++;
            ////progress bar////////
            }
      
			
		
		//header_remove('Set-Cookie');
		
		//unset($_SESSION['progress3']); 
		//$datos = array('respuesta' => $respuesta, 'errores' =>$errores);
		//echo json_encode($datos);	
		
	
	
	
?>
</body>
</html>