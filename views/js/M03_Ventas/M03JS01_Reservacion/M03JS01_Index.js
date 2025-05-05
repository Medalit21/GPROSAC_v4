var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});

function Control() {
    //InicializarGenericoModal();
    Ninguno();
    
    ConfiguracionInicioClientes();

    /*$('#txtDocumentoCliente,#txtDocumentoFiltro').keypress(function() {
        SoloNumeros1_9();
    });*/
    $('#txtMontoReserva').keypress(function() {
        SoloNumeros_Punto();
    });
    /***************ACCION BOTONES CABECERA********** */
    $('#nuevo').click(function() {
        Nuevo();
        $('#txtDocumentoFiltro').val(null).trigger('change');
    });

    $('#cancelar').click(function() {
        Restablecer();
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

    $('#btnNuevoCliente').click(function() {
        abrirModalCliente();
    });
    $('#btnCancelarCliente').click(function() {
        $('#modalClientes').modal('hide');
    });

    $('#busqueda_avanzada').click(function() {
        MostrarLista();
        $('#txtDocumentoFiltro').val(null).trigger('change');
    });
    
    $('input.CurrencyInput').on('blur', function() {
        const value = this.value.replace(/,/g, '');
        this.value = parseFloat(value).toLocaleString('en-US', {
          style: 'decimal',
          maximumFractionDigits: 2,
          minimumFractionDigits: 2
        });
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
    });
    LLenarZona();

    $('#cbxZona').change(function() {
        $("#cbxManzana").val("");
        $("#cbxLote").val("");
        LLenarManzanas();
        $("#cbxManzana").prop("disabled", false);
        document.getElementById('cbxLote').selectedIndex = 0;
        $("#cbxLote").prop("disabled", true);
    });

    $('#cbxManzana').change(function() {
        $("#cbxLote").val("");
        LLenarLotes();
        document.getElementById('cbxLote').selectedIndex = 0;
        $("#cbxLote").prop("disabled", false);
    });

    $('#cbxLote').change(function() {
        BuscarDatoLote();
    });


    $('#btnBuscarCliente').click(function() {
        BuscarDatoCliente();
    });
    //InicializarAtributosTablaBusquedaReservacion();
    $('#btnBuscarRegistro').click(function() {
        BusacarReservacionPaginado();
        BusacarReservacionReporte();
    });
    $('#btnLimpiar').click(function() {
        LimpiarFiltro();
        document.getElementById('bxFiltroProyectoReserva').selectedIndex = 0;
        document.getElementById('bxFiltroZonaReserva').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaReserva').selectedIndex = 0;
        document.getElementById('bxFiltroLoteReserva').selectedIndex = 0;
        BusacarReservacionPaginado();
        BusacarReservacionReporte();
    });
    $('#cbxTipoCasaFiltro').change(function() {
        BusacarReservacionPaginado();
        BusacarReservacionReporte();
    });

    BuscarDiasReferenciaReserva();
    $('#txtDesdeReserva').change(function() {
        BuscarSumarDiasReferenciaReserva();
    });
    
    VerTipoCambio();
    $('#cbxTipoMonedaReserva').change(function() {
        VerTipoCambio();
    });


    
    $('#bxFiltroProyectoReserva').change(function () {
        $("#bxFiltroZonaReserva").val("");
        $("#bxFiltroManzanaReserva").val("");
        $("#bxFiltroLoteReserva").val("");
        var url = '../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php';
        var datos = {
            "ListarZonass": true,
            "idProyecto": $('#bxFiltroProyectoReserva').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaReserva");
        document.getElementById('bxFiltroManzanaReserva').selectedIndex = 0;
        document.getElementById('bxFiltroLoteReserva').selectedIndex = 0;
    });
    LLenarZonass();

    $('#bxFiltroZonaReserva').change(function () {
        $("#bxFiltroManzanaReserva").val("");
        $("#bxFiltroLoteReserva").val("");
        var url = '../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php';
        var datos = {
            "ListarManzanass": true,
            "idZona": $('#bxFiltroZonaReserva').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaReserva");
        document.getElementById('bxFiltroLoteReserva').selectedIndex = 0;
    });

    $('#bxFiltroManzanaReserva').change(function () {
        $("#bxFiltroLoteReserva").val("");
        var url = '../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php';
        var datos = {
            "ListarLotess": true,
            "idManzana": $('#bxFiltroManzanaReserva').val()
        }
        llenarCombo(url, datos, "bxFiltroLoteReserva");
    });

    $("#btnBuscarCli").click(function() {
        let ndoc=$("#txtDocumentoAdd").val();
        if (ndoc=="" || ndoc==null) {
            mensaje_alerta("Falta dato","Ingresar numero de documento","info");
            $("#txtDocumentoAdd").focus();
        } else {
            let tipodoc = $("#cbxTipoDocumentoAdd").val();
            if(tipodoc == '1'){
                ConsultaReniec();
            }
        }
        console.log('Num Doc: '+ndoc);
        
    });

    asignarLote();

}

function LLenarZonass() {
 
    var url = '../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php';
    var datos = {
        "ListarZonass": true,
        "idProyecto": $('#bxFiltroProyectoReserva').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaReserva");
}

function VerTipoCambio() {
   
    var data = {
      "btnMostrarTipoCambio": true,
      "__ID_USER": $('#__IDUSUARIO').val()
    };
    $.ajax({
      type: "POST",
      url: "../../models/M07_Contabilidad/M07MD02_ComprobanteSunat/M07MD02_Procesos.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        console.log(dato);
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

/****************************LIMPIAR FILTROS DE BUSQUEDA**************************** */
function LimpiarFiltro() {
    $("#txtDesdeFiltro,#txtHastaFiltro").val("");
    $('#txtDocumentoFiltro').val(null).trigger('change');
    document.getElementById('cbxTipoCasaFiltro').selectedIndex = 0;
}
/********************CONFIGURAR BOTONES************************* */
var Estados = { Ninguno: "Ninguno", Nuevo: "Nuevo", Modificar: "Modificar", Guardado: "Guardado", SoloLectura: "SoloLectura", Consulta: "Consulta" };
var Estado = Estados.Ninguno;

function BloquearTodo(valor_c) {
    $("#txtDocumentoCliente,#btnBuscarCliente,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#txtMontoReserva,#cbxTipoMonedaReserva,#cbxTipoCasa,#txtDesdeReserva,#txtHastaReserva,#txtDescripcion").prop("disabled", valor_c);
    $("#formularioRegistrarGeneralCliente").addClass("disabled-form");
    $("#formularioRegistrarGeneralLote").addClass("disabled-form");
    $("#formularioRegistrarReserva").addClass("disabled-form");
}

function HabilitarCampos(valor_c) {
    $("#txtDocumentoCliente,#btnBuscarCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#txtMontoReserva,#cbxTipoMonedaReserva,#txtDesdeReserva,#cbxTipoCasa,#txtHastaReserva,#txtDescripcion").prop("disabled", valor_c);
}

function HabilitarCamposModificar(valor_c) {
    $("#txtDocumentoCliente,#btnBuscarCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#txtMontoReserva,#cbxTipoMonedaReserva,#txtDesdeReserva,#txtHastaReserva,#txtDescripcion").prop("disabled", valor_c);
}

/***************************CONFIGURACION ESTADO BOTONES************************* */
function Ninguno() {
    var dato_cliente = $("#__ID_CLIENTE").val();
    if(dato_cliente == "ninguno" || dato_cliente == ""){
        Estado = Estados.Ninguno;
        $("#nuevo").prop('disabled', false);
        $("#modificar").prop('disabled', true);
        $("#cancelar").prop('disabled', true);
        $("#guardar").prop('disabled', true);
        $("#eliminar").prop('disabled', true);
        $("#adjuntos").prop('disabled', true);       
        BloquearTodo(true);
        LimpiarCamposRegistro();
        BusacarReservacionPaginado();
        BusacarReservacionReporte();        
        $("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
        $("#contenido_registro").hide();
        $("#contenido_lista").show();
    }else{
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
        BusacarReservacionPaginado();
        BusacarReservacionReporte();    
        $("#contenido_registro").show();
        $("#contenido_lista").hide();
        BuscarMontoReferenciaReserva();
        $("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
        VerIdCliente(dato_cliente);
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
    $("#formularioRegistrarGeneralCliente").removeClass("disabled-form");
    $("#formularioRegistrarGeneralLote").removeClass("disabled-form");
    $("#formularioRegistrarReserva").removeClass("disabled-form");
    $("#txtDocumentoCliente").focus();
    BuscarMontoReferenciaReserva();
}

function Modificar() {
    Estado = Estados.Modificar;
    $("#nuevo").prop('disabled', true);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', false);
    $("#guardar").prop('disabled', false);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', false);
    HabilitarCamposModificar(false);
    $("#formularioRegistrarGeneralCliente").removeClass("disabled-form");
    $("#formularioRegistrarGeneralLote").removeClass("disabled-form");
    $("#formularioRegistrarReserva").removeClass("disabled-form");
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
    BloquearTodo(true);
}

function MostrarLista() {
    //Restablecer();
    Estado = Estados.Ninguno;
    $("#nuevo").prop('disabled', false);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', true);
    $("#guardar").prop('disabled', true);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', true); 
    LimpiarCamposRegistro();
    BusacarReservacionPaginado();
    BusacarReservacionReporte();        
    $("#contenido_registro").hide();
    $("#contenido_lista").show();
}
/****************** LIMPIAR TODO LOS CAMPOS VISTA PRINCIPAL************************** */
function LimpiarCamposRegistro() {
    $("#__ID_RESERVACION,#__ID_CLIENTE,#txtDocumentoCliente,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente,#cbxProyecto,#cbxZona,#cbxManzana,#cbxLote,#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#txtMontoReserva,#cbxTipoMonedaReserva,#cbxTipoCasa,#txtDesdeReserva,#txtHastaReserva,#txtDescripcion").val("");
    document.getElementById('cbxProyecto').selectedIndex = 0;
    document.getElementById('cbxZona').selectedIndex = 0;
    document.getElementById('cbxManzana').selectedIndex = 0;
    document.getElementById('cbxLote').selectedIndex = 0;
    document.getElementById('cbxTipoMonedaReserva').selectedIndex = 0;
    document.getElementById('cbxTipoCasa').selectedIndex = 0;
}

function Restablecer() {    
    
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnRestablecer": true,
        "idUser": $("#__IDUSUARIO").val()
    };
    realizarJsonPost(url, dato, IdRestablece, null, 10000, null);
}

function IdRestablece(dato){
    if(dato.status=="ok"){
        $("#__ID_CLIENTE").val("ninguno");
        window.location.href = dato.ruta;
    }    
}


function VerIdCliente(id) {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
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
    }    
}


/***********************LLENA ZONAS ********************** */

function LLenarZona() {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var datos = {
        "ReturnZonas": true,
        "idProyecto": $('#cbxProyecto').val()
    }
    llenarCombo(url, datos, 'cbxZona');
}

/***********************LLENA MANZANA ********************** */

function LLenarManzanas() {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var datos = {
        "ReturnManzana": true,
        "idZona": $('#cbxZona').val()
    }
    llenarCombo(url, datos, 'cbxManzana');
}
/***********************LLENA LOTE ********************** */

function LLenarLotes() {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var datos = {
        "ReturnLote": true,
        "idManzana": $('#cbxManzana').val()
    }
    llenarCombo(url, datos, 'cbxLote');
}

/**************************BUSCAR CLIENTE************************** */
function BuscarDatoCliente() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnBuscarCliente": true,
        "tipoDocumento": $("#cbxTipoDocumento").val(),
        "documento": $("#txtDocumentoCliente").val()

    };
    realizarJsonPost(url, dato, RespuestaBuscarDatoCliente, null, 10000, null);
}

function RespuestaBuscarDatoCliente(dato) {
    $("#__ID_CLIENTE,#txtNombreCliente,#txtApellidoPaternoCliente,#txtApellidoMaternoCliente").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        $("#__ID_CLIENTE").val(dato.data.id);
        $("#txtNroDocumentoCliente").val(dato.data.documento);
        $("#txtNombreCliente").val(dato.data.nombres);
        $("#txtApellidoPaternoCliente").val(dato.data.apellidoPaterno);
        $("#txtApellidoMaternoCliente").val(dato.data.apellidoMaterno);
        
        $("#cbxMedioPago").val(dato.medio_pago);
        $("#cbxTipoComprobante").val(dato.tipo_constancia);
        
        $("#cbxZona").focus();
    } else {
        $("#txtDocumentoCliente").focus();
        //mensaje_cliente_no_encontrado("¡Verifique!", "No se encontró información para el Documento ingresado", IrRegistroCliente, SeguirBuscando, dato.urlRegistroCliente);
        mensaje_alerta("\u00A1ATENCI\u00D3N!", "No se encontr\u00F3 informaci\u00F3n para el Documento ingresado", "info");
    }
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
/**************************BUSCAR INFORMACION LOTE************************** */
function BuscarDatoLote() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnInfoLote": true,
        "idLote": $("#cbxLote").val()
    };
    realizarJsonPost(url, dato, RespuestaBuscarDatoLote, null, 10000, null);
}

function RespuestaBuscarDatoLote(dato) {
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxTipoCasa").val("");
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status == "ok") {
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        var precio =  $("#txtPrecioNegocio").val();
        if(precio==0){
            $("#txtPrecioNegocio").val(dato.data.valoLoteCasa);
        }
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);
        $("#txtMontoReserva").focus();
        $("#cbxTipoCasa").val(dato.data.tipoCasa);
    }
}

/**********************CONTROLAR BOTON GUARDAR************************ */
function Guardar() {
    if (Estado == Estados.Nuevo) {
        GuardarNuevo();
    } else if (Estado == Estados.Modificar) {
        GuardarActualizacion();
    } else {
        mensaje_alerta("\u00A1ADVERTENCIA!", "Ocurrio un problema en el registro, por favor, intente nuevamente.", "warning");
    }
}

function CargarVoucher(nombre){

     var file_data = $('#ficheroVoucher').prop('files')[0];   
     var form_data = new FormData();
     var dataa = nombre;   
     form_data.append('file', file_data);
     form_data.append('data', dataa);
     //alert(form_data);                             
     $.ajax({
         url: '../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_SubirArchivo.php', // point to server-side PHP script 
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


/***************************GUARDAR NUEVO PROYECTO****************************** */
function GuardarActualizacion() {
    
        bloquearPantalla("Guardando...");
        var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
        var dato = {
            "ReturnActualizarReservacion": true,
            "idReservacion": $("#__ID_RESERVACION").val().trim(),
            "idCliente": $("#__ID_CLIENTE").val().trim(),
            "IdUser": $("#__IDUSUARIO").val(),			
            "idLote": $("#cbxLote").val().trim(),
            "descripcion": $("#txtDescripcion").val(),
            "montoReservado": $("#txtMontoReserva").val(),
            "tipoMoneda": $("#cbxTipoMonedaReserva").val(),
            "fechaIni": $("#txtDesdeReserva").val(),
            "fechaFin": $("#txtHastaReserva").val(),
            "tipoCasa": $("#cbxTipoCasa").val(),
            "cbxTipoMonedaPrecio": $("#cbxTipoMonedaPrecio").val(),
            "txtPrecioNegocio": $("#txtPrecioNegocio").val(),
            "cbxVendedor": $("#cbxVendedor").val()
        };
        realizarJsonPost(url, dato, respuestaGuardarActualizacion, null, 10000, null);
    
}
/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarActualizacion(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        //Restablecer();
        //Ninguno();
        mensaje_alerta("\u00A1Reserva actualizada con \u00E9xito!", "", "success");
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

/***************************GUARDAR NUEVO PROYECTO****************************** */
function ValidarDatosNuevoRequeridos() {
    var flat = true;
    let date = new Date();
    let output = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');

    var fechaInicio = new Date($("#txtDesdeReserva").val());
    var fechaFin = new Date($("#txtHastaReserva").val());

    var difference = fechaFin - fechaInicio;
    var daysDifference = Math.floor(difference / 1000 / 60 / 60 / 24);
    daysDifference = parseInt(daysDifference);
  
        if ($("#txtNroDocumentoCliente").val() === "" || $("#txtNroDocumentoCliente").val() === null) {
            $("#txtDocumentoCliente").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el cliente al cual corresponde la reserva.", "info");
            flat = false;
            
        } else if ($("#cbxLote").val() === "" || $("#cbxLote").val() === null) {
            $("#cbxLote").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el lote a reservar. Recuerde que para visualizar los lotes debe seleccionar la zona y manzana respectivamente.", "info");
            flat = false;
            
        } else if ($("#cbxTipoMonedaReserva").val() === "" || $("#cbxTipoMonedaReserva").val() === null) {
            $("#cbxTipoMonedaReserva").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de moneda del pago de la reserva.", "info");
            flat = false;
            
        } else if ($("#txtTipoCambio").val() === "" || $("#txtTipoCambio").val() === null) {
            $("#txtTipoCambio").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el tipo de cambio referente al pago de la reserva.", "info");
            flat = false;
            
        } else if ($("#txtTipoCambio").val() <= 0 ) {
            $("#txtTipoCambio").focus();
            mensaje_alerta("\u00A1Error de Dato!", "Por favor, ingresar un tipo de cambio mayor a cero (0.00).", "info");
            flat = false;
            
        }else if ($("#txtMontoPagado").val() === "" || $("#txtMontoPagado").val() === null) {
            $("#txtMontoPagado").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el monto pagado de la reserva del lote.", "info");
            flat = false;
            
        }else if ($("#txtDesdeReserva").val() === "" || $("#txtDesdeReserva").val() === null) {
            $("#txtDesdeReserva").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar la fecha de inicio de la reserva.", "info");
            flat = false;
            
        }else if ($("#txtHastaReserva").val() === "" || $("#txtHastaReserva").val() === null) {
            $("#txtHastaReserva").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar la fecha de termino de la reserva.", "info");
            flat = false;
            
        }else if ($("#txtDesdeReserva").val() > $("#txtHastaReserva").val()) {
            $("#txtHastaReserva").focus();
            mensaje_alerta("\u00A1Error de Dato!", "", "La fecha de t\u00E9rmino de la reserva no puede ser menor a la de inicio.");
            flat = false;
            
        }else if ($("#txtDesdeReserva").val() < output) {
            $("#txtDesdeReserva").focus();
            mensaje_alerta("\u00A1Error de Dato!", "La fecha de inicio de la reserva no puede ser menor a la fecha actual.", "info");
            flat = false;
            
        }else if ($("#cbxTipoMonedaPrecio").val() === "" || $("#cbxTipoMonedaPrecio").val() === null) {
            $("#cbxTipoMonedaPrecio").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el tipo de moneda del precio de negociaci\u00F3n.", "info");
            flat = false;
            
        }else if ($("#txtPrecioNegocio").val() === "" || $("#txtPrecioNegocio").val() === null) {
            $("#txtPrecioNegocio").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingresar el precio negociado del lote.", "info");
            flat = false;
            
        }else if ($("#txtTotalPagado").val() === "" || $("#txtTotalPagado").val() === null) {
            $("#txtTotalPagado").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingresar el monto total emitido en el comprobante.", "info");
            flat = false;
             
        }else if ($("#cbxMedioPago").val() === "" || $("#cbxMedioPago").val() === null) {
            $("#cbxMedioPago").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el medio de pago de la reserva.", "info");
            flat = false;
            
        }else if ($("#cbxAgenciaBancaria").val() === "" || $("#cbxAgenciaBancaria").val() === null) {
            $("#cbxAgenciaBancaria").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar la agencia bancaria destino del pago de la reserva.", "info");
            flat = false;
            
        }else if ($("#txtNumeroOperacion").val() === "" || $("#txtNumeroOperacion").val() === null) {
            $("#txtNumeroOperacion").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese el n\u00FAmero de operacion de la reserva.", "info");
            flat = false;
            
        }else if ($("#ficheroVoucher").val() === "" || $("#ficheroVoucher").val() === null) {
            $("#ficheroVoucher").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, cargar el voucher del pago de la reserva.", "info");
            flat = false;
            
        }else if (daysDifference < 1) {
            $("#txtDesdeReserva").focus();
            mensaje_alerta("\u00A1Verifique!", "Por favor, La fecha Inicio debe ser menor a la Fecha Fin", "info");
            flat = false;
        }else if ($("#cbxVendedor").val() === "" || $("#cbxVendedor").val() === null) {
            $("#cbxVendedor").focus();
            mensaje_alerta("\u00A1Verifique!", "Por favor, seleccione el vendedor encargado.", "info");
            flat = false;
        }
    
    return flat;
}
function GuardarNuevo() {
    if (ValidarDatosNuevoRequeridos()) {
        bloquearPantalla("Guardando...");
        var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
        var dato = {
            "ReturnGuardarReservacion": true,
            "idCliente": $("#__ID_CLIENTE").val().trim(),
            "IdUser": $("#__IDUSUARIO").val(),
            "idLote": $("#cbxLote").val().trim(),
            "descripcion": $("#txtDescripcion").val(),
            "montoReservado": $("#txtMontoReserva").val(),
            "txtTipoCambio": $("#txtTipoCambio").val(),
            "txtMontoPagado": $("#txtMontoPagado").val(),
            "tipoMoneda": $("#cbxTipoMonedaReserva").val(),
            "fechaIni": $("#txtDesdeReserva").val(),
            "fechaFin": $("#txtHastaReserva").val(),
            "tipoCasa": $("#cbxTipoCasa").val(),
            "ficheroVoucher": $("#ficheroVoucher").val(),
            "cbxTipoMonedaPrecio": $("#cbxTipoMonedaPrecio").val(),
            "txtPrecioNegocio": $("#txtPrecioNegocio").val(),
            "cbxMedioPago": $("#cbxMedioPago").val(),
            "cbxTipoComprobante": $("#cbxTipoComprobante").val(),
            "cbxAgenciaBancaria": $("#cbxAgenciaBancaria").val(),
            "txtNumeroOperacion": $("#txtNumeroOperacion").val(),
            "cbxVendedor": $("#cbxVendedor").val()
        };
        realizarJsonPost(url, dato, respuestaGuardarNuevoRegistro, null, 10000, null);
    }
}
/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarNuevoRegistro(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        //console.log(dato);        
        CargarVoucher(dato.name);
        mensaje_alerta("\u00A1Reserva guardado con \u00E9xito!", "", "success");
        Restablecer();
        Ninguno();
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

/*****************************************LLENAR TABLA LISTA********************************************* */
var tablaBusqReservacionReport = null;

function BusacarReservacionReporte() {
    if (tablaBusqReservacionReport) {
        tablaBusqReservacionReport.destroy();
        tablaBusqReservacionReport = null;
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
            "url": "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnReservacionPag": true,
                    "documento": $("#txtDocumentoFiltro").val(),
                    "tipoCasa": $("#cbxTipoCasaFiltro").val(),
                    "desde": $("#txtDesdeFiltro").val(),
                    "hasta": $("#txtHastaFiltro").val(),
                    "bxFiltroProyectoReserva": $("#bxFiltroProyectoReserva").val(),
                    "bxFiltroZonaReserva": $("#bxFiltroZonaReserva").val(),
                    "bxFiltroManzanaReserva": $("#bxFiltroManzanaReserva").val(),
                    "bxFiltroLoteReserva": $("#bxFiltroLoteReserva").val()
                });
            }
        },
        "columns": [{
                "data": "existe_venta",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.existe_venta == '1'){
                        html = 'REGISTRADO';
                    }else{
                        if(row.existe_venta == '2'){
                            html = 'FINALIZADO';
                        }else{
                            html = 'PENDIENTE';                    
                        }                   
                    }
                    return html;
                } 
            },
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "area" },
            { "data": "modImportePrecio" },
            { "data": "importe_precio" },
            { "data": "inicioReserva" },
            { "data": "finReserva" },
            { "data": "modMontoReserva" },
            { "data": "montoReserva" },
            { "data": "descTipoCasa" },            
            { "data": "vendedor" },
            { "data": "registro" }
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

    tablaBusqReservacionReport = $('#tableRegistroReportes').DataTable(options);
}
/*****************************************LLENAR TABLA LISTA********************************************* */
var tablaBusqReservacionPag = null;

function BusacarReservacionPaginado() {
    if (tablaBusqReservacionPag) {
        tablaBusqReservacionPag.destroy();
        tablaBusqReservacionPag = null;
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
            "url": "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnReservacionPag": true,
                    "documento": $("#txtDocumentoFiltro").val(),
                    "tipoCasa": $("#cbxTipoCasaFiltro").val(),
                    "desde": $("#txtDesdeFiltro").val(),
                    "hasta": $("#txtHastaFiltro").val(),
                    "bxFiltroProyectoReserva": $("#bxFiltroProyectoReserva").val(),
                    "bxFiltroZonaReserva": $("#bxFiltroZonaReserva").val(),
                    "bxFiltroManzanaReserva": $("#bxFiltroManzanaReserva").val(),
                    "bxFiltroLoteReserva": $("#bxFiltroLoteReserva").val()
                });
            }
        },
        "columns": [{
                "data": "idReservacion",
                "render": function (data, type, row) {
                    var html = "";
                    if(row.existe_venta == '1'){
                        html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarReserva(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a> \  <a href="javascript:void(0)" class="btn btn-edit-action" onclick="IrCopropietarios(\'' + data + '\')" title="Copropietarios"><i class="fas fa-users"></i></a>';
                    }else{
                        if(row.existe_venta == '2'){
                            html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="IrCopropietarios(\'' + data + '\')" title="Copropietarios"><i class="fas fa-users"></i></a>';
                        }else{
                            html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarReserva(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a> \ <a href="javascript:void(0)" class="btn btn-edit-action" onclick="IrCopropietarios(\'' + data + '\')" title="Copropietarios"><i class="fas fa-users"></i></a> \  <a href="javascript:void(0)" class="btn btn-success-action" onclick="IrVenta(\'' + data + '\')" title="Vender"><i class="fas fa-dollar-sign"></i></a> \  <a href="javascript:void(0)" class="btn btn-delete-action" onclick="AnularReserva(\'' + data + '\')" title="Anular Reserva"><i class="fas fa-times"></i></a>';
                        }
                    }
                    return html;
                }
            },
            {
                "data": "existe_venta",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.existe_venta == '1'){
                        html = '<span class="badge etiqueta-js" style="background-color: #0A77EB;"> REGISTRADO </span>';
                    }else{
                        if(row.existe_venta == '2'){
                            html = '<span class="badge etiqueta-js" style="background-color: #0EBF00;"> FINALIZADO </span>';
                        }else{
                             html = '<span class="badge etiqueta-js" style="background-color: #FFA342; "> PENDIENTE </span>';                    
                        }                   
                    }
                    return html;
                } 
            },
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "area" },
            { "data": "modImportePrecio" },
            { "data": "importe_precio" },
            { "data": "inicioReserva" },
            { "data": "finReserva" },
            { "data": "modMontoReserva" },
            { "data": "montoReserva" },
            { "data": "descTipoCasa" },
            { 
                "data": "voucher",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.voucher==""){
                        html="Ninguno";
                    }else{
                        html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="VerVoucher(\'' + row.idReservacion + '\')"><i class="fas fa-file-pdf"></i> Ver</a>';
                    }
                    return html;
                }
            },
            { "data": "vendedor" },
            { "data": "registro" }
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

    tablaBusqReservacionPag = $('#tableRegistroReservacion').DataTable(options);
}

