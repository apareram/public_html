// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addSchedule').addEventListener('click', addScheduleEntry);
});

function addScheduleEntry() {
    var container = document.getElementById('scheduleEntries');
    
    var scheduleDiv = document.createElement('div');
    scheduleDiv.classList.add('schedule-entry');
    
    var daySelect = document.createElement('select');
    daySelect.name = 'dias[]';
    ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'].forEach(function(day) {
        var option = document.createElement('option');
        option.value = day;
        option.textContent = day;
        daySelect.appendChild(option);
    });
    scheduleDiv.appendChild(daySelect);
    
    var arrivalTimeInput = document.createElement('input');
    arrivalTimeInput.type = 'time';
    arrivalTimeInput.name = 'horaLlegada[]';
    scheduleDiv.appendChild(arrivalTimeInput);
    
    var departureTimeInput = document.createElement('input');
    departureTimeInput.type = 'time';
    departureTimeInput.name = 'horaSalida[]';
    scheduleDiv.appendChild(departureTimeInput);

    // Botón para eliminar un horario
    var removeButton = document.createElement('button');
    removeButton.textContent = 'Eliminar';
    removeButton.type = 'button';
    removeButton.onclick = function() {
        this.parentNode.remove();
    };
    scheduleDiv.appendChild(removeButton);

    container.appendChild(scheduleDiv);
}

// Ejecuta la función una vez para añadir el primer conjunto de selección de horario
addScheduleEntry();

//funcion error
function verifica() {
    if (document.formaPresidentes.apellidoPresidente.value.length == 0) {
        alert("Por favor ingresa el apellido del presidente.");
        return false;
    }
    return true;
}

// Supongamos que tienes un endpoint 'obtenerNotificaciones.php' que devuelve el número de notificaciones para el usuario
function actualizarNotificaciones() {
    fetch('obtenerNotificaciones.php') // Asegúrate de que esta URL apunte a tu script PHP que devuelve el número de notificaciones
        .then(response => response.json()) // Asume que la respuesta es un JSON
        .then(data => {
            if(data.numeroNotificaciones !== undefined) {
                // Supongamos que tienes un elemento span con id 'contador-notificaciones' donde mostrarás el número de notificaciones
                document.getElementById('contador-notificaciones').textContent = data.numeroNotificaciones;
            }
        })
        .catch(error => {
            console.error('Error al actualizar las notificaciones:', error);
        });
}

// Llama a la función cuando la página se carga
document.addEventListener('DOMContentLoaded', actualizarNotificaciones);

// Además, podrías establecer un intervalo para actualizar las notificaciones periódicamente
// Por ejemplo, actualizar cada 5 minutos
setInterval(actualizarNotificaciones, 300000); // 300000 ms = 5 minutos
