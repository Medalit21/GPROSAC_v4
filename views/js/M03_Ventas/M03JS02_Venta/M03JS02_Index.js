var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {
  
    Ninguno();
    
    /***************ACCION BOTONES CABECERA********** */
    $('#nuevo').click(function() {
        Nuevo();
    });

    $('#cancelar').click(function() {
        Ninguno();
    });

    $('#guardar').click(function() {
        Guardar();
    });

    $('#modificar').click(function() {
        Modificar();
    });

    $('#eliminar').click(function() {
        Eliminar();
    });
    
    $('#busqueda_avanzada').click(function() {
        MostrarLista();
    });
    
	
    
    /******************NUEVO -- INICIALIZAR CARGA DE COMBOS PROYECTO, ZONA ,MANZANA, LOTE********************** */
    $('#cbxProyecto').change(function() {
        $("#cbxZona").val("");
        $("#cbxManzana").val("");
        $("#cbxLote").val("");
        LLenarZona();
        document.getElementById('cbxManzana').selectedIndex = 0;
        $("#cbxManzana").prop("disabled", true);
        document.getElementById('cbxLote').selectedIndex = 0;
        $("#cbxLote").prop("disabled", true);
        BuscarReservasCliente();
    });
    //LLenarZona();

    $('#cbxZona').change(function() {
        $("#cbxManzana").val("");
        $("#cbxLote").val("");
        LLenarManzanas();
        $("#cbxManzana").prop("disabled", false);
        document.getElementById('cbxLote').selectedIndex = 0;
        $("#cbxLote").prop("disabled", true);
        BuscarReservasCliente();
    });

    $('#cbxManzana').change(function() {
        $("#cbxLote").val("");
        LLenarLotes();
        document.getElementById('cbxLote').selectedIndex = 0;
        $("#cbxLote").prop("disabled", false);
        BuscarReservasCliente();
    });

    $('#cbxLote').change(function() {
        BuscarDatoLote();        
    });


      /******************* INICIALIZAR FILTROS DE LOTE ********************************************/
    $('#bxFiltroProyectoVenta').change(function () {
        $("#bxFiltroZonaVenta").val("");
        $("#bxFiltroManzanaVenta").val("");
        $("#bxFiltroLoteVenta").val("");
        var url = '../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php';
        var datos = {
            "ListarZonas": true,
            "idProyecto": $('#bxFiltroProyectoVenta').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaVenta");
        document.getElementById('bxFiltroManzanaVenta').selectedIndex = 0;
        document.getElementById('bxFiltroLoteVenta').selectedIndex = 0;
    });

    $('#bxFiltroZonaVenta').change(function () {
        $("#bxFiltroManzanaVenta").val("");
        $("#bxFiltroLoteVenta").val("");
        var url = '../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php';
        var datos = {
            "ListarManzanas": true,
            "idZona": $('#bxFiltroZonaVenta').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaVenta");
        document.getElementById('bxFiltroLoteVenta').selectedIndex = 0;
    });

    $('#bxFiltroManzanaVenta').change(function () {
        $("#bxFiltroLoteVenta").val("");
        var url = '../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php';
        var datos = {
            "ListarLotes": true,
            "idManzana": $('#bxFiltroManzanaVenta').val()
        }
        llenarCombo(url, datos, "bxFiltroLoteVenta");
    });

    $('#cbxTipoInmueble').change(function() {
        LLenarTipoCasa();
        ListarPagosPrevios();
        CalcularTotal();
    });

    $('#btnBuscarCliente').click(function() {
        BuscarDatoCliente();
        ListarPagosPrevios();
        CalculaTotalDato();
    });

    $('#btnLimpiarFiltrosCliente').click(function() {
        $("#__ID_CLIENTE,#__ID_RESERVA,#__ID_MONTO_RESERVA,#bxFiltroProyectoVenta,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxZona,#cbxManzana,#cbxLote, #bxFiltroZonaVenta, #bxFiltroManzanaVenta, #bxFiltroLoteVenta, #txtContactoCliente").val("");
        
		// Limpiar Select2
		$('#txtDocumentoCliente').val(null).trigger('change');
    });

    $('#txtDocumentoCliente').keydown(function() {
        LlenarTablaReservas([]);
        $("#__ID_CLIENTE,#__ID_RESERVA,#__ID_MONTO_RESERVA,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxZona,#cbxManzana,#cbxLote").val("");
        if ($("#cbxManzana").val() != "") {
            LLenarLotes();
        }
    });
    //InicializarAtributosTablaBusquedaVenta();

    $('#btnBuscarRegistro').click(function() {
        BusacarVentaPaginado();
        BusacarVentaReporte();
    });

    $('#btnLimpiar').click(function() {
        LimpiarFiltro();
        BusacarVentaPaginado();
        BusacarVentaReporte();
    });

    $('#cbxCondicionFiltro').change(function() {
        BusacarVentaPaginado();
        BusacarVentaReporte();
    });

    $('#cbxProyecto').on('change', function() {
        $("#cbxProyectoHtml").hide();
        $("#txtDescuento").val("0");
    });

    $('#cbxZona').on('change', function() {
        $("#cbxZonaHtml").hide();
        $("#txtDescuento").val("0");
    });

    $('#cbxManzana').on('change', function() {
        $("#cbxManzanaHtml").hide();
        $("#txtDescuento").val("0");
    });

    $('#cbxLote').on('change', function() {
        $("#cbxLoteHtml").hide();
        $("#txtDescuento").val("0");
    });

    $('#txtDescuento,#txtImporteVenta,#txtMontoCuotaInicialr,#txtTEAr,#txtMontoInicial, #txtPrecioVenta, #txtMontoDscto').keypress(function() {
        SoloNumeros_Punto();
    });

    $('#txtDocumentoCliente,#txtDocumentoFiltro,#txtCantidadLetrar,#txtNumeroComprobante').keypress(function() {
        SoloNumeros1_9();
    });

    $('#txtDescuento').keyup(delayTime(function(e) {
        BuscarDatoLoteParaDescuento();
    }, 1000));

    $('#cbxCondicionVenta').on('change', function() {
        if (parseInt($("#cbxCondicionVenta").val()) === 2) {
            $("#detalleCredito").show();
            $("#cbxTipoCreditor").prop("disabled", false);
            $("#txtCantidadLetrar").prop("disabled", false);
            $("#txtTEAr").prop("disabled", false);
            $("#txtFechaPrimerPagor").prop("disabled", false);
            $("#checkCuotaInicialr").prop("disabled", false);
            $("#txtMontoCuotaInicialr").prop("disabled", false);
        } else {
            $("#detalleCredito").hide();
            $("#txtCantidadLetrar,#txtTEAr,#txtFechaPrimerPagor,#txtMontoCuotaInicialr").val("");
            $("#checkCuotaInicialr").prop('checked', false);
             $("#cbxTipoCreditor").prop("disabled", true);
            $("#txtCantidadLetrar").prop("disabled", true);
            $("#txtTEAr").prop("disabled", true);
            $("#txtFechaPrimerPagor").prop("disabled", true);
            $("#checkCuotaInicialr").prop("disabled", true);
            $("#txtMontoCuotaInicialr").prop("disabled", true);
        }
    });
	// Forzar que se ejecute con el valor cargado por defecto
	$('#cbxCondicionVenta').trigger('change');

    $('#cbxTipoDescuento').on('change', function() {
        if (parseInt($("#cbxTipoDescuento").val()) === 1) {
            $("#txtValorDescuento").prop("disabled", false);
            $("#txtValorDescuento").val("0.00");
            $("#txtValorDescuento").focus();
        }else{
            if (parseInt($("#cbxTipoDescuento").val()) === 2) {
                $("#txtValorDescuento").prop("disabled", false);
                $("#txtValorDescuento").val("0");
                $("#txtValorDescuento").focus();
            }else{
                $("#txtValorDescuento").prop("disabled", true);
                $("#txtValorDescuento").val("0.00");
                $("#txtMontoDscto").val("0.00");
                CalcularTotal();
            }
        }
    });

    $('#cbxTipoMonedaVenta').on('change', function() {
        ValidarTipoMoneda();
    });

    InicializarAtributosTablaBusquedaRelacionada();
    /***********ADJUNTAR  DOCUMENTOS************ */
    $('#btnNuevoDocumentoAdjunto').click(function() {
        NuevoAdjuntarDocumento();
    });

    /********************SUBIR  DOCUMENTO ADJUNTO********************** */
    /*$('#fileSubirAdjuntoVenta').change(function(e) {
        SubirDocumentoAdjunto();
    });*/

    $('#btnGuardarAdjunto').click(function() {
        GuardarInformacionAdjunto();
        //RegistrarAdjuntoVenta();       

    });

    $("#txtValorDescuento").keyup(delayTime(function (e) { 
      CalculoDescuento();
      CalcularTotal();
    }, 1000)); 
    
     $("#txtPrecioNegocio").keyup(delayTime(function (e) { 
      CalcularTotal();
    }, 1000)); 
    
     $("#txtMontoInicial").keyup(delayTime(function (e) { 
      //CalcularTotal();
    }, 1000)); 


    /*if ($("#__ID_LOTE_VENTA").val().trim().length > 0) {
        $("#nuevo").click();
        if ($("#__ID_RESERVA_VENTA").val().trim().length > 0) {
            BuscarInformacionReservaLote();
        } else {
            BuscarInformacionSegmentadaLoteParaVender();
        }
    }*/
    
    /******* TABLA PAGOS PREVIOS ********************/
    
    $('#btnAgregarPagoPrevio').click(function() {
        AbrirPopupPagosPrevios();       
    });
    
    $('#btnGuardarPagosPrevios').click(function() {
        RegistrarPagosPrevios();       
    });
    
    
    $('#cbxTipoMonedaPP').on('change', function() {
        var tipo_mod = $("#cbxTipoMonedaPP").val();
        ValidarTipoMonedaPP(tipo_mod);
    });
    
    
    $("#txtImportePagoPP").keyup(delayTime(function (e) { 
       var pago = $("#txtImportePagoPP").val();
      ValidarImportePago(pago);
    }, 1000)); 
   
   
    $('#cbxTipoCronograma').on('change', function() {
        var tipo_cro = $("#cbxTipoCronograma").val();
        TipoCronograma(tipo_cro);
    });
    
    $('#btnCargarFormato').click(function() {
        ImportarCronogramaTemporal();       
    });
    
    $('#btnEliminarInformacion').click(function() {
        EliminarCronogramaTemporal();
        $("#filePlantilla").val("");
    });
    
    
     $('#btnDescargarPlantilla').click(function() {
        var filePath = 'plantilla/GPROSAC_PCRP.xlsx';
        var link=document.createElement('a');
        link.href = filePath;
        link.download = filePath.substr(filePath.lastIndexOf('/') + 1);
        link.click();     
    });
    
    asignarLote();
	
	// Detecta cambios en los campos de filtro
	$('#txtDocumentoCliente').on('change', validarActivacionBuscarCliente);
	$('#bxFiltroProyectoVenta').on('change', validarActivacionBuscarCliente);
	$('#bxFiltroZonaVenta').on('change', validarActivacionBuscarCliente);
	$('#bxFiltroManzanaVenta').on('change', validarActivacionBuscarCliente);
	$('#bxFiltroLoteVenta').on('change', validarActivacionBuscarCliente);

	// Si txtDocumentoCliente usa Select2:
	$('#txtDocumentoCliente').on('select2:select', validarActivacionBuscarCliente);

  
}

function ValidarFileRequerido() {
    var flat = true;
    if ($("#filePlantilla").val() === "" || $("#filePlantilla").val() === null) {
        $("#filePlantilla").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccione la plantilla del cronograma de pagos", "info");
        flat = false;
    } else if ($("#cbxLote").val() === "" || $("#cbxLote").val() === null) {
        $("#cbxLote").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccione el lote de la venta", "info");
        flat = false;
    } 
    return flat;
    
}

function LlenarCronogramaPagosManual() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnConsultarCronogramaManual": true,
        "cbxLote": $("#cbxLote").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarCronogramaGenerados, null, 10000, null);
}

function respuestaBuscarCronogramaGenerados(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaCronogramaTemporal(dato.data);
}

var tablaCronogramaManual = null;
function LlenarTabalaCronogramaTemporal(datos) {
    if (tablaCronogramaManual) {
        tablaCronogramaManual.destroy();
        tablaCronogramaManual = null;
    }

    tablaCronogramaManual = $('#TablaCronogramaManual').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "0%"
            },
            {
                'aTargets': [1],
                'ordering': false,
                'width': "0%"
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
            { "data": "fecha_vencimiento" },
            { "data": "item_letra" },
            { "data": "monto_letra" },
            { "data": "interes_amortizado" },
            { "data": "capital_amortizado" },
            { "data": "capital_vivo" },
            { "data": "registro" }
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

function ImportarCronogramaTemporal() {
    if(ValidarFileRequerido()){
    bloquearPantalla("Procesando...");
    var file_data = $('#filePlantilla').prop('files')[0];    
    var form_data = new FormData();  
    var dataa = $("#cbxLote").val();                  
    form_data.append('filePlantilla', file_data);
    form_data.append('data', dataa);
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD02_Venta/M03MD02_CargarCronograma.php",
        data: form_data,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    //console.log(dato);
                    mensaje_alerta("CORRECTO!", dato.data, "success");
                    //$('#TablaCronogramaManual').DataTable().ajax.reload();
                    LlenarCronogramaPagosManual();
                }, 100);
                return;
            } else {
                mensaje_alerta("ERROR!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });
    }
}

