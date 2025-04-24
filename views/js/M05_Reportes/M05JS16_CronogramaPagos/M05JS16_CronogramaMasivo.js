var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {

    LlenarProyectos();
	LLenarZonas();

    ConsultarTotalesGenerales();

    BuscarInfoCronogramaPagos();
    InicializarAtributosTablaBusquedaCabCronogramaPagos();


    $('#btnBuscarFiltros').click(function() {
        ConsultarTotalesGenerales();
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
        ConsultarTotalesGenerales();
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

    $('#btnIrReporte').click(function() {
        exportarExcel();
    })

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

function IrCronograma() {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD16_CronogramaPagos/M05MD16_Masivo.php";
    var dato = {
        "btnIrCronograma": true,
        "iduser": $("#txtUSR").val()
    };
    realizarJsonPost(url, dato, CronogramaIr, null, 10000, null);
}

function CronogramaIr(dato){
    //console.log(dato);
    if(dato.status=="ok"){
        window.location.href = dato.ruta;
    }    
}



function ConsultarTotalesGenerales() {    
    bloquearPantalla("Consultando Totales...");
    var url = "../../models/M05_Reportes/M05MD16_CronogramaPagos/M05MD16_Masivo.php";
    var dato = {
        "btnConsultarTotalesGenerales": true,
        "txtFiltroDocumentoCP": $("#txtFiltroDocumentoCP").val(),
        "bxFiltroProyectoCP": $("#bxFiltroProyectoCP").val(),
        "bxFiltroZonaCP": $("#bxFiltroZonaCP").val(),
        "bxFiltroManzanaCP": $("#bxFiltroManzanaCP").val(),
        "bxFiltroLoteCP": $("#bxFiltroLoteCP").val(),
        "bxFiltroEstadoCP": $("#bxFiltroEstadoCP").val()
    };
    realizarJsonPost(url, dato, RespuestaConsultaTotalesGnrles, null, 10000, null);
}

function RespuestaConsultaTotalesGnrles(dato){
    desbloquearPantalla();
    //console.log(dato);
    if(dato.status=="ok"){
        
        document.querySelector('#TXT_CANT_VENTAS').innerHTML = 'CANTIDAD DE VENTAS : <span style="color: #354EA2; font-weight: bold;">'+dato.conteo+'</span> REG.';
        document.querySelector('#TXT_TOT_VENTAS').innerHTML = 'TOTAL VENTAS : <span style="color: #009205">$ </span><span style="color: #354EA2; font-weight: bold;">'+dato.total_venta+'</span>';
        document.querySelector('#TXT_TOT_INTERESES').innerHTML = 'TOTAL INTERESES : <span style="color: #009205">$ </span><span style="color: #354EA2; font-weight: bold;">'+dato.intereses+'</span>';

        document.querySelector('#TXT_TOT_FINANCIADO').innerHTML = 'TOTAL FINANCIADO : <span style="color: #009205">$ </span><span style="color: #354EA2; font-weight: bold;">'+dato.total_financiado+'</span>';
        document.querySelector('#TXT_TOT_PAGADO').innerHTML = 'TOTAL PAGADO : <span style="color: #009205">$ </span><span style="color: #354EA2; font-weight: bold;">'+dato.total_pagado+'</span>';
        document.querySelector('#TXT_TOT_PENDIENTE').innerHTML = 'TOTAL PENDIENTE : <span style="color: #009205">$ </span><span style="color: #354EA2; font-weight: bold;">'+dato.total_pendiente+'</span>';

    }    
}



function BuscarInfoCronogramaPagos() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD16_CronogramaPagos/M05MD16_Masivo.php";
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
            { "data": "total_venta" },
            { "data": "intereses" },
            { "data": "total_financiado" },
            { "data": "total_pagado" },  
            { "data": "total_pendiente" }, 
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color: '+row.color_estado+'; color: white; font-weight: bold;">' + row.estado + '</span>';
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
        ' <th>Fecha Vencimiento</th>' +
        ' <th>Letra</th>' +
        ' <th>Monto Letra</th>' +
        ' <th>Intereses</th>' +
        ' <th>Amortizacion</th>' +
        ' <th>Capital Vivo</th>' +
        ' <th>Total Pagado</th>' +
        ' <th>Estado</th>' +
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
    var url = "../../models/M05_Reportes/M05MD16_CronogramaPagos/M05MD16_Masivo.php";
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
            { "data": "letra" },
            { "data": "monto" },
            { "data": "intereses" },
            { "data": "amortizacion" },
			{ "data": "capital_vivo" },
			{ "data": "pagado" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.color +'; color: white; font-weight: bold;">' + row.descEstado + '</span>';
                    return html;
                } 
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });

}


