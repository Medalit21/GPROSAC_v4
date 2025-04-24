var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    //REPORTE 001

   VerTabla1(); 
   CompletarTablaReporte();
   
   VerTabla2();
   CompletarTablaReporte2();
   

    $('#btnBuscarRegistro').click(function() {
        
        var dato = $("#bxFiltroTipo").val();
        var anio = $("#bxFiltroPeriodo").val();
        if(dato=="1"){
            $("#reporte1").show();
            $("#reporte2").hide();
            document.title = "GPROSAC - REPORTE VENTAS RESUMEN (Segun Fecha Pago - "+anio+")";
            VerTabla1();
            CompletarTablaReporte();
            
        }else{
            $("#reporte2").show();
            $("#reporte1").hide();
            document.title = "GPROSAC - REPORTE VENTAS RESUMEN (Segun Fecha Vencimiento - "+anio+")";
            VerTabla2();
            CompletarTablaReporte2();
        }
    });
    
    $('#btnLimpiar').click(function() {
        $('#txtdocumentoFiltro').val(null).trigger('change');
        $('#bxFiltroPeriodo').val(null).trigger('change');
        $('#bxFiltroTipo').val(null).trigger('change');
        document.getElementById('bxFiltroPeriodo').selectedIndex = 9;
        VerTabla1();
    });
    
    $('#btnExportarPdf').click(function() {
        IrReportePDF();
    });  
    
    
}

