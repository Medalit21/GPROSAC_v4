var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
	//REPORTE 001
    ListarUsuarios();
    ListarUsuariosReporte();
    
    ListarUsuariosConteo();
    ListarUsuariosConteoReporte();
	
	$('#btnBuscarRegistro').click(function() {
        ListarUsuarios();
        $('#TablaUsuarioReporte').DataTable().ajax.reload();
        
        ListarUsuariosConteo();
        $('#TablaUsuarioConteoReporte').DataTable().ajax.reload();
    });
	
	$('#btnLimpiar').click(function() {
	    
        $('#txtdocumentoFiltro').val(null).trigger('change');
        ListarUsuarios();
        $('#TablaUsuarioReporte').DataTable().ajax.reload();
        
        ListarUsuariosConteo();
        $('#TablaUsuarioConteoReporte').DataTable().ajax.reload();
    });
}

function ListarUsuarios() {

    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD05_Vendedores/M05MD05_ListarVendedores.php";
    var dato = {
        "ReturnListaClientes": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
		"txtNombresFiltro": $("#txtNombresFiltro").val(),
		"txtApellidoFiltro": $("#txtApellidoFiltro").val(),
		"bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    realizarJsonPost(url, dato, respuestaListarUsuarios, null, 10000, null);
}

function respuestaListarUsuarios(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarUsuarios(dato.data);
}

var getTablaBusquedaCabGenerado = null;

function LlenarTablaListarUsuarios(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaUsuario').DataTable({
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
            { "data": "vendedor" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

function ListarUsuariosReporte() {
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
            "url": "../../models/M05_Reportes/M05MD05_Vendedores/M05MD05_ListarVendedores.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
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
            { "data": "vendedor" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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
            {
                extend: null,
                text: 'Cuadro de Importes de Ventas Mensuales',
                titleAttr: null,
                className: 'btn btn-secondary'
            }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }

    });

    tablaEmpresas = $('#TablaUsuarioReporte').DataTable(options);
}




function ListarUsuariosConteo() {

    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD05_Vendedores/M05MD05_ListarVendedores.php";
    var dato = {
        "ReturnListaClientesConteo": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
		"txtNombresFiltro": $("#txtNombresFiltro").val(),
		"txtApellidoFiltro": $("#txtApellidoFiltro").val(),
		"bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    realizarJsonPost(url, dato, respuestaListarUsuariosConteo, null, 10000, null);
}

function respuestaListarUsuariosConteo(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarUsuariosConteo(dato.data);
}

var getTablaBusquedaCabGeneradoConteo = null;

function LlenarTablaListarUsuariosConteo(datos) {
    if (getTablaBusquedaCabGeneradoConteo) {
        getTablaBusquedaCabGeneradoConteo.destroy();
        getTablaBusquedaCabGeneradoConteo = null;
    }

    getTablaBusquedaCabGeneradoConteo = $('#TablaUsuarioConteo').DataTable({
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
            { "data": "vendedor" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

function ListarUsuariosConteoReporte() {
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
            "url": "../../models/M05_Reportes/M05MD05_Vendedores/M05MD05_ListarVendedores.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                   "ReturnListaClientesConteo": true,
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
            		"txtNombresFiltro": $("#txtNombresFiltro").val(),
            		"txtApellidoFiltro": $("#txtApellidoFiltro").val(),
		            "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
					
                });
            }
        },
        "columns": [
            { "data": "vendedor" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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
            {
                extend: null,
                text: 'Cuadro de Cantidad de Ventas Mensuales',
                titleAttr: null,
                className: 'btn btn-secondary'
            }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }

    });

    tablaEmpresas = $('#TablaUsuarioConteoReporte').DataTable(options);
}
