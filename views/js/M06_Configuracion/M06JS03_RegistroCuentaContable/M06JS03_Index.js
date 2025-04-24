$(document).ready(function() {
    Control();
});

function Control() {
    /***************ACCION BOTONES CABECERA********** */
	Ninguno();
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
        EliminarCliente();
    });

    $('#busqueda_avanzada').click(function() {
        MostrarLista();
    });
    
    //InicializarAtributosTablaBusquedaPersonal();
	
/*    $('#btnNuevoRegistro').click(function() {
        AbrirModalRegistroNuevo();
    });

    ConfiguracionInicioAcciones();

    $('#btnGuardarNuevo').click(function() {
        VerificarGuardarNuevoRegistro();
    });
    $('#txtCuentContFiltro').keypress(function() {
        SoloNumeros1_9();
    });*/
	
    $('#txtDescripCortaFiltro').keypress(function() {
        SoloLetras();
    });
    $('#btnBuscar').click(function() {
        $('#tableReportCuentCont').DataTable().ajax.reload();
        $('#tablaDatosCuentCont').DataTable().ajax.reload();
    });
    $('#btnTodos').click(function() {
        LimpiarFiltro();
        $('#tableReportCuentCont').DataTable().ajax.reload();
        $('#tablaDatosCuentCont').DataTable().ajax.reload();
    });

    /*****************GUARDAR REGITROS ACTUALIZADOS***************** */
    /*   $('#btnGuardarActulizado').click(function() {
           GuardarActualizarRegistro();
       });*/

    ConfiguracionInfoRequeridosctualizar();
}

/********************CONFIGURAR BOTONES************************* */
var Estados = { Ninguno: "Ninguno", Nuevo: "Nuevo", Modificar: "Modificar", Guardado: "Guardado", SoloLectura: "SoloLectura", Consulta: "Consulta" };
var Estado = Estados.Ninguno;


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
    $("#formularioRegistrarDatos").addClass("disabled-form");
    LimpiarDatosPersonales();
    CargarGrillaBusquedaPersonalListaPaginado();
    CargarGrillaBusquedaCuentContReportes();
    $("#contenido_lista").show();
    $("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
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
    LimpiarDatosPersonales();
    $("#contenido_registro").show();
    $("#contenido_lista").hide();
    $("#formularioRegistrarDatos").removeClass("disabled-form");
    $("#txtDocumento").focus();
}

function Modificar() {
    Estado = Estados.Modificar;
    $("#nuevo").prop('disabled', true);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', false);
    $("#guardar").prop('disabled', false);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', false);
    $("#formularioRegistrarDatos").removeClass("disabled-form");
    $("#txtApellidos").focus();
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
}

function MostrarLista() {
    Ninguno();
}

function LimpiarFiltro() {
    $("#txtCuentContFiltro").val("");
    $("#txtDescripCortaFiltro").val("");
}

function AbrirModalRegistroNuevo() {
    $('#modalRegistrar').modal('show');
    LimpiarDatosPersonales();
}

function LimpiarDatosPersonales() {
    //$("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
    //$("#txtDocumento").val("");
    $("#txtDescripCorta").val("");
    $("#txtDescripLarga").val("");
    $("#txtCuentaContableUSD").val("");
    $("#txtCuentaContablePEN").val("");

    $('[href="#DatosPersonalesss"]').tab('show');
}

/**************************Configuracion Inicio Acciones *************************** */
function ConfiguracionInicioAcciones() {

    /*$('#txtDocumento').keydown(function() {
        $("#txtDocumentoHtml").hide();
    });*/
	
    $('#txtDescripCorta').keydown(function() {
        $("#txtDescripCortaHtml").hide();
    });
    $('#txtDescripLarga').keydown(function() {
        $("#txtDescripLargaHtml").hide();
    });
    $('#txtCuentaContableUSD').keydown(function() {
        $("#txtCuentaContableUSDHtml").hide();
    });
	$('#txtCuentaContablePEN').keydown(function() {
        $("#txtCuentaContablePENHtml").hide();
    });
    $('#txtCuentaContableUSD, #txtCuentaContablePEN').keypress(function() {
        SoloNumeros1_9();
    });
    $('#txtDescripCorta,#txtDescripLarga').keypress(function() {
        SoloLetras();
    });
}

/*****************************************LLENAR TABLA REPORTE********************************************* */
var tablaBusqCuentContReport = null;

