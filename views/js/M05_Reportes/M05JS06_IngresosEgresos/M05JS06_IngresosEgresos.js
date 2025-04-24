var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
    ValidarFechas();
	//REPORTE 001
    ListarIngresEgresCabec();
    ListarIngresEgresCabecReporte();
	
	//REPORTE 002
	/*ListarIngresEgresDetalle();
    ListarListarIngresEgresDetalleReporte();*/
	//REPORTE 003
	
	 $('#btnBuscarRegistro').click(function() {
        ListarIngresEgresCabec();
        $('#TablaIngresoReporte').DataTable().ajax.reload();
        NombreReporte();
     });
     
     $('#btnLimpiar').click(function() {
        document.getElementById('bxFiltroIngresEgres').selectedIndex = 0;
        $("#txtDesdeFiltro").val("");
        $("#txtHastaFiltro").val("");
        ValidarFechas();
        ListarIngresEgresCabec();
        $('#TablaIngresoReporte').DataTable().ajax.reload();
        NombreReporte();
     });


}

function NombreReporte(){
    var fecini = $("#txtDesdeFiltro").val();
    var fecfin = $("#txtHastaFiltro").val();
    var d_h = $("#bxFiltroIngresEgres").val();
    
    if(d_h == "H"){
        document.title = "GPROSAC - REPORTE DE INGRESOS (De "+fecini+" hasta "+fecfin+")";
    }else{
        document.title = "GPROSAC - REPORTE DE EGRESOS (De "+fecini+" hasta "+fecfin+")";
    }
}

//AGREGAR FECHA INICIO DE PROYECTO Y FECHA ACTUAL
function ValidarFechas(){
    var data = {
       "btnValidarFechas": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Listar.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               $("#txtDesdeFiltro").val(dato.primero);
               $("#txtHastaFiltro").val(dato.ultimo);
               NombreReporte();
           } 
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

function ListarIngresEgresCabec() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD06_IngresosEgresos/M05MD06_IngresosEgresosCabc.php";
    var dato = {
        "ReturnIngresEgresCabec": true,
        "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
        "txtHastaFiltro": $("#txtHastaFiltro").val(),
        "bxFiltroIngresEgres": $("#bxFiltroIngresEgres").val()
    };
    realizarJsonPost(url, dato, respuestaListarIngresEgresCabec, null, 10000, null);
}

function respuestaListarIngresEgresCabec(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarIngresEgresCabec(dato.data);
}

var getTablaBusquedaCabeceraGenerado = null;
function LlenarTablaListarIngresEgresCabec(datos) {
    if (getTablaBusquedaCabeceraGenerado) {
        getTablaBusquedaCabeceraGenerado.destroy();
        getTablaBusquedaCabeceraGenerado = null;
    }

    getTablaBusquedaCabeceraGenerado = $('#TablaIngreso').DataTable({
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
			{ "data": "sede" },
            { "data": "fecha" },
            { "data": "glosa" },
			{ "data": "total" },
			{ "data": "accion" },
            { "data": "cuenta_contable" },
            { "data": "operacion" },
            { "data": "numero" },
			{ "data": "identificador" },
            { "data": "tipo" },
            { "data": "serie" },
            { "data": "numero" },
            { "data": "moneda" },
            { "data": "tipocambio" },
            { "data": "TotalImporte" },
            { "data": "total" },
            { "data": "cuenta_contable" },
            { "data": "centro_costo" },
            { "data": "razonsocial" },
			{ "data": "dniruc" },
			{ "data": "tipo" },
            { "data": "serie" },
            { "data": "numero" },
			{ "data": "FechaR" },
            { "data": "debhab" }
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

function ListarIngresEgresCabecReporte() {

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
            "url": "../../models/M05_Reportes/M05MD06_IngresosEgresos/M05MD06_IngresosEgresosCabc.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnIngresEgresCabec": true,
                    "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
                    "txtHastaFiltro": $("#txtHastaFiltro").val(),
                    "bxFiltroIngresEgres": $("#bxFiltroIngresEgres").val()
                });
            }
        },
        "columns": [
            { "data": "sede" },
            { "data": "fecha" },
            { "data": "glosa" },
			{ "data": "total" },
			//{ "data": "accion" },
            { "data": "cuenta_contable" },
            { "data": "operacion" },
            { "data": "numero" },
			{ "data": "identificador" },
            { "data": "tipo" },
            { "data": "serie" },
            { "data": "numero" },
            { "data": "moneda" },
            { "data": "tipocambio" },
            { "data": "TotalImporte" },
            { "data": "total" },
            { "data": "cuenta_contable" },
            { "data": "centro_costo" },
            { "data": "razonsocial" },
			{ "data": "dniruc" },
			{ "data": "tipo" },
            { "data": "serie" },
            { "data": "numero" },
			{ "data": "FechaR" },
            { "data": "debhab" }
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

    tablaEmpresas = $('#TablaIngresoReporte').DataTable(options);
}


