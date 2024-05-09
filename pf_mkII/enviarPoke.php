<?php
    include "config.php";
    $link = getDatabaseConnection();

    $idUsuario = $_POST['idUsuario'];  // ID del usuario al que se le envía el poke
    $idRemitente = $_SESSION['idUsuario']; // ID del usuario que envía el poke, asegúrate de tener esta información disponible en la sesión

    // Mensaje del poke
    $mensaje = "¡Hola! Te he enviado un poke. ¿Quieres compartir el transporte?";

    $query = "INSERT INTO Notificaciones (idUsuario, idRemitente, mensaje) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "iis", $idUsuario, $idRemitente, $mensaje);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Poke enviado correctamente.";
    } else {
        echo "Error al enviar poke: " . mysqli_error($link);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
?>