function CargarGrillaBusquedaCuentContReportes() {
    if (tablaBusqCuentContReport) {
        tablaBusqCuentContReport.destroy();
        tablaBusqCuentContReport = null;
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
        "paging": false,
        "info": false,
        "bSort": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 20, 50, 100, 150],
            [10, 20, 50, 100, 150] // change per page values here
        ],
        "pageLength": 1000000000, // default record count per page,
        "ajax": {
            "url": "../../models/M06_Configuracion/M06MD03_RegistroCuentaContable/M06MD03_RegistroCuentaContable.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnCuentContListaPaginada": true,
                    "txtDescripFiltro": $("#txtDescripCortaFiltro").val()
					//"txtCuentaConFiltro": $("#txtCuentContFiltro").val()
                });
            }
        },
        "columns": [
        //    { "data": "codigo_item" },
            { "data": "nombre_corto" },
            { "data": "texto2" },
            { "data": "texto3" }
            
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

    tablaBusqCuentContReport = $('#tableReportCuentCont').DataTable(options);
}

/*****************************************LLENAR TABLA LISTA********************************************* */
var tablaBusqCuentCont = null;

function CargarGrillaBusquedaPersonalListaPaginado() {
    if (tablaBusqCuentCont) {
        tablaBusqCuentCont.destroy();
        tablaBusqCuentCont = null;
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
            "url": "../../models/M06_Configuracion/M06MD03_RegistroCuentaContable/M06MD03_RegistroCuentaContable.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnCuentContListaPaginada": true,
                    "txtDescripCortaFiltro": $("#txtDescripCortaFiltro").val()
                });
            }
        },
        "columns": [
			{
                "data": "idconfig_detalle",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="AbrirModalRegistroActualizarCuent(\'' + data + '\')"><i class="fas fa-pencil-alt"></i></a>';
                }
            },
            { "data": "nombre_corto" },
            { "data": "texto2" },
            { "data": "texto3" }
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

    tablaBusqCuentCont = $('#tablaDatosCuentCont').DataTable(options);
}

function InicializarAtributosTablaBusquedaPersonal() {
    $('#tablaDatosCuentCont').on('key-focus.dt', function(e, datatable, cell) {
        tablaBusqCuentCont.row(cell.index().row).select();
        var data = tablaBusqCuentCont.row(cell.index().row).data();
        Consulta();
        ReflejarInformacionSelccionadaReservacion(data);
    });

    $('#tablaDatosCuentCont').on('tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = tablaBusqCuentCont.cell(this).index().row;
        tablaBusqCuentCont.row(rowIdx).select();
    });
}

function ReflejarInformacionSelccionadaReservacion(dato) {
    AbrirModalRegistroActualizarCuent(dato.id);
   // console.log(dato);
}



function VerificarCorreoValido(id) {
    var flat = true;
    var email = ValidarEmail($("#" + id).val());
    if ($("#" + id).val().trim().length > 0) {
        if (!email) {
            flat = false;
        }
    }
    return flat;
}

/*function ValidarCaracteresDocumento() {
    var flat = true;
    var Cadena = $("#cbxTipoDocumento :selected").text();
    if (Cadena.trim() === "DNI" && $("#txtDocumento").val().length != 8) {
        flat = false;
    } else if (Cadena.trim() === "RUC" && $("#txtDocumento").val().length != 11) {
        flat = false;
    }
    return flat;
}*/

/**********************CONTROLAR BOTON GUARDAR************************ */
function Guardar() {
    if (Estado == Estados.Nuevo) {
        VerificarGuardarNuevoRegistro();
    } else if (Estado == Estados.Modificar) {
        GuardarActualizarRegistro();
    } else {
        mensaje_alerta("\u00A1ADVERTENCIA!", "Ocurrio un problema en el registro, por favor, intente nuevamente.", "warning");
    }
}


