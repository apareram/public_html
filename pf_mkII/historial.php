<?php
    session_start();
    require_once "database.php";
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $idUsuario = $_SESSION['idUsuario']; // Usa el ID del usuario de la sesión

    // Consulta para obtener el historial de búsquedas del usuario
    $query = "SELECT términoBuscado, timestamp FROM Historial WHERE idUsuario = ? ORDER BY timestamp DESC";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("principal.html", true, true);

    // Preparar el bloque de contenido antes del bucle
    $template->addBlockfile("CONTENIDO", "HISTORIAL", "historial.html");

    // Verificar y procesar los resultados
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $template->setCurrentBlock("HISTORIA");
            $template->setVariable("TERMINO_BUSCADO", $row['términoBuscado']);
            $template->setVariable("FECHA", $row['timestamp']);
            $template->parseCurrentBlock("HISTORIA");
        }
    } else {
        $template->setVariable("MENSAJE", "No hay historial de búsqueda disponible.");
    }

    // Cerrar la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($link);
?>