function ValidarFileRequerido2() {
    var flat = true;
    if ($("#cbxLote").val() === "" || $("#cbxLote").val() === null) {
        $("#cbxLote").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccione el lote de la venta", "info");
        flat = false;
    } 
    return flat;
    
}

function EliminarCronogramaTemporal() {
    if(ValidarFileRequerido2()){
    var data = {
        "btnEliminarCronogramaTemporal": true,
        "cbxLote": $("#cbxLote").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                mensaje_alerta("CORRECTO!", dato.data, "success");
                LlenarCronogramaPagosManual();
            } else {
                mensaje_alerta("ERROR!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });
    }
}

function TipoCronograma(tipo_cro){
    if(tipo_cro == "1"){
       $("#formularioCargaCronogramaPagos").hide();
       $("#filePlantilla").prop("disabled", true);
    }else{
       $("#formularioCargaCronogramaPagos").show();
       $("#filePlantilla").prop("disabled", false);
       //$('#TablaCronogramaManual').DataTable().ajax.reload();
       LlenarCronogramaPagosManual();
    }
}

function ValidarTipoMonedaPP(tipo_mod){
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnValidarMonedaPP": true,
        "cbxTipoMonedaPP": tipo_mod,
        "txtImportePagoPP": $("#txtImportePagoPP").val()
    };
    realizarJsonPost(url, dato, VerTMPP, null, 10000, null);
}

function VerTMPP(dato){
    //console.log(dato);
    if(dato.status=="ok"){
        if(dato.Nombre=="PEN"){
            $("#txtTipoCambioPP").prop("disabled", false);
            $("#txtTipoCambioPP").val(dato.tipocambio);
            $("#txtImportePP").val(dato.total);
        }else{
            $("#txtTipoCambioPP").prop("disabled", true);
            $("#txtTipoCambioPP").val(dato.tipocambio);
            $("#txtImportePP").val(dato.total);
        }
        
    }
}

function ValidarImportePago(pago){
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnValidarImportePag": true,
        "txtTipoCambioPP": $("#txtTipoCambioPP").val(),
        "txtImportePagoPP": pago
    };
    realizarJsonPost(url, dato, VerImportePago, null, 10000, null);
}

function VerImportePago(dato){
    //console.log(dato);
    if(dato.status=="ok"){
      $("#txtImportePP").val(dato.total);
    }
}

function EnviarAdjunto(){

    var file_data = $('#fichero').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    //alert(form_data);                             
    $.ajax({
        url: '../../models/M03_Ventas/M03MD02_Venta/M03MD02_SubirArchivo.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
            //alert(php_script_response); // display response from the PHP script, if any
           // mensaje_alerta("Correcto!", "El adjunto fue cargado correctamente", "success"); 
        }
     });

}

function RegistrarAdjuntoVenta(){
  
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnCargarArchivo": true,
        "__ID_VENTA": $("#__ID_VENTA").val(),
        "cbxTipoDocumentoAdjunto": $("#cbxTipoDocumentoAdjunto").val(),
        "txtFechaSubidaAdjunto": $("#txtFechaSubidaAdjunto").val(),
        "txtNotariaAdjunto": $("#txtNotariaAdjunto").val(),
        "txtFechaFirmaAdjunto": $("#txtFechaFirmaAdjunto").val(),
        "txtTipoMonedaImporteInicial": $("#txtTipoMonedaImporteInicial").val(),
        "txtImporteInicialAdjunto": $("#txtImporteInicialAdjunto").val(),
        "txtTipoMonedaValorCerrado": $("#txtTipoMonedaValorCerrado").val(),
        "txtValorCerradoAdjunto": $("#txtValorCerradoAdjunto").val(),
        "txtDescripcionAdjunto": $("#txtDescripcionAdjunto").val(),
        "fichero": $("#fichero").val(),
        "ValidUsuario": $("#ValidUsuario").val()

    };
    realizarJsonPost(url, dato, EjecutarCargaArchivo, null, 10000, null);  
}

function EjecutarCargaArchivo(dato){
    console.log('HOLA MUNDO');
    console.log(dato);
    if(dato.status == "ok"){
        EnviarAdjunto();
        BuscarListaDocumentosAdjuntos();
        $("#modalNuevoDocumentoAdjunto").modal("hide");
        mensaje_alerta("\u00A1Correcto!", dato.mensaje, "success"); 
    }else{
        mensaje_alerta("\u00A1Error!", dato.mensaje, "info"); 
    } 
}


/*************************** *******************************/

function CalculoDescuento(){
    var valDscto = parseFloat($("#txtValorDescuento").val()); 
    var precioVenta = parseFloat($("#txtPrecioVenta").val());
    if(valDscto>0){
        if(parseInt($("#cbxTipoDescuento").val())=="1"){
            $("#txtMontoDscto").val(valDscto.toFixed(2));
        }else{
            var calculo = (precioVenta * (valDscto/100));
            $("#txtMontoDscto").val(calculo.toFixed(2));
        }
    }else{
        $("#txtMontoDscto").val("0.00");
        if(parseInt($("#cbxTipoDescuento").val())=="1"){
            $("#txtValorDescuento").val("0.00");
        }else{
            $("#txtValorDescuento").val("0");
        }
        CalcularTotal();
    }
}

function CalcularTotal(){
    
    var dato = $("#cbxTipoInmueble").val();
    
    if(dato=='1'){
        $("#txtPrecioVenta").val($("#txtValorLoteCasa").val());
    }else{
        $("#txtPrecioVenta").val($("#txtValorLoteSolo").val());
    }
    
    CalculaTotalDato();    
}

function CalculaTotalDato(){
     bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnTotal": true,
        "documento": $("#txtDocumentoCliente").val(),
        "idlote": $("#cbxLote").val(),
        "precioVenta": $("#txtPrecioNegocio").val(),
        "montoInicial": $("#txtMontoInicial").val(), 
        "montoDscto": $("#txtMontoDscto").val(),
        "idReserva": $("#__ID_RESERVA_VENTA").val()
    };
    realizarJsonPost(url, dato, CalTot, null, 10000, null);
}

function CalTot(dato){
    console.log(dato);
    desbloquearPantalla();
    $("#txtImporteVenta").val(dato.precioVenta);
    $("#txtMontoInicial").val(dato.montoInicial);
    $("#txtMontoDscto").val(dato.montoDscto);    
}

function milliFormat (num) {// Agregar miles
	s=num.toString()
	if(/[^0-9\.]/.test(s)) return "invalid value";
	s=s.replace(/^(\d*)$/,"$1.");
	s=(s+"00").replace(/(\d*\.\d\d)\d*/,"$1");
	s=s.replace(".",",");
	var re=/(\d)(\d{3},)/;
	while(re.test(s)){
		s=s.replace(re,"$1,$2");
	}
	s=s.replace(/,(\d\d)$/,".$1");
	return s.replace(/^\./,"0.")
}


function VerDatosTotal(dato){
    
    if(dato.status="ok"){
        $("#txtImporteVenta").val(total);
    }
}

function humanizeNumber(n) {
  n = n.toString()
  while (true) {
    var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
    if (n == n2) break
    n = n2
  }
  return n
}

function ValidarTipoMoneda(){

    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ValidarTipoMoneda": true,
        "TipoMoneda": $("#cbxTipoMonedaVenta").val()

    };
    realizarJsonPost(url, dato, RespuestaValidarTipoMoneda, null, 10000, null);
}

function RespuestaValidarTipoMoneda(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {

        if(dato.data.nombre == "SOLES"){
            $("#txtTipoCambio").prop("disabled", false);
            $("#txtTipoCambio").val(dato.tipo_cambio);
            $("#txtTipoCambio").focus();
        }else{
            $("#txtTipoCambio").prop("disabled", false);
            $("#txtTipoCambio").val(dato.tipo_cambio);
            $("#txtTipoCambio").focus();
        }

    }else{
        $("#txtTipoCambio").prop("disabled", true);
            $("#txtTipoCambio").val("0.00");
    }
}


/****************************LIMPIAR FILTROS DE BUSQUEDA**************************** */
function LimpiarFiltro() {
    $("#txtDesdeFiltro,#txtHastaFiltro").val("");
     $('#txtDocumentoFiltro').val(null).trigger('change');
    document.getElementById('cbxCondicionFiltro').selectedIndex = 0;
}
/********************CONFIGURAR BOTONES************************* */
var Estados = { Ninguno: "Ninguno", Nuevo: "Nuevo", Modificar: "Modificar", Guardado: "Guardado", SoloLectura: "SoloLectura", Consulta: "Consulta" };
var Estado = Estados.Ninguno;

function BloquearTodo(valor_c) {
    $("#txtDocumentoCliente,#btnBuscarCliente,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxTipoComprobante,#txtSerieComprobante,#txtNumeroComprobante,#cbxTipoInmueble,#cbxTipoCasa,#cbxCondicionVenta,#cbxTipoMonedaVenta,#cbxTipoDescuento,#txtDescuento,#cbxTipoCreditor,#txtFechaPrimerPagor,#txtMontoCuotaInicialr,#txtMontoCuotaInicialr").prop("disabled", valor_c);
    $("#formularioRegistrarGeneralCliente").addClass("disabled-form");
    $("#formularioRegistrarGeneralLote").addClass("disabled-form");
    $("#formularioRegistrarDocumentoVenta").addClass("disabled-form");
    $("#formularioReservasRelacionadasAlCliente").addClass("disabled-form");

}

function HabilitarCampos(valor_c) {
    $("#txtDocumentoCliente,#btnBuscarCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#cbxTipoComprobante,#txtSerieComprobante,#txtNumeroComprobante,#cbxTipoInmueble,#cbxCondicionVenta,#cbxTipoMonedaVenta,#cbxTipoDescuento").prop("disabled", valor_c);
}

function HabilitarCamposModificar(valor_c) {
    $("#txtDocumentoCliente,#btnBuscarCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#cbxTipoComprobante,#txtSerieComprobante,#txtNumeroComprobante,#cbxTipoInmueble,#cbxCondicionVenta,#cbxTipoMonedaVenta,#cbxTipoDescuento, #cbxTipoVenta, #txtPrecioVenta").prop("disabled", valor_c);
    var tipoPago = $("#cbxCondicionVenta").val();
    if(tipoPago == "2"){
        $("#cbxTipoCreditor, #txtMontoCuotaInicialr").prop("disabled", valor_c);
    }
    
}

/***************************CONFIGURACION ESTADO BOTONES************************* */
function Ninguno() {

    var dato_cliente = $("#__ID_CLIENTE").val();
    if((dato_cliente == "ninguno") || (dato_cliente == "")){
        
        //ESTA SECCION MUESTRA EL PANEL CON LA LISTA DE VENTAS REGISTRADAS
        Estado = Estados.Ninguno;
        $("#nuevo").prop('disabled', false);
        $("#modificar").prop('disabled', true);
        $("#cancelar").prop('disabled', true);
        $("#guardar").prop('disabled', true);
        $("#eliminar").prop('disabled', true);
        $("#adjuntos").prop('disabled', true);
        $("#contenido_registro").hide();
        $("#contenido_lista").show();
        BusacarVentaPaginado();
        BusacarVentaReporte();
        BloquearTodo(true);
        LimpiarCamposRegistro();
        $("#cbxTipoCronograma").prop('disabled', true);
        
    }else{
        
        //MUESTRA EL PANEL CON LOS CAMPOS DE REGISTRO PARA UNA NUEVA VENTA ENTRANTE
        Estado = Estados.Nuevo;
        $("#nuevo").prop('disabled', true);
        $("#modificar").prop('disabled', true);
        $("#cancelar").prop('disabled', false);
        $("#guardar").prop('disabled', false);
        $("#eliminar").prop('disabled', true);
        $("#adjuntos").prop('disabled', true);
        $("#contenido_registro").show();
        $("#contenido_lista").hide();
        $("#contenedorArchivosAdjuntos").hide();
        HabilitarCampos(false);
        LimpiarCamposRegistro();
        $("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
        $("#txtDocumentoCliente").prop('disabled', true);
        var dato_reserva = $("#__ID_RESERVA_VENTA").val();
        VerIdCliente(dato_cliente);
        ListarPagosPrevios();
        CalcularTotal();
        $("#cbxTipoCronograma").prop('disabled', true);
    }
}

function Nuevo() {
    Estado = Estados.Nuevo;
    $("#nuevo").prop('disabled', true);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', false);
    $("#guardar").prop('disabled', false);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', true);
    $("#Empresa_Gnl").prop('disabled', true);
    HabilitarCampos(false);
    LimpiarCamposRegistro();
    $("#contenido_registro").show();
    $("#contenido_lista").hide();
    $("#contenedorArchivosAdjuntos").hide();
    $("#formularioRegistrarGeneralCliente").removeClass("disabled-form");
    $("#formularioRegistrarGeneralLote").removeClass("disabled-form");
    $("#formularioRegistrarDocumentoVenta").removeClass("disabled-form");
    $("#formularioReservasRelacionadasAlCliente").removeClass("disabled-form");
    $("#txtDocumentoCliente").focus();
    $("#cbxTipoCronograma").prop('disabled', false);
	
	$("#btnBuscarCliente").prop('disabled', true); // Desactivar por defecto
}

function Modificar() {
    Estado = Estados.Modificar;
    $("#nuevo").prop('disabled', true);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', false);
    $("#guardar").prop('disabled', false);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', false);
    $("#contenedorArchivosAdjuntos").show();
    VerificaVenta();
    $("#formularioRegistrarGeneralCliente").removeClass("disabled-form");
    $("#formularioRegistrarGeneralLote").removeClass("disabled-form");
    $("#formularioRegistrarDocumentoVenta").removeClass("disabled-form");
    $("#formularioReservasRelacionadasAlCliente").removeClass("disabled-form");
    $("#txtDocumentoCliente").focus();
    
    $("#txtFechaVenta").prop('disabled', false);
    $("#txtFechaPrimerPagor").prop('disabled', false);
    $("#txtMontoInicial").prop('disabled', false);
    $("#txtFechaEntregaCasa").prop('disabled', false);
}

function VerificaVenta() {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnVerificarVenta": true,
        "idcliente": $("#__ID_CLIENTE").val(),
        "idventa": $("#__ID_VENTA").val()
    };
    realizarJsonPost(url, dato, RespuestaVenta, null, 10000, null);
}