function VerVoucher(id) {  
    var data = {
      btnMostrarVoucher: true,
      idRegistro: id,
    };
    $.ajax({
      type: "POST",
      url: "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            if(dato.formato == "jpeg"){
                var html = "";
                var documento = "../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+dato.voucher+"";
                html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                $("#my_img_doc").html(html);
                $("#modalVerVoucher").modal("show");   
            }else{
                var html = "";
                var documento = "../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+dato.voucher+"";
                html += "<object class='pdfview' type='application/pdf' data='" +documento +"' style='width: 100%'></object> ";
                $("#my_img_doc").html(html);
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
 
 
function AnularReserva(id){
    mensaje_condicionalDOS("\u00BFEst\u00E1 seguro que desea anular la Reserva seleccionada?", "Al confirmar se proceder\u00E1 a anular la Reserva y se liberar\u00E1 el lote asignado.", ConfirmarAnularReserva, id);
}
 
 function ConfirmarAnularReserva(id){
    var data = {
      btnAnularReserva: true,
      idRegistro: id,
    };
    $.ajax({
      type: "POST",
      url: "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            mensaje_alerta("\u00A1Anulado!", dato.data, "success");
        }else{
            mensaje_alerta("\u00A1Error!", dato.data, "info");
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    }); 
 }

function IrVenta(id) {  
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnIrVenta": true,
        "idRegistro": id,
        "idUser": $("#__IDUSUARIO").val()
    };
    realizarJsonPost(url, dato, Venta, null, 10000, null);    
}

function Venta(dato){
    if(dato.status=="ok"){
        window.location.href = dato.ruta;
    } 
}


function InicializarAtributosTablaBusquedaReservacion() {
    $('#tableRegistroReservacion').on('key-focus.dt', function(e, datatable, cell) {
        tablaBusqReservacionPag.row(cell.index().row).select();
        var data = tablaBusqReservacionPag.row(cell.index().row).data();
        Consulta();
        ReflejarInformacionSelccionadaReservacion(data);
    });

    $('#tableRegistroReservacion').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = tablaBusqReservacionPag.cell(this).index().row;
        tablaBusqReservacionPag.row(rowIdx).select();
    });
}

