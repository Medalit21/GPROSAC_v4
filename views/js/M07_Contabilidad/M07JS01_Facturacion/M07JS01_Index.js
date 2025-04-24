var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {
    CargarFechasFiltro();
    CargarPagosFacturacion();
    CargarComprobantesImpresos();
    CargarPagosFacturacionGnral();
    
 
    $('#btnEmitirBoleta').click(function() {
        EmitirBoleta();
    }); 
    
    $('#btnBuscarDocumento').click(function() {
        BuscarDocumento();
    }); 

    $('#btnBuscarImpresos').click(function() {
        CargarComprobantesImpresos();
    }); 

    $('#btnLimpiarFiltros').click(function() {
        $('#txtFiltroCliente2').val(null).trigger('change');
        document.getElementById('txtFiltroTipoComprobante2').selectedIndex = 0;
        CargarComprobantesImpresos();       
    });

  
    $('#btnBuscarRegistroCV').click(function() {
        CargarPagosComprobante();
    });
  
    $('#btnLimpiarPagos').click(function() {
        $('#txtFiltroDocumentoCV').val(null).trigger('change');
        $("#txtFiltroDesdeCV").val("");
        $("#txtFiltroHastaCV").val("");
        document.getElementById('cbxFiltroBancoCV').selectedIndex = 0;
        ValidarFechas();
    }); 
  
    $('#btnGuardarPagoCV').click(function() {
        GuardarComprobante();
    });

    $('#txtFiltroCliente').change(function () {       
        var documento = $("#txtFiltroCliente").val();
        LlenarLotes(documento);
    });

    $('#txtNroDocumento').keyup(delayTime(function (e) {  
        BuscarDocumento();
    }, 1000)); 

    
    $('input.CurrencyInput').on('blur', function() {
        const value = this.value.replace(/,/g, '');
        this.value = parseFloat(value).toLocaleString('en-US', {
          style: 'decimal',
          maximumFractionDigits: 2,
          minimumFractionDigits: 2
        });
    });

    $('#btnAgregarPagoVAL').click(function() {
        var id  =  $("#__IDPAGO_DET").val();
        var total  =  $("#txtTotalEmitirVAL").val();
        var total_ref  =  $("#txtTotalPagadoVAL").val();

        var tipo_doc  =  $("#__TIPCOM").val();
        if(tipo_doc == "01"){
            AgregarPagFac(id, total, total_ref);
        }else{
            if(tipo_doc == "03"){
                AgregarPago(id, total, total_ref);
            }
        }
        
    }); 

    /* ===================== EMITIR NUEVO COMPROBANTE ==================================*/

    $('#btnBuscarPagoPendiente').click(function() {
        CargarPagosFacturacionGnral();       
    });

    $('#btnLimpiarPagoPendiente').click(function() {
        CargarFechasFiltro();
        $('#txtFiltroCliente, #txtFiltroPropiedad').val(null).trigger('change');
        setTimeout(function() {
            var documento = $("#txtFiltroCliente").val();
            LlenarLotes(documento); 
            CargarPagosFacturacionGnral();  
        }, 1350);            
    }); 
	
	
	
    
    $('#btnCancelarEmision').click(function() {
        LimpiarBoleta();
        LimpiarBoletaFac();
        $('#txtFiltroCliente').val(null).trigger('change');
        document.getElementById('txtFiltroPropiedad').selectedIndex = 0;
        document.getElementById('txtFiltroTipoComprobante').selectedIndex = 0;
        CargarPagosFacturacionGnral()
        $("#PanelBoletaElectronica").hide();  

        $("#PanlBusqueda").show();
        $("#PanlFiltros").show();
        $("#PanlBtnsAction").hide();

        $("#panel_btn_bol").hide();
        $("#panel_btn_fac").hide();

        $("#__clt").val("");
        $("#__prd").val("");
        $("#__tpc").val("");

        $("#PanelRegistrosPagosValidadosGeneral").show();
        $("#PanelRegistrosPagosValidadosEspecifico").hide();
      
    });




    /* ===================== FACTURA ==================================*/

    $('#btnEmitirFactura').click(function() {
        EmitirFactura();
    }); 

    $('#btnCancelarFactura').click(function() {
        LimpiarBoletaFac();
        $('#txtFiltroCliente').val(null).trigger('change');
        document.getElementById('txtFiltroPropiedad').selectedIndex = 0;
        document.getElementById('txtFiltroTipoComprobante').selectedIndex = 0;
        CargarPagosFacturacion();
        CargarTotalesComprobanteFac();
        $("#PanelFacturaElectronica").hide();  
        $("#botonesAccionFac").show();
      
    });

    $('#btnBuscarDocFac').click(function() {
        BuscarDocumentoFac();
    }); 
    

    /* ===================== NOTA DE CREDITO ==================================*/

    $('#btnEmitirNC').click(function() {
        EmitirNotaCredito();
    }); 

    $('#btnEnviarMensaje').click(function() {
        EnviarMensajeWts();
    }); 
    
    /*======================== COMPROBANTES LIBRES ==============================*/
    
    $('#btnContinuarComprobanteOC').click(function() {
        MostrarVistaOC();
    });
    
    $('#btnAgregarDetalleComOC').click(function() {
        AgregarPagoOC();
    }); 
    
    $('#btnAgregarDetalleComFacOC').click(function() {
        AgregarPagoFacOC();
    }); 

    //BUSCAR CLIENTE EXISTENTE
    $('#txtFiltroClienteOC').change(function () { 
        BuscarDocumentoExisteOC();
        document.getElementById('txtFiltroTipoComprobanteOC').selectedIndex = 0;
        $("#txtFiltroTipoComprobanteOC").prop("disabled", true);
    });

    //BUSCAR CLIENTE NUEVO
    $('#btnBuscarDocumentoOC').click(function() {
        $("#txtFiltroCorreoOC").val("");
        BuscarDocumentoOC();
    });

    $('#cbxFiltroTipoBusqueda').change(function () {       
        var tipo = $("#cbxFiltroTipoBusqueda").val();
        if(tipo=="NUEVO"){
            $("#PanelBusquedaDocReg").hide();
            $("#PanelBusquedaDocNew").show();
        }else{
            $("#PanelBusquedaDocReg").show();
            $("#PanelBusquedaDocNew").hide();
        }
    });
    
    $('#txtfiltroConceptoVentaOC').change(function () { 
        LlenarMotivosConceptos();
    });
    

    $('#btnNuevaEmisionOC').click(function() {
        NuevaEmisionOC();
    });
    
    /*====BOLETAS====*/
    
    $('#btnEmitirBoletaOC').click(function() {
        EmitirBoletaOC();
    });   

    /*====FACTURAS===*/
    $('#btnBuscarDocFacOC').click(function() {
        BuscarDocumentoFacOC();
    });
    
    $('#btnEmitirFacturaOC').click(function() {
        EmitirFacturaOC();
    });   



    $('#btnIrBoleta').click(function() {
        $("#__tpc").val("03");
        var tipo_comprobante = "03";
        var propiedad = $("#__prd").val();
        var cliente = $("#__clt").val();

        IrBoleta(tipo_comprobante, propiedad, cliente);
    });   

    $('#btnIrFactura').click(function() {
        $("#__tpc").val("01");
        var tipo_comprobante = "01";
        var propiedad = $("#__prd").val();
        var cliente = $("#__clt").val();

        IrFactura(tipo_comprobante, propiedad, cliente);
    });   


    $('#btnCancelarBoleta').click(function() {
    
        var tipo_comprobante = $("#__tpc").val();
        var propiedad = $("#__prd").val();
        var cliente = $("#__clt").val();

        IrBoleta(tipo_comprobante, propiedad, cliente);
    });   

    $('#btnCancelarFactura').click(function() {
    
        var tipo_comprobante = $("#__tpc").val();
        var propiedad = $("#__prd").val();
        var cliente = $("#__clt").val();

        IrFactura(tipo_comprobante, propiedad, cliente);
    });   


}

function CargarFechasFiltro(){   
    bloquearPantalla("Cargando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnCargarFechasFiltro": true,
        "idproyecto": $("#txtFiltroProyecto").val()
    };
    realizarJsonPost(url, dato, respuestaGenerarFechasFiltro, null, 10000, null);
}

function respuestaGenerarFechasFiltro(dato){
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status == "ok") {  
        $("#txtFiltroDesde").val(dato.primero);
        $("#txtFiltroHasta").val(dato.ultimo);
    }
    BusacarVentaPaginado();
    BusacarVentaReporte();   
}

function LlenarLotes(documento){
    document.getElementById('txtFiltroPropiedad').selectedIndex = 0;
    var url = '../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php';
    var datos = {
        "btnListarPropiedad": true,
        "txtFiltroCliente": documento
    }
    llenarCombo(url, datos, "txtFiltroPropiedad");  
}

