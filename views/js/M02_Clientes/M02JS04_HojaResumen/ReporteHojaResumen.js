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
    CargarHojaResumen();
    CargarDatosHR();
}




function CargarHojaResumen() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_Listar.php";
    var dato = {
        "ReturnListaHojaResumen": true,
        "txtFiltroDocumentoHR": '08246739',
        "bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
		"bxFiltroEstadoHR": $("#bxFiltroEstadoHR").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarHojaResumenGenerados, null, 10000, null);
}

function respuestaBuscarHojaResumenGenerados(dato) {
    desbloquearPantalla();
    console.log(dato);
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
        "pageLength": 1000,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        columns: [
            { "data": "fecha" },
            { "data": "letra" },
            { "data": "monto" },
            { "data": "soles" },
            { "data": "dolares" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.color +'; color: white; font-weight: bold;">' + row.descEstado + '</span>';
                    return html;
                } 
            },
			{ "data": "mora" },
            { "data": "nro_operacion" },
            { "data": "nro_boleta" }
            
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


function CargarDatosHR() {
    var data = {
        "CargarDatosHR": true,
        "txtFiltroDocumentoHR": '08246739',
        "bxFiltroLoteHR": $("#bxFiltroLoteHR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            console.log(dato);
				if( parseInt(dato.precio_pactado) > 0){
                    $("#TXT1").val(dato.dato);
                    $("#TXT2").val('CONTACTO: '+dato.contacto);
                    $("#TXT3").val('PRECIO PACTADO: $ '+dato.precio_pactado);
                    $("#TXT4").val('IMPORTE INICIAL: $ '+dato.importe_inicial);
                    $("#TXT5").val('IMPORTE FINANCIADO: $ '+dato.importe_financiado);
                    $("#TXT6").val('MONTO PAGADO: $ '+dato.monto_pagado);
                    $("#TXT7").val('SALDO INICIAL: $ '+dato.saldo_inicial);
                    $("#TXT8").val('INTERÉS: '+dato.interes);
                    $("#TXT9").val('MONTO PENDIENTE: $ '+dato.monto_pendiente);
                    $("#TXT10").val('LOTE: '+dato.lote);
                    $("#TXT11").val('TIPO CASA: '+dato.tipo_casa);
					$("#TXT12").val('FECHA ENTREGA CASA: '+dato.fecha_entrega);
					$("#TXT13").val('LETRAS PAGADAS: '+dato.cont_pagadas+' -  $ '+dato.letras_pagadas);
					$("#TXT14").val('LETRAS VENCIDAS: '+dato.cont_vencidas+' -  $ '+dato.letras_vencidas);
					$("#TXT15").val('LETRAS PENDIENTES: '+dato.cont_pendientes+' -  $ '+dato.letras_pendientes);
			
				}else{
				    Limpiar();
				}	
			
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}