/***************************Reflejar Seleccion a Editar****************************** */
function EditarReserva(id) {
    
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnEditarReserva": true,
        "idReservacion": id
    };
    realizarJsonPost(url, dato, ReflejarInformacionSelccionadaReservacion, null, 10000, null);
}

function ReflejarInformacionSelccionadaReservacion(data) {

    Consulta();
    LimpiarCamposRegistro();
    $("#__ID_RESERVACION").val(data.data.idReservacion);
    $("#__ID_CLIENTE").val(data.data.idCliente);
	$("#__IDUSUARIO").val(data.data.IdUser);
    $("#cbxTipoDocumento").val(data.data.tipoDocumento);
    $("#txtNroDocumentoCliente").val(data.data.documento);
    $("#txtNombreCliente").val(data.data.nombres);
    $("#txtApellidoPaternoCliente").val(data.data.apellidoPaterno);
    $("#txtApellidoMaternoCliente").val(data.data.apellidoMaterno);
    $("#cbxProyecto").val(data.data.idProyecto);
    LLenarZonaId(data.data.idProyecto, data.data.idZona);
    LLenarManzanaId(data.data.idZona, data.data.idManzana);
    LLenarLoteId(data.data.idManzana, data.data.idLote);
    $("#txtArea").val(data.data.area);
    $("#txtTipoMonedaLote").val(data.data.siglaMoneda);
    $("#txtValorLoteCasa").val(data.data.valorLoteCasa);
    $("#txtValorLoteSolo").val(data.data.valorLoteSolo);
    $("#txtMontoReserva").val(data.data.montoReserva);
    $("#txtMontoPagado").val(data.data.montoReserva);
    $("#cbxTipoMonedaReserva").val(data.data.tipoMonedaReserva);
    $("#cbxTipoMonedaPrecio").val(data.data.tipoMonedaReserva);
    $("#cbxTipoCasa").val(data.data.tipoCasa);
    $("#txtDesdeReserva").val(data.data.inicioReservaCadena);
    $("#txtHastaReserva").val(data.data.finReservaCadena);
    $("#txtDescripcion").val(data.data.descripcion);
    $("#cbxTipoMonedaPrecio").val(data.data.monedaPrecio);
    $("#txtPrecioNegocio").val(data.data.precioNegociado);
    $("#cbxVendedor").val(data.data.id_vendedor); 
};


