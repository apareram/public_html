
// ========================================================================
//
// 	FUNCIONES DE AJAX
// 
// ========================================================================

function despliegaContenido(contenido, cadena, valor){
		
	var params = "nombre="+cadena+"&numero="+valor;
	
	if (contenido == 1) {
		url = "./ejemploAjaxContenido1.php";
	}
	else if (contenido == 2) {
		url = "./ejemploAjaxContenido2.php";
	}
	else if (contenido == 3) {
		url = "./ejemploAjaxContenido3.php";
	}
	
	$.ajax({
		url: url,
		dataType: 'html',
		type: 'POST',
		async: true,
		data: params,
		success: muestraContenido,
		error: funcionErrors
	});
	
	return true;
}// 

function muestraContenido(result,status,xhr){
	$("#contenido").html(result);
}// muestraEditarUsuario

function funcionErrors(xhr,status,error){
	alert(xhr);
}// muestraEditarUsuario