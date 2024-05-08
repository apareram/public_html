<?php
    require_once 'database.php';

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    if (isset($_POST['regBot'])) {
        // Validar si el usuario ya existe
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $query_check_user = "SELECT username FROM Usuarios WHERE username = '$username'";
        $result_check_user = mysqli_query($link, $query_check_user);

        if (mysqli_num_rows($result_check_user) > 0) {
            $template->addBlockfile("CONTENIDO", "MENSAJE_ERROR", "error.html");
            $template->setVariable("MENSAJE_ERROR", "El nombre de usuario ya está en uso. Por favor, ingresa otro.");
            $template->setCurrentBlock("MENSAJE_ERROR");
            $template->parseCurrentBlock("MENSAJE_ERROR");
        } else {
            // Insertar nuevo usuario en la base de datos
            $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
            $ap_paterno = mysqli_real_escape_string($link, $_POST['ap_paterno']);
            $ap_materno = mysqli_real_escape_string($link, $_POST['ap_materno']);
            $email = mysqli_real_escape_string($link, $_POST['email']);
            $contrasena = $_POST['contrasena'];
            $calle = mysqli_real_escape_string($link, $_POST['calle']);
            $numero = mysqli_real_escape_string($link, $_POST['numero']);
            $numero = $_POST['numero'];
            if ($numero === false) {
                // Manejar el error, por ejemplo enviando un mensaje al usuario
                echo "El número de exterior proporcionado no es válido.";
                return; // Salir del script si hay un error
            }
            $colonia = mysqli_real_escape_string($link, $_POST['colonia']);
            $zip_code = $_POST['zip_code'];
            
            $query_insert_user = "INSERT INTO Usuarios (nombre, ap_paterno, ap_materno, username, email, contrasena, calle, numero, colonia, zip_code) VALUES ('$nombre', '$ap_paterno', '$ap_materno', '$username', '$email', '$contrasena', '$calle', '$numero', '$colonia', '$zip_code')";

            $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
            $template->setCurrentBlock("DASHBOARD");
            $template->setVariable("USERNAME", $username);
            //$template->setVariable("NOTIFICACIONES", obtenerNumeroNotificaciones($username));
            $template->parseCurrentBlock("DASHBOARD");
        }
    }else {
        $template->addBlockfile("CONTENIDO", "REGISTER", "registro.html");
        $template->setCurrentBlock("REGISTER");
        $template->touchBlock("REGISTER");
    }
?>