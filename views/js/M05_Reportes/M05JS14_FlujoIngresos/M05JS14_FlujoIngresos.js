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
    //CargarTotalMesActual();
    
	 $('#btnBuscarRegistro').click(function() {
        ListarLetrasVencidas();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });
     
     $('#btnLimpiar').click(function() {
        document.getElementById('bxFiltroProyecto').selectedIndex = 0;
        document.getElementById('bxFiltroZona').selectedIndex = 0;
        document.getElementById('bxFiltroManzana').selectedIndex = 0;
        document.getElementById('bxFiltroLote').selectedIndex = 0;
        $('#cbxFiltroNumDocumento').val(null).trigger('change');
        ValidarFechas();
        ListarLetrasVencidas();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });
     
     
     $('#bxFiltroProyecto').change(function () {
        $("#bxFiltroZona").val("");
        $("#bxFiltroManzana").val("");
        $("#bxFiltroLote").val("");
        var url = '../../models/M05_Reportes/M05MD14_FlujoIngresos/M05MD14_FlujoIngresos.php';
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
        var url = '../../models/M05_Reportes/M05MD14_FlujoIngresos/M05MD14_FlujoIngresos.php';
        var datos = {
            "ListarManzanas": true,
            "idZona": $('#bxFiltroZona').val()
        }
        llenarCombo(url, datos, "bxFiltroManzana");
        document.getElementById('bxFiltroLote').selectedIndex = 0;
    });

    $('#bxFiltroManzana').change(function () {
        $("#bxFiltroLote").val("");
        var url = '../../models/M05_Reportes/M05MD14_FlujoIngresos/M05MD14_FlujoIngresos.php';
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
        "bxFiltroProyecto": $("#bxFiltroProyecto").val(),
        "bxFiltroZona": $("#bxFiltroZona").val(),
        "bxFiltroManzana": $("#bxFiltroManzana").val(),
        "bxFiltroLote": $("#bxFiltroLote").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD14_FlujoIngresos/M05MD14_FlujoIngresos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
		    if (dato.status == "ok") {
		        window.open('Reporte.php?Vsr='+dato.usr+'&d='+dato.documento+'&fi='+dato.fecini+'&ff='+dato.fecfin+'&p='+dato.idproyecto+'&z='+dato.idzona+'&m='+dato.idmanzana+'&l='+dato.idlote); 
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
       url: "../../models/M05_Reportes/M05MD14_FlujoIngresos/M05MD14_FlujoIngresos.php",
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
    var url = "../../models/M05_Reportes/M05MD14_FlujoIngresos/M05MD14_FlujoIngresos.php";
    var datos = {
        "ReturnZonas": true,
        "idProyecto": $('#bxFiltroProyecto').val()
    }
    llenarCombo(url, datos, 'bxFiltroZona');
}




function ListarLetrasVencidas() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD14_FlujoIngresos/M05MD14_FlujoIngresos.php";
    var dato = {
        "ReturnListaLetrasVencidas": true,
        "cbxPeriodo": $("#cbxPeriodo").val(),
    };
    realizarJsonPost(url, dato, respuestaListarLetrasVencidas, null, 10000, null);
}

function respuestaListarLetrasVencidas(dato) {
    desbloquearPantalla();
    
    LlenarTablaListarLetrasVencidas(dato.data);

    let mesActual = new Date().getMonth(); 

    if (dato.data && dato.data.length > mesActual) {
        let montoActual = dato.data[mesActual].por_cancelar;

        $("#txtTotalMesActual").val(" $ " + montoActual);
        $('#labelTotal').text("TOTAL DE CUOTAS PENDIENTES - " + dato.data[mesActual].descripcion);
    } else {
        $("#txtTotalMesActual").val(" $ 0.00");
        $('#labelTotal').text("Sin datos disponibles");
    }
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
        "pageLength": 12,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        columns: [ 
            {  "data": "descripcion" },
            { "data": "a_cancelar",
                "render": function(data, type, row) {
                    var html = ""; 
                    html = '<div class="text-center">'+row.a_cancelar+'</div>';
                    return html;
                }
            },
            { "data": "cancelado",
                "render": function(data, type, row) {
                    var html = ""; 
                    html = '<div class="text-center">'+row.cancelado+'</div>';
                    return html;
                }
                
            },
            { "data": "por_cancelar",
                "render": function(data, type, row) {
                    var html = ""; 
                    html = '<div class="text-center">'+row.por_cancelar+'</div>';
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
            "url": "../../models/M05_Reportes/M05MD14_FlujoIngresos/M05MD14_FlujoIngresos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnListaLetrasVencidas": true,
                    "cbxPeriodo": $("#cbxPeriodo").val(),	
                });
            }
        },
        "columns": [
            { "data": "descripcion"},
            { "data": "a_cancelar"},
            { "data": "cancelado"},
            { "data": "por_cancelar"}
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
