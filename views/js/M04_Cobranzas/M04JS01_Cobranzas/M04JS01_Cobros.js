var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    LlenarProyectos();
    LLenarZonas();
    ValidarFechas();
    ValidarFechasRES();
    ValidarFechasVRES();
    ValidarFechasValidados();
    
   BuscarPagosGenerados();
    BuscarPagosGeneradosRES();
   BuscarPagosGeneradosRES2();
   //LlenarTablaPagosGeneradosReporte();
   //InicializarAtributosTablaBusquedaCabActividades();
   Inicializar();

   //BuscarPagosGenerados2();
   LlenarTablaPagosGeneradosReporte2();
   LlenarTablaPagosGeneradosReporteRES2();
  
    $('#btnBuscar').click(function() {
        BuscarPagosGenerados();
        //$('#TablaPagosReporte').DataTable().ajax.reload();   
    });

     $('#btnBuscarRES').click(function() {
        BuscarPagosGeneradosRES();
        //$('#TablaPagosReporte').DataTable().ajax.reload();   
    });

     $('#btnBuscarR').click(function() {
        BuscarPagosGenerados2();
        $('#TablaPagosRealizadosReporte').DataTable().ajax.reload();   
    });
    
    $('#btnBuscarVRES').click(function() {
        BuscarPagosGeneradosRES();
        //$('#TablaPagosReporte').DataTable().ajax.reload();   
    });
    
    $('#btnBuscarVRES').click(function() {
        BuscarPagosGeneradosRES2();
        //$('#TablaPagosRealizadosReporteVRES').DataTable().ajax.reload();   
    });

    $('#btnGuardarVerificacion').click(function() {
        GuardarDatosVerificacion();        
    });    

    $('#btnContinuarVerificacion').click(function() {
        $("#PagosRealizados").hide();
        $("#ConformidadPagos").show();
        BuscarPagosRealizados2();
        EjecutarBusquedaLetra();       
    });  

    $('#btnAtras').click(function() {
        $("#PagosRealizados").show();
        $("#ConformidadPagos").hide();
        BuscarPagosRealizados();       
    }); 

    $('#btnPagosConformes').click(function() {
        $("#PagosRealizados").hide();
        $("#ConformidadPagos").hide();
        $("#PagosVerificados").show(); 
        BuscarPagosRealizados3();      
    });  

     $('#btnAtras2').click(function() {
        $("#PagosRealizados").hide();
        $("#ConformidadPagos").show();
        BuscarPagosRealizados2();
        $("#PagosVerificados").hide();        
    });  

     $('#bxFiltroLetraCobros').change(function () {
        EjecutarBusquedaLetra();
    });

    $('#btnAprobar').click(function() {        
        AprobarPago();       
    });

    $('#btnRechazar').click(function() {
        RechazarPago();
    });
  
    $('#btnLimpiar').click(function() {
         document.getElementById('bxFiltroZonaEC').selectedIndex = 0;
         document.getElementById('bxFiltroManzanaEC').selectedIndex = 0;
         document.getElementById('bxFiltroLoteEC').selectedIndex = 0;
         document.getElementById('bxFiltroEV').selectedIndex = 0;
         document.getElementById('bxFiltroEstadoEC').selectedIndex = 0;
         $('#txtFiltroDocumentoEC').val(null).trigger('change');
         ValidarFechas();
         BuscarPagosGenerados();
         //$('#TablaPagosReporte').DataTable().ajax.reload();   
    });
    
     $('#btnLimpiarRES').click(function() {
         document.getElementById('bxFiltroZonaRES').selectedIndex = 0;
         document.getElementById('bxFiltroManzanaRES').selectedIndex = 0;
         document.getElementById('bxFiltroLoteRES').selectedIndex = 0;
         document.getElementById('bxFiltroEVRES').selectedIndex = 0;
         document.getElementById('bxFiltroEstadoRES').selectedIndex = 0;
         $('#txtFiltroDocumentoRES').val(null).trigger('change');
         ValidarFechasRES();
         BuscarPagosGeneradosRES();
         //$('#TablaPagosReporte').DataTable().ajax.reload();   
    });
    
    $('#btnLimpiarVRES').click(function() {
         document.getElementById('bxFiltroZonaVRES').selectedIndex = 0;
         document.getElementById('bxFiltroManzanaVRES').selectedIndex = 0;
         document.getElementById('bxFiltroLoteVRES').selectedIndex = 0;
         $('#txtFiltroDocumentoVRES').val(null).trigger('change');
         ValidarFechasVRES();
         BuscarPagosGeneradosRES2();
         //$('#TablaPagosReporte').DataTable().ajax.reload();   
    });
    
     $('#btnLimpiarR').click(function() {
         document.getElementById('bxFiltroZonaPR').selectedIndex = 0;
         document.getElementById('bxFiltroManzanaPR').selectedIndex = 0;
         document.getElementById('bxFiltroLotePR').selectedIndex = 0;
         $('#txtFiltroDocumentoPR').val(null).trigger('change');
         ValidarFechasValidados();
         //BuscarPagosGenerados2();
        $('#TablaPagosRealizadosReporte').DataTable().ajax.reload();   
    });


    $('#btnNuevoPago').click(function() {
     IrPagos();
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
    



    $('#bxFiltroProyectoPR').change(function () {
        $("#bxFiltroZonaPR").val("");
        $("#bxFiltroManzanaPR").val("");
        var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
        var datos = {
            "ListarZonas": true,
            "idproyecto": $('#bxFiltroProyectoPR').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaPR");
        document.getElementById('bxFiltroManzanaPR').selectedIndex = 0;
    });

    $('#bxFiltroZonaPR').change(function () {
        $("#bxFiltroManzanaPR").val("");
        $("#bxFiltroLotePR").val("");
        var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaPR').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaPR");
        document.getElementById('bxFiltroLotePR').selectedIndex = 0;
    });

    $('#bxFiltroManzanaPR').change(function () {
        $("#bxFiltroLotePR").val("");
        var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaPR').val()
        };
        llenarCombo(url, datos, "bxFiltroLotePR");
    });
    
    
    /*======== BOTONES DE GUARDAR Y VALIDACION DE PAGOS RESERVAS  =========*/ 

    $('#btnGuardarPagoPR').click(function() {
      ActualizarPagoReserva();
    });
    
    $('#btnAprobarPagoReserva').click(function() {
      ValidarPagoReserva();
    });

  
}


function ValidarFechas(){
     var data = {
        "btnValidarFechas2": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                $("#txtFecIniFiltro").val(dato.primero);
                $("#txtFecFinFiltro").val(dato.ultimo);   
                LlenarInformacion();
            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function ValidarFechasRES(){
     var data = {
        "btnValidarFechas2": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                $("#txtFecIniFiltroRES").val(dato.primero);
                $("#txtFecFinFiltroRES").val(dato.ultimo); 
            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function ValidarFechasVRES(){
     var data = {
        "btnValidarFechas2": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                $("#txtFecIniFiltroVRES").val(dato.primero);
                $("#txtFecFinFiltroVRES").val(dato.ultimo); 
            } 

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function Inicializar(){
    $("#ConformidadPagos").hide();
    $("#PagosVerificados").hide();    
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


function LlenarProyectos() {
    var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoPR");    
}

function LLenarZonas() {
    var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoPR').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaPR");
}

/******************************************LISTAR BUSQUEDA CABECERA**************************************** */

function BuscarPagosGenerados() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnListaPagos3": true,
        "txtFiltroDocumentoEC": $("#txtFiltroDocumentoEC").val(),
        "bxFiltroLoteEC": $("#bxFiltroLoteEC").val(),
        "bxFiltroEV": $("#bxFiltroEV").val(),
        "bxFiltroEstadoEC": $("#bxFiltroEstadoEC").val(),
        "txtFecIniFiltro": $("#txtFecIniFiltro").val(),
        "txtFecFinFiltro": $("#txtFecFinFiltro").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosGenerados, null, 10000, null);
}

function respuestaBuscarPagosGenerados(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaActividadesGenerados(dato.data);
}

var getTablaBusquedaCabGenerado = null;
function LlenarTabalaActividadesGenerados(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaPagos').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "0%"
            },
            {
                'aTargets': [1],
                'ordering': false,
                'width': "0%"
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
                    if(row.descVisto_bueno == "POR VALIDAR"){
                        html = '<button class="btn btn-edit-action" onclick="AbrirModalValidarPagos(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                    }else{
                        html = '';
                    }
                    return html;
                        
                }
            },
            {
                "data": "visto_bueno",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.colorVisto_bueno +'; color: white; font-weight: bold;">' + row.descVisto_bueno + '</span>';
                    return html;
                } 
            },
            { "data": "fecha" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.colorEstado +'; color: white; font-weight: bold;">' + row.descEstado + '</span>';
                    return html;
                } 
            },
            { "data": "cliente" }, 
            { "data": "lote" },            
            { "data": "fechaVencimiento" },
            { "data": "letra" },
            { "data": "mora" },
            { "data": "importe_pago" },
            { "data": "nro_operacion" }
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

function LlenarTablaPagosGeneradosReporte() {
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
            "url": "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnListaPagos3": true,
                    "txtFiltroDocumentoEC": $("#txtFiltroDocumentoEC").val(),
                    "bxFiltroLoteEC": $("#bxFiltroLoteEC").val(),
                    "bxFiltroEV": $("#bxFiltroEV").val(),
                    "bxFiltroEstadoEC": $("#bxFiltroEstadoEC").val(),
                    "txtFecIniFiltro": $("#txtFecIniFiltro").val(),
                    "txtFecFinFiltro": $("#txtFecFinFiltro").val()
                });
            }
        },
        "columns": [
            { "data": "lote" },
            { "data": "fechaVencimiento" },
            { "data": "fecha" },
            { "data": "mora" },
            { "data": "letra" },
            { "data": "tipo_moneda" },
            { "data": "monto" },
            { "data": "descEstado"},
            { "data": "descVisto_bueno"}
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

    $('#TablaPagosReporte').DataTable(options);
}