/***************************Reflejar Copropietarios****************************** */
function IrCopropietarios(id) {
    //$('#modalCopropietarios').modal('show');
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "btnMostrarClienteReserva": true,
        "idReservacion": id
    };
    realizarJsonPost(url, dato, ReflejarCopropietarios, null, 10000, null);
    
}

function ReflejarCopropietarios(data) {

    if(data.status=="ok"){
        $("#txtidReserva").val(data.data.id);
        $("#txtNroDocC").val(data.data.documento);
        $("#txtApellidoNombreC").val(data.data.datos);
        $("#txtTelefonoC").val(data.data.celular); 
        $("#txtCorreoC").val(data.data.correo); 
        
        CargarTablaCopropietarios(data.data.id);
        
        $('#modalCopropietarios').modal('show');
        
    }
    
};

var tablaBusqReservacionPag2 = null;
function CargarTablaCopropietarios(id) {
    if (tablaBusqReservacionPag2) {
        tablaBusqReservacionPag2.destroy();
        tablaBusqReservacionPag2 = null;
    }
    var options = $.extend(true, {}, defaults, {
        "aoColumnDefs": [{
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
            "url": "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "btnCargarListaReservas": true,
                    "idReservacion": id
                });
            }
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="EliminarCopropietario(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                }
            },
            { "data": "datos" },
            { "data": "datos" },
            { "data": "celular" },
            { "data": "correo" },
            { "data": "adjunto",
               "render": function(data, type, row, host) {
                    var html = "";
                    if (row.adjunto == "") {
                        html = 'ninguno';
                    } else {
                        html = '<a href="'+"../../M03_Ventas/M03SM02_Venta/archivos/"+row.adjunto+'" download="'+row.documento+"_"+row.datos+'">documento.pdf</a>';                        
                    }
                    return html;
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

    tablaBusqReservacionPag2 = $('#TablaCopropietarios').DataTable(options);
}



/********************LLENAR ZONA SELECIONANDO****************/

function LLenarZonaId(idProyecto, idZona) {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var datos = {
        "ReturnZonas": true,
        "idProyecto": idProyecto
    }
    llenarComboSelecionar(url, datos, "cbxZona", idZona);
}

/********************LLENAR MANZANA SELECIONANDO****************/

function LLenarManzanaId(idZona, idManzana) {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var datos = {
        "ReturnManzana": true,
        "idZona": idZona
    }
    llenarComboSelecionar(url, datos, "cbxManzana", idManzana);
}
/********************LLENAR LOTE SELECIONANDO****************/

function LLenarLoteId(idManzana, idLote) {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var datos = {
        "ReturnLoteActualizable": true,
        "idManzana": idManzana,
        "idLote": idLote
    }
    llenarComboSelecionar(url, datos, "cbxLote", idLote);
}

/************************************ELIMINAR REGISTRO CLIENTES******************************** */
function Eliminar() {
    mensaje_condicionalUNO("¿Est\u00E1 seguro de eliminar?", "Al confirmar se proceder\u00E1 a eliminar el registro selecionado", ConfirmarEliminarRegistroReservacion, CancelEliminar, "");
}

function CancelEliminar() {
    return;
}

function ConfirmarEliminarRegistroReservacion(id) {
    bloquearPantalla("Eliminando...");
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnEliminarReservacion": true,
        "idReservacion": $("#__ID_RESERVACION").val()
    };
    realizarJsonPost(url, dato, RespuestaConfirmarEliminarRegistroReservacion, null, 10000, null);
}

function RespuestaConfirmarEliminarRegistroReservacion(dato) {
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


/**************************BUSCAR MONTO REFERENCIAL RESERVA************************** */
function BuscarMontoReferenciaReserva() {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnMontoReservaReferencial": true

    };
    realizarJsonPost(url, dato, RespuestaBuscarMontoReferenciaReserva, null, 10000, null);
}

function RespuestaBuscarMontoReferenciaReserva(dato) {
    if (dato.status == "ok") {
        $("#txtMontoReserva").val(dato.data.valor);
        $("#txtMontoPagado").val(dato.data.valor);
        $("#txtDesdeReserva").val(dato.fecha);
        document.getElementById('cbxTipoMonedaReserva').selectedIndex = 2;
        document.getElementById('cbxTipoMonedaPrecio').selectedIndex = 1;
        $("#txtDescripcion").val("REGISTRO DE RESERVA");
        BuscarSumarDiasReferenciaReserva();
    }
}

/**************************BUSCAR DÍAS REFERENCIAL RESERVA************************** */
function BuscarDiasReferenciaReserva() {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnDiasReservaReferencial": true

    };
    realizarJsonPost(url, dato, RespuestaBuscarDiasReferenciaReserva, null, 10000, null);
}

