<script>
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
</script>