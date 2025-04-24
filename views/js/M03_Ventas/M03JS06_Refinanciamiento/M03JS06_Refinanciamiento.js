var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {

    LimpiarCapmosAmortizacion();
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
        //BuscarReservasCliente();
    });
    LLenarZona();

    $('#cbxZona').change(function() {
        $("#cbxManzana").val("");
        $("#cbxLote").val("");
        LLenarManzanas();
        $("#cbxManzana").prop("disabled", false);
        document.getElementById('cbxLote').selectedIndex = 0;
        $("#cbxLote").prop("disabled", true);
        //BuscarReservasCliente();
    });

    $('#cbxManzana').change(function() {
        $("#cbxLote").val("");
        LLenarLotes();
        document.getElementById('cbxLote').selectedIndex = 0;
        $("#cbxLote").prop("disabled", false);
        //BuscarReservasCliente();
    });

    $('#cbxLote').change(function() {
        BuscarDatoLote();
        //BuscarReservasCliente();
        LLenarLoteId($("#cbxManzana").val(), $("#cbxLote").val());
    });


      /******************* INICIALIZAR FILTROS DE LOTE ********************************************/
    $('#bxFiltroProyectoVenta').change(function () {
        $("#bxFiltroZonaVenta").val("");
        $("#bxFiltroManzanaVenta").val("");
        $("#bxFiltroLoteVenta").val("");
        var url = '../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php';
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
        var url = '../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php';
        var datos = {
            "ListarManzanas": true,
            "idZona": $('#bxFiltroZonaVenta').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaVenta");
        document.getElementById('bxFiltroLoteVenta').selectedIndex = 0;
    });

    $('#bxFiltroManzanaVenta').change(function () {
        $("#bxFiltroLoteVenta").val("");
        var url = '../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php';
        var datos = {
            "ListarLotes": true,
            "idManzana": $('#bxFiltroManzanaVenta').val()
        }
        llenarCombo(url, datos, "bxFiltroLoteVenta");
    });


    $('#btnBuscarCliente').click(function() {
        BuscarDatoCliente();
    });

    $('#btnLimpiarFiltrosCliente').click(function() {
        $("#__ID_CLIENTE,#__ID_RESERVA,#__ID_MONTO_RESERVA,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxZona,#cbxManzana,#cbxLote, #bxFiltroZonaVenta, #bxFiltroManzanaVenta, #bxFiltroLoteVenta, #txtDocumentoCliente").val("");
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

    InicializarAtributosTablaBusquedaRelacionada();
    /***********ADJUNTAR  DOCUMENTOS************ */
    $('#btnNuevoDocumentoAdjunto').click(function() {
        NuevoAdjuntarDocumento();
    });

    /********************SUBIR  DOCUMENTO ADJUNTO********************** */
    $('#btnGuardarAdjunto').click(function() {
        GuardarInformacionAdjunto();
        //RegistrarAdjuntoVenta();

    });

    $("#txtValorDescuento").keyup(delayTime(function (e) {
      CalculoDescuento();
      CalcularTotal();
    }, 1000));

    $("#txtMontoInicial").keyup(delayTime(function (e) {
      CalcularTotal();
    }, 1000));


    if ($("#__ID_LOTE_VENTA").val().trim().length > 0) {
        $("#nuevo").click();
        if ($("#__ID_RESERVA_VENTA").val().trim().length > 0) {
            BuscarInformacionReservaLote();
        } else {
            BuscarInformacionSegmentadaLoteParaVender();
        }
    }

    $('#btnVerTipoCambio').click(function() {
        BuscarTipoCambio();
    });


}

function BuscarTipoCambio(){

    var url = "../../models/index.php";
    var dato = {
        "btnBuscarTipoCambio": true
    };
    realizarJsonPost(url, dato, AsignarTipoCambio, null, 10000, null);
}

function AsignarTipoCambio(dato){

    if(dato.status == "ok"){
         $("#txtTipoCambio").val(dato.tipo_cambio);
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

    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
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
        "fichero": $("#fichero").val()

    };
    realizarJsonPost(url, dato, EjecutarCargaArchivo, null, 10000, null);
}

function EjecutarCargaArchivo(dato){

    if(dato.status == "ok"){
        EnviarAdjunto();
        mensaje_alerta("Correcto!", dato.mensaje, "success");
    }else{
        mensaje_alerta("Error!", dato.mensaje, "info");
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
    var precioVenta = parseFloat($("#txtPrecioVenta").val());
    var montoInicial = parseFloat($("#txtMontoInicial").val());
    var montoDscto = parseFloat($("#txtMontoDscto").val());

    var total = parseFloat(precioVenta - (montoInicial + montoDscto));
    $("#txtImporteVenta").val(total.toFixed(2));
    $("#txtMontoCuotaInicialr").val($("#txtMontoInicial").val());
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


/****************************LIMPIAR FILTROS DE BUSQUEDA**************************** */
function LimpiarFiltro() {
    $("#txtDocumentoFiltro,#txtDesdeFiltro,#txtHastaFiltro").val("");
}


/********************CONFIGURAR BOTONES************************* */
var Estados = { Ninguno: "Ninguno", Nuevo: "Nuevo", Modificar: "Modificar", Guardado: "Guardado", SoloLectura: "SoloLectura", Consulta: "Consulta" };
var Estado = Estados.Ninguno;

function BloquearTodo(valor_c) {
    $("#txtDocumentoCliente,#btnBuscarCliente,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxTipoComprobante,#txtSerieComprobante,#txtNumeroComprobante,#txtFechaVenta,#cbxCondicionVenta,#cbxTipoDescuento,#txtDescuento,#cbxTipoCreditor,#txtCantidadLetrar,#txtTEAr,#txtFechaPrimerPagor,#txtMontoCuotaInicialr,#txtMontoCuotaInicialr").prop("disabled", valor_c);
    $("#formularioRegistrarGeneralCliente").addClass("disabled-form");
    $("#formularioRegistrarGeneralLote").addClass("disabled-form");
    $("#formularioRegistrarDocumentoVenta").addClass("disabled-form");
    $("#formularioReservasRelacionadasAlCliente").addClass("disabled-form");

}

function HabilitarCampos(valor_c) {
    //$("").prop("disabled", valor_c);
}

function HabilitarCamposModificar(valor_c) {
    $("#txtTipoCambio,#fileAmortizacion").prop("disabled", valor_c);

}


/***************************CONFIGURACION ESTADO BOTONES************************* */
function Ninguno() {
    Estado = Estados.Ninguno;
    $("#nuevo").prop('disabled', false);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', true);
    $("#guardar").prop('disabled', true);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', true);
    $("#contenido_registro").hide();
    $("#contenido_lista").show();
    /*$("#contenedorArchivosAdjuntos").hide();*/
    BusacarVentaPaginado();
    BusacarVentaReporte();
    BloquearTodo(true);
    LimpiarCamposRegistro();
    $("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
}

function Nuevo() {

    var doc = $("#txtDocumentoCliente").val();
    if(doc==""){
        LimpiarCapmosAmortizacion();

        $("#txtDocumentoCliente").prop("disabled", false);
        $("#btnBuscarCliente").prop("disabled", false);

        $("#contenido_registro").show();
        $("#contenido_lista").hide();

        $("#formularioDatosTraslado").hide();
        $("#formularioMensajeTraslado").show();

        $("#panel-busqueda, #panel-busqueda2, #panel-busqueda3, #panel-busqueda4").show();
        
        MostrarDatosCronograma();
    }else{

        Estado = Estados.Nuevo;
        $("#nuevo").prop('disabled', true);
        $("#modificar").prop('disabled', true);
        $("#cancelar").prop('disabled', false);
        $("#guardar").prop('disabled', false);
        $("#eliminar").prop('disabled', true);
        $("#adjuntos").prop('disabled', true);
        $("#Empresa_Gnl").prop('disabled', true);

        $("#formularioDatosTraslado").show();
        $("#formularioMensajeTraslado").hide();

        //Limpiar campos Amortizacion

        $("#__ACCION").val("1");
        $("#txtMontoInicialEntrante").val("0.00");
        HabilitarCampos(false);
        
        MostrarDatosCronograma();

    }
}

/***********************LLENAR DATOS DE LA VENTA ********************** */
function MostrarDatosCronograma(){

    var data = {
       "btnLlenarDatosCronograma": true,
       "idVenta": $("#__ID_VENTA").val()
   };
   $.ajax({
       type: "POST",
       url: "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               var resultado = dato.data;      
                $("#txtSaldoPendiente").val(resultado.pagado);
                $("#txtLetrasPendientes").val(resultado.tot_letras);  
           } 

       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       }
   });   
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
    //HabilitarCamposModificar(false);
    $("#formularioRegistrarGeneralCliente").removeClass("disabled-form");
    $("#formularioRegistrarGeneralLote").removeClass("disabled-form");
    $("#formularioRegistrarDocumentoVenta").removeClass("disabled-form");
    $("#formularioReservasRelacionadasAlCliente").removeClass("disabled-form");
    $("#txtDocumentoCliente").focus();
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
    $("#txtFrameDatosAmortizacion").show();
}

function MostrarLista() {
    LimpiarCapmosAmortizacion();
    Ninguno();
}


/****************** LIMPIAR TODO LOS CAMPOS VISTA PRINCIPAL************************** */
function LimpiarCamposRegistro() {
    $("#__ID_RESERVACION,#__ID_CLIENTE,#txtDocumentoCliente,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente,#cbxProyecto,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxTipoComprobante,#txtSerieComprobante,#txtNumeroComprobante,#txtFechaVenta,#cbxCondicionVenta,#txtDescuento,#txtImporteVenta,#txtCantidadLetrar,#txtTEAr,#txtFechaPrimerPagor,#txtMontoCuotaInicialr,#txtMontoCuotaInicialr").val("");
    $("#txtPrecioVenta,#txtMontoInicial,#txtMontoDscto,#txtImporteVenta").val("0.00");
}


/***********************LLENA ZONAS ********************** */
function LLenarZona() {
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var datos = {
        "ReturnZonas": true,
        "idProyecto": $('#cbxProyecto').val()
    }
    llenarCombo(url, datos, 'cbxZona');
}


/***********************LLENA MANZANA ********************** */
function LLenarManzanas() {
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var datos = {
        "ReturnManzana": true,
        "idZona": $('#cbxZona').val()
    }
    llenarCombo(url, datos, 'cbxManzana');
}


/***********************LLENA LOTE ********************** */
function LLenarLotes() {
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var datos = {
        "ReturnLote": true,
        "idManzana": $('#cbxManzana').val()
    }
    llenarCombo(url, datos, 'cbxLote');
}


/**************************BUSCAR CLIENTE************************** */
function BuscarDatoCliente() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var dato = {
        "ReturnBuscarCliente": true,
        "tipoDocumento": $("#cbxTipoDocumento").val(),
        "documento": $("#txtDocumentoCliente").val(),
        "idlote": $("#bxFiltroLoteVenta").val()

    };
    realizarJsonPost(url, dato, RespuestaBuscarDatoCliente, null, 10000, null);
}

function RespuestaBuscarDatoCliente(dato) {
    $("#__ID_CLIENTE,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#txtDireccionCliente").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#__ID_CLIENTE").val(dato.data.id);
        $("#txtNombreCliente").val(dato.data.nombres);
        $("#txtApellidoPaternoCliente").val(dato.data.apellidoPaterno);
        $("#txtApellidoMaternoCliente").val(dato.data.apellidoMaterno);
        $("#txtDireccionCliente").val(dato.data.correo);
        //BuscarReservasCliente();
        //$("#cbxZona").focus();
        if(dato.accion == "si"){
            LlenarListaZonas(dato.data.idproyecto, dato.data.idzona);
            LlenarListaManzanas(dato.data.idzona, dato.data.idmanzana);
            LlenarListaLotes(dato.data.idmanzana, dato.data.idlote);
            $("#txtArea").val(dato.data.lote_area);
            $("#txtTipoMonedaLote").val(dato.data.lote_tipo_moneda);
            $("#txtValorLoteCasa").val(dato.data.lote_valor_casa);
            $("#txtValorLoteSolo").val(dato.data.lote_valor_solo);
            /*$("#txtImporteVenta").val(dato.data.lote_valor_solo);*/
        }
        CalcularTotal();
    } else {
        $("#txtDocumentoCliente").focus();
        //mensaje_cliente_no_encontrado("Importante!", "No se encontro datos para el documento y/o lote seleccionado.", IrRegistroCliente, SeguirBuscando, dato.urlRegistroCliente);
        mensaje_alerta("ERROR!", dato.data, "info");
    }
}

function LlenarListaZonas(idProy, idZon) {
    var url = '../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php';
    var datos = {
        "ListarZonas": true,
        "idProyecto": idProy
    }
    llenarComboSelecionar(url, datos, "cbxZona", idZon);
}

function LlenarListaManzanas(idZon, idMan) {
    var url = '../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php';
    var datos = {
        "ListarManzanas": true,
        "idZona": idZon
    }
    llenarComboSelecionar(url, datos, "cbxManzana", idMan);
}

function LlenarListaLotes(idMan, idLot) {
    var url = '../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php';
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
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
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
    $("#txtMontoInicial").val(dato.motoReserva);
    CalcularTotal();
    BuscarPrecioLoteReservado(dato.idLote, dato.motoReserva);
}

/**************************BUSCAR INFORMACION LOTE PRECIO************************** */
function BuscarPrecioLoteReservado(idLote, montoReservado) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
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
    }
}


