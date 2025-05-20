$(document).ready(function() {
    Control();
});

function Control() {
    $('#cbxFiltroTrabajador').select2();
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
        EliminarCliente();
    });

    $('#busqueda_avanzada').click(function() {
        MostrarLista();
    });
    
    //InicializarAtributosTablaBusquedaPersonal();

    $('#txtdocumentoFiltro').keypress(function() {
        SoloNumeros1_9();
    });
    $('#txtNombreApellidoFiltro').keypress(function() {
        SoloLetras();
    });
    $('#btnBuscar').click(function() {
        $('#tableRegistReportPersonal').DataTable().ajax.reload();
        $('#tablaDatosPersonal').DataTable().ajax.reload();
    });
    $('#btnTodos').click(function() {
        LimpiarFiltro();
        $('#tableRegistReportPersonal').DataTable().ajax.reload();
        $('#tablaDatosPersonal').DataTable().ajax.reload();
    });

    /*****************GUARDAR REGITROS ACTUALIZADOS***************** */
    /*   $('#btnGuardarActulizado').click(function() {
           GuardarActualizarRegistro();
       });*/

    //ConfiguracionInfoRequeridosctualizar();
    
    /*$('#cbxEstado').change(function() {
        var estado = $("#cbxEstado").val();
        if(estado==1){
            var estilo = document.getElementById('cbxEstado');
            estilo.className = 'estado-activo';
        }else{
            var estilo = document.getElementById('cbxEstado');
            estilo.className = 'estado-inactivo';
        }
    });*/
    
	ConfiguracionInicioAcciones();
	
	
	/*$('#txtCoditem').on('blur', function() {
        BuscarDocumento();
    });*/
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
    $("#formularioRegistrarUsuario").addClass("disabled-form");
    LimpiarDatosPersonales();
    CargarGrillaBusquedaPersonalListaPaginado();
    CargarGrillaBusquedaPersonalReportes();
    $("#contenido_lista").show();
    //$("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
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
    $("#formularioRegistrarUsuario").removeClass("disabled-form");
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
    $("#formularioRegistrarGeneral").removeClass("disabled-form");
    $("#formularioRegistrarUsuario").removeClass("disabled-form");
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
    $('#cbxFiltroTrabajador').val(null).trigger('change');
    document.getElementById('cbxFiltroEstado').selectedIndex = 0;
}

function AbrirModalRegistroNuevo() {
    $('#modalRegistrar').modal('show');
    LimpiarDatosPersonales();
}

function LimpiarDatosPersonales() {
    //$("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
	document.getElementById('cbxCategoria').selectedIndex = 0;
    $("#txtCoditem").val("");
	$("#txtNombres").val("");
    $("#txtAbreviat").val("");
    $("#txtTexto1").val("");	
    $("#txtTexto2").val("");
    $("#txtTexto3").val("");
    $("#txtTexto4").val("");
    $("#txtTexto5").val("");
	document.getElementById('cbxEstado').selectedIndex = 0;

    $('[href="#DatosPersonalesss"]').tab('show');
}


/*function BuscarDocumento(){
    bloquearPantalla("Buscando Documento...");
    var data = {
        "btnBuscarDocumento": true,
        "cbxCategoria": $("#cbxCategoria").val(),
        "txtNroDocumento": $("#txtDocumento").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {
                $("#txtApellidos").val(dato.apellidos);
                $("#txtNombres").val(dato.nombres);
               
            } else {
                $("#txtdatos").val("");
                $("#txtDireccionCliente").val("");
                //mensaje_alerta("\u00A1ERROR!", dato.data, "info");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });   
}*/