function RespuestaBuscarDiasReferenciaReserva(dato) {
    if (dato.status == "ok") {
        $("#___DIAS_RESERVA_REFERENCIAL").val(dato.data.valor);
    }
}

/**************************SUMAR DÍAS REFERENCIAL RESERVA************************** */
function BuscarSumarDiasReferenciaReserva() {
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnSumarFechaReferencial": true,
        "desde": $("#txtDesdeReserva").val(),
        "dias": $("#___DIAS_RESERVA_REFERENCIAL").val()
    };
    realizarJsonPost(url, dato, RespuestaBuscarSumarDiasReferenciaReserva, null, 10000, null);
}

function RespuestaBuscarSumarDiasReferenciaReserva(dato) {
    if (dato.status == "ok") {
        $("#txtHastaReserva").val(dato.data.fecha);
    }
}

/**************************BUSCAR INFORMACION LOTE SEGMENTADO************************** */
function BuscarInformacionSegmentadaLoteParaVender() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD01_Reservacion/M03MD01_Reservacion_Proceso.php";
    var dato = {
        "ReturnLoteSegmentado": true,
        "idLote": $("#__ID_LOTE_RESERVAR").val()
    };
    realizarJsonPost(url, dato, RespuestaBuscarInformacionSegmentadaLoteParaVender, null, 10000, null);
}

