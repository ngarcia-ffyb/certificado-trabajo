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
							window.location='principal4.php';
							break;
						case '2':
							window.location='operador4.php';
							break;
						case '3':
							window.location='moderador4.php';
							break;
						case '4':	
							window.location='principal4.php';							
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

	/*
	$("#Continue").click(function(){
		//alert("aaa");
		/*
		var dataString = $('#F_pedidos')serialize();
		
		var formSubmit = $(this).serialize(); 
		
		$.post('ajax.php',{"formSubmit": "true", "formInfo": formSubmit}
		$(document).ready(function(){ 
			var serial = $('#category-dynamic').serialize() + "&formSubmit=true";
			$('pre').html(serial); 
			$.post("http://jfcoder.com/test/processor.php", serial, function(data){ $('pre').html($('pre').html()+"\n\n"+data); }); });
		*/

		//$('#F_pedidos').attr('action', 'recibe_pedido.php');
		//$('#F_pedidos').submit();
	//}
	//);


	$("#Iniciar2").click(function(){
		window.location='index4.php?id=0';
	}
	);

	$("#Iniciar3").click(function(){
		//window.location='index.php?id=0';
		
	}
	);

	$("#Iniciar4").click(function(){
		window.location='index4.php?id=0';
	}
	);
});

function traer_especies(div,id_proyecto) {
	$.ajax('includes/especies.php', {
    type: 'GET',  // http method
	dataType:'json',    
    data: { proyecto: id_proyecto },  // data to submit
    success: function (response) {
    	if(response.respuesta==true){
    		$(div).append(response.respuesta2);
    	}else{

    	}	
        
    },
});
}

function traer_responsable(id_proyecto) {
	$.ajax('includes/responsable.php', {
    type: 'GET',  // http method
	dataType:'json',    
    data: { proyecto: id_proyecto },  
    success: function (response) {
    	if(response.respuesta==true){
    		$('#Sretira').html(response.mensaje);
    		$('#Sresponsable').html(response.respuesta2);
    		$('#Sespecies').html(response.especies);
    		$('#Scantidad').html(response.resto);
    		$('#Sproductos').html(response.productos);
    		
    		//$('#IDretira').html(response.mensaje);
    	}else{

    	}	
        
    },
});
}


function traer_proyectos(div, id_legajo) {
	$.ajax('includes/proyectos.php', {
    type: 'GET',  // http method
	dataType:'json',    
    data: { legajo: id_legajo },  
    success: function (response) {
    	if(response.respuesta==true){
    		$(div).html(response.respuesta2);
    	}else{

    	}	
        
    },
});
}


function traer_producto(div,id_proyecto) {
	//alert("aaa");
	$.ajax('includes/productos.php', {
    type: 'GET',  // http method
	dataType:'json',    
    data: { proyecto: id_proyecto },  
    success: function (response) {
    	if(response.respuesta==true){
    		$(div).html(response.respuesta2);
    	}	
    },
});
}