/**************************Configuracion Inicio Acciones *************************** */
function ConfiguracionInicioAcciones() {
    //$("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
	
	/*$('#cbxTipoDocumento').on('change', function() {
        $("#cbxTipoDocumentoHtml").hide();
        ValidarPorTipoDocumento();
    });	*/
	/*$('#cbxSexo').on('change', function() {
        $("#cbxSexoHtml").hide();
    });*/
	$('#txtCoditem').on('change', function() {
        $("#txtCoditemHtml").hide();
    });
    $('#txtNombres').keydown(function() {
        $("#txtNombresHtml").hide();
    });
    $('#txtCoditem').keydown(function() {
        $("#txtCoditemHtml").hide();
    });
    $('#txtAbreviat').keydown(function() {
        $("#txtAbreviatHtml").hide();
    });
	$('#txtTexto1Html').keydown(function() {
        $("#txtTexto1Html").hide();
    });
	
	$('#txtTexto2').keydown(function() {
        $("#txtTexto2Html").hide();
    });
	
	$('#txtTexto3').keydown(function() {
        $("#txtTexto3Html").hide();
    });
	
	$('#txtTexto4').keydown(function() {
        $("#txtTexto4Html").hide();
    });
	
	$('#txtTexto5').keydown(function() {
        $("#txtTexto5Html").hide();
    });
    
   
   /* $('#txtApellidos,#txtNombres').keypress(function() {
        SoloLetras();
    });*/
}

/*****************************************LLENAR TABLA REPORTE********************************************* */
var tablaBusqPersReport = null;
function CargarGrillaBusquedaPersonalReportes() {
    if (tablaBusqPersReport) {
        tablaBusqPersReport.destroy();
        tablaBusqPersReport = null;
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
            "url": "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnPersonalListaPaginada": true,
                    "cbxFiltroTrabajador": $("#cbxFiltroTrabajador").val(),
                    "cbxFiltroEstado": $("#cbxFiltroEstado").val()
                });
            }
        },
        "columns": [
			{ "data": "categoria" },
            //{ "data": "codigo_tabla" },
			{ "data": "nombre_largo" },
            { "data": "nombre_corto" }
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

    tablaBusqPersReport = $('#tableRegistReportPersonal').DataTable(options);
}

/*****************************************LLENAR TABLA LISTA********************************************* */
var tablaBusqPersonal = null;
function CargarGrillaBusquedaPersonalListaPaginado() {
    if (tablaBusqPersonal) {
        tablaBusqPersonal.destroy();
        tablaBusqPersonal = null;
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
            "url": "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnPersonalListaPaginada": true,
                    "cbxFiltroTrabajador": $("#cbxFiltroTrabajador").val(),
                    "cbxFiltroEstado": $("#cbxFiltroEstado").val()
                });
            }
        },
        "columns": [
			{
                "data": "idconfig_detalle",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="AbrirModalRegistroActualizar(\'' + data + '\')"><i class="fas fa-pencil-alt"></i></a>';
                }
            },
            { "data": "categoria" },
            //{ "data": "codigo_tabla" },
			{ "data": "nombre_largo" },
            { "data": "nombre_corto" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    if(row.estado === 'ACTI'){
                        return '<img src="../../../images/conforme.png" alt="" width="25px" height="25px">';
                    }else{
                        return '<img src="../../../images/notificacion.png" alt="" width="25px" height="25px">';
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

    tablaBusqPersonal = $('#tablaDatosPersonal').DataTable(options);
}

function InicializarAtributosTablaBusquedaPersonal() {
    $('#tablaDatosPersonal').on('key-focus.dt', function(e, datatable, cell) {
        tablaBusqPersonal.row(cell.index().row).select();
        var data = tablaBusqPersonal.row(cell.index().row).data();
        Consulta();
        ReflejarInformacionSelccionadaReservacion(data);
    });

    $('#tablaDatosPersonal').on('tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = tablaBusqPersonal.cell(this).index().row;
        tablaBusqPersonal.row(rowIdx).select();
    });
}

function ReflejarInformacionSelccionadaReservacion(dato) {
    AbrirModalRegistroActualizar(dato.id);
   // console.log(dato);
}

function VerAdjunto(id) {  
    var data = {
      btnMostrarAdjunto: true,
      idRegistro: id,
    };
    $.ajax({
      type: "POST",
      url: "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            if(dato.formato == "jpeg" || dato.formato == "jpg"){
                var html = "";
                var documento = "archivos/"+dato.adjunto+"";
                html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                $("#my_img_doc").html(html);
                $("#modalVerAdjunto").modal("show");   
            }else{
                if(dato.formato == "png"){
                    var html = "";
                    var documento = "archivos/"+dato.adjunto+"";
                    html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                    $("#my_img_doc").html(html);
                    $("#modalVerAdjunto").modal("show");   
                }else{
                    var html = "";
                    var documento = "archivos/"+dato.adjunto+"";
                    html += "<object class='pdfview' type='application/pdf' data='" +documento +"' style='width: 100%'></object> ";
                    $("#my_img_doc").html(html);
                    $("#modalVerAdjunto").modal("show");        
                }
            }
          
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
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
        GuardarNuevoRegistro();
    } else if (Estado == Estados.Modificar) {
        GuardarActualizarRegistro();
    } else {
        mensaje_alerta("\u00A1ADVERTENCIA!", "Ocurrio un problema en el registro, por favor, intente nuevamente.", "warning");
    }
}

