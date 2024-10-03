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
	$fechabase=strftime("%Y-%m-%d");
	$horabase=strftime("%H:%M:%S");
	$horacodigo=strftime("%H%M%S");
	$diaguia=strftime("%d");
	$mesguia=strftime("%m");
	$anoguia=strftime("%Y");
	$anocalendario=strftime("%Y");
	$mescalendario=strftime("%B");
	$semcalendario=strftime("%W");
	$mescheck=strftime("%m");
	$anocheck=strftime("%Y");
	
	

	
?>