function RespuestaVenta(dato){
    desbloquearPantalla();
    if(dato.status=="ok"){
        HabilitarCamposModificar(true);
        $("#cbxTipoCronograma").prop('disabled', true);
    }else{
        HabilitarCamposModificar(false);
        $("#cbxTipoCronograma").prop('disabled', false);
        
        var tipo_cro = $("#cbxTipoCronograma").val();
        if(tipo_cro=="2"){
            TipoCronograma(tipo_cro);
            $("#btnCargarFormato").prop("disabled", false);
            $("#btnEliminarInformacion").prop("disabled", false);
            $("#btnDescargarPlantilla").prop("disabled", false);
            $("#filePlantilla").prop("disabled", false);
        }
    }
}

function Consulta() {
    Estado = Estados.Consulta;
    $("#nuevo").prop('disabled', false);
    $("#modificar").prop('disabled', false);
    $("#cancelar").prop('disabled', false);
    $("#guardar").prop('disabled', true);
    $("#eliminar").prop('disabled', false);
    $("#adjuntos").prop('disabled', false);
    $("#contenido_registro").show();
    $("#contenido_lista").hide();
    $("#contenedorArchivosAdjuntos").show();
    BloquearTodo(true);
}

function MostrarLista(){
    RetornaListaVentas();
}

/****************** validacion de campos de filtro busq *********************/
function validarActivacionBuscarCliente() {
    let cliente = $("#txtDocumentoCliente").val();
    let proyecto = $("#bxFiltroProyectoVenta").val();
    let zona = $("#bxFiltroZonaVenta").val();
    let manzana = $("#bxFiltroManzanaVenta").val();
    let lote = $("#bxFiltroLoteVenta").val();

    // Si cliente está lleno
    if (cliente !== null && cliente !== "") {
        $("#btnBuscarCliente").prop('disabled', false);
        return;
    }

    // Si todos los filtros están llenos
    if (proyecto && zona && manzana && lote) {
        $("#btnBuscarCliente").prop('disabled', false);
    } else {
        $("#btnBuscarCliente").prop('disabled', true);
    }
}

/****************** validacion de campos de filtro busq *********************/

/****************** LIMPIAR TODO LOS CAMPOS VISTA PRINCIPAL************************** */
function LimpiarCamposRegistro() {
    $("#__ID_RESERVACION,#__ID_CLIENTE,#txtDocumentoCliente,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxTipoComprobante,#txtSerieComprobante,#txtNumeroComprobante,#txtFechaVenta,#cbxTipoInmueble,#cbxTipoCasa#cbxCondicionVenta,#cbxTipoMonedaVenta,#txtDescuento,#txtImporteVenta,#txtCantidadLetrar,#txtTEAr,#txtFechaPrimerPagor,#txtMontoCuotaInicialr,#txtMontoCuotaInicialr,#txtContactoCliente").val("");
    $("#txtPrecioVenta,#txtMontoInicial,#txtMontoDscto,#txtImporteVenta,#txtPrecioNegocio").val("0.00");
    document.getElementById('cbxProyecto').selectedIndex = 0;
    document.getElementById('cbxZona').selectedIndex = 0;
    document.getElementById('cbxManzana').selectedIndex = 0;
    document.getElementById('cbxLote').selectedIndex = 0;
    document.getElementById('cbxTipoCasa').selectedIndex = 0;
    document.getElementById('cbxCondicionVenta').selectedIndex = 0;
    $('#txtDocumentoCliente').val(null).trigger('change');
}

function LimpiarCamposRegistro2() {
    $("#__ID_RESERVACION,#__ID_CLIENTE,#txtDocumentoCliente,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxTipoComprobante,#txtSerieComprobante,#txtNumeroComprobante,#txtFechaVenta,#cbxTipoInmueble,#cbxTipoCasa#cbxCondicionVenta,#cbxTipoMonedaVenta,#txtDescuento,#txtImporteVenta,#txtCantidadLetrar,#txtTEAr,#txtFechaPrimerPagor,#txtMontoCuotaInicialr,#txtMontoCuotaInicialr,#txtContactoCliente").val("");
    $("#txtPrecioVenta,#txtMontoInicial,#txtMontoDscto,#txtImporteVenta,#txtPrecioNegocio").val("0.00");
    document.getElementById('cbxProyecto').selectedIndex = 0;
    document.getElementById('cbxZona').selectedIndex = 0;
    document.getElementById('cbxManzana').selectedIndex = 0;
    document.getElementById('cbxLote').selectedIndex = 0;
    document.getElementById('cbxTipoCasa').selectedIndex = 0;
    document.getElementById('cbxCondicionVenta').selectedIndex = 0;
}


function VerIdCliente(id) {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnIrCliente": true,
        "idRegistro": id
    };
    realizarJsonPost(url, dato, IdCliente, null, 10000, null);
}

function IdCliente(dato){
    if(dato.status=="ok"){
        $("#txtDocumentoCliente").val(dato.documento);
        BuscarDatoCliente();
        $("#cbxZona").focus();
        //VerIdReserva(dato.documento);
    }    
}

/***********************VOLVER A LISTADO DE VENTAS ********************** */
function RetornaListaVentas() {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnIrListaVentas": true,
        "ValidUsuario": $("#txtUsr").val()
    };
    realizarJsonPost(url, dato, VerListaVentas, null, 10000, null);
}

function VerListaVentas(dato){
    if(dato.status=="ok"){
        $("#__ID_CLIENTE").val("");
        //console.log(dato);
        var ruta = dato.url;
        window.location.replace(ruta);
    }    
}

/***********************CONSULTAR ID RESERVA CON DOCUMENTO ********************** */
function VerIdReserva(documento) {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnConsultarIdReserva": true,
        "documento": documento
    };
    realizarJsonPost(url, dato, VerDatosReserva, null, 10000, null);
}

function VerDatosReserva(dato){
    if(dato.status=="ok"){
        var idReserva = dato.idreserva;
        MostrarDatosReserva(idReserva);
    }else{
        if(dato.status=="muchos"){
            mensaje_alerta("\u00A1Importante!", "El cliente cuenta con m\u00E1s de una reserva por lo tanto es importante seleccione una de las reservas para continuar con el registro de la venta. La informacion se muestra en una tabla debajo de la seccion de 'Datos Lote'.", "error");
        }else{
            mensaje_alerta("\u00A1ERROR!", "No se encontro la reserva seleccionada. Intente nuevamente la selecci\u00F3n desde el m\u00F3dulo de reservas.", "error");
        }    
    }       
}

/***********************LLENAR DATOS DE LA RESERVA DEL CLIENTE ********************** */
function MostrarDatosReserva(idreserva){    

    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnVerDatosReserva": true,
        "idReserva": idreserva
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD07_Comprobantes/M03MD07_Comprobantes.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status === "ok") {      
                var resultado = dato.data;      
                $("#txtIdVentaComprob").val(resultado.id);

                $("#txtProyectoComprob").val(resultado.proyecto);
                $("#txtZonaComprob").val(resultado.zona);
                $("#txtManzanaComprob").val(resultado.manzana);
                $("#txtLoteComprob").val(resultado.lote);
                $("#txtVendedorComprob").val(resultado.vendedor);

                $("#cbxTipoDocumentoComprob").val(resultado.tipo_documento);
                $("#txtNroDocumentoComprob").val(resultado.documento);
                $("#txtApePaternoComprob").val(resultado.apePaterno);
                $("#txtApeMaternoComprob").val(resultado.apeMaterno);
                $("#txtNombresComprob").val(resultado.nombres);

                $("#txtFecVentaComprob").val(resultado.fecha_venta);
                $("#cbxTipoMonedaComprob").val(resultado.tipo_moneda);
                $("#txtPrecioVentaComprob").val(resultado.precio_venta);
                $("#txtPagoInicialComprob").val(resultado.inicial);
                $("#txtNroLetraComprob").val(resultado.letras);

                $("#cbxCondicionComprob").val(resultado.condicion);
                $("#cbxTipoComprobanteComprob").val(resultado.tipo_comprobante);
                $("#cbxTipoInmuebleComprob").val(resultado.tipo_inmueble);
                $("#cbxTipoCasaComprob").val(resultado.tipo_casa);
                $("#txtFecEntregaComprob").val(resultado.fecha_entrega_casa);

                $("#contenido_lista").hide();
                $("#PanelCampos").show();
                ListarComprobantesVentasDoc(resultado.id);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
    });
}


/***********************LLENA ZONAS ********************** */
function LLenarZona() {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var datos = {
        "ReturnZonas": true,
        "idProyecto": $('#cbxProyecto').val()
    }
    llenarCombo(url, datos, 'cbxZona');
}

/***********************LLENA MANZANA ********************** */
function LLenarManzanas() {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var datos = {
        "ReturnManzana": true,
        "idZona": $('#cbxZona').val()
    }
    llenarCombo(url, datos, 'cbxManzana');
}
/***********************LLENA LOTE ********************** */

function LLenarLotes() {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var datos = {
        "ReturnLote": true,
        "idManzana": $('#cbxManzana').val()
    }
    llenarCombo(url, datos, 'cbxLote');
}

/**************************BUSCAR CLIENTE************************** */
function BuscarDatoCliente() {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnBuscarCliente": true,
        "tipoDocumento": $("#cbxTipoDocumento").val(),
        "documento": $("#txtDocumentoCliente").val(),
        "idlote": $("#bxFiltroLoteVenta").val(),
        "idcliente": $("#__ID_CLIENTE").val(),
        "idReserva": $("#__ID_RESERVA_VENTA").val()

    };
    realizarJsonPost(url, dato, RespuestaBuscarDatoCliente, null, 10000, null);
}

function RespuestaBuscarDatoCliente(dato) {
    $("#__ID_CLIENTE,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente").val("");
    LimpiarCamposRegistro2();
    console.log(dato);
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#__ID_CLIENTE").val(dato.data.id);
        $("#txtNombreCliente").val(dato.data.nombres);
        $("#txtApellidoPaternoCliente").val(dato.data.apellidoPaterno);
        $("#txtApellidoMaternoCliente").val(dato.data.apellidoMaterno);
        $("#txtContactoCliente").val(dato.data.contacto);
        $("#txtDocumentoClave").val(dato.data.documento);
        BuscarReservasCliente();
        //$("#cbxZona").focus();
        if(dato.accion == "si"){
            LlenarListaZonas(dato.data.idproyecto, dato.data.idzona);
            LlenarListaManzanas(dato.data.idzona, dato.data.idmanzana);
            LlenarListaLotes(dato.data.idmanzana, dato.data.idlote);
            $("#txtArea").val(dato.data.area);
            $("#txtTipoMonedaLote").val(dato.data.lote_tipo_moneda);
            $("#txtValorLoteCasa").val(dato.data.lote_valor_casa);
            $("#txtValorLoteSolo").val(dato.data.lote_valor_solo);
            /*$("#cbxTipoInmueble").val(2);
            $("#txtImporteVenta").val(dato.data.lote_valor_solo);*/
        }
        $("#txtPrecioVenta").val('0.00');
        if(dato.importe>0){
            $("#txtPrecioNegocio").val(dato.importe);
        }else{
            $("#txtPrecioNegocio").val(dato.data.lote_valor_casa);
        }
        $("#txtMontoInicial").val(dato.monto);
        $("#txtImporteVenta").val('0.00');
        
    } else {
        $("#txtDocumentoCliente").focus();
        //mensaje_cliente_no_encontrado("Importante!", "No se encontro datos para el documento y/o lote seleccionado.", IrRegistroCliente, SeguirBuscando, dato.urlRegistroCliente);
        mensaje_alerta("¡Advertencia!", dato.data, "info");
    }
}

function LlenarListaZonas(idProy, idZon) {
    var url = '../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php';
    var datos = {
        "ListarZonas": true,
        "idProyecto": idProy
    }
    llenarComboSelecionar(url, datos, "cbxZona", idZon);
}

function LlenarListaManzanas(idZon, idMan) {
    var url = '../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php';
    var datos = {
        "ListarManzanas": true,
        "idZona": idZon
    }
    llenarComboSelecionar(url, datos, "cbxManzana", idMan);
}

function LlenarListaLotes(idMan, idLot) {
    var url = '../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php';
    var datos = {
        "ListarLotes": true,
        "idManzana": idMan
    }
    llenarComboSelecionar(url, datos, "cbxLote", idLot);
}

function mensaje_cliente_no_encontrado(titulo, detalle, fun, fun2, url) {
    swal({
        title: titulo,
        text: detalle,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ir a Registrar",
        cancelButtonText: "Seguir Buscando",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            fun(url);
        } else {
            fun2();
        }
    });
}

