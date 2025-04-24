$(document).ready(function() {
    Control();
});

function Control() {
    ControlSesion();
    ColoresEstados();
    ejuctarInicioProyecto();

    $('#bxProyectoDato').change(function () {
        ejuctarInicioProyecto();
        ActualizarBoxManzanas();
    });

    $('#cbxZonascc').change(function () {
        ejuctarInicioProyecto();
    });

    $('#bxManzanasProyecto').change(function () {
        ejuctaBusquedaManzanaLotes();
    });

    $('#bxZonasProyecto').change(function () {
         if($('#bxZonasProyecto').val() == "todos"){
             ejuctarInicioProyecto();
         }else{
            ejuctaBusquedaManzanaLotes();
         }        
    });
	
}

function ActualizarBoxManzanas(){
    ControlSesion();
    $("#bxZonasProyecto").val("");
    $("#bxManzanasProyecto").val("");
    var url = '../../models/Home/Dashboard/Dashboard_Proceso.php';
    var datos = {
        "ReturnListaManzanas": true,
        "idZona": $('#bxZonasProyecto').val()
    }
    llenarCombo(url, datos, "bxManzanasProyecto");
}

function ColoresEstados(){

    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "ReturnColoresEstados": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/Home/Dashboard/Dashboard_Proceso.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {

                var intro = document.getElementById('pnlTotalLibres');
                intro.style.backgroundColor = dato.color1;

                var intro = document.getElementById('pnlTotalReservados');
                intro.style.backgroundColor = dato.color2;

                var intro = document.getElementById('pnlTotalPorVencer');
                intro.style.backgroundColor = dato.color3;

                var intro = document.getElementById('pnlTotalVencido');
                intro.style.backgroundColor = dato.color4;

                var intro = document.getElementById('pnlTotalVendidoT');
                intro.style.backgroundColor = dato.color5;

                var intro = document.getElementById('pnlTotalVendidoTC');
                intro.style.backgroundColor = dato.color6;

                var intro = document.getElementById('pnlTotalBloqueados');
                intro.style.backgroundColor = dato.color7;

                var intro = document.getElementById('pnlTotalCanjes');
                intro.style.backgroundColor = dato.color8;


                 var intro = document.getElementById('txtValorLteLibres');
                intro.style.backgroundColor = dato.color1;

                var intro = document.getElementById('txtValorLteReservados');
                intro.style.backgroundColor = dato.color2;

                var intro = document.getElementById('txtValorLtePorVencer');
                intro.style.backgroundColor = dato.color3;

                var intro = document.getElementById('txtValorLteVencidos');
                intro.style.backgroundColor = dato.color4;

                var intro = document.getElementById('txtValorLteVendidosT');
                intro.style.backgroundColor = dato.color5;

                var intro = document.getElementById('txtValorLteVendidosTC');
                intro.style.backgroundColor = dato.color6;

                 var intro = document.getElementById('txtValorLteBloqueados');
                intro.style.backgroundColor = dato.color7;

                var intro = document.getElementById('txtValorLteCanjes');
                intro.style.backgroundColor = dato.color8;



            } else {
                mensaje_alerta("�0�3IMPORTANTE!", dato.data, "info");
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
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: -9.54228113, lng: -77.53112970 },
    });
}

function BuscarCoordenadasProyecto() {
    bloquearPantalla("Cargando...");
    var url = "../../models/Home/Dashboard/Dashboard_Proceso.php";
    var dato = {
        "ReturnListaCoordenada": true,
    };
    realizarJsonPost(url, dato, RespuestaBuscarCoordenadasProyecto, null, 10000, null);
}

function RespuestaBuscarCoordenadasProyecto(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        PintarCoordenadas(dato.data);
        return;
    }
}