function MostrarVistaTC(tipo_comprobante, propiedad, cliente){   
    bloquearPantalla("Cargando..");
    var data = {
        "btnConsultarVistaComprobante": true,
        "txtFiltroTipoComprobante": tipo_comprobante,
        "txtFiltroPropiedad": propiedad,
        "txtFiltroCliente": cliente
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {            
                //console.log(dato);
                var resultado = dato.data;
                if(dato.registro == '1'){ 

                    $("#__clt").val(dato.cliente);
                    $("#__prd").val(dato.propiedad);
                    $("#__tpc").val(dato.tipodoc);

                    document.querySelector('#label_cliente').innerText = dato.nom_cliente;
                    document.querySelector('#label_lote').innerText = dato.lote;

                    if (parseInt(resultado.codigo) == 1) {
                        $("#PanelBoletaElectronica").show();
                        $("#PanelBoletaElectronica").removeClass("disabled-form");
    
                        $("#PanelFacturaElectronica").hide();
                        $("#PanelNotaCreditoElectronica").hide();
                    } else {
                        if (parseInt(resultado.codigo) == 2) {
                            $("#PanelBoletaElectronica").hide();
                            $("#PanelFacturaElectronica").show();
                            $("#PanelNotaCreditoElectronica").hide();
                        } else {
                            $("#PanelBoletaElectronica").hide();
                            $("#PanelFacturaElectronica").hide();
                            $("#PanelNotaCreditoElectronica").show();    
                        }
                    }
                    
                    CargarPagosFacturacion(dato.tipodoc, dato.propiedad, dato.cliente);        
                    var td = dato.tipodoc;
                    if(td == "03"){            
                        CargarItemsFacturacion(dato.cliente, dato.propiedad);
                        CargarDatosComprobante(dato.cliente, dato.propiedad);
                        /*CargarTotalesComprobante();*/        
                        $("#panel_btn_bol").hide();
                        $("#panel_btn_fac").show();

                    }
            
                    if(td == "01"){
                        CargarItemsFacturacionFac(dato.cliente, dato.propiedad);
                        CargarDatosComprobanteFac(dato.cliente, dato.propiedad);            
                        /*CargarTotalesComprobante();*/ 
                        
                        $("#panel_btn_bol").show();
                        $("#panel_btn_fac").hide();
                    }

                    $("#PanlFiltros").hide();
                    $("#PanlBusqueda").hide();
                    $("#PanlBtnsAction").show();

                    $("#PanelRegistrosPagosValidadosGeneral").hide();
                    $("#PanelRegistrosPagosValidadosEspecifico").show();                    
                    
                }else{
                    mensaje_alerta("\u00A1IMPORTANTE!", "No se encontraron pagos por facturar en relacion a los campos seleccionados.", "info");
                }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}

//AGREGAR FECHA INICIO Y TERMINO DEL MES ACTUAL
function ValidarFechas(){
    var data = {
       "btnValidarFechas": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               $("#txtFiltroDesdeCV").val(dato.primero);
               $("#txtFiltroHastaCV").val(dato.ultimo);   
           } 
           CargarPagosComprobante();

       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

//===============LLENAR TABLA LETRAS PAGADAS POR FACTURAR GENERAL ==============================================
function CargarPagosFacturacionGnral() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarTablaPagosFacturacionGnral": true,
        "txtFiltroProyecto": $("#txtFiltroProyecto").val(),
        "txtFiltroCliente": $("#txtFiltroCliente").val(),
        "txtFiltroPropiedad": $("#txtFiltroPropiedad").val(),
        "txtFiltroTipoComprobante": $("#txtFiltroTipoComprobante").val(),
        "txtFiltroDesde": $("#txtFiltroDesde").val(),
        "txtFiltroHasta": $("#txtFiltroHasta").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosFacturacionGneral, null, 10000, null);
}

function respuestaBuscarPagosFacturacionGneral(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaPagosFacturacionGnral(dato.data);
}

var getTablaPagosFacturacionGnral = null;
function LlenarTablaPagosFacturacionGnral(datos, tp) {
    if (getTablaPagosFacturacionGnral) {
        getTablaPagosFacturacionGnral.destroy();
        getTablaPagosFacturacionGnral = null;
    }
    var tipocom = tp;
    getTablaPagosFacturacionGnral = $('#TablaPagosFacturacionGnral').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = ""; 
                    var boleta = "03";
                    var factura = "01";
                    html = '<a href="#" class="btn btn-edit-action" style="font-weight: bold; font-size: 11px" onclick="IrBoleta(\'' + boleta + '\',\'' + row.cod_lote + '\',\'' + row.doc_cliente + '\')" title="Generar"><i class="fas fa-arrow-circle-right"></i> Boleta</a> \ <a href="#" class="btn btn-delete-action" style="font-weight: bold; font-size: 11px" onclick="IrFactura(\'' + factura + '\',\'' + row.cod_lote + '\',\'' + row.doc_cliente + '\')" title="Generar"><i class="fas fa-arrow-circle-right"></i> Factura</a> \  <a href="#" class="btn btn-info-action" style="font-weight: bold; font-size: 11px" onclick="GenerarClaveS(\'' + row.iddetalle + '\')" title="Clave Sol"><i class="fas fa-lock"></i> Clave Sol</a>';  
                    return html;
                }
            },
            { "data": "doc_cliente" },
            { "data": "cliente" },
            { "data": "lote" }, 
            { "data": "letra" }, 
            { "data": "fecha_pago" }, 
            { "data": "pagado" },   
            { "data": "monto_facturado" }, 
            { "data": "por_facturar" } 
            
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

