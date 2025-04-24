var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
    LlenarProyectos();
	LLenarZonas();
    ValidarFechas();
    LlenarTablaPagosComprobanteReporte();
    ValidarFechasR();
    LlenarTablaPagosComprobanteReporteReserva();
  
    $('#btnBuscarRegistroCV').click(function() {
        CargarPagosComprobante();
        $('#TablaPagoComprobanteReporte').DataTable().ajax.reload();   
    });
  
    $('#btnLimpiarCV').click(function() {
        $('#txtFiltroDocumentoCV').val(null).trigger('change');
        $("#txtFiltroDesdeCV").val("");
        $("#txtFiltroHastaCV").val("");
        document.getElementById('cbxFiltroBancoCV').selectedIndex = 0;
        document.getElementById('cbxFiltroProyectoPC').selectedIndex = 0;
        document.getElementById('cbxFiltroEstadoPC').selectedIndex = 0;
        document.getElementById('bxFiltroZonaPC').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaPC').selectedIndex = 0;
        document.getElementById('bxFiltroLotePC').selectedIndex = 0;
        document.getElementById('cbxFiltroEstadoValPC').selectedIndex = 0;
        ValidarFechas();
        $('#TablaPagoComprobanteReporte').DataTable().ajax.reload();  
    }); 
  
    $('#btnGuardarPagoCV').click(function() {
        GuardarComprobante();
    });
    
    $('#btnNuevoPagoCV').click(function() {
        NuevoDetalleComprobante();
    });
	
	/*$('#btnInsertar').click(function() {
        ElimPagoCab();
		
    });*/
    
    $('#bxFiltroProyectoPC').change(function () {
        $("#bxFiltroZonaPC").val("");
        $("#bxFiltroManzanaPC").val("");
        var url = '../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php';
        var datos = {
            "ListarZonas": true,
            "idproyecto": $('#bxFiltroProyectoPC').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaPC");
        document.getElementById('bxFiltroManzanaPC').selectedIndex = 0;
    });

    $('#bxFiltroZonaPC').change(function () {
        $("#bxFiltroManzanaPC").val("");
        $("#bxFiltroLotePC").val("");
        var url = '../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaPC').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaPC");
        document.getElementById('bxFiltroLotePC').selectedIndex = 0;
    });

   $('#bxFiltroManzanaPC').change(function () {
        $("#bxFiltroLotePC").val("");
        var url = '../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaPC').val()
        };
        llenarCombo(url, datos, "bxFiltroLotePC");
    });
    
     /*  RESERVAS */
    
    $('#btnBuscarRegistroCVR').click(function() {
        CargarPagosComprobanteReserva();
        $('#TablaPagoComprobanteReporteR').DataTable().ajax.reload();   
    });
  
    $('#btnLimpiarCVR').click(function() {
        $('#txtFiltroDocumentoCVR').val(null).trigger('change');
        $("#txtFiltroDesdeCVR").val("");
        $("#txtFiltroHastaCVR").val("");
        document.getElementById('cbxFiltroBancoCVR').selectedIndex = 0;
        document.getElementById('cbxFiltroEstadoPCR').selectedIndex = 0;
        ValidarFechasR();
        $('#TablaPagoComprobanteReporteR').DataTable().ajax.reload();  
    }); 
    

}