function PintarCoordenadas(dato) {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: -9.54228113, lng: -77.53112970 },
    });
    var data1 = [];
    for (var i = 0; i < dato.length; i++) {
        data1.push(
            [
                { lat: parseFloat(dato[i].latitud), lng: parseFloat(dato[i].longitud) },
                dato[i].descripcion,
                "#f70606"
            ]);
    }

    const infoWindow = new google.maps.InfoWindow();

    data1.forEach(([position, title, color], i) => {
        const marker = new google.maps.Marker({
            position,
            map,
            title: `${i + 1}. ${title}`,
            //label: `${i + 1}`,
            optimized: false,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                strokeColor: color,
                scale: 10
            },
        });
        marker.addListener("click", () => {
            infoWindow.close();
            infoWindow.setContent(marker.getTitle());
            infoWindow.open(marker.getMap(), marker);
        });
    });
}

function ejuctarInicioProyecto(){
    var dato_id = $('#bxProyectoDato').val();
    ControlSesion();
    BuscarTotales(dato_id);
    LLenarZonasProyecto(dato_id);
}

function BuscarTotales(id) {
    bloquearPantalla("Cargando...");
    var url = "../../models/Home/Dashboard/Dashboard_Proceso.php";
    var dato = {
        "ReturnTotales": true,
        "idProy" : id
    };
    realizarJsonPost(url, dato, RespuestaBuscarTotales, null, 10000, null);
}

function RespuestaBuscarTotales(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#txtTotalZonas").val(dato.data.totZonas);
        $("#txtTotalManzanas").val(dato.data.totManzanas);
        $("#txtTotalLotes").val(dato.data.totLotes);
        $("#txtTotalClientes").val(dato.data.totClientes);
        $("#txtTotalVentas").val(dato.data.totVentas);
        $("#txtTotalLibres").val(dato.data.totLibres);
        $("#txtTotalReservados").val(dato.data.totReservados);
        $("#txtTotalPorVencer").val(dato.data.totPorVencer);
        $("#txtTotalVencido").val(dato.data.totVencidos);
        $("#txtTotalVendidoT").val(dato.data.totVendidosT);
        $("#txtTotalVendidoTC").val(dato.data.totVendidosTC);
        $("#txtTotalBloqueados").val(dato.data.totBloqueados);
        $("#txtTotalCanjes").val(dato.data.totCanjes);

		$("#txtTotalLotesDetalle").val(dato.data.totLotes);
        $("#txtValorLteLibres").val(dato.data.totLibres);
        $("#txtValorLteReservados").val(dato.data.totReservados);
        $("#txtValorLtePorVencer").val(dato.data.totPorVencer);
        $("#txtValorLteVencidos").val(dato.data.totVencidos);
        $("#txtValorLteVendidosT").val(dato.data.totVendidosT);
        $("#txtValorLteVendidosTC").val(dato.data.totVendidosTC);
        $("#txtValorLteBloqueados").val(dato.data.totBloqueados);
        $("#txtValorLteCanjes").val(dato.data.totCanjes);

        $("#txtNombreProyecto").val(dato.data.nombre_proyecto);
        $("#txtAreaProyecto").val(dato.data.area_proyecto);
        $("#txtInicioProyecto").val(dato.data.inicio_proyecto);
        $("#txtDireccionProyecto").val(dato.data.direccion_proyecto);
        $("#bxDepartamentoProyecto").val(dato.data.departamento_proyecto);
        LLenarProvinciaId(dato.data.departamento_proyecto, dato.data.provincia_proyecto);
        LLenarDistritoId(dato.data.provincia_proyecto, dato.data.distrito_proyecto);
        //LlenarDistritoProyecto(id);
        return;
    }
}



//Llenar lista de provincia en base al valor del departamento
function LLenarProvinciaId(idDep, idPro) {
    var url = '../../../models/General/BusquedaUbigeo.php';
    var datos = {
        "ReturnListaProvincia": true,
        "ubigeo": idDep
    }
    llenarComboSelecionar(url, datos, "bxProvinciaProyecto", idPro);
}

//Llenar lista de distrito en base al valor de la provincia
function LLenarDistritoId(idProv, idDist) {
    var url = '../../../models/General/BusquedaUbigeo.php';
    var datos = {
        "ReturnListaDistritos": true,
        "ubigeo": idProv
    };
    llenarComboSelecionar(url, datos, "bxDistritoProyecto", idDist);
}


