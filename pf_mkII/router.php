<?php
    include 'configs.php';
    include 'functions.php';
    require_once "HTML/Template/ITX.php";

    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);
    $template->setVariable("TITULO", "Sistema de Transporte Universidad Iberoamericana");

    // Manejador de acciones
    if (isset($_GET['action']) || isset($_POST['action'])) {
        $action = $_GET['action'] ?? $_POST['action'];
        switch ($action) {
            case 'entrar':
                include 'login.php';
                break;
            case 'registrar':
                include 'register.php';
                break;
            case 'editUser':
                include 'editUser.php';
                break;
            case 'deleteUser':
                include 'deleteUser.php';
                break;
            case 'perfil':
                include 'perfil.php';
                break;
            case 'favoritos':
                include 'favoritos.php';
                break;
            case 'historial':
                include 'historial.php';
                break;
            case 'horario':
                include 'horario.php';
                break;
            case 'notificaciones':
                include 'notificaciones.php';
                break;
            case 'mensajes':
                include 'mensajes.php';
                break;
            default:
                // Mostrar mensaje de acción desconocida para depuración
                $template->addBlockfile("CONTENIDO", "UNKNOWN_ACTION", "unknownAction.html");
                $template->setCurrentBlock("UNKNOWN_ACTION");
                $template->setVariable("MESSAGE", "Acción desconocida: $action");
                $template->parseCurrentBlock("UNKNOWN_ACTION");
                break;
        }        
    } else {
        // Cargar la página principal si no se está intentando iniciar sesión o registrar
        $template->addBlockfile("CONTENIDO", "WELCOME", "mensajeBienvenida.html");
        $template->setCurrentBlock("WELCOME");
        $template->touchBlock("WELCOME");
    }

    $template->show();
?>