/**************************BUSCAR INFORMACION LOTE************************** */
function BuscarDatoLote() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var dato = {
        "ReturnInfoLote": true,
        "idLote": $("#cbxLote").val()
    };
    realizarJsonPost(url, dato, RespuestaBuscarDatoLote, null, 10000, null);
}

function RespuestaBuscarDatoLote(dato) {
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);
        $("#cbxTipoComprobante").focus();
        CalcularTotal();
    }
}


/**************************BUSCAR INFORMACION LOTE PRECIO************************** */
function BuscarDatoLotePrecio() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
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
        var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
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
    }
}


/**********************CONTROLAR BOTON GUARDAR************************ */
function Guardar() {
    if (Estado == Estados.Nuevo) {
        GuardarNuevo();
        //BuscarIDVENTA();
        //$("#btnNuevoDocumentoAdjunto").prop('disabled', false);
    } else if (Estado == Estados.Modificar) {
        //GuardarActualizacion();
        //BuscarIDVENTA();
        //$("#btnNuevoDocumentoAdjunto").prop('disabled', false);
    } else {
        mensaje_alerta("ADVERTENCIA!", "Ocurrio un problema en el registro, por favor, intente nuevamente.", "warning");
    }
}


/***************************VALIDAR DATOS REQUERIDOS****************************** */
function ValidarDatosNuevoRequeridosRef() {
    var flat = true;
   if ($("#txtNuevasLetras").val() === "" || $("#txtNuevasLetras").val() === null) {
        $("#txtNuevasLetras").focus();
        mensaje_alerta("Falta Dato!", "Por favor, ingrese la cantidad de letras que se refinanciaran.", "info");
        flat = false;
    } else if ($("#txtTNA").val() === "" || $("#txtTNA").val() === null) {
        $("#txtTNA").focus();
        mensaje_alerta("Falta Dato!", "Por favor, ingresar la tasa aplicable a las letras a refinanciar.", "info");
        flat = false;
    } 
    return flat;
}


