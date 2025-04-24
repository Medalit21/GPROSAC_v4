var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {

    ValidarFechas();
    ListarReservacionesReporte();
    ValidarFechasMes();
    
    $('#btnProcesarRecaudaciones').click(function() {
        CargarDatosTemporal();
        $('#TablaRecaudacionesReporte').DataTable().ajax.reload();
    }); 

    $('#btnRestablecerRecaudaciones').click(function() {
        $('#txtDocumentoREC').val(null).trigger('change');
        LimpiarTablaTemporal();
        ValidarFechas();      
    });

    $('#btnGenerarArchivo').click(function() {
        GenerarTxt();
    }); 
    
    
    $('#btnGuardarTipoRegistro').click(function() {
        ModificarTipoRegistro();
    }); 

    $('#btnModificarTodos').click(function() {
        ModificarTodosTipoRegistro();
    }); 
    
    
    $('#btnBuscarRecaudaciones').click(function() {
        CargarRecaudaciones();
        $('#TablaRecaudacionesReporte').DataTable().ajax.reload();
    }); 
    
    $('#btnLimpiarRecaudaciones').click(function() {
        ValidarFechasMes();
        $('#txtFiltroDocumento').val(null).trigger('change');
        CargarRecaudaciones();
        $('#TablaRecaudacionesReporte').DataTable().ajax.reload();
    }); 

}

function CargarDatosTemporal(){
    var data = {
       "btnCargarDatosTemporal": true,
        "txtFecIniREC": $("#txtFecIniREC").val(),
        "txtFecFinREC": $("#txtFecFinREC").val(),
        "txtDocumentoREC": $("#txtDocumentoREC").val(),
        "__ID_USER": $("#__ID_USER").val()
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               //console.log(dato);
               CargarRecaudaciones();
               mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
           } 
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}


function LimpiarTablaTemporal(){
    var data = {
       "btnLimpiarTemporal": true,
        "__ID_USER": $("#__ID_USER").val()
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               //console.log(dato);
               mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
           } 
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}


function ValidarFechasMes(){
    var data = {
       "btnValidarFechasMes": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           //console.log(dato);
           if (dato.status == "ok") {
               $("#txtDesdeFiltro").val(dato.primero);
               $("#txtHastaFiltro").val(dato.ultimo);   
           } 
           CargarRecaudaciones();
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}


function CargarRecaudaciones() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php";
    var dato = {
        "btnListarTablaRecaudaciones": true,
        "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
        "txtHastaFiltro": $("#txtHastaFiltro").val(),
        "txtFiltroDocumento": $("#txtFiltroDocumento").val(),
        "__ID_USER": $("#__ID_USER").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarRecaudaciones, null, 10000, null);
}

function respuestaBuscarRecaudaciones(dato) {
    desbloquearPantalla();
    //console.log(dato);
    $("#txtCuentaAfiliadoREC").val("");
    $("#txtNombreEmpresaREC").val("GPRO SAC");
    $("#txtTotalRegistrosREC").val(dato.totalreg);
    $("#txtMontoTotalREC").val(dato.totalmonto);
    //$("#txtTipoArchivoREC").val("");
    //$("#txtCodigoServicioREC").val("000000"); 
    LlenarTablaComprobantesImpresos(dato.data);
}

var getTablaRecaudaciones = null;
function LlenarTablaComprobantesImpresos(datos) {
    if (getTablaRecaudaciones) {
        getTablaRecaudaciones.destroy();
        getTablaRecaudaciones = null;
    }

    getTablaRecaudaciones = $('#TablaRecaudaciones').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
            },
            {
                'aTargets': [1],
                'ordering': false,
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
                    html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="EliminarRegistro(\'' + data + '\')" title="Descartar"><i class="fas fa-trash"></i></a>';
                    return html;
                }
            },
            { "data": "codigo_depo" },
            { "data": "nombre_depo" },
            { "data": "info_retorno" },            
            { "data": "fecha_emision" },
            { "data": "fecha_vencimiento" },
            { "data": "monto_pagar" },
            { "data": "mora" }, 
            { "data": "monto_min" },
            { "data": "tipo",
                "render": function (data, type, row) {
                    var html = "";
                    html = '<a href="javascript:void(0)" class="btn btn-edit-action boton-rec" onclick="VerTipoRegistro(\'' + row.id + '\')" title="Modificar Tipo Registro">'+row.tipo+'</a>';
                    return html;
                }
            }, 
            { "data": "documento_pago" }, 
            { "data": "nro_documento" } 
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

function ListarReservacionesReporte() {
    var options = $.extend(true, {}, defaults, {
        "aoColumnDefs": [{
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
            "url": "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                     "btnListarTablaRecaudaciones": true,
                    "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
                    "txtHastaFiltro": $("#txtHastaFiltro").val(),
                    "txtFiltroDocumento": $("#txtFiltroDocumento").val(),
                    "__ID_USER": $("#__ID_USER").val()	
                });
            }
        },
        "columns": [
            { "data": "codigo_depo" },
            { "data": "nombre_depo" },
            { "data": "info_retorno" },            
            { "data": "fecha_emision" },
            { "data": "fecha_vencimiento" },
            { "data": "monto_pagar" },
            { "data": "mora" }, 
            { "data": "monto_min" },
            { "data": "tipo",
                "render": function (data, type, row) {
                    var html = "";
                    html = '<a href="javascript:void(0)" class="btn btn-edit-action boton-rec" onclick="VerTipoRegistro(\'' + row.id + '\')" title="Modificar Tipo Registro">'+row.tipo+'</a>';
                    return html;
                }
            }, 
            { "data": "documento_pago" }, 
            { "data": "nro_documento" } 
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
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> ',
				orientation: 'landscape',
                titleAttr: 'Exportar a PDF',
				pageSize: 'LEGAL', 
                className: 'btn btn-danger'
            },
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

    tablaRecaud = $('#TablaRecaudacionesReporte').DataTable(options);
}





