<?
include("puerta_principal.php");



	$aColumns = array( 'id', 'nombre_local', 'direccion' , 'estado');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
	/* DB table to use */
	$sTable = "locales";
	
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
		$sWhere = "WHERE id!='' $filtro_cliente (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".$mysqli->real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ") ";
	}
	else
	{
		$sWhere = "WHERE id!='' $filtro_cliente";
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
        $botones ='<a href="locales_modificar.php?id='.$aRow[$aColumns[0]].'" data-toggle="tooltip" data-placement="top" data-original-title="Editar" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones"><i class="fas fa-edit text-warning"></i></a>'; 
        /*if($aRow[ $aColumns[4] ]=="ACTIVO")
                {
				$botones.='<a id="des'.$aRow[$aColumns[0]].'" href="#" onClick="desactivar_usuario('.$aRow[$aColumns[0]].')" data-toggle="tooltip" data-placement="top" data-original-title="Ver" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones"><i class="fa-solid fa-lock text-success"></i></a>';
				}
            else
                {
				$botones.='<a id="act'.$aRow[$aColumns[0]].'" href="#" onClick="activar_usuario('.$aRow[$aColumns[0]].')" data-toggle="tooltip" data-placement="top" data-original-title="Ver" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones"><i class="fa-solid fa-lock-open text-danger"></i></a>';
				
                }*/
		if($aRow[ $aColumns[3] ]=="ACTIVO") {$des="d-block"; $act="d-none"; }else {$des="d-none"; $act="d-block";}
                $botones.='<a id="des'.$aRow[$aColumns[0]].'" href="#" onClick="desactivar_local('.$aRow[$aColumns[0]].')" data-toggle="tooltip" data-placement="top" data-original-title="Ver" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones '.$des.'"><i class="fa-solid fa-lock text-success"></i></a>';
				
				$botones.='<a id="act'.$aRow[$aColumns[0]].'" href="#" onClick="activar_local('.$aRow[$aColumns[0]].')" data-toggle="tooltip" data-placement="top" data-original-title="Ver" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones '.$act.'"><i class="fa-solid fa-lock-open text-danger"></i></a>';
				
                
		$botones= '<div class="row">'.$botones.'</div>';
		$row[] =$botones;	
		$output['aaData'][] = $row;
	}
	
	echo json_encode($output);
?>