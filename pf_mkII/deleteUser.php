<?php
    session_start(); // Asegúrate de iniciar la sesión al principio

    if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
        header("Location: login.php"); // Redirige si no es admin o no está logueado
        exit();
    }

    require_once 'database.php';
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    if (isset($_POST['deleteUser'])) {
        $idUsuario = (int) $_POST['id'];
        $query = "DELETE FROM Usuarios WHERE idUsuario = ?";
        $stmt = mysqli_prepare($link, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $idUsuario);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "Usuario eliminado con éxito.";
            } else {
                echo "Error al eliminar el usuario.";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error al preparar la consulta: " . mysqli_error($link);
        }
        // Recargar el panel de administración
        cargarDashboardAdmin($template, $_SESSION['username'], $link);
    } else {
        // Si no se ha intentado borrar a un usuario, muestra la página normalmente
        cargarDashboardAdmin($template, $_SESSION['username'], $link);
    }
?>