<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

		///////web////////
		$mysqli = mysqli_connect("localhost", "negocioe_ignacio", "N#cWCG@@5PdY", "negocioe_bbdd_1_principal");
		
		


		

	//phpversion();
	setlocale(LC_ALL,'esl');
	setlocale(LC_CTYPE, 'esl');
	//setlocale(LC_TIME, 'es_ES.UTF-8');
	date_default_timezone_set("America/Santiago");
	$fechaserie=date("d/m/y",time());
	$fechabase=date("Y-m-d",time());
	$horabase=date("H:i:s",time());
	$horacodigo=date("His",time());
	$diaguia=date("d",time());
	$mesguia=date("m",time());
	$anoguia=date("y",time());
	$anocalendario=date("Y",time());
	$mescalendario=date("m",time());
	$semcalendario=date("W",time());
	$mescheck=date("m",time());
	$anocheck=date("Y",time());
	
	

	
?>