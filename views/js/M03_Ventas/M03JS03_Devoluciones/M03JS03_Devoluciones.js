var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    $('#cbxNotasCreditoList').select2();
	ValidarFechas();
    MostrarLeyenda();
	ListarDevoluciones();
	document.title = "GPROSAC - DEVOLUCIONES";
    ListarDevolucionesReporte();

    //MODAL
    ListarNCDevoluciones();
    ListarNCDevolucionesReport();
    
    $('#btnBuscar').click(function() {
        ListarDevoluciones();
        document.title = "GPROSAC - DEVOLUCIONES";
        $('#TablaReservasReporte').DataTable().ajax.reload();
    });

    $('#btnLimpiar').click(function() {
        $('#txtdocumentoFiltro').val(null).trigger('change');
        document.getElementById('bxFiltroEstado').selectedIndex = 0;
        document.getElementById('bxFiltroTipoCasa').selectedIndex = 0;
        document.getElementById('bxFiltroLote').selectedIndex = 0;
        document.getElementById('bxFiltroVendedor').selectedIndex = 0;
        document.getElementById('bxFiltroEstadoValidacion').selectedIndex = 0;
        $("#txtDesdeFiltro").val("");
        $("#txtHastaFiltro").val("");
        
        ListarDevoluciones();
        document.title = "GPROSAC - REPORTE DEVOLUCIONES";
        $('#TablaReservasReporte').DataTable().ajax.reload();
    });
	
    $('#btnGuardarAdenda').click(function() {
       GuardarAdenda();
     });

    $('#btnNuevaAdenda').click(function() {
       Nuevo();
     });

     $('#btnActualizarAdenda').click(function() {
       ActualizarAdenda();
     });
     
    $('#txtFechaTermino').change(function() {
        ObtenerMeses();
    });

    //MODAL
    $('#cbxNotasCreditoList').change(function() {       
        $("#btnVerNC").prop("disabled",false);
        $("#btnAgregarPagoNC").prop("disabled",false);
    });

    $('#btnVerNC').click(function() {
        var registro = $("#cbxNotasCreditoList").val(); 
        VerReporteNC(registro);
    });

    $('#btnRecargarTabNC').click(function() {
        ListarNCDevoluciones();
        $('#TablaPagosDevueltosReporte').DataTable().ajax.reload();
    });
      
    $('#btnAgregarPagoNC').click(function() {
        AgregarNC();
    });

    $('#btnNuevoPagoNC').click(function() {
        $('#cbxNotasCreditoList').val(null).trigger('change');
        $("#btnVerNC").prop("disabled",true);
        $("#btnAgregarPagoNC").prop("disabled",true);
        ListarNCDevoluciones();
        $('#TablaPagosDevueltosReporte').DataTable().ajax.reload();
    });

}

function ObtenerMeses(){
    
    var fechasol = $("#txtFechaInicio").val();
    var array_fechasol = fechasol.split("-")  //esta linea esta bien y te genera el arreglo
    var anio = parseInt(array_fechasol[0]); // porque repites el nombre dos veces con una basta
    var mes = parseInt(array_fechasol[1]); 
    var dia  = parseInt(array_fechasol[2]);
    
    var fechasol2 = $("#txtFechaTermino").val();
    var array_fechasol2 = fechasol2.split("-")  //esta linea esta bien y te genera el arreglo
    var anio2 = parseInt(array_fechasol2[0]); // porque repites el nombre dos veces con una basta
    var mes2 = parseInt(array_fechasol2[1]); 
    var dia2  = parseInt(array_fechasol2[2]); 
    
    var numberOfMonths;
    numberOfMonths = (anio2 - anio) * 12 + (mes2 - mes);
    
    $("#txtDuracion").val(numberOfMonths);
}