/***************************VALIDAR DATOS REQUERIDOS****************************** */
function ValidarDatosNuevoRequeridos() {
    var flat = true;
    if ($("#cbxCategoria").val() === "" || $("#cbxCategoria").val() === null) {
        $("#cbxCategoria").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la Categoría", "info");
        $("#cbxCategoriaHtml").html('(Requerido)');
        $("#cbxCategoriaHtml").show();
        flat = false;
    } else if ($("#txtCoditem").val() === "") {
        $("#txtCoditem").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Código", "info");
        $("#txtCoditemHtml").html('(Requerido)');
        $("#txtCoditemHtml").show();
        flat = false;
    } else if ($("#txtNombres").val() === "") {
        $("#txtNombres").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Nombre", "info");
        $("#txtNombresHtml").html('(Requerido)');
        $("#txtNombresHtml").show();
        flat = false;
    } else if ($("#txtAbreviat").val() === "") {
        $("#txtAbreviat").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la abreviatura del nombre.", "info");
        $("#txtAbreviatHtml").html('(Requerido)');
        $("#txtAbreviatHtml").show();
        flat = false;
    } /*else if ($("#txtFechaNacimiento").val() === "") {
        $("#txtFechaNacimiento").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la Fecha de Nacimiento.", "info");
        $("#txtFechaNacimientoHtml").html('(Requerido)');
        $("#txtFechaNacimientoHtml").show();
        flat = false;
    }else if ($("#txtTelefono").val() === "") {
        $("#txtTelefono").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el N° de celular.", "info");
        $("#txtTelefonoHtml").html('(Requerido)');
        $("#txtTelefonoHtml").show();
        flat = false;
    } else if ($("#txtDireccion").val() === "") {
        $("#txtDireccion").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la dirección.", "info");
        $("#txtDireccionHtml").html('(Requerido)');
        $("#txtDireccionHtml").show();
        flat = false;
    } else if ($("#txtDatoUser").val() === "") {
        $("#txtDatoUser").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el usuario asignado.", "info");
        $("#txtDatoUserHtml").html('(Requerido)');
        $("#txtDatoUserHtml").show();
        flat = false;
    } else if ($("#txtpassword").val() === "") {
        $("#txtpassword").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la contraseña.", "info");
        $("#txtpasswordHtml").html('(Requerido)');
        $("#txtpasswordHtml").show();
        flat = false;
    }else if ($("#txtpassword2").val() === "") {
        $("#txtpassword2").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese nuevamente la contraseña.", "info");
        $("#txtpassword2Html").html('(Requerido)');
        $("#txtpassword2Html").show();
        flat = false;
    }else if ($("#txtpassword").val() != $("#txtpassword2").val()) {
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, las contraseñas ingresadas no coinciden.", "info");
        flat = false;
    }else if ($("#constancia").val() === "") {
        $("#constancia").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar el Adjunto del documento del usuario.", "info");
        flat = false;
    }else if ($("#cbxJefeInmed").val() === "" || $("#cbxJefeInmed").val() === null) {
        $("#cbxJefeInmed").focus();
        $('[href="#DatosPersonalesss"]').tab('show');
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el Jefe Inmediato del personal.", "info");
        $("#cbxJefeInmedHtml").html('(Requerido)');
        $("#cbxJefeInmedHtml").show();
        flat = false;
    }*/
    return flat;
}

/***************************GUARDAR NUEVO PERSONAL****************************** */


