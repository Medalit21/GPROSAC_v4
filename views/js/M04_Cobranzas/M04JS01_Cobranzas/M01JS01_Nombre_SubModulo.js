var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    $('input[type="file"]').each(function() {
        var label = $(this).parents('.btn-subir-doc').find('label').html();
        label = (label) ? label : 'Upload File';
        $(this).wrap('<div class="input-file"></div>');
        $(this).before('<span class="btn">' + label + '</span>');
        $(this).before('<span class="file-selected"></span>');
    });
    $('.input-file .btn').click(function() {
        $(this).siblings('input[type="file"]').trigger('click');
    });
    
    $('.modal').on("hidden.bs.modal", function(e) {
        if ($('.modal:visible').length) {
            $('body').addClass('modal-open');
        }
    });

    ListarEmpresas();
    ListarEmpresasReporte();

    $('#btnRegistrarEmpresa').click(function() {
        RegistrarEmpresa();
     });

     $('#btnActualizarEmpresa').click(function() {
        ActualizarEmpresa();
     });

     $('#btnEliminarEmpresa').click(function() {
        EliminarEmpresa();               
     });

     $('#btnNuevaEmpresa').click(function() {
        NuevaEmpresa();
     });

     $('#btnCancelarEmpresa').click(function() {
        Cancelar();
     });

     $('#btnBuscarEmpresa').click(function() {
        $('#TablaEmpresas').DataTable().ajax.reload();
        $('#TablaEmpresasReporte').DataTable().ajax.reload();
     });

     $('#btnBuscarEmpresa').click(function() {
        $('#TablaEmpresas').DataTable().ajax.reload();
        $('#TablaEmpresasReporte').DataTable().ajax.reload();
     });

     $('#btnLimpiarFiltros').click(function() {
        $("#txtBuscarNombre").val("");
        $("#txtBuscarRuc").val("");
        $('#TablaEmpresas').DataTable().ajax.reload();
        $('#TablaEmpresasReporte').DataTable().ajax.reload();
     });
     
        $('#fileSubirFirma').change(function(e) {
        CargarFirma(e);
    });


}

function ListarEmpresas() {
   
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
            "url": "../../models/M01_Configuracion/M01MD01_Empresas/M01MD01_ListarEmpresas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "txtBuscarNombre": $("#txtBuscarNombre").val(),
                    "txtBuscarRuc": $("#txtBuscarRuc").val()
                });
            }
        },
        "columns": [{
                "data": "id",
                "render": function(data, type, row) {
                    return '<a href="javascript:void(0)"  onclick="EditarEmpresa(\'' + data + '\')"><img src="../../../images/editar.png" width="25px" height="25px" ></a>';
                }
            },
            { "data": "nombre" },
            { "data": "nombre" },
            { "data": "ruc" },
            { "data": "responsable" }
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

    tablaEmpresas = $('#TablaEmpresas').DataTable(options);

    $('#TablaEmpresas').on('key-focus.dt', function(e, datatable, cell) {
        tablaEmpresas.row(cell.index().row).select();
        var data = tablaEmpresas.row(cell.index().row).data();
        ListaSeleccionada.splice(0, ListaSeleccionada.length);
        ListaSeleccionada.push(data);
    });

    $('#TablaEmpresas').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = tablaEmpresas.cell(this).index().row;
        tablaEmpresas.row(rowIdx).select();
    });

    $('#TablaEmpresas').on('key.dt', function(e, datatable, key, cell, originalEvent) {
        if (key === 13) {
            var data = tablaEmpresas.row(cell.index().row).data();
            ReflejarInformacionSelccionadoBusqTrabajador(data);
        }
    });
    $('#TablaEmpresas tbody').dblclick(function(e) {
        SeleccionarRegistroBusqTrabajador();
    });
}

