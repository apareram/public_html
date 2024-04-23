<?php
include 'configs.php';
require_once "HTML/Template/ITX.php";

$template = new HTML_Template_ITX('./templates');
$template->loadTemplatefile("principal.html", true, true);

$template->setVariable("TITULO", "Sistema de Transporte Universidad Iberoamericana");
$template->setVariable("CABECERA", "Bienvenido al Sistema de Transporte de la Universidad Iberoamericana");
$template->setVariable("MENSAJE_BIENVENIDA", "Para acceder a todas las funcionalidades, por favor inicia sesión o crea una nueva cuenta.");
$template->setVariable("PIE_DE_PAGINA", "&copy; Universidad Iberoamericana - Sistema de Transporte");

// Mostrar el contenido procesado de la plantilla principal
$template->show();

// Lógica para manejar los botones de acción si se hace clic en ellos
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'login') {
        // Cargar la plantilla de inicio de sesión
        $template->loadTemplatefile("login.html", true, true);
        // Configurar las variables necesarias para la plantilla de inicio de sesión y mostrarla
    } elseif ($_GET['action'] == 'register') {
        // Cargar la plantilla de registro
        $template->loadTemplatefile("registro.html", true, true);
        // Configurar las variables necesarias para la plantilla de registro y mostrarla
    }
    // No olvides mostrar el template actualizado después de cargar la nueva plantilla
    $template->show();
}
?>