function SeguirBuscando() {
    $("#txtDocumentoCliente").focus();
}

function IrRegistroCliente(url) {
    location.href = url;
}


/**************************BUSCAR RESERVAS RELACIONADAS AL CLIENTE ************************** */
function BuscarReservasCliente() {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnBuscarReservaCliente": true,
        "tipoDocumento": $("#cbxTipoDocumento").val(),
        "documento": $("#txtDocumentoCliente").val()

    };
    realizarJsonPost(url, dato, RespuestaBuscarReservasCliente, null, 10000, null);
}

function RespuestaBuscarReservasCliente(dato) {
    //$("#__ID_MONTO_RESERVA,#__ID_RESERVA").val("");
    LlenarTablaReservas(dato.data);
}

var tablaReservas = null;
function LlenarTablaReservas(data) {
    $("#contenedorReservas").show();
    if (data.length < 1) {
        $("#contenedorReservas").hide();
        return;
    }
    LlenarReservasRelacionadasCliente(data);
}

var tablaReservasCliente = null;
function LlenarReservasRelacionadasCliente(dato) {
    if (tablaReservasCliente) {
        tablaReservasCliente.destroy();
        tablaReservasCliente = null;
    }
    var options = $.extend(true, {}, defaults, {
        data: dato,
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
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
        "lengthChange": false,
        "info": false,
        "bSort": false,
        "paging": false,
        "pageLength": 1000, // default record count per page,
        "columns": [
            { "data": "lote" },
            { "data": "area" },
            { "data": "descTipoCasa" },
            { "data": "descTipoMonedaReserva" },
            { "data": "motoReserva" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }
    });

    tablaReservasCliente = $('#dataReservaTable').DataTable(options);
}

function InicializarAtributosTablaBusquedaRelacionada() {
    $('#dataReservaTable').on('key-focus.dt', function(e, datatable, cell) {
        tablaReservasCliente.row(cell.index().row).select();
        var data = tablaReservasCliente.row(cell.index().row).data();
        ReflejarInformacionSelccionadaReservaRelacionada(data);
    });

    $('#dataReservaTable').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = tablaReservasCliente.cell(this).index().row;
        tablaReservasCliente.row(rowIdx).select();
    });
}

function ReflejarInformacionSelccionadaReservaRelacionada(dato) {
    $("#__ID_RESERVA").val(dato.idReservacion);
    $("#cbxProyecto").val(dato.idProyecto);
    LLenarZonaId(dato.idProyecto, dato.idZona);
    LLenarManzanaId(dato.idZona, dato.idManzana);
    LLenarLoteId(dato.idManzana, dato.idLote);
    $("#txtArea").val(dato.area);
    $("#txtTipoMonedaLote").val(dato.siglaMoneda);
    $("#txtValorLoteCasa").val(dato.valorLoteCasa);
    $("#txtValorLoteSolo").val(dato.valorLoteSolo);
    if (dato.tipoCasa != null && dato.tipoCasa != "") {
        $("#cbxTipoInmueble").val(1);
        LLenarTipoCasaId(dato.idManzana,1);
    } else {
        $("#cbxTipoInmueble").val("");
        LLenarTipoCasaId(dato.idManzana,2);
    }

    $("#cbxTipoMonedaVenta").val(dato.tipoMonedaReserva);
    $("#txtMontoInicial").val(dato.motoReserva);
    CalcularTotal();
    BuscarPrecioLoteReservado(dato.idLote, dato.motoReserva);
}

/**************************BUSCAR INFORMACION LOTE PRECIO************************** */
function BuscarPrecioLoteReservado(idLote, montoReservado) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnInfoLoteReservado": true,
        "idLote": idLote,
        "montoReserva": montoReservado
    };
    realizarJsonPost(url, dato, RespuestaBuscarPrecioLoteReservado, null, 10000, null);
}

function RespuestaBuscarPrecioLoteReservado(dato) {
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);
        $("#__ID_MONTO_RESERVA").val(dato.montoReserva);
        if (parseInt($("#cbxTipoInmueble").val()) === 1) {
            $("#txtImporteVenta").val(dato.data.valoLoteCasa - dato.montoReserva);
            $("#cbxTipoCasa").focus();
        } else {
            $("#txtImporteVenta").val(dato.data.valorLoteSolo - dato.montoReserva);
            $("#cbxCondicionVenta").focus();
        }
    }
}


/**************************BUSCAR INFORMACION LOTE************************** */
function BuscarDatoLote() {
    bloquearPantalla("Buscando...");
    var valor_1 = $("#txtDocumentoCliente").val();
    var valor_2 = $("#txtNombreCliente").val();
 
   if(valor_1 ==null && valor_2==''){
        desbloquearPantalla();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, realice la busqueda de un cliente", "info");
    }else{        
        var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
        var dato = {
            "ReturnInfoLote": true,
            "idLote": $("#cbxLote").val()
        };
        realizarJsonPost(url, dato, RespuestaBuscarDatoLote, null, 10000, null);
    }
}

function RespuestaBuscarDatoLote(dato) {
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);

        //Verifica si Existe registro de reserva
       /* if(parseInt(dato.data.reserva>0)){
            if (parseInt($("#cbxTipoInmueble").val()) === 1) {
                $("#txtPrecioVenta").val(dato.data.valoLoteCasa);
                $("#txtImporteVenta").val(dato.data.valoLoteCasa);
            } else {
                $("#txtPrecioVenta").val(dato.data.valorLoteSolo);
                $("#txtImporteVenta").val(dato.data.valorLoteSolo);
            }
            //BuscarReservasCliente();
            LLenarLoteId($("#cbxManzana").val(), $("#cbxLote").val());
            $("#cbxTipoComprobante").focus();
            CalcularTotal();
        }else{
            if (parseInt($("#cbxTipoInmueble").val()) === 1) {
                $("#txtPrecioVenta").val(dato.data.valoLoteCasa);
                $("#txtImporteVenta").val(dato.data.valoLoteCasa);
            } else {
                $("#txtPrecioVenta").val(dato.data.valorLoteSolo);
                $("#txtImporteVenta").val(dato.data.valorLoteSolo);
            }
            $("#cbxTipoComprobante").focus();
            CalcularTotal();
        }*/
    }
}

/**************************BUSCAR INFORMACION LOTE PRECIO************************** */
function BuscarDatoLotePrecio() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnInfoLote": true,
        "idLote": $("#cbxLote").val()
    };
    realizarJsonPost(url, dato, RespuestaBuscarDatoLotePrecio, null, 10000, null);
}

function RespuestaBuscarDatoLotePrecio(dato) {
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);
        if (parseInt($("#cbxTipoInmueble").val()) === 1) {
            $("#txtImporteVenta").val(dato.data.valoLoteCasa);
            $("#cbxTipoCasa").focus();
        } else {
            $("#txtImporteVenta").val(dato.data.valorLoteSolo);
            $("#cbxCondicionVenta").focus();
        }

    }
}

/***************************VALIDAR DATOS REQUERIDOS****************************** */

function ValidarBusquedaLote() {
    var flat = true;
    if ($("#cbxProyecto").val() === "" || $("#cbxProyecto").val() === null) {
        $("#cbxProyecto").focus();
        $("#cbxProyectoHtml").html('(Requerido)');
        $("#cbxProyectoHtml").show();
        flat = false;
    } else if ($("#cbxZona").val() === "" || $("#cbxZona").val() === null) {
        $("#cbxZona").focus();
        $("#cbxZonaHtml").html('(Requerido)');
        $("#cbxZonaHtml").show();
        flat = false;
    } else if ($("#cbxManzana").val() === "" || $("#cbxManzana").val() === null) {
        $("#cbxManzana").focus();
        $("#cbxManzanaHtml").html('(Requerido)');
        $("#cbxManzanaHtml").show();
        flat = false;
    } else if ($("#cbxLote").val() === "" || $("#cbxLote").val() === null) {
        $("#cbxLote").focus();
        $("#cbxLoteHtml").html('(Requerido)');
        $("#cbxLoteHtml").show();
        flat = false;
    }
    return flat;
}

/**************************VALIDAR DESCUENTO************************** */
function BuscarDatoLoteParaDescuento() {
    if (ValidarBusquedaLote()) {
        bloquearPantalla("Procesando...");
        var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
        var dato = {
            "ReturnInfoLote": true,
            "idLote": $("#cbxLote").val()
        };
        realizarJsonPost(url, dato, RespuestaBuscarDatoLoteParaDescuento, null, 10000, null);
    }
}

function RespuestaBuscarDatoLoteParaDescuento(dato) {
    desbloquearPantalla();
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo").val("");

    if (dato.status == "ok") {
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);
        var descuentoMonto = $("#txtDescuento").val().trim() != "" ? parseFloat($("#txtDescuento").val()) : 0;
        var MontoAdelantado = $("#__ID_MONTO_RESERVA").val();

        if (parseInt($("#cbxTipoDescuento").val()) === 1 || $("#cbxTipoDescuento").val().trim() == "") {
            if (parseInt($("#cbxTipoInmueble").val()) === 1) {
                if ($("#__ID_MONTO_RESERVA").val().length > 0) {
                    var precio = ((parseFloat(dato.data.valoLoteCasa) - parseFloat(MontoAdelantado)) - parseFloat(descuentoMonto));
                    $("#txtImporteVenta").val(precio);
                } else {
                    var precio = (parseFloat(dato.data.valoLoteCasa) - parseFloat(descuentoMonto));
                    $("#txtImporteVenta").val(precio);
                }
            } else {
                if ($("#__ID_MONTO_RESERVA").val().length > 0) {
                    var precio = ((parseFloat(dato.data.valorLoteSolo) - parseFloat(MontoAdelantado)) - parseFloat(descuentoMonto));
                    $("#txtImporteVenta").val(precio);
                } else {
                    var precio = (parseFloat(dato.data.valorLoteSolo) - parseFloat(descuentoMonto));
                    $("#txtImporteVenta").val(precio);
                }
            }
            return;
        }

        if (parseInt($("#cbxTipoDescuento").val()) === 2) {
            descuentoMonto = descuentoMonto / 100;
            if (parseInt($("#cbxTipoInmueble").val()) === 1) {
                if ($("#__ID_MONTO_RESERVA").val().length > 0) {
                    var precio = ((parseFloat(dato.data.valoLoteCasa) - (parseFloat(dato.data.valoLoteCasa) * parseFloat(descuentoMonto))) - parseFloat(MontoAdelantado));
                    $("#txtImporteVenta").val(precio);
                } else {
                    var precio = (parseFloat(dato.data.valoLoteCasa) - (parseFloat(dato.data.valoLoteCasa) * parseFloat(descuentoMonto)));
                    $("#txtImporteVenta").val(precio);
                }
            } else {
                if ($("#__ID_MONTO_RESERVA").val().length > 0) {
                    var precio = ((parseFloat(dato.data.valorLoteSolo) - (parseFloat(dato.data.valorLoteSolo) * parseFloat(descuentoMonto))) - parseFloat(MontoAdelantado));
                    $("#txtImporteVenta").val(precio);
                } else {
                    var precio = (parseFloat(dato.data.valorLoteSolo) - (parseFloat(dato.data.valorLoteSolo) * parseFloat(descuentoMonto)));
                    $("#txtImporteVenta").val(precio);
                }
            }
            return;
        }
    }
}

/***********************LLENA TIPO CASA ********************** */

function LLenarTipoCasa() {
  
        var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
        var datos = {
            "ReturnTipoCasa": true,
            "idmanzana": $("#cbxManzana").val(),
            "propiedad": $("#cbxTipoInmueble").val(),
            "__ID_CLIENTE": $("#__ID_CLIENTE").val()            
        }
        llenarCombo(url, datos, 'cbxTipoCasa');
        
        if(parseInt($("#cbxTipoInmueble").val())=="1"){
             $("#txtFechaEntregaCasa").val("");
            $("#txtFechaEntregaCasa").prop('disabled', false);
        }else{
            $("#txtFechaEntregaCasa").val("");
            $("#txtFechaEntregaCasa").prop('disabled', true);
        } 
}

/**********************CONTROLAR BOTON GUARDAR************************ */
function Guardar() { 
    if (Estado == Estados.Nuevo) {
        GuardarNuevo();
        //BuscarIDVENTA();
        $("#btnNuevoDocumentoAdjunto").prop('disabled', false);
    } else if (Estado == Estados.Modificar) {
        GuardarActualizacion();
        //BuscarIDVENTA();
        $("#btnNuevoDocumentoAdjunto").prop('disabled', false);
    } else {
        mensaje_alerta("\u00A1ADVERTENCIA!", "Ocurrio un problema en el registro, por favor, intente nuevamente.", "warning");
    }
}

