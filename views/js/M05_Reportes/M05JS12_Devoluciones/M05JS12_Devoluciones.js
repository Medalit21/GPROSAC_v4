var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
	//REPORTE
	ListarReservaciones();
    ListarReservacionesReporte();
    
     $('#btnBuscar').click(function() {
        ListarReservaciones();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });
     
     $('#btnLimpiar').click(function() {
        $('#txtdocumentoFiltro').val(null).trigger('change');
        document.getElementById('bxFiltroEstado').selectedIndex = 0;
        document.getElementById('bxFiltroTipoCasa').selectedIndex = 0;
        document.getElementById('bxFiltroLote').selectedIndex = 0;
        document.getElementById('bxFiltroVendedor').selectedIndex = 0;
        $("#txtDesdeFiltro").val("");
        $("#txtHastaFiltro").val("");
        
        ListarReservaciones();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });
	
}

/********************** REPORTE 002********************************/



function ListarReservaciones() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD12_Devoluciones/M05MD12_ListarDevoluciones.php";
    var dato = {
        "ReturnListaResevas": true,
        "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
        "txtHastaFiltro": $("#txtHastaFiltro").val(),
        "bxFiltroEstado": $("#bxFiltroEstado").val(),
        "bxFiltroTipoCasa": $("#bxFiltroTipoCasa").val(),
        "bxFiltroLote": $("#bxFiltroLote").val(),
        "bxFiltroVendedor": $("#bxFiltroVendedor").val(),
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val()
    };
    realizarJsonPost(url, dato, respuestaListarReportes, null, 10000, null);
}

function respuestaListarReportes(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarReportes(dato.data);
}

var getTablaBusquedaCabGeneradoListReport = null;

function LlenarTablaListarReportes(datos) {
    if (getTablaBusquedaCabGeneradoListReport) {
        getTablaBusquedaCabGeneradoListReport.destroy();
        getTablaBusquedaCabGeneradoListReport = null;
    }

    getTablaBusquedaCabGeneradoListReport = $('#TablaReservas').DataTable({
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
            { "data": "fecha_venta" },
            { "data": "zona" },
            { "data": "resumen_lote" },
            { "data": "area_lote" },
            { "data": "casa" },
            { "data": "tipo_casa" },
			{
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.motivo == 0){
                        html = '<span class="badge etiqueta-js" style="background-color:'+ row.color_estado +'">' + row.descEstado + '</span> - <span class="badge etiqueta-js" style="background-color: red; color: white"> DEVUELTO </span>';
                    }else{
                        html = '<span class="badge etiqueta-js" style="background-color:'+ row.color_estado +'">' + row.descEstado + '</span> ( <span class="badge" style="background-color:'+ row.color_motivo +'; color: black; font-weight: bold;">' + row.descMotivo + '</span> ) - <span class="badge etiqueta-js" style="background-color: red; color: white"> DEVUELTO </span>';
                    }
                    return html;
                }
            },
            { "data": "cliente" },
            { "data": "tipo_moneda" },			
            { "data": "cuota_inicial" },
            { "data": "valor_terreno" },
            { "data": "valor_casa" },
            { "data": "total" },            
            { "data": "vendedor" }
            
            
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

//REPORTE 002 JS

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
            "url": "../../models/M05_Reportes/M05MD12_Devoluciones/M05MD12_ListarDevoluciones.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
                    "txtHastaFiltro": $("#txtHastaFiltro").val(),
                    "bxFiltroEstado": $("#bxFiltroEstado").val(),
					"bxFiltroTipoCasa": $("#bxFiltroTipoCasa").val(),
					"bxFiltroLote": $("#bxFiltroLote").val(),
					"bxFiltroVendedor": $("#bxFiltroVendedor").val(),
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val()
                });
            }
        },
        "columns": [
            { "data": "fecha_venta" },
			{ "data": "zona" },
            { "data": "resumen_lote" },
            { "data": "area_lote" },
            { "data": "casa" },
            { "data": "tipo_casa" },
			{ "data": "descEstado" },			
            { "data": "cliente" },
            { "data": "tipo_moneda" },
            { "data": "cuota_inicial" },
            { "data": "valor_terreno" },
            { "data": "valor_casa" },
            { "data": "total" },
            { "data": "vendedor" }
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
//REPORTE 002 JS


