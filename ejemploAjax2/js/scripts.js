
// Funciones de javaScript

function buscaPresidentes(estado){
    
    console.log('Recibimos: ' + estado);
    var params = "estado="+estado;
    
    var url = "./buscaPresidentes.php";
    
    console.log('Params: ' + params);
    
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'GET',
        async: true,
        data: params,
        success: muestraContenido,
        error: funcionError
    });
}

function muestraContenido(result, status, xhr){
    $("#resultadoBusqueda").html(result);
}

function funcionError(xhr, status, error){
    alert('Error: ' + error + 'Status: ' + status);
}