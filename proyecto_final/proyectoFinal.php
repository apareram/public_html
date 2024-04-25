<?php
    include 'configs.php';
    require_once "HTML/Template/ITX.php";

    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    $template->setVariable("TITULO", "Sistema de Transporte Universidad Iberoamericana");

    if (isset($_GET['action'])) {

        $link = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password']) or die('Could not connect: ' . mysqli_error($link));
	    mysqli_select_db($link, $cfgServer['dbname']) or die("Could not select database");

        if ($_GET['action'] == 'login') {
            $template->addBlockfile("CONTENIDO", "LOGIN", "login.html");
            $template->setCurrentBlock("LOGIN");
            $template->touchBlock("LOGIN");
            if(isset($_GET['login'])){
                $username = $_GET['username'];
                $password = $_GET['password'];
                $query = "SELECT username FROM Usuarios WHERE username = '$username' AND contraseña = '$password'";
            }
        } 
        elseif ($_GET['action'] == 'register') {
            $template->addBlockfile("CONTENIDO", "REGISTER", "registro.html");
            $template->setCurrentBlock("REGISTER");
            $template->touchBlock("REGISTER");
        }
    } 
    else {
        $template->addBlockfile("CONTENIDO", "WELCOME", "mensajeBienvenida.html");
        $template->setCurrentBlock("WELCOME");
        $template->touchBlock("WELCOME");
    }

    $template->show();
?>