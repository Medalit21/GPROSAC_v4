var ListaTemporalMazanas = Array();
$(document).ready(function() {
    Control();
});

function Control() {
    BuscarListaEstadoLeyenda();
    BuscarDetalleManzana();
    BuscarLotes();    
    
    $('#cbxFiltroEstado').change(function() {
        BuscarLotes();
    });
    $('#btnLiberar').click(function() {
        ConfirmacionLiberarLote();
    });

    $('#txtFechaLiberacion').on('change', function() {
        $("#txtFechaLiberacionHtml").hide();
    });
    $('#txtDescripcionLiberacion').keydown(function() {
        $("#txtDescripcionLiberacionHtml").hide();
    });
    $('#cbxMotivoLiberacion').on('change', function() {
        $("#cbxMotivoLiberacionHtml").hide();
    });


    $('#bxProyectoInventario').change(function () {
        $("#bxZonaInventario").val("");
        var url = '../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php';
        var datos = {
            "ListarZonas": true,
            "idProyecto": $('#bxProyectoInventario').val()
        }
        llenarCombo(url, datos, "bxZonaInventario");
    });

    $('#bxZonaInventario').change(function () {
        $("#bxManzanaInventario").val("");
        var url = '../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php';
        var datos = {
            "ListarManzanas": true,
            "idZona": $('#bxZonaInventario').val()
        }
        llenarCombo(url, datos, "bxManzanaInventario");
    });

     $('#bxManzanaInventario').change(function() {
       $("#bxLotesInventario").val("");
        var url = '../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php';
        var datos = {
            "ListarLotes": true,
            "idManzana": $('#bxManzanaInventario').val()
        }
        llenarCombo(url, datos, "bxLotesInventario");
        AccionManzanas();
    });


    
    $('#modalEditarZona').modal('show');
  /* bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionarZona": true,
        "IdRegistro": $("#__ID_MZ").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                 var resultado = dato.data;
                $("#txtidZonaPopupcc").val(resultado.id); 
                $("#txtNombreZonaPopup").val(resultado.nombre);          
                $("#txtNroManzanasPopup").val(resultado.nro_manzanas); 
                $("#txtAreaPopup").val(resultado.area); 

                $('#modalEditarZona').modal('show');                           

                return;
            } else {
                mensaje_alerta("¡Error!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });  */      
}

/***************************BUSCAR DETALLE MANZANA****************************** */
function BuscarDetalleManzana() {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnZonaDetalle": true,
        "idManzana": $("#__ID_MZ").val(),
    };
    realizarJsonPost(url, dato, respuestaBuscarDetalleManzana, null, 10000, null);
}
/*********************RESPUESTA LISTA DE MANZANAS POR ZONA*********************** */
function respuestaBuscarDetalleManzana(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        BuscarZonas(dato.data.idproyecto);
        $("#htmlNombreProyecto").html(dato.data.proyecto);
        $("#htmlNombreZona").html(dato.data.zona);
        $("#htmlNombreManzana").html(dato.data.nombre);

        $("#bxProyectoInventario").val(dato.data.idproy);
        LlenarListaZonas(dato.data.idproy, dato.data.idzona);
        LlenarListaManzanas(dato.data.idzona, dato.data.id);
        // asignar id Proyecto y zona
        id_proyecto=dato.data.idproy;
        id_zona=dato.data.idzona;
    }
}

function LlenarListaZonas(idProy, idZon) {
    var url = '../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php';
    var datos = {
        "ListarZonas": true,
        "idProyecto": idProy
    }
    llenarComboSelecionar(url, datos, "bxZonaInventario", idZon);
}

function LlenarListaManzanas(idZon, idMan) {
    var url = '../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php';
    var datos = {
        "ListarManzanas": true,
        "idZona": idZon
    }
    llenarComboSelecionar(url, datos, "bxManzanaInventario", idMan);
}


