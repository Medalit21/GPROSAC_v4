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
	CargarDatosHR();
    CargarHojaResumen();
	CargarHojaResumenReporte();
	
    
    $('#btnBuscarHR').click(function() {
        CargarHojaResumen();
		CargarDatosHR();
		$('#TablaHojaResumenReporte').DataTable().ajax.reload();
    });  

    $('#btnLimpiarHR').click(function() {
        Limpiar();
        document.getElementById('bxFiltroProyectoHR').selectedIndex = 0;
        document.getElementById('bxFiltroZonaHR').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaHR').selectedIndex = 0;
        document.getElementById('bxFiltroLoteHR').selectedIndex = 0;
		document.getElementById('bxFiltroEstadoHR').selectedIndex = 0;
		//$("#txtFiltroDocumentoHR").val("");
		$('#txtFiltroDocumentoHR').val(null).trigger('change');
        CargarHojaResumen();
        CargarDatosHR();
        $('#TablaHojaResumenReporte').DataTable().ajax.reload();
        
        $("#TXT13").val('LETRAS PAGADAS: 0 -  $ 0.00');
		$("#TXT14").val('LETRAS VENCIDAS: 0 -  $ 0.00');
		$("#TXT15").val('LETRAS PENDIENTES: 0 -  $ 0.00');
        
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
    
     $('#btnExportarPdf').click(function() {
        IrReportePDF();
    });  
    
       $('#btnExportarPdf2').click(function() {
        IrReportePDF2();
    });  
    
    
    $('#txtFiltroDocumentoHR').change(function () {
        LlenarFiltroPropiedades();
    });

    
}

function IrReportePDF(){
    
     var data = {
        "parametros": true,
        "txtFiltroDocumentoHR": $("#txtFiltroDocumentoHR").val(),
        "bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
		"cbxPropiedadesCliente": $("#cbxPropiedadesCliente").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
		    if (dato.status == "ok") {
		        window.open('ReporteHojaResumen.php?Dto='+dato.param+'&lgm='+dato.idlote+'&vnt='+dato.idventa); 
		    }else{
		        mensaje_alerta("\u00A1IMPORTANTE!", "No se encontr\u00F3 informaci\u00F3n para los datos ingresados", "info");
		    }
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
    
}

function LlenarFiltroPropiedades(){
    $("#cbxPropiedadesCliente").val("");
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarPropiedadesCliente": true,
        "documento": $('#txtFiltroDocumentoHR').val()
    };
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: datos,
        success: function(dato) {
            var resutado = dato.data;
            if(dato.status=="ok"){
                $("#PanelPropiedades").show();
                $('#cbxPropiedadesCliente')
                    .find('option')
                    .remove()
                    .end();
    
                for (i = 0; i < resutado.length; i++) {
                    var option = resutado[i];
                    $('#cbxPropiedadesCliente')
                        .append('<option value="' + option.valor + '">' + option.texto + '</option>');
                }
            }else{
                $("#PanelPropiedades").hide();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            /*if (fError) {
                fError(jqXHR, textStatus, errorThrown);
            }*/

        }
    });
    
}

function LlenarFiltrosAutomaticamente(){
    
    //PROYECTO
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarProyectosAuto": true,
        "documento": $('#txtFiltroDocumentoHR').val(),
        "idventa": $('#cbxPropiedadesCliente').val()
    }
    llenarComboSelecionarInterno(url, datos, "bxFiltroProyectoHR");
    
    //ZONA
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarZonasAuto": true,
        "documento": $('#txtFiltroDocumentoHR').val(),
        "idventa": $('#cbxPropiedadesCliente').val()
    }
    llenarComboSelecionarInterno(url, datos, "bxFiltroProyectoHR");
    
    //MANZANA
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarManzanasAuto": true,
        "documento": $('#txtFiltroDocumentoHR').val(),
        "idventa": $('#cbxPropiedadesCliente').val()
    }
    llenarComboSelecionarInterno(url, datos, "bxFiltroProyectoHR");
    
    //LOTE
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarLotesAuto": true,
        "documento": $('#txtFiltroDocumentoHR').val(),
        "idventa": $('#cbxPropiedadesCliente').val()
    }
    llenarComboSelecionarInterno(url, datos, "bxFiltroProyectoHR");
}

