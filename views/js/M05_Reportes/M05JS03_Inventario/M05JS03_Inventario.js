var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
	LlenarProyectos();
	LLenarZonas();
	 graficas();
	//REPORTE 001
    ListarInventario();
    ListarReservacionesReporte();
   
	$('#btnBuscarInventario').click(function() {
        ListarInventario();
        $('#TablaInventarioReporte').DataTable().ajax.reload();
    });
     
    $('#btnLimpiarInventario').click(function() {
        document.getElementById('bxFiltroProyectoInventario').selectedIndex = 0;
        document.getElementById('bxFiltroZonaInventario').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaInventario').selectedIndex = 0;
        document.getElementById('bxFiltroLoteInventario').selectedIndex = 0;
        document.getElementById('bxFiltroEstadoInventario').selectedIndex = 0;
		document.getElementById('bxFiltroVendedorInventario').selectedIndex = 0;
        document.getElementById('bxFiltroMotivoInventario').selectedIndex = 0;
        ListarInventario();
        $('#TablaInventarioReporte').DataTable().ajax.reload();
    });
	   
	$('#bxFiltroProyectoInventario').change(function () {
        $("#bxFiltroZonaInventario").val("");
        $("#bxFiltroManzanaInventario").val("");
        var url = '../../models/M05_Reportes/M05MD03_Inventario/M05MD03_ListarTipos.php';
        var datos = {
            "ListarZonas": true,
            "idproyecto": $('#bxFiltroProyectoInventario').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaInventario");
        document.getElementById('bxFiltroManzanaInventario').selectedIndex = 0;
    });

    $('#bxFiltroZonaInventario').change(function () {
        $("#bxFiltroManzanaInventario").val("");
        $("#bxFiltroLoteInventario").val("");
        var url = '../../models/M05_Reportes/M05MD03_Inventario/M05MD03_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaInventario').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaInventario");
        document.getElementById('bxFiltroLoteInventario').selectedIndex = 0;
    });

    $('#bxFiltroManzanaInventario').change(function () {
        $("#bxFiltroLoteInventario").val("");
        var url = '../../models/M05_Reportes/M05MD03_Inventario/M05MD03_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaInventario').val()
        };
        llenarCombo(url, datos, "bxFiltroLoteInventario");
    });
	
	$('#bxFiltroEstadoInventario').change(function () {
      LLenarMotivos();
    });
}


function LlenarProyectos() {
    var url = '../../models/M05_Reportes/M05MD03_Inventario/M05MD03_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoInventario");    
}

function LLenarZonas() {
    var url = '../../models/M05_Reportes/M05MD03_Inventario/M05MD03_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoInventario').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaInventario");
}

function LLenarMotivos() {
    var url = '../../models/M05_Reportes/M05MD03_Inventario/M05MD03_Inventario.php';
    var datos = {
        "ListarMotivos": true,
        "bxFiltroEstadoInventario": $('#bxFiltroEstadoInventario').val()
    }
    llenarCombo(url, datos, "bxFiltroMotivoInventario");
}

function ListarInventario() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD03_Inventario/M05MD03_Inventario.php";
    var dato = {
        "btnListarLotesInventario": true,
        "bxFiltroProyectoInventario": $("#bxFiltroProyectoInventario").val(),
        "bxFiltroZonaInventario": $("#bxFiltroZonaInventario").val(),
        "bxFiltroManzanaInventario": $("#bxFiltroManzanaInventario").val(),
        "bxFiltroLoteInventario": $("#bxFiltroLoteInventario").val(),
        "bxFiltroEstadoInventario": $("#bxFiltroEstadoInventario").val(),
		"bxFiltroMotivoInventario": $("#bxFiltroMotivoInventario").val(),
        "bxFiltroVendedorInventario": $("#bxFiltroVendedorInventario").val()
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

    getTablaBusquedaCabGenerado = $('#TablaInventario').DataTable({
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
            { "data": "zona" },
            { "data": "manzana" },
            { "data": "lote" },
            { "data": "area",
                 "render": function (data, type, row) {
                return data+' <label>m<sup>2</sup></label>';
				} 
			},
            { "data": "tipo_casa" },
			{ "data": "valor_terreno" },
			{ "data": "valor_casa" },
			{ "data": "valor_venta" },
			{ "data": "tipo_moneda" },
			{
                "data": "Estado",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.bloqueo == 0){
                        html = '<span class="badge etiqueta-js" style="background-color:'+ row.colorEstado +';">' + row.descEstado + '</span>';
                    }else{
                        html = '<span class="badge etiqueta-js" style="background-color:'+ row.colorEstado +';">' + row.descEstado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.colorMotivo +';">' + row.descMotivo + '</span> )';
                    }
                    return html;
                }
            },
			{ "data": "disponibilidad" },
            { "data": "propietario" },
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
            "url": "../../models/M05_Reportes/M05MD03_Inventario/M05MD03_Inventario.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "btnListarLotesInventario": true,
					"bxFiltroProyectoInventario": $("#bxFiltroProyectoInventario").val(),
					"bxFiltroZonaInventario": $("#bxFiltroZonaInventario").val(),
					"bxFiltroManzanaInventario": $("#bxFiltroManzanaInventario").val(),
					"bxFiltroLoteInventario": $("#bxFiltroLoteInventario").val(),
					"bxFiltroEstadoInventario": $("#bxFiltroEstadoInventario").val(),
					"bxFiltroMotivoInventario": $("#bxFiltroMotivoInventario").val(),
					"bxFiltroVendedorInventario": $("#bxFiltroVendedorInventario").val()
					
                });
            }
        },
        "columns": [
            { "data": "fila" },
            { "data": "zona" },
            { "data": "manzana" },
            { "data": "lote" },
            { "data": "area" },
            { "data": "tipo_casa" },
			{ "data": "valor_terreno" },
			{ "data": "valor_casa" },
			{ "data": "valor_venta" },
			{ "data": "tipo_moneda" },
			{ "data": "estado_reporte" },
			{ "data": "disponibilidad" },
            { "data": "propietario" },
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
				orientation: 'landscape',
				fontSize: '7',
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

    tablaEmpresas = $('#TablaInventarioReporte').DataTable(options);
}