function RespuestaBuscarInformacionSegmentadaLoteParaVender(dato) {
    $("#txtArea,#txtTipoMonedaLote,#txtValorLoteCasa,#txtValorLoteSolo,#cbxTipoCasa").val("");
    desbloquearPantalla();
    if (dato.status == "ok") {
        LLenarZonaId(dato.data.idProyecto, dato.data.idZona);
        LLenarManzanaId(dato.data.idZona, dato.data.idManzana);
        LLenarLoteId(dato.data.idManzana, dato.data.idLote);
        $("#txtArea").val(dato.data.area);
        $("#txtTipoMonedaLote").val(dato.data.moneda);
        $("#txtValorLoteCasa").val(dato.data.valoLoteCasa);
        $("#txtValorLoteSolo").val(dato.data.valorLoteSolo);
        $("#cbxTipoCasa").val(dato.data.tipoCasa);
    }
}

// Modificaciones para obtener información de inventario de lote
const paramsLote = new URLSearchParams(window.location.search);


function obtenerLoteDesdeReserva(loteId) {
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
        console.log("No viene desde Reserva Id")
    } else {

        LLenarZonaId(pro, zona);
        LLenarManzanaId(zona, mz);
        LLenarLoteId(mz, lt);
        obtenerLoteDesdeReserva(lt);
        
    }
    
}