/***************************BUSCAR LISTA DE MANZANAS POR ZONA****************************** */
function BuscarLotes() {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnListaLote": true,
        "idManzana": $("#__ID_MZ").val(),
        "estado": $("#cbxFiltroEstado").val(),
		"val_user": $("#val_user").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarLotes, null, 10000, null);
}
/*********************RESPUESTA LISTA DE MANZANAS POR ZONA*********************** */
function respuestaBuscarLotes(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        PintarLote(dato.data);
    } else {
        PintarLote([]);
    }
}

let id_proyecto=0;
let id_zona=0;

function PintarLote(datos) {
    console.log(datos);
    // let idProyecto=$('#bxProyectoInventario').val();
    // let idZona= $('#bxZonaInventario').val();
    // let idProyecto=id_proyecto;
    // let idZona=id_zona;

    let idManzana=$("#__ID_MZ").val();

    $("#totalLote").html(datos.length);
    var html = "";
    if (datos.length == 0) {
        html += "<div class='col-md-12'>\
        <div class='alert alert-warning' role='alert'>\
        No se encontraron registro de <strong>LOTES</strong>!\
        </div>\
      </div>";
    }
    for (var i = 0; i < datos.length; i++) {
        var heading = "heading0" + i;
        var idCollapse = "collapse0" + i;
        var htmlBotones = "";
        if (parseInt(datos[i].idEstado) === 1) {
            htmlBotones += `<a href='${datos[i].urlReservacion}&Proyecto=${datos[i].idProy}&Zona=${datos[i].idZona}&Mz=${idManzana}&Lt=${datos[i].id}' class='btn btn-model-info m-1 text-white' >Reservar</a>`;
            htmlBotones += `<a href='${datos[i].urlVenta}&Proyecto=${datos[i].idProy}&Zona=${datos[i].idZona}&Mz=${idManzana}&Lt=${datos[i].id}' class='btn btn-model-info m-1 text-white' >Vender</a>`;
        }
        if (parseInt(datos[i].idEstado) === 2 || parseInt(datos[i].idEstado) === 3 || parseInt(datos[i].idEstado) === 4) {
            htmlBotones += '<button  class="btn btn-model-info m-1 text-white" onclick="LiberarLote(\'' + datos[i].idCliente + '\',\'' + datos[i].idLote + '\',\'' + datos[i].idReservacion + '\')">Liberar</button >';
            htmlBotones += `<a href='${datos[i].urlVenta}&Proyecto=${datos[i].idProy}&Zona=${datos[i].idZona}&Mz=${idManzana}&Lt=${datos[i].id}' class='btn btn-model-info m-1 text-white' >Vender</a>`;
        }
        if (parseInt(datos[i].idEstado) === 5 || parseInt(datos[i].idEstado) === 6) {
            if (parseInt(datos[i].idCondicion) === 2) {
                htmlBotones += '<button  class="btn btn-model-info m-1 text-white" onclick="VerCronogramaPago(\'' + datos[i].idVenta + '\')">Cronograma</button >';
            }
        }
        var htmlHistorial = datos[i].cantidadSuceso > 0 ? '<div class="m-icon w-100 title-03 text-danger" onclick="VerSucesosLote(\'' + datos[i].id + '\')"><i class="m-r-10 mdi mdi-information"></i> Ver Sucesos</div>' : "";

        var htmlDetalle = "";
        if ((parseInt(datos[i].idEstado) == 1) || (parseInt(datos[i].Bloqueado) == 7)) {
            var htmlDetalle = "<div class='col-md-12'>\
            <div class='card ' style='background: #fff;border: solid 1px " + datos[i].color + ";'>\
              <div class='card-body detalle-lote'>\
              <h6 class='title-02' >" + datos[i].estado + " - " + datos[i].area + " M² - " + datos[i].siglaMoneda + " </h5>\
                <h6 class='title-03'><strong>VALOR LOTE + CASA:</strong> " + datos[i].valorConCasa + "</h5>\
                <h6 class='title-03'><strong>VALOR LOTE SOLO:</strong> " + datos[i].valorSinCasa + "</h5>\
                <div class='d-flex justify-content-center'>\
                " + htmlBotones + "\
                </div>\
                " + htmlHistorial + "\
               </div>\
            </div>\
          </div>";
        } else if (((parseInt(datos[i].idEstado) == 2 || parseInt(datos[i].idEstado) == 3 || parseInt(datos[i].idEstado) == 4) && (parseInt(datos[i].Bloqueado) == 7)) || (parseInt(datos[i].idEstado) == 2 || parseInt(datos[i].idEstado) == 3 || parseInt(datos[i].idEstado) == 4)) {
            var cel2 = datos[i].celular2 != "" ? "/" + datos[i].celular2 : "";
            var htmlDetalle = "<div class='col-md-12'>\
            <div class='card ' style='background: #fff;border: solid 1px " + datos[i].color + ";'>\
              <div class='card-body detalle-lote'>\
              <h6 class='title-02'><strong>" + datos[i].estado + " - " + datos[i].area + " M² - " + datos[i].siglaMoneda + "</strong></h6>\
              <h6 class='title-02 mb-0'>CLIENTE:</h6>\
              <h6 class='title-03'>" + datos[i].cliente + "</h6>\
              <h6 class='title-03'><strong>CONTACTO:</strong> " + datos[i].celular1 + cel2 + "</h6>\
                <h6 class='title-03'><strong>V. LOTE - CASA:</strong> " + datos[i].valorConCasa + "</h6>\
                <h6 class='title-03'><strong>V. LOTE - SOLO:</strong> " + datos[i].valorSinCasa + "</h6>\
                <h6 class='title-03'><strong>MONTO RESERVADO:</strong> " + datos[i].montoReserva + "</h6>\
                <h6 class='title-03'><strong>INI:</strong> " + datos[i].inicioReserva + "</h6>\
                <h6 class='title-03'><strong>FIN:</strong> " + datos[i].finReserva + "</h6>\
                <h6 class='title-02 mb-0'>VENDEDOR:</h6>\
                <h6 class='title-03 '>" + datos[i].vendedor + "</h6>\
                <div class='d-flex justify-content-center'>\
                " + htmlBotones + "\
                </div>\
                " + htmlHistorial + "\
               </div>\
            </div>\
          </div>";
        } else if (parseInt(datos[i].idEstado) == 5 || parseInt(datos[i].idEstado) == 6) {
            var cel2 = datos[i].celular2Comprador != "" ? "/" + datos[i].celular2Comprador : "";
            var htmlDetalle = "<div class='col-md-12'>\
            <div class='card ' style='background: #fff;border: solid 1px " + datos[i].color + ";'>\
              <div class='card-body detalle-lote'>\
              <h6 class='title-02'><strong>" + datos[i].estado + " - " + datos[i].area + " M² - " + datos[i].siglaMoneda + "</strong></h6>\
              <h6 class='title-02 mb-0'>CLIENTE:</h6>\
              <h6 class='title-03'>" + datos[i].clienteComprador + "</h6>\
              <h6 class='title-03'><strong>CONTACTO:</strong> " + datos[i].celular1Comprador + cel2 + "</h6>\
                <h6 class='title-03'><strong>TIPO CASA:</strong> " + datos[i].tipoCasa + "</h6>\
                <h6 class='title-03'><strong>MONTO COMPRADO:</strong> " + datos[i].montTotalVenta + "</h6>\
                <h6 class='title-03'><strong>CONDICIÓN:</strong> " + datos[i].condicion + "</h6>\
                <h6 class='title-03'><strong>FECHA VENTA:</strong> " + datos[i].fechaVenta + "</h6>\
                <h6 class='title-02 mb-0'>VENDEDOR:</h6>\
                <h6 class='title-03 '>" + datos[i].vendedorFinalizado + "</h6>\
                <div class='d-flex justify-content-center'>\
                " + htmlBotones + "\
                </div>\
                " + htmlHistorial + "\
               </div>\
            </div>\
          </div>";
        } else if (parseInt(datos[i].Motivo) == 8 ) {
            var cel2 = datos[i].celular2Comprador != "" ? "/" + datos[i].celular2Comprador : "";
            var htmlDetalle = "<div class='col-md-12'>\
            <div class='card ' style='background: #fff;border: solid 1px " + datos[i].color + ";'>\
              <div class='card-body detalle-lote'>\
              <h6 class='title-02'><strong>" + datos[i].estado + " - " + datos[i].area + " M² - " + datos[i].siglaMoneda + "</strong></h6>\
              <h6 class='title-02 mb-0'>CLIENTE:</h6>\
              <h6 class='title-03'>" + datos[i].clienteComprador + "</h6>\
              <h6 class='title-03'><strong>CONTACTO:</strong> " + datos[i].celular1Comprador + cel2 + "</h6>\
                <h6 class='title-03'><strong>TIPO CASA:</strong> " + datos[i].tipoCasa + "</h6>\
                <h6 class='title-03'><strong>MONTO COMPRADO:</strong> " + datos[i].montTotalVenta + "</h6>\
                <h6 class='title-03'><strong>CONDICIÓN:</strong> " + datos[i].condicion + "</h6>\
                <h6 class='title-03'><strong>FECHA VENTA:</strong> " + datos[i].fechaVenta + "</h6>\
                <h6 class='title-02 mb-0'>VENDEDOR:</h6>\
                <h6 class='title-03 '>" + datos[i].vendedorFinalizado + "</h6>\
                <div class='d-flex justify-content-center'>\
                " + htmlBotones + "\
                </div>\
                " + htmlHistorial + "\
               </div>\
            </div>\
          </div>";
        }




        html += "<div class='col-md-3'>\
					<div class='card mb-1' style='box-shadow: 2px 2px 5px;'>\
						<div class='card-header  expandable collapsed' id='" + heading + "' data-toggle='collapse' data-target='#" + idCollapse + "' aria-expanded='false' aria-controls='" + idCollapse + "'  style='background:" + datos[i].color + ";'>\
							<h5 class='mb-0 text-black title-02'>" + datos[i].nombre + "\
								<i class='rotate fa fa-chevron-down'></i>\
							</h5>\
						</div>\
						<div id='" + idCollapse + "' class='collapse' aria-labelledby='" + heading + "' data-parent='#accordion'>\
							<div class='card-body' style='padding:0px 0px;'>\
								<div class='row' >" + htmlDetalle + "</div>\
							</div>\
						</div>\
					</div>\
				</div>";

    }
    $("#accordion").html(html);
}
 

