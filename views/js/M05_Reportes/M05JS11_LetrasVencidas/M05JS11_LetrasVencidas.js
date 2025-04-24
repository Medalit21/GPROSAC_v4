var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
    ValidarFechas();
    LLenarZona();
    ListarLetrasVencidas();
    ListarReservacionesReporte();
    
	 $('#btnBuscarRegistro').click(function() {
        ListarLetrasVencidas();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });
     
     $('#btnLimpiar').click(function() {
        document.getElementById('bxFiltroProyecto').selectedIndex = 0;
        document.getElementById('bxFiltroZona').selectedIndex = 0;
        document.getElementById('bxFiltroManzana').selectedIndex = 0;
        document.getElementById('bxFiltroLote').selectedIndex = 0;
        document.getElementById('cbxEstadoLetra').selectedIndex = 0;
        $('#cbxFiltroNumDocumento').val(null).trigger('change');
        ValidarFechas();
        ListarLetrasVencidas();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });
     
     
     $('#bxFiltroProyecto').change(function () {
        $("#bxFiltroZona").val("");
        $("#bxFiltroManzana").val("");
        $("#bxFiltroLote").val("");
        var url = '../../models/M05_Reportes/M05MD11_LetrasVencidas/M05MD11_procesos.php';
        var datos = {
            "ListarZonas": true,
            "idProyecto": $('#bxFiltroProyecto').val()
        }
        llenarCombo(url, datos, "bxFiltroZona");
        document.getElementById('bxFiltroManzana').selectedIndex = 0;
        document.getElementById('bxFiltroLote').selectedIndex = 0;
    });

    $('#bxFiltroZona').change(function () {
        $("#bxFiltroManzana").val("");
        $("#bxFiltroLote").val("");
        var url = '../../models/M05_Reportes/M05MD11_LetrasVencidas/M05MD11_procesos.php';
        var datos = {
            "ListarManzanas": true,
            "idZona": $('#bxFiltroZona').val()
        }
        llenarCombo(url, datos, "bxFiltroManzana");
        document.getElementById('bxFiltroLote').selectedIndex = 0;
    });

    $('#bxFiltroManzana').change(function () {
        $("#bxFiltroLote").val("");
        var url = '../../models/M05_Reportes/M05MD11_LetrasVencidas/M05MD11_procesos.php';
        var datos = {
            "ListarLotes": true,
            "idManzana": $('#bxFiltroManzana').val()
        }
        llenarCombo(url, datos, "bxFiltroLote");
    });

    $('#btnReporte').click(function() {
        IrReporte();
    });

}