function AbrirModalValidarPagos(id) {
   
    //$('#modalCobros').modal('show');
    bloquearPantalla("Buscando...");
    var data = {
        "btnCargarDatosCliente": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {
                var resultado = dato.data;
                $("#txtidPago").val(dato.id_pago);
                $("#txtApellidoNombre").val(resultado.datos);
                $("#txtTelefono").val(resultado.celular);                
                $("#txtCorreo").val(resultado.correo);
                $("#txtLote").val(resultado.lote);
                $("#modalCobros").modal('show'); 
                LlenarCuotasLista(dato.idventa, dato.id_pago);
                BuscarPagosRealizados();
                BuscarPagosRealizados2(); 
                //BuscarPagosAprobados();
                
                $("#PagosRealizados").show();
                $("#ConformidadPagos").hide();
                
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

/************************** TABLAS POPUP **************************/

function BuscarPagosRealizados() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnPagosRealizados": true,
        "txtidPago": $("#txtidPago").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosRealizadosGenerados, null, 10000, null);   
}

function respuestaBuscarPagosRealizadosGenerados(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaPagosRealizadosGenerados(dato.data);
}

var getTablaPagosRealizados = null;
function LlenarTabalaPagosRealizadosGenerados(datos) {
    if (getTablaPagosRealizados) {
        getTablaPagosRealizados.destroy();
        getTablaPagosRealizados = null;
    }

    getTablaPagosRealizados = $('#TablaPagosRealizadosCobros').DataTable({
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
                    var html="";
                    if(row.observacion=='1'){
                        html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="CargaComprobante(\'' + data + '\')" title="Cargar Comprobante"><i class="fas fa-pencil-alt"></i></a> \ <a href="javascript:void(0)" class="btn btn-warning-action" onclick="ObservacionesPago(\'' + data + '\')" title="Observar Pago"><i class="fas fa-exclamation"></i></a>';
                    }else{
                        html = '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="AbrirDatosPagos(\'' + data + '\')" title="Cargar Comprobante"><i class="fas fa-pencil-alt"></i></a>';
                    }
                    return html;
                }
            },
            { "data": "fecha" },
            { "data": "tipo_comprobante" },
            { "data": "nro_boleta" },
            { "data": "moneda_pago" },
            { "data": "tipo_cambio" },
            { "data": "importe_pago" },
            { "data": "medio_pago" },
            { "data": "nro_operacion" },
            { "data": "banco" },
            { 
                "data": "voucher",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.voucher==""){
                        html="Ninguno";
                    }else{
                        //html='<a href="'+"../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+row.voucher+'" download="'+row.fech_pago+"_"+row.lote_nom+"_"+row.nom_cliente+'_PAGOLETRA">Voucher <i class="fas fa-arrow-alt-circle-down"></i></a>';
                    
                        html = '<a href="javascript:void(0)" onclick="VerVoucher(\'' + row.id + '\')">Voucher</a>';
                    }
                    return html;
                }
            },
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

