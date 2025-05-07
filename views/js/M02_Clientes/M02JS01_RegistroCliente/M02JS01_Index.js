$(document).ready(function() {
    Control();
});

function Control() {
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
        EliminarCliente();
    });

    $('#busqueda_avanzada').click(function() {
        MostrarLista();
    });

    Ninguno(); //Funcion de inicializacion
    
    /******************NUEVO -- INICIALIZAR CARGA DE COMBOS DEPARTAMENTO, PROVINCIA NACIMIENTO********************** */
    $('#cbxPaisNacimiento').change(function() {
        $("#cbxDepartamentoNacimiento").val("");
        $("#cbxProvinciaNacimiento").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaDepartamento": true,
            "ubigeo": $('#cbxPaisNacimiento').val()
        }
        document.getElementById('cbxProvinciaNacimiento').selectedIndex = 0;
        $("#cbxProvinciaNacimiento").prop("disabled", true);
        llenarCombo(url, datos, "cbxDepartamentoNacimiento");
    });

    $('#cbxDepartamentoNacimiento').change(function() {
        $("#cbxProvinciaNacimiento").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaProvincia": true,
            "ubigeo": $('#cbxDepartamentoNacimiento').val()
        }
        llenarCombo(url, datos, "cbxProvinciaNacimiento");
        document.getElementById('cbxProvinciaNacimiento').selectedIndex = 0;
        $("#cbxProvinciaNacimiento").prop("disabled", false);

    });

    /******************NUEVO -- INICIALIZAR CARGA DE COMBOS PROVINCIA, DISTRITO DIRECCION********************** */
    $('#cbxDepartamentoDir').change(function() {
        $("#cbxProvinciaDir").val("");
        $("#cbxDistritoDir").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaProvincia": true,
            "ubigeo": $('#cbxDepartamentoDir').val()
        }
        llenarCombo(url, datos, "cbxProvinciaDir");
        document.getElementById('cbxDistritoDir').selectedIndex = 0;
        $("#cbxDistritoDir").prop("disabled", true);

    });

    $('#cbxProvinciaDir').change(function() {
        $("#cbxDistritoDir").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaDistritos": true,
            "ubigeo": $('#cbxProvinciaDir').val()
        };
        llenarCombo(url, datos, "cbxDistritoDir");
        $("#cbxDistritoDir").prop("disabled", false);
    });

    $('#btnNuevoRegistro').click(function() {
        AbrirModalRegistroNuevo();
    });

    ConfiguracionInicioAcciones();

    $('#btnGuardarNuevo').click(function() {
        VerificarGuardarNuevoRegistro();
    });

    $('#txtdocumentoFiltro').keypress(function() {
        SoloNumeros1_9();
    });

    $('#txtNombreApellidoFiltro').keypress(function() {
        SoloLetras();
    });

    $('#btnBuscar').click(function() {
        $('#tableRegistroReportes').DataTable().ajax.reload();
        $('#tablaDatosCliente').DataTable().ajax.reload();
    });
    $('#btnTodos').click(function() {
        LimpiarFiltro();
        $('#tableRegistroReportes').DataTable().ajax.reload();
        $('#tablaDatosCliente').DataTable().ajax.reload();
    });

    ConfiguracionInfoRequeridosctualizar();
    
    $("#btnBuscarCli").click(function() {
        let ndoc=$("#txtDocumento").val();
        if (ndoc=="" || ndoc==null) {
            mensaje_alerta("Falta dato","Ingresar numero de documento","info");
            $("#txtDocumento").focus();
        } else {
            let tipodoc = $("#cbxTipoDocumento").val();
            if(tipodoc == '1'){
                if(ndoc.length==8) {
                    ConsultaReniec();
                } else {
                    mensaje_alerta("Falta dato","Nro de documento no tiene los digitos necesarios","info");
                    $("#txtDocumento").focus();
                }
                
            } else {
                 mensaje_alerta("Informacion","No se encontró informacion, agregar de forma manual","info");
            }
        }
        console.log('Num Doc: '+ndoc);
        
    });
    // $("#txtDocumento").blur(function() {
    //     var tipodoc = $("#cbxTipoDocumento").val();
    //     if(tipodoc == '1'){
    //         ConsultaReniec();
    //     }
    // });  
    
}