/***************************VALIDAR DATOS REQUERIDOS****************************** */
function ValidarDatosNuevoRequeridos() {
    var flat = true;
    if ($("#txtDescripCorta").val() === "") {
        $("#txtDescripCorta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la descripción corta", "info");
        $("#txtDescripCortaHtml").html('(Requerido)');
        $("#txtDescripCortaHtml").show();
        flat = false;
    } else if ($("#txtDescripLarga").val() === "") {
        $("#txtDescripLarga").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la descripción larga.", "info");
        $("#txtDescripLargaHtml").html('(Requerido)');
        $("#txtDescripLargaHtml").show();
        flat = false;
    }else if ($("#txtCuentaContableUSD").val() === "") {
        $("#txtCuentaContableUSD").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la cuenta contable.", "info");
        $("#txtCuentaContableUSDHtml").html('(Requerido)');
        $("#txtCuentaContableUSDHtml").show();
        flat = false;
    }
    return flat;
}
/***************************GUARDAR NUEVO PERSONAL****************************** */
function GuardarNuevoRegistro() {
    bloquearPantalla("Guardando...");
    var url = "../../models/M06_Configuracion/M06MD03_RegistroCuentaContable/M06MD03_RegistroCuentaContable.php";
    var dato = {
        "ReturnGuardarRegCuent": true,
        "txtDescripCorta": $("#txtDescripCorta").val(),
        "txtDescripLarga": $("#txtDescripLarga").val(),
		"txtCuentaContableUSD": $("#txtCuentaContableUSD").val(),
		"txtCuentaContablePEN": $("#txtCuentaContablePEN").val(),
		"cbxEstado": $("#cbxEstado").val(),
		"txtempresa": $("#txtempresa").val(),
		"txtcodigotabla": $("#txtcodigotabla").val()
    };
    realizarJsonPost(url, dato, respuestaGuardarNuevoRegistro, null, 10000, null);
}
/*********************RESPUESTA GUARDAR NUEVO PERSONAL*********************** */
function respuestaGuardarNuevoRegistro(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        RegistrarControl("SUBMOD-REGISTRO CLIENTES", AccionControl.Registrar);
        Ninguno();
        mensaje_alerta("\u00A1Guardado!", dato.data, "success");
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}
/****************************VERIFICAR EXISTENCIA DEL REGISTRO****************** */
function VerificarGuardarNuevoRegistro() {
    if (ValidarDatosNuevoRequeridos()) {
        bloquearPantalla("Buscando...");
        var url = "../../models/M06_Configuracion/M06MD03_RegistroCuentaContable/M06MD03_RegistroCuentaContable.php";
        var dato = {
            "ReturnVerificaExixtencia": true,
			"DescrCort": $("#txtDescripCorta").val().trim(),
			"DescrLarg": $("#txtDescripLarga").val().trim(),
			"CuentaContableUSD": $("#txtCuentaContableUSD").val().trim(),
			"CuentaContablePEN": $("#txtCuentaContablePEN").val().trim(),
			"estado": $("#cbxEstado").val().trim()
			/*"empresa": $("#txtempresa").val().trim(),
			"codigotabla": $("#txtcodigotabla").val().trim()*/
        };
        realizarJsonPost(url, dato, respuestaVerificarGuardarNuevoRegistro, null, 10000, null);
    }
}

function respuestaVerificarGuardarNuevoRegistro(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        if (parseInt(dato.data.esta_borrado) === 0) {
            mensaje_alerta("\u00A1ADVERTENCIA!", "Ya existe un registro relacionado al DOCUMENTO ingresado" + "\n Pertenece a: " + dato.data.apellidoPaterno + " " + dato.data.apellidoMaterno + ", " + dato.data.nombres, "warning");
            return;
        }
        if (parseInt(dato.data.esta_borrado) === 1) {
            mensaje_alerta("\u00A1ADVERTENCIA!", "Ya existe un registro con este Documento, puede habilitarlo desde REGISTROS EN BAJA" + "\n Pertenece a: " + dato.data.apellidoPaterno + " " + dato.data.apellidoMaterno + ", " + dato.data.nombres, "warning");
            return;
        }
    } else {
        GuardarNuevoRegistro();
    }
}

/****************VALIDAR CAMPOS TIPO DOCUMENTOS ACTUALIZAR****************** */
/*function ValidarPorTipoDocumentoctualizar() {
    var Cadena = $("#cbxTipoDocumento :selected").text();
    if (Cadena.trim() === "DNI") {
        $('#txtDocumento').attr('maxlength', 8);
        $("#txtDocumento").val("");
    } else if (Cadena.trim() === "RUC") {
        $('#txtDocumento').attr('maxlength', 11);
        $("#txtDocumento").val("");
    } else {
        $('#txtDocumento').attr('maxlength', 15);
        $("#txtDocumento").val("");
    }
}*/
/**************************VALIDAR DATOS REQUERIDOS *************************** */
function ConfiguracionInfoRequeridosctualizar() {
    $('#txtDescripCorta').keydown(function() {
        $("#txtDescripCortaHtml").hide();
    });
    $('#txtDescripLarga').keydown(function() {
        $("#txtDescripLargaHtml").hide();
    });
    $('#txtCuentaContableUSD').keydown(function() {
        $("#txtCuentaContableUSDHtml").hide();
    });
	$('#txtCuentaContablePEN').keydown(function() {
        $("#txtCuentaContablePENHtml").hide();
    });
    $('#txtDescripCorta,#txtDescripLarga').keypress(function() {
        SoloLetras();
    });
	$('#txtCuentaContableUSD, #txtCuentaContablePEN').keypress(function() {
        SoloNumeros1_9();
    });
}

