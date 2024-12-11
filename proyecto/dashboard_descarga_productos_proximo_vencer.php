<?php


ini_set('memory_limit', '1024M'); // Aumenta el límite de memoria
set_time_limit(3600); // Aumenta el límite de tiempo de ejecución
include("puerta_principal.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php'; // Descomentado para asegurar que IOFactory esté disponible
$filtro_query="";
if($id_local!='0'){
	$filtro_query=" and numero_tienda='$id_local'";
}
// Crear nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades del documento
$objPHPExcel->getProperties()
    ->setCreator("GungaDevs")
    ->setLastModifiedBy("GungaDevs")
    ->setTitle("Office 2007 XLSX Document")
    ->setSubject("Office 2007 XLSX Document")
    ->setDescription("Document for Office 2007 XLS")
    ->setKeywords("office 2007")
    ->setCategory("Archivo");

// Renombrar hoja activa
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Informe de Productos');

// Encabezados de las columnas
$headers = [
    'Numero Local',
    'Nombre Local',
    'Codigo Retail',
    'Descripcion',
    'Inventario Actual',
    'Lote',
    'Fecha Vencimiento',
    'Dias para perdida',
    'Estado'
];

$col = 0; // Comienza en la columna A
foreach ($headers as $header) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $header);
    $col++;
}

$fila = 2; // Comienza en la fila 2 (después de los encabezados)
$num = 1;

// Consulta para obtener los datos
$resultlist = $mysqli->query("
    SELECT numero_tienda, nombre_tienda, numero_articulo, desc_art_1, cantidad_existente_tienda, lote, fecha_vencimiento 
    FROM comercial_stock 
    WHERE id != '' 
    AND DATEDIFF(fecha_vencimiento, fecha_carga) <= 7 
    AND fecha_carga = (SELECT MAX(fecha_carga) FROM comercial_stock) $filtro_query 
    ORDER BY numero_tienda DESC;
");

while ($row = $resultlist->fetch_row()) {
    $fecha_vencimiento = new DateTime(date('Y-m-d', strtotime($row[6])));
    $hoy = new DateTime();
    $diferencia = $hoy->diff($fecha_vencimiento)->format('%r%a');
    
    // Escribir los datos en las celdas correspondientes
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $row[0]);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $row[1]);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $row[2]);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $row[3]);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $row[4]);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $row[5]);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $row[6]);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $diferencia);
    
    // Establecer estado
    $estado = $diferencia >= 1 ? "NO VENCIDO" : "VENCIDO";
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $estado);
    
    // Incrementar fila
    $fila++;
}

// Ajustar el ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

// Enviar el archivo Excel al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // Para archivos .xlsx
header('Content-Disposition: attachment;filename="Informe_productos_proximo_vencer.xlsx"'); // Para archivos .xlsx
header('Cache-Control: max-age=0');

// Guardar el archivo en el flujo de salida (php://output)
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