/***************************GUARDAR NUEVA VENTA****************************** */
function GuardarNuevo(){
    var mensaje = 'Seguro(a) que desea refinanciar la venta?';
    mensaje_eliminar_parametro(mensaje, GuardarRefinanciamiento, 1);
}

function GuardarRefinanciamiento(dato) {
    if (ValidarDatosNuevoRequeridosRef()) {
        bloquearPantalla("Guardando...");
        var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
        var dato = {
            "btnGuardarRefinanciamientoVenta": true,
            "txtNuevasLetras": $("#txtNuevasLetras").val(),
            "txtTNA": $("#txtTNA").val(),
            "__ID_VENTA": $("#__ID_VENTA").val()
        };
        realizarJsonPost(url, dato, respuestaGuardarNuevoRegistro, null, 10000, null);
    }
}

function BuscarIDVENTA() {
    var data = {
        "BuscarIdVenta": true,
        "idLote": $("#cbxLote").val().trim()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
            $("#__ID_VENTA").val(dato.data);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        //timeout: timeoutDefecto
    });
}


/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarNuevoRegistro(dato) {
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status == "ok") {
        LlenarTablaCronograma();
        mensaje_alerta("CORRECTO!", dato.data, "success");
        return;
    } else {
       mensaje_alerta("ERROR!", dato.data, "info");
    }
}


