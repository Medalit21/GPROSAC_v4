var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
    LlenarProyectos();
    LLenarZonas();
	//REPORTE 001
    ListarClientes();
    ListarClientesReporte();
	
	$('#btnBuscarRegistro').click(function() {
        ListarClientes();
        $('#TablaClienteReporte').DataTable().ajax.reload();
    });
	
	$('#btnLimpiar').click(function() {
        $('#txtdocumentoFiltro').val(null).trigger('change');
        $("#txtNombresFiltro").val("");
        $("#txtApellidoFiltro").val("");
        document.getElementById('bxFiltroProyectoPropietarios').selectedIndex = 0;
        document.getElementById('bxFiltroZonaPropietarios').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaPropietarios').selectedIndex = 0;
        document.getElementById('bxFiltroLotePropietarios').selectedIndex = 0;
        document.getElementById('bxFiltroEstadoPropietarios').selectedIndex = 0;
        
        ListarClientes();
        $('#TablaClienteReporte').DataTable().ajax.reload();
    });
    
     $('#bxFiltroProyectoPropietarios').change(function () {
        $("#bxFiltroZonaPropietarios").val("");
        $("#bxFiltroManzanaPropietarios").val("");
        var url = '../../models/M05_Reportes/M05MD04_Clientes/M05MD04_ListarTipos.php';
        var datos = {
            "ListarZonas": true,
            "idproyecto": $('#bxFiltroProyectoPropietarios').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaPropietarios");
        document.getElementById('bxFiltroManzanaPropietarios').selectedIndex = 0;
    });

    $('#bxFiltroZonaPropietarios').change(function () {
        $("#bxFiltroManzanaPropietarios").val("");
        $("#bxFiltroLotePropietarios").val("");
        var url = '../../models/M05_Reportes/M05MD04_Clientes/M05MD04_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaPropietarios').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaPropietarios");
        document.getElementById('bxFiltroLotePropietarios').selectedIndex = 0;
    });

    $('#bxFiltroManzanaPropietarios').change(function () {
        $("#bxFiltroLotePropietarios").val("");
        var url = '../../models/M05_Reportes/M05MD04_Clientes/M05MD04_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaPropietarios').val()
        };
        llenarCombo(url, datos, "bxFiltroLotePropietarios");
    });
}

function LlenarProyectos() {
    var url = '../../models/M05_Reportes/M05MD04_Clientes/M05MD04_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoPropietarios");    
}

function LLenarZonas() {
    var url = '../../models/M05_Reportes/M05MD04_Clientes/M05MD04_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoPropietarios').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaPropietarios");
}

function ListarClientes() {

    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD04_Clientes/M05MD04_ListarClientes.php";
    var dato = {
        "ReturnListaClientes": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
		"txtNombresFiltro": $("#txtNombresFiltro").val(),
		"txtApellidoFiltro": $("#txtApellidoFiltro").val(),
		"bxFiltroProyectoPropietarios": $("#bxFiltroProyectoPropietarios").val(),
		"bxFiltroZonaPropietarios": $("#bxFiltroZonaPropietarios").val(),
		"bxFiltroManzanaPropietarios": $("#bxFiltroManzanaPropietarios").val(),
		"bxFiltroLotePropietarios": $("#bxFiltroLotePropietarios").val(),
		"bxFiltroEstadoPropietarios": $("#bxFiltroEstadoPropietarios").val()
    };
    realizarJsonPost(url, dato, respuestaListarClientes, null, 10000, null);
}

function respuestaListarClientes(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarClientes(dato.data);
}

var getTablaBusquedaCabGenerado = null;

function LlenarTablaListarClientes(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaCliente').DataTable({
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
            { "data": "fila" },
            { "data": "documento" },
            { "data": "nombres" },
            { "data": "apellidos" },
            { "data": "lote" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.motivo == 0){
                        if(row.devolucion == '1'){
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color1 +'">' + row.descEstado + '</span> - <span class="badge etiqueta-js" style="background-color: red; color: white; font-weight: bold;"> DEVUELTO </span>';
                        }else{
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color1 +'">' + row.descEstado + '</span>';
                        }
                    }else{
                        if(row.devolucion == '1'){
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color1 +'">' + row.descEstado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.color2 +'">' + row.descMotivo + '</span> ) - <span class="badge etiqueta-js" style="background-color: red; color: white; font-weight: bold;"> DEVUELTO </span>';
                        }else{
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color1 +'">' + row.descEstado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.color2 +'">' + row.descMotivo + '</span> )';
                        }
                            
                    }
                    return html;
                }
            },
            { "data": "celular" },
            { "data": "email" },
            { "data": "nacionalidad"}
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

function ListarClientesReporte() {
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
            "url": "../../models/M05_Reportes/M05MD04_Clientes/M05MD04_ListarClientes.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                     "ReturnListaClientes": true,
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
            		"txtNombresFiltro": $("#txtNombresFiltro").val(),
            		"txtApellidoFiltro": $("#txtApellidoFiltro").val(),
            		"bxFiltroProyectoPropietarios": $("#bxFiltroProyectoPropietarios").val(),
            		"bxFiltroZonaPropietarios": $("#bxFiltroZonaPropietarios").val(),
            		"bxFiltroManzanaPropietarios": $("#bxFiltroManzanaPropietarios").val(),
            		"bxFiltroLotePropietarios": $("#bxFiltroLotePropietarios").val(),
            		"bxFiltroEstadoPropietarios": $("#bxFiltroEstadoPropietarios").val()
                });
            }
        },
        "columns": [
            { "data": "fila" },
            { "data": "documento" },
            { "data": "nombres" },
            { "data": "apellidos" },
            { "data": "lote" },
            { "data": "estado_reporte" },
            { "data": "celular" },
            { "data": "email" },
            { "data": "nacionalidad"}
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
                titleAttr: 'Exportar a PDF',
				pageSize: 'LEGAL', 
				orientation: 'landscape',
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

    tablaEmpresas = $('#TablaClienteReporte').DataTable(options);
}
