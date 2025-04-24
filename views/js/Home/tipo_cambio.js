$(document).ready(function() {
    Control();
});

function Control() {
    
    CargarTipoCambio();
    BuscarDatos();
    
    $('#btnGuardarTC').click(function() {
        GuardarTipoCambio();
    });
}

function CargarTipoCambio() {
    bloquearPantalla("Buscando...");
    var url = "../../models/Home/control_valores/tipo_cambio.php";
    var dato = {
        "btnListarTipoCambio": true
    };
    realizarJsonPost(url, dato, respuestaBuscarTipoCambio, null, 10000, null);
}

function respuestaBuscarTipoCambio(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaTipoCambio(dato.data);
}

var getTablaBusquedaTipoCambio = null;
function LlenarTabalaTipoCambio(datos) {
    if (getTablaBusquedaTipoCambio) {
        getTablaBusquedaTipoCambio.destroy();
        getTablaBusquedaTipoCambio = null;
    }

    getTablaBusquedaTipoCambio = $('#TablaTipoCambio').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "1%"
            },
            {
                'aTargets': [1],
                'ordering': false,
                'width': "1%"
            }
        ],
        ordering: false,
        "info": true,
        "searching": false,
        "lengthChange": false,
        "paging": true,
        destroy: true,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        columns: [
            { "data": "contador" },
            { "data": "fecha" },
            { "data": "valor" },
            { "data": "registro" }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        },
        "order": [
            [2, 'asc']
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
    setTimeout(function() {
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }, 100);
}


/***************************BUSCAR LISTA DE ZONAS POR PROYECTO****************************** */

function BuscarDatos() { 

    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnBuscarDatos": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/Home/control_valores/tipo_cambio.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                $("#txtFechaTC").val(dato.fecha);
                $("#txtRegistroTC").val(dato.registro);
            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function ValidarCamposTipoCambio() {
    var flat = true;
    if ($("#txtFechaTC").val() === "" || $("#txtFechaTC").val() === null) {
        $("#txtFechaTC").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese la fecha de hoy.", "info");
        flat = false;
    } else if ($("#txtValorTC").val() === "" || $("#txtValorTC").val() === null) {
        $("#txtValorTC").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese valor del tipo de cambio aplicable a la fecha ingresada.", "info");
        flat = false;
    }
    return flat;
}

function GuardarTipoCambio() { 
    if(ValidarCamposTipoCambio()){
        var timeoutDefecto = 1000 * 60;
        bloquearPantalla("Procesando...");
        var data = {
            "btnGuardarTipoCambio": true,
            "txtFechaTC" : $("#txtFechaTC").val(),
            "txtValorTC" : $("#txtValorTC").val(),
            "__ID_USER" : $("#__ID_USER").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/Home/control_valores/tipo_cambio.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                if (dato.status == "ok") {
                    mensaje_alerta("\u00A1CORRECTO!", "Se guard\u00F3 correctamente el tipo de cambio.", "success");
                    CargarTipoCambio();
                }else{
                    mensaje_alerta("\u00A1ERROR!", "No se complet\u00F3 el registro del tipo de cambio ingresado.", "info");
                } 
    
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
                desbloquearPantalla();
            },
            timeout: timeoutDefecto
        });
    }
}




function ControlSesion(){ 
    var data = {
        "ReturnControlSesion": true,
        "d_u_sn": $("#d_u_sn").val()        
    };
    $.ajax({
        type: "POST",
        url: "../../models/session/control/control_sesion.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            if (dato.status == "ok") {
                console.log(dato.data);
                mensaje_sesion(dato.data, irlogin, dato.url);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
    });
}

function irlogin(ruta){
    window.location.href = ruta;
}