<?
header("Access-Control-Allow-Origin: *");
set_time_limit (3600);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
include("puerta_principal.php");
$proceso=$_POST['proceso'];
	



		
if ($proceso=='MENSAJES')
	{
		$fechaActual = date('Y-m-d H:i:s');
		$fechaRestada = date('Y-m-d H:i:s', strtotime('-5 minutes', strtotime("$fechabase $horabase")));
				$mysqli->query("update notificaciones set notificado=1 where id_cliente='$id_cliente' and fecha<'$fechaRestada' and categoria='info'");
		
		/*$busquedanotifi = $mysqli->query("select * from notificaciones where id_cliente='$id_cliente' and notificado=0 order by id desc ");
		while  ($noti=$busquedanotifi->fetch_row())
		{
			$id_notifica=$noti[0];
			$fecha_notifica=$noti[3];

			if($fecha_notifica<=$fechaRestada)
			{
				$mysqli->query("update notificaciones set notificado=1 where id= '$id_notifica'");
			}
			else
			{
				
			}
		}*/
		$busquedatienda = $mysqli->query("select * from notificaciones where id_cliente='$id_cliente' and notificado=0 order by id asc ");
		while ($row=$busquedatienda->fetch_row())
			{
			$id_men=$row[0];
			$fecha_men=$row[3];
			$tipo_men=$row[2];
			$texto_men=$row[1];
		?><script>
			toastr.options.onclick = function(e) {alert(this.data.Message); }
			toastr.options = {
				"closeButton": true,
				"debug": false,
				"newestOnTop": true,
				"progressBar": false,
				"positionClass": "toast-top-right",
				"preventDuplicates": false,
				"onclick": function(e) {notifica_ok('<?=$id_men;?>'); },
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "20000",
				"extendedTimeOut": "2000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut",
				"tapToDismiss": false
			};
			var t=toastr["<?=$tipo_men;?>"]("<?=$texto_men;?>", "<?=$fecha_men;?>");
			t.attr('id', "<?=$id_men;?>");
			</script>
		<?

			}
	}
if ($proceso=='LISTADO_MENSAJES')
	{
		$busquedatienda = $mysqli->query("select * from notificaciones where id_cliente='$id_cliente' order by id desc limit 0,100 ");
		while ($row=$busquedatienda->fetch_row())
			{
			$id_men=$row[0];
			$fecha_men=$row[3];
			$tipo_men=$row[2];
			$texto_men=$row[1];
			$fecha_ok=date("H:i:s d-m-Y",strtotime($fecha_men));
			if ($tipo_men=='info')
				{
				?>
				<div class="vertical-timeline-item vertical-timeline-element">
					<div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-primary"> </i></span>
						<div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title"><?=$fecha_ok;?></h4>
							<p><?=$texto_men;?></p></div>
					</div>
				</div>
				<?
				}
			if ($tipo_men=='success')
				{
				?>
				<div class="vertical-timeline-item vertical-timeline-element">
					<div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-success"> </i></span>
						<div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title"><?=$fecha_ok;?></h4>
							<p><?=$texto_men;?></p></div>
					</div>
				</div>
				<?
				}
			if ($tipo_men=='warning')
				{
				?>
				<div class="vertical-timeline-item vertical-timeline-element">
					<div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-warning"> </i></span>
						<div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title"><?=$fecha_ok;?></h4><p>Another meeting today, at <b class="text-danger">12:00 PM</b></p>
							<p><?=$texto_men;?></p></div>
					</div>
				</div>
				<?
				
				}
			if ($tipo_men=='error')
				{
				?>
				<div class="vertical-timeline-item vertical-timeline-element">
					<div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-danger"> </i></span>
						<div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title"><?=$fecha_ok;?></h4>
							<p><?=$texto_men;?></p></div>
					</div>
				</div>
				<?
				}
			
			
		
			}
	}
if ($proceso=='CAMPANA')
	{
	$fechaActual = date('Y-m-d H:i:s');
	$fechaRestada = date('Y-m-d H:i:s', strtotime('-60 minutes', strtotime("$fechabase $horabase")));
		
	
		$busquedatienda = $mysqli->query("select * from notificaciones where id_cliente='$id_cliente' and fecha>='$fechaRestada' ");
		if ($row=$busquedatienda->fetch_row())
			{
			print "NOTIFICA";
			}
		else
			{
			print "";
			
			}
	}
if ($proceso=='NOTIFICA_OK')
	{
	$id=$_POST['id'];
	if ($labor=='ADMINISTRADOR')
		{
		$mysqli->query("update notificaciones set notificado=1 where id='$id'");
		}
	}
if ($proceso=='LISTADO_CLIENTES')
	{
	$filtro_valor=isset($_POST['filtro_valor'])?$_POST['filtro_valor']:'';
	$busquedatienda = $mysqli->query("select * from locales where estado='ACTIVO' and nombre_local like '%$filtro_valor%' order by nombre_local asc");
    while ($row12=$busquedatienda->fetch_row())
        {
        $id_cliente=$row12[0];
        $nombre_cli=$row12[1];
            ?>
            <div class="vertical-timeline-item vertical-timeline-element">
                <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-primary"> </i></span>
                    <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title"><a href="#" onClick="cambio_cliente('<?=$id_cliente;?>')"><?=$nombre_cli;?></a></h4>
                        </div>
                </div>
            </div>
            <?
        }
	}
if ($proceso=='CAMBIO_CLIENTE')
	{
	$id=$_POST['valor'];
	$result2=$mysqli->query("SELECT * FROM locales WHERE id='$id'");
    if($row2 = $result2->fetch_array())
        {
        $nombre_cliente = "$row2[1]";
        }
	$mysqli->query("update acceso set id_local='$id',nombre_fantasia='$nombre_cliente' where id_acceso='$id_usuario'");
	}
if ($proceso=='LABOR')
	{
	echo $labor;
	}
if ($proceso=='CAMBIO_CLAVE')
	{
	$clave_a=$_POST['clave_a'];
	$clave_n=$_POST['clave_n'];
	
	$password=$_COOKIE["password"];
	$clave_a_encriptada = md5($clave_a);
	
	if ($clave_a_encriptada!=$password)
		{
		$respuesta='Clave actual inválida';
		}
	else
		{
		$clave_n_encriptada = md5($clave_n);
		$mysqli->query("update acceso set clavevisual='$clave_n' , clavecifrada='$clave_n_encriptada' where id_acceso='$id_usuario'");
		
		echo 'OK';
	
		}
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



?>