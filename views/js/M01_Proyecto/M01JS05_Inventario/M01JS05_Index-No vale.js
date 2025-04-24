var ListaTemporalMazanas = Array();
$(document).ready(function() {
    Control();
});

function Control() {
    $('#cbxFiltroProyecto').change(function() {
        BuscarZonas();
    });
    BuscarZonas();   
    initMap();
}

function BuscarCoordenadasProyecto() {   
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "ReturnListaCoordenada": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
               
                  var lat = "-12.080468299740325";
                  var long = "-77.00164279703625";
                  console.log(lat+' '+long); 
                  initMap(lat, long);   

            } else {
                mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function initMap() {
    const map = new google.maps.Map(document.getElementById("map2"), {
        zoom: 15,
        center: { lat: -12.080468299740325, lng: -77.00164279703625},
    });    

}



/***************************BUSCAR LISTA DE ZONAS POR PROYECTO****************************** */
function BuscarZonas() {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnListaZona": true,
        "idProyecto": $("#cbxFiltroProyecto").val().trim(),
    };
    realizarJsonPost(url, dato, respuestaBuscarZonas, null, 10000, null);
}
/*********************RESPUESTA LISTA DE ZONAS POR PROYECTO*********************** */
function respuestaBuscarZonas(dato) {
    console.log(dato);
    desbloquearPantalla();
    if (dato.status == "ok") {
        PintarZonas(dato.data);
    } else {
        PintarZonas([]);
    }
}

function PintarZonas(datos) {

    var html = "";
    for (var i = 0; i < datos.length; i++) {
        var heading = "heading0" + i;
        var idCollapse = "collapse0" + i;

        var htmlManzanas = "";

        for (var j = 0; j < datos[i].manzanas.length; j++) {
            htmlManzanas += "<a href='" + datos[i].manzanas[j].url + "' class='btn btn-outline-dark w-100 mb-2 title-02'>" + datos[i].manzanas[j].nombre + "</a>";
        }

        html += "<div class='card mb-1'>\
                    <div class='card-header bg-info expandable collapsed' id='" + heading + "'\
                        data-toggle='collapse' data-target='#" + idCollapse + "' aria-expanded='false'\
                        aria-controls='" + idCollapse + "'>\
                        <h5 class='mb-0 text-white title-01'>\
                            " + datos[i].nombre + "\
                            <i class='rotate fa fa-chevron-down'></i>\
                        </h5>\
                    </div>\
                    <div id='" + idCollapse + "' class='collapse' aria-labelledby='" + heading + "'\
                        data-parent='#accordion'>\
                        <div class='card-body' style='padding: 10px 0px;'>\
                            " + htmlManzanas + "\
                        </div>\
                    </div>\
                    </div>";

    }
    $("#accordion").html(html);
}

/***************************BUSCAR LISTA DE MANZANAS POR ZONA****************************** */
function BuscarManzanas(id) {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnListaManzana": true,
        "idZona": id,
    };
    realizarJsonPost(url, dato, respuestaBuscarManzanas, null, 10000, null);
}
/*********************RESPUESTA LISTA DE MANZANAS POR ZONA*********************** */
function respuestaBuscarManzanas(dato) {
    desbloquearPantalla();
    ListaTemporalMazanas = [];
    console.log(dato);
    if (dato.status == "ok") {
        ListaTemporalMazanas = dato.data;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}