/********************CONSULTA RENIEC************************* */
function ConsultaReniec(){

    bloquearPantalla("Consultando...");
    var url = "../../models/generales/mdl_apis.php";
    var dato = {
        "btnSeleccionReniec": true,
        "NroDocumento":  $("#txtDocumento").val()
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

    $("#formularioRegistrarGeneral").addClass("disabled-form");
    $("#formularioRegistrarContacto").addClass("disabled-form");
    $("#formularioRegistrarNacimiento").addClass("disabled-form");
    $("#formularioRegistrarDomicilio").addClass("disabled-form");


    LimpiarDatosPersonales();
    CargarGrillaBusquedaClienteListaPaginado();
    CargarGrillaBusquedaClienteReportes();
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
    $("#formularioRegistrarGeneral").removeClass("disabled-form");
    $("#formularioRegistrarContacto").removeClass("disabled-form");
    $("#formularioRegistrarNacimiento").removeClass("disabled-form");
    $("#formularioRegistrarDomicilio").removeClass("disabled-form");
    
    $("#cbxTipoDocumento").prop('disabled', false);
    $("#txtDocumento").prop('disabled', false);
    $("#cbxPaisEmisorDocumento").prop('disabled', false);
    $("#cbxNacionalidad").prop('disabled', false);
    $("#txtDocumento").focus();
    GenerarCodigoCliente();
}

function Modificar() {
    Estado = Estados.Modificar;
    $("#nuevo").prop('disabled', true);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', false);
    $("#guardar").prop('disabled', false);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', false);
    $("#formularioRegistrarGeneral").removeClass("disabled-form");
    $("#formularioRegistrarContacto").removeClass("disabled-form");
    $("#formularioRegistrarNacimiento").removeClass("disabled-form");
    $("#formularioRegistrarDomicilio").removeClass("disabled-form");
    $("#txtApellidoPaterno").focus();
    $("#txtCodigoCliente").prop('disabled', false);
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
    $('#txtdocumentoFiltro').val(null).trigger('change');
    $("#txtNombreApellidoFiltro").val("");
    $('#cbxFiltroVendedor').val(null).trigger('change');
}

function AbrirModalRegistroNuevo() {
    $('#modalRegistrar').modal('show');
    LimpiarDatosPersonales();
}

function LimpiarDatosPersonales() {
    $("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
    $("#cbxPaisEmisorDocumento option:contains('Perú')").attr('selected', true);
    $("#cbxNacionalidad option:contains('Perú')").attr('selected', true);

    $("#txtDocumento").val("");
    $("#txtApellidoPaterno").val("");
    $("#txtApellidoMaterno").val("");
    $("#txtNombres").val("");
    document.getElementById('cbxSexo').selectedIndex = 0;
    document.getElementById('cbxEstadoCivil').selectedIndex = 0;
    $("#txtRucNarutal").val("");
    $("#txtCelular2").val("");
    $("#txtTelefono").val("");
    $("#txtCelular").val("");
    $("#txtCorreo").val("");
    $("#txtFechaNacimineto").val("");
    document.getElementById('cbxPaisNacimiento').selectedIndex = 0;
    document.getElementById('cbxDepartamentoNacimiento').selectedIndex = 0;
    document.getElementById('cbxProvinciaNacimiento').selectedIndex = 0;
    document.getElementById('cbxTipoVia').selectedIndex = 0;
    $("#txtNombreVia").val("");
    $("#txtNroVia").val("");
    $("#txtNroDpto").val("");
    $("#txtInterior").val("");
    $("#txtMz").val("");
    $("#txtLt").val("");
    $("#txtKm").val("");
    $("#txtBlock").val("");
    $("#txtEtapa").val("");
    document.getElementById('cbxTipoZona').selectedIndex = 0;
    $("#txtNombreZona").val("");
    $("#txtReferencia").val("");
    document.getElementById('cbxDepartamentoDir').selectedIndex = 0;
    document.getElementById('cbxProvinciaDir').selectedIndex = 0;
    document.getElementById('cbxDistritoDir').selectedIndex = 0;
    document.getElementById('cbxSituacionDomiciliaria').selectedIndex = 0;

    $("#txtCodigoCliente").val("");
    $("#txtCodigoAnio").val("");
    $("#txtCodigoCorrelativo").val("");
    $("#documentoCliente").val("");

    $('[href="#DatosPersonalesss"]').tab('show');
}

/**************************Configuracion Inicio Acciones *************************** */
function ConfiguracionInicioAcciones() {
    $("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
    $("#cbxPaisEmisorDocumento option:contains('Perú')").attr('selected', true);
    $("#cbxNacionalidad option:contains('Perú')").attr('selected', true);


    $('#cbxTipoDocumento').on('change', function() {
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

    $('#txtDocumento').keydown(function() {
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
    $('#txtDocumento,#txtCelular2,#txtTelefono,#txtCelular').keypress(function() {
        SoloNumeros1_9();
    });
    $('#txtApellidoPaterno,#txtApellidoMaterno,#txtNombres').keypress(function() {
        SoloLetras();
    });
}

/*****************************************LLENAR TABLA REPORTE********************************************* */
var tablaBusqClienteReport = null;
function CargarGrillaBusquedaClienteReportes() {
    if (tablaBusqClienteReport) {
        tablaBusqClienteReport.destroy();
        tablaBusqClienteReport = null;
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
            "url": "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnClienteListaPaginada": true,
                    "txtDniFiltro": $("#txtdocumentoFiltro").val(),
                    "txtApeNomFiltro": $("#txtNombreApellidoFiltro").val(),
                    "txtVendedorFiltro": $("#cbxFiltroVendedor").val()
                });
            }
        },
        "columns": [
            { "data": "codigo" },
            { "data": "documento" },
            { "data": "apellidos" },
            { "data": "nombres" },
            { "data": "fechaNacimiento" },
            { "data": "email" },
            { "data": "celularTelefono" },
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

    tablaBusqClienteReport = $('#tableRegistroReportes').DataTable(options);
}

/*****************************************LLENAR TABLA LISTA********************************************* */
var tablaBusqCliente = null;
function CargarGrillaBusquedaClienteListaPaginado() {
    if (tablaBusqCliente) {
        tablaBusqCliente.destroy();
        tablaBusqCliente = null;
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
            "url": "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnClienteListaPaginada": true,
                    "txtDniFiltro": $("#txtdocumentoFiltro").val(),
                    "txtApeNomFiltro": $("#txtNombreApellidoFiltro").val(),
                    "txtVendedorFiltro": $("#cbxFiltroVendedor").val(),
                });
            }
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="AbrirModalRegistroActualizar(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a> \ <a href="javascript:void(0)" class="btn btn-warning-action" onclick="IrReservar(\'' + data + '\')" title="Reservar Lote"><i class="fas fa-tag"></i></a> \ <a href="javascript:void(0)" class="btn btn-success-action" onclick="IrVenta(\'' + data + '\')" title="Vender Lote"><i class="fas fa-dollar-sign"></i></a>';
                }
            },
            { "data": "codigo" },
            { "data": "documento" },
            { "data": "datos" },
            { "data": "fechaNacimiento" },
            { "data": "email" },
            { "data": "celularTelefono" },
            { "data": "id",
               "render": function(data, type, row, host) {
                    var html = "";
                    if (row.adjunto == "") {
                        html = 'ninguno';
                    } else {  
                        html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="VerDocumento(\'' + data + '\')"><i class="fas fa-file-pdf"></i> Documento</a>';
                    }
                    return html;
                }                 
            },
            {
                "data": "id",
                "render": function(data, type, row) {
                    return '<img src="../../../images/conforme.png" alt="" width="25px" height="25px">';
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

    tablaBusqCliente = $('#tablaDatosCliente').DataTable(options);
}

function VerDocumento(id) {  
  var data = {
    btnMostrarDocumento: true,
    idRegistro: id,
  };
  $.ajax({
    type: "POST",
    url: "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php",
    data: data,
    dataType: "json",
    success: function (dato) {
      desbloquearPantalla();
      if (dato.status == "ok") {
       
        var html = "";
        var documento = "archivos/"+dato.data+"";
        html +=
            "<object class='pdfview' type='application/pdf' data='" +
            documento +
            "' style='width: 100%'></object> ";
        $("#my_img_doc").html(html);
        $("#modalVerDocumento").modal("show");        
          
      } 
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus + ": " + errorThrown);
      desbloquearPantalla();
    },
  });
}

function GenerarCodigoCliente() {  
    var data = {
        btnGenerarCodigoCliente: true
    };
    $.ajax({
      type: "POST",
      url: "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            $("#txtCodigoCliente").val(dato.codigo);
            $("#txtCodigoAnio").val(dato.anio);
            $("#txtCodigoCorrelativo").val(dato.correlativo);
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
}

function InicializarAtributosTablaBusquedaCliente() {
    $('#tablaDatosCliente').on('key-focus.dt', function(e, datatable, cell) {
        tablaBusqCliente.row(cell.index().row).select();
        var data = tablaBusqCliente.row(cell.index().row).data();
        Consulta();
        ReflejarInformacionSelccionadaReservacion(data);
    });

    $('#tablaDatosCliente').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = tablaBusqCliente.cell(this).index().row;
        tablaBusqCliente.row(rowIdx).select();
    });
}

function ReflejarInformacionSelccionadaReservacion(dato) {
    //AbrirModalRegistroActualizar(dato.id);
    AbrirModalRegistroActualizar(dato);
    //console.log(dato);
}

function IrReservar(id) {    
    bloquearPantalla("Buscando...");
    var url = "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php";
    var dato = {
        "ReturnIrReserva": true,
        "idRegistro": id,
        "iduser": $("#__ID_USER").val()
    };
    realizarJsonPost(url, dato, Reserva, null, 10000, null);
}

function Reserva(dato){
    //console.log(dato);
    if(dato.status=="ok"){
        window.location.href = dato.ruta;
    }    
}

function IrVenta(id) {  
    bloquearPantalla("Buscando...");
    var url = "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php";
    var dato = {
        "ReturnIrVenta": true,
        "idRegistro": id,
        "iduser": $("#__ID_USER").val()
    };
    realizarJsonPost(url, dato, Venta, null, 10000, null);    
}

function Venta(dato){
    if(dato.status=="ok"){
        window.location.href = dato.ruta;
    } 
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

function ValidarCaracteresDocumento() {
    var flat = true;
    var Cadena = $("#cbxTipoDocumento :selected").text();
    if (Cadena.trim() === "DNI" && $("#txtDocumento").val().length != 8) {
        flat = false;
    } else if (Cadena.trim() === "RUC" && $("#txtDocumento").val().length != 11) {
        flat = false;
    }
    return flat;
}

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
    if ($("#cbxTipoDocumento").val() === "" || $("#cbxTipoDocumento").val() === null) {
        $("#cbxTipoDocumento").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el TIPO DOCUMENTO", "info");
        $("#cbxTipoDocumentoHtml").html('(Requerido)');
        $("#cbxTipoDocumentoHtml").show();
        flat = false;
    } else if ($("#txtDocumento").val() === "") {
        $("#txtDocumento").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el N° de Documento", "info");
        $("#txtDocumentoHtml").html('(Requerido)');
        $("#txtDocumentoHtml").show();
        flat = false;
    } else if (!ValidarCaracteresDocumento()) {
        $("#txtDocumento").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, verifique el N° del Documento", "info");
        $("#txtDocumentoHtml").html('(Verifique)');
        $("#txtDocumentoHtml").show();
        flat = false;
    } else if ($("#cbxPaisEmisorDocumento").val() === "" || $("#cbxPaisEmisorDocumento").val() === null) {
        $("#cbxPaisEmisorDocumento").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el País Emisor de Documento.", "info");
        $("#cbxPaisEmisorDocumentoHtml").html('(Requerido)');
        $("#cbxPaisEmisorDocumentoHtml").show();
        flat = false;
    } else if ($("#txtApellidoPaterno").val() === "") {
        $("#txtApellidoPaterno").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Apellido Paterno", "info");
        $("#txtApellidoPaternoHtml").html('(Requerido)');
        $("#txtApellidoPaternoHtml").show();
        flat = false;
    } else if ($("#txtApellidoMaterno").val() === "") {
        $("#txtApellidoMaterno").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Apellido Materno", "info");
        $("#txtApellidoMaternoHtml").html('(Requerido)');
        $("#txtApellidoMaternoHtml").show();
        flat = false;
    } else if ($("#txtNombres").val() === "") {
        $("#txtNombres").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese los nombres.", "info");
        $("#txtNombresHtml").html('(Requerido)');
        $("#txtNombresHtml").show();
        flat = false;
    } else if ($("#cbxNacionalidad").val() === "" || $("#cbxNacionalidad").val() === null) {
        $("#cbxNacionalidad").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la Nacionalidad.", "info");
        $("#cbxNacionalidadHtml").html('(Requerido)');
        $("#cbxNacionalidadHtml").show();
        flat = false;
    } else if ($("#cbxSexo").val() === "" || $("#cbxSexo").val() === null) {
        $("#cbxSexo").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Sexo.", "info");
        $("#cbxSexoHtml").html('(Requerido)');
        $("#cbxSexoHtml").show();
        flat = false;
    } else if ($("#txtCelular").val() === "") {
        $("#txtCelular").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el N° de celular.", "info");
        $("#txtCelularHtml").html('(Requerido)');
        $("#txtCelularHtml").show();
        flat = false;
    } else if (!VerificarCorreoValido("txtCorreo")) {
        $("#txtCorreo").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese un correo válido.", "info");
        $("#txtCorreoHtml").html('(Verifique)');
        $("#txtCorreoHtml").show();
        flat = false;
    } else if ($("#txtFechaNacimineto").val() === "") {
        $("#txtFechaNacimineto").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la Fecha de Nacimiento.", "info");
        $("#txtFechaNaciminetoHtml").html('(Requerido)');
        $("#txtFechaNaciminetoHtml").show();
        flat = false;
    } else if ($("#cbxDepartamentoDir").val() === "" || $("#cbxDepartamentoDir").val() === null) {
        $("#cbxDepartamentoDir").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Departamento de la dirección.", "info");
        $("#cbxDepartamentoDirHtml").html('(Requerido)');
        $("#cbxDepartamentoDirHtml").show();
        flat = false;
    } else if ($("#cbxProvinciaDir").val() === "" || $("#cbxProvinciaDir").val() === null) {
        $("#cbxProvinciaDir").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la Provincia de la dirección.", "info");
        $("#cbxProvinciaDirHtml").html('(Requerido)');
        $("#cbxProvinciaDirHtml").show();
        flat = false;
    } else if ($("#cbxDistritoDir").val() === "" || $("#cbxDistritoDir").val() === null) {
        $("#cbxDistritoDir").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Distrito de la dirección.", "info");
        $("#cbxDistritoDirHtml").html('(Requerido)');
        $("#cbxDistritoDirHtml").show();
        flat = false;
    }
    return flat;
}

/***************************GUARDAR NUEVO CLIENTE****************************** */
function GuardarNuevoRegistro() {
    bloquearPantalla("Guardando...");
    var url = "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php";
    var dato = {
        "ReturnGuardarRegCliente": true,
        "cbxTipoDocumento": $("#cbxTipoDocumento").val().trim(),
        "txtDocumento": $("#txtDocumento").val().trim(),
        "cbxNacionalidad": $("#cbxNacionalidad").val().trim(),
        "cbxPaisEmisorDocumento": $("#cbxPaisEmisorDocumento").val(),
        "txtApellidoPaterno": $("#txtApellidoPaterno").val(),
        "txtApellidoMaterno": $("#txtApellidoMaterno").val(),
        "txtNombres": $("#txtNombres").val(),
        "cbxDepartamentoNacimiento": $("#cbxDepartamentoNacimiento").val(),
        "cbxProvinciaNacimiento": $("#cbxProvinciaNacimiento").val(),
        "cbxPaisNacimiento": $("#cbxPaisNacimiento").val(),
        "txtFechaNacimineto": $("#txtFechaNacimineto").val(),
        "cbxSexo": $("#cbxSexo").val(),
        "cbxTipoVia": $("#cbxTipoVia").val(),
        "txtNombreVia": $("#txtNombreVia").val(),
        "txtNroVia": $("#txtNroVia").val(),
        "txtNroDpto": $("#txtNroDpto").val(),
        "txtInterior": $("#txtInterior").val(),
        "txtMz": $("#txtMz").val(),
        "txtLt": $("#txtLt").val(),
        "txtKm": $("#txtKm").val(),
        "txtBlock": $("#txtBlock").val(),
        "txtEtapa": $("#txtEtapa").val(),
        "cbxTipoZona": $("#cbxTipoZona").val(),
        "txtNombreZona": $("#txtNombreZona").val(),
        "txtReferencia": $("#txtReferencia").val(),
        "cbxDistritoDir": $("#cbxDistritoDir").val(),
        "cbxProvinciaDir": $("#cbxProvinciaDir").val(),
        "cbxDepartamentoDir": $("#cbxDepartamentoDir").val(),
        "txtCelular2": $("#txtCelular2").val(),
        "txtTelefono": $("#txtTelefono").val(),
        "txtCelular": $("#txtCelular").val(),
        "txtCorreo": $("#txtCorreo").val(),
        "cbxEstadoCivil": $("#cbxEstadoCivil").val(),
        "cbxSituacionDomiciliaria": $("#cbxSituacionDomiciliaria").val(),
        "documentoCliente": $("#file_documentoCliente").val(),
        "txtCodigoCliente": $("#txtCodigoCliente").val(),
        "txtCodigoAnio": $("#txtCodigoAnio").val(),
        "txtCodigoCorrelativo": $("#txtCodigoCorrelativo").val(),
        "__ID_USER": $("#__ID_USER").val()
    };
    realizarJsonPost(url, dato, respuestaGuardarNuevoRegistro, null, 10000, null);
}

/*********************RESPUESTA GUARDAR NUEVO CLIENTE*********************** */
function respuestaGuardarNuevoRegistro(dato) {
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status == "ok") {
        //RegistrarControl("SUBMOD-REGISTRO CLIENTES", AccionControl.Registrar);
        Ninguno();
        EnviarAdjunto(dato.nombre);
        mensaje_alerta("\u00A1Guardado!", dato.data, "success");
		//Llamamos a la función que actualizará el filtro
        actualizarFiltroClientes();
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

function actualizarFiltroClientes() {
    $.ajax({
        type: "POST",
        url: "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php",
        data: { "VerClientesBusqueda": true }, //Corregido
        dataType: "json",
        success: function (respuesta) {
            if (respuesta.status == "ok") {
                var select = $("#txtdocumentoFiltro");
                select.empty(); // Limpia el filtro                

                // Agrega la opción por defecto
                select.append('<option selected="true" value="" disabled="disabled">TODOS</option>');

                // Recorre los datos y añade las nuevas opciones
                $.each(respuesta.data, function (index, item) {
                    select.append('<option value="' + item.ID + '">' + item.Nombre + '</option>');
                });

                // Selecciona automáticamente el cliente recién agregado (opcional)
                if (respuesta.nuevo_cliente) {
                    select.val(respuesta.nuevo_cliente);
                }

                console.log("Filtro de clientes actualizado correctamente.");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error al actualizar filtro de clientes:", textStatus, errorThrown);
        }
    });
}

function EnviarAdjunto(nombre){

   var file_data = $('#file_documentoCliente').prop('files')[0];   
    var form_data = new FormData();  
    var dataa = nombre;                  
    form_data.append('file', file_data);
    form_data.append('data', dataa);
    //alert(form_data);                             
    $.ajax({
        url: '../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_SubirArchivo.php', // point to server-side PHP script 
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

/****************************VERIFICAR EXISTENCIA DEL REGISTRO****************** */
function VerificarGuardarNuevoRegistro() {
    if (ValidarDatosNuevoRequeridos()) {
        bloquearPantalla("Buscando...");
        var url = "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php";
        var dato = {
            "ReturnVerificaExixtencia": true,
            "tipoDocumento": $("#cbxTipoDocumento").val().trim(),
            "documento": $("#txtDocumento").val().trim(),
            "nacionalidad": $("#cbxNacionalidad").val().trim()
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
function ValidarPorTipoDocumentoctualizar() {
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
}
/**************************VALIDAR DATOS REQUERIDOS *************************** */
function ConfiguracionInfoRequeridosctualizar() {

    $('#cbxTipoDocumento').on('change', function() {
        $("#cbxTipoDocumentoHtml").hide();
        ValidarPorTipoDocumentoctualizar();
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

    $('#txtDocumento').keydown(function() {
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
    $('#txtDocumento,#txtCelular2,#txtTelefono,#txtCelular').keypress(function() {
        SoloNumeros1_9();
    });
    $('#txtApellidoPaterno,#txtApellidoMaterno,#txtNombres').keypress(function() {
        SoloLetras();
    });
}

function AbrirModalRegistroActualizar(id) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php";
    var dato = {
        "ReturnDetalleRegistroCliente": true,
        "IdRegistro": id
    };
    realizarJsonPost(url, dato, RespuestaAbrirModalRegistroActualizar, null, 10000, null);
}

function RespuestaAbrirModalRegistroActualizar(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        var resultado = dato.data;
        $("#__ID_DATOS_CLIENTE").val(resultado.id);
        $("#cbxTipoDocumento").val(resultado.tipoDocumento);
        $("#txtDocumento").val(resultado.documento);
        $("#cbxNacionalidad").val(resultado.nacionalidad);
        $("#cbxPaisEmisorDocumento").val(resultado.paisEmisorDoc);
        $("#txtApellidoPaterno").val(resultado.apellidoPaterno);
        $("#txtApellidoMaterno").val(resultado.apellidoMaterno);
        $("#txtNombres").val(resultado.nombres);
        $("#cbxPaisNacimiento").val(resultado.idPaisNac);
        LLenarDepartamentoNacIdPais(resultado.idPaisNac, resultado.idDepartamentoNac);
        LLenarProvinciaNacIdDepNac(resultado.idDepartamentoNac, resultado.idProvinciaNac);
        $("#txtFechaNacimineto").val(resultado.fecNacimiento);
        $("#cbxSexo").val(resultado.sexo);
        $("#cbxTipoVia").val(resultado.tipoVia);
        $("#txtNombreVia").val(resultado.nombreVia);
        $("#txtNroVia").val(resultado.nroVia);
        $("#txtNroDpto").val(resultado.nroDpto);
        $("#txtInterior").val(resultado.interior);
        $("#txtMz").val(resultado.mz);
        $("#txtLt").val(resultado.lt);
        $("#txtKm").val(resultado.km);
        $("#txtBlock").val(resultado.blockDir);
        $("#txtEtapa").val(resultado.etapa);
        $("#cbxTipoZona").val(resultado.tipoZona);
        $("#txtNombreZona").val(resultado.nombreZona);
        $("#txtReferencia").val(resultado.referencia);
        $("#cbxDepartamentoDir").val(resultado.idDepartamentoDir);
        LLenarProvinciaId(resultado.idDepartamentoDir, resultado.idProvinciaDir);
        LLenarDistritoId(resultado.idProvinciaDir, resultado.idDistritoDir);
        $("#txtTelefono").val(resultado.telefono);
        $("#txtCelular").val(resultado.celular1);
        $("#txtCelular2").val(resultado.celular2);
        $("#txtCorreo").val(resultado.email);
        $("#cbxEstadoCivil").val(resultado.estadoCivil);
        $("#cbxSituacionDomiciliaria").val(resultado.situacionDomiciliaria);

        $("#txtCodigoCliente").val(resultado.codigo);
        $("#txtCodigoAnio").val(resultado.codigo_anio);
        $("#txtCodigoCorrelativo").val(resultado.codigo_correlativo);

        if(resultado.validacion=="Si"){
            $("#txtCodigoCliente").prop('disabled', false);
        }else{
            $("#txtCodigoCliente").prop('disabled', true);
        }

        Consulta();
        //$('#modalActualizar').modal('show');
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}


/*************************LLenar combo de acuerdo pais nacimiento******************************* */
function LLenarDepartamentoNacIdPais(idPais, idDep) {
    var url = '../../../models/General/BusquedaUbigeo.php';
    var datos = {
        "ReturnListaDepartamento": true,
        "ubigeo": idPais
    }
    llenarComboSelecionar(url, datos, "cbxDepartamentoNacimiento", idDep);
}

/*************************LLenar combo de acuerdo departamento******************************* */
function LLenarProvinciaNacIdDepNac(idDep, idPro) {
    var url = '../../../models/General/BusquedaUbigeo.php';
    var datos = {
        "ReturnListaProvincia": true,
        "ubigeo": idDep
    }
    llenarComboSelecionar(url, datos, "cbxProvinciaNacimiento", idPro);
}


/*************************LLenar combo de acuerdo departamento******************************* */
function LLenarProvinciaId(idDep, idPro) {
    var url = '../../../models/General/BusquedaUbigeo.php';
    var datos = {
        "ReturnListaProvincia": true,
        "ubigeo": idDep
    }
    llenarComboSelecionar(url, datos, "cbxProvinciaDir", idPro);
}

/*************************LLenar combo de acuerdo provincia******************************* */
function LLenarDistritoId(idProv, idDist) {
    var url = '../../../models/General/BusquedaUbigeo.php';
    var datos = {
        "ReturnListaDistritos": true,
        "ubigeo": idProv
    };
    llenarComboSelecionar(url, datos, "cbxDistritoDir", idDist);
}

/***************************VALIDAR DATOS REQUERIDOS ACTUALIZAR****************************** */
function ValidarCaracteresDocumentoctualizar() {
    var flat = true;
    var Cadena = $("#cbxTipoDocumento :selected").text();
    if (Cadena.trim() === "DNI" && $("#txtDocumento").val().length != 8) {
        flat = false;
    } else if (Cadena.trim() === "RUC" && $("#txtDocumento").val().length != 11) {
        flat = false;
    }
    return flat;
}

function ValidarActualizarRequeridosDatosCliente() {
    var flat = true;
    if ($("#cbxTipoDocumento").val() === "" || $("#cbxTipoDocumento").val() === null) {
        $("#cbxTipoDocumento").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el TIPO DOCUMENTO", "info");
        $("#cbxTipoDocumentoHtml").html('(Requerido)');
        $("#cbxTipoDocumentoHtml").show();
        flat = false;
    } else if ($("#txtDocumento").val() === "") {
        $("#txtDocumento").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el N° de Documento", "info");
        $("#txtDocumentoHtml").html('(Requerido)');
        $("#txtDocumentoHtml").show();
        flat = false;
    } else if (!ValidarCaracteresDocumentoctualizar()) {
        $("#txtDocumento").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, verifique el N° del Documento", "info");
        $("#txtDocumentoHtml").html('(Verifique)');
        $("#txtDocumentoHtml").show();
        flat = false;
    } else if ($("#cbxPaisEmisorDocumento").val() === "" || $("#cbxPaisEmisorDocumento").val() === null) {
        $("#cbxPaisEmisorDocumento").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el País Emisor de Documento.", "info");
        $("#cbxPaisEmisorDocumentoHtml").html('(Requerido)');
        $("#cbxPaisEmisorDocumentoHtml").show();
        flat = false;
    } else if ($("#txtApellidoPaterno").val() === "") {
        $("#txtApellidoPaterno").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Apellido Paterno", "info");
        $("#txtApellidoPaternoHtml").html('(Requerido)');
        $("#txtApellidoPaternoHtml").show();
        flat = false;
    } else if ($("#txtApellidoMaterno").val() === "") {
        $("#txtApellidoMaterno").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Apellido Materno", "info");
        $("#txtApellidoMaternoHtml").html('(Requerido)');
        $("#txtApellidoMaternoHtml").show();
        flat = false;
    } else if ($("#txtNombres").val() === "") {
        $("#txtNombres").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese los nombres.", "info");
        $("#txtNombresHtml").html('(Requerido)');
        $("#txtNombresHtml").show();
        flat = false;
    } else if ($("#cbxNacionalidad").val() === "" || $("#cbxNacionalidad").val() === null) {
        $("#cbxNacionalidad").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la Nacionalidad.", "info");
        $("#cbxNacionalidadHtml").html('(Requerido)');
        $("#cbxNacionalidadHtml").show();
        flat = false;
    } else if ($("#cbxSexo").val() === "" || $("#cbxSexo").val() === null) {
        $("#cbxSexo").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Sexo.", "info");
        $("#cbxSexoHtml").html('(Requerido)');
        $("#cbxSexoHtml").show();
        flat = false;
    } else if ($("#txtCelular").val() === "") {
        $("#txtCelular").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el N° de celular.", "info");
        $("#txtCelularHtml").html('(Requerido)');
        $("#txtCelularHtml").show();
        flat = false;
    } else if (!VerificarCorreoValido("txtCorreo")) {
        $("#txtCorreo").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese un correo válido.", "info");
        $("#txtCorreoHtml").html('(Verifique)');
        $("#txtCorreoHtml").show();
        flat = false;
    } else if ($("#txtFechaNacimineto").val() === "") {
        $("#txtFechaNacimineto").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la Fecha de Nacimiento.", "info");
        $("#txtFechaNaciminetoHtml").html('(Requerido)');
        $("#txtFechaNaciminetoHtml").show();
        flat = false;
    } else if ($("#cbxDepartamentoDir").val() === "" || $("#cbxDepartamentoDir").val() === null) {
        $("#cbxDepartamentoDir").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Departamento de la dirección.", "info");
        $("#cbxDepartamentoDirHtml").html('(Requerido)');
        $("#cbxDepartamentoDirHtml").show();
        flat = false;
    } else if ($("#cbxProvinciaDir").val() === "" || $("#cbxProvinciaDir").val() === null) {
        $("#cbxProvinciaDir").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la Provincia de la dirección.", "info");
        $("#cbxProvinciaDirHtml").html('(Requerido)');
        $("#cbxProvinciaDirHtml").show();
        flat = false;
    } else if ($("#cbxDistritoDir").val() === "" || $("#cbxDistritoDir").val() === null) {
        $("#cbxDistritoDir").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Distrito de la dirección.", "info");
        $("#cbxDistritoDirHtml").html('(Requerido)');
        $("#cbxDistritoDirHtml").show();
        flat = false;
    }else if ($("#txtCodigoCliente").val() === "" || $("#txtCodigoCliente").val() === null) {
        $("#txtCodigoCliente").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese el codigo del cliente", "info");
        $("#txtCodigoClienteHtml").html('(Requerido)');
        $("#txtCodigoClienteHtml").show();
        flat = false;
    }
    return flat;
}
/***************************GUARDAR ACTUALIZAR REGISTRO****************************** */
function GuardarActualizarRegistro() {
    if (ValidarActualizarRequeridosDatosCliente()) {
        bloquearPantalla("Guardando...");
        var url = "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php";
        var dato = {
            "ReturnActualizarRegCliente": true,
            "id": $("#__ID_DATOS_CLIENTE").val(),
            "cbxTipoDocumento": $("#cbxTipoDocumento").val(),
            "txtDocumento": $("#txtDocumento").val(),
            "cbxNacionalidad": $("#cbxNacionalidad").val(),
            "cbxPaisEmisorDocumento": $("#cbxPaisEmisorDocumento").val(),
            "txtApellidoPaterno": $("#txtApellidoPaterno").val(),
            "txtApellidoMaterno": $("#txtApellidoMaterno").val(),
            "txtNombres": $("#txtNombres").val(),
            "cbxDepartamentoNacimiento": $("#cbxDepartamentoNacimiento").val(),
            "cbxProvinciaNacimiento": $("#cbxProvinciaNacimiento").val(),
            "cbxPaisNacimiento": $("#cbxPaisNacimiento").val(),
            "txtFechaNacimineto": $("#txtFechaNacimineto").val(),
            "cbxSexo": $("#cbxSexo").val(),
            "cbxTipoVia": $("#cbxTipoVia").val(),
            "txtNombreVia": $("#txtNombreVia").val(),
            "txtNroVia": $("#txtNroVia").val(),
            "txtNroDpto": $("#txtNroDpto").val(),
            "txtInterior": $("#txtInterior").val(),
            "txtMz": $("#txtMz").val(),
            "txtLt": $("#txtLt").val(),
            "txtKm": $("#txtKm").val(),
            "txtBlock": $("#txtBlock").val(),
            "txtEtapa": $("#txtEtapa").val(),
            "cbxTipoZona": $("#cbxTipoZona").val(),
            "txtNombreZona": $("#txtNombreZona").val(),
            "txtReferencia": $("#txtReferencia").val(),
            "cbxDistritoDir": $("#cbxDistritoDir").val(),
            "cbxProvinciaDir": $("#cbxProvinciaDir").val(),
            "cbxDepartamentoDir": $("#cbxDepartamentoDir").val(),
            "txtTelefono": $("#txtTelefono").val(),
            "txtCelular": $("#txtCelular").val(),
            "txtCelular2": $("#txtCelular2").val(),
            "txtCorreo": $("#txtCorreo").val(),
            "cbxEstadoCivil": $("#cbxEstadoCivil").val(),
            "cbxSituacionDomiciliaria": $("#cbxSituacionDomiciliaria").val(),
            "documentoCliente": $("#file_documentoCliente").val(),
            "txtCodigoCliente": $("#txtCodigoCliente").val()
        };
        realizarJsonPost(url, dato, RespuestaGuardarActualizarRegistro, null, 10000, null);
    }
}

function RespuestaGuardarActualizarRegistro(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        EnviarAdjunto(dato.nombre);
        //console.log(dato);
        Ninguno();        
        mensaje_alerta("\u00A1Actualizado!", dato.data, "success");
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data, "error");
    }
}

/************************************ELIMINAR REGISTRO CLIENTES******************************** */
function EliminarCliente() {
    mensaje_condicionalUNO("¿Est\u00E1 seguro de eliminar?", "Al confirmar se proceder\u00E1 a eliminar el registro selecionado, recordando que este cliente no tenga un reserva o venta agregada", ConfirmarEliminarRegistroCliente, CancelEliminarLicenciaProgramado, "");
}

function CancelEliminarLicenciaProgramado() {
    return;
}

function ConfirmarEliminarRegistroCliente() {
    bloquearPantalla("Guardando...");
    var url = "../../models/M02_Clientes/M02MD01_RegistroCliente/M02MD01_RegistroCliente_Procesos.php";
    var dato = {
        "ReturnEliminarRegCliente": true,
        "id": $("#__ID_DATOS_CLIENTE").val(),
        "__ID_USER": $("#__ID_USER").val()
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
    }else if(dato.status == "info"){
        setTimeout(function() {
            mensaje_alerta("Adventencia!", dato.data + "\n", "info");
        }, 100);

    } else {
        setTimeout(function() {
            mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
        }, 100);
    }
}