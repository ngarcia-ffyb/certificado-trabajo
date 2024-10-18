$("#contactForm").submit(function(event){
    if (event.isDefaultPrevented()) {
        // handle the invalid form...
    } else {
        // everything looks good!
        event.preventDefault();
        //submitForm();
        submitForm2();
    }
});


function submitForm(){
    // Initiate Variables With Form Content
    var name = $("#name").val();
    var email = $("#email").val();
    var message = $("#message").val();
 
    $.ajax({
        type: "POST",
        url: "includes/form-process.php",
        data: "name=" + name + "&email=" + email + "&message=" + message,
        success : function(text){
            if (text == "success"){
                formSuccess();
            }
        }
    });
}

function submitForm2(){
    // Initiate Variables With Form Content
    var name = $("#name").val();
    var email = $("#email").val();
    var message = $("#message").val();
 
    $.ajax({
        type: "GET",
        url: "includes/form-process.php",
        dataType:'json',
        data: { proyecto: 1 },
        success : function(response){
            if (response.respuesta == true){
                //formSuccess();
                $("#contenedor").html(response.respuesta2);
            }
        }
    });
}

function formSuccess(){
    //$( "#msgSubmit" ).removeClass( "hidden" );
    $("#contenedor").html('aaa');

}