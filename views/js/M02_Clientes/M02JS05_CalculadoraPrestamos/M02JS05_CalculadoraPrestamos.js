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
	
    CargarCalculo();
   // CargarCalculoReporte();
	
	 $('#btnBuscarCalcular').click(function() {
        btnCalcular();
    });  

	
	$('#btnLimpiarCalculo').click(function() {
		$("#txtTEA").val("");
		$("#txtTEM").val("");
		$("#txtCuotas").val("");
		$("#txtPrecioVenta").val("");
		$("#txtCuotaInicial").val("");
        btnLimpiar();
        CargarCalculo();
    });

    
}




/*****************************************LLENAR TABLA LISTA********************************************* */
function CargarCalculo() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M02_Clientes/M02MD05_CalculadoraPrestamos/M02MD05_ListarCalculo.php";
    var dato = {
        "ReturnListaCalculo": true
    };
    realizarJsonPost(url, dato, respuestaBuscarCronogramaGenerados, null, 10000, null);
}

function respuestaBuscarCronogramaGenerados(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaCronogramaGenerados(dato.data);
}

var getTablaBusquedaCabGeneradoo = null;

function LlenarTabalaCronogramaGenerados(datos) {
    if (getTablaBusquedaCabGeneradoo) {
        getTablaBusquedaCabGeneradoo.destroy();
        getTablaBusquedaCabGeneradoo = null;
    }

    getTablaBusquedaCabGeneradoo = $('#TablaCalculadora').DataTable({
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
            { "data": "nro_mes" },
            { "data": "cuota" },
            { "data": "intereses" },
            { "data": "amortizacion" },
            { "data": "capital_vivo" },
			{ "data": "capital_amortizado" },
			{ "data": "total_pagado" }
            
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

function CargarCalculoReporte() {
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
            "url": "./../models/M02_Clientes/M02MD05_CalculadoraPrestamos/M02MD05_ListarCalculo.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {  
					"ReturnListaCalculo": true		
                });
            }
        },
        "columns": [
            { "data": "nro_mes" },
            { "data": "cuota" },
            { "data": "intereses" },
            { "data": "amortizacion" },
            { "data": "capital_vivo" },
			{ "data": "capita_amortizado" },
			{ "data": "total_pagado" }

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

    $('#TablaCalculadoraReporte').DataTable(options);
}



function btnCalcular() {
    var data = {
        "btnCalcular": true,
        "txtTEA": $("#txtTEA").val(),
        "txtTEM": $("#txtTEM").val(),
        "txtCuotas": $("#txtCuotas").val(),
        "txtPrecioVenta": $("#txtPrecioVenta").val(),
        "txtCuotaInicial": $("#txtCuotaInicial").val()	
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD05_CalculadoraPrestamos/M02MD05_ListarCalculo.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            if(dato.status=="ok"){
    			CargarCalculo();
                mensaje_alerta("Correcto!", dato.data, "success");
			}else{
			    mensaje_alerta("Error!", dato.data, "info");
			}
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}


function btnLimpiar() {
    var data = {
        "LimpiarTabla": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD05_CalculadoraPrestamos/M02MD05_ListarCalculo.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            
			CargarCalculo();
         
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}