function IrReportePDF(){
    
     var data = {
        "Reporte1": true,
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD09_VentasResumen/M05MD09_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
            if (dato.status == "ok") {
                window.open('ReporteVentasResumen.php?Prd='+dato.param); 
            }else{
                mensaje_alerta("\u00A1IMPORTANTE!", "No se encontr\u00F3 informaci\u00F3n para los datos ingresados", "info");
            }
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function VerTabla1() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD09_VentasResumen/M05MD09_Procesos.php";
    var dato = {
        "ReturnListaClientes": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarReporte1, null, 10000, null);
}

function respuestaBuscarReporte1(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaRep1(dato.data);
}

var getTablaBusquedaRep1 = null;
function LlenarTabalaRep1(datos) {
    if (getTablaBusquedaRep1) {
        getTablaBusquedaRep1.destroy();
        getTablaBusquedaRep1 = null;
    }

    getTablaBusquedaRep1 = $('#TablaUsuario').DataTable({
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
            {   
                "data": "nom_vendedor",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        html = '<div style="font-weight: bold; text-align: left;">' + row.nom_vendedor + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold; text-align: left;">' + row.nom_vendedor + '</div>';
                        }else{
                            html = '<div style="text-align: left;">' + row.nom_vendedor + '</div>';
                        }
                    }
                    return html;
                } 
            },
            { "data": "fecha" },
            { "data": "cliente",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.fecha==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: left;">' + row.cliente + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "tipo_moneda",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.tipo_moneda==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: center;">' + row.tipo_moneda + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "proyecto",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.proyecto==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: left;">' + row.proyecto + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "zona",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.zona==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: left;">' + row.zona + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "lote",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.lote==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: left;">' + row.lote + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "estado_venta",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.estado_venta==""){
                        html = '';
                    }else{
                        if(row.estado_venta=="DEVUELTO"){
                            html = '<div style="text-align: left; color: red">' + row.estado_venta + '</div>';   
                        }else{
                            html = '<div style="text-align: left;">' + row.estado_venta + '</div>';  
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "total",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        html = '<div style="font-weight: bold;">' + row.total + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.total + '</div>';
                        }else{
                            html = '<div style="color: blue;">' + row.total + '</div>';
                        }
                    }
                    return html;
                } 

            },
            { 
                "data": "total_financiado",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        html = '<div style="font-weight: bold;">' + row.total_financiado + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.total_financiado + '</div>';
                        }else{
                            html = '<div style="color: blue;">' + row.total_financiado + '</div>';
                        }
                    }
                    return html;
                } 

            },
            { 
                "data": "monto_pagado",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        html = '<div style="font-weight: bold;">' + row.monto_pagado + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.monto_pagado + '</div>';
                        }else{
                            html = '<div style="color: blue;">' + row.monto_pagado + '</div>';
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "saldo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        html = '<div style="font-weight: bold;">' + row.saldo + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo + '</div>';
                        }else{
                            html = '<div style="color: red;">' + row.saldo + '</div>';
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "monto_inicial",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        html = '<div style="font-weight: bold;">' + row.monto_inicial + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.monto_inicial + '</div>';
                        }else{
                            html = '<div style="color: #E56C01;">' + row.monto_inicial + '</div>';
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "enero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.enero) > 0) {
                        html = '<div style="font-weight: bold;">' + row.enero + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.enero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.enero + '</div>';
                        }else{
                            if (parseInt(row.enero) > 0) {
                                html = '<div style="color: #109501;">' + row.enero + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "febrero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.febrero) > 0) {
                        html = '<div style="font-weight: bold;">' + row.febrero + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.febrero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.febrero + '</div>';
                        }else{
                            if (parseInt(row.febrero) > 0) {
                                html = '<div style="color: #109501;">' + row.febrero + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "marzo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.marzo) > 0) {
                        html = '<div style="font-weight: bold;">' + row.marzo + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.marzo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.marzo + '</div>';
                        }else{
                            if (parseInt(row.marzo) > 0) {
                                html = '<div style="color: #109501;">' + row.marzo + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "abril",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.abril) > 0) {
                        html = '<div style="font-weight: bold;">' + row.abril + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.abril) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.abril + '</div>';
                        }else{
                            if (parseInt(row.abril) > 0) {
                                html = '<div style="color: #109501;">' + row.abril + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "mayo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.mayo) > 0) {
                        html = '<div style="font-weight: bold;">' + row.mayo + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.mayo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.mayo + '</div>';
                        }else{
                            if (parseInt(row.mayo) > 0) {
                                html = '<div style="color: #109501;">' + row.mayo + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "junio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.junio) > 0) {
                        html = '<div style="font-weight: bold;">' + row.junio + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.junio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.junio + '</div>';
                        }else{
                            if (parseInt(row.junio) > 0) {
                                html = '<div style="color: #109501;">' + row.junio + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "julio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.julio) > 0) {
                        html = '<div style="font-weight: bold;">' + row.julio + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.julio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.julio + '</div>';
                        }else{
                            if (parseInt(row.julio) > 0) {
                                html = '<div style="color: #109501;">' + row.julio + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "agosto",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.agosto) > 0) {
                        html = '<div style="font-weight: bold;">' + row.agosto + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.agosto) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.agosto + '</div>';
                        }else{
                            if (parseInt(row.agosto) > 0) {
                                html = '<div style="color: #109501;">' + row.agosto + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "septiembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.septiembre) > 0) {
                        html = '<div style="font-weight: bold;">' + row.septiembre + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.septiembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.septiembre + '</div>';
                        }else{
                            if (parseInt(row.septiembre) > 0) {
                                html = '<div style="color: #109501;">' + row.septiembre + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "octubre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.octubre) > 0) {
                        html = '<div style="font-weight: bold;">' + row.octubre + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.octubre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.octubre + '</div>';
                        }else{
                            if (parseInt(row.octubre) > 0) {
                                html = '<div style="color: #109501;">' + row.octubre + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "noviembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.noviembre) > 0) {
                        html = '<div style="font-weight: bold;">' + row.noviembre + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.noviembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.noviembre + '</div>';
                        }else{
                            if (parseInt(row.noviembre) > 0) {
                                html = '<div style="color: #109501;">' + row.noviembre + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },                     
            { 
                "data": "diciembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.diciembre) > 0) {
                        html = '<div style="font-weight: bold;">' + row.diciembre + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.diciembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.diciembre + '</div>';
                        }else{
                            if (parseInt(row.diciembre) > 0) {
                                html = '<div style="color: #109501;">' + row.diciembre + '</div>';
                            }
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

var LlenarTablaReporte = null;
function CompletarTablaReporte() {
    if (LlenarTablaReporte) {
        LlenarTablaReporte.destroy();
        LlenarTablaReporte = null;
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
            "url": "../../models/M05_Reportes/M05MD09_VentasResumen/M05MD09_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnListaClientes": true,
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
                    "txtNombresFiltro": $("#txtNombresFiltro").val(),
                    "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
                    "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
                });
            }
        },
        "columns": [
             {  "data": "nom_vendedor" },
            { "data": "fecha" },
            { "data": "documento" },
            { "data": "cliente" },
            { "data": "tipo_moneda" },
            { "data": "proyecto" },
            { "data": "zona"},
            { "data": "lote"},
            { "data": "estado_venta"},
            { "data": "total" },
            { "data": "total_financiado" },
            { "data": "monto_pagado" },
            { "data": "saldo" },
            { "data": "monto_inicial" },
            { "data": "enero" },
            { "data": "febrero" },
            { "data": "marzo" },
            { "data": "abril" },
            { "data": "mayo" },
            { "data": "junio" },
            { "data": "julio" },
            { "data": "agosto" },
            { "data": "septiembre" },
            { "data": "octubre" },
            { "data": "noviembre" },                     
            { "data": "diciembre" }
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
            /*{
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> ',
                orientation: 'landscape',
                titleAttr: 'Exportar a PDF',
				pageSize: 'LEGAL', 
                className: 'btn btn-danger'
            },*/
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> ',
                titleAttr: 'Imprimir',
                className: 'btn btn-info'
            },
        ]

    });

    LlenarTablaReporte = $('#TablaUsuarioReporte').DataTable(options);
}





function VerTabla2() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD09_VentasResumen/M05MD09_Procesos.php";
    var dato = {
        "ReturnListaClientes2": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarReporte2, null, 10000, null);
}

function respuestaBuscarReporte2(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaRep2(dato.data);
}

var getTablaBusquedaRep2 = null;
function LlenarTabalaRep2(datos) {
    if (getTablaBusquedaRep2) {
        getTablaBusquedaRep2.destroy();
        getTablaBusquedaRep2 = null;
    }

    getTablaBusquedaRep2 = $('#TablaUsuario2').DataTable({
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
            {   
                "data": "nom_vendedor",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        html = '<div style="font-weight: bold; text-align: left;">' + row.nom_vendedor + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold; text-align: left;">' + row.nom_vendedor + '</div>';
                        }else{
                            html = '<div style="text-align: left;">' + row.nom_vendedor + '</div>';
                        }
                    }
                    return html;
                } 
            },
            { "data": "fecha" },
            { "data": "cliente",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.fecha==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: left;">' + row.cliente + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "tipo_moneda",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.tipo_moneda==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: center;">' + row.tipo_moneda + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "proyecto",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.proyecto==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: left;">' + row.proyecto + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "zona",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.zona==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: left;">' + row.zona + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "lote",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.lote==""){
                        html = '';
                    }else{
                        html = '<div style="text-align: left;">' + row.lote + '</div>';   
                    }
                    return html;
                } 
            },
            { "data": "estado_venta",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.estado_venta==""){
                        html = '';
                    }else{
                        if(row.estado_venta=="DEVUELTO"){
                            html = '<div style="text-align: left; color: red">' + row.estado_venta + '</div>';   
                        }else{
                            html = '<div style="text-align: left;">' + row.estado_venta + '</div>';  
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "total",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        html = '<div style="font-weight: bold;">' + row.total + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue; font-weight: bold;">' + row.total + '</div>';
                        }else{
                            if(row.total=="null"){
                                html = '<div></div>';
                            }else{
                                html = '<div style="color: blue;">' + row.total + '</div>';
                            }
                        }
                    }
                    return html;
                } 

            },
            { 
                "data": "monto_pagado",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        if(row.monto_pagado=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.monto_pagado + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.monto_pagado + '</div>';
                        }else{
                            if(row.monto_pagado=="null"){
                                html = '<div style="color: blue;"></div>';
                            }else{
                                html = '<div style="color: blue;">' + row.monto_pagado + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "saldo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        if(row.saldo=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo + '</div>';
                        }else{
                            if(row.saldo=="null"){
                                html = '<div style="color: red;"></div>';
                            }else{
                                html = '<div style="color: red;">' + row.saldo + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "monto_inicial",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2) {
                        if(row.monto_inicial=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.monto_inicial + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.monto_inicial + '</div>';
                        }else{
                            if(row.monto_inicial=="null"){
                                html = '<div style="color: #E56C01;"></div>';
                            }else{
                                html = '<div style="color: #E56C01;">' + row.monto_inicial + '</div>';
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "enero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.enero) > 0) {
                        if(row.enero=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.enero + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.enero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.enero + '</div>';
                        }else{
                            if (parseInt(row.enero) > 0) {
                                if(row.enero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.enero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_enero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.enero) > 0) {
                        if(row.enero=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_enero + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.enero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_enero + '</div>';
                        }else{
                            if (parseInt(row.enero) > 0) {
                                if(row.enero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_enero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_enero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.enero) > 0) {
                        if(row.enero=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_enero + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.enero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_enero + '</div>';
                        }else{
                            if (parseInt(row.enero) > 0) {
                                if(row.enero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_enero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_enero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.enero) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.enero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.enero) > 0) {
                                if(row.fec_enero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_enero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_enero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.enero) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.enero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.enero) > 0) {
                                if(row.letra_enero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_enero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "febrero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.febrero) > 0) {
                        html = '<div style="font-weight: bold;">' + row.febrero + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.febrero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.febrero + '</div>';
                        }else{
                            if (parseInt(row.febrero) > 0) {
                                if(row.febrero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.febrero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_febrero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.febrero) > 0) {
                        if(row.febrero=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_febrero + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.febrero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_febrero + '</div>';
                        }else{
                            if (parseInt(row.febrero) > 0) {
                                if(row.febrero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_febrero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_febrero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.febrero) > 0) {
                        if(row.febrero=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_febrero + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.febrero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_febrero + '</div>';
                        }else{
                            if (parseInt(row.febrero) > 0) {
                                if(row.febrero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_febrero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_febrero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.febrero) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.febrero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.febrero) > 0) {
                                if(row.fec_febrero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_febrero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_febrero",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.febrero) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.febrero) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.febrero) > 0) {
                                if(row.letra_febrero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_febrero + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "marzo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.marzo) > 0) {
                        html = '<div style="font-weight: bold;">' + row.marzo + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.marzo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.marzo + '</div>';
                        }else{
                            if (parseInt(row.marzo) > 0) {
                                if(row.marzo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.marzo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_marzo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.marzo) > 0) {
                        if(row.marzo=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_marzo + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.marzo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_marzo + '</div>';
                        }else{
                            if (parseInt(row.marzo) > 0) {
                                if(row.febrero=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_marzo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_marzo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.marzo) > 0) {
                        if(row.marzo=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_marzo + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.marzo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_marzo + '</div>';
                        }else{
                            if (parseInt(row.marzo) > 0) {
                                if(row.marzo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_marzo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_marzo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.marzo) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.marzo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.marzo) > 0) {
                                if(row.fec_marzo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_marzo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_marzo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.marzo) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.marzo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.marzo) > 0) {
                                if(row.letra_marzo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_marzo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "abril",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.abril) > 0) {
                        html = '<div style="font-weight: bold;">' + row.abril + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.abril) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.abril + '</div>';
                        }else{
                            if (parseInt(row.abril) > 0) {
                                if(row.abril=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.abril + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_abril",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.abril) > 0) {
                        if(row.abril=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_abril + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.abril) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_abril + '</div>';
                        }else{
                            if (parseInt(row.abril) > 0) {
                                if(row.abril=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_abril + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_abril",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.abril) > 0) {
                        if(row.abril=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_abril + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.abril) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_abril + '</div>';
                        }else{
                            if (parseInt(row.abril) > 0) {
                                if(row.abril=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_abril + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_abril",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.abril) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.abril) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.abril) > 0) {
                                if(row.fec_abril=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_abril + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_abril",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.abril) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.abril) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.abril) > 0) {
                                if(row.letra_abril=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_abril + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "mayo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.mayo) > 0) {
                        html = '<div style="font-weight: bold;">' + row.mayo + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.mayo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.mayo + '</div>';
                        }else{
                            if (parseInt(row.mayo) > 0) {
                                if(row.mayo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.mayo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_mayo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.mayo) > 0) {
                        if(row.mayo=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_mayo + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.mayo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_mayo + '</div>';
                        }else{
                            if (parseInt(row.mayo) > 0) {
                                if(row.mayo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_mayo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_mayo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.mayo) > 0) {
                        if(row.mayo=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_mayo + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.mayo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_mayo + '</div>';
                        }else{
                            if (parseInt(row.mayo) > 0) {
                                if(row.mayo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_mayo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_mayo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.mayo) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.mayo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.mayo) > 0) {
                                if(row.fec_mayo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_mayo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_mayo",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.mayo) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.mayo) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.mayo) > 0) {
                                if(row.letra_mayo=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_mayo + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "junio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.junio) > 0) {
                        html = '<div style="font-weight: bold;">' + row.junio + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.junio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.junio + '</div>';
                        }else{
                            if (parseInt(row.junio) > 0) {
                                if(row.junio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.junio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_junio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.junio) > 0) {
                        if(row.junio=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_junio + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.junio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_junio + '</div>';
                        }else{
                            if (parseInt(row.junio) > 0) {
                                if(row.junio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_junio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_junio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.junio) > 0) {
                        if(row.junio=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_junio + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.junio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_junio + '</div>';
                        }else{
                            if (parseInt(row.junio) > 0) {
                                if(row.junio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_junio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_junio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.junio) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.junio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.junio) > 0) {
                                if(row.fec_junio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_junio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_junio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.junio) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.junio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.junio) > 0) {
                                if(row.letra_junio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_junio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "julio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.julio) > 0) {
                        html = '<div style="font-weight: bold;">' + row.julio + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.julio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.julio + '</div>';
                        }else{
                            if (parseInt(row.julio) > 0) {
                                if(row.julio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.julio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_julio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.julio) > 0) {
                        if(row.julio=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_julio + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.julio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_julio + '</div>';
                        }else{
                            if (parseInt(row.julio) > 0) {
                                if(row.julio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_julio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_julio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.julio) > 0) {
                        if(row.julio=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_julio + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.julio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_julio + '</div>';
                        }else{
                            if (parseInt(row.julio) > 0) {
                                if(row.julio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_julio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_julio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.julio) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.julio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.julio) > 0) {
                                if(row.fec_julio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_julio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_julio",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.julio) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.julio) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.julio) > 0) {
                                if(row.letra_julio=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_julio + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "agosto",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.agosto) > 0) {
                        html = '<div style="font-weight: bold;">' + row.agosto + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.agosto) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.agosto + '</div>';
                        }else{
                            if (parseInt(row.agosto) > 0) {
                                if(row.agosto=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.agosto + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_agosto",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.agosto) > 0) {
                        if(row.agosto=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_agosto + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.agosto) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_agosto + '</div>';
                        }else{
                            if (parseInt(row.agosto) > 0) {
                                if(row.agosto=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_agosto + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_agosto",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.agosto) > 0) {
                        if(row.agosto=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_agosto + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.agosto) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_agosto + '</div>';
                        }else{
                            if (parseInt(row.agosto) > 0) {
                                if(row.agosto=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_agosto + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_agosto",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.agosto) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.agosto) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.agosto) > 0) {
                                if(row.fec_agosto=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_agosto + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_agosto",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.agosto) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.agosto) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.agosto) > 0) {
                                if(row.letra_agosto=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_agosto + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "septiembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.septiembre) > 0) {
                        html = '<div style="font-weight: bold;">' + row.septiembre + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.septiembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.septiembre + '</div>';
                        }else{
                            if (parseInt(row.septiembre) > 0) {
                                if(row.septiembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.septiembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_septiembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.septiembre) > 0) {
                        if(row.septiembre=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_septiembre + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.septiembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_septiembre + '</div>';
                        }else{
                            if (parseInt(row.septiembre) > 0) {
                                if(row.septiembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_septiembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_septiembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.septiembre) > 0) {
                        if(row.septiembre=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_septiembre + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.septiembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_septiembre + '</div>';
                        }else{
                            if (parseInt(row.septiembre) > 0) {
                                if(row.septiembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_septiembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_septiembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.septiembre) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.septiembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.septiembre) > 0) {
                                if(row.fec_septiembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_septiembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_septiembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.septiembre) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.septiembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.septiembre) > 0) {
                                if(row.letra_septiembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_septiembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "octubre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.octubre) > 0) {
                        html = '<div style="font-weight: bold;">' + row.octubre + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.octubre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.octubre + '</div>';
                        }else{
                            if (parseInt(row.octubre) > 0) {
                                if(row.octubre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.octubre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_octubre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.octubre) > 0) {
                        if(row.octubre=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_octubre + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.octubre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_octubre + '</div>';
                        }else{
                            if (parseInt(row.octubre) > 0) {
                                if(row.octubre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_octubre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_octubre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.octubre) > 0) {
                        if(row.octubre=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_octubre + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.octubre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_octubre + '</div>';
                        }else{
                            if (parseInt(row.octubre) > 0) {
                                if(row.octubre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_octubre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_octubre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.octubre) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.octubre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.octubre) > 0) {
                                if(row.fec_octubre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_octubre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_octubre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.octubre) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.octubre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.octubre) > 0) {
                                if(row.letra_octubre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_octubre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "noviembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.noviembre) > 0) {
                        html = '<div style="font-weight: bold;">' + row.noviembre + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.noviembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.noviembre + '</div>';
                        }else{
                            if (parseInt(row.noviembre) > 0) {
                                if(row.noviembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.noviembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_noviembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.noviembre) > 0) {
                        if(row.noviembre=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_noviembre + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.noviembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_noviembre + '</div>';
                        }else{
                            if (parseInt(row.noviembre) > 0) {
                                if(row.noviembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_noviembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_noviembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.noviembre) > 0) {
                        if(row.noviembre=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_noviembre + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.noviembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_noviembre + '</div>';
                        }else{
                            if (parseInt(row.noviembre) > 0) {
                                if(row.noviembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_noviembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_noviembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.noviembre) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.noviembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.noviembre) > 0) {
                                if(row.fec_noviembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_noviembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_noviembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.noviembre) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.noviembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.noviembre) > 0) {
                                if(row.letra_noviembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_noviembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            { 
                "data": "diciembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.diciembre) > 0) {
                        html = '<div style="font-weight: bold;">' + row.diciembre + '</div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.diciembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.diciembre + '</div>';
                        }else{
                            if (parseInt(row.diciembre) > 0) {
                                if(row.diciembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.diciembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "cancel_diciembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.diciembre) > 0) {
                        if(row.diciembre=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.cancel_diciembre + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.diciembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.cancel_diciembre + '</div>';
                        }else{
                            if (parseInt(row.diciembre) > 0) {
                                if(row.diciembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.cancel_diciembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "saldo_diciembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.diciembre) > 0) {
                        if(row.diciembre=="null"){
                               html = '<div style="font-weight: bold;"></div>';
                            }else{
                                html = '<div style="font-weight: bold;">' + row.saldo_diciembre + '</div>';
                        }
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.diciembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;">' + row.saldo_diciembre + '</div>';
                        }else{
                            if (parseInt(row.diciembre) > 0) {
                                if(row.diciembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.saldo_diciembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "fec_diciembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.diciembre) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.diciembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.diciembre) > 0) {
                                if(row.fec_diciembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.fec_diciembre + '</div>';
                                }
                            }
                        }
                    }
                    return html;
                } 
            },
            {
                "data": "letra_diciembre",
                "render": function(data, type, row) {
                    var html = "";
                    if (parseInt(row.accion) === 2 && parseInt(row.diciembre) > 0) {
                        html = '<div style="font-weight: bold;"></div>';
                    } else {
                        if (parseInt(row.accion) === 3 && parseInt(row.diciembre) > 0) {
                            html = '<div style="color: blue;font-weight: bold;"></div>';
                        }else{
                            if (parseInt(row.diciembre) > 0) {
                                if(row.letra_diciembre=="null"){
                                     html = '<div style="color: #109501;"></div>';
                                }else{
                                    html = '<div style="color: #109501;">' + row.letra_diciembre + '</div>';
                                }
                            }
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

var LlenarTablaReporte2 = null;
function CompletarTablaReporte2() {
    if (LlenarTablaReporte2) {
        LlenarTablaReporte2.destroy();
        LlenarTablaReporte2 = null;
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
            "url": "../../models/M05_Reportes/M05MD09_VentasResumen/M05MD09_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnListaClientes2": true,
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
                    "txtNombresFiltro": $("#txtNombresFiltro").val(),
                    "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
                    "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
                });
            }
        },
        "columns": [
            {  "data": "nom_vendedor" },
            { "data": "fecha" },
			{ "data": "documento" },
            { "data": "cliente" },
            { "data": "tipo_moneda" },
            { "data": "proyecto" },
            { "data": "zona"},
            { "data": "lote"},
            { "data": "estado_venta"},
            {  "data": "total" },
            { "data": "monto_pagado" },
            { "data": "saldo" },
            { "data": "monto_inicial" },
            { "data": "enero" },
            { "data": "cancel_enero" },
            { "data": "saldo_enero" },
            { "data": "fec_enero" },
            { "data": "letra_enero" },
            { "data": "febrero" },
            { "data": "cancel_febrero" },
            { "data": "saldo_febrero" },
            { "data": "fec_febrero" },
            { "data": "letra_febrero" },
            { "data": "marzo" },
            { "data": "cancel_marzo" },
            { "data": "saldo_marzo" },
            { "data": "fec_marzo" },
            { "data": "letra_marzo" },
            { "data": "abril" },
            { "data": "cancel_abril" },
            { "data": "saldo_abril" },
            { "data": "fec_abril" },
            { "data": "letra_abril" },
            { "data": "mayo" },
            { "data": "cancel_mayo" },
            { "data": "saldo_mayo" },
            { "data": "fec_mayo" },
            { "data": "letra_mayo" },
            { "data": "junio" },
            { "data": "cancel_junio" },
            { "data": "saldo_junio" },
            { "data": "fec_junio" },
            { "data": "letra_junio" },
            { "data": "julio" },
            { "data": "cancel_julio" },
            { "data": "saldo_julio" },
            { "data": "fec_julio" },
            { "data": "letra_julio" },
            { "data": "agosto" },
            { "data": "cancel_agosto" },
            { "data": "saldo_agosto" },
            { "data": "fec_agosto" },
            { "data": "letra_agosto" },
            { "data": "septiembre" },
            { "data": "cancel_septiembre" },
            { "data": "saldo_septiembre" },
            { "data": "fec_septiembre" },
            { "data": "letra_septiembre" },
            { "data": "octubre" },
            { "data": "cancel_octubre" },
            { "data": "saldo_octubre" },
            { "data": "fec_octubre" },
            { "data": "letra_octubre" },
            { "data": "noviembre" },
            { "data": "cancel_noviembre" },
            { "data": "saldo_noviembre" },
            { "data": "fec_noviembre" },
            { "data": "letra_noviembre" },
            { "data": "diciembre" },
            { "data": "cancel_diciembre" },
            { "data": "saldo_diciembre" },
            { "data": "fec_diciembre" },
            { "data": "letra_diciembre" }
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
            /*{
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> ',
                orientation: 'landscape',
                titleAttr: 'Exportar a PDF',
				pageSize: 'LEGAL', 
                className: 'btn btn-danger',
                messageTop: 'Seg\u00FAn fecha de Vencimiento'
            },*/
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> ',
                titleAttr: 'Imprimir',
                className: 'btn btn-info'
            },
        ]

    });

    LlenarTablaReporte2 = $('#TablaUsuarioReporte2').DataTable(options);
}















