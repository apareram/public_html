<?php
    session_start();

    require_once "database.php";
    require_once "HTML/Template/ITX.php";

    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    $link = getDatabaseConnection(); 
    $idUsuario = (int) $_GET['idUsuario'];
    $busqueda = $_GET['busqueda'];

    // Inserta el historial de búsqueda solo si hay un término de búsqueda
    if (!empty($busqueda)) {
        $queryHistorial = "INSERT INTO Historial (idUsuario, términoBuscado) VALUES (?, ?)";
        $stmtHistorial = mysqli_prepare($link, $queryHistorial);
        mysqli_stmt_bind_param($stmtHistorial, "is", $_SESSION['idUsuario'], $busqueda);
        mysqli_stmt_execute($stmtHistorial);
        mysqli_stmt_close($stmtHistorial);
    }

    if ($idUsuario) {
        $query = "SELECT nombre, ap_paterno, ap_materno, username, email FROM Usuarios WHERE idUsuario = ?";
        $stmt = mysqli_prepare($link, $query);
        if (!$stmt) {
            echo "Error al preparar la consulta: " . mysqli_error($link);
            exit;
        }

        mysqli_stmt_bind_param($stmt, "i", $idUsuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($line = mysqli_fetch_assoc($result)) {
            $template->addBlockfile("CONTENIDO", "PUSUARIO", "perfilUsuario.html");
            $template->setCurrentBlock("PUSUARIO");
            $template->setVariable("NOMBRE", $line['nombre']);
            $template->setVariable("AP_PATERNO", $line['ap_paterno']);
            $template->setVariable("AP_MATERNO", $line['ap_materno']);
            $template->setVariable("USERNAME", $line['username']);
            $template->setVariable("EMAIL", $line['email']);
            $template->parseCurrentBlock("PUSUARIO");
        } else {
            $template->setVariable("MESSAGE", "Usuario no encontrado.");
        }
        mysqli_stmt_close($stmt);
    } else {
        $template->setVariable("MESSAGE", "No se especificó un usuario válido.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
?>