function VerTipoCambio() {  
    var data = {
      "btnMostrarTipoCambio": true,
      "__ID_USER": $('#__ID_USER').val()
    };
    $.ajax({
      type: "POST",
      url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            $('#txtTipoCambio').val(dato.tipoCambio);
        }else{
            mensaje_alerta("\u00A1IMPORTANTE!", "Ingresar tipo de cambio del d\u00EDa.", "info");
            setTimeout(function() {
               window.location.href = ""+ dato.url +"";
            }, 1500);
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
 }

function LlenarProyectos() {
    var url = '../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoPC");    
}

function LLenarZonas() {
    var url = '../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoPC').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaPC");
}


//AGREGAR FECHA INICIO Y TERMINO DEL MES ACTUAL
function ValidarFechas(){
    var data = {
       "btnValidarFechas": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               $("#txtFiltroDesdeCV").val(dato.primero);
               $("#txtFiltroHastaCV").val(dato.ultimo);   
           } 
           CargarPagosComprobante();
           $('#TablaPagoComprobanteReporte').DataTable().ajax.reload();  

       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

//LLENAR TABLA CONTENEDORA
function CargarPagosComprobante() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php";
    var dato = {
        "btnListarTablaPagosComprobante": true,
		"txtFiltroDocumentoCV": $("#txtFiltroDocumentoCV").val(),
		"txtFiltroDesdeCV": $("#txtFiltroDesdeCV").val(),
		"txtFiltroHastaCV": $("#txtFiltroHastaCV").val(),
		"cbxFiltroBancoCV": $("#cbxFiltroBancoCV").val(),
		"cbxFiltroProyectoPC": $("#cbxFiltroProyectoPC").val(),
		"cbxFiltroEstadoPC": $("#cbxFiltroEstadoPC").val(),
        "bxFiltroZonaPC": $("#bxFiltroZonaPC").val(),
        "bxFiltroManzanaPC": $("#bxFiltroManzanaPC").val(),
        "bxFiltroLotePC": $("#bxFiltroLotePC").val(),
        "cbxFiltroEstadoValPC": $("#cbxFiltroEstadoValPC").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosComprobante, null, 10000, null);
}

function respuestaBuscarPagosComprobante(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaPagosComprobante(dato.data);
}

var getTablaPagosComprobante = null;
function LlenarTablaPagosComprobante(datos) {
    if (getTablaPagosComprobante) {
        getTablaPagosComprobante.destroy();
        getTablaPagosComprobante = null;
    }

    getTablaPagosComprobante = $('#TablaPagoComprobante').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";
                    if(row.estado_cierre!="FINALIZADO"){
                        html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="CargaComprobante(\'' + data + '\')" title="Cargar Comprobante"><i class="fas fa-paperclip"></i></a> \ <a href="javascript:void(0)" class="btn btn-edit-action" onclick="VerVoucher(\'' + row.id + '\')" title="Voucher de Pago"><i class="fas fa-file"></i></a> \ <a href="javascript:void(0)" class="btn btn-edit-action" onclick="VerComprobantesSunat(\'' + data + '\')" title="Ver Comprobante(s)"><i class="fas fa-folder-open"></i></a> \ <a href="javascript:void(0)" class="btn btn-success-action" onclick="FinalizarCarga(\'' + data + '\')" title="Finalizar"><i class="fas fa-check"></i></a> \ <a href="javascript:void(0)" class="btn btn-warning-action" onclick="ObservacionesPago(\'' + data + '\')" title="Observar Pago"><i class="fas fa-exclamation"></i></a>';
                    }else{                       
                        html = '<a href="javascript:void(0)" class="btn btn-warning-action" onclick="RestablecerCarga(\'' + data + '\')" title="Restablecer carga"><i class="fas fa-redo"></i></a> \ <a href="javascript:void(0)" class="btn btn-edit-action" onclick="VerVoucher(\'' + row.id + '\')" title="Voucher de Pago"><i class="fas fa-file"></i></a> \ <a href="javascript:void(0)" class="btn btn-edit-action" onclick="VerComprobantesSunat(\'' + data + '\')" title="Ver Comprobante(s)"><i class="fas fa-folder-open"></i></a>';   
                    }
                    return html;
                }
            },
            {
                "data": "estado_cierre",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color: '+ row.estado_cierre_color +';">' + row.estado_cierre + '</span>';
                    return html;
                } 
            },
            { 
                "data": "nro_comprobantes",
                "render": function(data, type, row, host) {
                    var html="";
                    html = '<label>'+ row.nro_comprobantes +'</label>';
                    return html;
                }
            },
            { "data": "fecha_pago" },
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fecha_vencimiento" },
            { "data": "letra" },
            { "data": "mora" },
			{ "data": "tipo_moneda" },
			{ "data": "importe" },
			{ "data": "tipo_cambio" },
			{ "data": "pagado" },
			{
                "data": "estado_pago",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color: '+ row.estado_pago_color +';">' + row.estado_pago + '</span>';
                    return html;
                } 
            },
            { "data": "banco" },
            { "data": "medio_pago" }, 
            { "data": "tipo_comprobante" },
            { "data": "nro_operacion" }
            
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

