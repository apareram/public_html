<?php
    include 'configs.php';
    require_once "HTML/Template/ITX.php";

    // se carga el template principal donde el contenido de este cambiara
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    $template->setVariable("TITULO", "Sistema de Transporte Universidad Iberoamericana");

    if (isset($_GET['action'])) {

        // se hace la conexión a la base de datos
        $link = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password']) or die('Could not connect: ' . mysqli_error($link));
		mysqli_select_db($link, $cfgServer['dbname']) or die("Could not select database");

        // si se pica el boton de login en mensajeBienvenida.html se abrira login.html
        if ($_GET['action'] == 'login') {
            $template->addBlockfile("CONTENIDO", "LOGIN", "login.html");
            $template->setCurrentBlock("LOGIN");
            $template->touchBlock("LOGIN");
        } 

        // si se pica el boton de "iniciar sesión" dentro de login.html
        if(isset($_GET['action']) && $_GET['action'] == 'login' && isset($_GET['loginBot'])) {
            // se guarda lo que este en las cajas de texto
            $username = $_GET['username'];
            $password = $_GET['password'];
        
            // se concatena el query con lo introdicido por el usuario en la variable $query
            $query = "SELECT username FROM Usuarios WHERE username = '$username' AND contraseña = '$password'";
            // se hace el query en la base de datos
            $result = mysqli_query($link, $query) or die("Query failed");

            // si lo introducido por el usuario esta en la base de datos se despliega dashboard.html
            if (mysqli_num_rows($result) > 0) {
                $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
                $template->setCurrentBlock("DASHBOARD");
                $template->touchBlock("DASHBOARD");
            }
            // si no se enucentra se manda un mensaje de error 
            else {
                $template->addBlockfile("CONTENIDO", "ERROR", "error.html");
                $template->setVariable("MENSAJE_ERROR", "No existe el usuarioa: '$username'");
                $template->setCurrentBlock("ERROR");
                $template->touchBlock("ERROR");
            }
        }
        // si se pica el boton de registrase en mensajeBienvenida.html se abrira registro.html
        if ($_GET['action'] == 'register') {
            $template->addBlockfile("CONTENIDO", "REGISTER", "registro.html");
            $template->setCurrentBlock("REGISTER");
            $template->touchBlock("REGISTER");

            // Validar si el usuario ya existe
            $username = mysqli_real_escape_string($link, $_POST['username']);
            $query_check_user = "SELECT username FROM Usuarios WHERE username = '$username'";
            $result_check_user = mysqli_query($link, $query_check_user);
            if (mysqli_num_rows($result_check_user) > 0) {
                $template->setVariable("ERROR", "El nombre de usuario ya está en uso. Por favor, intenta otro.");
            } else {
                // Insertar nuevo usuario en la base de datos
                $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
                $aPaterno = mysqli_real_escape_string($link, $_POST['ap_paterno']);
                $aMaterno = mysqli_real_escape_string($link, $_POST['ap_materno']);
                $email = mysqli_real_escape_string($link, $_POST['email']);
                $password = mysqli_real_escape_string($link, $_POST['contraseña']);
                $calle = mysqli_real_escape_string($link, $_POST['calle']);
                $numero = mysqli_real_escape_string($link, $_POST['número']);
                $colonia = mysqli_real_escape_string($link, $_POST['colonia']);
                $cp = mysqli_real_escape_string($link, $_POST['zip_code']);
                
                $query_insert_user = "INSERT INTO Usuarios (nombre, ap_paterno, ap_materno, email, username, contraseña, calle, número, colonia, zip_code) VALUES ('$nombre', '$ap_paterno', '$ap_materno', '$email', '$username', '$contraseña', '$calle', '$número', '$colonia', '$zip_code')";
                if (mysqli_query($link, $query_insert_user)) {
                    $template->setVariable("SUCCESS", "Usuario registrado exitosamente.");
                } else {
                    $template->setVariable("ERROR", "Error al registrar el usuario. Por favor, inténtalo de nuevo.");
                }
            }

        }
    } 
    // si no sucede ninguna acción se cargara mensajeBienvenida.html
    else {
        $template->addBlockfile("CONTENIDO", "WELCOME", "mensajeBienvenida.html");
        $template->setCurrentBlock("WELCOME");
        $template->touchBlock("WELCOME");
    }

    $template->show();
?>