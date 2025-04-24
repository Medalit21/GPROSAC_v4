var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {
  
    BusacarVentaPaginado();
    CargarVentasReportes();
    
    $('#btnBuscarRegistro').click(function() {
        $('#tableRegistroVenta').DataTable().ajax.reload();  
        $('#tableRegistroReportes').DataTable().ajax.reload(); 
    });

    $('#btnLimpiar').click(function() {
        $("#txtDesdeFiltro,#txtHastaFiltro").val("");
        $('#txtDocumentoFiltro').val(null).trigger('change');
        document.getElementById('cbxCondicionFiltro').selectedIndex = 0;
        $('#tableRegistroVenta').DataTable().ajax.reload();  
        $('#tableRegistroReportes').DataTable().ajax.reload(); 
    });
    
}

var tablaBusqVentaPag = null;
function BusacarVentaPaginado() {
    if (tablaBusqVentaPag) {
        tablaBusqVentaPag.destroy();
        tablaBusqVentaPag = null;
    }
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
        "sDom": '<"dt-panelmenu clearfix"Tfr>t<"dt-panelfooter clearfix"ip>',
        "tableTools": {
            "aButtons": []
        },
        "bFilter": false,
        "bSort": true,
        "processing": true,
        "serverSide": true,

        "lengthMenu": [
            [10, 20, 50, 100, 150],
            [10, 20, 50, 100, 150] // change per page values here
        ],
        "pageLength": 10, // default record count per page,
        "ajax": {
            "url": "../../models/M08_Administracion/M08MD01_RegVentas/M08MD01_RegVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnVentaPag": true,
                    "documento": $("#txtDocumentoFiltro").val(),
                    "condicion": $("#cbxCondicionFiltro").val(),
                    "desde": $("#txtDesdeFiltro").val(),
                    "hasta": $("#txtHastaFiltro").val()
                });
            }
        },
        "columns": [
            { "data": "fechaVenta" },
            { "data": "cliente" },
            { "data": "nombreLote" },
            {
                "data": "idVenta",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action text-center" onclick="VerDocumentos(\'' + data + '\')" title="Editar"><i class="fas fa-folder-open"></i> - '+row.conteo_adjuntos+'</a>';
                }
            },
            { "data": "area" },
            { "data": "condicion" },
            { "data": "tipoMoneda" },
            { "data": "montoDescuento" },
            { "data": "total" },
            { "data": "motoCuotaInicial" }, 
            { "data": "monto_total",
                "render": function (data, type, row) {
                    var html="";
                    html = "<div style='color: #AABB00; font-weight: bold;'>"+row.monto_total+"</div>"
                    return html;
                } 
            },
            { "data": "monto_pagado",
                "render": function (data, type, row) {
                    var html="";
                    html = "<div style='color: #007EE1; font-weight: bold;'>"+row.monto_pagado+"</div>"
                    return html;
                }
            },
            { "data": "monto_pendiente",
                "render": function (data, type, row) {
                    var html="";
                    html = "<div style='color: #EB9200; font-weight: bold;'>"+row.monto_pendiente+"</div>"
                    return html;
                }
            },           
            { "data": "vendedor" },
            { "data": "registro" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }
    });

    tablaBusqVentaPag = $('#tableRegistroVenta').DataTable(options);
}

var tablaBusqVentasReport = null;
function CargarVentasReportes() {
    if (tablaBusqVentasReport) {
        tablaBusqVentasReport.destroy();
        tablaBusqVentasReport = null;
    }
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
        "bSort": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 20, 50, 100, 150],
            [10, 20, 50, 100, 150] // change per page values here
        ],
        "pageLength": 1000000000, // default record count per page,
        "ajax": {
            "url": "../../models/M08_Administracion/M08MD01_RegVentas/M08MD01_RegVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnVentaPag": true,
                    "documento": $("#txtDocumentoFiltro").val(),
                    "condicion": $("#cbxCondicionFiltro").val(),
                    "desde": $("#txtDesdeFiltro").val(),
                    "hasta": $("#txtHastaFiltro").val()
                });
            }
        },
        "columns": [
            { "data": "fechaVenta" },
            { "data": "cliente" },
            { "data": "nombreLote" },
            { "data": "area" },
            { "data": "condicion" },
            { "data": "tipoMoneda" },
            { "data": "montoDescuento" },
            { "data": "total" },
            { "data": "motoCuotaInicial" }, 
            { "data": "monto_total" },
            { "data": "monto_pagado" },
            { "data": "monto_pendiente" },            
            { "data": "vendedor" },
            { "data": "registro" }
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
            }, 
        ]

    });

    tablaBusqPerfilesReport = $('#tableRegistroReportes').DataTable(options);
}

function VerDocumentos(id){
    BuscarListaDocumentosAdjuntos(id);
    $("#modalAdjuntos").modal("show");
}


/**************************LISTA DE DOCUMENTOS ADJUNTOS ************************** */
function BuscarListaDocumentosAdjuntos(id) {
    var url = "../../models/M08_Administracion/M08MD01_RegVentas/M08MD01_RegVentas.php";
    var dato = {
        "ReturnListaDocuemntosAdjuntos": true,
        "idVenta": id
    };
    realizarJsonPost(url, dato, RespuestaBuscarListaDocumentosAdjuntos, null, 10000, null);
}

function RespuestaBuscarListaDocumentosAdjuntos(dato) {
    LlenarTablaAdjuntosVenta(dato.data);
    console.log(dato.data);
}

var tablaAdjuntosVenta = null;
function LlenarTablaAdjuntosVenta(dato) {
    if (tablaAdjuntosVenta) {
        tablaAdjuntosVenta.destroy();
        tablaAdjuntosVenta = null;
    }
    var options = $.extend(true, {}, defaults, {
        data: dato,
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
        "iDisplayLength": 5,
        "aLengthMenu": [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ],
        "sDom": '<"dt-panelmenu clearfix"Tfr>t<"dt-panelfooter clearfix"ip>',
        "tableTools": {
            "aButtons": []
        },
        "bFilter": false,
        "lengthChange": false,
        "info": false,
        "bSort": false,
        "paging": false,
        "pageLength": 1000, // default record count per page,
        "columns": [
            { "data": "tipo_documento" },
            { "data": "fecha" },
            { "data": "notaria" },
            { "data": "fecha_firma" },
            { "data": "valor_inicial" },
            { "data": "valor_cerrado" },
            {
                "data": "adjunto",
                "render": function(data, type, row) {
                   /* return '<a href="'+row.URLadjunto+'" download="'+row.dato+'">documento.pdf</a>';*/
                   html = '<a href="javascript:void(0)" onclick="VerDocumento(\'' + row.adjunto + '\')">Documento</a>';
                   return html;
                }
                
            },
            { "data": "nom_archivo"}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });

    tablaAdjuntosVenta = $('#dataAdjuntoTable').DataTable(options);

    
}

function VerDocumento(adjunto) {  
    var html = "";
          var documento = "../../M03_Ventas/M03SM02_Venta/archivos/"+adjunto+"";
          html +=
              "<object class='pdfview' type='application/pdf' data='" +
              documento +
              "' style='width: 100%'></object> ";
          $("#my_img_doc").html(html);
          $("#modalVerDocumento").modal("show");  
  }



