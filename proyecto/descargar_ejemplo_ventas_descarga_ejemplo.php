<?php

ini_set('memory_limit', '1024M'); // Aumenta el límite de memoria
set_time_limit(3600); // Aumenta el límite de tiempo de ejecución
include("puerta_principal.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php'; // Descomentado para asegurar que IOFactory esté disponible

// Crear nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades del documento
$objPHPExcel->getProperties()
    ->setCreator("GungaDevs")
    ->setLastModifiedBy("GungaDevs")
    ->setTitle("Office 97-2003 XLS Document")
    ->setSubject("Office 97-2003 XLS Document")
    ->setDescription("Document for Office 97-2003 XLS")
    ->setKeywords("office 97-2003")
    ->setCategory("Archivo");

// Renombrar hoja activa
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Informe de Productos');

// Encabezados de las columnas
$headers = [
    'Diario',
    'Num Articulo',
    'Desc Art 1',
    'UPC',
    'Cnt POS',
    'Sku Interno',
    'Lote'
];

$col = 0; // Comienza en la columna A
foreach ($headers as $header) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $header);
    $col++;
}

// Escribir los datos en las celdas correspondientes
$data = [
    [$fechabase, 654591, "Producto 1", 74323091021, rand(10, 50), 100001, 1],
    [$fechabase, 654591, "Producto 1", 74323091021, rand(10, 50), 100002, 2],
    [$fechabase, 654592, "Producto 2", 74323091022, rand(10, 50), 100003, 1],
    [$fechabase, 654593, "Producto 3", 74323091023, rand(10, 50), 100004, 1],
    [$fechabase, 654594, "Producto 4", 74323091024, rand(10, 50), 100005, 1],
    [$fechabase, 654595, "Producto 5", 74323091025, rand(10, 50), 100006, 1],
    [$fechabase, 654596, "Producto 6", 74323091026, rand(10, 50), 100007, 1]
];

$row = 2; // Inicia en la fila 2
foreach ($data as $rowData) {
    $col = 0;
    foreach ($rowData as $cellData) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $cellData);
        $col++;
    }
    $row++;
}

// Ajustar el ancho de las columnas
$columns = range('A', 'G');
foreach ($columns as $column) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
}

// Enviar el archivo Excel al navegador en formato 97-2003 (.xls)
header('Content-Type: application/vnd.ms-excel'); // Para archivos .xls
header('Content-Disposition: attachment;filename="Ejemplo-archivo-ingresar-ventas.xls"'); // Para archivos .xls
header('Cache-Control: max-age=0');

// Guardar el archivo en el flujo de salida (php://output)
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Formato Excel 97-2003
$objWriter->save('php://output');
exit;

?>