function EditarEmpresa(id) {
    //$('#modalEditarFalladosDatosLaborales').modal('show');
    //LimpiarCamposEditarError();
    bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionarRegistro": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Configuracion/M01MD01_Empresas/M01MD01_SeleccionEdicionEmpresa.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                var resultado = dato.data;
                $("#txtid").val(resultado.id);
                $("#txtnombre").val(resultado.nombre);
                $("#txtrazon_social").val(resultado.razon_social);
                $("#txtsigla").val(resultado.sigla);
                $("#txtresponsable").val(resultado.responsable);
                $("#txtruc").val(resultado.ruc);
                $("#txtTamano").val(resultado.tamano);
                $("#txtTipoActividad").val(resultado.tipo_actividad);
                $("#txtOrigenCap").val(resultado.origen_capital);
                $("#txtConstJuridica").val(resultado.constitucion_juridica);
                $("#txtDestinoBenef").val(resultado.destino_beneficios);
                $("#txtSector").val(resultado.sector);
                $("#txtAmbitoAct").val(resultado.ambito_actividad);
                $("#txtdireccion").val(resultado.direccion);
                $("#txttelefono").val(resultado.telefono);
                $("#txtemail").val(resultado.email);
                $("#txtcontacto").val(resultado.contacto);
                $("#bxestado").val(resultado.estado);
                $("#bxTipoPlanilla").val(resultado.tipoPlanilla);
                $("#txtDiasVacaciones").val(resultado.vacaciones);
                $("#txtJornadaLaboral").val(resultado.jornadaLaboral);

                $("#btnRegistrarEmpresa").prop('disabled', true);
                $("#btnActualizarEmpresa").prop('disabled', false);
                $("#btnEliminarEmpresa").prop('disabled', false);
                $("#btnNuevaEmpresa").prop('disabled', false);
                $("#btnCancelarEmpresa").prop('disabled', true);
                return;
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}
var tablaEmpresas = null;

function ListarEmpresasReporte() {
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
            "url": "../../models/M01_Configuracion/M01MD01_Empresas/M01MD01_ListarEmpresas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "txtBuscarNombre": $("#txtBuscarNombre").val(),
                    "txtBuscarRuc": $("#txtBuscarRuc").val()
                });
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "nombre" },
            { "data": "nombre" },
            { "data": "ruc" },
            { "data": "responsable" }
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

    tablaEmpresas = $('#TablaEmpresasReporte').DataTable(options);
}

