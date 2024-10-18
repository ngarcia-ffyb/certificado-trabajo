//########################################################################
function getHTML(pagina2,div,param,div2,metodo,formulario){
//########################################################################
	var url = pagina2;
	var pars = param;
	var opciones = {evalScripts: true}; 
	var efecto_ventana1 = function(request, div) {new Effect.Combo(div, {duration: 3, scaleX: true, scaleContent: true});}
	
	//alert(url + " - "+div+" - " + pars +" metodo="+metodo+" formulario="+formulario);
	
	if (metodo==1)//0 get 1 post
	{
	 metodo="post";
	 //pars = Form.serialize("F_login");
	 pars = Form.serialize(formulario);
	 
    }else{
	 metodo="get";
	 pars = param;
	}
    
	//alert(metodo+" "+pars);
	//alert(pars);
	 
	var myAjax = new Ajax.Updater({success: div},
		url,
		{evalScripts: true,
			method: metodo, 
			parameters: pars, 
			onFailure: reportError,onComplete: efecto_ventana(div2)},opciones);
	}

	function reportError(request)
	{
		alert('lo siento. ha ocurrido un error.');
	}

	function efecto_ventana(div3)
	{
	
	switch (div3)
	{
	case 'contenedor11':
	 break;
	default:
	 break;
	}	
	 
	}