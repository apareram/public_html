<?php
    include 'configs.php';
    require_once "HTML/Template/ITX.php";

    // se carga el template principal donde el contenido de este cambiara
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    $template->setVariable("TITULO", "Sistema de Transporte Universidad Iberoamericana");

    if ((isset($_GET['action'])) || (isset($_POST['action']))){
        // se hace la conexión a la base de datos
        $link = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password']) or die('Could not connect: ' . mysqli_error($link));
		mysqli_select_db($link, $cfgServer['dbname']) or die("Could not select database");

        // si se pica el boton de login en mensajeBienvenida.html se abrira login.html
        if ($_GET['action'] == 'entrar') {
            $template->addBlockfile("CONTENIDO", "LOGIN", "login.html");
            $template->setCurrentBlock("LOGIN");
            $template->touchBlock("LOGIN");
        } 

        // Inicio de sesión
        if (isset($_GET['loginBot'])) {
            $username = mysqli_real_escape_string($link, $_GET['username']);
            $password = mysqli_real_escape_string($link, $_GET['password']);

            $query = "SELECT username FROM Usuarios WHERE username = '$username' AND contraseña = '$password'";

            $result = mysqli_query($link, $query);

            if (mysqli_num_rows($result) > 0) {
                // Usuario encontrado, iniciar sesión y cargar el dashboard
                $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
                $template->setCurrentBlock("DASHBOARD");
                $template->setVariable("USERNAME", $username);
                //$template->setVariable("NOTIFICACIONES", obtenerNumeroNotificaciones($username));
                $template->parseCurrentBlock("DASHBOARD");
            } else {
                // Usuario no encontrado, mostrar error y cargar el formulario de inicio de sesión nuevamente.
                $template->addBlockfile("CONTENIDO", "MENSAJE_ERROR", "error.html");
                $template->setVariable("MENSAJE_ERROR", "Contraseña incorrecta, intenta de nuevo.");
                $template->setCurrentBlock("MENSAJE_ERROR");
                $template->parseCurrentBlock("MENSAJE_ERROR");
            }
        }
        // si se pica el boton de registrase en mensajeBienvenida.html se abrira registro.html
        if ($_GET['action'] == 'registrar') {
            $template->addBlockfile("CONTENIDO", "REGISTER", "registro.html");
            $template->setCurrentBlock("REGISTER");
            $template->touchBlock("REGISTER");
        }

        // Registrar nuevo usuario
        if (isset($_GET['regBot'])) {
            // Validar si el usuario ya existe
            $username = mysqli_real_escape_string($link, $_GET['username']);
            $query_check_user = "SELECT username FROM Usuarios WHERE username = '$username'";
            $result_check_user = mysqli_query($link, $query_check_user);

            if (mysqli_num_rows($result_check_user) > 0) {
                $template->addBlockfile("CONTENIDO", "MENSAJE_ERROR", "error.html");
                $template->setVariable("MENSAJE_ERROR", "El nombre de usuario ya está en uso. Por favor, ingresa otro.");
                $template->setCurrentBlock("MENSAJE_ERROR");
                $template->parseCurrentBlock("MENSAJE_ERROR");
            } else {
                // Insertar nuevo usuario en la base de datos
                $nombre = mysqli_real_escape_string($link, $_GET['nombre']);
                $aPaterno = mysqli_real_escape_string($link, $_GET['ap_paterno']);
                $aMaterno = mysqli_real_escape_string($link, $_GET['ap_materno']);
                $email = mysqli_real_escape_string($link, $_GET['email']);
                $password = mysqli_real_escape_string($link, $_GET['contraseña']);
                $calle = mysqli_real_escape_string($link, $_GET['calle']);
                $numero = mysqli_real_escape_string($link, $_GET['número']);
                $colonia = mysqli_real_escape_string($link, $_GET['colonia']);
                $cp = mysqli_real_escape_string($link, $_GET['zip_code']);
                
                $query_insert_user = "INSERT INTO Usuarios (nombre, ap_paterno, ap_materno, username, email, contraseña, calle, número, colonia, zip_code) VALUES ('$nombre', '$aPaterno', '$aMaterno', '$username', '$email', '$password', '$calle', '$numero', '$colonia', '$cp')";

                if (mysqli_query($link, $query_insert_user)) {
                    $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
                    $template->setCurrentBlock("DASHBOARD");
                    $template->setVariable("USERNAME", $username);
                    //$template->setVariable("NOTIFICACIONES", obtenerNumeroNotificaciones($username));
                    $template->parseCurrentBlock("DASHBOARD");
                } else {
                    $template->setVariable("ERROR", "Error al registrar el usuario. Por favor, inténtalo de nuevo.");
                    $template->setCurrentBlock("ERROR");
                    $template->parseCurrentBlock("ERROR");
                }
            }
        }

    }else {
        // Cargar la página principal si no se está intentando iniciar sesión o registrar
        $template->addBlockfile("CONTENIDO", "WELCOME", "mensajeBienvenida.html");
        $template->setCurrentBlock("WELCOME");
        $template->touchBlock("WELCOME");
    }

    $template->show();
?>