function VerVoucher(id) {  
    var data = {
      btnMostrarVoucher: true,
      idRegistro: id,
    };
    $.ajax({
      type: "POST",
      url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            console.log(dato);
            if(dato.formato == "jpeg" || dato.formato == "jpg"){
                var html = "";
                var documento = "../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+dato.voucher+"";
                html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                $("#my_img_doc").html(html);
                $("#modalVerVoucher").modal("show");   
            }else{
                if(dato.formato == "png"){
                    var html = "";
                    var documento = "../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+dato.voucher+"";
                    html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                    $("#my_img_doc").html(html);
                    $("#modalVerVoucher").modal("show");   
                }else{
                    var html = "";
                    var documento = "../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+dato.voucher+"";
                    html += "<object class='pdfview' type='application/pdf' data='" +documento +"' style='width: 100%'></object> ";
                    $("#my_img_doc").html(html);
                    $("#modalVerVoucher").modal("show");        
                }
            }
          
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
 }
 
function ObservacionesPago(id) {  
    var data = {
      btnVerObservaciones: true,
      "idRegistro": id
    };
    $.ajax({
      type: "POST",
      url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            var resultado = dato.data;
            if(resultado.estado == '0'){
                $("#btnGuardarObservacion").show();
                $("#btnConforme").hide();
                $("#txtObservacion").prop('disabled', false);
            }else{
                if(resultado.estado == '1'){
                    $("#btnGuardarObservacion").hide();
                    $("#btnConforme").show();
                    $("#txtObservacion").prop('disabled', true);
                }else{
                    $("#btnGuardarObservacion").hide();
                    $("#btnConforme").hide();
                }
            }
            $("#_ID_PAGO_DETALLE").val(resultado.id);
            $("#txtObservacion").val(resultado.observacion);
            $("#txtRespuesta").val(resultado.respuesta);
            
            $("#modalObservaciones").modal("show"); 
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
 }

function ConformidadPago(){
    var data = {
        "btnConformidadPago": true,
        "_ID_PAGO_DETALLE": $("#_ID_PAGO_DETALLE").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
                    CargarPagosComprobante();  
                    $("#modalObservaciones").modal("hide");                
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
    
                } else {
                    mensaje_alerta("\u00A1ERROR!", dato.data, "info");
                }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });   

}

function ObservarPago(){
    var data = {
        "btnObservarPago": true,
        "_ID_PAGO_DETALLE": $("#_ID_PAGO_DETALLE").val(),
        "txtObservacion": $("#txtObservacion").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
                    CargarPagosComprobante();
                    $("#modalObservaciones").modal("hide");                   
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
    
                } else {
                    mensaje_alerta("\u00A1ERROR!", dato.data, "info");
                }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });   

}
 
function VerComprobantesSunat(id){
    $("#__IDPAGO_DET").val(id);
    $("#PanelBotonRegComprobante").hide();
    $("#PanelCamposRegComprobante").hide();
    CargarDetalleComprobante();
    $('#modalCargaComprobante').modal('show');

}
 
 
 
 
function VerComprobante(comprobante) {  
    
    var html2 = "";
    var documento2 = "archivos/"+comprobante+"";
    html2 += "<object class='pdfview' type='application/pdf' data='" +documento2 +"' style='width: 100%'></object> ";
    $("#my_img_doc_com").html(html2);
    $("#modalVerComprobante").modal("show");        
         
 }