/***************************BUSCAR LISTA DE ZONAS POR PROYECTO****************************** */
function BuscarZonas(idproyecto) {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnListaZona": true,
        "idProyecto": idproyecto,
    };
    realizarJsonPost(url, dato, respuestaBuscarZonas, null, 10000, null);
}
/*********************RESPUESTA LISTA DE ZONAS POR PROYECTO*********************** */
function respuestaBuscarZonas(dato) {
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
        var heading = "heading00" + datos[i].id;
        var idCollapse = "collapse00" + datos[i].id;

        var htmlManzanas = "";

        for (var j = 0; j < datos[i].manzanas.length; j++) {
            var idBoton = "btn00" + datos[i].manzanas[j].id;
            htmlManzanas += "<a href='" + datos[i].manzanas[j].url + "' class='btn btn-outline-dark w-100 mb-2 title-02' id='" + idBoton + "'>" + datos[i].manzanas[j].nombre + "</a>";
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
    $("#accordionZona").html(html);
    ActivarOpcionesZona();
}

function ActivarOpcionesZona() {
    $("#" + $("#__ID_HEARD_CARD").val()).removeClass("collapsed");
    $("#" + $("#__ID_BODY_CARD").val()).addClass("show");
    $("#" + $("#__ID_BOTON").val()).addClass("active-zona");
}


//NUEVA ACCION DE BUSQUEDA DE LOTES
function AccionManzanas() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "ReturnListaZona": true,
        "idProyecto": $("#bxProyectoInventario").val(),
        "idManzana": $("#bxManzanaInventario").val(),
        "idZona": $("#bxZonaInventario").val(),
		"val_user": $("#val_user").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                
                var url = dato.url;
                var id = dato.id;

                var idproyecto = dato.idproyecto;
                var idzona = dato.idzona;
                var idmanzana = dato.idmanzana;

                NuevoBuscarLotes(id, url, idproyecto, idzona, idmanzana);

            } else {
                mensaje_alerta("\u00A1ATENCI\u00D3N!", "No se encontraron lotes para la manzana seleccionada.", "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}


function NuevoBuscarLotes(id, url, idproyecto, idzona, idmanzana){

    var heading = "heading00" + id;
    var idCollapse = "collapse00" + id;
    var htmlManzanas = "";
    var idBoton = "btn00" + id;

    window.location.href = url;   
       
}


/********************ABRIR MODAL LIBERAR**************** */
function LiberarLote(idCliente, idLote, idReservacion) {
    BuscarDatoCliente(idCliente, idLote, idReservacion);
}

/***************************BUSCAR DATO CLIENTE****************************** */
function BuscarDatoCliente(idCliente, idLote, idReservacion) {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnDataCliente": true,
        "idCliente": idCliente,
        "idLote": idLote,
        "idReservacion": idReservacion
    };
    realizarJsonPost(url, dato, respuestaBuscarDatoCliente, null, 10000, null);
}
/*********************RESPUESTA BUSCAR DATO CLIENTE*********************** */
function LimpiarCamposLiberar() {
    $("#__ID_CLIENTE,#txtDocumento_cliente,#apePaterno_cliente,#apeMaterno_cliente,#nombres_cliente,#__ID_LOTE,#__ID_RESERAVACION,#txtDescripcionLiberacion").val("");
    $("#txtFechaLiberacionHtml").hide();
    $("#txtDescripcionLiberacionHtml").hide();
    $("#cbxMotivoLiberacionHtml").hide();
}

function respuestaBuscarDatoCliente(dato) {
    LimpiarCamposLiberar();
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#txtDocumento_cliente").val(dato.data.documento);
        $("#apePaterno_cliente").val(dato.data.apellidoPaterno);
        $("#apeMaterno_cliente").val(dato.data.apellidoMaterno);
        $("#nombres_cliente").val(dato.data.nombres);
        $("#__ID_CLIENTE").val(dato.data.id);
        $("#__ID_LOTE").val(dato.idLote);
        $("#__ID_RESERAVACION").val(dato.idReservacion);
        $('#modalLiberar').modal('show');
    }
}