function GuardarNuevoRegistro() {
    if (ValidarDatosNuevoRequeridos()) {
        bloquearPantalla("Buscando...");
        var url = "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php";
        var dato = {
            "ReturnGuardarRegCliente": true,
            "cbxCategoria": $("#cbxCategoria").val().trim(),
            "txtCoditem": $("#txtCoditem").val().trim(),    
            "txtNombres": $("#txtNombres").val(),
			"txtAbreviat": $("#txtAbreviat").val(),
    		"txtTexto1": $("#txtTexto1").val(),
    		"txtTexto2": $("#txtTexto2").val(),
    		"txtTexto3": $("#txtTexto3").val(),
    		"txtTexto4": $("#txtTexto4").val(),
    		"txtTexto5": $("#txtTexto5").val(),
			"cbxEstado": $("#cbxEstado").val()
			
        };
        realizarJsonPost(url, dato, respuestaGuardarNuevoRegistro, null, 10000, null);
    }
}

/*********************RESPUESTA GUARDAR NUEVO PERSONAL*********************** */
function respuestaGuardarNuevoRegistro(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        //RegistrarControl("SUBMOD-REGISTRO CLIENTES", AccionControl.Registrar);
        //Ninguno();
        //var file = $("#constancia").val();
        /*if(file !== ""){
            EnviarAdjunto(dato.name_file);
        }*/
        mensaje_alerta("\u00A1Guardado!", dato.data, "success");
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

/*function EnviarAdjunto(nombre){

   var file_data = $('#constancia').prop('files')[0];   
    var form_data = new FormData();  
    var dataa = nombre;                  
    form_data.append('file', file_data);
    form_data.append('data', dataa);
    //alert(form_data);                             
    $.ajax({
        url: '../../models/M06_Configuracion/M06MD02_RegistroPersonal/M06MD02_SubirArchivo.php', // point to server-side PHP script 
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

}*/

/****************************VERIFICAR EXISTENCIA DEL REGISTRO****************** */
function VerificarGuardarNuevoRegistro() {
    if (ValidarDatosNuevoRequeridos()) {
        bloquearPantalla("Buscando...");
        var url = "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php";
        var dato = {
            "ReturnVerificaExixtencia": true,
            "tipoDocumento": $("#cbxCategoria").val().trim(),
            "documento": $("#txtCoditem").val().trim()
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
        //GuardarNuevoRegistro();
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
/*function ConfiguracionInfoRequeridosctualizar() {

    $('#cbxTipoDocumento').on('change', function() {
        $("#cbxTipoDocumentoHtml").hide();
        ValidarPorTipoDocumentoctualizar();
    });
    $('#cbxSexo').on('change', function() {
        $("#cbxSexoHtml").hide();
    });
    $('#txtFechaNacimiento').on('change', function() {
        $("#txtFechaNaciminetoHtml").hide();
    });
    $('#cbxCargo').on('change', function() {
        $("#cbxCargoHtml").hide();
    });
    $('#cbxArea').on('change', function() {
        $("#cbxAreaHtml").hide();
    });
    $('#cbxJefeInmed').on('change', function() {
        $("#cbxJefeInmedHtml").hide();
    });

    $('#txtDocumento').keydown(function() {
        $("#txtDocumentoHtml").hide();
    });
    $('#txtApellidos').keydown(function() {
        $("#txtApellidosHtml").hide();
    });
    $('#txtNombres').keydown(function() {
        $("#txtNombresHtml").hide();
    });
    $('#txtDireccion').keydown(function() {
        $("#txtDireccionHtml").hide();
    });
    $('#txtTelefono').keydown(function() {
        $("#txtTelefonoHtml").hide();
    });
    $('#txtDocumento,#txtTelefono').keypress(function() {
        SoloNumeros1_9();
    });
    $('#txtApellidos,#txtNombres').keypress(function() {
        SoloLetras();
    });
}*/

function AbrirModalRegistroActualizar(id) {
    /*LimpiarDatosPersonalesctualizar();
    LimpiarDatosLaboralesctualizar();
    LimpiarCuentasCorrientesctualizar();
    LimpiarDatosComplementariosctualizar();*/
    bloquearPantalla("Buscando...");
    var url = "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php";
    var dato = {
        "ReturnDetalleRegistroMant": true,
        "IdRegistro": id
    };
    realizarJsonPost(url, dato, RespuestaAbrirModalRegistroActualizar, null, 10000, null);
}

function RespuestaAbrirModalRegistroActualizar(dato) {
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status == "ok") {
        var resultado = dato.data;
        $("#__ID_DATOS_MANTENIMIENTO").val(resultado.idconfig_detalle);
		
        $("#cbxCategoria").val(resultado.codigo_tabla);
        $("#cbxCategoria").prop("disabled", true);
		
        $("#txtCoditem").val(resultado.codigo_item);
        $("#txtCoditem").prop("disabled", true);
		$("#txtNombres").val(resultado.nombre_largo);
		$("#txtAbreviat").val(resultado.nombre_corto);
        $("#txtTexto1").val(resultado.texto1);
        $("#txtTexto2").val(resultado.texto2);
        $("#txtTexto3").val(resultado.texto3);
		$("#txtTexto4").val(resultado.texto4);
		$("#txtTexto5").val(resultado.texto5);
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

function ValidarActualizarRequeridosDatosCliente() {
    var flat = true;
    if ($("#cbxCategoria").val() === "" || $("#cbxCategoria").val() === null) {
        $("#cbxCategoria").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la categoría", "info");
        $("#cbxCategoriaHtml").html('(Requerido)');
        $("#cbxCategoriaHtml").show();
        flat = false;
    } else if ($("#txtCoditem").val() === "") {
        $("#txtCoditem").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Código item", "info");
        $("#txtCoditemHtml").html('(Requerido)');
        $("#txtCoditemHtml").show();
        flat = false;
    } else if ($("#txtNombres").val() === "") {
        $("#txtNombres").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el nombre.", "info");
        $("#txtNombresHtml").html('(Requerido)');
        $("#txtNombresHtml").show();
        flat = false;
    } else if ($("#txtAbreviat").val() === "") {
        $("#txtAbreviat").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la abreviatura del nombre.", "info");
        $("#txtAbreviatHtml").html('(Requerido)');
        $("#txtAbreviatHtml").show();
        flat = false;
    }	
    return flat;
}

/***************************GUARDAR ACTUALIZAR REGISTRO****************************** */
/*function GuardarActualizarRegistro() {
    if (ValidarActualizarRequeridosDatosCliente()) {
        bloquearPantalla("Guardando...");
        var url = "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php";
        var dato = {
            "ReturnActualizarRegCliente": true,
            "id": $("#__ID_DATOS_PERSONAL").val(),
            "txtNombres": $("#txtNombres").val(),
			"txtAbreviat": $("#txtAbreviat").val(),
			"txtTexto1": $("#txtTexto1").val(),
			"txtTexto2": $("#txtTexto2").val(),
			"txtTexto3": $("#txtTexto3").val(),
            "txtTexto4": $("#txtTexto4").val(),
            "txtTexto5": $("#txtTexto5").val(),
			"cbxEstado": $("#cbxEstado").val()
        };
        realizarJsonPost(url, dato, RespuestaGuardarActualizarRegistro, null, 10000, null);
    }
}*/

/******** new ******/
function GuardarActualizarRegistro() {
    if (ValidarActualizarRequeridosDatosCliente()) {
		bloquearPantalla("Guardando...");
        var url = "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php";
        var dato = {
            "ReturnActualizarRegMantenimiento": true,
            "id": $("#__ID_DATOS_MANTENIMIENTO").val(),
            "codigo_sunat": $("#codigoSunatInput").val(), // Make sure these inputs exist
            "codigo_item": $("#codigoItemInput").val(),
            "txtNombres": $("#txtNombres").val(),
            "txtAbreviat": $("#txtAbreviat").val(),
            "txtTexto1": $("#txtTexto1").val(),
            "txtTexto2": $("#txtTexto2").val(),
            "txtTexto3": $("#txtTexto3").val(),
            "txtTexto4": $("#txtTexto4").val(),
            "txtTexto5": $("#txtTexto5").val(),
            "cbxEstado": $("#cbxEstado").val()
        };
        realizarJsonPost(url, dato, RespuestaGuardarActualizarRegistro);
    }
}

/******** new ******/

function RespuestaGuardarActualizarRegistro(dato) {
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status == "ok") {
        //Ninguno();
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
    var url = "../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php";
    var dato = {
        "ReturnEliminarRegPersonal": true,
        "id": $("#__ID_DATOS_MANTENIMIENTO").val()
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















