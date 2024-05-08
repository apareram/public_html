<?php
    $idUsuario = (int) $_POST['id']; // Ensuring the ID is an integer
    $query = "DELETE FROM Usuarios WHERE idUsuario = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Usuario eliminado con éxito.";
        } else {
            echo "Error al eliminar el usuario.";
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $mysqli->error;
    }

    // Redirect to admin dashboard or show a message
    header("Location: adminPanel.php");
    exit();
?>