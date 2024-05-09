<?php
    require_once "database.php";  // Cambia esta línea para incluir database.php en lugar de config.php
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();  // Esta línea ahora debería funcionar correctamente

    $busqueda = $_GET['busqueda'] ?? '';  // Asegurarse de que la variable existe
    $busqueda = mysqli_real_escape_string($link, $busqueda);
    $query = "SELECT idUsuario, nombre, ap_paterno, ap_materno, username, email FROM Usuarios WHERE CONCAT(nombre, ' ', ap_paterno, ' ', ap_materno, ' ', username, ' ', email) LIKE CONCAT('%', ?, '%')";
    $stmt = mysqli_prepare($link, $query);

    mysqli_stmt_bind_param($stmt, "s", $busqueda);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("resultadoBusqueda.html", true, true);

    if (mysqli_num_rows($result) > 0) {
        while ($line = mysqli_fetch_assoc($result)) {
            $template->setCurrentBlock("USUARIO");
            $template->setVariable("IDUSUARIO", $line['idUsuario']);
            $template->setVariable("NOMBRE", $line['nombre']);
            $template->setVariable("AP_PATERNO", $line['ap_paterno']);
            $template->setVariable("AP_MATERNO", $line['ap_materno']);
            $template->setVariable("USERNAME", $line['username']);
            $template->setVariable("EMAIL", $line['email']);
            $template->setVariable("BUSQUEDA", $busqueda);
            $template->parseCurrentBlock("USUARIO");
        }
    } else {
        $template->setVariable("MESSAGE", "No se encontraron resultados.");
    }

    $template->show();
    mysqli_stmt_close($stmt);
    mysqli_close($link);
?>