function ObservacionesPago(id) {  
    var data = {
      btnVerObservaciones: true,
      "idRegistro": id
    };
    $.ajax({
      type: "POST",
      url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            var resultado = dato.data;
            
                if(resultado.respuesta == ""){
                    $("#btnEnviarRespuesta").show();
                    $("#txtRespuesta").prop('disabled', false);
                }else{
                    $("#btnEnviarRespuesta").hide();
                    $("#txtRespuesta").prop('disabled', true);
                }
            
            $("#_ID_PAGO_DETALLE").val(resultado.id);
            $("#txtObservacion").val(resultado.observacion);
            $("#txtRespuesta").val(resultado.respuesta);
            
            $("#modalVerObservaciones").modal("show"); 
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
 }

 function ResponderObservacion(){
    var data = {
        "btnResponderObservacion": true,
        "_ID_PAGO_DETALLE": $("#_ID_PAGO_DETALLE").val(),
        "txtRespuesta": $("#txtRespuesta").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
                    $("#modalVerObservaciones").modal("hide");                   
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
    
                } else {
                    mensaje_alerta("\u00A1ERROR!", dato.data, "info");
                }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });   

}


function VerVoucher(id) {  
    var data = {
      btnMostrarVoucher: true,
      idRegistro: id,
    };
    $.ajax({
      type: "POST",
      url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            if(dato.formato == "jpeg" || dato.formato == "jpg"){
                var html = "";
                var documento = "archivos/"+dato.voucher+"";
                html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                $("#my_img_doc").html(html);
                $("#modalVerVoucher").modal("show");   
            }else{
                if(dato.formato == "png"){
                    var html = "";
                    var documento = "archivos/"+dato.voucher+"";
                    html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                    $("#my_img_doc").html(html);
                    $("#modalVerVoucher").modal("show");   
                }else{
                    var html = "";
                    var documento = "archivos/"+dato.voucher+"";
                    html += "<object class='pdfview' type='application/pdf' data='" +documento +"' style='width: 100%'></object> ";
                    $("#my_img_doc").html(html);
                    $("#modalVerVoucher").modal("show");        
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


function AbrirDatosPagos(id) {
   
    //$('#modalCobros').modal('show');
    bloquearPantalla("Buscando...");
    var data = {
        "btnCargarDatosPago": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                var resultado = dato.data;
                $("#txtID_PAGO").val(resultado.id);
                $("#txtFechaPagoDetalle").val(resultado.fecha);
                $("#bxMedioPagoDetalle").val(resultado.medio_pago);                
                $("#bxTipoComprobanteDetalle").val(resultado.tipo_comprobante);
                $("#txtSerieBoletaDetalle").val(resultado.serie);
                $("#txtNumeroBoletaDetalle").val(resultado.numero);
                $("#txtImportePagadoDetalle").val(resultado.monto_pagado);
                $("#bxAgenciaBancariaDetalle").val(resultado.banco);
                $("#bxTipoMonedaDetalle").val(resultado.moneda_pago);
                $("#txtTipoCambioDetalle").val(resultado.tipo_cambio);
                $("#txtNroOperacionDetalle").val(resultado.nro_operacion);   
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


function GuardarDatosVerificacion() {
   
    //$('#modalCobros').modal('show');
    bloquearPantalla("Buscando...");
    var data = {
        "btnGuardarDatosVerificacion": true,
        "txtID_PAGO": $("#txtID_PAGO").val(),
        "txtFechaPagoDetalle": $("#txtFechaPagoDetalle").val(),
        "bxMedioPagoDetalle": $("#bxMedioPagoDetalle").val(),                
        "bxTipoComprobanteDetalle": $("#bxTipoComprobanteDetalle").val(),
        "txtImportePagadoDetalle": $("#txtImportePagadoDetalle").val(),
        "bxAgenciaBancariaDetalle": $("#bxAgenciaBancariaDetalle").val(),
        "bxTipoMonedaDetalle": $("#bxTipoMonedaDetalle").val(),
        "txtTipoCambioDetalle": $("#txtTipoCambioDetalle").val(),
        "txtNroOperacionDetalle": $("#txtNroOperacionDetalle").val(),
        "file": $("#fichero").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {
                /*if(dato.valida_file=="si"){
                    ActualizarAdjunto();
                }*/
                mensaje_alerta("\u00A1Correcto!", "Se guardaron los cambios realizados en el pago.", "success");
                BuscarPagosRealizados();  
            } else {
                mensaje_alerta("\u00A1Error!", dato.data, "info");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });       
}

function ActualizarAdjunto(){

   var file_data = $('#fichero').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    //alert(form_data);                             
    $.ajax({
        url: '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_ActualizarArchivo.php', // point to server-side PHP script 
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

function LlenarCuotasLista(idventa, idpago) {
    var url = '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php';
    var datos = {
        "ListarCuotasCronograma": true,
        "idventa": idventa,
        "idpago": idpago
    }
    llenarCombo(url, datos, "bxFiltroLetraCobros");    
}

function BuscarPagosAprobados() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnPagosAprobados": true,
        "txtidPago": $("#txtidPago").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosAprobadosGenerados, null, 10000, null);
}

function respuestaBuscarPagosAprobadosGenerados(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaPagosAprobadosGenerados(dato.data);
}

var getTablaPagosAprobadosGenerado = null;
function LlenarTabalaPagosAprobadosGenerados(datos) {
    if (getTablaPagosAprobadosGenerado) {
        getTablaPagosAprobadosGenerado.destroy();
        getTablaPagosAprobadosGenerado = null;
    }

    getTablaPagosAprobadosGenerado = $('#TablaPagosAprobados').DataTable({
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
                    return '<button class="btn btn-delete-action"   onclick="AbrirDetallePagos(\'' + data + '\')"><i class="fas fa-trash"></i></button>';
                }
            },
            { "data": "fecha" },
            { "data": "tipo_comprobante" },
            { "data": "nro_boleta" },
            { "data": "moneda_pago" },
            { "data": "tipo_cambio" },
            { "data": "importe_pago" },
            { "data": "medio_pago" },
            { "data": "nro_operacion" },
            { "data": "nro_boleta",
                "render": function(data, type, row) {
                    return '<a href="'+row.adjunto+'" download="voucher">Voucher</a>';
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

function AbrirDetallePagos(id) {
   
    //$('#modalCobros').modal('show');
    bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionaDetallePagos": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                var resultado = dato.data;
                $("#txtidPago").val(resultado.id);
                $("#txtApellidoNombre").val(resultado.datos);
                $("#txtTelefono").val(resultado.celular);                
                $("#txtCorreo").val(resultado.correo);
                $("#txtLote").val(resultado.lote);
                $("#modalCobros").modal('show');    
                BuscarPagosRealizados();
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


/*********************** END **************************/

function format(data) {

    return '<div class="table-child">' +
        '<table  class="table table-striped table-bordered  w-100" id="TablaTareasReportt" style="margin-top: -1px !important;">' +
        '<thead class="cabecera-child">' +
        '<tr>' +
        ' <th>Fecha</th>' +
        ' <th>Letra</th>' +
        ' <th>Monto</th>' +
        ' <th>Soles</th>' +
        ' <th>Dolares</th>' +
        ' <th>Nro Operacion</th>' +
        ' <th>Nro Boleta</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody>' +
        '</tbody>' +
        '</table>' +
        '</div>';
}

function InicializarAtributosTablaBusquedaCabActividades() {
    $('#TablaPagos').on('key-focus.dt', function(e, datatable, cell) {

        getTablaBusquedaCabGenerado.row(cell.index().row).select();
        var data = getTablaBusquedaCabGenerado.row(cell.index().row).data();
    });

    $('#TablaPagos').on('click', 'tbody td', function(e) {
        e.stopPropagation();
        var rowIdx = getTablaBusquedaCabGenerado.cell(this).index().row;
        getTablaBusquedaCabGenerado.row(rowIdx).select();
    });
    $('#TablaPagos tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = getTablaBusquedaCabGenerado.row(tr);
        var open = row.child.isShown();
        getTablaBusquedaCabGenerado.rows().every(function(rowIdx, tableLoop, rowLoop) {
            if (this.child.isShown()) {
                this.child.hide();
                $(this.node()).removeClass('shown');
            }
        });
        if (!open) {
            row.child(format(row.data())).show();
            tr.next('tr').addClass('details-row');
            tr.addClass('shown');
            var data = row.data();
            BuscarTareasGenerado(data.id);
        }
    });
}

function BuscarTareasGenerado(codigo) {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnListaSubPagos": true,
        "codigo": codigo
    };
    realizarJsonPost(url, dato, respuestaBuscarMovimientoDetalleGenerado, null, 10000, null);
}

function respuestaBuscarMovimientoDetalleGenerado(dato) {
    desbloquearPantalla();   
    CargarTablaBusquedaDetalleMovimientoGenerado(dato.data);
}

var getTablaBusquedaTareasGenerado = null;
function CargarTablaBusquedaDetalleMovimientoGenerado(data) {
    console.log(data);
    if (getTablaBusquedaTareasGenerado) {
        getTablaBusquedaTareasGenerado.destroy();
        getTablaBusquedaTareasGenerado = null;
    }

    getTablaBusquedaTareasGenerado = $('#TablaTareasReportt').DataTable({
        "data": data,
        "order": [
            [0, "desc"]
        ],
        "sDom": '<"dt-panelmenu clearfix"Tfr>t<"dt-panelfooter clearfix"ip>',
        "ordering": false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "paging": true,
        destroy: true,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        "columns": [
            { "data": "fecha" },
            { "data": "letra" },
            {"data": "monto" },
            { "data": "soles" },
            { "data": "dolares" },
            { "data": "nro_operacion" },
            { "data": "nro_boleta" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
}




//*************** popo 2 *////////////////////

function BuscarPagosRealizados2() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnPagosRealizados": true,
        "txtidPago": $("#txtidPago").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosRealizadosGenerados2, null, 10000, null);   
}

function respuestaBuscarPagosRealizadosGenerados2(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaPagosRealizadosGenerados2(dato.data);
}

var getTablaPagosRealizados2 = null;
function LlenarTabalaPagosRealizadosGenerados2(datos) {
    if (getTablaPagosRealizados2) {
        getTablaPagosRealizados2.destroy();
        getTablaPagosRealizados2 = null;
    }

    getTablaPagosRealizados2 = $('#TablaPagosRealizadosCobros2').DataTable({
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
                    return '<button class="btn btn-edit-action"   onclick="AbrirDatosPagos2(\'' + data + '\')"><i class="fas fa-pencil-alt"></i></button>';
                }
            },
            { "data": "fecha" },
            { "data": "tipo_comprobante" },
            { "data": "moneda_pago" },
            { "data": "tipo_cambio" },
            { "data": "importe_pago" },
            { "data": "pagado" },
            { "data": "medio_pago" },
            { "data": "nro_operacion" },
            { "data": "banco" },
            { 
                "data": "voucher",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.voucher==""){
                        html="Ninguno";
                    }else{
                        //html='<a href="'+"../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+row.voucher+'" download="'+row.fech_pago+"_"+row.lote_nom+"_"+row.nom_cliente+'_PAGOLETRA">Voucher <i class="fas fa-arrow-alt-circle-down"></i></a>';
                   
                        html = '<a href="javascript:void(0)" onclick="VerVoucher(\'' + row.id + '\')">Voucher</a>';
                   
                    }
                    return html;
                }
            },
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

function AbrirDatosPagos2(id) {
   
    //$('#modalCobros').modal('show');
    bloquearPantalla("Buscando...");
    var data = {
        "btnCargarDatosPago": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                var resultado = dato.data;
                $("#txtID_PAGO2").val(resultado.id);
                $("#txtFechaPagoDetalle2").val(resultado.fecha);               
                $("#bxTipoComprobanteDetalle2").val(resultado.tipo_comprobante);
                $("#txtImportePagadoDetalle2").val(resultado.importe_pago);
                $("#bxTipoMonedaDetalle2").val(resultado.moneda_pago);
                $("#txtTipoCambioDetalle2").val(resultado.tipo_cambio);
                $("#txtMontoPagado2").val(resultado.monto_pagado); 
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


function EjecutarBusquedaLetra(){
    var correlativo =  $("#bxFiltroLetraCobros").val();
    BuscarDatosLetra(correlativo);
}


function BuscarDatosLetra(correlativo) {
   
    //$('#modalCobros').modal('show');
    bloquearPantalla("Buscando...");
    var data = {
        "btnBuscarDatosLetra": true,
        "correlativo": correlativo,
        "idpago": $("#txtidPago").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            //console.log(dato);
            if (dato.status == "ok") {
                var resultado = dato.data;
                $("#txtidcronograma").val(resultado.id);
                $("#txtFechaVencimientoLetra").val(resultado.fecha);               
                $("#bxTipoMonedaLetra").val(resultado.tipo_moneda);
                $("#txtMontoLetra").val(dato.total);
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


function LimpiarCamposPagos(){
    $("#txtFechaPagoDetalle2").val("");
    $("#txtTipoCambioDetalle2").val("");
    $("#txtImportePagadoDetalle2").val("");
    document.getElementById('bxTipoComprobanteDetalle2').selectedIndex = 0;
    document.getElementById('bxTipoMonedaDetalle2').selectedIndex = 0;
}

function AprobarPago() {
   
    //$('#modalCobros').modal('show');
    bloquearPantalla("Buscando...");
    var data = {
        "btnAprobarPago": true,
        "idpago": $("#txtID_PAGO2").val(),
        "idcronograma": $("#txtidcronograma").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                BuscarPagosRealizados2();
                EjecutarBusquedaLetra(); 
                if(dato.variable=="completo"){
                    BuscarPagosGenerados();
                }  
                LimpiarCamposPagos();
                mensaje_alerta("\u00A1Correcto!", dato.data, "success");             
                return;
            } else {
                mensaje_alerta("\u00A1Error!", dato.data, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });       
}

function RechazarPago() {
   
    //$('#modalCobros').modal('show');
    bloquearPantalla("Buscando...");
    var data = {
        "btnRechazarPago": true,
        "idpago": $("#txtID_PAGO2").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                BuscarPagosRealizados2();
                mensaje_alerta("\u00A1Correcto!", dato.data, "success"); 
                LimpiarCamposPagos()
                return;
            } else {
                mensaje_alerta("\u00A1Error!", dato.data, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });       
}






function BuscarPagosRealizados3() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnPagosRealizados3": true,
        "txtidPago": $("#txtidPago").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosRealizadosGenerados3, null, 10000, null);   
}

function respuestaBuscarPagosRealizadosGenerados3(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaPagosRealizadosGenerados3(dato.data);
}

var getTablaPagosRealizados3 = null;
function LlenarTabalaPagosRealizadosGenerados3(datos) {
    if (getTablaPagosRealizados3) {
        getTablaPagosRealizados3.destroy();
        getTablaPagosRealizados3 = null;
    }

    getTablaPagosRealizados3 = $('#TablaPagosRealizadosCobros3').DataTable({
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
                    return '<button class="btn btn-edit-action"   onclick="AbrirDatosPagos2(\'' + data + '\')"><i class="fas fa-pencil-alt"></i></button>';
                }
            },
            { "data": "fecha" },
            { "data": "tipo_comprobante" },
            { "data": "nro_boleta" },
            { "data": "moneda_pago" },
            { "data": "tipo_cambio" },
            { "data": "importe_pago" },
            { "data": "medio_pago" },
            { "data": "nro_operacion" },
            { "data": "banco" },
            { 
                "data": "voucher",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.voucher==""){
                        html="Ninguno";
                    }else{
                        html='<a href="'+"../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+row.voucher+'" download="'+row.fech_pago+"_"+row.lote_nom+"_"+row.nom_cliente+'_PAGOLETRA">Voucher <i class="fas fa-arrow-alt-circle-down"></i></a>';
                    }
                    return html;
                }
            },
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

function ValidarFechasValidados(){
     var data = {
        "btnValidarFechas": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                $("#txtFecIniFiltroPR").val(dato.primero);
                $("#txtFecFinFiltroPR").val(dato.ultimo);
            } 
            BuscarPagosGenerados2();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}


/********************** PAGOS REALIZADOOS ***************//////////////////////

function BuscarPagosGenerados2() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnListaPagos4": true,
        "txtFiltroDocumentoPR": $("#txtFiltroDocumentoPR").val(),
        "bxFiltroLotePR": $("#bxFiltroLotePR").val(),
        "bxFiltroManzanaPR": $("#bxFiltroManzanaPR").val(),
        "bxFiltroZonaPR": $("#bxFiltroZonaPR").val(),
        "bxFiltroBancoPR": $("#bxFiltroBancoPR").val(),
        "txtFecIniFiltroPR": $("#txtFecIniFiltroPR").val(),
        "txtFecFinFiltroPR": $("#txtFecFinFiltroPR").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosGenerados2, null, 10000, null);
}

function respuestaBuscarPagosGenerados2(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaActividadesGenerados2(dato.data);
}

var getTablaBusquedaCabGenerado2 = null;
function LlenarTabalaActividadesGenerados2(datos) {
    if (getTablaBusquedaCabGenerado2) {
        getTablaBusquedaCabGenerado2.destroy();
        getTablaBusquedaCabGenerado2 = null;
    }

    getTablaBusquedaCabGenerado2 = $('#TablaPagosRealizados').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "0%"
            },
            {
                'aTargets': [1],
                'ordering': false,
                'width': "0%"
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
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fechaVencimiento" },
            { "data": "mora" },
            { "data": "letra" },
            { "data": "tipo_moneda" },
            { "data": "importe_pago" },
            { "data": "tipo_cambio" },
            { "data": "pagado" },
            { "data": "banco" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color: #43B647;">' + row.estado + '</span>';
                    return html;
                } 
            },
            {
                "data": "estado_nom",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color:'+ row.estado_col +';">' + row.estado_nom + '</span>';
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

function LlenarTablaPagosGeneradosReporte2() {
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
            "url": "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnListaPagos4": true,
                    "txtFiltroDocumentoPR": $("#txtFiltroDocumentoPR").val(),
                    "bxFiltroLotePR": $("#bxFiltroLotePR").val(),
                    "bxFiltroBancoPR": $("#bxFiltroBancoPR").val(),
                    "txtFecIniFiltroPR": $("#txtFecIniFiltroPR").val(),
                    "txtFecFinFiltroPR": $("#txtFecFinFiltroPR").val()
                });
            }
        },
        "columns": [
            { "data": "fecha" },            
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fechaVencimiento" },
            { "data": "mora" },
            { "data": "letra" },
            { "data": "tipo_moneda" },
            { "data": "importe_pago" },
            { "data": "tipo_cambio" },
            { "data": "pagado" },
            { "data": "banco" },
            { "data": "estado" },
            { "data": "estado_nom" }
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

    $('#TablaPagosRealizadosReporte').DataTable(options);
}




/******************************** LISTAR PAGOS REALIZADOS EN LA RESERVA **************************************/

function BuscarPagosGeneradosRES() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnListaPagosRES": true,
        "txtFiltroDocumentoEC": $("#txtFiltroDocumentoRES").val(),
        "bxFiltroLoteEC": $("#bxFiltroLoteRES").val(),
        "bxFiltroEV": $("#bxFiltroEVRES").val(),
        "bxFiltroEstadoEC": $("#bxFiltroEstadoRES").val(),
        "txtFecIniFiltro": $("#txtFecIniFiltroRES").val(),
        "txtFecFinFiltro": $("#txtFecFinFiltroRES").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosGeneradosRES, null, 10000, null);
}

function respuestaBuscarPagosGeneradosRES(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaActividadesGeneradosRES(dato.data);
}

var getTablaBusquedaCabGeneradoRES = null;
function LlenarTabalaActividadesGeneradosRES(datos) {
    if (getTablaBusquedaCabGeneradoRES) {
        getTablaBusquedaCabGeneradoRES.destroy();
        getTablaBusquedaCabGeneradoRES = null;
    }

    getTablaBusquedaCabGeneradoRES = $('#TablaPagosRES').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "0%"
            },
            {
                'aTargets': [1],
                'ordering': false,
                'width': "0%"
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
                    if(row.descVisto_bueno == "POR VALIDAR"){
                        html = '<button class="btn btn-edit-action" onclick="MostrarDetallePagoReserva(\'' + data + '\')" title="Validar Pago Reserva"><i class="fas fa-pencil-alt"></i></button>';
                    }else{
                        html = '';
                    }
                    return html;
                        
                }
            },
            {
                "data": "visto_bueno",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.colorVisto_bueno +'; color: white; font-weight: bold;">' + row.descVisto_bueno + '</span>';
                    return html;
                } 
            },
            { "data": "fecha" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge" style="background-color:'+ row.colorEstado +'; color: white; font-weight: bold;">' + row.descEstado + '</span>';
                    return html;
                } 
            },
            { 
                "data": "voucher",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.voucher==""){
                        html="Ninguno";
                    }else{
                        html = '<a href="javascript:void(0)" class="btn btn-delete-action" onclick="VerVoucher2(\'' + row.id + '\')"><i class="fas fa-file-pdf"></i> Ver</a>';
                    }
                    return html;
                }
            },
            { "data": "cliente" }, 
            { "data": "lote" },            
            { "data": "fechaVencimiento" },
            { "data": "letra" },
            { "data": "mora" },
            { "data": "importe_pago" },
            { "data": "nro_operacion" }
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

function VerVoucher2(id) {  
    var data = {
      btnMostrarVoucher2: true,
      idRegistro: id,
    };
    $.ajax({
      type: "POST",
      url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            if(dato.formato == "jpeg" || dato.formato == "jpg"){
                var html = "";
                var documento = "archivos/"+dato.voucher+"";
                html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                $("#my_img_doc").html(html);
                $("#modalVerVoucher").modal("show");   
            }else{
                if(dato.formato == "png"){
                    var html = "";
                    var documento = "archivos/"+dato.voucher+"";
                    html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                    $("#my_img_doc").html(html);
                    $("#modalVerVoucher").modal("show");   
                }else{
                    var html = "";
                    var documento = "archivos/"+dato.voucher+"";
                    html += "<object class='pdfview' type='application/pdf' data='" +documento +"' style='width: 100%'></object> ";
                    $("#my_img_doc").html(html);
                    $("#modalVerVoucher").modal("show");        
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
 
 
 function MostrarDetallePagoReserva(id){

   var data = {
       "btnEditarPagoReserva": true,
       "idRegistro": id
   };
   $.ajax({
       type: "POST",
       url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
               //console.log(dato);
               $("#_ID_PAGOR").val(dato.data.id);
               $("#txtFechaPagoPR").val(dato.data.fecha);
               $("#cbxTipoMonedaPR").val(dato.data.tipomoneda);
               $("#txtImportePagoPR").val(dato.data.importePago);
               $("#txtTipoCambioPR").val(dato.data.tipoCambio);
               $("#txtPagadoPR").val(dato.data.pagado);
               $("#cbxBancoPR").val(dato.data.banco);
               $("#cbxMedioPagoPR").val(dato.data.medioPago);
               $("#cbxTipoComprobantePR").val(dato.data.tipoComprobante);
               $("#txtSeriePR").val(dato.data.serie);
               $("#txtNumeroPR").val(dato.data.numero);
               $("#txtNumOperacionPR").val(dato.data.nro_operacion);
               $('#modalVerEditarPagoReserva').modal('show');

       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

function ActualizarPagoReserva(){
  
    var data = {
        "btnActualizarPagoReserva": true,
        "_ID_PAGO": $("#_ID_PAGOR").val(),
        "txtFechaPagoP": $("#txtFechaPagoPR").val(),
        "cbxTipoMonedaP": $("#cbxTipoMonedaPR").val(),
        "txtImportePagoP": $("#txtImportePagoPR").val(),
        "txtTipoCambioP": $("#txtTipoCambioPR").val(),
        "txtPagadoP": $("#txtPagadoPR").val(),
        "cbxBancoP": $("#cbxBancoPR").val(),
        "cbxMedioPagoP": $("#cbxMedioPagoPR").val(),
        "cbxTipoComprobanteP": $("#cbxTipoComprobantePR").val(),
        /*"txtSerieP": $("#txtSerieP").val(),
        "txtNumeroP": $("#txtNumeroP").val(),*/
        "txtNumOperacionP": $("#txtNumOperacionPR").val(),
        "ficheroPago": $("#ficheroPagoR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
                    BuscarPagosGeneradosRES();
                    var dat = $("#ficheroPagoR").val();
                    if(dat!=""){
                        var nombre = dato.name;
                        EnviarAdjuntoPago(nombre);                        
                    }                    
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
    
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

 
function ValidarPagoReserva(){
    var data = {
        "btnValidarPagoReserva": true,
        "idRegistro": $("#_ID_PAGOR").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
                    $('#modalVerEditarPagoReserva').modal('hide');
                    mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
                    BuscarPagosGeneradosRES();
                } else {
                    mensaje_alerta("\u00A1ERROR!", dato.data, "info");
                }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });   

}

function LlenarTablaPagosGeneradosReporteRES() {
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
            "url": "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnListaPagosRES": true,
                    "txtFiltroDocumentoEC": $("#txtFiltroDocumentoRES").val(),
                    "bxFiltroLoteEC": $("#bxFiltroLoteRES").val(),
                    "bxFiltroEV": $("#bxFiltroEVRES").val(),
                    "bxFiltroEstadoEC": $("#bxFiltroEstadoRES").val(),
                    "txtFecIniFiltro": $("#txtFecIniFiltroRES").val(),
                    "txtFecFinFiltro": $("#txtFecFinFiltroRES").val()
                });
            }
        },
        "columns": [
            { "data": "lote" },
            { "data": "fechaVencimiento" },
            { "data": "fecha" },
            { "data": "mora" },
            { "data": "letra" },
            { "data": "tipo_moneda" },
            { "data": "monto" },
            { "data": "descEstado"},
            { "data": "descVisto_bueno"}
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

    $('#TablaPagosReporteRES').DataTable(options);
}



/*** PAGOS RESERVA VALIDADOS **/

function BuscarPagosGeneradosRES2() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnListaPagosVRES": true,
        "txtFiltroDocumentoPR": $("#txtFiltroDocumentoVRES").val(),
        "bxFiltroLotePR": $("#bxFiltroLoteVRES").val(),
        "bxFiltroManzanaPR": $("#bxFiltroManzanaVRES").val(),
        "bxFiltroZonaPR": $("#bxFiltroZonaVRES").val(),
        "bxFiltroBancoPR": $("#bxFiltroBancoVRES").val(),
        "txtFecIniFiltroPR": $("#txtFecIniFiltroVRES").val(),
        "txtFecFinFiltroPR": $("#txtFecFinFiltroVRES").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosGeneradosRES2, null, 10000, null);
}

function respuestaBuscarPagosGeneradosRES2(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaActividadesGeneradosRES2(dato.data);
}

var getTablaBusquedaCabGeneradoRES2 = null;
function LlenarTabalaActividadesGeneradosRES2(datos) {
    if (getTablaBusquedaCabGeneradoRES2) {
        getTablaBusquedaCabGeneradoRES2.destroy();
        getTablaBusquedaCabGeneradoRES2 = null;
    }

    getTablaBusquedaCabGeneradoRES2 = $('#TablaPagosRealizadosVRES').DataTable({
        "data": datos,
        "columnDefs": [{
                'aTargets': [0],
                'ordering': false,
                'width': "0%"
            },
            {
                'aTargets': [1],
                'ordering': false,
                'width': "0%"
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
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fechaVencimiento" },
            { "data": "mora" },
            { "data": "letra" },
            { "data": "tipo_moneda" },
            { "data": "importe_pago" },
            { "data": "tipo_cambio" },
            { "data": "pagado" },
            { "data": "banco" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color: #43B647;">' + row.estado + '</span>';
                    return html;
                } 
            },
            {
                "data": "estado_nom",
                "render": function(data, type, row) {
                    var html = "";
                    html = '<span class="badge etiqueta-js" style="background-color:'+ row.estado_col +';">' + row.estado_nom + '</span>';
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

function LlenarTablaPagosGeneradosReporteRES2() {
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
            "url": "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
                    "ReturnListaPagosVRES": true,
                    "txtFiltroDocumentoPR": $("#txtFiltroDocumentoPR").val(),
                    "bxFiltroLotePR": $("#bxFiltroLotePR").val(),
                    "bxFiltroBancoPR": $("#bxFiltroBancoPR").val(),
                    "txtFecIniFiltroPR": $("#txtFecIniFiltroPR").val(),
                    "txtFecFinFiltroPR": $("#txtFecFinFiltroPR").val()
                });
            }
        },
        "columns": [
            { "data": "fecha" },            
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fechaVencimiento" },
            { "data": "mora" },
            { "data": "letra" },
            { "data": "tipo_moneda" },
            { "data": "importe_pago" },
            { "data": "tipo_cambio" },
            { "data": "pagado" },
            { "data": "banco" },
            { "data": "estado" },
            { "data": "estado_nom" }
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

    $('#TablaPagosRealizadosReporteVRES').DataTable(options);
}












