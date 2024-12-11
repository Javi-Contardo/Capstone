<?php

ini_set('memory_limit', '1024M'); // Aumenta el límite de memoria
set_time_limit(3600); // Aumenta el límite de tiempo de ejecución
include("puerta_principal.php");

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php'; // Asegura que IOFactory esté disponible

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
    'Num Articulo',
    'Desc Art 1',
    'UPC',
    'Cantidad Actual en Existentes de la tienda',
    'Fecha expiracion',
    'Sku Interno',
    'Lote'
];

$col = 0; // Comienza en la columna A
foreach ($headers as $header) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $header);
    $col++;
}

// Escribir los datos (datos de ejemplo)
$productos = [
    [654591, "Producto 1", 74323091021, rand(10, 50), "2024-12-13", 100001, 1],
    [654591, "Producto 1", 74323091021, rand(10, 50), "2024-12-13", 100002, 2],
    [654592, "Producto 2", 74323091022, rand(10, 50), "2024-12-13", 100003, 1],
    [654593, "Producto 3", 74323091023, rand(10, 50), "2024-12-13", 100004, 1],
    [654594, "Producto 4", 74323091024, rand(10, 50), "2024-12-13", 100005, 1],
    [654595, "Producto 5", 74323091025, rand(10, 50), "2024-12-13", 100006, 1],
    [654596, "Producto 6", 74323091026, rand(10, 50), "2024-12-13", 100007, 1],
];

$fila = 2; // Comienza en la fila 2
foreach ($productos as $producto) {
    $col = 0;
    foreach ($producto as $dato) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $dato);
        $col++;
    }
    $fila++;
}

// Ajustar el ancho de las columnas
foreach (range('A', 'G') as $column) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
}

// Enviar el archivo Excel al navegador
header('Content-Type: application/vnd.ms-excel'); // Para archivos .xls
header('Content-Disposition: attachment;filename="Ejemplo-archivo-ingresar-stock.xls"'); // Nombre del archivo
header('Cache-Control: max-age=0');

// Guardar el archivo en el flujo de salida (php://output)
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Cambiar a 'Excel5' para .xls
$objWriter->save('php://output');
exit;

?>
