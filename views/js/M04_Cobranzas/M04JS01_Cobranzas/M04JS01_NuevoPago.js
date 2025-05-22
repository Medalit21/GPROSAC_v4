var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    LlenarProyectos();
    LLenarZonas();
    VerificarPerfil();
    
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


   
  //BuscarActividadesGenerados();
  //InicializarAtributosTablaBusquedaRelacionada();

  $('#btnNuevoPago').click(function() {
     IrPagos();
  });
  
    $('#btnNewPago').click(function() {
     Nuevo();
  });


  $('#btnPagosRealizados').click(function() {
     IrPagosRealizados();
  });

  
    $('#bxListaLote').change(function() {
        LlenarCuotas();
    });
    
    $('#bxNroCuotas').change(function() {
        CargarDatosAdicionales();
    });
    
   VerTipoCambio();
    $('#bxTipoMoneda').change(function() {
        ValidarTipoMoneda();
        VerTipoCambio();
    });

  $('#btnPagar').click(function() {
	  	if (ValidarCamposNuevoPago()) {
			RegistrarPago();
		}
  });

  //FORMATEAR MILES EN INPUT DE MONEDA
    /*$("#txtMontoPagado").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });*/
    
    $('#btnBuscar').click(function() {
        BuscarCliente();

    });  

    $('#btnLimpiar').click(function() {
        //$("#txtFiltroDocumentoEC").val("");
        $('#txtFiltroDocumentoEC').val(null).trigger('change');
        document.getElementById('bxFiltroProyectoEC').selectedIndex = 0;
        document.getElementById('bxFiltroZonaEC').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaEC').selectedIndex = 0;
        document.getElementById('bxFiltroLoteEC').selectedIndex = 0;
		document.getElementById('bxFiltroEstadoEC').selectedIndex = 0;
		$("#txtDocCliente").val("");
		$("#txtNomCliente").val("");
		$("#txtApePaternoCliente").val("");
		$("#txtApeMaternoCliente").val("");
		$("#txtTipoDocCliente").val("");
		$("#txtNacionalidadCliente").val("");
		
    });
    
    $('#bxFiltroProyectoEC').change(function () {
        $("#bxFiltroZonaEC").val("");
        $("#bxFiltroManzanaEC").val("");
        var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
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
        var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaEC').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaEC");
        document.getElementById('bxFiltroLoteEC').selectedIndex = 0;
    });

    $('#bxFiltroManzanaEC').change(function () {
        $("#bxFiltroLoteEC").val("");
        var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaEC').val()
        };
        llenarCombo(url, datos, "bxFiltroLoteEC");
    });
    
    
    $('#txtMontoPagado').keyup(delayTime(function (e) {  
        CalcularTotalPagado();
    }, 1000));  

  
}

