var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
	//REPORTE
    ListarReservacionesReporte();
    ValidarFechas();
    
    
     $('#btnBuscar').click(function() {
        ListarReservaciones();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });
     
     $('#btnLimpiar').click(function() {
         
        $('#txtDocumentoFiltro').val(null).trigger('change');
        document.getElementById('bxFiltroEstado').selectedIndex = 0;
        document.getElementById('bxFiltroTipoCasa').selectedIndex = 0;
        document.getElementById('bxFiltroLote').selectedIndex = 0;
        document.getElementById('bxFiltroVendedor').selectedIndex = 0;
        $("#txtDesdeFiltroV").val("");
        $("#txtHastaFiltroV").val("");
        ValidarFechas();
     });
	
}

function ValidarFechas(){
    
     var data = {
        "btnValidarFechas": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD02_Ventas/M05MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                $("#txtDesdeFiltroV").val(dato.primero);
                $("#txtHastaFiltroV").val(dato.ultimo);
                ListarReservaciones();
                $('#TablaReservasReporte').DataTable().ajax.reload();
            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });   
}



/********************** REPORTE 002********************************/

function ListarReservaciones() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD02_Ventas/M05MD02_ListarVentas.php";
    var dato = {
        "ReturnListaResevas": true,
        "txtDesdeFiltro": $("#txtDesdeFiltroV").val(),
        "txtHastaFiltro": $("#txtHastaFiltroV").val(),
        "bxFiltroEstado": $("#bxFiltroEstado").val(),
        "bxFiltroTipoCasa": $("#bxFiltroTipoCasa").val(),
        "bxFiltroLote": $("#bxFiltroLote").val(),
        "bxFiltroVendedor": $("#bxFiltroVendedor").val(),
        "txtDocumentoFiltro": $("#txtDocumentoFiltro").val()
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
            { "data": "manzana" },
            { "data": "lote" },
            { "data": "area_lote" },
            { "data": "casa" },
            { "data": "tipo_casa" },
			{
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.motivo == 0){
                        html = '<span class="badge etiqueta-js" style="background-color:'+ row.color_estado +'">' + row.descEstado + '</span>';
                    }else{
                        html = '<span class="badge etiqueta-js" style="background-color:'+ row.color_estado +'">' + row.descEstado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.color_motivo +';">' + row.descMotivo + '</span> )';
                    }
                    return html;
                }
            },
            { "data": "documento" },
            { "data": "cliente" },
            { "data": "tipo_moneda" },
            { "data": "cuota_inicial" },
            { "data": "precio_pactado" },
            { "data": "interes" },
            { "data": "total_lote" },
            { "data": "financiamiento" },
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
            "url": "../../models/M05_Reportes/M05MD02_Ventas/M05MD02_ListarVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnListaResevas": true,
                    "txtDesdeFiltro": $("#txtDesdeFiltroV").val(),
                    "txtHastaFiltro": $("#txtHastaFiltroV").val(),
                    "bxFiltroEstado": $("#bxFiltroEstado").val(),
					"bxFiltroTipoCasa": $("#bxFiltroTipoCasa").val(),
					"bxFiltroLote": $("#bxFiltroLote").val(),
					"bxFiltroVendedor": $("#bxFiltroVendedor").val(),
                    "txtDocumentoFiltro": $("#txtDocumentoFiltro").val()
                });
            }
        },
        "columns": [
            { "data": "fecha_venta" },
			{ "data": "zona" },
            { "data": "manzana" },
            { "data": "lote" },
            { "data": "area_lote" },
            { "data": "casa" },
            { "data": "tipo_casa" },
			{ "data": "descEstado" },
			{ "data": "documento" },
			{ "data": "cliente" },
            { "data": "tipo_moneda" },
            { "data": "cuota_inicial" },
            { "data": "precio_pactado" },
            { "data": "interes" },
            { "data": "total_lote" },
            { "data": "financiamiento" },
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


