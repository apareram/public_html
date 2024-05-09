<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start(); // Asegúrate de que la sesión está iniciada si vas a usar datos de sesión

    require_once 'database.php'; // Asegúrate de que este archivo contiene la función getDatabaseConnection
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    // Añadir el bloque de perfil
    if (!$template->addBlockfile("CONTENIDO", "PERFIL", "perfil.html")) {
        echo "No se pudo cargar el bloque de perfil.";
    } else {
        $template->setCurrentBlock("PERFIL");
        $template->setVariable("USERNAME", $_SESSION['username']);
        $template->parseCurrentBlock("PERFIL");
    }
?>