function RegistrarEmpresa() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = { "btnRegistrarEmpresa": true,
                  "txtid": $("#txtid").val(),
                  "txtnombre": $("#txtnombre").val(),
                  "txtrazon_social": $("#txtrazon_social").val(),
                  "txtsigla": $("#txtsigla").val(),
                  "txtresponsable": $("#txtresponsable").val(),
                  "txtruc": $("#txtruc").val(),
                  "txtTamano": $("#txtTamano").val(),
                  "txtTipoActividad": $("#txtTipoActividad").val(),
                  "txtSector": $("#txtSector").val(),
                  "txtOrigenCap": $("#txtOrigenCap").val(),
                  "txtConstJuridica": $("#txtConstJuridica").val(),
                  "txtAmbitoAct": $("#txtAmbitoAct").val(),
                  "txtDestinoBenef": $("#txtDestinoBenef").val(),
                  "txtdireccion": $("#txtdireccion").val(),
                  "txttelefono": $("#txttelefono").val(),
                  "txtemail": $("#txtemail").val(),
                  "txtcontacto": $("#txtcontacto").val(),                  
                  "bxTipoPlanilla": $("#bxTipoPlanilla").val(),
                  "txtDiasVacaciones": $("#txtDiasVacaciones").val(),
                  "txtJornadaLaboral": $("#txtJornadaLaboral").val(),
                  "bxestado": $("#bxestado").val()
                };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Configuracion/M01MD01_Empresas/M01MD01_RegistrarEmpresas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {	
                $('#TablaEmpresas').DataTable().ajax.reload();
                $('#TablaEmpresasReporte').DataTable().ajax.reload();
                setTimeout(function() {
                    mensaje_alerta("\u00A1Proceso Correcto!", dato.data, "success");
                }, 100);
                return;
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

function ActualizarEmpresa() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = { "btnActualizarEmpresa": true,
                "txtid": $("#txtid").val(),
                "txtnombre": $("#txtnombre").val(),
                "txtrazon_social": $("#txtrazon_social").val(),
                "txtsigla": $("#txtsigla").val(),
                "txtresponsable": $("#txtresponsable").val(),
                "txtruc": $("#txtruc").val(),
                "txtTamano": $("#txtTamano").val(),
                "txtTipoActividad": $("#txtTipoActividad").val(),
                "txtSector": $("#txtSector").val(),
                "txtOrigenCap": $("#txtOrigenCap").val(),
                "txtConstJuridica": $("#txtConstJuridica").val(),
                "txtAmbitoAct": $("#txtAmbitoAct").val(),
                "txtDestinoBenef": $("#txtDestinoBenef").val(),
                "txtdireccion": $("#txtdireccion").val(),
                "txttelefono": $("#txttelefono").val(),
                "txtemail": $("#txtemail").val(),
                "txtcontacto": $("#txtcontacto").val(), 
                "bxTipoPlanilla": $("#bxTipoPlanilla").val(),
                "txtDiasVacaciones": $("#txtDiasVacaciones").val(),
                "txtJornadaLaboral": $("#txtJornadaLaboral").val(),                 
                "bxestado": $("#bxestado").val()};
    $.ajax({
        type: "POST",
        url: "../../models/M01_Configuracion/M01MD01_Empresas/M01MD01_ActualizarEmpresas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {	
                $('#TablaEmpresas').DataTable().ajax.reload();
                $('#TablaEmpresasReporte').DataTable().ajax.reload();
                setTimeout(function() {
                    mensaje_alerta("\u00A1Proceso Correcto!", dato.data, "success");
                }, 100);
                return;
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

function EliminarEmpresa() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = { "btnEliminarEmpresa": true,
                  "txtid": $("#txtid").val()};
    $.ajax({
        type: "POST",
        url: "../../models/M01_Configuracion/M01MD01_Empresas/M01MD01_EliminarEmpresas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {	
                $('#TablaEmpresas').DataTable().ajax.reload();
                $('#TablaEmpresasReporte').DataTable().ajax.reload();
                $("#txtBuscarNombre").val("");
                $("#txtBuscarRuc").val("");
                NuevaEmpresa(); 
                setTimeout(function() {
                    mensaje_alerta("\u00A1Proceso Correcto!", dato.data, "success");
                }, 100);
                return;
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

function NuevaEmpresa() { 
    $("#txtnombre").val("");
    $("#txtrazon_social").val("");
    $("#txtsigla").val("");
    $("#txtruc").val("");
    $("#txtdireccion").val("");
    $("#txttelefono").val("");
    $("#txtemail").val("");
    $("#txtcontacto").val("");
    $("#txtdescripcion").val("");
    $("#txtid").val("");
    $("#txtDiasVacaciones").val("");
    $("#txtJornadaLaboral").val("");
    document.getElementById('txtresponsable').selectedIndex = 0;
    document.getElementById('bxestado').selectedIndex = 0;
    document.getElementById('txtTamano').selectedIndex = 0;
    document.getElementById('txtTipoActividad').selectedIndex = 0;
    document.getElementById('txtSector').selectedIndex = 0;
    document.getElementById('txtOrigenCap').selectedIndex = 0;
    document.getElementById('txtConstJuridica').selectedIndex = 0;
    document.getElementById('txtAmbitoAct').selectedIndex = 0;
    document.getElementById('txtDestinoBenef').selectedIndex = 0;
    document.getElementById('bxTipoPlanilla').selectedIndex = 0;


    $("#btnRegistrarEmpresa").prop('disabled', false);
    $("#btnActualizarEmpresa").prop('disabled', true);
    $("#btnEliminarEmpresa").prop('disabled', true);
    $("#btnNuevaEmpresa").prop('disabled', true);
    $("#btnCancelarEmpresa").prop('disabled', false);
}

function Cancelar() { 
    $("#txtnombre").val("");
    $("#txtrazon_social").val("");
    $("#txtsigla").val("");
    $("#txtruc").val("");
    $("#txtdireccion").val("");
    $("#txttelefono").val("");
    $("#txtemail").val("");
    $("#txtcontacto").val("");
    $("#txtdescripcion").val("");
    $("#txtid").val("");
    $("#txtDiasVacaciones").val("");
    $("#txtJornadaLaboral").val("");
    document.getElementById('txtresponsable').selectedIndex = 0;
    document.getElementById('bxestado').selectedIndex = 0;
    document.getElementById('txtTamano').selectedIndex = 0;
    document.getElementById('txtTipoActividad').selectedIndex = 0;
    document.getElementById('txtSector').selectedIndex = 0;
    document.getElementById('txtOrigenCap').selectedIndex = 0;
    document.getElementById('txtConstJuridica').selectedIndex = 0;
    document.getElementById('txtAmbitoAct').selectedIndex = 0;
    document.getElementById('txtDestinoBenef').selectedIndex = 0;
    document.getElementById('bxTipoPlanilla').selectedIndex = 0;


    $("#btnRegistrarEmpresa").prop('disabled', true);
    $("#btnActualizarEmpresa").prop('disabled', true);
    $("#btnEliminarEmpresa").prop('disabled', true);
    $("#btnNuevaEmpresa").prop('disabled', false);
    $("#btnCancelarEmpresa").prop('disabled', true);
}

function BuscarEmpresas() {
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
            "url": "../../models/M01_Configuracion/M01MD01_Empresas/M01MD01_BuscarEmpresas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "txtBuscarNombre": $("#txtBuscarNombre").val(),
                    "txtBuscarRuc": $("#txtBuscarRuc").val()
                });
            }
        },
        "columns": [{
            "data": "id",
            "render": function(data, type, row) {
                return '<a href="javascript:void(0)"  onclick="EditarEmpresa(\'' + data + '\')"><img src="../../../images/editar.png" width="35px" height="35px" ></a>';
            }
        },
        { "data": "nombre" },
        { "data": "ruc" },
        { "data": "responsable" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },

    });

    $('#TablaEmpresas').DataTable(options);
}