function IrReporte(){
    
     var data = {
        "btnIrReporte": true,
        "_TXTUSR": $("#_TXTUSR").val(),
        "cbxFiltroNumDocumento": $("#cbxFiltroNumDocumento").val(),
        "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
        "txtHastaFiltro": $("#txtHastaFiltro").val(),
        "cbxEstadoLetra": $("#cbxEstadoLetra").val(),
        "bxFiltroProyecto": $("#bxFiltroProyecto").val(),
        "bxFiltroZona": $("#bxFiltroZona").val(),
        "bxFiltroManzana": $("#bxFiltroManzana").val(),
        "bxFiltroLote": $("#bxFiltroLote").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD11_LetrasVencidas/M05MD11_procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
		    if (dato.status == "ok") {
		        window.open('Reporte.php?Vsr='+dato.usr+'&d='+dato.documento+'&fi='+dato.fecini+'&ff='+dato.fecfin+'&ee='+dato.estado+'&p='+dato.idproyecto+'&z='+dato.idzona+'&m='+dato.idmanzana+'&l='+dato.idlote); 
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


function ValidarFechas(){
    var data = {
       "btnValidarFechas": true,
       "idProyecto": $('#bxFiltroProyecto').val()
   };
   $.ajax({
       type: "POST",
       url: "../../models/M05_Reportes/M05MD11_LetrasVencidas/M05MD11_procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               $("#txtDesdeFiltro").val(dato.primero);
               $("#txtHastaFiltro").val(dato.ultimo);
               ListarLetrasVencidas();
           } 

       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

/***********************LLENA ZONAS ********************** */

function LLenarZona() {
    var url = "../../models/M05_Reportes/M05MD11_LetrasVencidas/M05MD11_procesos.php";
    var datos = {
        "ReturnZonas": true,
        "idProyecto": $('#bxFiltroProyecto').val()
    }
    llenarCombo(url, datos, 'bxFiltroZona');
}




function ListarLetrasVencidas() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD11_LetrasVencidas/M05MD11_procesos.php";
    var dato = {
        "ReturnListaLetrasVencidas": true,
        "cbxFiltroNumDocumento": $("#cbxFiltroNumDocumento").val(),
        "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
        "txtHastaFiltro": $("#txtHastaFiltro").val(),
        "cbxEstadoLetra": $("#cbxEstadoLetra").val(),
        "bxFiltroProyecto": $("#bxFiltroProyecto").val(),
        "bxFiltroZona": $("#bxFiltroZona").val(),
        "bxFiltroManzana": $("#bxFiltroManzana").val(),
        "bxFiltroLote": $("#bxFiltroLote").val()
    };
    realizarJsonPost(url, dato, respuestaListarLetrasVencidas, null, 10000, null);
}

function respuestaListarLetrasVencidas(dato) {
    desbloquearPantalla();
    console.log(dato);
    $("#txtTotalLetras").val(dato.letras);
    $("#txtTotalMora").val(dato.mora);
    $("#txtTotalMonto").val(dato.total);
    LlenarTablaListarLetrasVencidas(dato.data);
}

var getTablaBusquedaLetrasVencidas = null;
function LlenarTablaListarLetrasVencidas(datos) {
    if (getTablaBusquedaLetrasVencidas) {
        getTablaBusquedaLetrasVencidas.destroy();
        getTablaBusquedaLetrasVencidas = null;
    }

    getTablaBusquedaLetrasVencidas = $('#TablaLetrasVencidas').DataTable({
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
            { "data": "cliente",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen"></div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot"></div>';
                        }else{
                            html = '<span class="text-center">' + row.cliente + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "fecha",
                "render": function(data, type, row) {
                   var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen"></div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot"></div>';
                        }else{
                            html = '<span class="text-center">' + row.fecha + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "lote",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen"></div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot"></div>';
                        }else{
                            html = '<span class="text-center">' + row.lote + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "letra",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="text-right report-tot-gen">TOTAL GENERAL</div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="text-right report-tot">TOTAL</div>';
                        }else{
                            html = '<span class="text-center">' + row.letra + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "monto",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="text-right report-tot-gen">' + row.monto + '</div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="text-right report-tot">' + row.monto + '</div>';
                        }else{
                            html = '<span class="text-center">' + row.monto + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "mora",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="text-right report-tot-gen">' + row.mora + '</div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="text-right report-tot">' + row.mora + '</div>';
                        }else{
                            html = '<span class="text-center">' + row.mora + '</span>';
                        }
                    }
                    return html;
                } 
            },
            { "data": "total_cancelado",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="text-right report-tot-gen">' + row.total_cancelado + '</div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="text-right report-tot">' + row.total_cancelado + '</div>';
                        }else{
                            html = '<span class="text-center"> - </span>';
                        }
                    }
                    return html;
                } 
            },
			{
                "data": "estado",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen"></div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot"></div>';
                        }else{
                            if(row.estado==='POR VENCER'){
                                html = '<span class="badge etiqueta-js" style="background-color: orange; color: white; font-weight: bold;">' + row.estado + '</span>';
                            }else{
                                html = '<span class="badge etiqueta-js" style="background-color: red; color: white; font-weight: bold;">' + row.estado + '</span>';
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
            "url": "../../models/M05_Reportes/M05MD11_LetrasVencidas/M05MD11_procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnListaLetrasVencidas": true,
                    "cbxFiltroNumDocumento": $("#cbxFiltroNumDocumento").val(),
                    "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
                    "txtHastaFiltro": $("#txtHastaFiltro").val(),
                    "cbxEstadoLetra": $("#cbxEstadoLetra").val(),
                    "bxFiltroProyecto": $("#bxFiltroProyecto").val(),
                    "bxFiltroZona": $("#bxFiltroZona").val(),
                    "bxFiltroManzana": $("#bxFiltroManzana").val(),
                    "bxFiltroLote": $("#bxFiltroLote").val()	
                });
            }
        },
        "columns": [
            { "data": "cliente",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen"></div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot"></div>';
                        }else{
                            html = '<span class="text-center">' + row.cliente + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "fecha",
                "render": function(data, type, row) {
                   var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen"></div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot"></div>';
                        }else{
                            html = '<span class="text-center">' + row.fecha + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "lote",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen"></div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot"></div>';
                        }else{
                            html = '<span class="text-center">' + row.lote + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "letra",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="text-right">TOTAL GENERAL</div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="text-right report-tot">TOTAL</div>';
                        }else{
                            html = '<span class="text-center">' + row.letra + '</span>';
                        }
                    }
                    return html;
                }
            },
            { "data": "monto" },
            { "data": "mora" },
            {
                "data": "total_cancelado",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen">' + row.total_cancelado + '</div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot">' + row.total_cancelado + '</div>';
                        }else{
                            html = '<span class="badge etiqueta-js" style="background-color: red; color: white; font-weight: bold;"> - </span>';
                        }
                    }
                    return html;
                }
            },
			{
                "data": "estado",
                "render": function(data, type, row) {
                    var html = null;
                    if(row.letra==null && row.cliente==null){
                        html = '<div class="report-tot-gen"></div>';
                    }else{
                        if(row.letra==null && row.cliente!=null){
                            html = '<div class="report-tot"></div>';
                        }else{
                            if(row.dato_estado=='1'){
                                html = '<span class="badge etiqueta-js" style="background-color: orange; color: white; font-weight: bold;">' + row.estado + '</span>';
                            }else{
                                html = '<span class="badge etiqueta-js" style="background-color: red; color: white; font-weight: bold;">' + row.estado + '</span>';
                            }
                        }
                    }
                    return html;
                }
            }
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

    tablaEmpresas = $('#TablaReservasReporte').DataTable(options);
}
