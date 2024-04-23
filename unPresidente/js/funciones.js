function verifica() {
    if (document.formaPresidentes.apellidoPresidente.value.length == 0) {
        alert("Por favor ingresa el apellido del presidente.");
        return false;
    }
    return true;
}