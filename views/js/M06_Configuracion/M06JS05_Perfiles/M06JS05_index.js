$(document).ready(function() {
    Control();
});

function Control() {
    
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

    Ninguno();

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
    //LimpiarDatosPersonales();
    $("#contenido_registro").show();
    $("#contenido_lista").hide();
   /* $("#formularioRegistrarGeneral").removeClass("disabled-form");
    $("#formularioRegistrarUsuario").removeClass("disabled-form");
    $("#txtDocumento").focus();*/
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

function MostrarLista() {
    Ninguno();
}

var Estados = { Ninguno: "Ninguno", Nuevo: "Nuevo", Modificar: "Modificar", Guardado: "Guardado", SoloLectura: "SoloLectura", Consulta: "Consulta" };
var Estado = Estados.Ninguno;

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
    LimpiarCampos();
    CargarPerfiles();
    CargarPerfilesReporte();
    $("#contenido_lista").show();
    $("#cbxTipoDocumento option:contains('DNI')").attr('selected', true);
}

function LimpiarCampos() {
    $("#txtNombrePerfil").val("");
    document.getElementById('cbxSexo').selectedIndex = 0;

    $('[href="#DatosPersonalesss"]').tab('show');
}

var tablaPerfiles = null;
function CargarPerfiles() {
    if (tablaPerfiles) {
        tablaPerfiles.destroy();
        tablaPerfiles = null;
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
            "url": "../../models/M06_Configuracion/M06MD05_Perfiles/M06MD05_listar.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ListarPerfiles": true
                });
            }
        },
        "columns": [
			{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarPerfil(\'' + data + '\')"><i class="fas fa-pencil-alt"></i></a>';
                }
            },
            { "data": "contador" },
            { "data": "perfil" },
            { "data": "area" },
            { "data": "registro" },
			{
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    if (data == 'Activo') {
                        html = '<span class="badge badge-success">' + row.estado + '</span>';
                    } else {
                        html = '<span class="badge badge-danger">' + row.estado + '</span>';                       
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

    tablaPerfiles = $('#TablaPerfiles').DataTable(options);
}

function EditarPerfil(id) {
    $('#contenido_lista').hide();
    $('#contenido_registro').show();
    /*bloquearPantalla("Buscando...");
    var url = "../../models/M06_Configuracion/M06MD02_RegistroPersonal/M06MD02_RegistroPersonal.php";
    var dato = {
        "ReturnDetalleRegistroCliente": true,
        "IdRegistro": id
    };
    realizarJsonPost(url, dato, RespuestaEditarPerfil, null, 10000, null);*/
}

function RespuestaEditarPerfil(dato) {
    desbloquearPantalla();
    if (dato.status == "ok") {
        var resultado = dato.data;
        $("#__ID_DATOS_PERSONAL").val(resultado.id);
        $("#cbxTipoDocumento").val(resultado.tipoDocumento);
        $("#cbxTipoDocumento").prop("disabled", true);
        $("#txtDocumento").val(resultado.documento);
        $("#txtDocumento").prop("disabled", true);
        $("#txtApellidos").val(resultado.apellidos);
        $("#txtNombres").val(resultado.nombres);
		$("#cbxSexo").val(resultado.sexo);
        $("#txtFechaNacimiento").val(resultado.fecNacimiento);
		$("#txtTelefono").val(resultado.telefono);
        $("#txtDireccion").val(resultado.direccion);
        $("#txtusuario").val(resultado.usuario);
        $("#txtpassword").val(resultado.password);        
        $("#cbxCargo").val(resultado.cargo);
        $("#cbxArea").val(resultado.area);
        $("#cbxJefeInmed").val(resultado.jefeinm);
		$('#contenido_lista').hide();
        $('#contenido_registro').show();
        
        return;
    } else {
        mensaje_alerta("\u00A1Error!", dato.data + "\n" + dato.dataDB, "error");
    }
}

var tablaPerfilesReporte = null;

function CargarPerfilesReporte() {
    if (tablaPerfilesReporte) {
        tablaPerfilesReporte.destroy();
        tablaPerfilesReporte = null;
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
            "url": "../../models/M06_Configuracion/M06MD05_Perfiles/M06MD05_listar.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ListarPerfiles": true
                });
            }
        },
        "columns": [
            { "data": "contador" },
            { "data": "perfil" },
            { "data": "area" },
            { "data": "registro" },
			{ "data": "estado" }            
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

    tablaPerfilesReporte = $('#TablaPerfilesReporte').DataTable(options);
}