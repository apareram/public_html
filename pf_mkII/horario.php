<?php
    session_start();
    require_once "database.php";
    require_once "HTML/Template/ITX.php";

    $link = getDatabaseConnection();
    $template = new HTML_Template_ITX('./templates');
    $template->loadTemplatefile("horario.html", true, true);

    $idUsuario = $_SESSION['idUsuario'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $dias = $_POST['dias'] ?? [];
        $llegadas = $_POST['horaLlegada'] ?? [];
        $salidas = $_POST['horaSalida'] ?? [];

        foreach ($dias as $index => $dia) {
            $llegada = $llegadas[$index];
            $salida = $salidas[$index];

            $query = "INSERT INTO Horario (idUsuario, dia, llegada, salida) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE llegada = ?, salida = ?";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt, "isssss", $idUsuario, $dia, $llegada, $salida, $llegada, $salida);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "horario actualizado con exito";
    }

    // Cargar el horario actual del usuario
    $query = "SELECT dia, llegada, salida FROM Horario WHERE idUsuario = ? ORDER BY FIELD(dia, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes')";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $template->setCurrentBlock("HORAS");
            $template->setVariable("DIA", $row['dia']);
            $template->setVariable("LLEGADA", $row['llegada']);
            $template->setVariable("SALIDA", $row['salida']);
            $template->parseCurrentBlock("HORAS");
        }
    } else {
        $template->setVariable("MENSAJE", "No hay horario configurado.");
    }

    $template->show();
?>