function EliminarRegistro(id){
    var data = {
       "btnEliminarRegistro": true,
        "idRegistro": id
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               CargarRecaudaciones();
               mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
           } 
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}


function VerTipoRegistro(id){
    var data = {
       "btnVerTipoReg": true,
        "idRegistro": id
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           $("#cbxTipoRegistro").val(dato.tipo_registro);
           $("#_ID_TEMP_RECAUDA").val(dato.id);
           $('#modalTipoRegistro').modal('show');
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

function ModificarTipoRegistro(){
    var data = {
       "btnModificarTipoReg": true,
        "_ID_TEMP_RECAUDA": $("#_ID_TEMP_RECAUDA").val(),
        "cbxTipoRegistro": $("#cbxTipoRegistro").val()
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
           $('#modalTipoRegistro').modal('hide');
           CargarRecaudaciones();
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}



function ValidarCampoModifTodos() {
    var flat = true;
  
        if ($("#cbxFiltroTipoRegistro").val() === "" || $("#cbxFiltroTipoRegistro").val() === null) {
            $("#cbxFiltroTipoRegistro").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de registro que reemplazar\u00E1 en la informaci\u00F3n filtrada.", "info");
            flat = false;
        } 
    
    return flat;
}

function ModificarTodosTipoRegistro(){
    if(ValidarCampoModifTodos()){
        var data = {
           "btnModificarTodosTipoReg": true,
            "__ID_USER": $("#__ID_USER").val(),
            "cbxFiltroTipoRegistro": $("#cbxFiltroTipoRegistro").val()
       };
       $.ajax({
           type: "POST",
           url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
           data: data,
           dataType: "json",
           success: function (dato) {
               desbloquearPantalla();
               mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
               CargarRecaudaciones();
           },
           error: function (jqXHR, textStatus, errorThrown) {
               console.log(textStatus + ': ' + errorThrown);
               desbloquearPantalla();
           },
           timeout: timeoutDefecto
       });
    }
}




function ValidarFechas(){
    var data = {
       "btnValidarFechas": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           //console.log(dato);
           if (dato.status == "ok") {
               $("#txtFecIniREC").val('2');
               $("#txtFecFinREC").val(dato.ultimo);   
           } 
           CargarRecaudaciones();
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}


function ValidarCamposComprobante(){
    var flat = true;
  
        if ($("#txtCuentaAfiliadoREC").val() === "" || $("#txtCuentaAfiliadoREC").val() === null) {
            $("#txtCuentaAfiliadoREC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar la cuenta del Afiliado.", "info");
            flat = false;
        } else if ($("#txtNombreEmpresaREC").val() === "" || $("#txtNombreEmpresaREC").val() === null) {
            $("#txtNombreEmpresaREC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el nombre de la Empresa.", "info");
            flat = false;
        } else if ($("#txtTipoArchivoREC").val() === "" || $("#txtTipoArchivoREC").val() === null) {
            $("#txtTipoArchivoREC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de Archivo.", "info");
            flat = false;
        } else if ($("#txtCodigoServicioREC").val() === "" || $("#txtCodigoServicioREC").val() === null) {
            $("#txtCodigoServicioREC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el c\u00F3digo de servicio.", "info");
            flat = false;
        }
    
    return flat;
}


function GenerarTxt(){
    if(ValidarCamposComprobante()){
        var data = {
           "btnGenerarTxt": true,
           "txtCuentaAfiliadoREC": $("#txtCuentaAfiliadoREC").val(),
           "txtNombreEmpresaREC": $("#txtNombreEmpresaREC").val(),
           "txtTotalRegistrosREC": $("#txtTotalRegistrosREC").val(),
           "txtMontoTotalREC": $("#txtMontoTotalREC").val(),
           "txtTipoArchivoREC": $("#txtTipoArchivoREC").val(),
           "txtCodigoServicioREC": $("#txtCodigoServicioREC").val(),
           "txtFecIniREC": $("#txtFecIniREC").val(),
            "txtFecFinREC": $("#txtFecFinREC").val(),
            "txtDocumentoREC": $("#txtDocumentoREC").val(),
            "__ID_USER": $("#__ID_USER").val()
       };
       $.ajax({
           type: "POST",
           url: "../../models/M07_Contabilidad/M07MD03_Recaudaciones/M07MD03_Procesos.php",
           data: data,
           dataType: "json",
           success: function (dato) {
               desbloquearPantalla();
               console.log(dato);
               if (dato.status == "ok") {
    
                    var filePath = '../../models/M07_Contabilidad/M07MD03_Recaudaciones/CREP.txt';
                    var link=document.createElement('a');
                    link.href = filePath;
                    link.download = filePath.substr(filePath.lastIndexOf('/') + 1);
                    link.click();
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");    
               }else{
                    mensaje_alerta("\u00A1ERROR!", dato.data, "info");   
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