/***************************VALIDAR DATOS ACTUALIZACION REQUERIDOS****************************** */
function ValidarDatosActualizacionRequeridos() {
    var flat = true;
    if ($("#txtDocumentoCliente").val() === "" || $("#txtDocumentoCliente").val() === null) {
        $("#txtDocumentoCliente").focus();
        mensaje_alerta("Falta Dato!", "Por favor, realizar la busqueda de un cliente", "info");
        flat = false;
    } else if ($("#__ID_CLIENTE").val() === "" || $("#__ID_CLIENTE").val() === null) {
        $("#txtDocumentoCliente").focus();
        mensaje_alerta("Falta Dato!", "Por favor, realizar la busqueda de un Cliente V¡§¡élido", "info");
        flat = false;
    } else if ($("#__ID_VENTA").val() === "" || $("#__ID_VENTA").val() === null) {
        $("#txtDocumentoCliente").focus();
        mensaje_alerta("Falta Dato!", "Por favor, Intentar nuevamente, ocurri¡§? un problema", "info");
        flat = false;
    } else if ($("#cbxLote").val() === "" || $("#cbxLote").val() === null) {
        $("#cbxLote").focus();
        mensaje_alerta("Falta Dato!", "Por favor, seleccionar el Lote", "info");
        flat = false;
    } else if ($("#cbxTipoComprobante").val() === "" || $("#cbxTipoComprobante").val() === null) {
        $("#cbxTipoComprobante").focus();
        mensaje_alerta("Falta Dato!", "Por favor, Seleccionar el tipo de comprobante", "info");
        flat = false;
    } else if ($("#txtSerieComprobante").val() === "" || $("#txtSerieComprobante").val() === null) {
        $("#txtSerieComprobante").focus();
        mensaje_alerta("Falta Dato!", "Por favor, Ingresar la Serie del Comprobante", "info");
        flat = false;
    } else if ($("#txtNumeroComprobante").val() === "" || $("#txtNumeroComprobante").val() === null) {
        $("#txtNumeroComprobante").focus();
        mensaje_alerta("Falta Dato!", "Por favor, Ingresar el N\u00FAmero del Comprobante", "info");
        flat = false;
    } else if ($("#txtFechaVenta").val() === "" || $("#txtFechaVenta").val() === null) {
        $("#txtFechaVenta").focus();
        mensaje_alerta("Falta Dato!", "Por favor, Seleccionar la Fecha de Venta", "info");
        flat = false;
    } else if ($("#cbxCondicionVenta").val() === "" || $("#cbxCondicionVenta").val() === null) {
        $("#cbxCondicionVenta").focus();
        mensaje_alerta("Falta Dato!", "Por favor, Seleccionar el Tipo de Pago", "info");
        flat = false;
    } else if ($("#txtImporteVenta").val() === "" || $("#txtImporteVenta").val() === null) {
        $("#txtImporteVenta").focus();
        mensaje_alerta("Falta Dato!", "Por favor, verificar el Monto Total", "info");
        flat = false;
    } else if ($("#txtMontoInicial").val() === "" || $("#txtMontoInicial").val() === null) {
        $("#txtMontoInicial").focus();
        mensaje_alerta("Falta Dato!", "Por favor, Ingresar el monto de la cuota inicial pagada por el cliente.", "info");
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
            mensaje_alerta("Falta Dato!", "Por favor, seleccionar el tipo de crédito.", "info");
            flat = false;
        } else if ($("#txtCantidadLetrar").val() === "" || $("#txtCantidadLetrar").val() === null) {
            $("#txtCantidadLetrar").focus();
            mensaje_alerta("Falta Dato!", "Por favor, Ingresar la cantidad de N\u00FAmero de Letras", "info");
            flat = false;
        } else if ($("#txtTEAr").val() === "" || $("#txtTEAr").val() === null) {
            $("#txtTEAr").focus();
            mensaje_alerta("Falta Dato!", "Por favor, Ingresar la Tasa Efectiva Anual (%)", "info");
            flat = false;
        } else if ($("#txtFechaPrimerPagor").val() === "" || $("#txtFechaPrimerPagor").val() === null) {
            $("#txtFechaPrimerPagor").focus();
            mensaje_alerta("Falta Dato!", "Por favor, Ingresar la Primera Fecha de Pago", "info");
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
            var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
            var dato = {
                "ReturnActualizarVenta": true,
                "idVenta": $("#__ID_VENTA").val().trim(),
                "idCliente": $("#__ID_CLIENTE").val().trim(),
                "idLote": $("#cbxLote").val().trim(),
                "descripcion": $("#txtDescripcion").val(),
                "tipoComprobante": $("#cbxTipoComprobante").val(),
                "serie": $("#txtSerieComprobante").val(),
                "numero": $("#txtNumeroComprobante").val(),
                "fechaVenta": $("#txtFechaVenta").val(),
                "condicion": $("#cbxCondicionVenta").val(),
                "descuentoMonto": $("#txtDescuento").val(),
                "total": $("#txtImporteVenta").val(),
                "tipoCredito": $("#cbxTipoCreditor").val(),
                "cantidadLetra": $("#txtCantidadLetrar").val(),
                "tea": $("#txtTEAr").val(),
                "primeraFechaPago": $("#txtFechaPrimerPagor").val(),
                "cuotaInicial": $('#checkCuotaInicialr').prop('checked'),
                "montoCuotaInicial": $("#txtMontoCuotaInicialr").val(),
                "idReservacion": $("#__ID_RESERVA").val()
            };
            realizarJsonPost(url, dato, respuestaGuardarActualizacion, null, 10000, null);
        }
    }
}


