<?php
    session_start(); // Asegúrate de iniciar la sesión en cada script que lo requiera

    if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
        header("Location: login.php"); // Redirige si no es admin o no está logueado
        exit();
    }

    require_once 'database.php';
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    if (isset($_POST['editUser'])) {
        echo "Cargando datos para editar.";
        cargarDatosUsuarios($template, $link);
    }
    
    if (isset($_POST['actualizarUsurario'])) {
        echo "Actualizando datos.";
        actualizarDatosUsuario($template, $link);
        cargarDashboardAdmin($template, $_SESSION['username'], $link);
    }
?>