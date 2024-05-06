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
        if (isset($_POST['loginBot'])) {
            $user = mysqli_real_escape_string($link, $_POST['username']);
            $pass = mysqli_real_escape_string($link, $_POST['password']);
        
            // Intentar como usuario
            $uQuery = "SELECT username FROM Usuarios WHERE username = '$user' AND contrasena = '$pass'";
            $uResult = mysqli_query($link, $uQuery);
        
            // Intentar como administrador si no se encontró como usuario
            if (mysqli_num_rows($uResult) > 0) {
                cargarDashboardUsuario($template, $user);
            } else {
                $aQuery = "SELECT username FROM Administradores WHERE username = '$user' AND contrasena = '$pass'";
                $aResult = mysqli_query($link, $aQuery);
                if (mysqli_num_rows($aResult) > 0) {
                    cargarDashboardAdmin($template, $user, $link);
                } else {
                    mostrarErrorLogin($template);
                }
            }
        }        

        // si se pica el boton de registrase en mensajeBienvenida.html se abrira registro.html
        if ($_GET['action'] == 'registrar') {
            $template->addBlockfile("CONTENIDO", "REGISTER", "registro.html");
            $template->setCurrentBlock("REGISTER");
            $template->touchBlock("REGISTER");
        }

        // Registrar nuevo usuario
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
        }
        // si se pica el boton de perfil
        if ($_GET['action'] == 'perfil') {
            $template->addBlockfile("CONTENIDO", "PERFIL", "perfil.html");
            $template->setCurrentBlock("PERFIL");
            $template->touchBlock("PERFIL");
        }

    }else {
        // Cargar la página principal si no se está intentando iniciar sesión o registrar
        $template->addBlockfile("CONTENIDO", "WELCOME", "mensajeBienvenida.html");
        $template->setCurrentBlock("WELCOME");
        $template->touchBlock("WELCOME");
    }

    $template->show();

    // funciones
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
                $template->setCurrentBlock("USER_ROW");
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
                $template->parseCurrentBlock();
            }
        } else {
            // Manejar caso en que no hay usuarios
            $template->setCurrentBlock("NO_USERS");
            $template->setVariable("MESSAGE", "No hay usuarios registrados.");
            $template->parseCurrentBlock();
        }
    
        // Finalizar y mostrar el bloque 'ADMIN'
        $template->parseCurrentBlock();
    }
    
    function mostrarErrorLogin($template) {
        $template->addBlockfile("CONTENIDO", "MENSAJE_ERROR", "error.html");
        $template->setVariable("MENSAJE_ERROR", "Nombre de usuario o contraseña incorrecta, intenta de nuevo.");
        $template->setCurrentBlock("MENSAJE_ERROR");
        $template->parseCurrentBlock("MENSAJE_ERROR");
    }
?>