/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarActualizacion(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        Ninguno();
        mensaje_alerta("Guardado!", "El registro de Venta se guard\u00F3 con \u00E9xito.", "success");
        return;
    } else {
        mensaje_alerta("Error!", dato.data + "\n" + dato.dataDB, "error");
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
            "url": "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php", // ajax source
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
                    return '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="LlenarImportesVentas(\'' + data + '\')"><i class="fas fa-pencil-alt"></i></a>';
                }
            },
            { "data": "fechaVenta" },
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "condicion" },
            { "data": "tipoMoneda" },
            { "data": "montoDescuento" },
            { "data": "total" },
            { "data": "motoCuotaInicial" },
            { "data": "vendedor" },
            {
                "data": "refinanciado",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.refinanciado == "NINGUNO"){
                         html = '<span class="badge" style="background-color:#0668C4; color: white; font-weight: bold;">' + row.refinanciado + '</span>';
                        return html;
                    }else{
                         html = '<span class="badge" style="background-color:#06BF00; color: white; font-weight: bold;">' + row.refinanciado + '</span>';
                        return html;
                    }
                }
            }
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

    //$("#contenedorArchivosAdjuntos").hide();

    LlenarImportesVentas(data.idVenta);
};

function LlenarImportesVentas(idventa){
    //bloquearPantalla("Guardando...");
    Consulta();
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var dato = {
        "btnBuscarImportesVentas": true,
        "idventa": idventa
    };
    realizarJsonPost(url, dato, ImportesVentas, null, 10000, null);
}

