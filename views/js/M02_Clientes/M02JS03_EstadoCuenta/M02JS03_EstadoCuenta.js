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
	MostrarInformacionEstadoCuenta();
	//CargarEstadoCuentaReporte()
	VerificarPerfil();
    
    $('#btnBuscarEC').click(function() {
        MostrarInformacionEstadoCuenta();
		//$('#TablaEstadoCuentasReporte').DataTable().ajax.reload();
    });  

    $('#btnLimpiar').click(function() {
        document.getElementById('bxFiltroProyectoEC').selectedIndex = 0;
        document.getElementById('bxFiltroZonaEC').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaEC').selectedIndex = 0;
        document.getElementById('bxFiltroLoteEC').selectedIndex = 0;
		document.getElementById('bxFiltroEstadoEC').selectedIndex = 0;
		//$("#txtFiltroDocumentoEC").val("");
		$('#txtFiltroDocumentoEC').val(null).trigger('change');
		$("#TXT1").val("");
		$("#TXT2").val("");
		$("#txt3campo").val("");
		$("#TXT4").val("");
		$("#TXT5").val("");
		$("#TXT6").val("");
		$("#TXT7").val("");
		$("#TXT8").val("");
		$("#TXT9").val("");
		$("#TXT10").val("");
		$("#TXT11").val("");
        MostrarInformacionEstadoCuenta();
        //$('#TablaEstadoCuentasReporte').DataTable().ajax.reload();
    });
  
    $('#bxFiltroProyectoEC').change(function () {
        $("#bxFiltroZonaEC").val("");
        $("#bxFiltroManzanaEC").val("");
        var url = '../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_ListarTipos.php';
        var datos = {
            "ListarZonas": true,
            "idproyecto": $('#bxFiltroProyectoEC').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaEC");
        document.getElementById('bxFiltroManzanaEC').selectedIndex = 0;
    });

    $('#bxFiltroZonaEC').change(function () {
        $("#bxFiltroManzanaEC").val("");
        $("#bxFiltroLoteEC").val("");
        var url = '../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaEC').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaEC");
        document.getElementById('bxFiltroLoteEC').selectedIndex = 0;
    });

    $('#bxFiltroManzanaEC').change(function () {
        $("#bxFiltroLoteEC").val("");
        var url = '../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaEC').val()
        };
        llenarCombo(url, datos, "bxFiltroLoteEC");
    });
    
    
    $('#btnExportarPdf').click(function() {
        var documento = $("#txtFiltroDocumentoEC").val();
        var lote = $("#bxFiltroLoteEC").val();
        IrReportePDF(documento, lote);
    });  

    $('#btnExportarPdfCliente').click(function() {
        EjecutarReporte();
        
    }); 

     $('#bxLotesAdquiridos').change(function () {
       FiltroLotesAdquiridos();
    });
    
}

function FiltroLotesAdquiridos(){

    var lote = $("#bxLotesAdquiridos").val();
    CargarEstadoCuenta('', lote);
    CargarDatosEC('', lote);
}

function EjecutarReporte(){
    var idlote_1 = $("#txtID_LOTE").val();

    if(idlote_1 === ""){
        IrReportePDFMultiple('');
    }else{
        DesencriptarLote();
    }
}

