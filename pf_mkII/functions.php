<?php
    require_once "HTML/Template/ITX.php";
    // se carga el template principal donde el contenido de este cambiara
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    function cargarDashboardUsuario($template, $user) {
        $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
        $template->setCurrentBlock("DASHBOARD");
        $template->setVariable("USERNAME", $user);
        $template->parseCurrentBlock("DASHBOARD");
    }
    
    function cargarDashboardAdmin($template, $user, $link) {
        $query = "SELECT idUsuario, nombre, ap_paterno, ap_materno, username, email, contrasena, calle, numero, colonia, zip_code FROM Usuarios";
        $result = mysqli_query($link, $query) or die("Query failed");
    
        // Inicializa el bloque 'ADMIN' y establece variables comunes
        $template->addBlockfile("CONTENIDO", "ADMIN", "admin.html");
        $template->setCurrentBlock("ADMIN");
        $template->setVariable("ADMIN", $user);
    
        if (mysqli_num_rows($result) > 0) {
            while ($line = mysqli_fetch_assoc($result)) {
                // Configurar bloque 'USER_ROW' para cada usuario
                $template->setCurrentBlock("USUARIOS");
                $template->setVariable("IDUSUARIO", $line['idUsuario']);
                $template->setVariable("NOMBRE", $line['nombre']);
                $template->setVariable("AP_PATERNO", $line['ap_paterno']);
                $template->setVariable("AP_MATERNO", $line['ap_materno']);
                $template->setVariable("USERNAME", $line['username']);
                $template->setVariable("EMAIL", $line['email']);
                $template->setVariable("PASSWORD", $line['contrasena']);
                $template->setVariable("CALLE", $line['calle']);
                $template->setVariable("NUMERO", $line['numero']);
                $template->setVariable("COLONIA", $line['colonia']);
                $template->setVariable("ZIP_CODE", $line['zip_code']);
                $template->parseCurrentBlock("USUARIOS");
            }
        } else {
            // Manejar caso en que no hay usuarios
            $template->setCurrentBlock("NO_USERS");
            $template->setVariable("MESSAGE", "No hay usuarios registrados.");
            $template->parseCurrentBlock();
        }
    
        // Finalizar y mostrar el bloque 'ADMIN'
        $template->parseCurrentBlock("ADMIN");
    }
    
    function mostrarErrorLogin($template) {
        $template->addBlockfile("CONTENIDO", "MENSAJE_ERROR", "error.html");
        $template->setVariable("MENSAJE_ERROR", "Nombre de usuario o contraseña incorrecta, intenta de nuevo.");
        $template->setCurrentBlock("MENSAJE_ERROR");
        $template->parseCurrentBlock("MENSAJE_ERROR");
    }

    function cargarDatosUsuarios($template, $link) {
        $idUsuario = mysqli_real_escape_string($link, $_POST['id']);
        $query = "SELECT * FROM Usuarios WHERE idUsuario = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "i", $idUsuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userData = mysqli_fetch_assoc($result);

        if ($userData) {
            $template->addBlockfile("CONTENIDO", "EDIT", "editUsuario.html");
            $template->setCurrentBlock("EDIT");
            $template->setVariable(array(
                "ID_USUARIO" => $userData['idUsuario'],
                "NOMBRE_USUARIO" => $userData['nombre'],
                "APELLIDO_PATERNO" => $userData['ap_paterno'],
                "APELLIDO_MATERNO" => $userData['ap_materno'],
                "USERNAME_USUARIO" => $userData['username'],
                "EMAIL_USUARIO" => $userData['email'],
                "CONTRASENA_USUARIO" => $userData['contrasena'],
                "CALLE_USUARIO" => $userData['calle'],
                "NUMERO_USUARIO" => $userData['numero'],
                "COLONIA_USUARIO" => $userData['colonia'],
                "ZIP_CODE_USUARIO" => $userData['zip_code'],
            ));
            $template->parseCurrentBlock("EDIT");
        } else {
            echo "No se encontraron datos para el usuario.";
        }
        mysqli_stmt_close($stmt);
    }

    function actualizarDatosUsuario($template, $link) {
        $idUsuario = $_POST['idUsuario'];
        $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
        $ap_paterno = mysqli_real_escape_string($link, $_POST['ap_paterno']);
        $ap_materno = mysqli_real_escape_string($link, $_POST['ap_materno']);
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $contrasena = mysqli_real_escape_string($link, $_POST['contrasena']);
        $calle = mysqli_real_escape_string($link, $_POST['calle']);
        $numero = mysqli_real_escape_string($link, $_POST['numero']);
        $colonia = mysqli_real_escape_string($link, $_POST['colonia']);
        $zip_code = mysqli_real_escape_string($link, $_POST['zip_code']);

        $query = "UPDATE Usuarios SET nombre=?, ap_paterno=?, ap_materno=?, username=?, email=?, contrasena=?, calle=?, numero=?, colonia=?, zip_code=? WHERE idUsuario=?";
        $stmt = mysqli_prepare($link, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssssssi", $nombre, $ap_paterno, $ap_materno, $username, $email, $contrasena, $calle, $numero, $colonia, $zip_code, $idUsuario);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "Usuario actualizado con éxito.";
            } else {
                echo "Error al actualizar usuario: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error al preparar la consulta: " . mysqli_error($link);
        }
    }
?>