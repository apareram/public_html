<?php
    require_once 'database.php';
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    // Verificar si se está intentando iniciar sesión
    if (isset($_POST['loginBot'])) {
        $user = mysqli_real_escape_string($link, $_POST['username']);
        $pass = mysqli_real_escape_string($link, $_POST['password']);
    
        // Intentar como usuario
        $uQuery = "SELECT username FROM Usuarios WHERE username = '$user' AND contrasena = '$pass'";
        $uResult = mysqli_query($link, $uQuery);
        var_dump(mysqli_fetch_assoc($uResult)); 
        print($uQuery);
    
        // Intentar como administrador si no se encontró como usuario
        if (mysqli_num_rows($uResult) > 0) {
            $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
            $template->setCurrentBlock("DASHBOARD");
            $template->setVariable("USERNAME", $user);
            $template->parseCurrentBlock("DASHBOARD");
        } else {
            $aQuery = "SELECT username FROM Administradores WHERE username = '$user' AND contrasena = '$pass'";
            $aResult = mysqli_query($link, $aQuery);
            var_dump(mysqli_fetch_assoc($aResult)); 
            print($aQuery);
            if (mysqli_num_rows($aResult) > 0) {
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
            } else {
                $template->addBlockfile("CONTENIDO", "MENSAJE_ERROR", "error.html");
                $template->setVariable("MENSAJE_ERROR", "Nombre de usuario o contraseña incorrecta, intenta de nuevo.");
                $template->setCurrentBlock("MENSAJE_ERROR");
                $template->parseCurrentBlock("MENSAJE_ERROR");
            }
        }
    }else {
        // Mostrar la plantilla de inicio de sesión si no se ha enviado el formulario
        $template->addBlockfile("CONTENIDO", "LOGIN", "login.html");
        $template->setCurrentBlock("LOGIN");
        $template->touchBlock("LOGIN");
    }
?>