function VerificarPerfil(){
     var data = {
        "VerificarPerfil": true,
        "txtUSR": $("#txtUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if(dato.perfil=="6"){
                    document.getElementById("PanelFiltros").style.display = "none";
                    document.getElementById("PanelBotons").style.display = "none";
                   if(dato.num_lotes > 1){
                        document.getElementById("PanelLotes").style.display = "block";
                        document.getElementById("BtnPanelLotes").style.display = "block";
                        LlenarLotesAdquiridos(dato.documento);
                    }else{
                        if(dato.num_lotes = 1){
                            document.getElementById("PanelLotes").style.display = "none";
                            document.getElementById("BtnPanelLotes").style.display = "block";
                            $("#txtID_LOTE").val(dato.idlote);
                            CargarEstadoCuenta(dato.documento, '');
                            CargarDatosEC(dato.documento, '');
                        }else{
                            mensaje_alerta("\u00A1IMPORTANTE!", "No se encontraron datos para el usuario actual.", "info");
                        }
                    }

                }else{                    
                    document.getElementById("PanelLotes").style.display = "none";
                    document.getElementById("BtnPanelLotes").style.display = "none";
                }           

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}


function LlenarLotesAdquiridos(documento) {
    var url = '../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_Listar.php';
    var datos = {
        "ListarLotesAdquiridos": true,
        "documento": documento
    }
    llenarCombo(url, datos, "bxLotesAdquiridos");    
}

function DesencriptarLote(){
     var data = {
        "DesencriptarLote": true,
        "codigo": $("#txtID_LOTE").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);              
                IrReportePDF('',dato.idlote);     

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function IrReportePDF(documento, lote){
    
     var data = {
        "parametros": true,
        "txtFiltroDocumentoEC": documento,
        "bxFiltroLoteEC": lote
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
		    if (dato.status == "ok") {
		        window.open('ReporteEstadoCuenta.php?Dto='+dato.param_doc+'&lgm='+dato.idlote); 
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

function IrReportePDFMultiple(documento){
    
     var data = {
        "parametros": true,
        "txtFiltroDocumentoEC": documento,
        "bxFiltroLoteEC": $("#bxLotesAdquiridos").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
            if (dato.status == "ok") {
                window.open('ReporteEstadoCuenta.php?Dto='+dato.param_doc+'&lgm='+dato.idlote); 
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

function LlenarProyectos() {
    var url = '../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoEC");    
}

function LLenarZonas() {
    var url = '../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoEC').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaEC");
}


/*****************************************LLENAR TABLA LISTA********************************************* */

function  MostrarInformacionEstadoCuenta(){
    var documento = $("#txtFiltroDocumentoEC").val();
    var lote = $("#bxFiltroLoteEC").val();

    CargarEstadoCuenta(documento, lote);
    CargarDatosEC(documento, lote);
}


function CargarEstadoCuenta(documento, lote) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_Listar.php";
    var dato = {
        "ReturnListaEstadoCuenta": true,
        "txtFiltroDocumentoEC": documento,
        "bxFiltroLoteEC": lote,
		"bxFiltroEstadoEC": $("#bxFiltroEstadoEC").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarEstadoCuentaGenerados, null, 10000, null);
}

function respuestaBuscarEstadoCuentaGenerados(dato) {
    desbloquearPantalla();
    console.log(dato);
    LlenarTabalaEstadoCuentaGenerados(dato.data);
}

var getTablaBusquedaCabGenerado = null;
function LlenarTabalaEstadoCuentaGenerados(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaEstadoCuentas').DataTable({
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
                    html = '<span class="badge" style="background-color:'+ row.color +'; color: white; font-weight: bold;">' + row.descEstado + '</span>';
                    return html;
                } 
            },
			{ "data": "fecha_pago" },
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
}

function CargarEstadoCuentaReporte() {
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
            "url": "../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_Listar.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
					"ReturnListaEstadoCuenta": true,
					"txtFiltroDocumentoEC": $("#txtFiltroDocumentoEC").val(),
					"bxFiltroLoteEC": $("#bxFiltroLoteEC").val(),
					"bxFiltroEstadoEC": $("#bxFiltroEstadoEC").val()                    
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
            { "data": "pagado" },
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

    $('#TablaEstadoCuentasReporte').DataTable(options);
}


function CargarDatosEC(documento, lote) {
    var data = {
        "CargarDatosECc": true,
        "txtFiltroDocumentoEC": documento,
        "bxFiltroLoteEC": lote
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD03_EstadoCuenta/M02MD03_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
				if(dato.validar === "ok"){
                    $("#TXT1").val(dato.dato);
                    $("#TXT2").val('UBICACI\u00D3N: '+dato.ubicacion);
                    $("#txt3campo").val('PRECIO DE VENTA: $ '+dato.precio_venta);
                    $("#TXT4").val('INTERESES: $ '+dato.intereses);
                    $("#TXT5").val('PRECIO TOTAL DEL LOTE: $ '+dato.precio_total);
                    $("#TXT6").val('CAPITAL VIVO: $ '+dato.capital_vivo);
                    $("#TXT7").val('TIEMPO DE FINANC.: '+dato.financiamiento);
                    $("#TXT8").val('TOTAL CANCELADO: $ '+dato.monto_pagado);
                    $("#TXT9").val('Correo: '+dato.correo);
                    $("#TXT10").val('Tel\u00E9fono: '+dato.telefono);
                    $("#TXT11").val('Fecha Entrega Casa: '+dato.fecha_entrega);
                    
                    
                    var cancelado = dato.cancelado;
			        if(cancelado=='1'){
			            $("#PanelCancelado").show();
			        }else{
			            $("#PanelCancelado").hide();
			        }
			        
			        var devolucion = dato.devolucion;
			        if(devolucion=='1'){
			            $("#PanelDevuelto").show();
			            $().val();
			        }else{
			            $("#PanelDevuelto").hide();
			        }
				}	
			
         
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}