/***************************VALIDAR DATOS REQUERIDOS****************************** */
function ValidarDatosNuevoRequeridos() {
    var flat = true;
    /*if ($("#txtDocumentoCliente").val() === "" || $("#txtDocumentoCliente").val() === null) {
        $("#txtDocumentoCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, realizar la busqueda de un cliente", "info");
        flat = false;
    } else if ($("#__ID_CLIENTE").val() === "" || $("#__ID_CLIENTE").val() === null) {
        $("#txtDocumentoCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, realizar la busqueda de un Cliente V\u00E1lido", "info");
        flat = false;
    } else*/ if ($("#cbxLote").val() === "" || $("#cbxLote").val() === null) {
        $("#cbxLote").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el Lote", "info");
        flat = false;
    } else if ($("#cbxTipoComprobante").val() === "" || $("#cbxTipoComprobante").val() === null) {
        $("#cbxTipoComprobante").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el tipo de comprobante", "info");
        flat = false;
    }   else if ($("#txtFechaVenta").val() === "" || $("#txtFechaVenta").val() === null) {
        $("#txtFechaVenta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar la Fecha de Venta", "info");
        flat = false;
    } else if ($("#cbxTipoInmueble").val() === "" || $("#cbxTipoInmueble").val() === null) {
        $("#cbxTipoInmueble").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de propiedad", "info");
        flat = false;
    }else if ($("#cbxTipoMonedaVenta").val() === "" || $("#cbxTipoMonedaVenta").val() === null) {
        $("#cbxTipoMonedaVenta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el Tipo de Moneda", "info");
        flat = false;
    }else if ($("#txtPrecioNegocio").val() === "" || $("#txtPrecioNegocio").val() === null) {
        $("#txtPrecioNegocio").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el precio de negociación", "info");
        flat = false;
    } else if ($("#cbxCondicionVenta").val() === "" || $("#cbxCondicionVenta").val() === null) {
        $("#cbxCondicionVenta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el tipo de pago.", "info");
        flat = false;
    } else if ($("#txtImporteVenta").val() === "" || $("#txtImporteVenta").val() === null) {
        $("#txtImporteVenta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, verificar el Monto Total", "info");
        flat = false;
    } 
    return flat;
    
}

/***************************GUARDAR NUEVA VENTA****************************** */
function GuardarNuevo() {
    if (ValidarDatosNuevoRequeridos()) {
        if (ValidarDatosRequeridosCredito()) {
            bloquearPantalla("Guardando...");
            var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
            var dato = {
                "ReturnGuardarVenta": true,
                "idCliente": $("#__ID_CLIENTE").val().trim(),
                "idLote": $("#cbxLote").val().trim(),
                "tipoComprobante": $("#cbxTipoComprobante").val(),
                "serie": $("#txtSerieComprobante").val(),
                "numero": $("#txtNumeroComprobante").val(),
                "fechaVenta": $("#txtFechaVenta").val(),
                "tipoInmobiliario": $("#cbxTipoInmueble").val(),
                "tipoCasa": $("#cbxTipoCasa").val(),
                "condicion": $("#cbxCondicionVenta").val(),
                "tipoMoneda": $("#cbxTipoMonedaVenta").val(),
                "descuentoMonto": $("#txtDescuento").val(),
                "total": $("#txtImporteVenta").val(),
                "totalNegociado": $("#txtPrecioNegocio").val(),
                "tipoCredito": $("#cbxTipoCreditor").val(),
                "cantidadLetra": $("#txtCantidadLetrar").val(),
                "tea": $("#txtTEAr").val(),
                "primeraFechaPago": $("#txtFechaPrimerPagor").val(),
                "cuotaInicial": $('#checkCuotaInicialr').prop('checked'),
                "montoCuotaInicial": $("#txtMontoCuotaInicialr").val(),
                "ValidUsuario": $("#ValidUsuario").val(),
                "txtFechaEntregaCasa": $("#txtFechaEntregaCasa").val(),
                "cbxTipoVenta": $("#cbxTipoVenta").val(),
                "cbxTipoCronograma": $("#cbxTipoCronograma").val(),
                "idReservacion": $("#__ID_RESERVA").val(),
                "bxFiltroVendedor": $("#cbxVendedor").val()
            };
            realizarJsonPost(url, dato, respuestaGuardarNuevoRegistro, null, 10000, null);
        }
    }
}

function BuscarIDVENTA() {
    var data = {
        "BuscarIdVenta": true,
        "idLote": $("#cbxLote").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            console.log(dato);
            $("#__ID_VENTA").val(dato.data);            
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });
}

/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarNuevoRegistro(dato) {
    desbloquearPantalla();
    console.log(dato.res);
    if (dato.status == "ok") {
        //Ninguno();
        $("#__ID_VENTA").val(dato.idventa);
        $("#btnNuevoDocumentoAdjunto").prop('disabled', false);
        $("#contenedorArchivosAdjuntos").show();
        mensaje_alerta("\u00A1Guardado!", "El registro de Venta se guardo con exito. A continuacion, adjunte el documento relacionado con el registro de venta.", "success");
        return;
    } else {
        if (dato.status == "error") {
            mensaje_alerta("\u00A1Error!", dato.data, "info");
        }else{
            mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
        }
    }
}

/***************************VALIDAR DATOS ACTUALIZACION REQUERIDOS****************************** */
function ValidarDatosActualizacionRequeridos() {
    var flat = true;
    /*if ($("#txtDocumentoCliente").val() === "" || $("#txtDocumentoCliente").val() === null) {
        $("#txtDocumentoCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, realizar la busqueda de un cliente", "info");
        flat = false;
    } else if ($("#__ID_CLIENTE").val() === "" || $("#__ID_CLIENTE").val() === null) {
        $("#txtDocumentoCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, realizar la busqueda de un Cliente V\u00E1lido", "info");
        flat = false;
    } else*/ if ($("#__ID_VENTA").val() === "" || $("#__ID_VENTA").val() === null) {
        $("#txtDocumentoCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Intentar nuevamente, ocurri\u00F3 un problema", "info");
        flat = false;
    } else if ($("#cbxLote").val() === "" || $("#cbxLote").val() === null) {
        $("#cbxLote").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el Lote", "info");
        flat = false;
    } else if ($("#cbxTipoComprobante").val() === "" || $("#cbxTipoComprobante").val() === null) {
        $("#cbxTipoComprobante").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el tipo de comprobante", "info");
        flat = false;
    } /*else if ($("#txtSerieComprobante").val() === "" || $("#txtSerieComprobante").val() === null) {
        $("#txtSerieComprobante").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la Serie del Comprobante", "info");
        flat = false;
    } else if ($("#txtNumeroComprobante").val() === "" || $("#txtNumeroComprobante").val() === null) {
        $("#txtNumeroComprobante").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el N\u00FAmero del Comprobante", "info");
        flat = false;
    } */else if ($("#txtFechaVenta").val() === "" || $("#txtFechaVenta").val() === null) {
        $("#txtFechaVenta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar la Fecha de Venta", "info");
        flat = false;
    } else if ($("#cbxCondicionVenta").val() === "" || $("#cbxCondicionVenta").val() === null) {
        $("#cbxCondicionVenta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el Tipo de Pago", "info");
        flat = false;
    } else if ($("#cbxTipoMonedaVenta").val() === "" || $("#cbxTipoMonedaVenta").val() === null) {
        $("#cbxTipoMonedaVenta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el Tipo de Moneda", "info");
        flat = false;
    } else if ($("#txtImporteVenta").val() === "" || $("#txtImporteVenta").val() === null) {
        $("#txtImporteVenta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, verificar el Monto Total", "info");
        flat = false;
    } else if ($("#txtMontoInicial").val() === "" || $("#txtMontoInicial").val() === null) {
        $("#txtMontoInicial").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el monto de la cuota inicial pagada por el cliente.", "info");
        flat = false;
    }
    return flat;
}

/***************************VALIDAR DATOS REQUERIDOS SI ES A CREDITO****************************** */
function ValidarDatosRequeridosCredito() {
    var flat = true;
    if (parseInt($("#cbxCondicionVenta").val()) === 2) {
        if ($("#cbxTipoCreditor").val() === "" || $("#cbxTipoCreditor").val() === null) {
            $("#cbxTipoCreditor").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de cr�dito.", "info");
            flat = false;
        } else if ($("#txtCantidadLetrar").val() === "" || $("#txtCantidadLetrar").val() === null) {
            $("#txtCantidadLetrar").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la cantidad de N\u00FAmero de Letras", "info");
            flat = false;
        } else if ($("#txtTEAr").val() === "" || $("#txtTEAr").val() === null) {
            $("#txtTEAr").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la Tasa Efectiva Anual (%)", "info");
            flat = false;
        } else if ($("#txtFechaPrimerPagor").val() === "" || $("#txtFechaPrimerPagor").val() === null) {
            $("#txtFechaPrimerPagor").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la Primera Fecha de Pago", "info");
            flat = false;
        }
    }
    return flat;
}
/***************************GUARDAR ACTUALIZACION VENTA****************************** */
function GuardarActualizacion() {
    if (ValidarDatosActualizacionRequeridos()) {
        if (ValidarDatosRequeridosCredito()) {
            bloquearPantalla("Guardando...");
            var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
            var dato = {
                "ReturnActualizarVenta": true,
                "idVenta": $("#__ID_VENTA").val(),
                "idCliente": $("#__ID_CLIENTE").val().trim(),
                "idLote": $("#cbxLote").val().trim(),
                "descripcion": $("#txtDescripcion").val(),
                "tipoComprobante": $("#cbxTipoComprobante").val(),
                "serie": $("#txtSerieComprobante").val(),
                "numero": $("#txtNumeroComprobante").val(),
                "fechaVenta": $("#txtFechaVenta").val(),
                "tipoInmobiliario": $("#cbxTipoInmueble").val(),
                "tipoCasa": $("#cbxTipoCasa").val(),
                "condicion": $("#cbxCondicionVenta").val(),
                "tipoMoneda": $("#cbxTipoMonedaVenta").val(),
                "descuentoMonto": $("#txtValorDescuento").val(),
                "total": $("#txtImporteVenta").val(),
                "tipoCredito": $("#cbxTipoCreditor").val(),
                "cantidadLetra": $("#txtCantidadLetrar").val(),
                "tea": $("#txtTEAr").val(),
                "primeraFechaPago": $("#txtFechaPrimerPagor").val(),
                "cuotaInicial": $('#checkCuotaInicialr').prop('checked'),
                "montoCuotaInicial": $("#txtMontoCuotaInicialr").val(),
                "txtMontoInicial": $("#txtMontoInicial").val(),
                "idReservacion": $("#__ID_RESERVA").val(),
                "cbxTipoCronograma": $("#cbxTipoCronograma").val(),
                "totalNegociado": $("#txtPrecioNegocio").val(),
                "bxFiltroVendedor": $("#cbxVendedor").val(),
                "txtFechaEntregaCasa": $("#txtFechaEntregaCasa").val(),
                "txtUsr": $("#txtUsr").val()
            };
            realizarJsonPost(url, dato, respuestaGuardarActualizacion, null, 10000, null);
        }
    }
}
/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarActualizacion(dato) {
    desbloquearPantalla();
    console.log(dato);
    if (dato.status == "ok") {
        //Ninguno();
        mensaje_alerta("\u00A1Guardado!", "El registro de Venta se guard\u00F3 con \u00E9xito.", "success");
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

/*****************************************LLENAR TABLA LISTA********************************************* */
var tablaBusqVentaPag = null;
function BusacarVentaPaginado() {
    if (tablaBusqVentaPag) {
        tablaBusqVentaPag.destroy();
        tablaBusqVentaPag = null;
    }
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
            "url": "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnVentaPag": true,
                    "documento": $("#txtDocumentoFiltro").val(),
                    "condicion": $("#cbxCondicionFiltro").val(),
                    "desde": $("#txtDesdeFiltro").val(),
                    "hasta": $("#txtHastaFiltro").val()
                });
            }
        },
        "columns": [{
                "data": "idVenta",
                "render": function (data, type, row) {
                    var html="";
                    if(row.conformidad=='PENDIENTE'){
                        html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="IrDetalleVenta(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a> \ <a href="javascript:void(0)" class="btn btn-success-action" onclick="IrConformidad(\'' + data + '\')" title="Finalizar Venta"><i class="fas fa-check"></i></a>';
                    }else{
                        if(row.devolucion=='DEVUELTO' || row.cancelado=='CANCELADO'){
                            html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="IrDetalleVenta(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                        }else{
                            html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="IrDetalleVenta(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                        }
                    }
                    return html;
                    
                }
            },
            { "data": "fechaVenta" },
            {
                "data": "idVenta",
                "render": function (data, type, row) {
                    var html = "";
                    if(row.devolucion=='DEVUELTO'){
                        html = '<span class="badge text-center" style="background-color: #BF0000; color: white; font-weight: bold;">' + row.devolucion + '</span>';
                    }else{
                        if(row.cancelado=='CANCELADO'){
                            html = '<span class="badge text-center" style="background-color: #0065D1; color: white; font-weight: bold;">' + row.cancelado + '</span>';
                        }else{
                           if(row.conformidad=='CONFORME'){
                                html = '<span class="badge text-center" style="background-color: #00B403; color: white; font-weight: bold;">ACTIVO</span>';
                            }else{
                                html = '<span class="badge text-center" style="background-color: #E19300; color: white; font-weight: bold;">' + row.conformidad + '</span>';
                            }
                        }
                    }
                    
                    return html;
                }
            },
            { "data": "cliente" },
            { "data": "nombreLote" },
            {
                "data": "idVenta",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action text-center" onclick="VerAdjuntos(\'' + data + '\')" title="Editar"><i class="fas fa-folder-open"></i> - '+row.conteo_adjuntos+'</a>';
                }
            },
            { "data": "area" },
            { "data": "condicion" },
            { "data": "tipoMoneda" },
            { "data": "montoDescuento" },
            { "data": "tea" },
            { "data": "total" },
            { "data": "motoCuotaInicial" },            
            { "data": "vendedor" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }
    });

    tablaBusqVentaPag = $('#tableRegistroVenta').DataTable(options);
}

