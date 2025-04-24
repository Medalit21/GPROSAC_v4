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
	
    CargarInformacion();
    CargarCronogramaPagosReporte();
    VerificarPerfil();
    

	 $('#btnBuscarCP').click(function() {
        CargarInformacion();
        $("#PanelEdicionCronograma").hide();
        $("#btnFinalizar").hide();
        $("#btnModificar").show();
        LimpiarCamposRegNewLetra();
		$('#TablaCronogramaReporte').DataTable().ajax.reload();
    });  
    
     $('#btnGuardarLetraC').click(function() {
         ActualizarLetraCronograma();
        CargarInformacion();
    });  
    
    
	$('#btnLimpiarCP').click(function() {
        document.getElementById('bxFiltroProyectoCP').selectedIndex = 0;
        document.getElementById('bxFiltroZonaCP').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaCP').selectedIndex = 0;
        document.getElementById('bxFiltroLoteCP').selectedIndex = 0;
        //$("#txtFiltroDocumentoCP").val("");
        $('#txtFiltroDocumentoCP').val(null).trigger('change');
		$("#TXT_DATOS").val("");
		$("#TXT_UBICACION").val("");
		$("#TXT_PRECVENTA").val("");
		$("#TXT_INTERESES").val("");
		$("#TXT_PRECTOTAL").val("");
		$("#TXT_CAPVIVO").val("");
		$("#TXT_FINANCIAMIENTO").val("");
		$("#TXT_TOTCANCELADO").val("");
		$("#TXT_EMAIL").val("");
		$("#TXT_CELULAR").val("");
		$("#TXT_ENTREGA").val("");
        CargarInformacion();
		$('#TablaCronogramaReporte').DataTable().ajax.reload();
        
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


    $('#btnExportarPdf').click(function() {
        var documento = $("#txtFiltroDocumentoCP").val();
        var lote = $("#bxFiltroLoteCP").val();
        IrReportePDF(documento, lote);
    });  

    $('#btnIrCronMasivo').click(function() {
        //window.open('ReporteMasivoCronPagos.php'); 
        IrMasivo();
    });  
    
    $('#btnExportarPdfCliente').click(function() {
        EjecutarReporte();
        
    }); 

     $('#bxLotesAdquiridos').change(function () {
       FiltroLotesAdquiridos();
    });
    
    $('#btnModificar').click(function() {
       HabilitarModificarCronogramaPagos();       
       $("#txtMondoLetra").val();
       $("#txtTEALetra").val();
       $("#txtFechaInicio").val();
       $("#txtDiasVencimiento").val();
       document.getElementById('cbxLetraFin').selectedIndex = 0;
       document.getElementById('cbxLetraInicio').selectedIndex = 0;
    }); 
    

    $('#btnFinalizar').click(function() {
        DeshabilitarModificarCronogramaPagos();
    }); 


    $('#cbxLetraInicio').change(function () {
       var doc = $("#txtFiltroDocumentoCP").val(); 
       LlenarDatosLetraFinal(doc);
    });


    $('#btnGuardarCambiosCP').click(function() {
        ModificarCronogramaPagos()
    }); 
    
    
    $('#btnLimpiarCambiosCP').click(function() {
       $("#txtMondoLetra").val();
       $("#txtTEALetra").val();
       $("#txtFechaInicio").val();
       $("#txtDiasVencimiento").val();
       document.getElementById('cbxLetraFin').selectedIndex = 0;
       document.getElementById('cbxLetraInicio').selectedIndex = 0;
    }); 


    $('#btnAgregarLetraFormat').click(function() {
        AgregarLetraCronograma()
    }); 

    $('#btnLimpiarLetraFormat').click(function() {
        LimpiarCamposRegNewLetra();
        CargarInformacion();
    }); 

    $('#cbxTipoLetraFormat').change(function () {
        var tipo = $("#cbxTipoLetraFormat").val(); 
        if(tipo == 2){
            $("#txtLetraFormatText").hide();
            $("#txtLetraFormatNumber").show();
        }else{
            $("#txtLetraFormatText").show();
            $("#txtLetraFormatText").val("C.I."); 
            $("#txtLetraFormatNumber").hide();
        }
    });


}

function IrMasivo() {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php";
    var dato = {
        "btnIrMasivo": true,
        "iduser": $("#txtUSR").val()
    };
    realizarJsonPost(url, dato, MasivoIr, null, 10000, null);
}

function MasivoIr(dato){
    //console.log(dato);
    if(dato.status=="ok"){
        window.location.href = dato.ruta;
    }    
}



function FiltroLotesAdquiridos(){

    var lote = $("#bxLotesAdquiridos").val();
    CargarCronogramaPagos('', lote,'');
    CargarDatosCRO('', lote, '');
}

function EjecutarReporte(){
    var idlote_1 = $("#txtID_LOTE").val();

    if(idlote_1===""){
        IrReportePDFMultiple('');
    }else{
        DesencriptarLote();
    }
}

function HabilitarModificarCronogramaPagos(){
    var doc = $("#txtFiltroDocumentoCP").val(); 
    var lote = $("#bxFiltroLoteCP").val(); 
    if(doc==null){
        $("#PanelEdicionCronograma").hide();
        $("#btnFinalizar").hide();
        $("#btnModificar").show();
        mensaje_alerta("\u00A1IMPORTANTE!", "Seleccionar a un cliente.", "info");
    }else{
        $("#PanelEdicionCronograma").show();
        $("#btnFinalizar").show();
        $("#btnModificar").hide();
        LlenarDatosLetraInicio(doc);
        LlenarDatosLetrasUbicar(doc,lote);
    }
}


function DeshabilitarModificarCronogramaPagos(){
    $("#PanelEdicionCronograma").hide();
    $("#btnFinalizar").hide();
    $("#btnModificar").show();
}

function LlenarDatosLetraInicio(documento) {
    var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php';
    var datos = {
        "ListarLetrasInicio": true,
        "documento": documento
    }
    llenarCombo(url, datos, "cbxLetraInicio");    
}

function LlenarDatosLetrasUbicar(documento,lote) {
    var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php';
    var datos = {
        "ListarLetrasUbicar": true,
        "documento": documento,
        "lote": lote
    }
    llenarCombo(url, datos, "cbxUbicarLetraFormat");    
}

function LimpiarCamposRegNewLetra(){
    $("#txtFecVencimientoFormat").val("");
    $("#txtLetraFormatNumber").val("");
    $("#txtLetraFormatText").val("");
    $("#txtMontoLetraFormat").val("0.00");
    $("#txtInteresesFormat").val("0.00");
    $("#txtAmortizacionFormat").val("0.00");
    $("#txtCapitalVivoFormat").val("0.00");
    document.getElementById('cbxTipoLetraFormat').selectedIndex = 0;
    document.getElementById('cbxUbicarLetraFormat').selectedIndex = 0;
}

function LlenarDatosLetraFinal(documento) {
    var letraInicio = $("#cbxLetraInicio").val();
    var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php';
    var datos = {
        "ListarLetrasFin": true,
        "documento": documento,
        "letraInicio": letraInicio
    }
    llenarCombo(url, datos, "cbxLetraFin");    
}

function ModificarCronogramaPagos(){
     var data = {
        "btnModificarCronograma": true,
        "cbxLetraInicio": $("#cbxLetraInicio").val(),
        "cbxLetraFin": $("#cbxLetraFin").val(),
        "txtMondoLetra": $("#txtMondoLetra").val(),
        "txtTEALetra": $("#txtTEALetra").val(),
        "txtFechaInicio": $("#txtFechaInicio").val(),
        "txtDiasVencimiento": $("#txtDiasVencimiento").val(),
        "txtUSR": $("#txtUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if(dato.status=="ok"){
                    mensaje_alerta("\u00A1IMPORTANTE!", dato.data, "success");
                    CargarInformacion();
                }else{
                    mensaje_alerta("\u00A1ERROR!", dato.data, "info");
                }              

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function ValidarCamposRegLetraNew() {
    var flat = true;
    if ($("#IDPRODUCTO").val() === "" || $("#IDPRODUCTO").val() === null) {
        $("#IDPRODUCTO").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, primero registre el producto o seleccione uno ya registrado de la tabla inicial.", "info");  
        flat = false;
    }  else if ($("#cbxTipoLetraFormat").val() === "" || $("#cbxTipoLetraFormat").val() === null) {
        $("#cbxTipoLetraFormat").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de letra.", "info");  
        flat = false;
    } else if ($("#txtFecVencimientoFormat").val() === "" || $("#txtFecVencimientoFormat").val() === null) {
        $("#txtFecVencimientoFormat").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar la fecha de vencimiento de la letra.", "info");  
        flat = false;
    } else if($("#cbxTipoLetraFormat").val() == 2){
        if ($("#txtLetraFormatNumber").val() === "" || $("#txtLetraFormatNumber").val() === null) {
            $("#txtLetraFormatNumber").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el item de la letra.", "info");  
            flat = false;
        }
    
    } else if($("#cbxTipoLetraFormat").val() == 1){
        if ($("#txtLetraFormatText").val() === "" || $("#txtLetraFormatText").val() === null) {
            $("#txtLetraFormatText").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el item de la letra.", "info");  
            flat = false;
        }
    
    }   else if ($("#txtMontoLetraFormat").val() === "" || $("#txtMontoLetraFormat").val() === null || $("#txtMontoLetraFormat").val() == 0) {
        $("#txtMontoLetraFormat").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el monto de la letra.", "info");  
        flat = false;

    } else if ($("#txtInteresesFormat").val() === "" || $("#txtInteresesFormat").val() === null) {
        $("#txtInteresesFormat").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el monto de intereses pagados.", "info");  
        flat = false;

    } else if ($("#txtAmortizacionFormat").val() === "" || $("#txtAmortizacionFormat").val() === null) {
        $("#txtAmortizacionFormat").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el monto amortizado en la letra.", "info");  
        flat = false;

    } else if ($("#txtCapitalVivoFormat").val() === "" || $("#txtCapitalVivoFormat").val() === null) {
        $("#txtCapitalVivoFormat").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el monto del capital vivo en la letra.", "info");  
        flat = false;
    } 
    return flat;
}


function AgregarLetraCronograma(){
    if(ValidarCamposRegLetraNew()){
        var data = {
        "btnAgregarLetraCronograma": true,
        "cbxTipoLetraFormat": $("#cbxTipoLetraFormat").val(),
        "cbxUbicarLetraFormat": $("#cbxUbicarLetraFormat").val(),
        "txtFecVencimientoFormat": $("#txtFecVencimientoFormat").val(),
        "txtLetraFormatNumber": $("#txtLetraFormatNumber").val(),
        "txtLetraFormatText": $("#txtLetraFormatText").val(),
        "txtMontoLetraFormat": $("#txtMontoLetraFormat").val(),
        "txtInteresesFormat": $("#txtInteresesFormat").val(),
        "txtAmortizacionFormat": $("#txtAmortizacionFormat").val(),
        "txtCapitalVivoFormat": $("#txtCapitalVivoFormat").val(),
        "ID_VNTA": $("#ID_VNTA").val(),
        "txtUSR": $("#txtUSR").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                //console.log(dato);
                if(dato.status=="ok"){
                    mensaje_alerta("\u00A1IMPORTANTE!", dato.data, "success");
                    CargarInformacion();
                }else{
                    mensaje_alerta("\u00A1ERROR!", dato.data, "info");
                }              

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
                desbloquearPantalla();
            },
            timeout: timeoutDefecto
        });   
    }
}




function VerificarPerfil(){
     var data = {
        "VerificarPerfil": true,
        "txtUSR": $("#txtUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
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
                            CargarCronogramaPagos(dato.documento, '', '');
                            CargarDatosCRO(dato.documento, '', '');
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
    var url = '../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php';
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
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
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
        "txtFiltroDocumentoEC": $("#txtFiltroDocumentoCP").val(),
        "bxFiltroLoteEC": lote
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
		    if (dato.status == "ok") {
		        window.open('ReporteCronogramaPagos.php?Dto='+dato.param+'&d0q='+dato.param2); 
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
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
            if (dato.status === "ok") {
                window.open('ReporteCronogramaPagos.php?Dto='+dato.param); 
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


/*****************************************LLENAR TABLA LISTA********************************************* */

function CargarInformacion(){

    var documento = $("#txtFiltroDocumentoCP").val();
    var lote = $("#bxFiltroLoteCP").val();
    var estado  = $("#bxFiltroEstadoCP").val();
    
    if(documento==null){
        //mensaje_alerta("\u00A1IMPORTANTE!", "Seleccionar a un cliente.", "info");
    }else{
        $("#btnModificar").prop("disabled", false);
    }
    
    CargarCronogramaPagos(documento, lote, estado);
    CargarDatosCRO(documento, lote, estado);
}

function CargarCronogramaPagos(documento, lote, estado) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php";
    var dato = {
        "ReturnListaCronogramaPagos": true,
        "txtFiltroDocumentoCP": documento,
        "bxFiltroLoteCP": lote,
		"bxFiltroEstadoCP": estado,
        "txtUSR": $("#txtUSR").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarCronogramaGenerados, null, 10000, null);
}

function respuestaBuscarCronogramaGenerados(dato) {
    desbloquearPantalla();
    //console.log(dato);
    document.title = "GProsac - Cronograma Pagos - "+dato.cliente;
    $("#ID_VNTA").val(dato.idventa);
    LlenarTabalaCronogramaGenerados(dato.data);
}

var getTablaBusquedaCabGenerado = null;
function LlenarTabalaCronogramaGenerados(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaCronograma').DataTable({
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
            {
                "data": "id",
                "render": function (data, type, row) {
                    var html;
                    if(row.acceso == "ok"){
                        if(row.pago_cubierto!=2){ 
                            if(row.pago_cubierto==0){
                                if(row.idcron == row.id){
                                    html = '<button class="btn btn-edit-action" onclick="ModalEditarCronograma(\'' + data + '\')" title="Editar Letra"><i class="fas fa-pencil-alt"></i></button>\
                                    <button class="btn btn-delete-action" onclick="EliminarLetraCron(\'' + data + '\')" title="Eliminar Letra"><i class="fas fa-trash-alt"></i></button>\
                                    <button class="btn btn-block-action" disabled><i class="fas fa-arrow-up"></i></button>\
                                    <button class="btn btn-warning-action" onclick="BajarLetra(\'' + data + '\')" title="Bajar Letra"><i class="fas fa-arrow-down"></i></button>\
                                    <button class="btn btn-pdf-action" onclick="RevisarDatosAuditoria(\'' + data + '\')" title="Ver Datos Control"><i class="fas fa-user-secret"></i></button>';
                                }else{
                                    html = '<button class="btn btn-edit-action" onclick="ModalEditarCronograma(\'' + data + '\')" title="Editar Letra"><i class="fas fa-pencil-alt"></i></button>\
                                    <button class="btn btn-delete-action" onclick="EliminarLetraCron(\'' + data + '\')" title="Eliminar Letra"><i class="fas fa-trash-alt"></i></button>\
                                    <button class="btn btn-success-action" onclick="SubirLetra(\'' + data + '\')" title="Subir Letra"><i class="fas fa-arrow-up"></i></button>\
                                    <button class="btn btn-warning-action" onclick="BajarLetra(\'' + data + '\')" title="Bajar Letra"><i class="fas fa-arrow-down"></i></button>\
                                    <button class="btn btn-pdf-action" onclick="RevisarDatosAuditoria(\'' + data + '\')" title="Ver Datos Control"><i class="fas fa-user-secret"></i></button>';
                                }
                            }else{
                                html = '<button class="btn btn-edit-action" onclick="ModalEditarCronograma(\'' + data + '\')" title="Editar Letra"><i class="fas fa-pencil-alt"></i></button>\
                                <button class="btn btn-block-action" disabled><i class="fas fa-trash-alt"></i></button>\
                                <button class="btn btn-block-action" disabled><i class="fas fa-arrow-up"></i></button>\
                                <button class="btn btn-block-action" disabled><i class="fas fa-arrow-down"></i></button>\
                                <button class="btn btn-pdf-action" onclick="RevisarDatosAuditoria(\'' + data + '\')" title="Ver Datos Control"><i class="fas fa-user-secret"></i></button>';
                            }

                        }else{
                            html = '<button class="btn btn-edit-action" onclick="ModalEditarCronograma(\'' + data + '\')" title="Editar Letra"><i class="fas fa-pencil-alt"></i></button>\
                            <button class="btn btn-block-action" disabled><i class="fas fa-trash-alt"></i></button>\
                            <button class="btn btn-block-action" disabled><i class="fas fa-arrow-up"></i></button>\
                            <button class="btn btn-block-action" disabled><i class="fas fa-arrow-down"></i></button>\
                            <button class="btn btn-pdf-action" onclick="RevisarDatosAuditoria(\'' + data + '\')"><i class="fas fa-user-secret"></i></button>';
                        }
                    }else{
                        html = '<button class="btn btn-block-action" disabled><i class="fas fa-check"></i></button>';
                    }
                    return html;
                }
            },
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

function CargarCronogramaPagosReporte() {
  
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
            "url": "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {  
					"ReturnListaCronogramaPagos": true,
					"txtFiltroDocumentoCP": $("#txtFiltroDocumentoCP").val(),
					"bxFiltroLoteCP": $("#bxFiltroLoteCP").val(),
					"bxFiltroEstadoCP": $("#bxFiltroEstadoCP").val(),
                    "txtUSR": $("#txtUSR").val()				
                });
            }
        },
        "columns": [
            { "data": "fecha" },
            { "data": "letra" },
            { "data": "monto" },
            { "data": "intereses" },
            { "data": "amortizacion" },
			{ "data": "capital_vivo" },
			{ "data": "pagado" },
            { "data": "descEstado" }

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

    $('#TablaCronogramaReporte').DataTable(options);
}



function EliminarLetraCron(id) {
    mensaje_condicionalUNO("¿Est\u00E1 seguro de eliminar?", "Al confirmar se proceder\u00E1 a eliminar la letra selecionada.", ConfirmarEliminarLetraCron, CancelEliminarLetraCron, id);
}

function CancelEliminarLetraCron() {
    return;
}

function ConfirmarEliminarLetraCron(id){ 
    
    bloquearPantalla("Eliminando...");
    var url = "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php";
    var dato = {
        "btnEliminarLetraCron": true,
        "idCronograma": id,
        "txtUSR": $("#txtUSR").val()
    };
    realizarJsonPost(url, dato, respuestaEliminarLetraCron, null, 10000, null);

}

function respuestaEliminarLetraCron(dato){
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status == "ok") {                        
        mensaje_alerta("\u00A1IMPORTANTE!", dato.data, "success");
        CargarInformacion();
    }else{
        mensaje_alerta("\u00A1ERROR!", dato.data, "info"); 
    }
}



function SubirLetra(id){
    var data = {
        "btnSubirLetraCron": true,
        "idCronograma": id,
        "txtUSR": $("#txtUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
            if(dato.status=="ok"){
                mensaje_alerta("\u00A1IMPORTANTE!", dato.data, "success");
                CargarInformacion();
            }else{
                mensaje_alerta("\u00A1ERROR!", dato.data, "info");
            }   	
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
    });
 }

function BajarLetra(id){
    var data = {
        "btnBajarLetraCron": true,
        "idCronograma": id,
        "txtUSR": $("#txtUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
            if(dato.status=="ok"){
                mensaje_alerta("\u00A1IMPORTANTE!", dato.data, "success");
                CargarInformacion();
            }else{
                mensaje_alerta("\u00A1ERROR!", dato.data, "info");
            }   
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
    });
}


function RevisarDatosAuditoria(id){
    var data = {
         "btnRevisarDatosAudit": true,
         "idCronograma": id,
         "txtUSR": $("#txtUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
            if(dato.status=="ok"){

                var resultado = dato.data;

                $("#txtUserRegister").val(resultado.nom_usu_crea);
                $("#txtDateRegister").val(resultado.creado);

                $("#txtUserUpdate").val(resultado.nom_usu_actualiza);
                $("#txtDateUpdate").val(resultado.actualizado);

                $('#modalAuditoriaCronograma').modal('show');
            } 	
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
    });
}




function CargarDatosCRO(documento, lote, estado) {
    var data = {
        "CargarDatosCP": true,
		"txtFiltroDocumentoCP": documento,
		"bxFiltroLoteCP": lote,
		"bxFiltroEstadoCP": estado
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
			if( dato.validar === "ok"){
                    $("#TXT_DATOS").val(dato.dato);
                    $("#TXT_UBICACION").val('UBICACI\u00D3N: '+dato.ubicacion);
                    $("#TXT_PRECVENTA").val('PRECIO DE VENTA: $ '+dato.precio_venta);
                    $("#TXT_INTERESES").val('INTERESES: $ '+dato.intereses);
                    $("#TXT_PRECTOTAL").val('PRECIO TOTAL DEL LOTE: $ '+dato.precio_total);
                    $("#TXT_CAPVIVO").val('CAPITAL VIVO: $ '+dato.capital_vivo);
                    $("#TXT_SALDOPENDIENTE").val('SALDO PENDIENTE: $ '+dato.saldo_pendiente);
                    $("#TXT_FINANCIAMIENTO").val('TIEMPO DE FINANC.: '+dato.financiamiento);
                    $("#TXT_TOTCANCELADO").val('TOTAL CANCELADO: $ '+dato.monto_pagado);
                    $("#TXT_EMAIL").val('Correo: '+dato.correo);
                    $("#TXT_CELULAR").val('Tel\u00E9fono: '+dato.telefono);
                    $("#TXT_ENTREGA").val('Fecha Entrega Casa: '+dato.fecha_entrega);
                    
                    var cancelado = dato.cancelado;
			        if(cancelado=='1'){
			            $("#PanelCancelado").show();
			        }else{
			            $("#PanelCancelado").hide();
			        }
			        
			        var devolucion = dato.devolucion;
			        if(devolucion=='1'){
			            $("#PanelDevuelto").show();
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

function ModalEditarCronograma(id){
   // $('#modalEditarCronograma').modal('show');
    var data = {
        "btnEditarCronograma": true,
		"idCronograma": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
			if( dato.status === "ok"){
			    
                    $("#txtIdCronogramaC").val(dato.data.id);
                    $("#txtFecVencimientoC").val(dato.data.fecha_vencimiento);
                    $("#txtLetraC").val(dato.data.item_letra);
                    $("#txtMontoLetraC").val(dato.data.monto);
                    $("#txtInteresesC").val(dato.data.interes_amortizado);
                    $("#txtAmortizacionC").val(dato.data.capital_amortizado);
                    $("#txtCapitalVivoC").val(dato.data.capital_vivo);
                    ConsultarEstadoLetra(dato.data.id);
                    $("#cbxEstadoLetra").val(dato.data.estado);
                    
                    $('#modalEditarCronograma').modal('show');
			}		
			
         
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function ConsultarEstadoLetra(id){
     var data = {
        "btnConsultarEstadoLetra": true,
        "idregistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
            if(dato.estado=="0"){
                $("#cbxEstadoLetra").prop("disabled", false);
            }else{
                $("#cbxEstadoLetra").prop("disabled", true);
            }
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });   
}



function ActualizarLetraCronograma(){
   // $('#modalEditarCronograma').modal('show');
    var data = {
        "btnActualizarCronograma": true,
        "txtIdCronograma": $("#txtIdCronogramaC").val(),
        "txtFecVencimientoC": $("#txtFecVencimientoC").val(),
        "txtLetraC": $("#txtLetraC").val(),
        "txtMontoLetraC": $("#txtMontoLetraC").val(),
        "txtInteresesC": $("#txtInteresesC").val(),
        "txtAmortizacionC": $("#txtAmortizacionC").val(),
        "txtCapitalVivoC": $("#txtCapitalVivoC").val(),
        "cbxEstadoLetra": $("#cbxEstadoLetra").val(),
        "txtUSR": $("#txtUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD02_CronogramaPagos/M02MD02_Listar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
			if( dato.status === "ok"){
			    mensaje_alerta("\u00A1CORRECTO!", "Se guardaron los cambios en la Letra", "success");
			}else{
			    mensaje_alerta("\u00A1ERROR!", "No se guardo el cambio, por favor, intente nuevamente.", "info");
			}		
			
         
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}
























