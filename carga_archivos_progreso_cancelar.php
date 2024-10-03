<?php
session_start();
$_SESSION['Cancelar_pro']='SI';

  


$limpiar_sesion=$_POST['limpiar_sesion'];
if ($limpiar_sesion!='')
	{
	unset($_SESSION['Cancelar_pro']);
	$_SESSION['Cancelar_pro']='';
	unset($_SESSION['progress']);
	unset($_SESSION['progresstime']);
	}
session_write_close();
?>