//AGREGAR FECHA INICIO DE PROYECTO Y FECHA ACTUAL
function ValidarFechas(){
    var data = {
       "btnValidarFechas": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Listar.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               $("#txtDesdeFiltro").val(dato.primero);
               $("#txtHastaFiltro").val(dato.ultimo);   
           } 
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

function ListarDevoluciones() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Listar.php";
    var dato = {
        "ReturnListaDevoluciones": true,
        "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
        "txtHastaFiltro": $("#txtHastaFiltro").val(),
        "bxFiltroEstado": $("#bxFiltroEstado").val(),
        "bxFiltroTipoCasa": $("#bxFiltroTipoCasa").val(),
        "bxFiltroLote": $("#bxFiltroLote").val(),
        "bxFiltroVendedor": $("#bxFiltroVendedor").val(),
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "bxFiltroEstadoValidacion": $("#bxFiltroEstadoValidacion").val()
    };
    realizarJsonPost(url, dato, respuestaListarReportes, null, 10000, null);
}

function respuestaListarReportes(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarReportes(dato.data);
}

var getTablaBusquedaCabGeneradoListReport = null;
function LlenarTablaListarReportes(datos) {
    if (getTablaBusquedaCabGeneradoListReport) {
        getTablaBusquedaCabGeneradoListReport.destroy();
        getTablaBusquedaCabGeneradoListReport = null;
    }

    getTablaBusquedaCabGeneradoListReport = $('#TablaReservas').DataTable({
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
                    var html = "";
                    if(row.desc_devolucion == 'VALIDADO'){
                        html = '<button class="btn btn-edit-action"   onclick="AbrirModalAdenda(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> \
                                <button class="btn btn-warning-action"   onclick="RevertirValidacion(\'' + data + '\')" title="Revertir Devoluci\u00F3n"><i class="fas fa-redo"></i></button>';
                    }else{
                        if(row.desc_devolucion == 'NO APLICA'){
                        html = '<button class="btn btn-edit-action"   onclick="AbrirModalAdenda(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> \
                                <button class="btn btn-success-action"   onclick="ValidarDevolucion(\'' + data + '\')" title="Validar Devoluci\u00F3n" disabled><i class="fas fa-check"></i></button>';
                        }else{
                            html = '<button class="btn btn-edit-action"   onclick="AbrirModalAdenda(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> \
                                    <button class="btn btn-success-action"   onclick="ValidarDevolucion(\'' + data + '\')" title="Validar Devoluci\u00F3n"><i class="fas fa-check"></i></button>';
                        }
                    }
                    return html;
                }
            },
            { "data": "fecha_devolucion" },
            {
                "data": "desc_devolucion",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color:'+ row.color_devolucion +';">'+ row.desc_devolucion + '</span>';
                    return html;
                }
            },
            { "data": "inicio" },
            { "data": "codigo_cliente" },
            { "data": "documento" },
            { "data": "cliente" },
            { "data": "nombreLote" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    
                    if(row.bloqueo == 0){
                        if(row.devolucion=='1'){
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color +';">' + row.estado + '</span> ( <span class="badge etiqueta-js" style="background-color:red;"> DEVUELTO </span> )';    
                        }else{
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color +';">' + row.estado + '</span>';
                        }
                    }else{
                        if(row.devolucion=='1'){
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color +';">' + row.estado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.colorMotivo +';">' + row.motivo + '</span> ) ( <span class="badge etiqueta-js" style="background-color:red;"> DEVUELTO </span> )';
                        }else{
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color +';">' + row.estado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.colorMotivo +';">' + row.motivo + '</span> )';
                        }
                    }
                    return html;
                }
            },
            { "data": "tipo_moneda" },
            { "data": "cuota_inicial" },
            { "data": "total" },
            { "data": "casa" },
            { "data": "tipo_casa" },
            { "data": "area" },
			{ "data": "vendedor" }
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

