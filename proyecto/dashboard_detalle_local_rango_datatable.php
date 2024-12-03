<?php
include("puerta_principal.php");

$numero_local = $_GET['var1'];
$anio = $_GET['var3'];
$fecha_desde = $_GET['var4'];
$fecha_hasta = $_GET['var5'];
$dia = "27";

if (in_array($fecha_hasta, ['1', '3', '5', '7', '8', '10', '12'])) {
    $dia = "31";
} elseif (in_array($fecha_hasta, ['4', '6', '9', '11'])) {
    $dia = "30";
} elseif ($fecha_hasta == '2') {
    $dia = "28";
}

$fecha_desde_concatenada = "$anio-$fecha_desde-01";
$fecha_hasta_concatenada = "$anio-$fecha_hasta-$dia";

$sTable = "comercial_stock_out";

// Filtro de fechas
$sWhere = "WHERE numero_local!='$numero_local' AND fecha_subida BETWEEN '$fecha_desde_concatenada' AND '$fecha_hasta_concatenada'";

// Cálculo del total de registros antes de la paginación
$sQueryTotal = "
    SELECT COUNT(*) AS total_registros
    FROM (
        SELECT 
            descripcion,
            codigo_retail,
            YEAR(fecha_subida) AS anio,
            MONTH(fecha_subida) AS mes
        FROM $sTable
        $sWhere
        GROUP BY descripcion, codigo_retail, anio, mes
    ) AS subquery
";
$rTotalResult = $mysqli->query($sQueryTotal) or die($mysqli->error);
$totalRegistros = $rTotalResult->fetch_assoc()['total_registros'];

// Configuración de paginación
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $start = intval($_GET['iDisplayStart']);
    $length = intval($_GET['iDisplayLength']);
    $sLimit = "LIMIT $start, $length";
}

// Query para obtener los registros con paginación
$sQuery = "
    SELECT 
        descripcion,
        codigo_retail,
        YEAR(fecha_subida) AS anio,
        MONTH(fecha_subida) AS mes,
        AVG(ventas_semana_actual) AS promedio_ventas,
        AVG(on_hand) AS promedio_on_hand
    FROM $sTable
    $sWhere
    GROUP BY descripcion, codigo_retail, anio, mes
    ORDER BY descripcion, codigo_retail, anio, mes
    $sLimit
";

$rResult = $mysqli->query($sQuery) or die($mysqli->error);

// Construcción del resultado
$output = array(
    "sEcho" => intval($_GET['sEcho'] ?? 1), // Ajusta el valor predeterminado si no se pasa 'sEcho'
    "iTotalRecords" => $totalRegistros, // Total de registros disponibles
    "iTotalDisplayRecords" => $totalRegistros, // Total para DataTables
    "aaData" => array()
);

// Construcción del arreglo `aaData` como indexado
while ($row = $rResult->fetch_assoc()) {
    $output['aaData'][] = array(
        $row['codigo_retail'],          // Columna 0
        $row['descripcion'],        // Columna 1
        $row['anio'],                 // Columna 2
        $row['mes'],                  // Columna 3
        round($row['promedio_ventas'], 2), // Columna 4
        round($row['promedio_on_hand'], 2) // Columna 5
    );
}

// Salida JSON
header('Content-Type: application/json');
echo json_encode($output);
?>
