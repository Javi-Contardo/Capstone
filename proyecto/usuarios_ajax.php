<?
header("Access-Control-Allow-Origin: *");
set_time_limit (3600);
include("puerta_principal.php");
$proceso=$_POST['proceso'];
		
if ($proceso=='DESACTIVAR_USUARIO')
	{
	$id=$_POST['id'];
	$mysqli->query("update acceso set estado='SUSPENDIDO' where id_acceso='$id'");
	$respuesta='OK';
	$datos = array('respuesta' => $respuesta);
	echo json_encode($datos);	
	}

if ($proceso=='ELIMINAR_USUARIO')
	{
	$id=$_POST['id'];
	$mysqli->query("DELETE from acceso where id_acceso='$id'");
	$respuesta='OK';
	$datos = array('respuesta' => $respuesta);
	echo json_encode($datos);	
	}

if ($proceso=='ACTIVAR_USUARIO')
	{
	$id=$_POST['id'];
	$mysqli->query("update acceso set estado='ACTIVO' where id_acceso='$id'");
	$respuesta='OK';
	$datos = array('respuesta' => $respuesta);
	echo json_encode($datos);	
	}

if ($proceso=='MOD_SELECT')
	{
	$id=$_POST['id'];
	$id_usuario=$_POST['id_usuario'];
	$nombre_tienda=array();
	$id_tiendas=array();
	$seleccionados=array();
	$respuesta='';
	$cliente='';
	
	$busquedatienda = $mysqli->query("select id_tiendas,cliente from tiendas_clientes where id_cliente IN($id) group by id_tiendas");
	while ($row=$busquedatienda->fetch_row())
	{
		$id_tienda=$row[0];
		$cliente=$row[1];
		
		$busquedanombretienda = $mysqli->query("select id,nombre_tienda from tiendas where id='$id_tienda'");
		if ($row2=$busquedanombretienda->fetch_row())
		{
			$id_tiendas[]=$row2[0];
			$nombre_tienda[]=$row2[1];
			$respuesta='OK';
		}
		if ($id_usuario!='')
			{
			$busquedanombretienda2 = $mysqli->query("select * from acceso_tiendas where id_usuario='$id_usuario' and id_tienda='$id_tienda'");
			if ($row4=$busquedanombretienda2->fetch_row())
				{
				$seleccionados[]='SI';
				}
			else
				{
				$seleccionados[]='NO';
				}
			}
	}
	/*else
	{
		$respuesta='NO';
	}*/
	
	$datos = array('respuesta' => $respuesta,'id_tiendas' =>$id_tiendas,'nombre_tienda' =>$nombre_tienda,'cliente' =>$cliente,'seleccionados'=>$seleccionados);
	echo json_encode($datos);	
	}
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################


if ($proceso=='FILTROS')
	{
	$filtro=$_POST['filtro'];
	if ($filtro=='Comuna')
		{
		$listado=array();
		$resultlist=$mysqli->query("select comuna from registros where estado!='RETIRADO' group by comuna order by comuna asc");
		while ($row2=$resultlist->fetch_row())
			{
			$listado[]="$row2[0]";
			}
		
		$datos = array('listado' => $listado);
		echo json_encode($datos);	
		
		}
	if ($filtro=='Estado')
		{
		$listado=array();
		$listado[]="EN PROCESO";
		$listado[]="AGENDADO";
		$listado[]="SOLO ENTREGA";
		$listado[]="ENTREGA Y RETIRO";
		$listado[]="SIN MORADORES";
		$listado[]="RECHAZA SERVICIO";
		
		$datos = array('listado' => $listado);
		echo json_encode($datos);	
		
		}
	if ($filtro=='Fecha Visita')
		{
		$listado=array();
		$resultlist=$mysqli->query("select fecha_visita from registros where estado!='RETIRADO' and fecha_visita!='0001-01-01' group by fecha_visita order by fecha_visita asc");
		while ($row2=$resultlist->fetch_row())
			{
			$listado[]="$row2[0]";
			}
		
		$datos = array('listado' => $listado);
		echo json_encode($datos);	
		
		}
	if ($filtro=='Jornada')
		{
		$listado=array();
		$listado[]="AM";
		$listado[]="PM";
		
		$datos = array('listado' => $listado);
		echo json_encode($datos);	
		
		}
	
	}

?>