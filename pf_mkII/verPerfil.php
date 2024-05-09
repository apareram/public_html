<?php
    include "config.php";
    require_once "HTML/Template/ITX.php";
    require_once "database.php";

    $link = getDatabaseConnection();
    $idUsuario = $_GET['idUsuario'] ?? '';
    $busqueda = $_GET['busqueda'] ?? '';

    // Inserta el historial de búsqueda solo si hay un término de búsqueda
    if (!empty($busqueda)) {
        $queryHistorial = "INSERT INTO Historial (idUsuario, términoBuscado) VALUES (?, ?)";
        $stmtHistorial = mysqli_prepare($link, $queryHistorial);
        mysqli_stmt_bind_param($stmtHistorial, "is", $_SESSION['idUsuario'], $busqueda); // Asegúrate de tener el ID del usuario en sesión
        mysqli_stmt_execute($stmtHistorial);
        mysqli_stmt_close($stmtHistorial);
    }

    $query = "SELECT nombre, ap_paterno, ap_materno, username, email FROM Usuarios WHERE idUsuario = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("perfilUsuario.html", true, true);

    if ($line = mysqli_fetch_assoc($result)) {
        $template->setVariable("NOMBRE", $line['nombre']);
        $template->setVariable("AP_PATERNO", $line['ap_paterno']);
        $template->setVariable("AP_MATERNO", $line['ap_materno']);
        $template->setVariable("USERNAME", $line['username']);
        $template->setVariable("EMAIL", $line['email']);
    } else {
        $template->setVariable("MESSAGE", "Usuario no encontrado.");
    }

    $template->show();
    mysqli_stmt_close($stmt);
    mysqli_close($link);
?>