function ValidarLiberarRequeridos() {
    var flat = true;
    if ($("#txtFechaLiberacion").val().trim() === "") {
        $("#txtFechaLiberacion").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la Fecha", "info");
        $("#txtFechaLiberacionHtml").html('<i class="fas fa-exclamation-circle"></i> Ingrese la  <strong> Fecha</strong>');
        $("#txtFechaLiberacionHtml").show();
        flat = false;
    } else if ($("#cbxMotivoLiberacion").val() === "" || $("#cbxMotivoLiberacion").val() == null) {
        $("#cbxMotivoLiberacion").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione un Motivo", "info");
        $("#cbxMotivoLiberacionHtml").html('<i class="fas fa-exclamation-circle"></i> Seleccione el  <strong> Motivo</strong>');
        $("#cbxMotivoLiberacionHtml").show();
        flat = false;
    } else if ($("#txtDescripcionLiberacion").val().trim() === "") {
        $("#txtDescripcionLiberacion").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la Decripción.", "info");
        $("#txtDescripcionLiberacionHtml").html('<i class="fas fa-exclamation-circle"></i> Ingrese la <strong> Descripción</strong>');
        $("#txtDescripcionLiberacionHtml").show();
        flat = false;
    }

    return flat;
}

/*************************DAR DE BAJA AL REGISTRO PERSONAL******************************* */