function VerAdjuntos(id){
    BuscarListaVerAdjuntos(id);
    $("#modalAdjuntos").modal("show");
}

/**************************LISTA DE DOCUMENTOS ADJUNTOS ************************** */
function BuscarListaVerAdjuntos(id) {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnListaVerAdjuntos": true,
        "idVenta": id
    };
    realizarJsonPost(url, dato, RespuestaBuscarListasAdjuntos, null, 10000, null);
}

function RespuestaBuscarListasAdjuntos(dato) {
    LlenarTablaVerAdjuntosVenta(dato.data);
    //console.log(dato.data);
}

var tablaVerAdjuntosVenta = null;
function LlenarTablaVerAdjuntosVenta(dato) {
    if (tablaVerAdjuntosVenta) {
        tablaVerAdjuntosVenta.destroy();
        tablaVerAdjuntosVenta = null;
    }
    var options = $.extend(true, {}, defaults, {
        data: dato,
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
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
        "lengthChange": false,
        "info": false,
        "bSort": false,
        "paging": false,
        "pageLength": 1000, // default record count per page,
        "columns": [
            { "data": "tipo_documento" },
            { "data": "fecha" },
            { "data": "notaria" },
            { "data": "fecha_firma" },
            { "data": "valor_inicial" },
            { "data": "valor_cerrado" },
            {
                "data": "adjunto",
                "render": function(data, type, row) {
                   /* return '<a href="'+row.URLadjunto+'" download="'+row.dato+'">documento.pdf</a>';*/
                   html = '<a href="javascript:void(0)" onclick="VerAdjunto(\'' + row.adjunto + '\')">Documento</a>';
                   return html;
                }
                
            },
            { "data": "nom_archivo"}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });

    tablaVerAdjuntosVenta = $('#dataAdjuntoTabla').DataTable(options);
}

function VerAdjunto(adjunto) {  
    var html = "";
          var documento = "archivos/"+adjunto+"";
          html +=
              "<object class='pdfview' type='application/pdf' data='" +
              documento +
              "' style='width: 100%'></object> ";
          $("#my_img_doc2").html(html);
          $("#modalVerAdjuntos").modal("show");  
}

function IrConformidad(id){
    mensaje_condicionalDOS("\u00BFEst\u00E1 seguro que desea finalizar el registro de venta?", "Al confirmar se proceder\u00E1 con el envio de los pagos registrados en la venta, asi como la generaci\u00F3n del cronograma correspondiente segun la informaci\u00F3n ingresada.", FinalizarVenta, id);
}

function FinalizarVenta(id){
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnFinalizarVenta": true,
        "idRegistro": id
    };
    realizarJsonPost(url, dato, MensajeFinalizado, null, 10000, null);
}

function MensajeFinalizado(dato) {
    if (dato.status == "ok") {
        console.log(dato);
        mensaje_alerta("CORRECTO!", "Se finaliz\u00F3 correctamente el registro de la venta.", "success");
        BusacarVentaPaginado();
    } else {
        mensaje_alerta("ERROR!", dato.data, "error");
    }
}

function InicializarAtributosTablaBusquedaVenta() {
    $('#tableRegistroVenta').on('key-focus.dt', function(e, datatable, cell) {
        tablaBusqVentaPag.row(cell.index().row).select();
        var data = tablaBusqVentaPag.row(cell.index().row).data();
        Consulta();
        ReflejarInformacionSelccionadaVenta(data);
    });

    $('#tableRegistroVenta').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = tablaBusqVentaPag.cell(this).index().row;
        tablaBusqVentaPag.row(rowIdx).select();
    });
}

function IrDetalleVenta(ID){    

    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnIrDetalleVenta": true,
        "idRegistro": ID
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            var data = dato.data;
            console.log(data);
            if (dato.status === "ok") {   
                Consulta();
                $("#__ID_VENTA").val(data.id);
                $("#_ID_CLIENTE").val(data.idCliente);
                $("#__ID_CLIENTE").val(data.idCliente);
                //$("#__ID_RESERVA").val(data.idReserva);
                //$("#__ID_MONTO_RESERVA").val(data.montoReserva);
                $("#cbxTipoDocumento").val(data.tipo_documento);
                $("#txtDocumentoCliente").val(data.documento);
                $("#txtNombreCliente").val(data.nombres);
                $("#txtApellidoPaternoCliente").val(data.apePaterno);
                $("#txtApellidoMaternoCliente").val(data.apeMaterno);
                $("#txtContactoCliente").val(data.celular);
                $("#cbxProyecto").val(data.proyecto);
                LLenarZonaId(data.proyecto, data.zona);
                LLenarManzanaId(data.zona, data.manzana);
                LLenarLoteId(data.manzana, data.lote);
                $("#txtArea").val(data.area);
                $("#txtTipoMonedaLote").val(data.lote_moneda);
                $("#txtValorLoteCasa").val(data.lote_con_casa);
                $("#txtValorLoteSolo").val(data.lote_sin_casa);
                $("#cbxTipoComprobante").val(data.tipo_comprobante);
                //$("#txtSerieComprobante").val(data.serie);
                //$("#txtNumeroComprobante").val(data.numero);
                $("#txtFechaVenta").val(data.fecha_venta);
                $("#cbxTipoInmueble").val(data.tipo_inmueble);
                LLenarTipoCasaId(data.manzana, data.tipo_inmueble, data.tipo_casa);
                $("#cbxCondicionVenta").val(data.tipo_pago);
                if (parseInt(data.tipo_pago) === 2) {
                    $("#detalleCredito").show();
                } else {
                    $("#detalleCredito").hide();
                }
                $("#cbxTipoMonedaVenta").val(data.venta_moneda);
                $("#txtFechaEntregaCasa").val(data.fecha_entrega_casa);
                $("#cbxTipoVenta").val(data.tipo_venta);
                $("#txtTipoCambio").val(data.tipo_cambio);
                $("#cbxTipoDescuento").val(data.tipo_dscto);
                $("#txtValorDescuento").val(data.dscto_monto);
                $("#txtPrecioVenta").val(data.precio_venta_inicial);
                $("#txtPrecioNegocio").val(data.precio_negociado);
                $("#txtMontoInicial").val(data.inicial);
                $("#txtMontoDscto").val(data.dscto_monto);
                $("#txtImporteVenta").val(data.capital_vivo_inicial);
                $("#cbxTipoCreditor").val(data.tipo_credito);
                $("#txtCantidadLetrar").val(data.letras);
                $("#txtTEAr").val(data.tea);
                $("#txtFechaPrimerPagor").val(data.primera_fecha);
                $("#cbxVendedor").val(data.id_vendedor);
                BuscarReservasCliente();
                BuscarListaDocumentosAdjuntos();
                ListarPagosPrevios();
                //CalcularTotal();
                
                if(data.conformidad=='1'){
                    $("#btnAgregarPagoPrevio").hide();
                }
                $("#cbxTipoCronograma").val(data.tipo_cronograma);
                if(data.tipo_cronograma=="2"){
                    var tipo_cro = $("#cbxTipoCronograma").val();
                    TipoCronograma(tipo_cro);
                    $("#btnCargarFormato").prop("disabled", true);
                    $("#btnEliminarInformacion").prop("disabled", true);
                    $("#btnDescargarPlantilla").prop("disabled", true);
                    $("#filePlantilla").prop("disabled", true);
                }
                $("#contenedorArchivosAdjuntos").hide();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function ListarPagosPrevios(){
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnPagosPreviosVenta": true,
        "idreserva": $("#__ID_RESERVA_VENTA").val(),
        "idVenta": $("#__ID_VENTA").val(),
        "idLote": $("#cbxLote").val(),
        "documento": $("#txtDocumentoClave").val()
    };
    realizarJsonPost(url, dato, ListarComprobantesPagosPrevios, null, 10000, null);
}

function ListarComprobantesPagosPrevios(dato) {
    desbloquearPantalla();
    console.log(dato);
    ListarComprobantesPP(dato.data);
}

var tablaComprobantesPagosP = null;
function ListarComprobantesPP(datos) {
    if (tablaComprobantesPagosP) {
        tablaComprobantesPagosP.destroy();
        tablaComprobantesPagosP = null;
    }

    tablaComprobantesPagosP = $('#TablaPagosPrevios').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "0%"
            },
            {
                'aTargets': [1],
                'ordering': false,
                'width': "0%"
            }
        ],
        ordering: false,
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
        columns: [{
            "data": "id",
            "render": function (data, type, row) {
                var html = "";
                if (row.categoria === 'PAGO VENTA' && row.conformidad!='1') {
                   /* html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="IdDetallePagosPrevios(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a> \ <a href="javascript:void(0)" class="btn btn-delete-action" onclick="IrEliminarPagosPrevios(\'' + data + '\')" title="Editar"><i class="fas fa-trash"></i></a>';*/
                    html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="IrEliminarPagosPrevios(\'' + data + '\')" title="Editar"><i class="fas fa-trash"></i></a>';
                }else{
                    /*html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="IdDetallePagosPrevios(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a>';*/
                    html = "";
                }
                return html;
            }
        },
        {
            "data": "categoria",
            "render": function(data, type, row) {
                var html = "";
                if (data === 'PAGO RESERVA') {
                    html = '<span class="label label-warning">' + row.categoria + '</span>';
                } else {
                    html = '<span class="label label-success">' + row.categoria + '</span>';                        
                }
                return html;
            }
        },
        { "data": "fecha_pago" },
        { "data": "tipo_moneda" },
        { "data": "importe" },
        { "data": "voucher",
           "render": function(data, type, row, host) {
                var nombre = row.voucher;
                var html = "";
                if (row.categoria === 'PAGO RESERVA') {
                    //html = '<a href="'+"../../M03_Ventas/M03SM01_Reservacion/archivos/"+row.voucher+'" download="'+row.fecha_pago+"_"+row.dato_lote+"_"+row.dato_cliente+"_"+row.dato_categoria+'">voucher_pago.pdf</a>';
                
                    var ruta='../../M03_Ventas/M03SM01_Reservacion/archivos/';
                    html = '<a href="javascript:void(0)" onclick="VerVoucher(\'' + row.voucher + '\',\'' + ruta + '\')">Voucher</a>';
                    
                } else {
                    //html = '<a href="'+"../../M03_Ventas/M03SM02_Venta/archivos/"+row.voucher+'" download="'+row.fecha_pago+"_"+row.dato_lote+"_"+row.dato_cliente+"_"+row.dato_categoria+'">voucher_pago.pdf</a>';                        
                
                    var ruta='../../M03_Ventas/M03SM02_Venta/archivos/';
                    html = '<a href="javascript:void(0)" onclick="VerVoucher(\'' + row.voucher + '\',\'' + ruta + '\')">Voucher</a>';
                }
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

function VerVoucher(voucher, ruta) {  
    var data = {
      btnMostrarVoucher: true,
      "dato_voucher": voucher,
      "ruta": ruta
    };
    $.ajax({
      type: "POST",
      url: "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            if(dato.formato == "jpeg"){
                var html = "";
                var documento = ""+dato.ruta+dato.voucher+"";
                html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                $("#my_img_doc_voucher").html(html);
                $("#modalVerVoucher").modal("show");   
            }else{
                var html = "";
                var documento = ""+dato.ruta+dato.voucher+"";
                html += "<object class='pdfview' type='application/pdf' data='" +documento +"' style='width: 100%'></object> ";
                $("#my_img_doc_voucher").html(html);
                $("#modalVerVoucher").modal("show");        
            }
          
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
 }

function AbrirPopupPagosPrevios(){
    $('#modalPagosPrevios').modal('show');
}

/**************************BUSCAR DETALLE PAGOS PREVIOS ************************** */
function IdDetallePagosPrevios(id) {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnDetallePagosPrevios": true,
        "idRegistro": id
    };
    realizarJsonPost(url, dato, RespuestaDetallePP, null, 10000, null);
}

function RespuestaDetallePP(dato) {
    //console.log(dato);
    if (dato.status == "ok") {
        $("#cbxTipoMonedaPP2").val(dato.tipo_moneda);
        $("#txtImportePP2").val(dato.importe);
        $("#cbxTipoPagoPP2").val(dato.tipo_pago);
        $("#txtFechaPagoPP2").val(dato.fecha_pago);
        $("#txtDescripcionPP2").val(dato.descripcion);
        $('#modalDetallePagosPrevios').modal('show');
    } else {
        mensaje_alerta("ERROR!", dato.data + "\n" + dato.dataDB, "error");
    }
}

/**************************BUSCAR ELIMINAR PAGOS PREVIOS ************************** */
function IrEliminarPagosPrevios(id) {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "btnEliminarPagosPrevios": true,
        "idRegistro": id
    };
    realizarJsonPost(url, dato, RespuestaEliminarPP, null, 10000, null);
}

function RespuestaEliminarPP(dato) {
    if (dato.status == "ok") {
        ListarPagosPrevios();
        CalcularTotal();
        mensaje_alerta("ELIMINADO!", "Se elimin\u00F3 el correctamente el pago seleccionado.", "success");
        return;
    } else {
        mensaje_alerta("ERROR!", dato.data, "error");
    }
}

/***************************Reflejar Seleccion a Editar****************************** */
function ReflejarInformacionSelccionadaVenta(data) {
    $("#__ID_VENTA").val(data.idVenta);
    $("#__ID_CLIENTE").val(data.idCliente);
    $("#__ID_RESERVA").val(data.idReserva);
    $("#__ID_MONTO_RESERVA").val(data.montoReserva);
    $("#cbxTipoDocumento").val(data.tipoDocumento);
    $("#txtDocumentoCliente").val(data.documento);
    $("#txtNombreCliente").val(data.nombres);
    $("#txtApellidoPaternoCliente").val(data.apellidoPaterno);
    $("#txtApellidoMaternoCliente").val(data.apellidoMaterno);
    $("#txtDireccionCliente").val(data.direccion);
    $("#cbxProyecto").val(data.idProyecto);
    LLenarZonaId(data.idProyecto, data.idZona);
    LLenarManzanaId(data.idZona, data.idManzana);
    LLenarLoteId(data.idManzana, data.idLote);
    $("#txtArea").val(data.area);
    $("#txtTipoMonedaLote").val(data.siglaMoneda);
    $("#txtValorLoteCasa").val(data.valorConCasa);
    $("#txtValorLoteSolo").val(data.valorSinCasa);
    $("#cbxTipoComprobante").val(data.tipoComprobante);
    $("#txtSerieComprobante").val(data.serie);
    $("#txtNumeroComprobante").val(data.numero);
    $("#txtFechaVenta").val(data.fechaVenta);
    $("#cbxTipoInmueble").val(data.tipoInmobiliario);
    if (parseInt(data.tipoInmobiliario) === 1) {
        LLenarTipoCasaId(data.idManzana, data.tipoInmobiliario);
        LLenarTipoCasa();
        $("#txtPrecioVenta").val(data.valorConCasa);
    }else{
        $("#txtPrecioVenta").val(data.valorSinCasa);
    }

    $("#cbxCondicionVenta").val(data.idCondicion);

    if (parseInt(data.idCondicion) === 2) {
        $("#detalleCredito").show();
    } else {
        $("#detalleCredito").hide();
    }

    $("#cbxTipoMonedaVenta").val(data.idTipoMoneda);
    $("#txtMontoDscto").val(data.montoDescuento);
    //$("#txtImporteVenta").val(data.total);
    $("#cbxTipoCreditor").val(data.idTipoCredito);
    $("#txtCantidadLetrar").val(data.cantidadLetra);
    $("#txtTEAr").val(data.tea);
    $("#txtFechaPrimerPagor").val(data.primeraFechaPago);
    var tieneCuotaInicial = parseInt(data.tieneCuotaInicial) === 1 ? true : false;
    $("#checkCuotaInicialr").prop('checked', tieneCuotaInicial);
    $("#txtMontoInicial").val(data.motoCuotaInicial);
    BuscarReservasCliente();
    BuscarListaDocumentosAdjuntos();
    ListarPagosPrevios();
    $("#contenedorArchivosAdjuntos").hide();
};

/*****************************************LLENAR TABLA REPORTE LISTA********************************************* */
var tablaBusqVentaReport = null;
function BusacarVentaReporte() {
    if (tablaBusqVentaReport) {
        tablaBusqVentaReport.destroy();
        tablaBusqVentaReport = null;
    }
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
        "bSort": true,
        "processing": true,
        "serverSide": true,
        "info": false,
        "paging": false,
        "lengthMenu": [
            [10, 20, 50, 100, 150],
            [10, 20, 50, 100, 150] // change per page values here
        ],
        "pageLength": 1000000000, // default record count per page,
        "ajax": {
            "url": "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnVentaPag": true,
                    "documento": $("#txtDocumentoFiltro").val(),
                    "condicion": $("#cbxCondicionFiltro").val(),
                    "desde": $("#txtDesdeFiltro").val(),
                    "hasta": $("#txtHastaFiltro").val()
                });
            }
        },
        "columns": [
            { "data": "fechaVenta" },
            { "data": "cliente" },
            { "data": "nombreLote" },
            { "data": "area" },
            { "data": "condicion" },
            { "data": "tipoMoneda" },
            { "data": "montoDescuento" },
            { "data": "tea" },
            { "data": "total" },
            { "data": "motoCuotaInicial" },
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

    tablaBusqVentaReport = $('#tableRegistroReportes').DataTable(options);
}

