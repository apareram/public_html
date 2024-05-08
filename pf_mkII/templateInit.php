<?php
    require_once "HTML/Template/ITX.php";
    require_once 'configs.php';

    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);
?>