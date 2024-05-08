<?php
session_start(); // Asegúrate de iniciar la sesión al principio

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

    // Intentar como administrador si no se encontró como usuario
    if (mysqli_num_rows($uResult) > 0) {
        $_SESSION['username'] = $user; // Guarda el nombre de usuario en la sesión
        $_SESSION['is_admin'] = false; // No es administrador
        cargarDashboardUsuario($template, $user);
    } else {
        $aQuery = "SELECT username FROM Administradores WHERE username = '$user' AND contrasena = '$pass'";
        $aResult = mysqli_query($link, $aQuery);
        if (mysqli_num_rows($aResult) > 0) {
            $_SESSION['username'] = $user; // Guarda el nombre de usuario en la sesión
            $_SESSION['is_admin'] = true; // Es administrador
            cargarDashboardAdmin($template, $user, $link);
        } else {
            mostrarErrorLogin($template);
        }
    }
} else {
    // Mostrar la plantilla de inicio de sesión si no se ha enviado el formulario
    $template->addBlockfile("CONTENIDO", "LOGIN", "login.html");
    $template->setCurrentBlock("LOGIN");
    $template->touchBlock("LOGIN");
}
?>