/********************LLENAR ZONA SELECIONANDO****************/

function LLenarZonaId(idProyecto, idZona) {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var datos = {
        "ReturnZonas": true,
        "idProyecto": idProyecto
    }
    llenarComboSelecionar(url, datos, "cbxZona", idZona);
}

/********************LLENAR MANZANA SELECIONANDO****************/

function LLenarManzanaId(idZona, idManzana) {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var datos = {
        "ReturnManzana": true,
        "idZona": idZona
    }
    llenarComboSelecionar(url, datos, "cbxManzana", idManzana);
}
/********************LLENAR LOTE SELECIONANDO****************/

function LLenarLoteId(idManzana, idLote) {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var datos = {
        "ReturnLoteActualizable": true,
        "idManzana": idManzana,
        "idLote": idLote
    }
    llenarComboSelecionar(url, datos, "cbxLote", idLote);
}
/***********************LLENA TIPO CASA ********************** */

function LLenarTipoCasaId(idmanzana, propiedad, idTipoCasa) {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var datos = {
        "ReturnTipoCasa": true,
        "idmanzana": idmanzana,
        "propiedad": propiedad,
        "idTipoCasa": idTipoCasa
    }
    llenarComboSelecionar(url, datos, 'cbxTipoCasa', idTipoCasa);
}

/************************************ELIMINAR REGISTRO VENTAS******************************** */
function Eliminar() {
    mensaje_condicionalUNO("\u00BFEst\u00E1s seguro de eliminar?", "Al confirmar se proceder\u00E1 a eliminar el registro selecionado", ConfirmarEliminarRegistroVentas, CancelEliminar, "");
}

function CancelEliminar() {
    return;
}

function ConfirmarEliminarRegistroVentas() {
    bloquearPantalla("Eliminando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnEliminarVenta": true,
        "idVenta": $("#__ID_VENTA").val()
    };
    realizarJsonPost(url, dato, RespuestaConfirmarEliminarRegistroVentas, null, 10000, null);
}

function RespuestaConfirmarEliminarRegistroVentas(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        Ninguno();
        setTimeout(function() {
            mensaje_alerta("\u00A1Eliminado!", dato.data, "success");
        }, 100);
        return;
    } else {
        setTimeout(function() {
            mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
        }, 100);
    }
}

/********************CONFIGURAR ESTADO POP UP ADJUTOS************************* */
var EstadosAdjuntoDoc = { Ninguno: "Ninguno", Nuevo: "Nuevo", Modificar: "Modificar" };
var EstadoAdjunto = EstadosAdjuntoDoc.Ninguno;

/**************ABRIR  MODAL NUEVO ADJUNTAR  DOCUMENTOS************** */
function NuevoAdjuntarDocumento() {
    EstadoAdjunto = EstadosAdjuntoDoc.Nuevo;
    $("#cbxTipoDocumentoAdjunto,#txtFechaSubidaAdjunto,#txtDescripcionAdjunto").val("");
    $(".file-selected").html("");
    $('#modalNuevoDocumentoAdjunto').modal('show');
    var fecha = new Date();
    var mes = fecha.getMonth()+1; //obteniendo mes
    var dia = fecha.getDate(); //obteniendo dia
    var ano = fecha.getFullYear(); //obteniendo a�o
    if(dia<10)
    dia='0'+dia; //agrega cero si el menor de 10
    if(mes<10)
    mes='0'+mes //agrega cero si el menor de 10
    document.getElementById('txtFechaSubidaAdjunto').value=ano+"-"+mes+"-"+dia;
}

/**************ABRIR  MODAL ACTUALIZAR ADJUNTAR  DOCUMENTOS************** */
function AbrirActualizarAdjuntarDocumento(id) {
    bloquearPantalla("Guardando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnDetalleAdjuntoInfo": true,
        "idArchivoVenta": id
    };
    realizarJsonPost(url, dato, respuestaAbrirActualizarAdjuntarDocumento, null, 10000, null);
}

function respuestaAbrirActualizarAdjuntarDocumento(dato) {
    desbloquearPantalla();
    console.log(dato);
    if (dato.status == "ok") {
        $('#modalNuevoDocumentoAdjunto').modal('show');
        EstadoAdjunto = EstadosAdjuntoDoc.Modificar;

        $("#_ID_ARCHIVO_VENTA").val(dato.data.id);
        $("#cbxTipoDocumentoAdjunto").val(dato.data.tipodocumento);
        $("#txtFechaSubidaAdjunto").val(dato.data.fecha);
        $("#txtNotariaAdjunto").val(dato.data.notaria);
        $("#txtFechaFirmaAdjunto").val(dato.data.fecha_firma);
        $("#txtTipoMonedaImporteInicial").val(dato.data.tp_inicial);
        $("#txtImporteInicialAdjunto").val(dato.data.importe_inicial);
        $("#txtTipoMonedaValorCerrado").val(dato.data.tp_valor_cerrado);
        $("#txtValorCerradoAdjunto").val(dato.data.valor_cerrado);
        $("#txtDescripcionAdjunto").val(dato.data.descripcion);
        $("#txtNombreArchivo").val(dato.data.nom_archivo);
        
        return;
    } else {
        EstadoAdjunto = EstadosAdjuntoDoc.Ninguno;
        mensaje_alerta("\u00A1Error!", "El Ocurrio un problema verifique por favor", "error");
    }
}
/**********************SUBIR ARCHIVO ADJUNTO PDF********************************* */

function SubirDocumentoAdjunto() {
    bloquearPantalla("Cargando...");
    var formData = new FormData($("#filesFormAdjuntosVenta")[0]);
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(data) {
            desbloquearPantalla();
            $("#fileSubirAdjuntoVenta").val('');
            if (data.status === "ok") {
                $(".file-selected").html(data.nombreArchivoReal + ' <i class="fas fa-check-circle" style="color:#28b779;"></i>');
                $("#_ID_ARCHIVO").val(data.data.idArchivo);
                return;
            } else {
                $(".file-selected").html("");
                mensaje_alerta("\u00A1Error!", data.mensaje, "error");
                return;
            }
        }
    });
}

/**********************CONTROLAR BOTON GUARDAR************************ */
function GuardarInformacionAdjunto() {
    if (EstadoAdjunto == EstadosAdjuntoDoc.Nuevo) {
        //GuardarNuevoInformacionAdjunto();
        RegistrarAdjuntoVenta(); 
    } else if (EstadoAdjunto == EstadosAdjuntoDoc.Modificar) {
        GuardarActualizacionInformacionAdjunto();
    } else {
        mensaje_alerta("\u00A1ADVERTENCIA!", "Ocurrio un problema en el registro, por favor, intente nuevamente.", "warning");
    }
}

/***************************GUARDAR INFORMACION ADJUNTO****************************** */
function GuardarNuevoInformacionAdjunto() {
    bloquearPantalla("Guardando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnGuardarAdjuntoInfo": true,
        "idArchivo": $("#_ID_ARCHIVO").val().trim(),
        "idVenta": $("#__ID_VENTA").val().trim(),
        "idTipoDocumento": $("#cbxTipoDocumentoAdjunto").val(),
        "descripcion": $("#txtDescripcionAdjunto").val(),
        "fechaAdjunto": $("#txtFechaSubidaAdjunto").val(),
        "notaria": $("#txtNotariaAdjunto").val(),
        "fechafirma": $("#txtFechaFirmaAdjunto").val(),
        "importeInicial": $("#txtImporteInicialAdjunto").val(),
        "valorCerrado": $("#txtValorCerradoAdjunto").val(),
        "txtTipoMonedaImporteInicial": $("#txtTipoMonedaImporteInicial").val(),
        "txtTipoMonedaValorCerrado": $("#txtTipoMonedaValorCerrado").val()
    };
    realizarJsonPost(url, dato, respuestaGuardarNuevoInformacionAdjunto, null, 10000, null);
}
/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarNuevoInformacionAdjunto(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        $('#modalNuevoDocumentoAdjunto').modal('hide');
        EstadoAdjunto = EstadosAdjuntoDoc.Ninguno;
        BuscarListaDocumentosAdjuntos();
        mensaje_alerta("\u00A1Guardado!", "El documento adjunto se guardo con exito.", "success");
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

/***************************ACTUALIZAR INFORMACION ADJUNTO****************************** */
function GuardarActualizacionInformacionAdjunto() {
    bloquearPantalla("Guardando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnActualizarAdjuntoInfo": true,
        "idArchivoVenta": $("#_ID_ARCHIVO_VENTA").val().trim(),
        "__ID_VENTA": $("#__ID_VENTA").val(),
        "cbxTipoDocumentoAdjunto": $("#cbxTipoDocumentoAdjunto").val(),
        "txtFechaSubidaAdjunto": $("#txtFechaSubidaAdjunto").val(),
        "txtNotariaAdjunto": $("#txtNotariaAdjunto").val(),
        "txtFechaFirmaAdjunto": $("#txtFechaFirmaAdjunto").val(),
        "txtTipoMonedaImporteInicial": $("#txtTipoMonedaImporteInicial").val(),
        "txtImporteInicialAdjunto": $("#txtImporteInicialAdjunto").val(),
        "txtTipoMonedaValorCerrado": $("#txtTipoMonedaValorCerrado").val(),
        "txtValorCerradoAdjunto": $("#txtValorCerradoAdjunto").val(),
        "txtDescripcionAdjunto": $("#txtDescripcionAdjunto").val(),
        "fichero": $("#fichero").val(),
        "txtNombreArchivo": $("#txtNombreArchivo").val()
    };
    realizarJsonPost(url, dato, respuestaGuardarActualizacionInformacionAdjunto, null, 10000, null);
}
/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarActualizacionInformacionAdjunto(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        $('#modalNuevoDocumentoAdjunto').modal('hide');
        EstadoAdjunto = EstadosAdjuntoDoc.Ninguno;

        if(dato.adjunto == "si"){
            ActualizarAdjunto();
        }

        BuscarListaDocumentosAdjuntos();
        mensaje_alerta("\u00A1Guardado!", "El documento adjunto se actualiz\u00F3 con \u00E9xito.", "success");
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

function ActualizarAdjunto(){

   var file_data = $('#fichero').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    //alert(form_data);                             
    $.ajax({
        url: '../../models/M03_Ventas/M03MD02_Venta/M03MD02_ActualizarArchivo.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
            //alert(php_script_response); // display response from the PHP script, if any
           // mensaje_alerta("Correcto!", "El adjunto fue cargado correctamente", "success"); 
        }
     });
}

/**************************LISTA DE DOCUMENTOS ADJUNTOS ************************** */
function BuscarListaDocumentosAdjuntos() {
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnListaDocuemntosAdjuntos": true,
        "idVenta": $("#__ID_VENTA").val()
    };
    realizarJsonPost(url, dato, RespuestaBuscarListaDocumentosAdjuntos, null, 10000, null);
}