var tablaPagosReporte = null;
function LlenarTablaPagosComprobanteReporte() {
    var options = $.extend(true, {}, defaults, {
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
        "iDisplayLength": 5,
        "aLengthMenu": [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ],
        "sDom": 'Bfrtilp',
        "tableTools": {
            "aButtons": []
        },
        "bFilter": false,
        "paging": false,
        "info": false,
        "bSort": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 20, 50, 100, 150],
            [10, 20, 50, 100, 150] // change per page values here
        ],
        "pageLength": 1000000000, // default record count per page,
        "ajax": {
            "url": "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "btnListarTablaPagosComprobante": true,
                    "txtFiltroDocumentoCV": $("#txtFiltroDocumentoCV").val(),
                    "txtFiltroDesdeCV": $("#txtFiltroDesdeCV").val(),
                    "txtFiltroHastaCV": $("#txtFiltroHastaCV").val(),
                    "cbxFiltroBancoCV": $("#cbxFiltroBancoCV").val(),
                    "cbxFiltroProyectoPC": $("#cbxFiltroProyectoPC").val(),
                    "cbxFiltroEstadoPC": $("#cbxFiltroEstadoPC").val(),
                    "bxFiltroZonaPC": $("#bxFiltroZonaPC").val(),
                    "bxFiltroManzanaPC": $("#bxFiltroManzanaPC").val(),
                    "bxFiltroLotePC": $("#bxFiltroLotePC").val(),
                    "cbxFiltroEstadoValPC": $("#cbxFiltroEstadoValPC").val()
                });
            }
        },
        "columns": [
        { "data": "estado_cierre" },    
        { "data": "fecha_pago" },
        { "data": "cliente" },
        { "data": "lote" },
        { "data": "fecha_vencimiento" },
        { "data": "letra" },
        { "data": "mora" },
        { "data": "tipo_moneda" },
        { "data": "importe" },
        { "data": "tipo_cambio" },
        { "data": "pagado" },
        { "data": "estado_pago" }, 
        { "data": "banco" },
        { "data": "medio_pago" },
        { "data": "tipo_comprobante" },
        { "data": "nro_operacion" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        responsive: "true",

        buttons: [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> ',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success'
            },
           /* {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> ',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger'
            },*/
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> ',
                titleAttr: 'Imprimir',
                className: 'btn btn-info'
            }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }

    });

    tablaPagosReporte = $('#TablaPagoComprobanteReporte').DataTable(options);
}

