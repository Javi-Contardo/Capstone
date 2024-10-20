<?
include("puerta_principal.php");

/*if ($id_local!='0'){$filtro_cliente=" and numero_local='$id_local'";}else{$filtro_cliente=" and numero_local='0'";}*/

	$aColumns = array('sku','descripcion','on_hand','ventas_semana_actual', 'dias_desde_ultima_venta','codigo_retail','id','numero_local','nombre_local');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
	/* DB table to use */
	$sTable = "comercial_stock_out";
	
	/* 
	 * mysqli connection
	 */

	$gaSql['link'] = $mysqli;
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".$mysqli->real_escape_string( $_GET['iDisplayStart'] ).", ".$mysqli->real_escape_string( $_GET['iDisplayLength'] );
	}

	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".$mysqli->real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and mysqli's regex functionality is very limited
	 */
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".$mysqli->real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ")  $filtro_cliente ";
	}
	else
	{
		$sWhere = "WHERE id!='' and numero_local='$id_local' ";
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE id!='' and numero_local='$id_local' ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".$mysqli->real_escape_string( $_GET['sSearch_'.$i] )."%' ";
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	$rResult = $mysqli->query($sQuery) or die(mysqli_error($mysqli));
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = $mysqli->query($sQuery) or die(mysqli_error($mysqli));
    $aResultFilterTotal = $rResultFilterTotal->fetch_array();
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = $mysqli->query($sQuery) or die(mysqli_error($mysqli));
    $aResultTotal = $rResultTotal->fetch_array();
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while ( $aRow = $rResult->fetch_array())
	{
		$row = array();
		
		$row[] = $aRow[ $aColumns[0] ];	
		$row[] = $aRow[ $aColumns[1] ];	
		$row[] = $aRow[ $aColumns[2] ];	
		$row[] = $aRow[ $aColumns[3] ];	
		$row[] = $aRow[ $aColumns[4] ];	
		$codigo_retail=$aRow[ $aColumns[5] ];	
		$sku1=$aRow[ $aColumns[0] ];	
		$descripcion1=$aRow[ $aColumns[1] ];	
		$numero_local=$aRow[ $aColumns[7] ];	
		$nombre_local1=$aRow[ $aColumns[8] ];	
		$ver_factura ='<a href="#" onclick="abrir_detalle(\''.$codigo_retail.'\',\''.$sku1.'\',\''.$descripcion1.'\',\''.$numero_local.'\')"><i class="fa-sharp fa-solid fa-circle-exclamation text-success"></i></a>';
		if($id_local=='0')
		{
		$ver_grafico ='<a href="#" onclick="graficado_general(\''.$codigo_retail.'\')"><i class="fa-sharp fa-solid fa-chart-simple text-success"></i></a>';
		$row[] =$ver_grafico;
		
			$row[] =$ver_factura;
		}
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode($output);
?>