<?
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);*/
include("puerta_principal.php");

if ($id_local!='0'){$filtro_cliente=" and numero_tienda='$id_local'";}else{$filtro_cliente="";}

	$aColumns = array('numero_tienda','nombre_tienda','numero_articulo','desc_art_1', 'cantidad_existente_tienda','lote','fecha_vencimiento','sku_interno');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
	/* DB table to use */
	$sTable = "comercial_stock";
	
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
		$sWhere = "WHERE id!='' and DATEDIFF(fecha_vencimiento, fecha_carga)<='7' and fecha_carga = (SELECT MAX(fecha_carga) FROM comercial_stock) and(";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".$mysqli->real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ")  $filtro_cliente ";
	}
	else
	{
		$sWhere = "WHERE id!='' and DATEDIFF(fecha_vencimiento, fecha_carga)<='7' and fecha_carga = (SELECT MAX(fecha_carga) FROM comercial_stock) $filtro_cliente ";
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
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
		$row[] = $aRow[ $aColumns[5] ];	
		$row[] = $aRow[ $aColumns[6] ];
		$fecha_vencimiento = new DateTime(date('Y-m-d', strtotime($aRow[$aColumns[6]])));

		$hoy = new DateTime();

		$diferencia = $hoy->diff($fecha_vencimiento)->format('%r%a');
		$row[] = $diferencia;	
		if($diferencia>=1)
		{
			$estado='<p style="color: green">NO VENCIDO</p>';
		}
		else
		{
			$estado='<p style="color: red">VENCIDO</p>';
		}
		$row[] = $estado;	
		
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode($output);
?>