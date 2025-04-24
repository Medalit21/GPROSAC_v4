var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    $('.modal').on("hidden.bs.modal", function(e) {
        if ($('.modal:visible').length) {
            $('body').addClass('modal-open');
        }
    });
    
    var input=  document.getElementById('usuario');
    input.addEventListener('input',function(){
          if (this.value.length > 11) 
             this.value = this.value.slice(0,11); 
     })
    

    $('#btnacceder').click(function() {
        Login();
     });


}


function Login() {
    
    var data = { "btnacceder": true,
                  "usuario": $("#usuario").val(),
                  "clave": $("#clave").val()};
    $.ajax({
        type: "POST",
        url: "../models/get_login.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                var variable = '<?php echo $NAME_SERVER; ?>';   
                window.location.replace(''+variable+'views/home.php');   

                alert("¡Error en Inicio Sesión!");  
              
            }else{

                alerta("¡Error en Inicio Sesión!", dato.data, "error");                                   

            }
            
       },
            error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}
