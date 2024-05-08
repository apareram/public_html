<?php
    include 'configs.php';
    require_once "HTML/Template/ITX.php";

    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    // Establecer el título por defecto
    $template->setVariable("TITULO", "Sistema de Transporte Universidad Iberoamericana");

    if ((isset($_GET['action'])) || (isset($_POST['action']))) {
        // Sección de acciones basadas en la acción especificada en la solicitud GET o POST

        // Se hace la conexión a la base de datos
        $link = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password']) or die('Could not connect: ' . mysqli_error($link));
        mysqli_select_db($link, $cfgServer['dbname']) or die("Could not select database");

        // Si se pica el botón de login en mensajeBienvenida.html se abrirá login.html
        if ($_GET['action'] == 'entrar') {
            $template->addBlockfile("CONTENIDO", "LOGIN", "login.html");
            $template->setCurrentBlock("LOGIN");
            $template->touchBlock("LOGIN");
        } 

        // Inicio de sesión
        if (isset($_GET['loginBot'])) {
            $username = mysqli_real_escape_string($link, $_GET['username']);
            $password = mysqli_real_escape_string($link, $_GET['password']);

            $query = "SELECT username FROM Usuarios WHERE username = '$username' AND contrasena = '$password'";

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
        
        // Si se pica el botón de registrarse en mensajeBienvenida.html se abrirá registro.html
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
                if (!mysqli_query($link, $query_insert_user)) {
                    echo "Error de inserción: " . mysqli_error($link);
                } else {
                    echo "Usuario registrado exitosamente.";
                }

                $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
                $template->setCurrentBlock("DASHBOARD");
                $template->setVariable("USERNAME", $username);
                //$template->setVariable("NOTIFICACIONES", obtenerNumeroNotificaciones($username));
                $template->parseCurrentBlock("DASHBOARD");
            }
        }

    } else {
        // Cargar la página principal si no se está intentando iniciar sesión o registrar
        $template->addBlockfile("CONTENIDO", "WELCOME", "mensajeBienvenida.html");
        $template->setCurrentBlock("WELCOME");
        $template->touchBlock("WELCOME");
    }
    if ($_GET['action'] == 'perfil') {
        $template->addBlockfile("CONTENIDO", "PERFIL", "perfil.html");
        $template->setCurrentBlock("PERFIL");
        $template->touchBlock("PERFIL");
    }

    if ($_GET['action'] == 'notificaciones') {
        $template->addBlockfile("CONTENIDO", "NOTIFICACIONES", "notificaciones.html");
        $template->setCurrentBlock("NOTIFICACIONES");
        $template->touchBlock("NOTIFICACIONES");
    }

    if ($_GET['action'] == 'favoritos') {
        

        // Obtener el idUsuario del usuario actual usando $username
        $query_id_usuario = "SELECT idUsuario FROM Usuarios WHERE username = '$username'";
        $result_id_usuario = mysqli_query($link, $query_id_usuario);
        $row_id_usuario = mysqli_fetch_assoc($result_id_usuario);
        $idUsuario = $row_id_usuario['idUsuario'];
    
        // Obtener los usuarios favoritos del usuario actual
        $query_favoritos = "SELECT * FROM Favoritos WHERE idUsuario = $idUsuario";
        $result_favoritos = mysqli_query($link, $query_favoritos);
    
        // Inicializar una cadena para almacenar el contenido de los usuarios favoritos
        $favoritos_content = '';
    
        // Iterar a través de los resultados y construir el contenido de los usuarios favoritos
        while ($row_favoritos = mysqli_fetch_assoc($result_favoritos)) {
            $idUsuarioFavorito = $row_favoritos['idUsuarioFavorito'];
    
            // Obtener los datos del usuario favorito
            $query_usuario_favorito = "SELECT * FROM Usuarios WHERE idUsuario = $idUsuarioFavorito";
            $result_usuario_favorito = mysqli_query($link, $query_usuario_favorito);
            $row_usuario_favorito = mysqli_fetch_assoc($result_usuario_favorito);
    
            // Construir el contenido de cada usuario favorito
            $favoritos_content .= '<div class="favorite-user">';
            $favoritos_content .= '<img src="path_to_user_picture" alt="Foto de Perfil">';
            $favoritos_content .= '<h3>' . $row_usuario_favorito['nombre'] . '</h3>';
            $favoritos_content .= '<button type="submit" name="terceroBot" value="' . $idUsuarioFavorito . '">Ver perfil</button>';
            $favoritos_content .= '</div>';
        }
    
        // Asignar el contenido de los usuarios favoritos a la plantilla
        $template->setCurrentBlock("FAVORITOS");
        $template->setVariable("FAVORITOS_LIST", $favoritos_content);
        $template->parseCurrentBlock("FAVORITOS");
    }
    

    if ($_GET['action'] == 'historial') {
        $template->addBlockfile("CONTENIDO", "HISTORIAL", "historial.html");
        $template->setCurrentBlock("HISTORIAL");
        $template->touchBlock("HISTORIAL");
    }

    if (isset($_POST['terceroBot1'])) {

        $template->addBlockfile("CONTENIDO", "PERFILTERCERO", "perfiltercero.html");
        $template->setCurrentBlock("PERFILTERCERO");
        $template->touchBlock("PERFILTERCERO");

    }

    if (isset($_POST['terceroBot2'])) {

        $template->addBlockfile("CONTENIDO", "PERFILTERCERO", "perfiltercero.html");
        $template->setCurrentBlock("PERFILTERCERO");
        $template->touchBlock("PERFILTERCERO");

    }

    if (isset($_POST['pingBot'])) {
        // // Realizar la conexión a la base de datos
        // $link = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password']) or die('Could not connect: ' . mysqli_error($link));
        // mysqli_select_db($link, $cfgServer['dbname']) or die("Could not select database");
    
        // Obtener idUsuario del usuario actual
        $query_usuario = "SELECT idUsuario FROM Usuarios WHERE username = '$username'";
        $result_usuario = mysqli_query($link, $query_usuario);
        $row_usuario = mysqli_fetch_assoc($result_usuario);
        $idUsuario = $row_usuario['idUsuario'];
    
        // Obtener idUsuario del remitente del ping
        $query_remitente = "SELECT idUsuario FROM Usuarios WHERE username = '$usernameremitente'";
        $result_remitente = mysqli_query($link, $query_remitente);
        $row_remitente = mysqli_fetch_assoc($result_remitente);
        $idRemitente = $row_remitente['idUsuario'];
    
        // Insertar notificación en la tabla Notificaciones
        $mensaje = "Te ha enviado un ping";
        $query_insert = "INSERT INTO Notificaciones (idUsuario, idRemitente, mensaje) VALUES ('$idUsuario', '$idRemitente', '$mensaje')";
        if (mysqli_query($link, $query_insert)) {
            // Notificación insertada exitosamente
            echo "Notificación enviada exitosamente.";
        } else {
            // Error al insertar la notificación
            echo "Error al enviar la notificación: " . mysqli_error($link);
        }
    
        // // Cerrar la conexión a la base de datos
        // mysqli_close($link);
    
        // Añadir el bloque del template
        $template->addBlockfile("CONTENIDO", "PERFILTERCERO", "perfiltercero.html");
        $template->setCurrentBlock("PERFILTERCERO");
        $template->touchBlock("PERFILTERCERO");
    }

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
                if (!mysqli_query($link, $query_insert_user)) {
                    echo "Error de inserción: " . mysqli_error($link);
                } else {
                    echo "Usuario registrado exitosamente.";
                }

                $template->addBlockfile("CONTENIDO", "DASHBOARD", "dashboard.html");
                $template->setCurrentBlock("DASHBOARD");
                $template->setVariable("USERNAME", $username);
                //$template->setVariable("NOTIFICACIONES", obtenerNumeroNotificaciones($username));
                $template->parseCurrentBlock("DASHBOARD");
            }
        }

    
    // Cargar los nuevos templates

    // Mostrar la plantilla final con las modificaciones realizadas
    $template->show();
?>
