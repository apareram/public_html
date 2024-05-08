<?php
    if (isset($_POST['submit'])) {
        $idUsuario = $_POST['idUsuario']; // Sanitize and validate this ID
        $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
        $ap_paterno = mysqli_real_escape_string($link, $_POST['ap_paterno']);
        $ap_materno = mysqli_real_escape_string($link, $_POST['ap_materno']);
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $contrasena = $_POST['contrasena'];
        $calle = mysqli_real_escape_string($link, $_POST['calle']);
        $numero = $_POST['numero'];
        $colonia = mysqli_real_escape_string($link, $_POST['colonia']);
        $zip_code = $_POST['zip_code'];

        $query = "UPDATE Usuarios SET nombre = '$nombre', ap_paterno = '$ap_paterno', ap_materno = '$ap_materno', username = '$username', email = '$email', contrasena = '$contrasena', calle = '$calle', numero = '$numero', colonia = '$colonia', zip_code = '$zip_code' WHERE idUsuario = $idUsuario";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Usuario actualizado con éxito.";
            // Redirect or reload admin dashboard
        } else {
            echo "Error al actualizar usuario.";
        }
    }
?>