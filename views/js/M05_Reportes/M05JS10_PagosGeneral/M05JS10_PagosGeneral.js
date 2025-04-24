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
    
    CargarHojaResumen();
    InicializarAtributosPagos();
	//CargarHojaResumenReporte();

    
    $('#btnBuscarPago').click(function() {
        CargarHojaResumen();
		//$('#TablaHojaResumenReporte').DataTable().ajax.reload();
    });  

    $('#btnLimpiarPago').click(function() {

        document.getElementById('bxFiltroProyectoHR').selectedIndex = 0;
        document.getElementById('bxFiltroZonaHR').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaHR').selectedIndex = 0;
        document.getElementById('bxFiltroLoteHR').selectedIndex = 0;
		document.getElementById('bxFiltroEstadoHR').selectedIndex = 0;
		$('#txtFiltroDocumentoHR').val(null).trigger('change');
		Fechas();
        CargarHojaResumen();
    });
  
    $('#bxFiltroProyectoHR').change(function () {
        $("#bxFiltroZonaHR").val("");
        $("#bxFiltroManzanaHR").val("");
        var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
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
        var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaHR').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaHR");
        document.getElementById('bxFiltroLoteHR').selectedIndex = 0;
    });
   
    $('#bxFiltroManzanaHR').change(function () {
        $("#bxFiltroLoteHR").val("");
        var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaHR').val()
        };
        llenarCombo(url, datos, "bxFiltroLoteHR");
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

function CargarHojaResumen() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD10_PagosGeneral/M05MD10_PagosGeneral.php";
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
        columns: [{
                className: 'details-control',
                defaultContent: '',
                data: null,
                orderable: true
            },
            { "data": "id" },
            { "data": "cliente" },
            { "data": "proyecto" },
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
            { "data": "importe_pago" },
            { "data": "mora" },
			{ "data": "tipo_moneda" },
			{ "data": "tipo_cambio" },
            {
                "data": "estado_pago",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.color_estado_pago +'; color: white; font-weight: bold;">' + row.descEstado_pago + '</span>';
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


function format(data) {

    return '<div class="table-child">' +
        '<table  class="table table-striped table-bordered  w-100" id="TablaPagosDetalle" style="margin-top: -1px !important;">' +
        '<thead class="cabecera-child">' +
        '<tr>' +
        ' <th>Lote</th>' +
        ' <th>Letra</th>' +
        ' <th>Fecha Pago</th>' +
        ' <th>Estado</th>' +
        ' <th>Tipo Moneda</th>' +
        ' <th>Tipo Cambio</th>' +
        ' <th>Importe Pago</th>' +
        ' <th>Pagado</th>' +
        ' <th>Medio Pago</th>' +
        ' <th>Tipo Comprobante</th>' +
        ' <th>Nro Operacion</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody>' +
        '</tbody>' +
        '</table>' +
        '</div>';
};

function InicializarAtributosPagos() {

    $('#TablaHojaResumen').on('key-focus.dt', function(e, datatable, cell) {

        getTablaBusquedaCabGenerado.row(cell.index().row).select();
        var data = getTablaBusquedaCabGenerado.row(cell.index().row).data();
    });

    $('#TablaHojaResumen').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = getTablaBusquedaCabGenerado.cell(this).index().row;
        getTablaBusquedaCabGenerado.row(rowIdx).select();
    });
    $('#TablaHojaResumen tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = getTablaBusquedaCabGenerado.row(tr);
        var open = row.child.isShown();
        getTablaBusquedaCabGenerado.rows().every(function(rowIdx, tableLoop, rowLoop) {
            if (this.child.isShown()) {
                this.child.hide();
                $(this.node()).removeClass('shown');
            }
        });
        if (!open) {
            row.child(format(row.data())).show();
            tr.next('tr').addClass('details-row');
            tr.addClass('shown');
            var data = row.data();
            BuscarDetalleGenerado(data.id);
        }
    });
}

function BuscarDetalleGenerado(codigo) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD10_PagosGeneral/M05MD10_PagosGeneral.php";
    var dato = {
        "ReturnListaDetalle": true,
        "Codigo": codigo
    };
    realizarJsonPost(url, dato, respuestaBuscarMovimientoDetalleGenerado, null, 10000, null);
}

function respuestaBuscarMovimientoDetalleGenerado(dato) {
    desbloquearPantalla();
    CargarTablaBusquedaDetalleMovimientoGenerado(dato.data);
}

var getTablaBusquedaTareasGenerado = null;
function CargarTablaBusquedaDetalleMovimientoGenerado(data) {
   // console.log(data);
    if (getTablaBusquedaTareasGenerado) {
        getTablaBusquedaTareasGenerado.destroy();
        getTablaBusquedaTareasGenerado = null;
    }

    getTablaBusquedaTareasGenerado = $('#TablaPagosDetalle').DataTable({
        "data": data,
        "order": [
            [0, "desc"]
        ],
        "sDom": '<"dt-panelmenu clearfix"Tfr>t<"dt-panelfooter clearfix"ip>',
        "ordering": false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "paging": true,
        destroy: true,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        "columns": [
            { "data": "lote" },
            { "data": "letra" },
            { "data": "fecha_pago" },
            {
                "data": "estado_pago",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.color_estado_pago +'; color: white; font-weight: bold;">' + row.descEstado_pago + '</span>';
                    return html;
                } 
            },
			{ "data": "tipo_moneda" },
			{ "data": "tipo_cambio" },
			{ "data": "importe_pago" },
			{ "data": "importe_pago" },
			{ "data": "medio_pago" },
			{ "data": "tipo_comprobante" },
            { "data": "nro_operacion" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });

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
            "url": "../../models/M05_Reportes/M05MD10_PagosGeneral/M05MD10_PagosGeneral.php", // ajax source
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
            { "data": "cliente" },
            { "data": "proyecto" },
            { "data": "zona" },
            { "data": "lote" },
            { "data": "letra" },
            { "data": "fecha_vencimiento" },
            { "data": "descEstado_cuota" },
            { "data": "fecha_pago" },
            { "data": "descEstado_pago" },
            { "data": "mora" },
			{ "data": "tipo_moneda" },
			{ "data": "tipo_cambio" },
			{ "data": "importe_pago" },
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
        url: "../../models/M05_Reportes/M05MD10_PagosGeneral/M05MD10_PagosGeneral.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            
           $("#txtFiltroFechaInicio").val(dato.primer_dia);
           $("#txtFiltroFechaFin").val(dato.ultimo_dia);
			CargarHojaResumen();
          //$('#TablaHojaResumenReporte').DataTable().ajax.reload();
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}




