<?php
	include "config.php";
	require_once "HTML/Template/ITX.php";
	
	$template = new HTML_Template_ITX('./templates');
	$template->loadTemplatefile("principal.html", true, true);
	
	if(isset($_GET['buscar'])){
		$apellidoPresidente = $_GET['apellidoPresidente'];
		
		$link = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password']) or die('Could not connect: ' . mysqli_error($link));
		mysqli_select_db($link, $cfgServer['dbname']) or die("Could not select database");
		
		$query = "SELECT nombre, ap_paterno, ap_materno, ciudad, estado, nacimiento, muerte FROM presidentes WHERE ap_paterno = '$apellidoPresidente'";

		$result = mysqli_query($link, $query) or die("Query failed");
		$i = 0;
		
		while($line = mysqli_fetch_assoc($result)){
			if($i == 0){
				$template->setVariable("TITULO", "Presidentes de Mexico");
				$template->addBlockfile("CONTENIDO", "PRESIDENTES", "tabla.html");
				
				$template->setCurrentBlock("PRESIDENTES");
				$template->setVariable("MENSAJE_BIENVENIDA", "Presidentes con apellido: '$apellidoPresidente'");
		
			}

			if($line['muerte'] == NULL){
				$muerte = '--';
			}
			else{
				$muerte = $line['muerte'];
			}
			
			$template->setCurrentBlock("PRESIDENTE");
			$template->setVariable("NOMBRE", $line['nombre']);
			$template->setVariable("AP_PATERNO", $line['ap_paterno']);
			$template->setVariable("AP_MATERNO", $line['ap_materno']);
			$template->setVariable("CIUDAD", $line['ciudad']);
			$template->setVariable("ESTADO", $line['estado']);
			$template->setVariable("NACIMIENTO", $line['nacimiento']);
			$template->setVariable("MUERTE", $muerte);
			
			$template->parseCurrentBlock("PRESIDENTE");
			$i++;
		}

		if($i == 0){
			$template->setVariable("TITULO", "Presidentes de Mexico");
			$template->addBlockfile("CONTENIDO", "ERROR", "error.html");
			
			$template->setCurrentBlock("ERROR");
			$template->setVariable("MENSAJE_ERROR", "No existen presidentes con el apellido: '$apellidoPresidente'");
			$template->parseCurrentBlock("ERROR");
		}
		else{
			$template->parseCurrentBlock("PRESIDENTES");
		}
		
		mysqli_free_result($result);
		@mysqli_close($link);
	}
	else{
		$template->setVariable("TITULO", "Presidentes");
		$template->addBlockfile("CONTENIDO", "INICIO", "inicio.html");
		
		$template->setCurrentBlock("INICIO");
		$template->setVariable("USUARIO", "");
		$template->parseCurrentBlock();
	}
	
	$template->show();
?>