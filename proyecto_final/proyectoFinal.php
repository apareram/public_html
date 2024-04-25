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

                $username = mysqli_real_escape_string($link, $_POST['username']);
                $password = mysqli_real_escape_string($link, $_POST['password']);
                $query = "SELECT username FROM Usuarios WHERE username = '$username' AND contraseña = '$password'";
                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0 && isset($_POST['loginButton'])) {

                    $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
                    $template->setCurrentBlock("DASHBOARD");
                    $template->touchBlock("DASHBOARD");
                    
                } else {
                    $template->setVariable("CONTENIDO", "Usuario no encontrado. Por favor, <a href='proyectoFinal.php?action=register'>regístrate</a>.");
                }
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