function CargaComprobante(id){
    var data = {
        "btnEditarComprobante": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                $("#__IDPAGO_DET").val(dato.data.id);
                /*$("#txtFechaEmisionCV").val(dato.data.fecha_emision);
                $("#cbxTipoComprobanteCV").val(dato.data.tipoComprobante);
                $("#txtSerieCV").val(dato.data.serie);
                $("#txtNumeroCV").val(dato.data.numero);*/
                $("#PanelBotonRegComprobante").show();
                $("#PanelCamposRegComprobante").show();
                CargarDetalleComprobante();
                $('#modalCargaComprobante').modal('show');
                
                VerTipoCambio();
 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}

function ValidarCamposComprobante() {
    var flat = true;
  
        if ($("#txtFechaEmisionCV").val() === "" || $("#txtFechaEmisionCV").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar la fecha de emision del comprobante.", "info");
            flat = false;
        } else if ($("#cbxTipoComprobanteCV").val() === "" || $("#cbxTipoComprobanteCV").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de comprobante sunat.", "info");
            flat = false;
        } else if ($("#txtSerieCV").val() === "" || $("#txtSerieCV").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la serie del comprobante.", "info");
            flat = false;
        }else if ($("#txtNumeroCV").val() === "" || $("#txtNumeroCV").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el numero del comprobante.", "info");
            flat = false;
        }else if ($("#cbxTipoDoc").val() === "" || $("#cbxTipoDoc").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de documento del cliente.", "info");
            flat = false;
        }else if ($("#txtNroDocumento").val() === "" || $("#txtNroDocumento").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el numero de documento del cliente.", "info");
            flat = false;
        }else if ($("#txtDatosCliente").val() === "" || $("#txtDatosCliente").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar los apellidos y nombres del cliente.", "info");
            flat = false;
        }else if ($("#txtTipoMoneda").val() === "" || $("#txtTipoMoneda").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de moneda del comprobante.", "info");
            flat = false;
        }else if ($("#txtTotalPagado").val() === "" || $("#txtTotalPagado").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el monto total emitido en el comprobante.", "info");
            flat = false;
        }else if ($("#txtFechaVencimiento").val() === "" || $("#txtFechaVencimiento").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la fecha de vencimiento.", "info");
            flat = false;
        }else if ($("#cbxConceptos").val() === "" || $("#cbxConceptos").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el concepto del comprobante.", "info");
            flat = false;
        }else if ($("#ComprobanteCV").val() === "" || $("#ComprobanteCV").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el comprobante emitido. (Formato PDF)", "info");
            flat = false;
        }
    
    return flat;
}

function GuardarComprobante(){
    if(ValidarCamposComprobante()){
        var data = {
            "btnGuardarComprobante": true,
            "__IDPAGO_DET": $("#__IDPAGO_DET").val(),
            "__IDPAGO_DET_COMPROBANTE": $("#__IDPAGO_DET_COMPROBANTE").val(),
            "txtFechaEmisionCV": $("#txtFechaEmisionCV").val(),
            "cbxTipoComprobanteCV": $("#cbxTipoComprobanteCV").val(),
            "txtSerieCV": $("#txtSerieCV").val(),
            "txtNumeroCV": $("#txtNumeroCV").val(),
            "cbxTipoDoc": $("#cbxTipoDoc").val(),
            "txtNroDocumento": $("#txtNroDocumento").val(),
            "txtDatosCliente": $("#txtDatosCliente").val(),
            "txtTipoMoneda": $("#txtTipoMoneda").val(),
            "txtTotalPagado": $("#txtTotalPagado").val(),
            "txtFechaVencimiento": $("#txtFechaVencimiento").val(),
            "cbxConceptos": $("#cbxConceptos").val(),
            "ComprobanteCV": $("#ComprobanteCV").val(),
            "txtTipoCambio": $("#txtTipoCambio").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                    //console.log(dato);
                    if (dato.status == "ok") {
                        if (dato.operacion == "registra") {
                            var dat = $("#ComprobanteCV").val();
                            if(dat!=""){
                                var nombre = dato.name;
                                EnviarAdjuntoPago(nombre);                        
                            }
                            CargarDetalleComprobante();
                            /*CargarPagosComprobante();
                            $('#TablaPagoComprobanteReporte').DataTable().ajax.reload(); */
                            mensaje_alerta("\u00A1CORRECTO!", "Se registr\u00F3 el comprobante ingresado.", "success"); 
                        }else{
                            var dat = $("#ComprobanteCV").val();
                            if(dat!=""){
                                var nombre = dato.name;
                                EnviarAdjuntoPago(nombre);                        
                            }
                            CargarDetalleComprobante();
                            /*CargarPagosComprobante();
                            $('#TablaPagoComprobanteReporte').DataTable().ajax.reload(); */
                            mensaje_alerta("\u00A1CORRECTO!", "Se actualizaron los cambios realizados.", "success");
                        }
        
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

}

function EnviarAdjuntoPago(nombre){

    var file_data = $('#ComprobanteCV').prop('files')[0];   
     var form_data = new FormData();
     var dataa = nombre;                  
     form_data.append('file', file_data);
     form_data.append('data', dataa);
     //alert(form_data);                             
     $.ajax({
         url: '../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_SubirComprobante.php', // point to server-side PHP script 
         dataType: 'text',  // what to expect back from the PHP script, if anything
         cache: false,
         contentType: false,
         processData: false,
         data: form_data,                         
         type: 'post',
         success: function(php_script_response){
             //alert(php_script_response); // display response from the PHP script, if any
            // mensaje_alerta("Correcto!", "El adjunto fue cargado correctamente", "success"); 
         }
      });
 
}

function FinalizarCarga(id){
    var data = {
        "btnCierreComprobante": true,
        "_ID_PAGO_CV": id,
        "__ID_USER": $("#__ID_USER").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
                if (dato.status == "ok") {
                    if(parseInt(dato.variable) == 1){
                        
                        //VALIDACION DE REGISTRO DE INGRESOS Y VENTAS PARA CARGA MANUAL
                        if(dato.ingresos!='ok' && dato.ventas!='ok'){
    					    InsertarPagsCab(dato.iddato); 
                        }
                        
						CargarPagosComprobante();
						CargarPagosComprobanteReserva();
                        mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
                        
                    }else{
                        mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
                    }
    
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

function InsertarPagsCab(id){

	var data = {
		"btnProcesarIngEg": true,
		"id": id
	};  
	$.ajax({
		type: "POST",
		url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
		data: data,
		dataType: "json",
		success: function (dato) { 
			console.log(dato);
		},
		error: function (jqXHR, textStatus, errorThrown) {
		
			console.log(textStatus + ': ' + errorThrown);
			desbloquearPantalla();
		},
		timeout: timeoutDefecto
	});
	
	
}


function RestablecerCarga(id){
    var data = {
        "btnRestablecerComprobante": true,
        "_ID_PAGO_CV": id,
        "__ID_USER": $("#__ID_USER").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
					var dato = data;
					ElimPagoCab(dato._ID_PAGO_CV);
					CargarPagosComprobante();
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
    
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

function ElimPagoCab(id){
	var data = {
		"btnElimPago": true,
		"id": id
	};
	$.ajax({
		type: "POST",
		url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
		data: data,
		dataType: "json",
		success: function (dato) {
			console.log(dato);
		},
		error: function (jqXHR, textStatus, errorThrown) {
		
			console.log(textStatus + ': ' + errorThrown);
			desbloquearPantalla();
		},
		timeout: timeoutDefecto
	});
}



/* ================ POPUP COMPROBANTES =====================*/

//LLENAR TABLA CONTENEDORA COMPROBANTES
function CargarDetalleComprobante() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php";
    var dato = {
        "btnListarTablaDetalleComprobante": true,
		"__IDPAGO_DET": $("#__IDPAGO_DET").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarDetalleComprobante, null, 10000, null);
}

function respuestaBuscarDetalleComprobante(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaDetalleComprobante(dato.data);
}

var getTablaDetalleComprobante = null;
function LlenarTablaDetalleComprobante(datos) {
    if (getTablaDetalleComprobante) {
        getTablaDetalleComprobante.destroy();
        getTablaDetalleComprobante = null;
    }

    getTablaDetalleComprobante = $('#TablaDetalleComprobantes').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";
                    html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarDetalleComprobante(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a> \ <a href="javascript:void(0)" class="btn btn-delete-action" onclick="SolicitarEliminar(\'' + data + '\',\'' + row.serie + '\',\'' + row.numero + '\')" title="Cargar Comprobante"><i class="fas fa-trash"></i></a>';
                    return html;
                }
            },
            { "data": "serie" },
            { "data": "numero" },   
            { 
                "data": "adjunto",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.adjunto=="1"){
                        html = '<a class="badge" href="'+row.tipcomp_2+'" target="_blank" style="font-size: 14px; color red; text-align: center; font-weight: bold;"><i class="fas fa-file-pdf"></i> Ver</a>';
                    }else{
                        if(row.adjunto=="2"){
                            html = '<a href="javascript:void(0)" onclick="VerComprobante(\'' + row.tipcomp_1 + '\')">Comprobante</a>';
                        }else{
                            html = "ninguno";
                        }
                    }
                    return html;
                }
            }
            
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

function EditarDetalleComprobante(id){
	var data = {
		"btnEditarDetalleComprobante": true,
		"idRegistro": id
	};
	$.ajax({
		type: "POST",
		url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
		data: data,
		dataType: "json",
		success: function (dato) {
			console.log(dato);
			if (dato.status == "ok") {
			    
			    NuevoDetalleComprobante();
			    
			    var resultado = dato.data;
			    
			    $("#__IDPAGO_DET_COMPROBANTE").val(resultado.id);
                $("#txtFechaEmisionCV").val(resultado.fecha_emision);
                $("#cbxTipoComprobanteCV").val(resultado.tipoComprobante);
                $("#txtSerieCV").val(resultado.serie);
                $("#txtNumeroCV").val(resultado.numero);
                $("#cbxTipoDoc").val(resultado.tipo_documento);
                $("#txtNroDocumento").val(resultado.documento);
                $("#txtDatosCliente").val(resultado.cliente);
                $("#txtTipoMoneda").val(resultado.tipo_moneda);
                $("#txtTotalPagado").val(resultado.total_pagado);
                $("#txtFechaVencimiento").val(resultado.fecha_vencimiento);
                $("#txtTipoCambio").val(resultado.tipo_cambio);
                
            } else {
                mensaje_alerta("¡ERROR!", dato.data, "info");
            }
		},
		error: function (jqXHR, textStatus, errorThrown) {
		
			console.log(textStatus + ': ' + errorThrown);
			desbloquearPantalla();
		},
		timeout: timeoutDefecto
	});
}


function NuevoDetalleComprobante(){
    
    document.getElementById('cbxTipoComprobanteCV').selectedIndex = 0;
    document.getElementById('cbxTipoDoc').selectedIndex = 0;
    document.getElementById('txtTipoMoneda').selectedIndex = 0;
    document.getElementById('cbxConceptos').selectedIndex = 0;
    $("#__IDPAGO_DET_COMPROBANTE").val("");
    $("#txtFechaEmisionCV").val("");
    $("#txtSerieCV").val("");
    $("#txtNumeroCV").val("");
    $("#txtNroDocumento").val("");
    $("#txtDatosCliente").val("");
    $("#txtTotalPagado").val("0.00");
    $("#txtFechaVencimiento").val("");
    $("#txtTipoCambio").val("0.00");
    $("#ComprobanteCV").val("");
}


function SolicitarEliminar(id,serie,numero){
    mensaje_eliminar_parametro('\u00BFEst\u00E1 seguro(a) que desea eliminar el comprobante '+serie+' - '+numero+'?', EliminarComprobante, id);
}


function EliminarComprobante(id){
	var data = {
		"btnEliminarComprobante": true,
		"id": id
	};
	$.ajax({
		type: "POST",
		url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
		data: data,
		dataType: "json",
		success: function (dato) {
			console.log(dato);
			if (dato.status == "ok") {
                mensaje_alerta("¡CORRECTO!", dato.data, "success");
            } else {
                mensaje_alerta("¡ERROR!", dato.data, "info");
            }
		},
		error: function (jqXHR, textStatus, errorThrown) {
		
			console.log(textStatus + ': ' + errorThrown);
			desbloquearPantalla();
		},
		timeout: timeoutDefecto
	});
}













/* ================ PAGOS - RESERVAS =====================*/

//AGREGAR FECHA INICIO Y TERMINO DEL MES ACTUAL
function ValidarFechasR(){
    var data = {
       "btnValidarFechas": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               $("#txtFiltroDesdeCVR").val(dato.primero);
               $("#txtFiltroHastaCVR").val(dato.ultimo);   
           } 
           CargarPagosComprobanteReserva();
           $('#TablaPagoComprobanteReporteR').DataTable().ajax.reload();  

       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}


//LLENAR TABLA CONTENEDORA PAGOS RESERVAS
function CargarPagosComprobanteReserva() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php";
    var dato = {
        "btnListarTablaPagosComprobanteReserva": true,
		"txtFiltroDocumentoCV": $("#txtFiltroDocumentoCVR").val(),
		"txtFiltroDesdeCV": $("#txtFiltroDesdeCVR").val(),
		"txtFiltroHastaCV": $("#txtFiltroHastaCVR").val(),
		"cbxFiltroBancoCV": $("#cbxFiltroBancoCVR").val(),
		"cbxFiltroEstadoPC": $("#cbxFiltroEstadoPCR").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosComprobanteReserva, null, 10000, null);
}

function respuestaBuscarPagosComprobanteReserva(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaPagosComprobanteReserva(dato.data);
}

var getTablaPagosComprobanteReserva = null;
function LlenarTablaPagosComprobanteReserva(datos) {
    if (getTablaPagosComprobanteReserva) {
        getTablaPagosComprobanteReserva.destroy();
        getTablaPagosComprobanteReserva = null;
    }

    getTablaPagosComprobanteReserva = $('#TablaPagoComprobanteR').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";
                    if(row.estado_cierre!="FINALIZADO"){
                        html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="CargaComprobante(\'' + data + '\')" title="Cargar Comprobante"><i class="fas fa-paperclip"></i></a>\ <a href="javascript:void(0)" class="btn btn-edit-action" onclick="VerVoucher(\'' + row.id + '\')" title="Voucher de Pago"><i class="fas fa-file"></i></a>\ <a href="javascript:void(0)" class="btn btn-edit-action" onclick="VerComprobantesSunat(\'' + data + '\')" title="Ver Comprobante(s)"><i class="fas fa-folder-open"></i></a>\ <a href="javascript:void(0)" class="btn btn-success-action" onclick="FinalizarCarga(\'' + data + '\')" title="Finalizar"><i class="fas fa-check"></i></a>';
                    }else{                       
                        html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="VerVoucher(\'' + row.id + '\')" title="Voucher de Pago"><i class="fas fa-file"></i></a>\ <a href="javascript:void(0)" class="btn btn-edit-action" onclick="VerComprobantesSunat(\'' + data + '\')" title="Ver Comprobante(s)"><i class="fas fa-folder-open"></i></a>';   
                    }
                    return html;
                }
            },
            {
                "data": "estado_cierre",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color: '+ row.estado_cierre_color +';">' + row.estado_cierre + '</span>';
                    return html;
                } 
            },
            { 
                "data": "nro_comprobantes",
                "render": function(data, type, row, host) {
                    var html="";
                    html = '<label>'+ row.nro_comprobantes +'</label>';
                    return html;
                }
            },
            { "data": "fecha_pago" },
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fecha_vencimiento" },
            { "data": "letra" },
            { "data": "mora" },
			{ "data": "tipo_moneda" },
			{ "data": "importe" },
			{ "data": "tipo_cambio" },
			{ "data": "pagado" },
			{
                "data": "estado_pago",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color: '+ row.estado_pago_color +';">' + row.estado_pago + '</span>';
                    return html;
                } 
            },
            { "data": "banco" },
            { "data": "medio_pago" }, 
            { "data": "tipo_comprobante" },
            { "data": "nro_operacion" }
            
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

var tablaPagosReporteReserva = null;
function LlenarTablaPagosComprobanteReporteReserva() {
    var options = $.extend(true, {}, defaults, {
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
        "iDisplayLength": 5,
        "aLengthMenu": [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ],
        "sDom": 'Bfrtilp',
        "tableTools": {
            "aButtons": []
        },
        "bFilter": false,
        "paging": false,
        "info": false,
        "bSort": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 20, 50, 100, 150],
            [10, 20, 50, 100, 150] // change per page values here
        ],
        "pageLength": 1000000000, // default record count per page,
        "ajax": {
            "url": "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "btnListarTablaPagosComprobanteReserva": true,
            		"txtFiltroDocumentoCV": $("#txtFiltroDocumentoCVR").val(),
            		"txtFiltroDesdeCV": $("#txtFiltroDesdeCVR").val(),
            		"txtFiltroHastaCV": $("#txtFiltroHastaCVR").val(),
            		"cbxFiltroBancoCV": $("#cbxFiltroBancoCVR").val(),
            		"cbxFiltroEstadoPC": $("#cbxFiltroEstadoPCR").val()
                });
            }
        },
        "columns": [
        { "data": "estado_cierre" },    
        { "data": "fecha_pago" },
        { "data": "cliente" },
        { "data": "lote" },
        { "data": "fecha_vencimiento" },
        { "data": "letra" },
        { "data": "mora" },
        { "data": "tipo_moneda" },
        { "data": "importe" },
        { "data": "tipo_cambio" },
        { "data": "pagado" },
        { "data": "estado_pago" }, 
        { "data": "banco" },
        { "data": "medio_pago" },
        { "data": "tipo_comprobante" },
        { "data": "nro_operacion" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        responsive: "true",

        buttons: [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> ',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success'
            },
           /* {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> ',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger'
            },*/
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> ',
                titleAttr: 'Imprimir',
                className: 'btn btn-info'
            }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }

    });

    tablaPagosReporteReserva = $('#TablaPagoComprobanteReporteR').DataTable(options);
}

