function RespuestaBuscarListaDocumentosAdjuntos(dato) {
    LlenarTablaAdjuntosVenta(dato.data);
    console.log('--------------INICIO----------------');
    console.log(dato);
    console.log('----------------FINAL--------------');
}

var tablaAdjuntosVenta = null;
function LlenarTablaAdjuntosVenta(dato) {
    if (tablaAdjuntosVenta) {
        tablaAdjuntosVenta.destroy();
        tablaAdjuntosVenta = null;
    }
    var options = $.extend(true, {}, defaults, {
        data: dato,
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
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
        "lengthChange": false,
        "info": false,
        "bSort": false,
        "paging": false,
        "async": false,
        "pageLength": 1000, // default record count per page,
        "columns": [{
                "data": "id",
                "render": function(data, type, row) {
                    return '<button class="btn btn-edit-action"   onclick="AbrirActualizarAdjuntarDocumento(\'' + data + '\')"><i class="fas fa-edit"></i></button>\
                    <button class="btn btn-info btn-delete-action"  onclick="EliminarDocumentoAdjunto(\'' + data + '\')"><i class="fas fa-trash"></i></button>';
                }
            },
            { "data": "tipo_documento" },
            { "data": "fecha" },
            { "data": "notaria" },
            { "data": "fecha_firma" },
            { "data": "valor_inicial" },
            { "data": "valor_cerrado" },
            {
                "data": "adjunto",
                "render": function(data, type, row) {
                   /* return '<a href="'+row.URLadjunto+'" download="'+row.dato+'">documento.pdf</a>';*/
                   html = '<a href="javascript:void(0)" onclick="VerDocumento(\'' + row.adjunto + '\')">Documento</a>';
                   return html;
                }
                
            },
            { "data": "nom_archivo"}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });

    tablaAdjuntosVenta = $('#dataAdjuntoTable').DataTable(options);
}

function VerDocumento(adjunto) {  
    var html = "";
          var documento = "archivos/"+adjunto+"";
          html +=
              "<object class='pdfview' type='application/pdf' data='" +
              documento +
              "' style='width: 100%'></object> ";
          $("#my_img_doc").html(html);
          $("#modalVerDocumento").modal("show");  
}

/************************************ELIMINAR ARCHIVOS ADJUNTOS******************************** */
function EliminarDocumentoAdjunto(id) {
    mensaje_condicionalUNO("\u00BFEst\u00E1s seguro de eliminar?", "Al confirmar se proceder\u00E1 a eliminar el registro selecionado", ConfirmarEliminarAdjunto, CancelEliminarAdjunto, id);
}

function CancelEliminarAdjunto() {
    return;
}

function ConfirmarEliminarAdjunto(id) {
    bloquearPantalla("Eliminando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnEliminarAdjuntoInfo": true,
        "idArchivoVenta": id
    };
    realizarJsonPost(url, dato, RespuestaConfirmarEliminarAdjunto, null, 10000, null);
}

function RespuestaConfirmarEliminarAdjunto(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        BuscarListaDocumentosAdjuntos();
        setTimeout(function() {
            mensaje_alerta("\u00A1Eliminado!", "El registro se elimin\u00F3 con \u00E9xito.", "success");
        }, 100);
        return;
    } else {
        setTimeout(function() {
            mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
        }, 100);
    }
}

/**************************BUSCAR INFORMACION LOTE SEGMENTADO************************** */
function BuscarInformacionSegmentadaLoteParaVender() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnLoteSegmentado": true,
        "idLote": $("#__ID_LOTE_VENTA").val()
    };
    realizarJsonPost(url, dato, RespuestaBuscarInformacionSegmentadaLoteParaVender, null, 10000, null);
}

function RespuestaBuscarInformacionSegmentadaLoteParaVender(dato) {
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        LLenarZonaId(dato.data.idProyecto, dato.data.idZona);
        LLenarManzanaId(dato.data.idZona, dato.data.idManzana);
        LLenarLoteId(dato.data.idManzana, dato.data.idLote);
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);
        if (parseInt($("#cbxTipoInmueble").val()) === 1) {
            $("#txtImporteVenta").val(dato.data.valoLoteCasa);
            $("#txtDocumentoCliente").focus();
        } else {
            $("#txtImporteVenta").val(dato.data.valorLoteSolo);
            $("#txtDocumentoCliente").focus();
        }
    }
}

/**************************BUSCAR INFORMACION LOTE************************** */
function BuscarInformacionReservaLote() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
    var dato = {
        "ReturnBuscarInformacionReserva": true,
        "idReserva": $("#__ID_RESERVA_VENTA").val()
    };
    realizarJsonPost(url, dato, RespuestaBuscarInformacionReservaLote, null, 10000, null);
}

function RespuestaBuscarInformacionReservaLote(dato) {
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        LLenarZonaId(dato.data.idProyecto, dato.data.idZona);
        LLenarManzanaId(dato.data.idZona, dato.data.idManzana);
        LLenarLoteId(dato.data.idManzana, dato.data.idLote);

        $("#__ID_CLIENTE").val(dato.data.idCliente);
        $("#cbxTipoDocumento").val(dato.data.tipoDocumento);
        $("#txtDocumentoCliente").val(dato.data.documento);
        $("#txtNombreCliente").val(dato.data.nombres);
        $("#txtApellidoPaternoCliente").val(dato.data.apellidoPaterno);
        $("#txtApellidoMaternoCliente").val(dato.data.apellidoMaterno);
        $("#txtDireccionCliente").val(dato.data.direccion);
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);
        $("#__ID_RESERVA").val(dato.data.idReserva);
        $("#__ID_MONTO_RESERVA").val(dato.data.montoReserva);

        if (dato.data.idTipoCasa != null && dato.data.idTipoCasa != "") {
            $("#cbxTipoInmueble").val(1);
            LLenarTipoCasaId(dato.data.idManzana,1);
        } else {
            $("#cbxTipoInmueble").val("");
            LLenarTipoCasaId(dato.data.idManzana,1);
        }

        $("#cbxTipoMonedaVenta").val(dato.data.idTipoMonedaReserva);

        if (parseInt($("#cbxTipoInmueble").val()) === 1) {
            $("#txtImporteVenta").val(dato.data.valoLoteCasa - parseFloat(dato.data.montoReserva));
            $("#cbxTipoComprobante").focus();
        } else {
            $("#txtImporteVenta").val(dato.data.valorLoteSolo - parseFloat(dato.data.montoReserva));
            $("#cbxTipoComprobante").focus();
        }

    }
}

function ValidarDatosPagosPrevios() {
    var flat = true;
  
        if ($("#cbxTipoMonedaPP").val() === "" || $("#cbxTipoMonedaPP").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo moneda", "info");
            flat = false;
        } else if ($("#txtImportePP").val() === "" || $("#txtImportePP").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el importe", "info");
            flat = false;
        } else if ($("#cbxTipoPagoPP").val() === "" || $("#cbxTipoPagoPP").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el tipo de pago", "info");
            flat = false;
        } else if ($("#txtFechaPagoPP").val() === "" || $("#txtFechaPagoPP").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la fecha de pago", "info");
            flat = false;
        } else if ($("#txtDescripcionPP").val() === "" || $("#txtDescripcionPP").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la descripcion", "info");
            flat = false;
        }
    
    return flat;
}

function RegistrarPagosPrevios(){    
    if(ValidarDatosPagosPrevios()){
        var timeoutDefecto = 1000 * 60;
        bloquearPantalla("Procesando...");
        var data = {
            "btnRegistrarPagosPrevios": true,
            "cbxTipoMonedaPP": $("#cbxTipoMonedaPP").val(),
            "txtImportePP": $("#txtImportePP").val(),
            "cbxTipoPagoPP": $("#cbxTipoPagoPP").val(),
            "txtFechaPagoPP": $("#txtFechaPagoPP").val(),
            "txtDescripcionPP": $("#txtDescripcionPP").val(),
            "cbxLote": $("#cbxLote").val(),
            "cbxPagoRealizado": $("#cbxPagoRealizado").val(),
            "txtTipoCambioPP": $("#txtTipoCambioPP").val(),
            "txtImportePagoPP": $("#txtImportePagoPP").val(),
            "txtDocumentoCliente": $("#txtDocumentoClave").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                //console.log(dato);
                if (dato.status === "ok") {      
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");
                    CargarVoucher();
                    ListarPagosPrevios();
                    CalculaTotalDato();
                   /* var total = parseFloat(data.total);
                    var inicial = parseFloat($("#txtMontoInicial").val()); 
                    var operacion = total + inicial;
                    $("#txtMontoInicial").val(operacion);*/
                }else{
                    mensaje_alerta("\u00A1ERROR!", dato.data + "\n" + dato.dataDB, "error");
                }
    
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
                desbloquearPantalla();
            },
        });
    }
}

function CargarVoucher(){

    var file_data = $('#fichero4').prop('files')[0];   
     var form_data = new FormData();                  
     form_data.append('file', file_data);
     //alert(form_data);                             
     $.ajax({
         url: '../../models/M03_Ventas/M03MD02_Venta/M03MD02_SubirArchivoPP.php', // point to server-side PHP script 
         dataType: 'text',  // what to expect back from the PHP script, if anything
         cache: false,
         contentType: false,
         processData: false,
         data: form_data,                         
         type: 'post',
         success: function(php_script_response){
             //alert(php_script_response); // display response from the PHP script, if any
            // mensaje_alerta("Correcto!", "El adjunto fue cargado correctamente", "success"); 
         }
      });
}

function ActualizarPagosPrevios(){    

    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnRegistrarPagosPrevios": true,
        "cbxTipoMonedaPP": $("#cbxTipoMonedaPP").val(),
        "txtImportePP": $("#txtImportePP").val(),
        "cbxTipoPagoPP": $("#cbxTipoPagoPP").val(),
        "txtFechaPagoPP": $("#txtFechaPagoPP").val(),
        "txtDescripcionPP": $("#txtDescripcionPP").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status === "ok") {      
                var resultado = dato.data;      
                $("#txtIdVentaComprob").val(resultado.id);

                $("#txtProyectoComprob").val(resultado.proyecto);
                $("#txtZonaComprob").val(resultado.zona);
                $("#txtManzanaComprob").val(resultado.manzana);
                $("#txtLoteComprob").val(resultado.lote);
                $("#txtVendedorComprob").val(resultado.vendedor);

                $("#cbxTipoDocumentoComprob").val(resultado.tipo_documento);
                $("#txtNroDocumentoComprob").val(resultado.documento);
                $("#txtApePaternoComprob").val(resultado.apePaterno);
                $("#txtApeMaternoComprob").val(resultado.apeMaterno);
                $("#txtNombresComprob").val(resultado.nombres);

                ListarPagosPrevios();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
    });
}

// Modificaciones para obtener información de inventario de lote
const paramsLote = new URLSearchParams(window.location.search);

function obtenerLoteDesdeVenta(loteId) {
    bloquearPantalla("Buscando...");
    let valor_1 = $("#cbxLote").val();
    
 
   if(loteId ==null){
        desbloquearPantalla();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, No se asignado informacion de lote", "info");
    }else{        
        var url = "../../models/M03_Ventas/M03MD02_Venta/M03MD02_Venta_Proceso.php";
        var dato = {
            "ReturnInfoLote": true,
            "idLote": loteId,
        };
        realizarJsonPost(url, dato, RespuestaBuscarDatoLote, null, 10000, null);
    }
}

function obtenerLote() {
    const params = new URLSearchParams(window.location.search);

    // Leer valores específicos
    const pro = params.get("Proyecto");
    const zona = params.get("Zona");
    const mz = params.get("Mz");
    const lt = params.get("Lt");

    console.log("Proyecto:", pro);
    console.log("Zona:", zona);
    console.log("Manzana:", mz);
    console.log("Lote:", lt);
    if (pro=='' && pro==null) {
        console.log("No viene desde Venta Id");
    } else {       
        LLenarZonaId(pro, zona);
        LLenarManzanaId(zona, mz);
        LLenarLoteId(mz, lt);
        obtenerLoteDesdeVenta(lt);
    }
    
}

function asignarLote() {

    if (paramsLote.has('Proyecto')) {
        Nuevo();
        obtenerLote();
    } else {
        console.log("No viene desde Venta Id")
        LLenarZona();
    }
}