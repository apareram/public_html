<?php
    require_once 'database.php';
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    if (isset($_POST['regBot'])) {
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $query_check_user = "SELECT username FROM Usuarios WHERE username = '$username'";
        $result_check_user = mysqli_query($link, $query_check_user);

        if (mysqli_num_rows($result_check_user) > 0) {
            $template->addBlockfile("CONTENIDO", "MENSAJE_ERROR", "error.html");
            $template->setVariable("MENSAJE_ERROR", "El nombre de usuario ya está en uso. Por favor, ingresa otro.");
            $template->setCurrentBlock("MENSAJE_ERROR");
            $template->parseCurrentBlock("MENSAJE_ERROR");
        } else {
            $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
            $ap_paterno = mysqli_real_escape_string($link, $_POST['ap_paterno']);
            $ap_materno = mysqli_real_escape_string($link, $_POST['ap_materno']);
            $email = mysqli_real_escape_string($link, $_POST['email']);
            $contrasena = mysqli_real_escape_string($link, $_POST['contrasena']);  // Asegúrate de que 'contrasena' es el nombre correcto
            $calle = mysqli_real_escape_string($link, $_POST['calle']);
            $numero = mysqli_real_escape_string($link, $_POST['numero']);
            $colonia = mysqli_real_escape_string($link, $_POST['colonia']);
            $zip_code = mysqli_real_escape_string($link, $_POST['cp']);

            $query_insert_user = "INSERT INTO Usuarios (nombre, ap_paterno, ap_materno, username, email, contrasena, calle, numero, colonia, zip_code) VALUES ('$nombre', '$ap_paterno', '$ap_materno', '$username', '$email', '$contrasena', '$calle', '$numero', '$colonia', '$zip_code')";

            if (mysqli_query($link, $query_insert_user)) {
                $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
                $template->setCurrentBlock("DASHBOARD");
                $template->setVariable("USERNAME", $username);
                $template->parseCurrentBlock("DASHBOARD");
            } else {
                echo "Error al insertar usuario: " . mysqli_error($link);
            }
        }
    } else {
        $template->addBlockfile("CONTENIDO", "REGISTER", "registro.html");
        $template->setCurrentBlock("REGISTER");
        $template->touchBlock("REGISTER");
    }
?>