//LLENAR ZONAS
function LLenarZonasProyecto(id) {
    var url = '../../models/Home/Dashboard/Dashboard_Proceso.php';
    var datos = {
        "ReturnListaZonasProyecto": true,
        "idProyecto": id
    };
    llenarCombo(url, datos, "bxZonasProyecto");
}

//LLENAR MANZANAS
$('#bxZonasProyecto').change(function () {
    $("#bxManzanasProyecto").val("");
    var url = '../../models/Home/Dashboard/Dashboard_Proceso.php';
    var datos = {
        "ReturnListaManzanas": true,
        "idZona": $('#bxZonasProyecto').val()
    }
    llenarCombo(url, datos, "bxManzanasProyecto");
});

function ejuctaBusquedaManzanaLotes(){
    ControlSesion();
    var dato_id = $('#bxManzanasProyecto').val();
    BuscarTotalesManzana(dato_id);
}

function BuscarTotalesManzana(id) {
    bloquearPantalla("Cargando...");
    var url = "../../models/Home/Dashboard/Dashboard_Proceso.php";
    var dato = {
        "ReturnTotalesManzana": true,
        "idManzan" : id,
        "idpro" : $('#bxProyectoDato').val(),
        "idzon" : $('#bxZonasProyecto').val()
    }
    realizarJsonPost(url, dato, RespuestaBuscarTotalesManzana, null, 10000, null);
}

function RespuestaBuscarTotalesManzana(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
		 
		$("#txtTotalLotesDetalle").val(dato.data.totLotes);	
        $("#txtValorLteLibres").val(dato.data.totLibres);
        $("#txtValorLteReservados").val(dato.data.totReservados);
        $("#txtValorLtePorVencer").val(dato.data.totPorVencer);
        $("#txtValorLteVencidos").val(dato.data.totVencidos);
        $("#txtValorLteVendidosT").val(dato.data.totVendidosT);
        $("#txtValorLteVendidosTC").val(dato.data.totVendidosTC);
        $("#txtValorLteBloqueados").val(dato.data.totBloqueados);
        $("#txtValorLteCanjes").val(dato.data.totCanjes);
        return;
    }
}


/***************************BUSCAR LISTA DE ZONAS POR PROYECTO****************************** */

function BuscarProyectos() { 

    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "ReturnListaProyectos": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/Home/Dashboard/Dashboard_Proceso.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                console.log(dato.data);
                PintarProyectos(dato.data);
            } else {
                mensaje_alerta("�0�3IMPORTANTE!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

/*********************RESPUESTA LISTA DE ZONAS POR PROYECTO*********************** */
function PintarProyectos(datos) {
    console.log(datos);
    var html = "";

    if (datos.length == 0) {
        html += "<div class='col-md-12'>\
        <div class='alert alert-warning' role='alert'>\
        No se encontraron registro de <strong>LOTES</strong>!\
        </div>\
      </div>";
    }

    for (var i = 0; i < datos.length; i++) {

        var nombre=datos[i].nombre;
        var direccion = datos[i].direccion;
        var area = datos[i].area;
        var zonas = datos[i].zonas;
        var manzanas = datos[i].manzanas;
        var lotes = datos[i].lotes;

         html += "<div class='bg-info p-10 text-white text-center' style='width: 230px; margin-left: 2%; margin-top: 1%; border-radius: 8px;'>\
                    <i class='fas fa-map-marker-alt m-b-5 font-16'></i>\
                    <a href='"+ datos[i].url +"' class='text-white'><h5 class='m-b-0 m-t-5'>"+ nombre +"</h5></a>\
                    <small class='font-light'>Ubicaci��n : "+ direccion +"</small><br>\
                    <small class='font-light'>�0�9rea : "+ area +" m<sup>2</sup></small><br>\
                    <small class='font-light'>Nro Zonas : "+ zonas +"</small><br>\
                    <small class='font-light'>Nro Manzanas : "+ manzanas +"</small><br>\
                    <small class='font-light'>Nro Lotes : "+ lotes +"</small><br>\
                    </div>";

    }

    $("#accordion_proyecto").html(html);
    //ActivarOpcionesZona();
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