function ConfirmacionLiberarLote() {
    if (ValidarLiberarRequeridos()) {
        mensaje_condicional_SinParametros("¿Est\u00E1 seguro Liberar el Lote?", "Al confirmar se proceder\u00E1 a cambiar de estado", SiLiberar, CancelAction);
    }
}

function SiLiberar() {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnLiberarLote": true,
        "fecha": $("#txtFechaLiberacion").val(),
        "descricion": $("#txtDescripcionLiberacion").val(),
        "idCliente": $("#__ID_CLIENTE").val(),
        "idLote": $("#__ID_LOTE").val(),
        "idReservacion": $("#__ID_RESERAVACION").val(),
        "idMotivo": $("#cbxMotivoLiberacion").val()
    };
    realizarJsonPost(url, dato, respuestaSiLiberar, null, 10000, null);
}

function respuestaSiLiberar(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        $('#modalLiberar').modal('hide');
        BuscarLotes();
        setTimeout(function() {
            mensaje_alerta("\u00A1Liberado!", dato.data, "success");
        }, 100);
        return;
    } else {
        setTimeout(function() {
            mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
        }, 100);
    }
}

function CancelAction() {
    return;
}

function VerSucesosLote(idLote) {
    BuscarSucesoLote(idLote);
}

function BuscarSucesoLote(id) {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnSucesoLote": true,
        "idLote": id
    };
    realizarJsonPost(url, dato, respuestaBuscarSucesoLote, null, 10000, null);
}