function graficas(){
    
    var disponible = parseInt($('#txtDisponible').val());
    var nodisponible = parseInt($('#txtNoDisponible').val());
    var libres =parseInt($('#txtLibre').val());
    var reservados = parseInt($('#txtReservados').val());
    var bloqueados = parseInt($('#txtBloqueados').val());
    var vendidos = parseInt($('#txtVendidos').val());
    var lotecasa = parseInt($('#txtLoteCasa').val());
    var lotesolo = parseInt($('#txtLoteSolo').val());
    var canjes = parseInt($('#txtCanje').val());
    
    Grafica1(disponible, nodisponible);
    Grafica2(libres, reservados, bloqueados, vendidos);
    Grafica3(lotecasa, lotesolo, canjes);
    
}

function Grafica1(disponible, nodisponible){
	
	Highcharts.chart('GraficaPie1', {
		chart: {
			type: 'pie',
			options3d: {
				enabled: true,
				alpha: 45,
				beta: 0
			}
		},
		title: {
			text: 'DISPONIBILIDAD DE LOTES'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		credits: {
            enabled: false
        },
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				depth: 35,
				dataLabels: {
					enabled: true,
					format: '{point.name}'
				}
			}
		},
		series: [{
			type: 'pie',
			name: 'Lotes',
			data: [
				{
					name: 'Si :'+disponible,
					y: disponible,
					sliced: true,
					selected: true,
					color: '#C6F9A9'
				},
				{
				   name: 'No :'+nodisponible, 
			       y: nodisponible,
				   color: '#FF4842'
				}
			]
		}]
	})
}

function Grafica2(libres, reservados, bloqueados, vendidos){
	
	Highcharts.chart('GraficaPie2', {
		chart: {
			type: 'pie',
			options3d: {
				enabled: true,
				alpha: 45,
				beta: 0
			}
		},
		title: {
			text: 'NO DISPONIBLES'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		credits: {
            enabled: false
        },
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				depth: 35,
				dataLabels: {
					enabled: true,
					format: '{point.name}'
				}
			}
		},
		series: [{
			type: 'pie',
			name: 'Lotes',
			data: [
				{ name: 'Reservados :'+reservados, 
				  y: reservados,
				  color: '#F8F9A9'
				},
				{ name: 'Vendidos :'+vendidos, 
				  y: vendidos,
				  color: '#BDE6FF'
				},
				{
					name: 'Bloqueados :'+bloqueados,
					y: bloqueados,
					sliced: true,
					selected: true,
					color: '#DE97FF'
				}
			]
		}]
	})
}

function Grafica3(lotecasa, lotesolo, canjes){
	
	Highcharts.chart('GraficaPie3', {
		chart: {
			type: 'pie',
			options3d: {
				enabled: true,
				alpha: 45,
				beta: 0
			}
		},
		title: {
			text: 'VENDIDOS '
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		credits: {
            enabled: false
        },
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				depth: 35,
				dataLabels: {
					enabled: true,
					format: '{point.name}'
				}
			}
		},
		series: [{
			type: 'pie',
			name: 'Lotes',
			data: [
				{ name: 'Lote :'+lotesolo, 
				  y: lotesolo,
				  color: '#BDE6FF'
				      
				},
				{ name: 'LoteCasa :'+lotecasa, 
				  y: lotecasa,
				  color: '#73C8FC'    
				},
				{
					name: 'Canje :'+canjes,
					y: canjes,
					sliced: true,
					selected: true,
				    color: '#DEAB7B'   
				}
			]
		}]
	})
}







