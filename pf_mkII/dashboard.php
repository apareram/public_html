<?php
    session_start();
    
    require_once 'database.php';
    require_once "HTML/Template/ITX.php";
    require_once 'functions.php';

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    cargarDashboardUsuario($template, $_SESSION['username']);
?>