function respuestaBuscarSucesoLote(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        LlenarTbalaSucesos(dato.data);
        $('#modalSucesos').modal('show');
    } else {}
}

var tablasucesos = null;

function LlenarTbalaSucesos(data) {
    if (tablasucesos) {
        tablasucesos.destroy();
        tablasucesos = null;
    }
    var html = "";
    for (i = 0; i < data.length; i++) {

        html += '<tr >' +
            "<td>" + data[i].estadoAsignado + "</td>" +
            "<td>" + data[i].fecha + "</td>" +
            " <td>" + data[i].clienteRelacionado + "</td> " +
            " <td>" + data[i].motivo + "</td> " +
            "</tr>";
    }
    $("#dataSucesoTable tbody").html(html);
    tablasucesos = $('#dataSucesoTable').DataTable({
        "bFilter": false,
        "lengthChange": false,
        "info": false,
        "bSort": false,
        "paging": false
    });
}

function BuscarListaEstadoLeyenda() {
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnListaEstados": true
    };
    realizarJsonPost(url, dato, respuestaBuscarListaEstadoLeyenda, null, 10000, null);
}

function respuestaBuscarListaEstadoLeyenda(dato) {
    if (dato.status == "ok") {
        pintarLeyenda(dato.data);
    } else {
        pintarLeyenda([]);
    }
}

function pintarLeyenda(datos) {
    var html = "";
    for (var i = 0; i < datos.length; i++) {
        html += ' <div class="col">\
        <h1 class="h6 mb-0 lh-1 text-left title-04" > <span class="leyenda-estado" style="background: ' + datos[i].color + ';"></span> ' + datos[i].descripcion + '</h1>\
    </div>';

    }
    $("#LeyedaEstados").html(html);
}




function VerCronogramaPago(id) {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01SD05_Inventario/M01SM05_Inventario_Procesos.php";
    var dato = {
        "ReturnCronogramaPago": true,
        "idVenta": id
    };
    realizarJsonPost(url, dato, respuestaVerCronogramaPago, null, 10000, null);
}

function respuestaVerCronogramaPago(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        LlenarTablaCronograma(dato.data);
        $('#modalCronograma').modal('show');
    } else {}
}

var tablaCronograma = null;

function LlenarTablaCronograma(data) {
    if (tablaCronograma) {
        tablaCronograma.destroy();
        tablaCronograma = null;
    }
    var html = "";
    for (i = 0; i < data.length; i++) {

        html += '<tr >' +
            "<td>" + data[i].numeroCuota + "</td>" +
            "<td>" + data[i].montoPagar + "</td>" +
            " <td>" + data[i].fechaVencimiento + "</td> " +
            " <td>" + data[i].capitalAmortizado + "</td> " +
            " <td>" + data[i].interesAmortizado + "</td> " +
            " <td>" + data[i].capitalVivo + "</td> " +
            "</tr>";
    }
    $("#dataCronogramaTable tbody").html(html);
    tablaCronograma = $('#dataCronogramaTable').DataTable({
        "bFilter": false,
        "lengthChange": false,
        "info": false,
        "bSort": false,
        "paging": false
    });
}