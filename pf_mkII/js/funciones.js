document.addEventListener('DOMContentLoaded', function() {
    addScheduleEntry();  // Añade la primera entrada de horario automáticamente al cargar la página
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

addScheduleEntry();

function buscaUsuarios(busqueda) {
    console.log('Iniciando búsqueda: ' + busqueda);
    var params = "busqueda=" + encodeURIComponent(busqueda);
    console.log('Params: ' + params);

    $.ajax({
        url: "./buscaUsuarios.php",
        type: 'GET',
        data: params,
        success: function(result) {
            console.log('Resultado recibido: ' + result);
            $("#resultadoBusqueda").html(result);
        },
        error: function(xhr, status, error) {
            console.error('Error en AJAX: ' + error);
        }
    });
}

function muestraContenido(result, status, xhr) {
    $("#resultadoBusqueda").html(result);
}

function funcionError(xhr, status, error) {
    alert('Error: ' + error + 'Status: ' + status);
}

function enviarPoke(idUsuario) {
    var url = "enviarPoke.php";
    var params = { idUsuario: idUsuario };
    $.post(url, params, function(data) {
        alert(data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        alert("Error al enviar poke: " + textStatus + " " + errorThrown);
    });
}