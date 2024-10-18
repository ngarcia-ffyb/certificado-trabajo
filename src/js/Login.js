$(document).ready(function(){
	$("#Iniciar").click(function(){
		var Usuario=$('#usuario').val();
		var Contrasena=$("#contrasena").val();
		//console.log(Usuario,Contrasena);
		$.ajax({
			type:"POST",
			dataType:'json',
			url:'includes/LoginAjax.php',
			data:{Usuario:Usuario,Contrasena:Contrasena},
			success:function(response){
				if(response.respuesta==true){
					//alert(response.perfil);
					switch(response.perfil){
						case '1':
							window.location='principal.php';
							break;
						case '2':
							window.location='operador3.php';
							break;
						case '3':
							window.location='moderador.php';
							break;
						case '4':	
							window.location='principal.php';							
							break;
					}
					$("#mensaje").html(response.mensaje);
					//window.location='principal.php';
				}else{
					$("#mensaje").html(response.mensaje);
				}
			},error:function(){
				alert('Error general en el sistema');
			}
		});
	});

	$("#Iniciar2").click(function(){
		window.location='index.php?id=0';
	}
	);

	$("#Iniciar3").click(function(){
		//window.location='index.php?id=0';
		
	}
	);

	$("#Iniciar4").click(function(){
		window.location='index.php?id=0';
	}
	);
});

function traer_especies(div) {
	// body...
	//alert(div);
	$.ajax('includes/especies.php', {
    type: 'GET',  // http method
	dataType:'json',    
    data: { myData: 'This is my data.' },  // data to submit
    //success: function (data, status, xhr) {
    //    $(div).append('status: ' + status + ', data: ' + data);
    //},
    success: function (response) {
    	if(response.respuesta==true){
    		//$(div).append('status: ' + status + ', data: ' + data);
    		//$("#divid").load(" #divid");
    		//alert(response.respuesta2);
    		$(div).append(response.respuesta2);
    		//$(div).load(response.respuesta2);
    	}else{

    	}	
        
    },
    //error: function (jqXhr, textStatus, errorMessage) {$(div).append('Error' + errorMessage);}
});
}

function traer_responsable(id_proyecto) {
	//alert(id_proyecto);
	$.ajax('includes/responsable.php', {
    type: 'GET',  // http method
	dataType:'json',    
    data: { proyecto: id_proyecto },  
    success: function (response) {
    	if(response.respuesta==true){
    		$("#IDresponsable").append(response.respuesta2);
    	}else{

    	}	
        
    },
    //error: function (jqXhr, textStatus, errorMessage) {$(div).append('Error' + errorMessage);}
});
}





function traer_proyectos(div, id_legajo) {
	$.ajax('includes/proyectos.php', {
    type: 'GET',  // http method
	dataType:'json',    
    data: { legajo: id_legajo },  
    success: function (response) {
    	if(response.respuesta==true){
    		$(div).append(response.respuesta2);
    	}else{

    	}	
        
    },
    //error: function (jqXhr, textStatus, errorMessage) {$(div).append('Error' + errorMessage);}
});
}





/*
function traer_responsable(id_proyecto) {
	alert(id_proyecto);
	$.ajax('includes/responsable.php', {
    type: 'GET',  // http method
	dataType:'json',    
    data: { proyecto: id_proyecto },  
    success: function (response) {
    	if(response.respuesta==true){
    		$('#IDresponsable').append(response.respuesta2);
    	}else{

    	}	
     
    },
});
}
*/