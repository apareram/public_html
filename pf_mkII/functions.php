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
?>