/*******************SUBIR IMAGEN FIRMA DIGITAL******************* */
function CargarFirma(e) {
    var file = e.target.files[0],
        imageType = /image.*/;
    var Name = e.target.files[0].name;
    if (!file.type.match(imageType))
        return;
    var reader = new FileReader();
    reader.onload = fileOnload;
    reader.readAsDataURL(file);
}

function fileOnload(e) {
    var image = new Image();
    image.src = e.target.result;
    image.onload = function() {
        if (parseFloat(this.naturalWidth) == 500 && parseFloat(this.naturalHeight) == 300) {
            $('#imgSalida').attr("src", image.src);
            GuardarFirmaServer();
            return;
        } else {
            mensaje_alerta("\u00A1Tama\u00F1o Incorrecto!", "Las medidas deben ser: Anchura = 500px y Altura=300px", "info");
            return;
        }
    };
}

/**********************SUBIR DOCUMENTO ********************************* */
function GuardarFirmaServer() {
    bloquearPantalla("Cargando...");
    var formData = new FormData($("#filesFormFirmaMega")[0]);
    $.ajax({
        type: "POST",
        url: "../../models/M01_Configuracion/M01MD01_Empresas/M01MD01_ProcesosEmpresas.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(dato) {
            $("#fileSubirFirma").val("");
            desbloquearPantalla();
            if (dato.status === "ok") {
                $("#txtFirma").val(dato.mensaje);
                $('#imagensubida').show();
                return;
            } else {
                $("#txtFirma").val(dato.mensaje);
                mensaje_alerta("\u00A1Error!", dato.mensaje, "error");
                return;
            }
        },
        error: function(error) {
            $("#fileSubirFirma").val("");
            console.log(error);
            desbloquearPantalla();
        },
        timeout: 1000 * 60
    });
}