function ImportesVentas(data){
    $("#__ID_VENTA").val(data.idventa);
    $("#__ID_CLIENTE").val(data.idCliente);
    /*$("#__ID_RESERVA").val(data.id_reservacion);
    $("#__ID_MONTO_RESERVA").val(data.montoReserva);*/
    $("#cbxTipoDocumento").val(data.tipoDocumento);
    $("#txtDocumentoCliente").val(data.documento);
    $("#txtNombreCliente").val(data.nombres);
    $("#txtApellidoPaternoCliente").val(data.apellidoPaterno);
    $("#txtApellidoMaternoCliente").val(data.apellidoMaterno);
    $("#txtDireccionCliente").val(data.direccion);
    $("#cbxProyecto").val(data.idproyecto);
    LLenarZonaId(data.idproyecto, data.idzona);
    LLenarManzanaId(data.idzona, data.idmanzana);
    LLenarLoteId(data.idmanzana, data.idlote);

    $("#txtFechaVenta").val(data.fecha_venta);
    $("#txtPropiedad").val(data.propiedad);

    $("#txtTipoMoneda").val(data.tipo_moneda);
    $("#txtPrecioVenta").val(data.precio_venta);
    $("#txtMontoInicial").val(data.monto_inicial);
    $("#txtMontoPagado").val(data.total_pagado);
    $("#txtLetrasPagadas").val(data.ultima_letra);

    if(data.idtraslado == 0){
        $("#formularioDatosTraslado").hide();
        $("#formularioMensajeTraslado").show();
    }else{
        $("#formularioMensajeTraslado").hide();
        $("#formularioDatosTraslado").show();

        CargarDatosAmortizacion(data.idventa);
        CalcularTotalInicial();

        $("#fileAmortizacion").prop("disabled", true);
        $("#txtTipoMoneda").prop("disabled", true);
        $("#txtMontoInicialEntrante").prop("disabled", true);
    }
    $("#panel-busqueda, #panel-busqueda2, #panel-busqueda3, #panel-busqueda4").hide();

    LlenarTablaCronograma();

}

