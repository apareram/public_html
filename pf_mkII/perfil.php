<?php
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    $template->addBlockfile("CONTENIDO", "PERFIL", "perfil.html");
    $template->setCurrentBlock("PERFIL");
    $template->touchBlock("PERFIL");
?>