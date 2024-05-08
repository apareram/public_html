<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
        header("Location: login.php");
        exit();
    }

    require_once 'database.php';
    require_once "HTML/Template/ITX.php";
    require_once 'functions.php';

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    if (isset($_POST['editUser'])) {
        cargarDatosUsuarios($template, $link);
    } elseif (isset($_POST['actualizarUsuario'])) {
        actualizarDatosUsuario($template, $link);
        cargarDashboardAdmin($template, $_SESSION['username'], $link);
    } else {
        cargarDashboardAdmin($template, $_SESSION['username'], $link);
    }

    $template->show();
?>