function CargarDatosAmortizacion(idventa){
    //bloquearPantalla("Guardando...");
    Consulta();
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var dato = {
        "btnBuscarDatosAmortizacion": true,
        "idventa": idventa
    };
    realizarJsonPost(url, dato, LlenarDatosAmortizacion, null, 10000, null);
}

function LlenarDatosAmortizacion(datos){
    //console.log(datos);
    $("#txtMontoInicialEntrante").val(datos.capital_inicial);
}

function LimpiarCapmosAmortizacion(){
    $("#__ID_VENTA").val("");
    $("#txtDocumentoCliente").val("");
    $("#txtNombreCliente").val("");
    $("#txtApellidoPaternoCliente").val("");
    $("#txtApellidoMaternoCliente").val("");
    $("#txtDireccionCliente").val("");

    $("#txtTipoMoneda").val("");
    $("#txtPrecioVenta").val("");
    $("#txtMontoInicial").val("");
    $("#txtMontoPagado").val("");
    $("#txtLetrasPagadas").val("");

    $("#txtMontoInicialEntrante").val("");

    $("#__ACCION").val("0");

    //document.getElementById('cbxTipoDocumento').selectedIndex = 0;
    document.getElementById('cbxProyecto').selectedIndex = 0;
    document.getElementById('bxFiltroZonaVenta').selectedIndex = 0;
    document.getElementById('bxFiltroManzanaVenta').selectedIndex = 0;
    document.getElementById('bxFiltroProyectoVenta').selectedIndex = 0;
    document.getElementById('bxFiltroLoteVenta').selectedIndex = 0;

     LlenarTablaCronograma();
}


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
            "url": "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php", // ajax source
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
            { "data": "fechaVentaCadena" },
            { "data": "cliente" },
            { "data": "nombreLote" },
            { "data": "condicion" },
            { "data": "tipoMoneda" },
            { "data": "montoDescuento" },
            { "data": "total" },
            { "data": "motoCuotaInicial" },
            { "data": "vendedor" },
            { "data": "refinanciado"}
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

