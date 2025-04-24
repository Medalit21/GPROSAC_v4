var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {
    $('.modal').on("hidden.bs.modal", function(e) {
        if ($('.modal:visible').length) {
            $('body').addClass('modal-open');
        }
    });
    LlenarProyectos();
    LLenarZonas();
    
    CargarPagos();
	CargarHojaResumenReporte();
	
	TotalesAcumulados();
	Totales();
    
    $('#btnBuscarHR').click(function() {
        CargarPagos();
		$('#TablaHojaResumenReporte').DataTable().ajax.reload();
    });  

    $('#btnLimpiarHR').click(function() {
        document.getElementById('bxFiltroProyectoHR').selectedIndex = 0;
        document.getElementById('bxFiltroZonaHR').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaHR').selectedIndex = 0;
        document.getElementById('bxFiltroLoteHR').selectedIndex = 0;
		document.getElementById('bxFiltroEstadoHR').selectedIndex = 0;
		$('#txtFiltroDocumentoHR').val(null).trigger('change');
		Fechas();
        CargarPagos();
        $('#TablaHojaResumenReporte').DataTable().ajax.reload();
        
        
    });
  
    $('#bxFiltroProyectoHR').change(function () {
        $("#bxFiltroZonaHR").val("");
        $("#bxFiltroManzanaHR").val("");
        var url = '../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php';
        var datos = {
            "ListarZonas": true,
            "idproyecto": $('#bxFiltroProyectoHR').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaHR");
        document.getElementById('bxFiltroManzanaHR').selectedIndex = 0;
    });

    $('#bxFiltroZonaHR').change(function () {
        $("#bxFiltroManzanaHR").val("");
        $("#bxFiltroLoteHR").val("");
        var url = '../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaHR').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaHR");
        document.getElementById('bxFiltroLoteHR').selectedIndex = 0;
    });
   
    $('#bxFiltroManzanaHR').change(function () {
        $("#bxFiltroLoteHR").val("");
        var url = '../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaHR').val()
        };
        llenarCombo(url, datos, "bxFiltroLoteHR");
    });


    $('#btReporteExcel').click(function() {
        CargarPagosExcel();
    });  
    
}


function LlenarProyectos() {
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoHR");    
}

function LLenarZonas() {
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoHR').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaHR");
}

function CargarPagos() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php";
    var dato = {
        "ReturnLista": true,
        "txtFiltroFechaInicio": $("#txtFiltroFechaInicio").val(),
        "txtFiltroFechaFin": $("#txtFiltroFechaFin").val(),
		"txtFiltroDocumentoHR": $("#txtFiltroDocumentoHR").val(),
		"bxFiltroProyectoHR": $("#bxFiltroProyectoHR").val(),
		"bxFiltroZonaHR": $("#bxFiltroZonaHR").val(),
		"bxFiltroManzanaHR": $("#bxFiltroManzanaHR").val(),
		"bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
		"bxFiltroEstadoHR": $("#bxFiltroEstadoHR").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarHojaResumenGenerados, null, 10000, null);
}

function respuestaBuscarHojaResumenGenerados(dato) {
    desbloquearPantalla();
    //console.log(dato);
    document.title = "GPROSAC - REPORTE DE PAGOS "+dato.fec_hoy;
    LlenarTabalaHojaResumenGenerados(dato.data);
}

var getTablaBusquedaCabGenerado = null;
function LlenarTabalaHojaResumenGenerados(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaHojaResumen').DataTable({
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
            { "data": "documento" },
            { "data": "cliente" },
            { "data": "zona" },
            { "data": "lote" },
            { "data": "letra" },
            { "data": "fecha_vencimiento" },
            {
                "data": "estado_cuota",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.color_estado_cuota +'; color: white; font-weight: bold;">' + row.descEstado_cuota + '</span>';
                    return html;
                } 
            },
            { "data": "fecha_pago" },
            {
                "data": "estado_pago",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.color_estado_pago +'; color: white; font-weight: bold;">' + row.descEstado_pago + '</span>';
                    return html;
                } 
            },
            { "data": "mora" },
			{ "data": "tipo_moneda" },
			{ "data": "importe_pago" },
			{ "data": "tipo_cambio" },
			{ "data": "pagado" },
			{ "data": "medio_pago" },
			{ "data": "tipo_comprobante" },
            { "data": "boleta" },
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

function CargarHojaResumenReporte() {
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
            "url": "../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
					"ReturnLista": true,
                    "txtFiltroFechaInicio": $("#txtFiltroFechaInicio").val(),
                    "txtFiltroFechaFin": $("#txtFiltroFechaFin").val(),
            		"txtFiltroDocumentoHR": $("#txtFiltroDocumentoHR").val(),
            		"bxFiltroProyectoHR": $("#bxFiltroProyectoHR").val(),
            		"bxFiltroZonaHR": $("#bxFiltroZonaHR").val(),
            		"bxFiltroManzanaHR": $("#bxFiltroManzanaHR").val(),
            		"bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
            		"bxFiltroEstadoHR": $("#bxFiltroEstadoHR").val()                  
                });
            }
        },
        "columns": [
            { "data": "documento" },
            { "data": "cliente" },
            { "data": "zona" },
            { "data": "lote" },
            { "data": "letra" },
            { "data": "fecha_vencimiento" },
            { "data": "descEstado_cuota" },
            { "data": "fecha_pago" },
            { "data": "descEstado_pago" },
            { "data": "mora" },
			{ "data": "tipo_moneda" },
			{ "data": "importe_pago" },
			{ "data": "tipo_cambio" },
			{ "data": "pagado" },
			{ "data": "medio_pago" },
			{ "data": "tipo_comprobante" },
            { "data": "boleta" },
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

    $('#TablaHojaResumenReporte').DataTable(options);
}

function Fechas() {
    var data = {
        "Fechas": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            
           $("#txtFiltroFechaInicio").val(dato.primer_dia);
           $("#txtFiltroFechaFin").val(dato.ultimo_dia);
			CargarPagos();
          //$('#TablaHojaResumenReporte').DataTable().ajax.reload();
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function TotalesAcumulados() {
    var data = {
        "ConsultarTotalesAcumulados": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
			$("#TXT18").val(dato.pagados);
			$("#TXT19").val(dato.vencidos);
			$("#TXT20").val(dato.pendientes);
			$("#TXT21").val(dato.total);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function Totales() {
    var data = {
        "ConsultarTotales": true,
        "txtFiltroFechaInicio": $("#txtFiltroFechaInicio").val(),
        "txtFiltroFechaFin": $("#txtFiltroFechaFin").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
			$("#TXT23").val(dato.pagados);
			$("#TXT24").val(dato.vencidos);
			$("#TXT25").val(dato.pendientes);
			$("#TXT26").val(dato.total);
		
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}



function CargarPagosExcel() {
    var data = {
        "ReturnListaExcel": true,
        "txtFiltroFechaInicio": $("#txtFiltroFechaInicio").val(),
        "txtFiltroFechaFin": $("#txtFiltroFechaFin").val(),
		"txtFiltroDocumentoHR": $("#txtFiltroDocumentoHR").val(),
		"bxFiltroProyectoHR": $("#bxFiltroProyectoHR").val(),
		"bxFiltroZonaHR": $("#bxFiltroZonaHR").val(),
		"bxFiltroManzanaHR": $("#bxFiltroManzanaHR").val(),
		"bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
		"bxFiltroEstadoHR": $("#bxFiltroEstadoHR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD08_Pagos/M05MD08_Pagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            
         
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

