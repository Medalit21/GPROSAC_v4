var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {

    LlenarProyectos();
	LLenarZonas();

    BuscarInfoCronogramaPagos();
    InicializarAtributosTablaBusquedaCabCronogramaPagos();


    $('#btnBuscarFiltros').click(function() {
        BuscarInfoCronogramaPagos();
    });  

    $('#btnLimpiarFiltros').click(function() {
        $('#txtFiltroDocumentoCP').val(null).trigger('change');
        document.getElementById('bxFiltroProyectoCP').selectedIndex = 0;
        document.getElementById('bxFiltroProyectoCP').selectedIndex = 0;
        document.getElementById('bxFiltroZonaCP').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaCP').selectedIndex = 0;
        document.getElementById('bxFiltroLoteCP').selectedIndex = 0;
        BuscarInfoCronogramaPagos();
    });  


    $('#bxFiltroProyectoCP').change(function () {
        $("#bxFiltroZonaCP").val("");
        $("#bxFiltroManzanaCP").val("");
        var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_ListarTipos.php';
        var datos = {
            "ListarZonas": true,
            "idproyecto": $('#bxFiltroProyectoCP').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaCP");
        document.getElementById('bxFiltroManzanaCP').selectedIndex = 0;
    });

    $('#bxFiltroZonaCP').change(function () {
        $("#bxFiltroManzanaCP").val("");
        $("#bxFiltroLoteCP").val("");
        var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaCP').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaCP");
        document.getElementById('bxFiltroLoteCP').selectedIndex = 0;
    });

   $('#bxFiltroManzanaCP').change(function () {
        $("#bxFiltroLoteCP").val("");
        var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaCP').val()
        };
        llenarCombo(url, datos, "bxFiltroLoteCP");
    });



}


function LlenarProyectos() {
    var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoCP");    
}

function LLenarZonas() {
    var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoCP').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaCP");
}



function BuscarInfoCronogramaPagos() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD17_LetrasDetalle/M05MD17_LetrasDetalle.php";
    var dato = {
        "btnInfoCronPagos": true,
        "txtFiltroDocumentoCP": $("#txtFiltroDocumentoCP").val(),
        "bxFiltroProyectoCP": $("#bxFiltroProyectoCP").val(),
        "bxFiltroZonaCP": $("#bxFiltroZonaCP").val(),
        "bxFiltroManzanaCP": $("#bxFiltroManzanaCP").val(),
        "bxFiltroLoteCP": $("#bxFiltroLoteCP").val(),
        "bxFiltroEstadoCP": $("#bxFiltroEstadoCP").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarInfoCronogramaPagos, null, 10000, null);
}

function respuestaBuscarInfoCronogramaPagos(dato) {
    console.log(dato.data);
    LlenarTablaActividadesGenerados(dato.data);
}

var getTablaBusquedaCabGenerado = null;
function LlenarTablaActividadesGenerados(datos) {
    desbloquearPantalla();
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaCronogramaPrincipal').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "1%"
            },
            {
                'aTargets': [1],
                "visible": false,
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
            { "data": "fecha" },
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "letra" },             
            { "data": "monto" },
            { "data": "intereses" },
            { "data": "amortizacion" },
            { "data": "capital_vivo" },  
            {
                "data": "descEstado",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color: '+row.color+'; color: white; font-weight: bold;">' + row.descEstado + '</span>';
                    return html;
                }
            },            
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
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            zeroRecords: "Cargando..."
        }
    });
    setTimeout(function() {
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }, 100);

}



function format(data) {

    return '<div class="table-child">' +
        '<table  class="table table-striped table-bordered  w-100" id="TablaCronogramaSecundaria" style="margin-top: -1px !important;">' +
        '<thead class="cabecera-child">' +
        '<tr>' +
        ' <th>Fecha Pago</th>' +
        ' <th>Tipo Moneda</th>' +
        ' <th>Monto Pagado</th>' +
        ' <th>Tipo Cambio</th>' +
        ' <th>Total</th>' +
        ' <th>Nro operaci\u00F3n</th>' +
        ' <th>Nro Boleta</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody>' +
        '</tbody>' +
        '</table>' +
        '</div>';
};


function InicializarAtributosTablaBusquedaCabCronogramaPagos() {
    
    $('#TablaCronogramaPrincipal').on('key-focus.dt', function(e, datatable, cell) {

        getTablaBusquedaCabGenerado.row(cell.index().row).select();
        var data = getTablaBusquedaCabGenerado.row(cell.index().row).data();
    });

    $('#TablaCronogramaPrincipal').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = getTablaBusquedaCabGenerado.cell(this).index().row;
        getTablaBusquedaCabGenerado.row(rowIdx).select();
    });
    $('#TablaCronogramaPrincipal tbody').on('click', 'td.details-control', function() {
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
            BuscarCronogramaPersonalGenerado(data.id);
        }
    });
}




function BuscarCronogramaPersonalGenerado(codigo) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD17_LetrasDetalle/M05MD17_LetrasDetalle.php";
    var dato = {
        "btnConsultarCronogramaIndividual": true,
        "Codigo": codigo
    };
    realizarJsonPost(url, dato, respuestaCronogramaPagosIndividual, null, 10000, null);
}

function respuestaCronogramaPagosIndividual(dato) {
    desbloquearPantalla();   
    console.log(dato.data);
    CartarTablaCronogramaPagosInd(dato.data);
}

var getTablaConogramaPagosIndv = null;
function CartarTablaCronogramaPagosInd(data) {
    //console.log(data);
    if (getTablaConogramaPagosIndv) {
        getTablaConogramaPagosIndv.destroy();
        getTablaConogramaPagosIndv = null;
    }

    getTablaConogramaPagosIndv = $('#TablaCronogramaSecundaria').DataTable({
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
            { "data": "fecha" },
            { "data": "tipo_moneda" },
            { "data": "importe" },
            { "data": "tipo_cambio" },
            { "data": "pagado" },
			{ "data": "nro_operacion" },
			{ "data": "boleta" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });

}