function IrReportePDF2(){
    
     var data = {
        "parametros2": true,
        "txtFiltroDocumentoHR": $("#txtFiltroDocumentoHR").val(),
        "bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
		"cbxPropiedadesCliente": $("#cbxPropiedadesCliente").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
		    if (dato.status == "ok") {
		        window.open('ReporteHojaResumen2.php?Dto='+dato.param+'&lgm='+dato.idlote+'&vnt='+dato.idventa); 
		    }else{
		        mensaje_alerta("\u00A1IMPORTANTE!", "No se encontr\u00F3 informaci\u00F3n para los datos ingresados", "info");
		    }
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
    
}


function Limpiar(){
		$("#TXT1").val("");
		$("#TXT2").val("");
		$("#TXT3").val("");
		$("#TXT4").val("");
		$("#TXT5").val("");
		$("#TXT6").val("");
		$("#TXT7").val("");
		$("#TXT8").val("");
		$("#TXT9").val("");
		$("#TXT10").val("");
		$("#TXT11").val("");
		$("#TXT12").val("");
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
    var url = "../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_Listar.php";
    var dato = {
        "ReturnListaHojaResumen": true,
        "txtFiltroDocumentoHR": $("#txtFiltroDocumentoHR").val(),
        "bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
		"bxFiltroEstadoHR": $("#bxFiltroEstadoHR").val(),
		"cbxPropiedadesCliente": $("#cbxPropiedadesCliente").val()
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
        columns: [
            { "data": "fecha" },
            { "data": "letra" },
            { "data": "monto" },
            { "data": "mora" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.pago_cubierto=='1' && row.estado=='2'){
                        html = '<span class="badge" style="background-color:'+ row.color_pendiente +'; color: white; font-weight: bold;">POR VALIDAR</span>';
                    }else{
                        html = '<span class="badge" style="background-color:'+ row.color +'; color: white; font-weight: bold;">' + row.descEstado + '</span>';
                    }
                    
                    return html;
                } 
            },
            { "data": "fecha_pago" },
            { "data": "importe_pago" },
            { "data": "tipo_cambio" },
            { "data": "pagado" },
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
            "url": "../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_Listar.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
					"ReturnListaHojaResumen": true,
                    "txtFiltroDocumentoHR": $("#txtFiltroDocumentoHR").val(),
                    "bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
            		"bxFiltroEstadoHR": $("#bxFiltroEstadoHR").val(),
		            "cbxPropiedadesCliente": $("#cbxPropiedadesCliente").val()                
                });
            }
        },
        "columns": [
            { "data": "fecha" },
            { "data": "letra" },
            { "data": "monto" },
            { "data": "mora" },
            { "data": "descEstado" },
			{ "data": "fecha_pago" },
			{ "data": "tipo_moneda_reporte" },
            { "data": "importe_pago_reporte" },
            { "data": "tipo_cambio" },
            { "data": "pagado_reporte" },
            { "data": "nro_operacion" },
            { "data": "nro_boleta" }

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

    $('#TablaHojaResumenReporte').DataTable(options);
}


function CargarDatosHR() {
    var data = {
        "CargarDatosHR": true,
        "txtFiltroDocumentoHR": $("#txtFiltroDocumentoHR").val(),
        "bxFiltroLoteHR": $("#bxFiltroLoteHR").val(),
		"cbxPropiedadesCliente": $("#cbxPropiedadesCliente").val()
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
			        
			        
			        var cancelado = dato.cancelado;
			        if(cancelado == '1'){;
			            $("#PnlEtiqueta").show()
			            $("#pnl_cancelado").show();
			        }else{
			            $("#PnlEtiqueta").hide();
			        }
			        
			        var devolucion = dato.devolucion;
			        if(devolucion =='1'){
			            $("#PnlEtiqueta").show();
                        var devo = dato.devolucion_estado
                        if(devo == '2'){
                             $("#pnl_pendiente").show();
                             $("#pnl_devuelto").hide();
                             $("#pnl_cancelado").hide();
                        }else{
                            if(devo == '3'){
                                 $("#pnl_devuelto").show();
                                 $("#pnl_pendiente").hide();
                                $("#pnl_cancelado").hide();
                            }
                        }

			        }else{
			            $("#PnlEtiqueta").hide();
			        }
			        
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



