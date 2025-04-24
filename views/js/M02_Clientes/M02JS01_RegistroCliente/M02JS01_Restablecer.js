$(document).ready(function() {
    Control();
});

function Control() {
    CargarGrillaBusquedaClienteReportes();
    CargarGrillaBusquedaClienteListaPaginado();
    $('#btnBuscar').click(function() {
        $('#tableRegistroReportes').DataTable().ajax.reload();
        $('#tablaDatosCliente').DataTable().ajax.reload();
    });
    $('#btnTodos').click(function() {
        LimpiarFiltro();
        $('#tableRegistroReportes').DataTable().ajax.reload();
        $('#tablaDatosCliente').DataTable().ajax.reload();
    });
}

/*********************LIMPIAR FILTROS******************* */
function LimpiarFiltro() {
    $("#txtdocumentoFiltro").val("");
    $("#txtNombreApellidoFiltro").val("");
}

/*****************************************LLENAR TABLA REPORTE********************************************* */
function CargarGrillaBusquedaClienteReportes() {
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
            "url": "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnClienteEnBajaListaPaginada": true,
                    "txtDniFiltro": $("#txtdocumentoFiltro").val(),
                    "txtApeNomFiltro": $("#txtNombreApellidoFiltro").val()
                });
            }
        },
        "columns": [
            { "data": "documento" },
            { "data": "apellidos" },
            { "data": "nombres" },
            { "data": "fechaNacimiento" },
            { "data": "email" },
            { "data": "celularTelefono" },
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

    $('#tableRegistroReportes').DataTable(options);
}

/*****************************************LLENAR TABLA LISTA********************************************* */
function CargarGrillaBusquedaClienteListaPaginado() {
    var options = $.extend(true, {}, defaults, {
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
        "order": [
            [0, "desc"]
        ],
        "iDisplayLength": 5,
        "aLengthMenu": [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ],
        "sDom": '<"dt-panelmenu clearfix"Tfr>t<"dt-panelfooter clearfix"ip>',
        "tableTools": {
            "aButtons": []
        },
        "bFilter": false,
        "bSort": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 20, 50, 100, 150],
            [10, 20, 50, 100, 150] // change per page values here
        ],
        "pageLength": 10, // default record count per page,
        "ajax": {
            "url": "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnClienteEnBajaListaPaginada": true,
                    "txtDniFiltro": $("#txtdocumentoFiltro").val(),
                    "txtApeNomFiltro": $("#txtNombreApellidoFiltro").val()
                });
            }
        },
        "columns": [{
                "data": "id",
                "render": function(data, type, row) {
                    return '<button class="btn btn-dar-alta-action"   onclick="ConfirmacionRestablecerTrabajador(\'' + data + '\')"><i class="fas fa-arrow-alt-circle-up"></i></button>';
                }
            },
            { "data": "documentoCadena" },
            { "data": "apellidos" },
            { "data": "nombres" },
            { "data": "fechaNacimiento" },
            { "data": "email" },
            { "data": "celularTelefono" },
            {
                "data": "",
                "render": function(data, type, row) {
                    return "<span class='badge badge-danger'> En Baja </span>";
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },

    });

    $('#tablaDatosCliente').DataTable(options);
}

/*************************DAR DE ALTA AL REGISTRO PERSONAL******************************* */

function ConfirmacionRestablecerTrabajador(id) {
    mensaje_condicionalUNO("\u00BFEst\u00E1 seguro de Restablecer?", "Al confirmar se proceder\u00E1 dar de Alta al registro, el cual se visualizar\u00E1 en la tabla de registros activos.", DarDeAlta, CancelRestablecerTrabajador, id);
}

function CancelRestablecerTrabajador() {
    return;
}

function DarDeAlta(id) {
    bloquearPantalla("Procesando...");
    var url = "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php";
    var dato = {
        "ReturnDarAltaRegCliente": true,
        "id": id
    };
    realizarJsonPost(url, dato, respuestaDarDeAlta, null, 10000, null);
}

function respuestaDarDeAlta(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        $('#tableRegistroReportes').DataTable().ajax.reload();
        $('#tablaDatosCliente').DataTable().ajax.reload();
        setTimeout(function() {
            mensaje_alerta("\u00A1Reestablecido!", dato.data, "success");
        }, 100);
        return;
    } else {
        setTimeout(function() {
            mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
        }, 100);
    }
}