function LlenarTablaCronograma(){
    bloquearPantalla("Buscando...");
    Consulta();
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var dato = {
        "btnLlenarTablaCronograma": true,
        "idventa": $("#__ID_VENTA").val()
    };
    realizarJsonPost(url, dato, LlenarTablaCronogramaProceso, null, 10000, null);
}

function LlenarTablaCronogramaProceso(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaCronogramaData(dato.data);
}

var tablaBusqCronograma = null;
function LlenarTablaCronogramaData(datos){
    if (tablaBusqCronograma) {
        tablaBusqCronograma.destroy();
        tablaBusqCronograma = null;
    }
    tablaBusqCronograma = $('#TablaCronograma').DataTable({
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


/********************LLENAR ZONA SELECIONANDO****************/
function LLenarZonaId(idProyecto, idZona) {
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var datos = {
        "ReturnZonas": true,
        "idProyecto": idProyecto
    }
    llenarComboSelecionar(url, datos, "cbxZona", idZona);
}


/********************LLENAR MANZANA SELECIONANDO****************/
function LLenarManzanaId(idZona, idManzana) {
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var datos = {
        "ReturnManzana": true,
        "idZona": idZona
    }
    llenarComboSelecionar(url, datos, "cbxManzana", idManzana);
}


/********************LLENAR LOTE SELECIONANDO****************/
function LLenarLoteId(idManzana, idLote) {
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
    var datos = {
        "ReturnLoteActualizable": true,
        "idManzana": idManzana,
        "idLote": idLote
    }
    llenarComboSelecionar(url, datos, "cbxLote", idLote);
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
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
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
            mensaje_alerta("?0?3Eliminado!", dato.data, "success");
        }, 100);
        return;
    } else {
        setTimeout(function() {
            mensaje_alerta("?0?3Error!", dato.data + "\n" + dato.dataDB, "error");
        }, 100);
    }
}


/**********************CONTROLAR BOTON GUARDAR************************ */
function GuardarInformacionAdjunto() {
    if (EstadoAdjunto == EstadosAdjuntoDoc.Nuevo) {
        //GuardarNuevoInformacionAdjunto();
        RegistrarAdjuntoVenta();
    } else if (EstadoAdjunto == EstadosAdjuntoDoc.Modificar) {
        GuardarActualizacionInformacionAdjunto();
    } else {
        mensaje_alerta("?0?3ADVERTENCIA!", "Ocurrio un problema en el registro, por favor, intente nuevamente.", "warning");
    }
}


/***************************GUARDAR INFORMACION ADJUNTO****************************** */
function GuardarNuevoInformacionAdjunto() {
    bloquearPantalla("Guardando...");
    var url = "../../models/M03_Ventas/M03MD06_Refinanciamiento/M03MD06_Procesos.php";
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
