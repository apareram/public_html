<?php
    include "config.php";
    require_once "HTML/Template/ITX.php";

    $template = new HTML_Template_ITX('./templates');
    
    
    $estado = $_GET['estado'];
    
    $error = 0;
    
    if( count(explode("'", $estado)) > 1 ){
        $error = 1;
    }
    
    //print "Error es: $error";
    
    // Todo va bien
    if($error == 0){
        
        // Nos conectamos a la base de datos
        $link = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password']) or die('Could not connect: ' . mysqli_error($link));
    
        // Seleccionamos la base de datos
        mysqli_select_db($link, $cfgServer['dbname']) or die("Could not select database");

        $query = "SELECT id, nombre, ap_paterno, ap_materno FROM presidentes WHERE estado LIKE '%$estado%'";
        
        $result = mysqli_query($link, $query) or die("Query failed");
        
        $i = 1;
        
        while($line = mysqli_fetch_assoc($result)){
            
            if($i == 1){
                $template->loadTemplatefile("./resultadoBusqueda.html", true, true);
            }
            
            $template->setCurrentBlock("PRESIDENTE");
            
            $template->setVariable("ID", $line['id']);
            $template->setVariable("AP_PATERNO",$line['ap_paterno']);
            $template->setVariable("AP_MATERNO", $line['ap_materno']);
            $template->setVariable("NOMBRE", $line['nombre']);
            
            $template->parseCurrentBlock("PRESIDENTE");
            
            $i++;
        }
        
        if($i == 1){
            $template->loadTemplatefile("./errorBusqueda.html", true, true);
            $template->setVariable("ETIQUETA", "");
        }
        else{
            mysqli_free_result($result);   
        }
        
        @mysqli_close($link);
        
    }
    // Hubo alguna intenci—n de inyectar c—digo SQL
    else{
        $template->loadTemplatefile("./error.html", true, true);
        
        $template->setVariable("ETIQUETA", "");
    }
    
    $template->show();
?>