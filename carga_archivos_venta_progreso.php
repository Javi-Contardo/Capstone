<?php
session_start();
if (!empty($_SESSION['progress']))
	{ 
 	$datos = array('progress' => $_SESSION['progress'], 'progresstime' =>$_SESSION['progresstime'], 'parametro' =>$_SESSION['parametro']);
	echo json_encode($datos);
	}
else
	{
	$datos = array('progress' => '', 'progresstime' =>'');
	echo json_encode($datos);
	}
if (!empty($_SESSION['progress']) && $_SESSION['progress'] >= $_SESSION['total']) 
	{
    unset($_SESSION['progress']);
	unset($_SESSION['progresstime']);
	}
if (isset($_POST['limpiar_sesion'])){$limpiar_sesion=$_POST['limpiar_sesion'];} else {$limpiar_sesion='';}
if ($limpiar_sesion!='')
	{
	unset($_SESSION['progress']);
	unset($_SESSION['progresstime']);
	}

?>