function MostrarLeyenda(){  
    var data = {
       "btnMostrarLeyenda": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if(dato.status=="ok"){

                array = new Array(dato.total);
                let resultado = dato.data;
                for(var i = 0; i < dato.total; i++){
        
                    array[i] = '<span class="badge etiqueta-js" style="background-color:'+ resultado[i]['color'] +';">'+resultado[i]['nombre']+'</span> : <span style="font-size: 11px;">'+resultado[i]['descripcion']+'</span><br>';
                    
                }
                const data =  document.querySelector("#PnlEstados");
                data.innerHTML = array.join('');
        

                $("#PnlLeyenda").show();  

           }else{
                $("#PnlLeyenda").hide();   
           } 
       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

function ValidarDevolucion(id){  
     var data = {
        "btnValidarDevolucion": true,
        "txtIDUSR": $("#txtIDUSR").val(),
        "idVenta": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if(dato.status=="ok"){
                mensaje_alerta("CORRECTO!", "Se declar\u00F3 como VALIDADO a la devoluci\u00F3n seleccionada", "success");
                ListarDevoluciones();
                document.title = "GPROSAC - DEVOLUCIONES";
                $('#TablaReservasReporte').DataTable().ajax.reload();
            }else{
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

function RevertirValidacion(id){    
     var data = {
        "btnRevertirValidacionDev": true,
        "txtIDUSR": $("#txtIDUSR").val(),
        "idVenta": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if(dato.status=="ok"){
                mensaje_alerta("CORRECTO!", "Se ha revertido la validaci\u00F3n de la devoluci\u00F3n.", "success");
                ListarDevoluciones();
                document.title = "GPROSAC - DEVOLUCIONES";
                $('#TablaReservasReporte').DataTable().ajax.reload();
            }else{
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


function AbrirModalAdenda(id){

    //$('#modalAdendas').modal('show');    
     var data = {
        "CargarDatosVentas": true,
        "idVenta": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                var resultado = dato.data; 
                $("#txtIDVENTA_").val(resultado.id);
                $("#txtClienteLote").val(resultado.datos);
                $("#txtFechaVentaLote").val(resultado.fecha);
                $("#txtNombreLote").val(resultado.lote);
                $("#txtMontoVentaLote").val(resultado.total);
                $("#txtMontoPagadoLote").val(resultado.total_pagado);
                $("#txtNroLetras").val(resultado.letras);
                $("#txtFechaRegDevolucion").val(resultado.fec_devolucion);
                ListarAdendas(resultado.id);  
                //Nuevo();     
                $('#modalAdendas').modal('show'); 
                $("#btnActualizarAdenda").hide();
                $("#btnGuardarAdenda").show();

                $("#btnVerNC").prop("disabled",true);
                $("#btnAgregarPagoNC").prop("disabled",true);

                ListarRegNC();
                ListarNCDevoluciones();
                $('#TablaPagosDevueltosReporte').DataTable().ajax.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

//REPORTE 002 JS
function ListarDevolucionesReporte() {
    var options = $.extend(true, {}, defaults, {
        "aoColumnDefs": [{
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
            "url": "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Listar.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnListaDevoluciones": true,
                    "txtDesdeFiltro": $("#txtDesdeFiltro").val(),
                    "txtHastaFiltro": $("#txtHastaFiltro").val(),
                    "bxFiltroEstado": $("#bxFiltroEstado").val(),
					"bxFiltroTipoCasa": $("#bxFiltroTipoCasa").val(),
					"bxFiltroLote": $("#bxFiltroLote").val(),
					"bxFiltroVendedor": $("#bxFiltroVendedor").val(),
                    "bxFiltroEstadoValidacion": $("#bxFiltroEstadoValidacion").val()
                });
            }
        },
        "columns": [
            { "data": "fecha_devolucion" },
            {
                "data": "desc_devolucion",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color:'+ row.color_devolucion +';">'+ row.desc_devolucion + '</span>';
                    return html;
                }
            },
            { "data": "inicio" },
            { "data": "codigo_cliente" },
            { "data": "documento" },
            { "data": "cliente" },
            { "data": "nombreLote" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    
                    if(row.bloqueo == 0){
                        if(row.devolucion=='1'){
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color +';">' + row.estado + '</span> ( <span class="badge etiqueta-js" style="background-color:red;"> DEVUELTO </span> )';    
                        }else{
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color +';">' + row.estado + '</span>';
                        }
                    }else{
                        if(row.devolucion=='1'){
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color +';">' + row.estado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.colorMotivo +';">' + row.motivo + '</span> ) ( <span class="badge etiqueta-js" style="background-color:red;"> DEVUELTO </span> )';
                        }else{
                            html = '<span class="badge etiqueta-js" style="background-color:'+ row.color +';">' + row.estado + '</span> ( <span class="badge etiqueta-js" style="background-color:'+ row.colorMotivo +';">' + row.motivo + '</span> )';
                        }
                    }
                    return html;
                }
            },
            { "data": "tipo_moneda" },
            { "data": "cuota_inicial" },
            { "data": "total" },
            { "data": "casa" },
            { "data": "tipo_casa" },
            { "data": "area" },
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
            }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }

    });

    tablaEmpresas = $('#TablaReservasReporte').DataTable(options);
}


function Nuevo(){
    document.getElementById('bxEstadoAdenda').selectedIndex = 0;
    document.getElementById('bxTipoAdenda').selectedIndex = 0;
    $("#txtContrato").val("");
    $("#txtNroAdenda").val("");
    $("#txtImporteSolicitado").val("");
    $("#txtFechaInicio").val("");
    $("#txtDuracion").val("");
    $("#txtFechaTermino").val("");
    $("#txtReferencia").val("");
    $("#txtJustificacion").val("");
    $("#txtObservacion").val("");
    $("#fichero").val("");
}


function ValidarCamposAdenda() {
    var flat = true;
    if ($("#txtNroAdenda").val() === "" || $("#txtNroAdenda").val() === null) {
        $("#txtNroAdenda").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el n\u00FAmero de adenda.", "info");
        flat = false;
    } else if ($("#txtImporteSolicitado").val() === "" || $("#txtImporteSolicitado").val() === null) {
        $("#txtImporteSolicitado").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el importe solicitado.", "info");
        flat = false;
    } else if ($("#txtFechaInicio").val() === "" || $("#txtFechaInicio").val() === null) {
        $("#txtFechaInicio").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la fecha de inicio de la adenda.", "info");
        flat = false;
    } else if ($("#txtDuracion").val() === "" || $("#txtDuracion").val() === null) {
        $("#txtDuracion").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el tiempo de duraci\u00F3n de la adenda.", "info");
        flat = false;
    } else if ($("#txtJustificacion").val() === "" || $("#txtJustificacion").val() === null) {
        $("#txtJustificacion").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la justificaci\u00F3n de la adenda.", "info");
        flat = false;
    }
    return flat;
}

function GuardarAdenda(){

    if(ValidarCamposAdenda()){   
        var data = {
            "btnGuardarAdenda": true,
            "txtIDVENTA_": $("#txtIDVENTA_").val(),
            "txtIDADENDA_": $("#txtIDADENDA_").val(),
            "txtContrato": $("#txtContrato").val(),
            "txtNroAdenda": $("#txtNroAdenda").val(),
            "bxEstadoAdenda": $("#bxEstadoAdenda").val(),
            "bxTipoAdenda": $("#bxTipoAdenda").val(),
            "txtImporteSolicitado": $("#txtImporteSolicitado").val(),
            "txtFechaInicio": $("#txtFechaInicio").val(),
            "txtDuracion": $("#txtDuracion").val(),
            "txtFechaTermino": $("#txtFechaTermino").val(),
            "txtReferencia": $("#txtReferencia").val(),
            "txtJustificacion": $("#txtJustificacion").val(),
            "txtObservacion": $("#txtObservacion").val(),
            "fichero": $("#fichero").val(),
            "txtIDUSR": $("#txtIDUSR").val(),
            "txtFechaRegDevolucion": $("#txtFechaRegDevolucion").val(),
        };
        $.ajax({
            type: "POST",
            url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                    //console.log(dato);
                    if(dato.status=="ok"){
                        EnviarAdjunto(dato.name);
                        mensaje_alerta("Correcto!", dato.data, "success");
                        ListarAdendas(dato.idventa);
                        $("#txtIDADENDA_").val("");
                    }else{
                        mensaje_alerta("Error!", dato.data, "info");
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

function EnviarAdjunto(nombre){

   var file_data = $('#fichero').prop('files')[0];   
    var form_data = new FormData(); 
    var dataa = nombre;                  
    form_data.append('file', file_data);
    form_data.append('data', dataa);
    //alert(form_data);                             
    $.ajax({
        url: '../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_SubirArchivo.php', // point to server-side PHP script 
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


function ListarAdendas(idventa) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php";
    var dato = {
        "ReturnListaAdendas": true,
        "idventa": idventa
    };
    realizarJsonPost(url, dato, respuestaListarAdendasReportes, null, 10000, null);
}

function respuestaListarAdendasReportes(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarAdendasReportes(dato.data);
}

var getTablaBusquedaAdendasListReport = null;
function LlenarTablaListarAdendasReportes(datos) {
    if (getTablaBusquedaAdendasListReport) {
        getTablaBusquedaAdendasListReport.destroy();
        getTablaBusquedaAdendasListReport = null;
    }

    getTablaBusquedaAdendasListReport = $('#TablaAdendasContratos').DataTable({
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
                    return '<button class="btn btn-edit-action"   onclick="AbrirModalEditar(\'' + data + '\')" title="Editar Adenda"><i class="fas fa-pencil-alt"></i></button> \ <button class="btn btn-delete-action"   onclick="EliminarAdendaAdj(\'' + data + '\')" title="Eliminar Adenda"><i class="fas fa-trash"></i></button>';
                }
            },
            { 
                "data": "id",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.contrato==""){
                        html="Falta adjunto";
                    }else{
                        //html='<a href="'+"../../M03_Ventas/M03SM03_Devoluciones/archivos/"+row.contrato+'" download="'+row.cliente+'_ADENDA">Documento <i class="fas fa-arrow-alt-circle-down"></i></a>';
                        
                        html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="VerDocuments(\'' + data + '\')"><i class="fas fa-file-pdf"></i> Documento</a>';
                    }
                    return html;
                }
            },	
            { "data": "nro_adenda" },
            { "data": "tipo" },
            { "data": "importe_solicitado" },
            { "data": "fecha_inicio" },
            { "data": "duracion" },
            { "data": "fecha_termino" }
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


function VerDocuments(id) {  
  var data = {
    btnMostrarDocumento: true,
    idRegistro: id,
  };
  $.ajax({
    type: "POST",
    url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
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
        $("#modalVerDocumentoDevolucion").modal("show");        
          
      } 
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus + ": " + errorThrown);
      desbloquearPantalla();
    },
  });
}

function AbrirModalEditar(idadenda){

    //$('#modalAdendas').modal('show');    
     var data = {
        "btnEditarAdenda": true,
        "idadenda": idadenda
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                $("#btnActualizarAdenda").show();
                $("#btnGuardarAdenda").hide();
                $("#txtIDADENDA_").val(dato.data.id),
                $("#txtIDVENTA_").val(dato.data.id_venta),
                $("#txtNroAdenda").val(dato.data.nro_adenda),
                $("#bxEstadoAdenda").val(dato.data.estado),
                $("#bxTipoAdenda").val(dato.data.tipo),
                $("#txtImporteSolicitado").val(dato.data.importe_solicitado),
                $("#txtFechaInicio").val(dato.data.fecha_inicio),
                $("#txtDuracion").val(dato.data.duracion),
                $("#txtFechaTermino").val(dato.data.fecha_termino),
                $("#txtReferencia").val(dato.data.referencia),
                $("#txtJustificacion").val(dato.data.justificacion),
                $("#txtObservacion").val(dato.data.observacion)
                           
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function EliminarAdendaAdj(idadenda){

    var mensaje = 'è¢ƒSeguro(a) que desea eliminar la adenda seleccionada?';
    mensaje_eliminar_parametro(mensaje, EliminarAdendaDev, idadenda);
}

function EliminarAdendaDev(idadenda){
    //$('#modalAdendas').modal('show');    
     var data = {
        "btnEliminarAdenda": true,
        "idadenda": idadenda,
        "txtIDVENTA_": $("#txtIDVENTA_").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if(dato.status=="ok"){
                mensaje_alerta("CORRECTO!", dato.data, "success");
                ListarAdendas(dato.idVenta);
            }else{
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


function ActualizarAdenda(){

    if(ValidarCamposAdenda()){   
        var data = {
            "btnActualizarAdenda": true,
            "txtIDVENTA_": $("#txtIDVENTA_").val(),
            "txtIDADENDA_": $("#txtIDADENDA_").val(),
            "txtContrato": $("#txtContrato").val(),
            "txtNroAdenda": $("#txtNroAdenda").val(),
            "bxEstadoAdenda": $("#bxEstadoAdenda").val(),
            "bxTipoAdenda": $("#bxTipoAdenda").val(),
            "txtImporteSolicitado": $("#txtImporteSolicitado").val(),
            "txtFechaInicio": $("#txtFechaInicio").val(),
            "txtDuracion": $("#txtDuracion").val(),
            "txtFechaTermino": $("#txtFechaTermino").val(),
            "txtReferencia": $("#txtReferencia").val(),
            "txtJustificacion": $("#txtJustificacion").val(),
            "txtObservacion": $("#txtObservacion").val(),
            "fichero": $("#fichero").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                    //console.log(dato);
                    if(dato.status=="ok"){
                        if(dato.name != 'ninguno'){
                            EnviarAdjunto(dato.name);
                        }
                        mensaje_alerta("Correcto!", dato.data, "success");
                        ListarAdendas(dato.idventa);
                    }else{
                        mensaje_alerta("Error!", dato.data, "info");
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



function AbrirModalAprobar(idadenda){
    var mensaje = 'Recuerde que al APROBAR la adenda automaticamente el estado actual del lote vendido sera devuelto al estado libre y podra ser vendido a otro cliente. Desea aprobar la adenda?';
    mensaje_eliminar_parametro(mensaje, AprobarAdenda, idadenda);
}


function AprobarAdenda(idadenda){

    //$('#modalAdendas').modal('show');    
    /* var data = {
        "btnAprobarAdenda": true,
        "idadenda": idadenda
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
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
    });   */
}


function AbrirModalRechazar(idadenda){
    var mensaje = 'Recuerde que si RECHAZA la adenda no tendra algun efecto en el contrato vigente del lote. è¢ƒsea rechazar la adenda?';
    mensaje_eliminar_parametro(mensaje, RechazarAdenda, idadenda);
}

function RechazarAdenda(idadenda){

    //$('#modalAdendas').modal('show');    
   /*  var data = {
        "btnRechazarAdenda": true,
        "idadenda": idadenda
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
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
    });  */ 
}



function ListarRegNC(){
    var url = '../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php';
    var datos = {
        "ReturnListaNC": true,
        "idVenta": $('#txtIDVENTA_').val()
    }
    llenarCombo(url, datos, "cbxNotasCreditoList");
}


function ListarNC() {
    bloquearPantalla("Consultando...");
    var url = "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php";
    var dato = {
        "ListarNotasCredito": true,
        "idventa": idventa
    };
    realizarJsonPost(url, dato, respuestaListarNCReportes, null, 10000, null);
}

function respuestaListarNCReportes(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarNCReportes(dato.data);
}

var getTablaBusquedaListaNC = null;
function LlenarTablaListarNCReportes(datos) {
    if (getTablaBusquedaListaNC) {
        getTablaBusquedaListaNC.destroy();
        getTablaBusquedaListaNC = null;
    }

    getTablaBusquedaListaNC = $('#TablaPagosDevueltos').DataTable({
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
                    return '<button class="btn btn-edit-action"   onclick="AbrirModalEditar(\'' + data + '\')" title="Editar Adenda"><i class="fas fa-pencil-alt"></i></button> \ <button class="btn btn-delete-action"   onclick="EliminarAdendaAdj(\'' + data + '\')" title="Eliminar Adenda"><i class="fas fa-trash"></i></button>';
                }
            },
            { 
                "data": "id",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.contrato==""){
                        html="Falta adjunto";
                    }else{
                        //html='<a href="'+"../../M03_Ventas/M03SM03_Devoluciones/archivos/"+row.contrato+'" download="'+row.cliente+'_ADENDA">Documento <i class="fas fa-arrow-alt-circle-down"></i></a>';
                        
                        html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="VerDocuments(\'' + data + '\')"><i class="fas fa-file-pdf"></i> Documento</a>';
                    }
                    return html;
                }
            },	
            { "data": "nro_adenda" },
            { "data": "tipo" },
            { "data": "importe_solicitado" },
            { "data": "fecha_inicio" },
            { "data": "duracion" },
            { "data": "fecha_termino" }
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




//MODAL
function VerReporteNC(registro){  
    var data = {
        "btnVerReporteNC": true,
        "idfac_cab": registro
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if(dato.status=="ok"){
                    window.open(dato.ruta, '_blank');
                }else{
                    mensaje_alerta("Error!", "No se encontr\u00F3 el reporte del comprobante seleccionado dentro de aquellos que fueron emitidos en el m\u00F3dulo de Facturaci\u00F3.", "info");
                }
                            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    }); 
    
}



function ListarNCDevoluciones() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php";
    var dato = {
        "ReturnListarNCDevolucion": true,
        "idventa": $("#txtIDVENTA_").val()
    };
    realizarJsonPost(url, dato, respuestaListarNCRep, null, 10000, null);
}

function respuestaListarNCRep(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarNCReport(dato.data);
}

var getTablaBusquedaListNCReport = null;
function LlenarTablaListarNCReport(datos) {
    if (getTablaBusquedaListNCReport) {
        getTablaBusquedaListNCReport.destroy();
        getTablaBusquedaListNCReport = null;
    }

    getTablaBusquedaListNCReport = $('#TablaPagosDevueltos').DataTable({
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
                    var html = "";                   
                    html = '<button class="btn btn-delete-action"   onclick="EliminarNCDev(\'' + data + '\')" title="Editar"><i class="fas fa-trash"></i></button>';
                    return html;
                }
            },
            { "data": "lote" },
            { "data": "letra" },
            { "data": "serie" },
            { "data": "numero" },
            {
                "data": "ruta",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<a class="btn btn-danger-action" href="' + row.ruta + '" target="_blank"><i class="fas fa-file-pdf"></i> Ver</a>';
                    return html;
                }
            },
            { "data": "devuelto" },
            { "data": "moneda" },
            { "data": "serie_ref" },
            { "data": "numero_ref" },
            { "data": "tipo_doc_ref" },
            {
                "data": "ruta_ref",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<a class="btn btn-danger-action" href="' + row.ruta_ref + '" target="_blank"><i class="fas fa-file-pdf"></i> Ver</a>';
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

function ListarNCDevolucionesReport() {
    var options = $.extend(true, {}, defaults, {
        "aoColumnDefs": [{
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
            "url": "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnListarNCDevolucion": true,
                    "idventa": $("#txtIDVENTA_").val()
                });
            }
        },
        "columns": [
            { "data": "lote" },
            { "data": "letra" },
            { "data": "serie" },
            { "data": "numero" },
            { "data": "ruta" },
            { "data": "devuelto" },
            { "data": "moneda" },
            { "data": "serie_ref" },
            { "data": "numero_ref" },
            { "data": "tipo_doc_ref" },
            { "data": "ruta_ref" }
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

    tablaNCReport = $('#TablaPagosDevueltosReporte').DataTable(options);
}

function EliminarNCDev(idreg){  
    
    var data = {
        "btnEliminarNCDev": true,
        "idregistro": idreg,
        "txtIDUSR": $("#txtIDUSR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if(dato.status=="ok"){           
                    mensaje_alerta("Correcto!", "Se elimin\u00F3 la nota de cr\u00E9dito seleccionada.", "success");   
                    ListarRegNC();          
                    ListarNCDevoluciones();
                    $('#TablaPagosDevueltosReporte').DataTable().ajax.reload();
                }else{
                    mensaje_alerta("Error!", dato.data, "info");
                }
                            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    }); 

    
}


//AGREGAR NC
function ValidarCampoNC(){
    var flat = true;
    if ($("#cbxNotasCreditoList").val() === "" || $("#cbxNotasCreditoList").val() === null) {
        $("#cbxNotasCreditoList").focus();
        mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccionar una Nota de Cr\u00E9dito del listado.", "info");  
        flat = false;

    } 
    return flat;
}

function AgregarNC(){  
    if(ValidarCampoNC()){
        var data = {
            "btnAgregarNC": true,
            "cbxNotasCreditoList": $("#cbxNotasCreditoList").val(),
            "txtIDUSR": $("#txtIDUSR").val()
        };
        $.ajax({
            type: "POST",
            url: "../../models/M03_Ventas/M03MD03_Devoluciones/M03MD03_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                    //console.log(dato);
                    if(dato.status=="ok"){           
                        mensaje_alerta("Correcto!", "Se agreg\u00F3 la nota de cr\u00E9dito seleccionada.", "success");    
                        ListarRegNC();         
                        ListarNCDevoluciones();
                        $('#TablaPagosDevueltosReporte').DataTable().ajax.reload();
                    }else{
                        mensaje_alerta("Error!", "No se pudo completar la operaci\u00F3n. Intentar nuevamente.", "info");
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