/********************** REPORTE 002********************************/
 $('#btnBuscarDetalle').click(function() {
        ListarIngresEgresDetalle();
        $('#TablaIngEgrDetalReporte').DataTable().ajax.reload();
     });
     
     $('#btnLimpiarDetalle').click(function() {
        document.getElementById('bxFiltroIngresEgres2').selectedIndex = 0;
        $("#txtDesdeFiltro2").val("");
        $("#txtHastaFiltro2").val("");
        
        ListarIngresEgresDetalle();
        $('#TablaIngEgrDetalReporte').DataTable().ajax.reload();
     });

function ListarIngresEgresDetalle() {
	
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD06_IngresosEgresos/M05MD06_IngresosEgresosDetal.php";
    var dato = {
        "ReturnListaResevas": true,
        "txtDesdeFiltro2": $("#txtDesdeFiltro2").val(),
        "txtHastaFiltro2": $("#txtHastaFiltro2").val(),
        "bxFiltroIngresEgres2": $("#bxFiltroIngresEgres2").val(),
    };
    realizarJsonPost(url, dato, respuestaListarDetalle, null, 10000, null);
}

function respuestaListarDetalle(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarDetalle(dato.data);
}

var getTablaBusquedaCabGeneradoListDetalle = null;

function LlenarTablaListarDetalle(datos) {
    if (getTablaBusquedaCabGeneradoListDetalle) {
        getTablaBusquedaCabGeneradoListDetalle.destroy();
        getTablaBusquedaCabGeneradoListDetalle = null;
    }

    getTablaBusquedaCabGeneradoListDetalle = $('#TablaIngEgrDetalle').DataTable({
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
            { "data": "identificador" },
            { "data": "tipo" },
            { "data": "serie" },
            { "data": "numero" },
            { "data": "total" },
            { "data": "cuenta_contable" },
            { "data": "centro_costo" },
            { "data": "razonsocial" },
			{ "data": "dniruc" },
			{ "data": "tipo" },
            { "data": "serie" },
            { "data": "numero" },
			{ "data": "FechaR" },
            { "data": "debhab" }
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



function ListarListarIngresEgresDetalleReporte() {
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
            "url": "../../models/M05_Reportes/M05MD06_IngresosEgresos/M05MD06_IngresosEgresosDetal.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "txtDesdeFiltro2": $("#txtDesdeFiltro2").val(),
                    "txtHastaFiltro2": $("#txtHastaFiltro2").val(),
                    "bxFiltroIngresEgres2": $("#bxFiltroIngresEgres2").val()
                });
            }
        },
        "columns": [
			{ "data": "identificador" },
            { "data": "tipo" },
            { "data": "serie" },
            { "data": "numero" },
            { "data": "total" },
            { "data": "cuenta_contable" },
            { "data": "centro_costo" },
            { "data": "razonsocial" },
			{ "data": "dniruc" },
			{ "data": "tipo" },
            { "data": "serie" },
            { "data": "numero" },
			{ "data": "FechaR" },
            { "data": "debhab" }
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

    tablaEmpresas = $('#TablaIngEgrDetalReporte').DataTable(options);
}
//REPORTE 002 JS