function GenerarClaveS(id){
    var mensaje = '¿Est\u00E1 seguro(a) de generear el comprobante por Clave Sol?';
    mensaje_eliminar_parametro(mensaje, AccionClaveSol, id);
}
 /****************/
 function AccionClaveSol(id){
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnGenerarClaveSol": true,
        "idUsuario": $("#txtUsuario").val(),
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                mensaje_alerta("CORRECTO!", "Se genero la clave sol.", "success");
                // Redireccionar a la vista de Clave Sol
                //window.location.href = "../M07SM04_ClaveSol/M07SM04_ClaveSol.php";
				window.location.href = dato.ruta;
            } else {
                mensaje_alerta("ERROR!", dato.data, "info");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function IrBoleta(tipo_comprobante, propiedad, cliente){
    LimpiarBoleta(cliente, propiedad);
    LimpiarBoletaFac(cliente, propiedad);
    CargarTotalesComprobante(cliente, propiedad);
    MostrarVistaTC(tipo_comprobante, propiedad, cliente);
}

function IrFactura(tipo_comprobante, propiedad, cliente){
    LimpiarBoleta(cliente, propiedad);
    LimpiarBoletaFac(cliente, propiedad);
    MostrarVistaTC(tipo_comprobante, propiedad, cliente);
}


//===============LLENAR TABLA LETRAS PAGADAS POR FACTURAR ESPECIFICO ==============================================
function CargarPagosFacturacion(tipodoc, propiedad, cliente) {
    bloquearPantalla("Cargando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarTablaPagosFacturacion": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad,
        "txtFiltroTipoComprobante": tipodoc
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosFacturacion, null, 10000, null);
}

function respuestaBuscarPagosFacturacion(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaPagosFacturacion(dato.data, dato.tipo_comp);
}

var getTablaPagosFacturacion = null;
function LlenarTablaPagosFacturacion(datos, tp) {
    if (getTablaPagosFacturacion) {
        getTablaPagosFacturacion.destroy();
        getTablaPagosFacturacion = null;
    }
    var tipocom = tp;
    getTablaPagosFacturacion = $('#TablaPagosFacturacion').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = ""; 
                    if(tipocom=='03'){

                        if(row.estado_fac=="PENDIENTE"){                                           
                            html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="SeleccionarPago(\'' + data + '\',\'' + row.cliente + '\',\'' + row.propiedad + '\',\'' + row.tipo_comp + '\')" title="Asignar Pago"><i class="fas fa-plus-square"></i></a>';                        
                        }else{
                            html = '';
                        }

                    }else{

                        if(row.estado_fac=="PENDIENTE"){                                           
                            html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="SeleccionarPago(\'' + data + '\',\'' + row.cliente + '\',\'' + row.propiedad + '\',\'' + row.tipo_comp + '\')" title="Asignar Pago"><i class="fas fa-plus-square"></i></a>';                        
                        }else{
                            html = '';
                        }

                    }
                    
                    return html;
                }
            },
            { "data": "fecha_pago" },
            { "data": "letra" },
            { "data": "tea" },
            { "data": "interes" },
            { "data": "capital" },
            { "data": "tipo_moneda" },            
            { "data": "importe_pago" },
            { "data": "tipo_cambio" },  
            { "data": "pagado" },            
            { "data": "facturado" },
            { "data": "saldo" },
            {
                "data": "estado_fac",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.estado_fac=="PENDIENTE"){
                        html = '<span class="badge" style="background-color: #F09900; color: white; font-weight: bold;">' + row.estado_fac + '</span>';
                    }else{                       
                        html = '<span class="badge" style="background-color: #00BC17; color: white; font-weight: bold;">' + row.estado_fac + '</span>';   
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

// ==================================================================================================

function CargarDatosComprobante(cliente, propiedad){
    bloquearPantalla("Cargando..");
    var data = {
        "btnCargarDatosBoleta": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
               // console.log(dato);
                $("#txtRazonSocial").val(dato.razon_social);
                $("#txtDireccion").val(dato.direccion);
                $("#txtRuc").val(dato.ruc);
                $("#txtLugar").val(dato.lugar);
                $("#txtSerie").val(dato.serie);

                $("#txtFechaVencimiento").val(dato.fecha);
                $("#txtFechaEmision").val(dato.fecha);
                $("#txtNroDocumento").val(dato.data.documento);
                $("#txtdatos").val(dato.data.datos);
                $("#cbxTipoMoneda").val(dato.data.tipo_moneda);

                $("#txtNumeroControlBol").val(dato.num_bol);
                $("#txtSerieControlBol").val(dato.serie_bol); 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}

function CargarTotalesComprobante(cliente, propiedad){
    var data = {
        "btnCargarTotalesComprobante": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad,
        "txtUsuario": $("#txtUsuario").val(),
        "txtFechaEmision": $("#txtFechaEmision").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {    
                    $("#txtOpGravada").val(dato.data.op_gravada);
                    $("#txtOp").val(dato.data.op);
                    $("#txtExonerada").val(dato.data.exonerada);
                    $("#txtOpInafecta").val(dato.data.op_inafecta);
                    $("#txtIsc").val(dato.data.isc);
                    $("#txtIgv").val(dato.data.igv);
                    $("#txtOtrosCargos").val(dato.data.otros_cargos);
                    $("#txtOtrosTributos").val(dato.data.otros_tributos);
                    $("#txtMontoRedondeo").val(dato.data.monto_redondeo);
                    $("#txtImporteTotal").val(dato.data.importe_total);
                }else{
                    $("#txtOpGravada").val("0.00");
                    $("#txtOp").val("0.00");
                    $("#txtExonerada").val("0.00");
                    $("#txtOpInafecta").val("0.00");
                    $("#txtIsc").val("0.00");
                    $("#txtIgv").val("0.00");
                    $("#txtOtrosCargos").val("0.00");
                    $("#txtOtrosTributos").val("0.00");
                    $("#txtMontoRedondeo").val("0.00");
                    $("#txtImporteTotal").val("0.00");
                }
 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}


function SeleccionarPago(id, cliente, propiedad, tipodoc){
    bloquearPantalla("Cargando..");
    var data = {
        "btnSeleccionarPago": true,
        "IdRegistro": id,
        "cliente": cliente,
        "propiedad": propiedad,
        "tipodoc": tipodoc
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {                  
                $("#__IDPAGO_DET").val(dato.id);
                $("#__PROP").val(dato.propiedad);
                $("#__CLIE").val(dato.cliente);
                $("#__TIPCOM").val(dato.tipodoc);

                $("#cbxTipoMonedaVAL").val(dato.cod_tipo_moneda);
                $("#txtTotalPagadoVAL").val(dato.pagado);
                $("#txtTipoCambioVAL").val(dato.tipo_cambio);
                $("#txtTotalEmitirVAL").val(dato.pagado);
                
                $('#modalSeleccionPago').modal('show');  
            } else {
                mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    }); 
}

function AgregarPago(id, total, total_ref){
    bloquearPantalla('Agregando..')
    var data = {
        "btnAgregarPago": true,
        "txtFiltroCliente": $("#__CLIE").val(),
        "txtFiltroPropiedad": $("#__PROP").val(),
        "txtUsuario": $("#txtUsuario").val(),
        "txtFechaEmision": $("#txtFechaEmision").val(),
        "txtFiltroTipoComprobante": $("#__TIPCOM").val(),
        "txtSerieControlFac": $("#txtSerieControlBol").val(),
        "txtNumeroControlFac": $("#txtNumeroControlBol").val(),
        "IdRegistro": id,
        "DatoTotal": total,
        "TotalRef": total_ref
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == 'ok') {                
                $('#modalSeleccionPago').modal('hide'); 
                EjecutarInformacion(dato.cliente, dato.propiedad);
                CargarTotalesComprobante(dato.cliente, dato.propiedad);

                document.querySelector('#lbl_tot_cap').innerText = dato.total_capital;
                document.querySelector('#lbl_tot_int').innerText = dato.total_intereses;

                if(dato.total_intereses == "0.00"){
                    $('#PanelTotCronogramaBol').hide(); 
                }else{
                    $('#PanelTotCronogramaBol').show(); 
                }

            } else {
                mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });  
    
}

function EjecutarInformacion(cliente, propiedad){
    CargarItemsFacturacion(cliente, propiedad);
    CargarTotalesComprobante(cliente, propiedad); 
}

function EjecutarInformacionFac(cliente, propiedad){
    CargarItemsFacturacionFac(cliente, propiedad);
    CargarTotalesComprobanteFac(cliente, propiedad); 
}

//LLENAR TABLA PAGOS DE FACTURACION
function CargarItemsFacturacion(cliente, propiedad) {
    bloquearPantalla("Cargando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarItemsBoleta": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad,
        "txtUsuario": $("#txtUsuario").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarItemsFacturacion, null, 10000, null);
}

function respuestaBuscarItemsFacturacion(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaItemsFacturacion(dato.data);   
}

var getTablaItemsFacturacion = null;
function LlenarTablaItemsFacturacion(datos) {
    if (getTablaItemsFacturacion) {
        getTablaItemsFacturacion.destroy();
        getTablaItemsFacturacion = null;
    }

    getTablaItemsFacturacion = $('#TablaItemsBoleta').DataTable({
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
            { "data": "cantidad" },
            { "data": "medida" },            
            { "data": "descripcion" },
            { "data": "tipo" },
            { "data": "valor_unitario" }, 
            { "data": "valor_igv" },
            { "data": "descuento" },
            { "data": "importe_venta" }
            
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

function IncluidoIGV(id){
    var data = {
        "btnIncluirIGV": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {                 
                CargarItemsFacturacion();    
                CargarTotalesComprobante();
                mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");
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

function NoIncluidoIGV(id){
    var data = {
        "btnNoIncluidoIGV": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") { 
                mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");
                CargarItemsFacturacion();    
                CargarTotalesComprobante();
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



function HabilitarInafecto(id){
    var data = {
        "btnHabilitarInafecto": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") { 
                mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");
                CargarItemsFacturacion();    
                CargarTotalesComprobante();
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

function DeshabilitarInafecto(id){
    var data = {
        "btnDeshabilitarInafecto": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") { 
                mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");
                CargarItemsFacturacion();    
                CargarTotalesComprobante();
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

function LimpiarCamposTotales(){
    $("#txtOpGravada").val("0.00");
    $("#txtOp").val("0.00");
    $("#txtExonerada").val("0.00");
    $("#txtOpInafecta").val("0.00");
    $("#txtIsc").val("0.00");
    $("#txtIgv").val("0.00");
    $("#txtOtrosCargos").val("0.00");
    $("#txtOtrosTributos").val("0.00");
    $("#txtMontoRedondeo").val("0.00");
    $("#txtImporteTotal").val("0.00"); 
}

function LimpiarBoleta(cliente, propiedad){
    var data = {
        "btnLimpiarBoleta": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad,
        "txtUsuario": $("#txtUsuario").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {                               
                $("#TablaComprobantesEmitidos").hide();
                LimpiarCamposTotales();
            } else {
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

function Eliminar(id, cliente, propiedad){
    bloquearPantalla("Eliminando..");
    var data = {
        "btnEliminarPagoComprobante": true,
        "IdRegistro": id,
        "cliente": cliente,
        "propiedad": propiedad
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") { 
                CargarItemsFacturacion(dato.cliente, dato.propiedad);   
                LimpiarCamposTotales(); 
                CargarTotalesComprobante(dato.cliente, dato.propiedad);
                
            } else {
                mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function EnviarTXT(){
    var data = {
        "Enviartxt": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                mensaje_alerta("\u00A1CORRECTO!", "Se envio informacion de comprobante", "success");   
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function ValidarCamposBoleta() {
    var flat = true;  
        if ($("#txtNroDocumento").val() === "" || $("#txtNroDocumento").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el Nro de Documento del Cliente.", "info");
            flat = false;
        } else if ($("#txtdatos").val() === "" || $("#txtdatos").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar los apellidos y nombres del cliente.", "info");
            flat = false;
        } else if ($("#txtDireccionCliente").val() === "" || $("#txtDireccionCliente").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la direcci\u00F3n del Cliente", "info");
            flat = false;
        }    
    return flat;
}


function EmitirBoleta(){
    if(ValidarCamposBoleta()){
        bloquearPantalla("Emitiendo Boleta Electronica...");
        var data = {
            "btnEmitirBoleta": true,
            "txtFiltroCliente": $("#__clt").val(),
            "txtFiltroPropiedad": $("#__prd").val(),
            "txtUsuario": $("#txtUsuario").val(),
            "txtFechaEmision": $("#txtFechaEmision").val(),
            "txtFechaVencimiento": $("#txtFechaVencimiento").val(),
            "cbxTipoMoneda": $("#cbxTipoMoneda").val(),
            "cbxTipoDocumento": $("#cbxTipoDocumento").val(),
            "txtNroDocumento": $("#txtNroDocumento").val(),
            "txtdatos": $("#txtdatos").val(),
            "txtDireccionCliente": $("#txtDireccionCliente").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                console.log(dato);
                if (dato.status == "ok") { 
                    $("#TablaComprobantesEmitidos").show();
                    CargarComprobantesEmitidos(dato.serie, dato.numero, dato.fecha_emision);
                    CargarPagosFacturacion(dato.tipodoc, dato.propiedad, dato.cliente);
                    CargarComprobantesImpresos();                    
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");                    
                    $("#botonesAccion").hide();
					
					$("#panel_btn_bol").hide();
                    $("#panel_btn_fac").hide();
                    CargarPagosFacturacionGnral();
					
                } else {
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

function InsertarPagsCab(id){
	var data = {
		"btnProcesarIngEg": true,
		"id": id,
		"serie": serie,
		"numero": numero
		
	};  
	$.ajax({
		type: "POST",
		url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
		data: data,
		dataType: "json",
		success: function (dato) { 
			console.log(dato);
		},
		error: function (jqXHR, textStatus, errorThrown) {
		
			console.log(textStatus + ': ' + errorThrown);
			desbloquearPantalla();
		},
		timeout: timeoutDefecto
	});
}


function InsertarVentas(serie, numero){
	var data = {
		"btnProcesarVentas": true,
		"serie": serie,
		"numero": numero
		
	};  
	$.ajax({
		type: "POST",
		url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
		data: data,
		dataType: "json",
		success: function (dato) { 
		    
			//console.log(dato);
			
		},
		error: function (jqXHR, textStatus, errorThrown) {
		
			console.log(textStatus + ': ' + errorThrown);
			desbloquearPantalla();
		},
		timeout: timeoutDefecto
	});
}


function CargarComprobantesEmitidos(serie, numero, fechaEmision) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarTablaComprobantesEmitidos": true,
        "serie": serie,
        "numero": numero,
        "fechaEmision": fechaEmision
    };
    realizarJsonPost(url, dato, respuestaBuscarComprobantesEmitidos, null, 10000, null);
}

function respuestaBuscarComprobantesEmitidos(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaComprobantesEmitidos(dato.data);
}

var getTablaComprobantesEmitidos = null;
function LlenarTablaComprobantesEmitidos(datos) {
    if (getTablaComprobantesEmitidos) {
        getTablaComprobantesEmitidos.destroy();
        getTablaComprobantesEmitidos = null;
    }

    getTablaComprobantesEmitidos = $('#TablaComprobanteImpreso').DataTable({
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
            { "data": "fecha_emision" },
            { "data": "serie" },
            { "data": "numero" },            
            { "data": "datos" },
            { "data": "propiedad" },
            { "data": "propiedad" },
            {
                "data": "url_valor",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<a class="badge" href="'+row.url_valor+'" target="_blank" style="font-size: 14px; color red; text-align: center; font-weight: bold;"><i class="fas fa-file-pdf"></i> Ver</a>';
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

function BuscarDocumento(){
    bloquearPantalla("Buscando Documento...");
    var data = {
        "btnBuscarDocumento": true,
        "cbxTipoDocumento": $("#cbxTipoDocumento").val(),
        "txtNroDocumento": $("#txtNroDocumento").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {                
               $("#txtdatos").val(dato.cliente);
               $("#txtDireccionCliente").val(dato.direccion);
            } else {
                $("#txtdatos").val("");
                $("#txtDireccionCliente").val("");
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




function CargarComprobantesImpresos() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarTablaComprobantesImpresos": true,
        "txtFiltroCliente2": $("#txtFiltroCliente2").val(),
        "txtFiltroTipoComprobante2": $("#txtFiltroTipoComprobante2").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarComprobantesImpresos, null, 10000, null);
}

function respuestaBuscarComprobantesImpresos(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaComprobantesImpresos(dato.data);
}

var getTablaComprobantesImpresos = null;
function LlenarTablaComprobantesImpresos(datos) {
    if (getTablaComprobantesImpresos) {
        getTablaComprobantesImpresos.destroy();
        getTablaComprobantesImpresos = null;
    }

    getTablaComprobantesImpresos = $('#TablaDocImpresos').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
            },
            {
                'aTargets': [1],
                'ordering': false,
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
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";  
                    if(row.codigo_tipo_comprobante != "07"){                                                            
                        html = '<a href="#" class="btn btn-success-action" style="font-weight: bold; font-size: 11px" onclick="IrEnviarMensaje(\'' + data + '\')" title="Enviar documento"><i class="fab fa-whatsapp"></i> Enviar</a> \ <a href="#" class="btn btn-delete-action" style="font-weight: bold; font-size: 11px" onclick="AnularComprobante(\'' + data + '\')" title="Anular Documento"><i class="fas fa-times"></i> Anular</a> \ <a href="#" class="btn btn-primary-action" style="font-weight: bold; font-size: 11px" onclick="IrNotaCredito(\'' + data + '\')" title="Nota de Credito"><i class="fas fa-file-alt"></i> Nota Cr\u00E9dito</a>'; 
                    }else{
                        html = '<a href="#" class="btn btn-success-action" style="font-weight: bold; font-size: 11px" onclick="IrEnviarMensaje(\'' + data + '\')" title="Enviar documento"><i class="fab fa-whatsapp"></i> Enviar</a> \ <a href="#" class="btn btn-delete-action" style="font-weight: bold; font-size: 11px" onclick="AnularComprobante(\'' + data + '\')" title="Anular Documento"><i class="fas fa-times"></i> Anular</a>'; 
                    }
                    return html;
                }
            },
            {
                "data": "nombre_comprobante",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<div class="badge etiqueta-js" style="background-color: '+ row.color_comprobante +'; text-align: center; width: 100%;">'+ row.nombre_comprobante +'</div>';
                    return html;
                } 
            },
            { "data": "fecha_emision" },
            { "data": "serie" },
            { "data": "numero" },            
            { "data": "cliente" },
            { "data": "igv" },
            { "data": "inafecto" },
            { "data": "total" },
            {
                "data": "url_valor",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<a class="badge" href="'+row.url_valor+'" target="_blank" style="font-size: 14px; color red; text-align: center; font-weight: bold;"><i class="fas fa-file-pdf"></i> Ver</a>';
                    return html;
                } 
            },
            { "data": "tip_doc_ref" },
            { "data": "serie_ref" },
            { "data": "correlativo_ref" }
            
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

function  IrNotaCredito(id){
    /*var data = {
        "btnIrNotaCredito": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);   */            
                //window.location.href="#EmitirComprobantes";
                CargarComprobantesNC(id);
                BuscarDatosComprobanteNC(id);
                CargarDatosComprobanteNC(id);
                $('#modalNotaCredito').modal('show'); 
                $("#cbxTipoNotaCredito").val("01");
                $("#TablaComprobantesEmitidosNC").hide();
      /*  },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   */
}

function AnularComprobante(id){
    var mensaje = 'Si anula el comprobante en el sistema de gesti\u00F3n recuerde que tambien tendrá que se anularlo vía la plataforma de MiFac. La anulaci\u00F3n del comprobante en el sistema habilitará nuevamente la opci\u00F3n de emitir un nuevo comprobante para el pago en relaci\u00F3n. ¿Est\u00E1 seguro(a) de anular el comprobante?';
    mensaje_eliminar_parametro(mensaje, AccionAnularComprobante, id);
}

function AccionAnularComprobante(id){
    // var idpart = id;       
     var timeoutDefecto = 1000 * 60;
     bloquearPantalla("Procesando...");
     var data = {
         "btnAnularComprobante": true,
         "idUsuario": $("#txtUsuario").val(),
         "idRegistro": id
     };
     $.ajax({
         type: "POST",
         url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
         data: data,
         dataType: "json",
         success: function (dato) {
             desbloquearPantalla();
             if (dato.status == "ok") {
                mensaje_alerta("CORRECTO!", "Se anul\u00F3 el comprobante seleccionado.", "success"); 
                CargarComprobantesImpresos();
             } else {
                 mensaje_alerta("ERROR!", dato.data, "info");
             }
 
         },
         error: function (jqXHR, textStatus, errorThrown) {
             console.log(textStatus + ': ' + errorThrown);
             desbloquearPantalla();
         },
         timeout: timeoutDefecto
     });
 }



function  IrEnviarMensaje(id){
   var data = {
        "btnVerDatosMensaje": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);               
                $("#txtNroCelular").val(dato.numero);
                $("#__txtIDCOMPROBANTE").val(dato.id);
                $('#modalMensajeWts').modal('show');  
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function  EnviarMensajeWts(){
    var data = {
        "btnEnviarMensajeWts": true,
        "idRegistro": $("#__txtIDCOMPROBANTE").val(),
        "txtNroCelular": $("#txtNroCelular").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);               
                mensaje_alerta("\u00A1CORRECTO!", "Se envi\u00F3 el documento al nro de celular ingresado.", "success");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}


function  BuscarDatosComprobanteNC(id){
    var data = {
        "btnBuscarDatosComprobanteNC": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);               
                $("#txtDenominacion").val(dato.denominacion);
                $("#txtFechaModificaCom").val(dato.fec_emision);
                $("#txtNroComprobanteNC").val(dato.dato_ser_num);
                $("#txtNroDocumentoNC").val(dato.documento);
                $("#txtDatosClienteNC").val(dato.dato_cliente); 

                $("#txtSerieControlNC").val(dato.serie); 
                $("#txtNumeroControlNC").val(dato.numero); 
                
                //TOTALES
                $("#txtOpGravadaNC").val(dato.data.op_gravada);
                $("#txtIgvNC").val(dato.data.igv);
                $("#txtOpInafectaNC").val(dato.data.inafecto);
                $("#txtImporteTotalNC").val(dato.data.importe_total);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}



/* =================================== NOTA DE CREDITO ============================= */

function CargarDatosComprobanteNC(id){
    var data = {
        "btnCargarDatosNotaCredito": true,
        "txtFiltroCliente": $("#txtNroDocumentoNC").val(),
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);               
                $("#txtRazonSocialNC").val(dato.razon_social);
                $("#txtDireccionNC").val(dato.direccion);
                $("#txtRucNC").val(dato.ruc);
                $("#txtLugarNC").val(dato.lugar);
                $("#txtSerieNC").val(dato.seriefac);

                $("#txtFecVencimientoNC").val(dato.fecha);
                $("#txtFecEmisionNC").val(dato.fecha);
                //$("#txtRucFac").val(dato.data.documento);
                //$("#txtClienteFac").val(dato.data.datos);
                $("#cbxTipoMonedaNC").val("USD");

                $("#txtSerieControlNC").val(dato.serie_control);
                $("#txtNumeroControlNC").val(dato.num_control);
                
                 $("#txtSerieControlDNC").val(dato.dato_serie);
                $("#txtNumeroControlDNC").val(dato.dato_numero);

               /* $("#txtNumeroControl").val(dato.data.num_control);
                $("#txtSerieControlFac").val(dato.data.serie_control);*/
 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}

function CargarComprobantesNC(id) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnCargarPagosNotaCredito": true,
        "idRegistro": id
    };
    realizarJsonPost(url, dato, respuestaBuscarComprobantesNC, null, 10000, null);
}

function respuestaBuscarComprobantesNC(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaComprobantesNC(dato.data);
}

var getTablaComprobantesNC = null;
function LlenarTablaComprobantesNC(datos) {
    if (getTablaComprobantesNC) {
        getTablaComprobantesNC.destroy();
        getTablaComprobantesNC = null;
    }

    getTablaComprobantesNC = $('#TablaNotaCredito').DataTable({
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
            { "data": "cantidad" },
            { "data": "unidad" },
            { "data": "descripcion" },
            { "data": "valor_unitario" },            
            { "data": "valor_venta" }
            
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


function EmitirNotaCredito(){
    
        bloquearPantalla("Emitiendo Nota de Credito...");
        var data = {
            "btnEmitirNotaCredito": true,
            "txtUsuario": $("#txtUsuario").val(),
            "txtFechaEmision": $("#txtFecEmisionNC").val(),
            "txtFechaVencimiento": $("#txtFecVencimientoNC").val(),
            "cbxTipoMoneda": $("#cbxTipoMonedaNC").val(),
            "cbxTipoDocumento": 7,
            "txtNroDocumento": $("#txtNroDocumentoNC").val(),
            "txtdatos": $("#txtDatosClienteNC").val(),
            "txtSerieControlNC": $("#txtSerieControlNC").val(),
            "txtNumeroControlNC": $("#txtNumeroControlNC").val(),
            "txtFechaModificaCom": $("#txtFechaModificaCom").val(),
            "txtSustentoNC": $("#txtSustentoNC").val(),
            "txtDenominacion": $("#txtDenominacion").val(),
            "txtSerieControlDNC": $("#txtSerieControlDNC").val(),
            "txtNumeroControlDNC": $("#txtNumeroControlDNC").val(),
            "cbxTipoNotaCredito": $("#cbxTipoNotaCredito").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") { 
                    $("#TablaComprobantesEmitidosNC").show();
                    CargarComprobantesEmitidosNC(dato.serie, dato.numero, dato.fecha_emision);
                    CargarPagosFacturacion();
                    CargarComprobantesImpresos();                   
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");                    
                    $("#botonesAccionFac").hide();
                } else {
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

function CargarComprobantesEmitidosNC(serie, numero, fechaEmision) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarTablaComprobantesEmitidos": true,
        "serie": serie,
        "numero": numero,
        "fechaEmision": fechaEmision
    };
    realizarJsonPost(url, dato, respuestaBuscarComprobantesEmitidosNC, null, 10000, null);
}

function respuestaBuscarComprobantesEmitidosNC(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaComprobantesEmitidosNC(dato.data);
}

var getTablaComprobantesEmitidosNC = null;
function LlenarTablaComprobantesEmitidosNC(datos) {
    if (getTablaComprobantesEmitidosNC) {
        getTablaComprobantesEmitidosNC.destroy();
        getTablaComprobantesEmitidosNC = null;
    }

    getTablaComprobantesEmitidosNC = $('#TablaComprobanteImpresoNC').DataTable({
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
            { "data": "fecha_emision" },
            { "data": "serie" },
            { "data": "numero" },            
            { "data": "datos" },
            { "data": "propiedad" },
            { "data": "propiedad" },
            {
                "data": "url_valor",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<a class="badge" href="'+row.url_valor+'" target="_blank" style="font-size: 14px; color red; text-align: center; font-weight: bold;"><i class="fas fa-file-pdf"></i> Ver</a>';
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


/*==================================== FACTURA ===================================== */ 
function CargarDatosComprobanteFac(cliente, propiedad){
    var data = {
        "btnCargarDatosFactura": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);               
                $("#txtRazonSocialFac").val(dato.razon_social);
                $("#txtDireccionFac").val(dato.direccion);
                $("#txtRucFac").val(dato.ruc);
                $("#txtLugarFac").val(dato.lugar);
                $("#txtSerieFac").val(dato.seriefac);

                $("#txtFechaVencimientoFac").val(dato.fecha);
                $("#txtFechaEmisionFac").val(dato.fecha);
                //$("#txtRucFac").val(dato.data.documento);
                //$("#txtClienteFac").val(dato.data.datos);
                $("#cbxTipoMonedaFac").val(dato.data.tipo_moneda);

                $("#txtNumeroControlFac").val(dato.num_control);
                $("#txtSerieControlFac").val(dato.serie_control);
                
                $("#botonesAccionFac").show();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}

function AgregarPagFac(id, total, total_ref){
    var data = {
        "btnAgregarPago": true,
        "txtFiltroCliente": $("#__CLIE").val(),
        "txtFiltroPropiedad": $("#__PROP").val(),
        "txtUsuario": $("#txtUsuario").val(),
        "txtFechaEmision": $("#txtFechaEmisionFac").val(),
        "txtFiltroTipoComprobante": $("#__TIPCOM").val(),
        "txtSerieControlFac": $("#txtSerieControlBol").val(),
        "txtNumeroControlFac": $("#txtNumeroControlBol").val(),
        "IdRegistro": id,
        "DatoTotal": total,
        "TotalRef": total_ref
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {                
                $('#modalSeleccionPago').modal('hide'); 
                EjecutarInformacionFac(dato.cliente, dato.propiedad);                      
                CargarTotalesComprobanteFac(dato.cliente, dato.propiedad);

                document.querySelector('#lbl_tot_cap_f').innerText = dato.total_capital;
                document.querySelector('#lbl_tot_int_f').innerText = dato.total_intereses;

                if(dato.total_intereses == "0.00"){
                    $('#PanelTotCronogramaFac').hide(); 
                }else{
                    $('#PanelTotCronogramaFac').show(); 
                }

            } else {
                mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

//LLENAR TABLA PAGOS DE FACTURACION
function CargarItemsFacturacionFac(cliente, propiedad) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarItemsBoleta": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad,
        "txtUsuario": $("#txtUsuario").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarItemsFacturacionFac, null, 10000, null);
}

function respuestaBuscarItemsFacturacionFac(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaItemsFacturacionFac(dato.data);
    
}

var getTablaItemsFacturacionFac = null;
function LlenarTablaItemsFacturacionFac(datos) {
    if (getTablaItemsFacturacionFac) {
        getTablaItemsFacturacionFac.destroy();
        getTablaItemsFacturacionFac = null;
    }

    getTablaItemsFacturacionFac = $('#TablaPagoComprobanteFac').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";                    
                    html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="Eliminar(\'' + data + '\')" title="Agregar Pago"><i class="fas fa-trash"></i></a>';
                    return html;
                }
            },
            { "data": "cantidad" },
            { "data": "medida" },            
            { "data": "descripcion" },
            { "data": "valor_unitario" },   
            { "data": "descuento" },
            { "data": "importe_venta" }
            
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

function CargarTotalesComprobanteFac(cliente, propiedad){
    var data = {
        "btnCargarTotalesComprobante": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad,
        "txtUsuario": $("#txtUsuario").val(),
        "txtFechaEmision": $("#txtFechaEmisionFac").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                console.log(dato);
                $("#txtOpGravadaFac").val(dato.data.op_gravada);
                $("#txtOpFac").val(dato.data.op);
                $("#txtExoneradaFac").val(dato.data.exonerada);
                $("#txtOpInafectaFac").val(dato.data.op_inafecta);
                $("#txtIscFac").val(dato.data.isc);
                $("#txtIgvFac").val(dato.data.igv);
                $("#txtOtrosCargosFac").val(dato.data.otros_cargos);
                $("#txtOtrosTributosFac").val(dato.data.otros_tributos);
                $("#txtMontoRedondeoFac").val(dato.data.monto_redondeo);
                $("#txtImporteTotalFac").val(dato.data.importe_total);
 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function BuscarDocumentoFac(){
    bloquearPantalla("Buscando Documento...");
    var data = {
        "btnBuscarDocumentoFac": true,
        "txtRucFac": $("#txtRucClienteFac").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {                
               $("#txtClienteFac").val(dato.cliente);
               $("#txtDireccionClienteFac").val(dato.direccion);
            } else {
                $("#txtClienteFac").val("");
                $("#txtDireccionClienteFac").val("");
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


function ValidarCamposFactura() {
    var flat = true;
  
        if ($("#txtRucClienteFac").val() === "" || $("#txtRucClienteFac").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el RUC del cliente.", "info");
            flat = false;
        } else if ($("#txtClienteFac").val() === "" || $("#txtClienteFac").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Se requiere el nombre y apellido del cliente.", "info");
            flat = false;
        } else if ($("#txtDireccionClienteFac").val() === "" || $("#txtDireccionClienteFac").val() === null) {
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la direcci贸n del cliente", "info");
            flat = false;
        }
    
    return flat;
}

function EmitirFactura(){
    if(ValidarCamposFactura()){
        bloquearPantalla("Emitiendo Factura Electronica...");
        var data = {
            "btnEmitirFactura": true,
            "txtFiltroCliente": $("#__clt").val(),
            "txtFiltroPropiedad": $("#__prd").val(),
            "txtUsuario": $("#txtUsuario").val(),
            "txtFechaEmision": $("#txtFechaEmisionFac").val(),
            "txtFechaVencimiento": $("#txtFechaVencimientoFac").val(),
            "cbxTipoMoneda": $("#cbxTipoMonedaFac").val(),
            "cbxTipoDocumento": 6,
            "txtNroDocumento": $("#txtRucClienteFac").val(),
            "txtdatos": $("#txtClienteFac").val(),
            "txtDireccionCliente": $("#txtDireccionClienteFac").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                console.log(dato);
                if (dato.status == "ok") { 
                    $("#TablaComprobantesEmitidosFac").show();
                    CargarComprobantesEmitidosFac(dato.serie, dato.numero, dato.fecha_emision);
                    CargarPagosFacturacion(dato.tipodoc, dato.propiedad, dato.cliente);
                    CargarComprobantesImpresos();                  
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");                   
                    $("#botonesAccionFac").hide();

					$("#panel_btn_bol").hide();
                    $("#panel_btn_fac").hide();

                    CargarPagosFacturacionGnral();
                } else {
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
 
function CargarComprobantesEmitidosFac(serie, numero, fechaEmision) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarTablaComprobantesEmitidos": true,
        "serie": serie,
        "numero": numero,
        "fechaEmision": fechaEmision
    };
    realizarJsonPost(url, dato, respuestaBuscarComprobantesEmitidosFac, null, 10000, null);
}

function respuestaBuscarComprobantesEmitidosFac(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaComprobantesEmitidosFac(dato.data);
}

var getTablaComprobantesEmitidosFac = null;
function LlenarTablaComprobantesEmitidosFac(datos) {
    if (getTablaComprobantesEmitidosFac) {
        getTablaComprobantesEmitidosFac.destroy();
        getTablaComprobantesEmitidosFac = null;
    }

    getTablaComprobantesEmitidosFac = $('#TablaComprobanteImpresoFac').DataTable({
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
            { "data": "fecha_emision" },
            { "data": "serie" },
            { "data": "numero" },            
            { "data": "datos" },
            { "data": "propiedad" },
            { "data": "propiedad" },
            {
                "data": "url_valor",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<a class="badge" href="'+row.url_valor+'" target="_blank" style="font-size: 14px; color red; text-align: center; font-weight: bold;"><i class="fas fa-file-pdf"></i> Ver</a>';
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

function LimpiarCamposTotalesFac(){
    $("#txtOpGravadaFac").val("0.00");
    $("#txtOpFac").val("0.00");
    $("#txtExoneradaFac").val("0.00");
    $("#txtOpInafectaFac").val("0.00");
    $("#txtIscFac").val("0.00");
    $("#txtIgvFac").val("0.00");
    $("#txtOtrosCargosFac").val("0.00");
    $("#txtOtrosTributosFac").val("0.00");
    $("#txtMontoRedondeoFac").val("0.00");
    $("#txtImporteTotalFac").val("0.00"); 
}

function LimpiarBoletaFac(cliente, propiedad){
    var data = {
        "btnLimpiarBoleta": true,
        "txtFiltroCliente": cliente,
        "txtFiltroPropiedad": propiedad,
        "txtUsuario": $("#txtUsuario").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {                               
                $("#TablaComprobantesEmitidosFac").hide();
                LimpiarCamposTotalesFac();

                document.querySelector('#lbl_tot_cap').innerText = "0.00";
                document.querySelector('#lbl_tot_int').innerText = "0.00";

                document.querySelector('#lbl_tot_cap_f').innerText = "0.00";
                document.querySelector('#lbl_tot_int_f').innerText = "0.00";
            } else {
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



/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= OTROS CONCEPTOS A EMITIR ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/


function MostrarVistaOC(){
    var data = $("#txtFiltroTipoComprobanteOC").val();
    $("#contenido_campos").show();
    $("#txtCorreoClienteOC").val("");
    $("#txtEmailFacOC").val("");
    LimpiarCamposIngresantes();
    if (data == '03') {
        $("#PanelBoletaElectronicaOC").show();
        $("#PanelBoletaElectronicaOC").removeClass("disabled-form");
        $("#PanelFacturaElectronicaOC").hide();
        CargarDatosComprobanteBolOC();
        CargarItemsFacturacionBolOC();

        $("#cbxTipoDocOC").val($("#cbxFiltroTipoDocumentoOC").val());
        $("#txtNroDocOC").val($("#txtFiltroNroDocumentoOC").val());
        $("#txtdatosOC").val($("#txtFiltroDatoClienteOC").val());
        $("#txtDireccionClienteOC").val($("#txtFiltroDireccionOC").val());
        $("#txtCamDescripcionOC").val($("#txtfiltroMotivoConceptoOC").val());
        $("#txtCorreoClienteOC").val($("#txtFiltroCorreoOC").val());
        var concepto = $("#txtfiltroConceptoVentaOC").val();
        
        if(concepto == "01"){
            $("#PanelInafecto").hide();
            $("#PanelPagosReservas").show();
            BuscarReservasValidadas();
        }else{
            if(concepto == "04"){
                $("#PanelInafecto").show();
                $("#cbxInafectoOC").val(1);
                $("#PanelPagosReservas").hide();
            }else{
                $("#PanelInafecto").show();
                $("#cbxInafectoOC").val(2);
                $("#PanelPagosReservas").hide();
            }
        }
        
        $("#botonesAccionOC").show();
        $("#TablaComprobantesEmitidosOC").hide();
        

    } else {
        $("#PanelFacturaElectronicaOC").show();
        $("#PanelFacturaElectronicaOC").removeClass("disabled-form");
        $("#PanelBoletaElectronicaOC").hide();
        CargarDatosComprobanteFacOC();
        CargarItemsFacturacionFacOC();
        CargarItemsFacturacionFacOC();

        $("#txtRucClienteFacOC").val($("#txtFiltroNroDocumentoOC").val());
        $("#txtClienteFacOC").val($("#txtFiltroDatoClienteOC").val());
        $("#txtDireccionClienteFacOC").val($("#txtFiltroDireccionOC").val());
        $("#txtCamDescripcionFacOC").val($("#txtfiltroMotivoConceptoOC").val());
        $("#txtEmailFacOC").val($("#txtFiltroCorreoOC").val());
        var concepto = $("#txtfiltroConceptoVentaOC").val();
        
        if(concepto == "01"){
            $("#PanelInafectoFac").hide();
            BuscarReservasValidadas();
        }else{
            if(concepto == "04"){
                $("#PanelInafectoFac").show();
                $("#cbxInafectoOC").val(1);
                $("#PanelPagosReservas").hide();
            }else{
                $("#PanelInafectoFac").show();
                $("#cbxInafectoOC").val(2);
                $("#PanelPagosReservas").hide();
            }
        }
        
        $("#botonesAccionFacOC").show();
        $("#TablaComprobantesEmitidosFacOC").hide();
        
    }
       
}

function BuscarReservasValidadas(){

    var data = {
        "btnBuscarReservasVal": true,
        "txtFiltroClienteOC": $("#txtFiltroNroDocumentoOC").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            console.log(dato);
            if (dato.status == 'ok') { 
                
                CargarPagosReservas();
                var monto = dato.reserva;
                var total_Reg = dato.total;
                if(total_Reg>2){
                    $("#PanelPagosReservas").show();
                    $("#txtCamValorUnitOC").val("0.00");
                    $("#txtCamValorUnitFacOC").val("0.00");
                    $("#txtIdReservaVal").val("");
                    $("#txtIdReservaValFac").val("");
                }else{
                    if(monto>0){
                        $("#PanelPagosReservas").hide();
                        $("#txtCamValorUnitOC").val(dato.reserva);
                        $("#txtCamValorUnitFacOC").val(dato.reserva);
                        $("#txtIdReservaVal").val(dato.id);
                        $("#txtIdReservaValFac").val(dato.id);
                    }else{
                        $("#PanelPagosReservas").hide();
                        $("#txtCamValorUnitOC").val("0.00");
                        $("#txtCamValorUnitFacOC").val("0.00");
                        $("#txtIdReservaVal").val("");
                        $("#txtIdReservaValFac").val("");
                    }
                }
                
            } else {
                mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });  
    
}


function CargarPagosReservas() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarPagosReservas": true,
        "txtFiltroCliente": $("#txtFiltroNroDocumentoOC").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarItemsPagosReservas, null, 10000, null);
}

function respuestaBuscarItemsPagosReservas(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaItemsPagosReservas(dato.data);
    
}

var getTablaItemsPagosReserva = null;
function LlenarTablaItemsPagosReservas(datos) {
    if (getTablaItemsPagosReserva) {
        getTablaItemsPagosReserva.destroy();
        getTablaItemsPagosReserva = null;
    }

    getTablaItemsPagosReserva = $('#TablaPagosReservas').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";                    
                    html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="AgregarReserva(\'' + data + '\')" title="Agregar Pago"><i class="fas fa-plus-square"></i></a>';       
                    return html;
                }
            },
            { "data": "fecini" },
            { "data": "fecfin" },            
            { "data": "cliente" },
            { "data": "lote" },   
            { "data": "tipo_moneda" },
            { "data": "tipo_cambio" },
            { "data": "importe_pago" },
            { "data": "pagado" },
            { "data": "estado" }
            
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






function LimpiarCamposIngresantes(){
    $("#txtCamCantidadOC").val("1");
    $("#txtCamUnidadOC").val("UNIDAD");
    $("#txtCamDireccionOC").val("");
    $("#txtCamValorUnitOC").val("0.00");
    $("#txtCamDescOC").val("0.00");
    
    $("#txtCamCantidadFacOC").val("1");
    $("#txtCamUnidadFacOC").val("UNIDAD");
    $("#txtCamDireccionFacOC").val("");
    $("#txtCamValorUnitFacOC").val("0.00");
    $("#txtCamDescFacOC").val("0.00");
    LimpiarTemporalFacturacion();
    LimpiarCamposTotales();
}

function LimpiarTemporalFacturacion(){
    var data = {
        "btnLimpiarTemporal": true,
        "txtUsuario": $("#txtUsuario").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });  
    
}

function LimpiarCamposTotales(){
    $("#txtOpGravadaOC").val("0.00");
    $("#txtOpOC").val("0.00");
    $("#txtExoneradaOC").val("0.00");
    $("#txtOpInafectaOC").val("0.00");
    $("#txtIscOC").val("0.00");
    $("#txtIgvOC").val("0.00");
    $("#txtOtrosCargosOC").val("0.00");
    $("#txtOtrosTributosOC").val("0.00");
    $("#txtMontoRedondeoOC").val("0.00");
    $("#txtImporteTotalOC").val("0.00"); 
    
    $("#txtOpGravadaFacOC").val("0.00");
    $("#txtOpFacOC").val("0.00");
    $("#txtExoneradaFacOC").val("0.00");
    $("#txtOpInafectaFacOC").val("0.00");
    $("#txtIscFacOC").val("0.00");
    $("#txtIgvFacOC").val("0.00");
    $("#txtOtrosCargosFacOC").val("0.00");
    $("#txtOtrosTributosFacOC").val("0.00");
    $("#txtMontoRedondeoFacOC").val("0.00");
    $("#txtImporteTotalFacOC").val("0.00"); 
}

/*=== BOLETAS OTROS CONCEPTOS === */


function CargarItemsFacturacionBolOC() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarItemsBoletaOC": true,
        "txtFiltroCliente": $("#txtFiltroClienteOC").val(),
        "txtUsuario": $("#txtUsuario").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarItemsFacturacionBolOC, null, 10000, null);
}

function respuestaBuscarItemsFacturacionBolOC(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaItemsFacturacionBolOC(dato.data);
    
}

var getTablaItemsFacturacionBolOC = null;
function LlenarTablaItemsFacturacionBolOC(datos) {
    if (getTablaItemsFacturacionBolOC) {
        getTablaItemsFacturacionBolOC.destroy();
        getTablaItemsFacturacionBolOC = null;
    }

    getTablaItemsFacturacionBolOC = $('#TablaItemsBoletaOC').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";                    
                    //html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="Eliminar(\'' + data + '\')" title="Agregar Pago"><i class="fas fa-trash"></i></a>';
                    html = '';
                    return html;
                }
            },
            { "data": "cantidad" },
            { "data": "medida" },            
            { "data": "descripcion" },
            { "data": "valor_unitario" },   
            { "data": "descuento" },
            { "data": "importe_venta" }
            
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

function CargarTotalesComprobanteOC(){
    var data = {
        "btnCargarTotalesComprobanteOC": true,
        "txtFiltroCliente": $("#txtFiltroClienteOC").val(),
        "txtUsuario": $("#txtUsuario").val(),
        "txtFechaEmision": $("#txtFechaEmisionOC").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                $("#txtOpGravadaOC").val(dato.data.op_gravada);
                $("#txtOpOC").val(dato.data.op);
                $("#txtExoneradaOC").val(dato.data.exonerada);
                $("#txtOpInafectaOC").val(dato.data.op_inafecta);
                $("#txtIscOC").val(dato.data.isc);
                $("#txtIgvOC").val(dato.data.igv);
                $("#txtOtrosCargosOC").val(dato.data.otros_cargos);
                $("#txtOtrosTributosOC").val(dato.data.otros_tributos);
                $("#txtMontoRedondeoOC").val(dato.data.monto_redondeo);
                $("#txtImporteTotalOC").val(dato.data.importe_total);
 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}

function CargarDatosComprobanteBolOC(){
    var data = {
        "btnCargarDatosBoleta": true,
        "txtFiltroCliente": $("#txtFiltroClienteOC").val(),
        "txtFiltroPropiedad": ''
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                $("#txtRazonSocialOC").val(dato.razon_social);
                $("#txtDireccionOC").val(dato.direccion);
                $("#txtRucOC").val(dato.ruc);
                $("#txtLugarOC").val(dato.lugar);
                $("#txtSerieOC").val(dato.serie);
                
                $("#cbxTipoMonedaOC").val('USD');

                $("#txtNumeroControlBolOC").val(dato.num_bol);
                $("#txtSerieControlBolOC").val(dato.serie_bol); 
                
                $("#txtFechaVencimientoOC").val(dato.fecha);
                $("#txtFechaEmisionOC").val(dato.fecha);

                var variable = dato.val;

                if(variable=="1"){
                    $("#txtNroDocumentoOC").val(dato.data.documento);
                    $("#txtdatosOC").val(dato.data.datos);
                }    
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}


function ValidarCamposOC() {
    var flat = true;
  
        if ($("#txtCamCantidadOC").val() === "" || $("#txtCamCantidadOC").val() === null) {
            $("#txtCamCantidadOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar la cantidad del detalle a agregar.", "info");
            flat = false;
        } else if ($("#txtCamUnidadOC").val() === "" || $("#txtCamUnidadOC").val() === null) {
            $("#txtCamUnidadOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, se requiere de la UNIDAD para el detalle.", "info");
            flat = false;
        } else if ($("#txtCamDescripcionOC").val() === "" || $("#txtCamDescripcionOC").val() === null) {
            $("#txtCamDescripcionOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar la descripci\u00F3n/glosa del detalle.", "info");
            flat = false;
        }else if ($("#txtCamValorUnitOC").val() === "" || $("#txtCamValorUnitOC").val() === null) {
            $("#txtCamValorUnitOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el valor unitario del detalle.", "info");
            flat = false;
        } else if ($("#txtCamDescOC").val() === "" || $("#txtCamDescOC").val() === null) {
            $("#txtCamDescOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, si no existe descuento alguno para el detalle, ingresar el valor de cero (0).", "info");
            flat = false;
        }
    
    return flat;
}

function AgregarPagoOC(){

    if(ValidarCamposOC()){
        var data = {
            "btnAgregarPagoOC": true,
            "txtFiltroCliente": $("#txtNroDocOC").val(),
            "txtFiltroPropiedad": '',
            "txtUsuario": $("#txtUsuario").val(),
            "txtFechaEmision": $("#txtFechaEmisionOC").val(),
            "txtFiltroTipoComprobante": $("#txtFiltroTipoComprobanteOC").val(),
            "txtSerieControlFac": $("#txtSerieControlBolOC").val(),
            "txtNumeroControlFac": $("#txtNumeroControlBolOC").val(),
            "txtCamCantidadOC": $("#txtCamCantidadOC").val(),
            "txtCamUnidadOC": $("#txtCamUnidadOC").val(),
            "txtCamDescripcionOC": $("#txtCamDescripcionOC").val(),
            "txtCamValorUnitOC": $("#txtCamValorUnitOC").val(),
            "txtCamDescOC": $("#txtCamDescOC").val(),
            "txtdatosOC": $("#txtdatosOC").val(),
            "cbxInafectoOC": $("#cbxInafectoOC").val(),
            "txtfiltroConceptoVentaOC": $("#txtfiltroConceptoVentaOC").val(),
            "idpago": $("#txtIdReservaVal").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                //console.log(dato);
                if (dato.status == 'ok') { 
                    CargarItemsFacturacionBolOC();
                    CargarTotalesComprobanteOC();
                    CargarDatosComprobanteBolOC();
                } else {
                    mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
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


function NuevaEmisionOC(){

    //OCULTAR PANELES DE TIPO COMPROBANTE
    $("#PanelBoletaElectronicaOC").hide();
    $("#PanelFacturaElectronicaOC").hide();
    $("#PanelPagosReservas").hide();

    //OCULTAR CAMPOS FILTRO NUEVO CLIENTE
    $("#PanelBusquedaDocNew").hide();
    $("#PanelBusquedaDocReg").show();

    //BLOQUEAR Y RESTABLECER CAMPOS
    
    $("#cbxFiltroTipoDocumentoOC").val("");
    document.getElementById('cbxFiltroTipoDocumentoOC').selectedIndex = 0;
    $("#txtFiltroNroDocumentoOC").val("");
    $("#txtFiltroDatoClienteOC").val("");
    $("#txtFiltroDireccionOC").val("");
    $("#txtfiltroConceptoVentaOC").prop("disabled", true);
    document.getElementById('txtfiltroConceptoVentaOC').selectedIndex = 0;
    $("#txtfiltroMotivoConceptoOC").prop("disabled", true);
    $("#txtfiltroMotivoConceptoOC").val("");
    document.getElementById('txtfiltroMotivoConceptoOC').selectedIndex = 0;
    $("#txtFiltroTipoComprobanteOC").prop("disabled", true);
    document.getElementById('txtFiltroTipoComprobanteOC').selectedIndex = 0;

    $("#btnContinuarComprobanteOC").prop("disabled", true);
    $("#btnNuevaEmisionOC").prop("disabled", true);

    document.getElementById('cbxFiltroTipoBusqueda').selectedIndex = 1;

    $("#botonesAccionOC").show();
    $("#TablaComprobantesEmitidosOC").hide();
    $("#PanelBoletaElectronicaOC").hide();
    $("#PanelFacturaElectronicaOC").hide();

}

function BuscarDocumentoOC(){
    bloquearPantalla("Buscando Documento...");
    var data = {
        "btnBuscarDocumentoOC": true,
        "cbxTipoDocumento": $("#cbxTipoDocumentoOC").val(),
        "txtNroDocumento": $("#txtNroDocumentoOC").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {   
                var tipoDocumento = $("#cbxTipoDocumentoOC").val();
                var Documento = $("#txtNroDocumentoOC").val();  
                $("#cbxFiltroTipoDocumentoOC").val(tipoDocumento);
                $("#txtFiltroNroDocumentoOC").val(Documento);             
                $("#txtFiltroDatoClienteOC").val(dato.cliente);
                $("#txtFiltroDireccionOC").val(dato.direccion);
                $("#txtfiltroConceptoVentaOC").prop("disabled", false);
                $("#txtfiltroMotivoConceptoOC").prop("disabled", false);
                $("#txtFiltroTipoComprobanteOC").prop("disabled", false);

                $("#btnContinuarComprobanteOC").prop("disabled", false);
                $("#btnNuevaEmisionOC").prop("disabled", false);
                
                $("#cbxFiltroTipoDocumentoOC").prop("disabled", true);
                $("#txtFiltroNroDocumentoOC").prop("disabled", true);             
                $("#txtFiltroDatoClienteOC").prop("disabled", true);

                LlenarMotivosConceptos();
                
                if(tipoDocumento == "6"){
                    $("#txtFiltroTipoComprobanteOC").prop("disabled", false);
                }else{
                    $("#txtFiltroTipoComprobanteOC").prop("disabled", false);
                }
                
            } else {
                
                if(dato.status == "regular"){
                    
                    var tipoDocumento = $("#cbxTipoDocumentoOC").val();
                    var Documento = $("#txtNroDocumentoOC").val();  
                    $("#cbxFiltroTipoDocumentoOC").val(tipoDocumento);
                    $("#txtFiltroNroDocumentoOC").val(Documento);             
                    $("#txtFiltroDatoClienteOC").val("");
                    $("#txtFiltroDireccionOC").val("");
                    $("#txtfiltroConceptoVentaOC").prop("disabled", false);
                    $("#txtfiltroMotivoConceptoOC").prop("disabled", false);
                    $("#txtFiltroTipoComprobanteOC").prop("disabled", false);
    
                    $("#btnContinuarComprobanteOC").prop("disabled", false);
                    $("#btnNuevaEmisionOC").prop("disabled", false);
                    
                    $("#cbxFiltroTipoDocumentoOC").prop("disabled", false);
                    $("#txtFiltroNroDocumentoOC").prop("disabled", false);             
                    $("#txtFiltroDatoClienteOC").prop("disabled", false);

                    mensaje_alerta("\u00A1IMPORTANTE!", dato.data, "info");
                    
                    LlenarMotivosConceptos();
                    
                    if(tipoDocumento == "6"){
                        $("#txtFiltroTipoComprobanteOC").prop("disabled", false);
                    }else{
                        $("#txtFiltroTipoComprobanteOC").prop("disabled", false);
                    }
                    
                }else{
                    
                    $("#cbxFiltroTipoDocumentoOC").val("");
                    $("#txtFiltroNroDocumentoOC").val("");
                    $("#txtFiltroDatoClienteOC").val("");
                    $("#txtFiltroDireccionOC").val("");
    
                    $("#txtfiltroConceptoVentaOC").prop("disabled", true);
                    $("#txtfiltroMotivoConceptoOC").prop("disabled", true);
                    $("#txtFiltroTipoComprobanteOC").prop("disabled", true);
    
                    $("#btnContinuarComprobanteOC").prop("disabled", true);
                    $("#btnNuevaEmisionOC").prop("disabled", true);
                    
                    $("#cbxFiltroTipoDocumentoOC").prop("disabled", true);
                    $("#txtFiltroNroDocumentoOC").prop("disabled", true);             
                    $("#txtFiltroDatoClienteOC").prop("disabled", true);
    
                    mensaje_alerta("\u00A1IMPORTANTE!", dato.data, "info");
                    
                    $("#txtFiltroTipoComprobanteOC").prop("disabled", false);
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

function BuscarDocumentoExisteOC(){
    bloquearPantalla("Buscando Cliente...");
    var data = {
        "btnBuscarDocumentoExiste": true,
        "txtNroDocumento": $("#txtFiltroClienteOC").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {   
                var Documento = $("#txtFiltroClienteOC").val();  
                $("#cbxFiltroTipoDocumentoOC").val(dato.tipo_documento);
                $("#txtFiltroNroDocumentoOC").val(Documento);             
                $("#txtFiltroDatoClienteOC").val(dato.cliente);
                $("#txtFiltroDireccionOC").val(dato.direccion);
                $("#txtFiltroCorreoOC").val(dato.correo);
                $("#txtfiltroConceptoVentaOC").prop("disabled", false);
                $("#txtfiltroMotivoConceptoOC").prop("disabled", false);
                if(dato.tipo_documento=="6"){
                   $("#txtFiltroTipoComprobanteOC").prop("disabled", false);
                }

                $("#btnContinuarComprobanteOC").prop("disabled", false);
                $("#btnNuevaEmisionOC").prop("disabled", false);

                LlenarMotivosConceptos();
            } else {
                $("#cbxFiltroTipoDocumentoOC").val("");
                $("#txtFiltroNroDocumentoOC").val("");
                $("#txtFiltroDatoClienteOC").val("");
                $("#txtFiltroDireccionOC").val("");

                $("#txtfiltroConceptoVentaOC").prop("disabled", true);
                $("#txtfiltroMotivoConceptoOC").prop("disabled", true);
                $("#txtFiltroTipoComprobanteOC").prop("disabled", true);

                $("#btnContinuarComprobanteOC").prop("disabled", true);
                $("#btnNuevaEmisionOC").prop("disabled", true);

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

function LlenarMotivosConceptos(){
    document.getElementById('txtfiltroMotivoConceptoOC').selectedIndex = 0;
    var url = '../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php';
    var datos = {
        "btnListarMotivosConceptos": true,
        "idconcepto": $("#txtfiltroConceptoVentaOC").val()
    }
    llenarCombo(url, datos, "txtfiltroMotivoConceptoOC");
}



function ValidarCamposBoletaOC() {
    var flat = true;  
        if ($("#txtNroDocOC").val() === "" || $("#txtNroDocOC").val() === null) {
            $("#txtNroDocOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el Nro de Documento del Cliente.", "info");
            flat = false;
        } else if ($("#txtdatosOC").val() === "" || $("#txtdatosOC").val() === null) {
            $("#txtdatosOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar los apellidos y nombres del cliente.", "info");
            flat = false;
        } else if ($("#txtDireccionClienteOC").val() === "" || $("#txtDireccionClienteOC").val() === null) {
            $("#txtDireccionClienteOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la direcci\u00F3n del Cliente", "info");
            flat = false;
        }  
    return flat;
}


function EmitirBoletaOC(){
    if(ValidarCamposBoletaOC()){
        bloquearPantalla("Emitiendo Boleta Electronica...");
        var data = {
            "btnEmitirBoletaOC": true,
            "txtFiltroCliente": $("#txtNroDocOC").val(),
            "txtFiltroPropiedad": '0',
            "txtUsuario": $("#txtUsuario").val(),
            "txtFechaEmision": $("#txtFechaEmisionOC").val(),
            "txtFechaVencimiento": $("#txtFechaVencimientoOC").val(),
            "cbxTipoMoneda": $("#cbxTipoMonedaOC").val(),
            "cbxTipoDocumento": $("#cbxTipoDocOC").val(),
            "txtNroDocumento": $("#txtNroDocOC").val(),
            "txtdatos": $("#txtdatosOC").val(),
            "txtDireccionCliente": $("#txtDireccionClienteOC").val(),
            "txtCamCantidadOC": $("#txtCamCantidadOC").val(),
            "txtCamDescripcionOC": $("#txtCamDescripcionOC").val(),
            "txtCamValorUnitOC": $("#txtCamValorUnitOC").val(),
            "txtCamDescOC": $("#txtCamDescOC").val(),
            "txtSerieControlBolOC": $("#txtSerieControlBolOC").val(),
            "txtNumeroControlBolOC": $("#txtNumeroControlBolOC").val(),
            "txtfiltroConceptoVentaOC": $("#txtfiltroConceptoVentaOC").val(),
            "txtCorreoClienteOC": $("#txtCorreoClienteOC").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") { 
                    $("#TablaComprobantesEmitidosOC").show();
                    CargarComprobantesEmitidosOC(dato.serie, dato.numero, dato.fecha_emision);
                    CargarComprobantesImpresos();                
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");                    
                    $("#botonesAccionOC").hide();
					
					//InsertarVentas(dato.serie, dato.numero);
					
                } else {
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

function CargarComprobantesEmitidosOC(serie, numero, fechaEmision) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarTablaComprobantesEmitidosOC": true,
        "serie": serie,
        "numero": numero,
        "fechaEmision": fechaEmision
    };
    realizarJsonPost(url, dato, respuestaBuscarComprobantesEmitidosOC, null, 10000, null);
}

function respuestaBuscarComprobantesEmitidosOC(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaComprobantesEmitidosOC(dato.data);
}

var getTablaComprobantesEmitidosOC = null;
function LlenarTablaComprobantesEmitidosOC(datos) {
    if (getTablaComprobantesEmitidosOC) {
        getTablaComprobantesEmitidosOC.destroy();
        getTablaComprobantesEmitidosOC = null;
    }

    getTablaComprobantesEmitidosOC = $('#TablaComprobanteImpresoOC').DataTable({
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
            { "data": "fecha_emision" },
            { "data": "serie" },
            { "data": "numero" },            
            { "data": "datos" },
            { "data": "total" },
            {
                "data": "url_valor",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<a class="badge" href="'+row.url_valor+'" target="_blank" style="font-size: 14px; color red; text-align: center; font-weight: bold;"><i class="fas fa-file-pdf"></i> Ver</a>';
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






/* FACTURAS */ 

function CargarDatosComprobanteFacOC(){
    var data = {
        "btnCargarDatosFacturaOC": true,
        "txtFiltroCliente": $("#txtFiltroClienteOC").val(),
        "txtFiltroPropiedad": ''
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                $("#txtRazonSocialFacOC").val(dato.razon_social);
                $("#txtDireccionFacOC").val(dato.direccion);
                $("#txtRucFacOC").val(dato.ruc);
                $("#txtLugarFacOC").val(dato.lugar);
                $("#txtSerieFacOC").val(dato.seriefac);

                $("#txtFechaVencimientoFacOC").val(dato.fecha);
                $("#txtFechaEmisionFacOC").val(dato.fecha);
                //$("#txtRucFac").val(dato.data.documento);
                //$("#txtClienteFac").val(dato.data.datos);
                $("#cbxTipoMonedaFacOC").val('USD');
    
                $("#txtNumeroControlFacOC").val(dato.num_control);
                $("#txtSerieControlFacOC").val(dato.serie_control);
                
                $("#botonesAccionFacOC").show();


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}


function BuscarDocumentoFacOC(){
    bloquearPantalla("Buscando Documento...");
    var data = {
        "btnBuscarDocumentoFac": true,
        "txtRucFac": $("#txtRucClienteFacOC").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {                
               $("#txtClienteFacOC").val(dato.cliente);
               $("#txtDireccionClienteFacOC").val(dato.direccion);
            } else {
                $("#txtClienteFacOC").val("");
                $("#txtDireccionClienteFacOC").val("");
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



function CargarItemsFacturacionFacOC() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarItemsBoletaOC": true,
        "txtFiltroCliente": $("#txtRucClienteFacOC").val(),
        "txtUsuario": $("#txtUsuario").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarItemsFacturacionFacOC, null, 10000, null);
}

function respuestaBuscarItemsFacturacionFacOC(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaItemsFacturacionFacOC(dato.data);
    
}

var getTablaItemsFacturacionFacOC = null;
function LlenarTablaItemsFacturacionFacOC(datos) {
    if (getTablaItemsFacturacionFacOC) {
        getTablaItemsFacturacionFacOC.destroy();
        getTablaItemsFacturacionFacOC = null;
    }

    getTablaItemsFacturacionFacOC = $('#TablaPagoComprobanteFacOC').DataTable({
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
        columns: [{
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";                    
                    html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="EliminarItemFacOC(\'' + data + '\')" title="Eliminar item"><i class="fas fa-trash"></i></a>';
                    return html;
                }
            },
            { "data": "cantidad" },
            { "data": "medida" },            
            { "data": "descripcion" },
            { "data": "valor_unitario" },   
            { "data": "descuento" },
            { "data": "importe_venta" }
            
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


function EliminarItemFacOC(id){
    var data = {
        "btnEliminarItemFacturaOC": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {                
                console.log(dato);
                CargarItemsFacturacionFacOC();   
                LimpiarCamposTotalesFacOC(); 
                CargarTotalesComprobanteFacOC();
                
            } else {
                mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}


function LimpiarCamposTotalesFacOC(){
    $("#txtOpGravadaFacOC").val("0.00");
    $("#txtOpFacOC").val("0.00");
    $("#txtExoneradaFacOC").val("0.00");
    $("#txtOpInafectaFacOC").val("0.00");
    $("#txtIscFacOC").val("0.00");
    $("#txtIgvFacOC").val("0.00");
    $("#txtOtrosCargosFacOC").val("0.00");
    $("#txtOtrosTributosFacOC").val("0.00");
    $("#txtMontoRedondeoFacOC").val("0.00");
    $("#txtImporteTotalFacOC").val("0.00"); 
}




function ValidarCamposFacOC() {
    var flat = true;
  
        if ($("#txtCamCantidadFacOC").val() === "" || $("#txtCamCantidadFacOC").val() === null) {
            $("#txtCamCantidadFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar la cantidad del detalle a agregar.", "info");
            flat = false;
        } else if ($("#txtCamUnidadFacOC").val() === "" || $("#txtCamUnidadFacOC").val() === null) {
            $("#txtCamUnidadFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, se requiere de la UNIDAD para el detalle.", "info");
            flat = false;
        } else if ($("#txtCamDescripcionFacOC").val() === "" || $("#txtCamDescripcionFacOC").val() === null) {
            $("#txtCamDescripcionFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar la descripci\u00F3n/glosa del detalle.", "info");
            flat = false;
        }else if ($("#txtCamValorUnitFacOC").val() === "" || $("#txtCamValorUnitFacOC").val() === null) {
            $("#txtCamValorUnitFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el valor unitario del detalle.", "info");
            flat = false;
        } else if ($("#txtCamDescFacOC").val() === "" || $("#txtCamDescFacOC").val() === null) {
            $("#txtCamDescFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, si no existe descuento alguno para el detalle, ingresar el valor de cero (0).", "info");
            flat = false;
        }
    
    return flat;
}

function AgregarPagoFacOC(){

    if(ValidarCamposFacOC()){
        var data = {
            "btnAgregarPagoOC": true,
            "txtFiltroCliente": $("#txtRucClienteFacOC").val(),
            "txtFiltroPropiedad": '',
            "txtUsuario": $("#txtUsuario").val(),
            "txtFechaEmision": $("#txtFechaEmisionFacOC").val(),
            "txtFiltroTipoComprobante": $("#txtFiltroTipoComprobanteOC").val(),
            "txtSerieControlFac": $("#txtSerieControlFacOC").val(),
            "txtNumeroControlFac": $("#txtNumeroControlFacOC").val(),
            "txtCamCantidadOC": $("#txtCamCantidadFacOC").val(),
            "txtCamUnidadOC": $("#txtCamUnidadFacOC").val(),
            "txtCamDescripcionOC": $("#txtCamDescripcionFacOC").val(),
            "txtCamValorUnitOC": $("#txtCamValorUnitFacOC").val(),
            "txtCamDescOC": $("#txtCamDescFacOC").val(),
            "txtdatosOC": $("#txtClienteFacOC").val(),
            "cbxInafectoOC": $("#cbxInafectoFacOC").val(),
            "txtfiltroConceptoVentaOC": $("#txtfiltroConceptoVentaFacOC").val(),
            "idpago": $("#txtIdReservaValFac").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                //console.log(dato);
                if (dato.status == 'ok') { 
                    CargarItemsFacturacionFacOC();
                    CargarTotalesComprobanteFacOC();
                    CargarDatosComprobanteFacOC();
                } else {
                    mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
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

function CargarTotalesComprobanteFacOC(){
    var data = {
        "btnCargarTotalesComprobanteOC": true,
        "txtFiltroCliente": $("#txtRucClienteFacOC").val(),
        "txtUsuario": $("#txtUsuario").val(),
        "txtFechaEmision": $("#txtFechaEmisionFacOC").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                console.log(dato);
                $("#txtOpGravadaFacOC").val(dato.data.op_gravada);
                $("#txtOpFacOC").val(dato.data.op);
                $("#txtExoneradaFacOC").val(dato.data.exonerada);
                $("#txtOpInafectaFacOC").val(dato.data.op_inafecta);
                $("#txtIscFacOC").val(dato.data.isc);
                $("#txtIgvFacOC").val(dato.data.igv);
                $("#txtOtrosCargosFacOC").val(dato.data.otros_cargos);
                $("#txtOtrosTributosFacOC").val(dato.data.otros_tributos);
                $("#txtMontoRedondeoFacOC").val(dato.data.monto_redondeo);
                $("#txtImporteTotalFacOC").val(dato.data.importe_total);
 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}



function ValidarCamposFacturaOC() {
    var flat = true;  
        if ($("#txtRucClienteFacOC").val() === "" || $("#txtRucClienteFacOC").val() === null) {
            $("#txtRucClienteFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el Nro de Documento del Cliente.", "info");
            flat = false;
        } else if ($("#txtClienteFacOC").val() === "" || $("#txtClienteFacOC").val() === null) {
            $("#txtClienteFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar los apellidos y nombres del cliente.", "info");
            flat = false;
        } else if ($("#txtDireccionClienteFacOC").val() === "" || $("#txtDireccionClienteFacOC").val() === null) {
            $("#txtDireccionClienteFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar la direcci\u00F3n del Cliente", "info");
            flat = false;
        }  else if ($("#txtEmailFacOC").val() === "" || $("#txtEmailFacOC").val() === null) {
            $("#txtEmailFacOC").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el correo del Cliente, al que se le notificara el comprobante.", "info");
            flat = false;
        } 
    return flat;
}

function EmitirFacturaOC(){
    if(ValidarCamposFacturaOC()){
        bloquearPantalla("Emitiendo Factura Electronica...");
        var data = {
            "btnEmitirFacturaOC": true,
            "txtFiltroCliente": $("#txtRucClienteFacOC").val(),
            "txtFiltroPropiedad": '0',
            "txtUsuario": $("#txtUsuario").val(),
            "txtFechaEmision": $("#txtFechaEmisionFacOC").val(),
            "txtFechaVencimiento": $("#txtFechaVencimientoFacOC").val(),
            "cbxTipoMoneda": $("#cbxTipoMonedaFacOC").val(),
            "cbxTipoDocumento": $("#cbxTipoDocumentoOC").val(),
            "txtNroDocumento": $("#txtRucClienteFacOC").val(),
            "txtdatos": $("#txtClienteFacOC").val(),
            "txtDireccionCliente": $("#txtDireccionClienteFacOC").val(),
            "txtCamCantidadOC": $("#txtCamCantidadFacOC").val(),
            "txtCamDescripcionOC": $("#txtCamDescripcionFacOC").val(),
            "txtCamValorUnitOC": $("#txtCamValorUnitFacOC").val(),
            "txtCamDescOC": $("#txtCamDescFacOC").val(),
            "txtSerieControlBolOC": $("#txtSerieControlFacOC").val(),
            "txtNumeroControlBolOC": $("#txtNumeroControlFacOC").val(),
            "txtfiltroConceptoVentaOC": $("#txtfiltroConceptoVentaFacOC").val(),
            "txtCorreoClienteOC": $("#txtCorreoClienteFacOC").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") { 
                    $("#TablaComprobantesEmitidosFacOC").show();
                    CargarComprobantesEmitidosFacOC(dato.serie, dato.numero, dato.fecha_emision);
                    CargarComprobantesImpresos();                
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success");                    
                    $("#botonesAccionFacOC").hide();
					
					//InsertarVentas(dato.serie, dato.numero);
					
                } else {
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


function CargarComprobantesEmitidosFacOC(serie, numero, fechaEmision) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M07_Contabilidad/M07MD01_Facturacion/M07MD01_Procesos.php";
    var dato = {
        "btnListarTablaComprobantesEmitidosOC": true,
        "serie": serie,
        "numero": numero,
        "fechaEmision": fechaEmision
    };
    realizarJsonPost(url, dato, respuestaBuscarComprobantesEmitidosFacOC, null, 10000, null);
}

function respuestaBuscarComprobantesEmitidosFacOC(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaComprobantesEmitidosFacOC(dato.data);
}

var getTablaComprobantesEmitidosFacOC = null;
function LlenarTablaComprobantesEmitidosFacOC(datos) {
    if (getTablaComprobantesEmitidosFacOC) {
        getTablaComprobantesEmitidosFacOC.destroy();
        getTablaComprobantesEmitidosFacOC = null;
    }

    getTablaComprobantesEmitidosFacOC = $('#TablaComprobanteImpresoFacOC').DataTable({
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
            { "data": "fecha_emision" },
            { "data": "serie" },
            { "data": "numero" },            
            { "data": "datos" },
            { "data": "total" },
            {
                "data": "url_valor",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<a class="badge" href="'+row.url_valor+'" target="_blank" style="font-size: 14px; color red; text-align: center; font-weight: bold;"><i class="fas fa-file-pdf"></i> Ver</a>';
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