//REPORTE EXCEL
/*
function exportarExcel(){
	let idPersona, txtFechaIni, txtFechaFin, txtEstado;
	let fechaInicio = convertStringToDate("/", $('#txtFechaIni').val());
    let fechaFin = convertStringToDate("/", $('#txtFechaFin').val());
	idPersona=($('#txtColaborador').val()==="" ? 0: $('#txtColaborador').val());
	txtFechaIni=($('#txtFechaIni').val()==="" ? 0: fechaInicio);
    txtFechaFin=($('#txtFechaFin').val()==="" ? 0: fechaFin);
    txtEstado=($('#txtEstado').val()==="" ? 0: $('#txtEstado').val());
	 window.location.href="<?php echo base_url('gestion/pagos_varios/exportarExcel'); ?>"+'/'+idPersona+'/'+txtFechaIni+'/'+txtFechaFin+'/'+txtEstado;
}*/

/*function exportarExcel(){   
    bloquearPantalla("Exportando..");
    var data = {
        "exportarExcel": true,
        "txtFiltroDocumentoCP": $("#txtFiltroDocumentoCP").val(),
        "bxFiltroProyectoCP": $("#bxFiltroProyectoCP").val(),
        "bxFiltroZonaCP": $("#bxFiltroZonaCP").val(),
        "bxFiltroManzanaCP": $("#bxFiltroManzanaCP").val(),
        "bxFiltroLoteCP": $("#bxFiltroLoteCP").val(),
        "bxFiltroEstadoCP": $("#bxFiltroEstadoCP").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD16_CronogramaPagos/M05MD16_Masivo.php",
        data: data,
        dataType: "json",
        success: function (dato) {   
            desbloquearPantalla();         
            //console.log(dato);
            if (dato.status == "ok") { 
                mensaje_alerta("\u00A1CORRECTO!", "Se ha exportado correctamente el reporte. Revisar la secci\u00F3n de descargas.", "success");    
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}*/

/*function exportarExcel(){
    bloquearPantalla("Exportando..");
    //spinner.show();
    var data = {
        "btnExportarExcel": true,
        "txtFiltroDocumentoCP": $("#txtFiltroDocumentoCP").val(),
        "bxFiltroProyectoCP": $("#bxFiltroProyectoCP").val(),
        "bxFiltroZonaCP": $("#bxFiltroZonaCP").val(),
        "bxFiltroManzanaCP": $("#bxFiltroManzanaCP").val(),
        "bxFiltroLoteCP": $("#bxFiltroLoteCP").val(),
        "bxFiltroEstadoCP": $("#bxFiltroEstadoCP").val()
    };
    $.ajax({
        type: "POST",
		url : "../../../controllers/ControllerExcel.php",
        data: data,    
        async: true,
        success: function (dato) {
		alert("hola");
            //console.log(dato);   
            desbloquearPantalla();
            mensaje_alerta("\u00A1CORRECTO!", "Se ha exportado correctamente el reporte. Revisar la secci\u00F3n de descargas.", "success");   
        },
        error: function(error) {
			alert("error");
            console.log(error);
        }
    });
}*/

	function exportarExcel() {
		//console.log("Exportando Excel..."); // 🧪 Verifica que entra
		bloquearPantalla("Exportando...");
		
		// Asignar los valores al formulario oculto
		$("#formExportarExcel input[name='txtFiltroDocumentoCP']").val($("#txtFiltroDocumentoCP").val());
		$("#formExportarExcel input[name='bxFiltroProyectoCP']").val($("#bxFiltroProyectoCP").val());
		$("#formExportarExcel input[name='bxFiltroZonaCP']").val($("#bxFiltroZonaCP").val());
		$("#formExportarExcel input[name='bxFiltroManzanaCP']").val($("#bxFiltroManzanaCP").val());
		$("#formExportarExcel input[name='bxFiltroLoteCP']").val($("#bxFiltroLoteCP").val());
		$("#formExportarExcel input[name='bxFiltroEstadoCP']").val($("#bxFiltroEstadoCP").val());

		setTimeout(function () {
			//console.log("Formulario enviado...");
			document.getElementById("formExportarExcel").submit();
			desbloquearPantalla();
		}, 500);
	}
	
	
	
	