function asignarLote() {

    if (paramsLote.has('Proyecto')) {
        Nuevo();
        obtenerLote();
    } else {
        console.log("No viene desde Reserva Id")
        
    }
}

/*================== CLIENTES =====================*/

function abrirModalCliente(data) {
    $('#modalClientes').modal('show');    
}

function ValidarPorTipoDocumento() {
    var Cadena = $("#cbxTipoDocumentoAdd :selected").text();
    if (Cadena.trim() === "DNI") {
        $('#txtDocumentoAdd').attr('maxlength', 8);
        $("#txtDocumentoAdd").val("");
    } else if (Cadena.trim() === "RUC") {
        $('#txtDocumentoAdd').attr('maxlength', 11);
        $("#txtDocumentoAdd").val("");
    } else {
        $('#txtDocumentoAdd').attr('maxlength', 15);
        $("#txtDocumentoAdd").val("");
    }
}

function ConfiguracionInicioClientes() {
    
    $("#cbxPaisEmisorDocumento option:contains('Perú')").attr('selected', true);
    $("#cbxNacionalidad option:contains('Perú')").attr('selected', true);
    $("#cbxTipoDocumentoAdd option:contains('DNI')").attr('selected', true);

    $('#cbxTipoDocumentoAdd').on('change', function() {
        $("#cbxTipoDocumentoHtml").hide();
        ValidarPorTipoDocumento();
    });
    $('#cbxPaisEmisorDocumento').on('change', function() {
        $("#cbxPaisEmisorDocumentoHtml").hide();
    });
    $('#cbxNacionalidad').on('change', function() {
        $("#cbxNacionalidadHtml").hide();
    });
    $('#cbxSexo').on('change', function() {
        $("#cbxSexoHtml").hide();
    });
    $('#txtFechaNacimineto').on('change', function() {
        $("#txtFechaNaciminetoHtml").hide();
    });
    $('#cbxDepartamentoDir').on('change', function() {
        $("#cbxDepartamentoDirHtml").hide();
    });
    $('#cbxProvinciaDir').on('change', function() {
        $("#cbxProvinciaDirHtml").hide();
    });
    $('#cbxDistritoDir').on('change', function() {
        $("#cbxDistritoDirHtml").hide();
    });

    $('#txtDocumentoAdd').keydown(function() {
        $("#txtDocumentoHtml").hide();
    });
    $('#txtApellidoPaterno').keydown(function() {
        $("#txtApellidoPaternoHtml").hide();
    });
    $('#txtApellidoMaterno').keydown(function() {
        $("#txtApellidoMaternoHtml").hide();
    });
    $('#txtNombres').keydown(function() {
        $("#txtNombresHtml").hide();
    });
    $('#txtCelular').keydown(function() {
        $("#txtCelularHtml").hide();
    });
    $('#txtCorreo').keydown(function() {
        $("#txtCorreoHtml").hide();
    });
    $('#txtDocumentoAdd,#txtCelular2,#txtTelefono,#txtCelular').keypress(function() {
        SoloNumeros1_9();
    });
    $('#txtApellidoPaterno,#txtApellidoMaterno,#txtNombres').keypress(function() {
        SoloLetras();
    });
}



/********************CONSULTA RENIEC************************* */
function ConsultaReniec(){

    bloquearPantalla("Consultando...");
    var url = "../../models/generales/mdl_apis.php";
    var dato = {
        "btnSeleccionReniec": true,
        "NroDocumento":  $("#txtDocumentoAdd").val()
    };
    realizarJsonPost(url, dato, respuestaSeleccionReniec, null, 10000, null);

}

function respuestaSeleccionReniec(dato){
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status == "ok") {                        
             
        $("#txtApellidoPaterno").val(dato.apellido_pat);
        $("#txtApellidoMaterno").val(dato.apellido_mat);
        $("#txtNombres").val(dato.nombres);

    } else{
        mensaje_alerta("SIN RESULTADOS!","No se encontraron registros con el nro de documento ingresado","info");
        
        $("#txtApellidoPaterno").val("");
        $("#txtApellidoMaterno").val("");
        $("#txtNombres").val("");
    }
}