function VerTipoCambio() {  
    var data = {
      "btnMostrarTipoCambio": true,
      "__ID_USER": $('#txtUSR').val()
    };
    $.ajax({
      type: "POST",
      url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            $('#txtTipoCambio').val(dato.tipoCambio);
        }else{
            mensaje_alerta("\u00A1IMPORTANTE!", "Ingresar tipo de cambio del d\u00EDa.", "info");
            setTimeout(function() {
               window.location.href = ""+ dato.url +"";
            }, 1500);
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
 }

function CalcularTotalPagado(){
    
    var tipoMoneda = $("#bxTipoMoneda").val();
    var tipoCambio = $("#txtTipoCambio").val();
    var montoPagado = $("#txtMontoPagado").val();
    var totalPagado = 0;
    
    if(tipoMoneda=="15380"){
        totalPagado = (montoPagado / tipoCambio);
        totalPagado = totalPagado.toFixed(2);
    }else{
        if(tipoMoneda=="15381"){
            totalPagado = montoPagado.toFixed(2);
        }
    }
    
    $("#txtTotalPagado").val(totalPagado);
    
}

function VerificarPerfil(){
     var data = {
        "VerificarPerfil": true,
        "txtUSR": $("#txtUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                
                if(dato.perfil=="6"){
                    $("#PanelFiltros").hide();
                    $("#PanelBotons").hide();
                    CargarDatosCliente(dato.documento, '');
                    LlenarLotes(dato.documento, '');
                }else{
                    $("#PanelFiltros").show();
                    $("#PanelBotons").show();
                }           

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}


function Nuevo(){
    document.getElementById('txtFiltroDocumentoEC').selectedIndex = 0;
    document.getElementById('bxFiltroProyectoEC').selectedIndex = 0;
    document.getElementById('bxFiltroZonaEC').selectedIndex = 0;
    document.getElementById('bxFiltroManzanaEC').selectedIndex = 0;
    document.getElementById('bxFiltroLoteEC').selectedIndex = 0;

    document.getElementById('bxListaLote').selectedIndex = 0;
    document.getElementById('bxNroCuotas').selectedIndex = 0;
    document.getElementById('bxMedioPago').selectedIndex = 0;
    document.getElementById('bxTipoComprobante').selectedIndex = 0;
    document.getElementById('bxAgenciaBancaria').selectedIndex = 0;
    document.getElementById('bxTipoMoneda').selectedIndex = 0;
    document.getElementById('bxFlujoCaja').selectedIndex = 0;
    $("#txtDocCliente").val("");
    $("#txtNomCliente").val("");
    $("#txtApePaternoCliente").val("");
    $("#txtApeMaternoCliente").val("");
	
    $("#txtTipoCambio").val("");
    $("#txtMontoPagado").val("");
    $("#txtFechaPago").val("");
    $("#txtNumeroOperacion").val("");
    $("#file").val("");
    $("#txtidVenta").val("");
    $("#bxNroCuotas").val("");
    $("#txtTotalPagado").val("");
    $("#txtMontoPagar").val("");

}


function LlenarProyectos() {
    var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoEC");    
}

function LLenarZonas() {
    var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoEC').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaEC");
}

function BuscarCliente(){

    var documento = $('#txtFiltroDocumentoEC').val();
    var lote = $('#bxFiltroLoteEC').val();

    LlenarLotes(documento, lote);
    CargarDatosCliente(documento, lote);
}


function LlenarLotes(documento, lote){
    document.getElementById('bxListaLote').selectedIndex = 0;
    var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php';
    var datos = {
        "btnListarLotes": true,
        "txtFiltroDocumentoEC": documento,
        "idlote": lote
    }
    llenarCombo(url, datos, "bxListaLote");  
}

function CargarDatosCliente(documento, lote) {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = { 
        "btnCargarDatosCliente": true,
        "documento": documento,
        "idlote": lote
        
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD01_CargarDatosCliente.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {                  

                var resultado = dato.data;
                $("#txtTipoDocCliente").val(resultado.tp);
                $("#txtNacionalidadCliente").val(resultado.nac);
                $("#txtDocCliente").val(resultado.doc);
                $("#txtNomCliente").val(resultado.nom);
                $("#txtApePaternoCliente").val(resultado.ap);
                $("#txtApeMaternoCliente").val(resultado.am);
                $("#txtidVenta").val(resultado.idventa); 

            }else{
                mensaje_alerta("\u00A1Error en el Proceso!", dato.data, "info");
            }
            
        },
            error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}


function LlenarCuotas(){
    document.getElementById('bxNroCuotas').selectedIndex = 0;
    var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php';
    var datos = {
        "btnCuotas": true,
        "idLote": $('#bxListaLote').val(),
        "documento": $('#txtFiltroDocumentoEC').val()
    }
    llenarCombo(url, datos, "bxNroCuotas");  
    //CargarDatosAdicionales();
}

function CargarDatosAdicionales(){
     var data = {
        "btnCargarDatosAdicionales": true,
        "idLote": $('#bxListaLote').val(),
        "idCuota": $('#bxNroCuotas').val(),
        "documento": $('#txtFiltroDocumentoEC').val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            console.log(dato);
            if (dato.status == "ok") {                
                 var resultado = dato.data;
                $("#txtTipoMod1").val(resultado.tm);
                $("#txtidVenta").val(resultado.id);
                
                $("#txtMontoPagar").val(dato.monto);
                $("#txtTotalPagado").val(dato.monto);
                $("#txtMontoPagado").val(dato.monto);
                $("#bxTipoMoneda").val(dato.moneda);
                $("#bxMedioPago").val(dato.medio_pago);
                $("#bxTipoComprobante").val(dato.tipo_comprobante);
                $("#bxAgenciaBancaria").val(dato.agencia_bancaria);
                $("#txtFechaPago").val(dato.fecha_pago);
                
            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function ValidarTipoMoneda(){

 bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ValidarTipoMoneda": true,
        "TipoMoneda": $("#bxTipoMoneda").val()

    };
    realizarJsonPost(url, dato, RespuestaValidarTipoMoneda, null, 10000, null);
}

function RespuestaValidarTipoMoneda(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {

        if(dato.data.nombre == "SOLES"){
            $("#txtTipoCambio").prop("disabled", false);
            //$("#txtTipoCambio").val(dato.tipo_cambio);
            $("#txtTipoCambio").focus();
        }else{
            $("#txtTipoCambio").prop("disabled", true);
            //$("#txtTipoCambio").val("0.00");
            $("#txtTipoCambio").focus();
        }

    }else{
        $("#txtTipoCambio").prop("disabled", true);
            $("#txtTipoCambio").val("0.00");
    }
}


function VerificarTipoMoneda(){
     var data = {
        "btnConsultarTipoMoneda": true,
        "idTipoMoneda": $('#bxTipoMoneda').val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {                
                 var resultado = dato.data;                
                $("#txtTipoMod2").val(resultado.simbolo);
                $("#txtTipoMod2").val(resultado.tipo_cambio);    

            }else{

                var resultado = dato.data;                
                $("#txtTipoMod2").val(resultado.simbolo);

            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

//validar
function ValidarCamposNuevoPago() {
    var flat = true;
    if ($("#txtDocCliente").val() === "") {
        $("#txtDocCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el cliente", "info");
        $("#txtDocumentoHtml").html('(Requerido)');
        $("#txtDocumentoHtml").show();
        flat = false;
    } else if ($("#txtNomCliente").val() === "") {
        $("#txtNomCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese los nombres", "info");
        $("#txtApellidoPaternoHtml").html('(Requerido)');
        $("#txtApellidoPaternoHtml").show();
        flat = false;
    } else if ($("#txtApePaternoCliente").val() === "") {
        $("#txtApePaternoCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Apellido Paterno", "info");
        $("#txtApellidoMaternoHtml").html('(Requerido)');
        $("#txtApellidoMaternoHtml").show();
        flat = false;
    } else if ($("#txtApeMaternoCliente").val() === "") {
        $("#txtApeMaternoCliente").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Apellido Materno.", "info");
        $("#txtNombresHtml").html('(Requerido)');
        $("#txtNombresHtml").show();
        flat = false;
    } else if ($("#bxListaLote").val() === "" || $("#bxListaLote").val() === null) {
        $("#bxListaLote").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la propiedad.", "info");
        $("#cbxNacionalidadHtml").html('(Requerido)');
        $("#cbxNacionalidadHtml").show();
        flat = false;
    } else if ($("#bxNroCuotas").val() === "" || $("#bxNroCuotas").val() === null) {
        $("#bxNroCuotas").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el nro. de cuota.", "info");
        $("#cbxSexoHtml").html('(Requerido)');
        $("#cbxSexoHtml").show();
        flat = false;
    }  else if ($("#bxMedioPago").val() === "" || $("#bxMedioPago").val() === null) {
        $("#bxMedioPago").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el medio de pago.", "info");
        $("#cbxSexoHtml").html('(Requerido)');
        $("#cbxSexoHtml").show();
        flat = false;
    } else if ($("#bxTipoComprobante").val() === "" || $("#bxTipoComprobante").val() === null) {
        $("#bxTipoComprobante").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el tipo de constancia.", "info");
        $("#cbxSexoHtml").html('(Requerido)');
        $("#cbxSexoHtml").show();
        flat = false;
    } else if ($("#txtMontoPagar").val() === "") {
        $("#txtMontoPagar").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Importe a pagar.", "info");
        $("#txtCelularHtml").html('(Requerido)');
        $("#txtCelularHtml").show();
        flat = false;
    } else if ($("#bxTipoMoneda").val() === "" || $("#bxTipoMoneda").val() === null) {
        $("#bxTipoMoneda").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Tipo de moneda.", "info");
        $("#cbxDepartamentoDirHtml").html('(Requerido)');
        $("#cbxDepartamentoDirHtml").show();
        flat = false;
    } else if ($("#txtTipoCambio").val() === "") {
        $("#txtTipoCambio").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Tipo de cambio", "info");
        $("#txtApellidoPaternoHtml").html('(Requerido)');
        $("#txtApellidoPaternoHtml").show();
        flat = false;
    } else if ($("#txtMontoPagado").val() === "") {
        $("#txtMontoPagado").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Importe pagado.", "info");
        $("#txtApellidoMaternoHtml").html('(Requerido)');
        $("#txtApellidoMaternoHtml").show();
        flat = false;
    } else if ($("#txtTotalPagado").val() === "") {
        $("#txtTotalPagado").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Total pagado.", "info");
        $("#txtNombresHtml").html('(Requerido)');
        $("#txtNombresHtml").show();
        flat = false;
    } else if ($("#bxAgenciaBancaria").val() === "" || $("#bxAgenciaBancaria").val() === null) {
        $("#bxAgenciaBancaria").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la Agencia bancaria.", "info");
        $("#cbxProvinciaDirHtml").html('(Requerido)');
        $("#cbxProvinciaDirHtml").show();
        flat = false;
    } else if ($("#bxFlujoCaja").val() === "" || $("#bxFlujoCaja").val() === null) {
        $("#bxFlujoCaja").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Flujo de Caja.", "info");
        $("#cbxDistritoDirHtml").html('(Requerido)');
        $("#cbxDistritoDirHtml").show();
        flat = false;
    } else if ($("#txtFechaPago").val() === "") {
        $("#txtFechaPago").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la Fecha de pago.", "info");
        $("#txtFechaNaciminetoHtml").html('(Requerido)');
        $("#txtFechaNaciminetoHtml").show();
        flat = false;
    } else if ($("#txtNumeroOperacion").val() === "") {
        $("#txtNumeroOperacion").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Nro de Operación.", "info");
        $("#txtApellidoMaternoHtml").html('(Requerido)');
        $("#txtApellidoMaternoHtml").show();
        flat = false;
    }
	
    return flat;
}


function RegistrarPago(){
     var data = {
        "btnRegistrarPago": true,
        "txtidVenta": $('#txtidVenta').val(),
        "bxNroCuotas": $('#bxNroCuotas').val(),
        "bxTipoMoneda": $('#bxTipoMoneda').val(),
        "txtTipoCambio": $('#txtTipoCambio').val(),
        "txtMontoPagado": $('#txtMontoPagado').val(),
        "bxMedioPago": $('#bxMedioPago').val(),
        "bxTipoComprobante": $('#bxTipoComprobante').val(),
        "bxAgenciaBancaria": $('#bxAgenciaBancaria').val(),
        "txtNumeroOperacion": $('#txtNumeroOperacion').val(),
        "txtFechaPago": $('#txtFechaPago').val(),
        "constancia": $('#constancia').val(),
        "txtMontoPagar": $('#txtMontoPagar').val(),
        "bxFlujoCaja": $('#bxFlujoCaja').val(),
        "txtUSR": $('#txtUSR').val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") { 
                    console.log(dato);  
                     var nombre = dato.nombre;
                     EnviarAdjunto(nombre);
                     LlenarCuotas();
                     Nuevo();
                     mensaje_alerta("\u00A1Correcto!", dato.data, "success");      
                return;
            } else {
                mensaje_alerta("\u00A1ATENCI\u00D3N!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function EnviarAdjunto(nombre){

   var file_data = $('#constancia').prop('files')[0];   
    var form_data = new FormData();  
    var dataa = nombre;                  
    form_data.append('file', file_data);
    form_data.append('data', dataa);
    //alert(form_data);                             
    $.ajax({
        url: '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_SubirArchivo.php', // point to server-side PHP script 
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


function IrPagos(){
     var data = {
        "btnIrPagos": true,
        "txtUSR": $('#txtUSR').val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                window.location.replace(dato.URL);                
            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function IrPagosRealizados(){
     var data = {
        "btnIrPagosRealizados": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                window.location.replace(dato.URL);                
            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function ListarVentas() {

    var options = $.extend(true, {}, defaults, {
        "order": [
            [1, "asc"]
        ],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0],
            "targets": [1],
            "visible": false
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
            "url": "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                });
            }
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)"  onclick="AbrirModalProyecto(\'' + data + '\')"><img src="../../../images/editar.png" width="25px" height="25px" ></a> \ <a href="javascript:void(0)"  onclick="EliminarProyecto(\'' + data + '\')"><img src="../../../images/eliminar.png" width="25px" height="25px" ></a> ';
                }
            },
            {
                "data": "lote"
            },
            {
                "data": "lote"
            },
            {
                "data": "area"
            },
            {
                "data": "tipocasa"
            },
            {
                "data": "tipomoneda"
            },
            {
                "data": "precioventa"
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

    tablaEmpresas = $('#TablaVentas').DataTable(options);
}

function SeleccionarProyecto(id) {
    //$('#modalEditarFalladosDatosLaborales').modal('show');
    //LimpiarCamposEditarError();
    bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionarRegistro": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {

                $('#BusquedaRegistro').hide();
                $('#NuevoRegistro').hide();
                $('#ControlRegistro').show();

                var resultado = dato.data;
                $("#txtidProyectoc").val(resultado.id);
                $("#txtNombrec").val(resultado.nombre);
                $("#txtCodigoc").val(resultado.codigo);
                $("#txtResponsablec").val(resultado.responsable);
                $("#txtAreac").val(resultado.area);
                $("#txtNroZonasc").val(resultado.nro_zonas);
                $("#txtDireccionc").val(resultado.direccion);
                $("#cbxDepartamentoDirc").val(dato.departamento);
                $("#cbxProvinciaDirc").val(resultado.provincia);
                $("#cbxDistritoDirc").val(resultado.distrito);

                $("#txtidProyectoZonac").val(resultado.id);
                $("#txtNomProyectoZonac").val(resultado.nombre);
                $("#txtAreaProyectoZonac").val(resultado.area);
                $("#txtNroZonasProyectoZonac").val(resultado.nro_zonas);

                $("#txtidProyectozc").val(resultado.id);
                $("#txtNomProyectozc").val(resultado.nombre);
                $("#txtAreaProyectozc").val(resultado.area);
                $("#txtNroZonasProyectozc").val(resultado.nro_zonas);

                $("#txtidProyectozltc").val(resultado.id);
                $("#txtNomProyectozltc").val(resultado.nombre);
                $("#txtAreaProyectozltc").val(resultado.area);
                $("#txtNroZonasProyectozltc").val(resultado.nro_zonas);

                
                return;
            }
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function AbrirModalProyecto(id) {
  //$('#modalProyecto').modal('show');
   bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionarRegistro": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                 var resultado = dato.data;
                $("#txtidProyectoZona").val(resultado.id);
                $("#txtNombrecc").val(resultado.nombre);
                $("#txtCodigocc").val(resultado.codigo);
                $("#txtResponsablecc").val(resultado.responsable);
                $("#txtAreacc").val(resultado.area);
                $("#txtNroZonascc").val(resultado.nro_zonas);
                $("#txtDireccioncc").val(resultado.direccion);
                $("#cbxDepartamentoDircc").val(dato.departamento);
                $("#cbxProvinciaDircc").val(resultado.provincia);
                $("#cbxDistritoDircc").val(resultado.distrito);               

                $('#modalProyecto').modal('show');

                BuscarZonasPopup(resultado.id);
                LlenarZonasPopup(resultado.id);                              

                return;
            } else {
                mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });         
}

function ListarVentasReporte() {
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
            "url": "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                });
            }
        },
        "columns": [{
                "data": "id"
            },
            {
                "data": "lote"
            },
            {
                "data": "lote"
            },
            {
                "data": "area"
            },
            {
                "data": "tipocasa"
            },
            {
                "data": "tipomoneda"
            },
            {
                "data": "precioventa"
            }
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
            }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }

    });

    tablaEmpresas = $('#TablaVentasReporte').DataTable(options);
}

function BuscarActividadesGenerados() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnListaActividades": true
    };
    realizarJsonPost(url, dato, respuestaBuscarActividadesGenerados, null, 10000, null);
}

function respuestaBuscarActividadesGenerados(dato) {
    desbloquearPantalla();
    console.log(dato);
    LlenarTabalaActividadesGenerados(dato.data);
}

var getTablaBusquedaCabGenerado = null;

function LlenarTabalaActividadesGenerados(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaVentas').DataTable({
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
                "render": function(data, type, row) {
                    return '<button class="btn btn-edit-action"   onclick="MostrarDatos(\'' + data + '\')"><i class="fas fa-check-square"></i></button>';
                }
            },
            {"data": "lote"},
            {"data": "area"},
            {"data": "tipocasa"},
            {"data": "tipomoneda"},
            {"data": "precioventa"}
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

function InicializarAtributosTablaBusquedaRelacionada() {
    $('#TablaVentas').on('key-focus.dt', function(e, datatable, cell) {
        getTablaBusquedaCabGenerado.row(cell.index().row).select();
        var data = getTablaBusquedaCabGenerado.row(cell.index().row).data();
        ReflejarInformacionSelccionadaReservaRelacionada(data);
    });

    $('#TablaVentas').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = getTablaBusquedaCabGenerado.cell(this).index().row;
        getTablaBusquedaCabGenerado.row(rowIdx).select();
    });
}

function ReflejarInformacionSelccionadaReservaRelacionada(dato) {
    //$("#__ID_RESERVA").val(dato.idReservacion);
    $("#cbxProyecto").val(dato.idProyecto);
    LLenarZonaId(dato.idProyecto, dato.idZona);
    LLenarManzanaId(dato.idZona, dato.idManzana);
    LLenarLoteId(dato.idManzana, dato.idLote);
    $("#txtArea").val(dato.area);
    $("#txtTipoMonedaLote").val(dato.tipomoneda);
    $("#txtValorLoteCasa").val(dato.valorLoteCasa);
    $("#txtValorLoteSolo").val(dato.valorLoteSolo);
    
}

function LlenarResponsables(){
    document.getElementById('bxTipoActividad').selectedIndex = 0;
    var url = '../../models/M01_Actividades/M01MD01_Actividades/M01MD03_ListarTiposActividad.php';
    var datos = {
        "btnListarResponsables": true
    }
    llenarCombo(url, datos, "bxResponsable");  
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







