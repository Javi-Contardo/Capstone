<?
include("puerta_principal.php");

if ($labor!='OWNER'){$filtro_cliente="";}else{ $filtro_cliente="";}

	$aColumns = array( 'descripcion', 'codigo_retail', 'id_homologacion' );
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id_homologacion";
	
	/* DB table to use */
	$sTable = "homologacion";
	
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
		$sWhere = "WHERE id_homologacion!='' (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".$mysqli->real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ")  $filtro_cliente ";
	}
	else
	{
		$sWhere = "WHERE id_homologacion!='' ";
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
        $botones ='<a href="homologacion_modificar.php?id_homologacion='.$aRow[$aColumns[2]].'" data-toggle="tooltip" data-placement="top" data-original-title="Editar" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones"><i class="fas fa-edit text-warning"></i></a>'; 
        /*if($aRow[ $aColumns[4] ]=="ACTIVO")
                {
				$botones.='<a id="des'.$aRow[$aColumns[0]].'" href="#" onClick="desactivar_usuario('.$aRow[$aColumns[0]].')" data-toggle="tooltip" data-placement="top" data-original-title="Ver" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones"><i class="fa-solid fa-lock text-success"></i></a>';
				}
            else
                {
				$botones.='<a id="act'.$aRow[$aColumns[0]].'" href="#" onClick="activar_usuario('.$aRow[$aColumns[0]].')" data-toggle="tooltip" data-placement="top" data-original-title="Ver" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones"><i class="fa-solid fa-lock-open text-danger"></i></a>';
				
                }*/
				$botones .= '<a id="des' . $aRow[$aColumns[2]] . '" href="#" onClick="eliminar_homologacion(' . $aRow[$aColumns[2]] . ')" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 botones d-block">
					<i class="fa-solid fa-trash text-danger"></i>
				</a>';

/*				$botones.='<a id="act'.$aRow[$aColumns[5]].'" href="#" onClick="eliminar_usuario('.$aRow[$aColumns[5]].')" data-toggle="tooltip" data-placement="top" data-original-title="Ver" class="col-xl-4 col-lg-4 col-md-4 col-sm-12 botones"><i class="fa-solid fa-trash-can text-danger"></i></a>';*/
				
                
		$botones= '<div class="row">'.$botones.'</div>';
		$row[] =$botones;	
		$output['aaData'][] = $row;
	}
	
	echo json_encode($output);
?>