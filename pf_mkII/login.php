<?php
    session_start();

    require_once 'database.php';
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    if (isset($_POST['loginBot'])) {
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $password = mysqli_real_escape_string($link, $_POST['password']);

        // Preparar consulta para buscar usuario
        $stmt = mysqli_prepare($link, "SELECT idUsuario, username FROM Usuarios WHERE username = ? AND contrasena = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $idUsuario, $fetchedUsername);
            if (mysqli_stmt_fetch($stmt)) {
                $_SESSION['username'] = $fetchedUsername; 
                $_SESSION['idUsuario'] = $idUsuario;
                $_SESSION['is_admin'] = false; 
                cargarDashboardUsuario($template, $fetchedUsername);
            } else {
                // Intentar como administrador
                $stmt = mysqli_prepare($link, "SELECT username FROM Administradores WHERE username = ? AND contrasena = ?");
                mysqli_stmt_bind_param($stmt, "ss", $username, $password);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $fetchedAdminUsername);
                if (mysqli_stmt_fetch($stmt)) {
                    $_SESSION['username'] = $fetchedAdminUsername;
                    $_SESSION['is_admin'] = true;
                    cargarDashboardAdmin($template, $fetchedAdminUsername, $link);
                } else {
                    mostrarErrorLogin($template);
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            mostrarErrorLogin($template);
        }
    } else {
        $template->addBlockfile("CONTENIDO", "LOGIN", "login.html");
        $template->setCurrentBlock("LOGIN");
        $template->touchBlock("LOGIN");
    }
?>