<?php
    include 'configs.php';
    require_once "HTML/Template/ITX.php";

    $template = new HTML_Template_ITX('./templates');

    // Determinar qué plantilla cargar basado en la acción de la URL
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    switch ($action) {
        case 'login':
            $template->loadTemplatefile("login.html", true, true);
            break;
        case 'register':
            $template->loadTemplatefile("registro.html", true, true);
            break;
        default:
            $template->loadTemplatefile("principal.html", true, true);
            break;
    }

    $template->show();
?>