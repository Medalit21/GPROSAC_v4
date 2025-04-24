var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {
   
    ValidarFechas();
    ListarVentasCancelacion();
    ListarCancelacionesReporte();
	
    /***************ACCION BOTONES CABECERA********** */

    $('#btnBuscarRegistro').click(function() {
        ListarVentasCancelacion();
        document.title = "GPROSAC - REPORTE CANCELACIONES";
        $('#TablaRegVentasReport').DataTable().ajax.reload();
    });

    $('#btnLimpiar').click(function() {
        $('#txtFiltroDocumento').val(null).trigger('change');
        ValidarFechas();
        document.getElementById('cbxFiltroEstado').selectedIndex = 0;
        ListarVentasCancelacion();
        document.title = "GPROSAC - REPORTE CANCELACIONES";
        $('#TablaRegVentasReport').DataTable().ajax.reload();
    });
	
}

//AGREGAR FECHA INICIO DE PROYECTO Y FECHA ACTUAL
function ValidarFechas(){
    var data = {
       "btnValidarFechas": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M03_Ventas/M03MD07_Cancelacion/M03MD07_Cancelacion.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               $("#txtFiltroDesde").val(dato.primero);
               $("#txtFiltroHasta").val(dato.ultimo);   
           } 
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       }
   });   
}


function ListarVentasCancelacion() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD07_Cancelacion/M03MD07_Cancelacion.php";
    var dato = {
        "ReturnTablaVentasCancelacion": true,
        "txtFiltroDocumento": $("#txtFiltroDocumento").val(),
        "txtFiltroDesde": $("#txtFiltroDesde").val(),
        "txtFiltroHasta": $("#txtFiltroHasta").val(),
        "cbxFiltroEstado": $("#cbxFiltroEstado").val()
    };
    realizarJsonPost(url, dato, ListarRegVentasCancelacion, null, 10000, null);
}

function ListarRegVentasCancelacion(dato) {
    desbloquearPantalla();
    //console.log(dato);
    ListarReggistrosVentasCancelacion(dato.data);
}

var tablaVentasCancelacion = null;
function ListarReggistrosVentasCancelacion(datos) {
    if (tablaVentasCancelacion) {
        tablaVentasCancelacion.destroy();
        tablaVentasCancelacion = null;
    }

    tablaVentasCancelacion = $('#TablaRegVentas').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "0%"
            },
            {
                'aTargets': [1],
                'ordering': false,
                'width': "0%"
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
        {
            "data": "estado",
            "render": function(data, type, row) {
                var html = "";
                if (data === 'POR CANCELAR') {
                    html = '<span class="label label-warning etiqueta-js">' + row.estado + '</span>';
                } else {
                    html = '<span class="label label-success etiqueta-js">' + row.estado + '</span>';                        
                }
                return html;
            }
        },
        { "data": "fecha" },
        { "data": "documento" },
        { "data": "datos" },
        { "data": "lote" },
        { "data": "nro_letras" },
        { "data": "precio_venta" },
        { "data": "intereses" },
        { 
            "data": "total_lote",
            "render": function(data, type, row) {
                var html = ""; 
                html = '<div class="fond-tc text-center"><span class="etiqueta-val" >' + row.total_lote + '</span></div>';
                return html;
            }
        },
        { 
            "data": "total_cancelado",
            "render": function(data, type, row) {
                var html = ""; 
                html = '<div class="fond-tca text-center"><span class="etiqueta-val" >' + row.total_cancelado + '</span></div>';
                return html;
            }
        },
        { 
            "data": "deuda_capital",
            "render": function(data, type, row) {
                var html = ""; 
                html = '<div class="fond-tpc text-center"><span class="etiqueta-val" >' + row.deuda_capital + '</span></div>';
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


function ListarCancelacionesReporte() {
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
            "url": "../../models/M03_Ventas/M03MD07_Cancelacion/M03MD07_Cancelacion.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnTablaVentasCancelacion": true,
                    "txtFiltroDocumento": $("#txtFiltroDocumento").val(),
                    "txtFiltroDesde": $("#txtFiltroDesde").val(),
                    "txtFiltroHasta": $("#txtFiltroHasta").val(),
                    "cbxFiltroEstado": $("#cbxFiltroEstado").val()
                });
            }
        },
        "columns": [
            { "data": "estado" },
            { "data": "fecha" },
            { "data": "documento" },
            { "data": "datos" },
            { "data": "lote" },
            { "data": "nro_letras" },
            { "data": "precio_venta" },
            { "data": "intereses" },
            { "data": "total_lote" },
            { "data": "total_cancelado" },
            { "data": "deuda_capital" }
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

    tablaEmpresas = $('#TablaRegVentasReport').DataTable(options);
}



//INDICAR CANCELACION DE VENTA
function CancelarVenta(id){
    var data = {
       "btnCancelarVenta": true,
       "idRegistro": id
   };
   $.ajax({
       type: "POST",
       url: "../../models/M03_Ventas/M03MD07_Cancelacion/M03MD07_Cancelacion.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               ListarVentasCancelacion();
               mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");  
           }else{
               mensaje_alerta("\u00A1ERROR!", dato.data, "info"); 
           }
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       }
   });   
}

//ANULAR CANCELACION DE VENTA
function AnularCancelacionVenta(id){
    var data = {
       "btnAnularCancelacionVenta": true,
       "idRegistro": id
   };
   $.ajax({
       type: "POST",
       url: "../../models/M03_Ventas/M03MD07_Cancelacion/M03MD07_Cancelacion.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               ListarVentasCancelacion();
               mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");  
           }else{
               mensaje_alerta("\u00A1ERROR!", dato.data, "info"); 
           }
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       }
   });   
}