function AbrirModalRegistroActualizarCuent(id) { 

    bloquearPantalla("Buscando...");
    var url = "../../models/M06_Configuracion/M06MD03_RegistroCuentaContable/M06MD03_RegistroCuentaContable.php";
    var dato = {
        "ReturnDetalleRegistroCuent": true,
        "IdRegistro": id
    };
    realizarJsonPost(url, dato, RespuestaAbrirModalRegistroCuenActualizar, null, 10000, null);
}

function RespuestaAbrirModalRegistroCuenActualizar(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        var resultado = dato.data;
        $("#__ID_DATOS_CUENTA").val(resultado.id);
        $("#txtDescripCorta").val(resultado.DescrCort);
        $("#txtDescripLarga").val(resultado.DescrLarg);
		$("#txtCuentaContableUSD").val(resultado.CuentaContableUSD);
		$("#txtCuentaContablePEN").val(resultado.CuentaContablePEN);
		$("#cbxEstado").val(resultado.estado);
		
		$("#modificar").prop('disabled', false);
        $("#eliminar").prop('disabled', false);
        $("#cancelar").prop('disabled', false);

		$('#contenido_lista').hide();
        $('#contenido_registro').show();
        
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}
/*function ValidarCaracteresDocumentoctualizar() {
    var flat = true;
    var Cadena = $("#cbxTipoDocumento :selected").text();
    if (Cadena.trim() === "DNI" && $("#txtDocumento").val().length != 8) {
        flat = false;
    } else if (Cadena.trim() === "RUC" && $("#txtDocumento").val().length != 11) {
        flat = false;
    }
    return flat;
}*/

function ValidarActualizarRequeridosDatosCliente() {
    var flat = true;
    if ($("#txtDescripCorta").val() === "") {
        $("#txtDescripCorta").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la descripción corta", "info");
        $("#txtDescripCortaHtml").html('(Requerido)');
        $("#txtDescripCortaHtml").show();
        flat = false;
    } else if ($("#txtDescripLarga").val() === "") {
        $("#txtDescripLarga").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la descripción larga", "info");
        $("#txtDescripLargaHtml").html('(Requerido)');
        $("#txtDescripLargaHtml").show();
        flat = false;
    } else if ($("#txtCuentaContableUSD").val() === "") {
        $("#txtCuentaContableUSD").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la cuenta contable", "info");
        $("#txtCuentaContableUSDHtml").html('(Requerido)');
        $("#txtCuentaContableUSDHtml").show();
        flat = false;
    }
    return flat;
}
/***************************GUARDAR ACTUALIZAR REGISTRO****************************** */
function GuardarActualizarRegistro() {
    if (ValidarActualizarRequeridosDatosCliente()) {
        bloquearPantalla("Guardando...");
        var url = "../../models/M06_Configuracion/M06MD03_RegistroCuentaContable/M06MD03_RegistroCuentaContable.php";
        var dato = {
            "ReturnActualizarRegCliente": true,
            "id": $("#__ID_DATOS_CUENTA").val(),
            "txtDescripCorta": $("#txtDescripCorta").val(),
            "txtDescripLarga": $("#txtDescripLarga").val(),
			"txtCuentaContableUSD": $("#txtCuentaContableUSD").val(),
			"txtCuentaContablePEN": $("#txtCuentaContablePEN").val(),
			"cbxEstado": $("#cbxEstado").val()
        };
        realizarJsonPost(url, dato, RespuestaGuardarActualizarRegistro, null, 10000, null);
    }
}

function RespuestaGuardarActualizarRegistro(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        Ninguno();
        mensaje_alerta("\u00A1Actualizado!", dato.data, "success");
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

/************************************ELIMINAR REGISTRO CLIENTES******************************** */
function EliminarCliente() {
    mensaje_condicionalUNO("¿Est\u00E1 seguro de eliminar?", "Al confirmar se proceder\u00E1 a eliminar el registro selecionado", ConfirmarEliminarRegistroCliente, CancelEliminarLicenciaProgramado, "");
}

function CancelEliminarLicenciaProgramado() {
    return;
}

function ConfirmarEliminarRegistroCliente() {
    bloquearPantalla("Guardando...");
    var url = "../../models/M06_Configuracion/M06MD03_RegistroCuentaContable/M06MD03_RegistroCuentaContable.php";
    var dato = {
        "ReturnEliminarRegCliente": true,
        "id": $("#__ID_DATOS_CUENTA").val()
    };

    realizarJsonPost(url, dato, RespuestaConfirmarEliminarRegistroCliente, null, 10000, null);
}

function RespuestaConfirmarEliminarRegistroCliente(dato) {
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