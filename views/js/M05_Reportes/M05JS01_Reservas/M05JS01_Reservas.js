var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    

    ListarReservaciones();
    ListarReservacionesReporte();
    
	 $('#btnBuscarRegistro').click(function() {
        ListarReservaciones();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });
     
     $('#btnLimpiar').click(function() {
        $('#txtDocumentoFiltro').val(null).trigger('change');
        document.getElementById('bxFiltroEstado').selectedIndex = 0;
        document.getElementById('bxFiltroMotivo').selectedIndex = 0;
        document.getElementById('bxFiltroMedio').selectedIndex = 0;
        document.getElementById('bxFiltroLote').selectedIndex = 0;
        document.getElementById('bxFiltroVendedor').selectedIndex = 0;
        $("#txtDesdeFiltro").val("");
        $("#txtHastaFiltro").val("");
        
        ListarReservaciones();
        $('#TablaReservasReporte').DataTable().ajax.reload();
     });


}

function ListarReservaciones() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD01_Reservas/M05MD01_ListarReservas.php";
    var dato = {
        "ReturnListaActividades": true,
        "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
        "txtHastaFiltro": $("#txtHastaFiltro").val(),
        "bxFiltroEstado": $("#bxFiltroEstado").val(),
        "bxFiltroMotivo": $("#bxFiltroMotivo").val(),
        "bxFiltroMedio": $("#bxFiltroMedio").val(),
        "bxFiltroLote": $("#bxFiltroLote").val(),
        "bxFiltroVendedor": $("#bxFiltroVendedor").val(),
        "txtDocumentoFiltro": $("#txtDocumentoFiltro").val()
    };
    realizarJsonPost(url, dato, respuestaListarReservaciones, null, 10000, null);
}

function respuestaListarReservaciones(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarReservaciones(dato.data);
}

var getTablaBusquedaCabGenerado = null;
function LlenarTablaListarReservaciones(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaReservas').DataTable({
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
            { "data": "inicio" },
            { "data": "fin" },
            { "data": "documento" },
			{ "data": "cliente" },
            { "data": "zona" },
            { "data": "manzana" },
            { "data": "lote" },
            { "data": "area" },
            { "data": "casa" },
            { "data": "tipo_casa" },
			{
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.bloqueo == 0){
                        html = '<span class="badge etiqueta-js" style="background-color:'+ row.colorEstado +'">' + row.descEstado + '</span>';
                    }else{
                        html = '<span class="badge etiqueta-js" style="background-color:'+ row.colorEstado +'">' + row.descEstado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.colorMotivo +'">' + row.descMotivo + '</span> )';
                    }
                    return html;
                }
            },            
            { "data": "MedioCaptacion" },
            { "data": "importe_reserva" },
            { "data": "valor_terreno" },
            { "data": "valor_casa" },
            { "data": "total" },            
            { "data": "tipo_moneda" },            
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
            "url": "../../models/M05_Reportes/M05MD01_Reservas/M05MD01_ListarReservas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
                    "txtHastaFiltro": $("#txtHastaFiltro").val(),
                    "bxFiltroEstado": $("#bxFiltroEstado").val(),
                    "bxFiltroMotivo": $("#bxFiltroMotivo").val(),
					"bxFiltroMedio": $("#bxFiltroMedio").val(),
					"bxFiltroLote": $("#bxFiltroLote").val(),
					"bxFiltroVendedor": $("#bxFiltroVendedor").val(),
                    "txtDocumentoFiltro": $("#txtDocumentoFiltro").val()
					
                });
            }
        },
        "columns": [
            { "data": "inicio" },
            { "data": "fin" },
            { "data": "documento" },
			{ "data": "cliente" },	
            { "data": "zona" },
            { "data": "manzana" },
            { "data": "lote" },
            { "data": "area" },
            { "data": "casa" },
            { "data": "tipo_casa" },
			{ "data": "descEstado" },
            { "data": "descMotivo" },            		
            { "data": "MedioCaptacion" },
            { "data": "importe_reserva" },
            { "data": "valor_terreno" },
            { "data": "valor_casa" },
            { "data": "total" },
            { "data": "tipo_moneda" },            
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
