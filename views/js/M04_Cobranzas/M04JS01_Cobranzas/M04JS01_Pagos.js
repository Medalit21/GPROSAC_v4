var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
  $("#PanelFiltros").hide();
  $("#PanelBotons").hide(); 
  LlenarProyectos();
  LLenarZonas();
  ValidarFechas();
  VerificarPerfil();

  $('#btnNuevoPago').click(function() {
     IrPagos();
  });
  
   $('#btnBuscarPagos').click(function() {
      LlenarInformacion();
  });
  
   $('#btnLimpiarPagos').click(function() {
     document.getElementById('bxFiltroZonaEC').selectedIndex = 0;
     document.getElementById('bxFiltroManzanaEC').selectedIndex = 0;
     document.getElementById('bxFiltroLoteEC').selectedIndex = 0;
     $('#txtFiltroDatoCliente').val(null).trigger('change');
     $("#txtFiltroFecIniPago").val("");
     $("#txtFiltroFecFinPago").val("");
     document.getElementById('cbxFiltroEstadoPago').selectedIndex = 0;
     document.getElementById('cbxFiltroBancosPago').selectedIndex = 0;
     document.getElementById('cbxMoraPago').selectedIndex = 0;
     ValidarFechas();
     //LlenarInformacion();
  });
  
   $('#btnGuardarPagoP').click(function() {
      ActualizarPago();
  });
  
  $('#bxFiltroProyectoEC').change(function () {
        $("#bxFiltroZonaEC").val("");
        $("#bxFiltroManzanaEC").val("");
        var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
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
        var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaEC').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaEC");
        document.getElementById('bxFiltroLoteEC').selectedIndex = 0;
    });
   
    $('#bxFiltroManzanaEC').change(function () {
        $("#bxFiltroLoteEC").val("");
        var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaEC').val()
        };
        llenarCombo(url, datos, "bxFiltroLoteEC");
    });
    
    
    
    $('#btneliminarvou').click(function() {
      EliminarVoucherPago();
    });
  
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
                //console.log(dato);
                if(dato.perfil=="6"){
                    $("#PanelFiltros").hide();
                    $("#PanelBotons").hide();
                    $("#txtFiltroDocumentoEC").val(dato.documento);
                    CargarDatosAdicionales(dato.documento, '');
                    CargarPagos();
                    $('#TablaPagosReporte').DataTable().ajax.reload();
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


function LlenarProyectos() {
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoEC");    
}

function LLenarZonas() {
    var url = '../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoEC').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaEC");
}



function LlenarInformacion(){

    var documento = $("#txtFiltroDatoCliente").val();
    var lote = $("#bxFiltroLoteEC").val();

    CargarPagos();
    CargarDatosAdicionales(documento, lote);
}


function CargarPagos() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "ReturnListaPagos2": true,
		"txtFiltroDatoCliente": $("#txtFiltroDatoCliente").val(),
		"txtFiltroFecIniPago": $("#txtFiltroFecIniPago").val(),
		"txtFiltroFecFinPago": $("#txtFiltroFecFinPago").val(),
		"cbxFiltroEstadoPago": $("#cbxFiltroEstadoPago").val(),
		"cbxFiltroEstadoPagoFac": $("#cbxFiltroEstadoPagoFac").val(),
		"cbxFiltroBancosPago": $("#cbxFiltroBancosPago").val(),
		"cbxMoraPago": $("#cbxMoraPago").val(),
		"bxFiltroProyectoEC": $("#bxFiltroProyectoEC").val(),
		"bxFiltroZonaEC": $("#bxFiltroZonaEC").val(),
		"bxFiltroManzanaEC": $("#bxFiltroManzanaEC").val(),
		"bxFiltroLoteEC": $("#bxFiltroLoteEC").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarHojaResumenGenerados, null, 10000, null);
}

function respuestaBuscarHojaResumenGenerados(dato) {
    desbloquearPantalla();
    console.log(dato);
    LlenarTabalaHojaResumenGenerados(dato.data);
}

var getTablaBusquedaCabGenerado = null;
function LlenarTabalaHojaResumenGenerados(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaPagos').DataTable({
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
                /*"render": function (data, type, row) {
                    var html = "";
                    if(row.estado == '2'){
                         if(row.estado_cierre== '4'){
                            html = '<button href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarReserva(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> \ <button href="javascript:void(0)" class="btn btn-delete-action" onclick="ConfirmarAnularPago(\'' + data + '\')" title="Anular Pago"><i class="fas fa-times"></i></button>';    
                        }else{
                            if(row.estado_cierre== '2'){
                                html = '<button href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarReserva(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> \ <button href="javascript:void(0)" class="btn btn-delete-action" onclick="ConfirmarAnularPago(\'' + data + '\')" title="Anular Pago"><i class="fas fa-times"></i></button>';
                            }else{
                                html =  '<button href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarReserva(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> \ <button href="javascript:void(0)" class="btn btn-delete-action" onclick="ConfirmarAnularPago(\'' + data + '\')" title="Anular Pago"><i class="fas fa-times"></i></button>';
                            }
                        }
                    }else{
                       
                        html =  '<button href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarReserva(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> \ <button href="javascript:void(0)" class="btn btn-delete-action" onclick="ConfirmarAnularPago(\'' + data + '\')" title="Anular Pago"><i class="fas fa-times"></i></button>';
                
                    }  
                    return html;
                }*/
				
				/*"render": function (data, type, row) {
					var html = "";

					// Si estado_cierre es 1 (FINALIZADO) -> NO MOSTRAR BOTONES
					if (row.estado !== '2') {
						html += '<button href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarReserva(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> ';
						html += '<button href="javascript:void(0)" class="btn btn-delete-action" onclick="ConfirmarAnularPago(\'' + data + '\')" title="Anular Pago"><i class="fas fa-times"></i></button>';
					}

					return html;
				}*/
				
				"render": function (data, type, row) {
					var html = "";

					if (parseInt(row.estado) === 2) {
						// FINALIZADO: 
						//html = '<i class="fas fa-lock" style="color: #888;" title="Finalizado: No editable"></i>';
						
					} else if (row.estado_validacion === 'RECHAZADO') {
						// Solo mostrar botón de Anular
						html += '<button href="javascript:void(0)" class="btn btn-delete-action" onclick="ConfirmarAnularPago(\'' + data + '\')" title="Anular Pago"><i class="fas fa-times"></i></button>';
					} else {
						// Mostrar ambos botones: Editar y Anular
						html += '<button href="javascript:void(0)" class="btn btn-edit-action" onclick="EditarReserva(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></button> ';
						html += '<button href="javascript:void(0)" class="btn btn-delete-action" onclick="ConfirmarAnularPago(\'' + data + '\')" title="Anular Pago"><i class="fas fa-times"></i></button>';
					}

					return html;
				}

				

            },
            {
                "data": "id",
                "render": function (data, type, row) {
                    var html = "";
                    if(row.estado == '2'){
                        if(row.estado_cierre== '1'){
                            html = '<span class="badge" style="background-color: #00BC17; color: white; font-weight: bold;">FINALIZADO</span>';    
                        }else{
                            
                            html = '<span class="badge" style="background-color: #F09900; color: white; font-weight: bold;">PROCESANDO</span>';
                            //html =  '<span class="badge" style="background-color: #B2B1AF; color: white; font-weight: bold;">EN ESPERA</span>';
                            
                        }
                    }else{
                       
                        html =  '<span class="badge" style="background-color: #B2B1AF; color: white; font-weight: bold;">EN ESPERA</span>';
                
                    }  
                    return html;
               }
            },
            { "data": "fecha_pago" },
			{
                "data": "estado_validacion",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.estado_validacion=='PENDIENTE'){
                        html = '<span class="badge" style="background-color: #B2B1AF; color: white; font-weight: bold;">PENDIENTE</span>';
                    }else{
                        if(row.estado_validacion=='VALIDADO'){
                            html = '<span class="badge" style="background-color: #00BC17; color: white; font-weight: bold;">VALIDADO</span>';
                        }else{
                            html = '<span class="badge" style="background-color: #D30000; color: white; font-weight: bold;">RECHAZADO</span>';
                        }
                    }
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
                        html = '<a href="javascript:void(0)" class="btn btn-primary-action" onclick="VerVoucher(\'' + row.id + '\')"><i class="fas fa-file-pdf"></i> Voucher</a>';
                    }
                    return html;
                }
            },
            { "data": "tipo_moneda" },
			{ "data": "importe" },
			{ "data": "tipo_cambio" },
			{ "data": "pagado" },
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fecha_vencimiento" },
            { "data": "letra" },
            { "data": "mora" },
            { "data": "banco" },
            { "data": "medio_pago" },
            { "data": "tipo_comprobante" },
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
            $("#ID_PDET").val(dato.id);
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
                    if(dato.formato == "pdf"){
                        var html = "";
                        var documento = "archivos/"+dato.voucher+"";
                        html += "<object class='pdfview' type='application/pdf' data='" +documento +"' style='width: 100%'></object> ";
                        $("#my_img_doc").html(html);
                        $("#modalVerVoucher").modal("show");     
                    }else{
                        var html = "";
                        var documento = "archivos/FORMATO.png";
                        html +="<img class='pdfview' src='" +documento +"' style='width: 100%;'></img> ";
                        $("#my_img_doc").html(html);
                        $("#modalVerVoucher").modal("show"); 
                    }
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
  
  
function EliminarVoucherPago(){
    var id = $("#ID_PDET").val();
    mensaje_condicionalUNO("\u00A1ADVERTENCIA!", "\u00BFEsta seguro(a) de eliminar el voucher de pago?", ConfirmarEliminarVoucherPago, DenegarEliminarVoucherPago, id);
}

function ConfirmarEliminarVoucherPago(id){   
    bloquearPantalla("Eliminando...");
    var url = "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php";
    var dato = {
        "btnEliminarVoucherPago": true,
        "idRegistro": id,
        "txtUSR": $("#txtUSR").val()
    };
    realizarJsonPost(url, dato, respuestaConfirmarEliminar, null, 10000, null);
}

function respuestaConfirmarEliminar(dato){
    desbloquearPantalla();
    if (dato.status == "ok") {                        
        mensaje_alerta("\u00A1CONFORME!", "Se ha eliminado correctamente el Voucher de Pago.", "success");
        LlenarInformacion();
    } else {
        mensaje_alerta("\u00A1ERROR!", dato.data, "info");
    }
}

function DenegarEliminarVoucherPago(){
    return;
}

  
  
  

function ConfirmarAnularPago(id){
    mensaje_eliminar_parametro("\u00BFEst\u00E1 seguro que desea eliminar el pago seleccionado? Al confirmar se proceder\u00E1 a eliminar el registro seleccionado.", AnularPago, id);
}

function AnularPago(id){
  
    var data = {
        "btnAnularPago": true,
        "idRegistro": id,
        "txtUSR": $("#txtUSR").val()
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
                    mensaje_alerta("\u00A1ANULADO!", "El pago seleccionado fue anulado.", "success"); 
                    CargarPagos();
                } else {
                    mensaje_alerta("\u00A1ERROR!", "El pago no fue anulado correctamente. Intente nuevamente.", "info");
                }
 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        }
    });  
 }




function EditarReserva(id){

   var data = {
       "btnEditarPago": true,
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
               $("#_ID_PAGO").val(dato.data.id);
               $("#txtFechaPagoP").val(dato.data.fecha);
               $("#cbxTipoMonedaP").val(dato.data.tipomoneda);
               $("#txtImportePagoP").val(dato.data.importePago);
               $("#txtTipoCambioP").val(dato.data.tipoCambio);
               $("#txtPagadoP").val(dato.data.pagado);
               $("#cbxBancoP").val(dato.data.banco);
               $("#cbxMedioPagoP").val(dato.data.medioPago);
               $("#cbxTipoComprobanteP").val(dato.data.tipoComprobante);
               $("#txtSerieP").val(dato.data.serie);
               $("#txtNumeroP").val(dato.data.numero);
               $("#txtNumOperacionP").val(dato.data.nro_operacion);
               $('#modalEditarPago').modal('show');

       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

function ActualizarPago(){
  
    var data = {
        "btnActualizarPago": true,
        "_ID_PAGO": $("#_ID_PAGO").val(),
        "txtFechaPagoP": $("#txtFechaPagoP").val(),
        "cbxTipoMonedaP": $("#cbxTipoMonedaP").val(),
        "txtImportePagoP": $("#txtImportePagoP").val(),
        "txtTipoCambioP": $("#txtTipoCambioP").val(),
        "txtPagadoP": $("#txtPagadoP").val(),
        "cbxBancoP": $("#cbxBancoP").val(),
        "cbxMedioPagoP": $("#cbxMedioPagoP").val(),
        "cbxTipoComprobanteP": $("#cbxTipoComprobanteP").val(),
        /*"txtSerieP": $("#txtSerieP").val(),
        "txtNumeroP": $("#txtNumeroP").val(),*/
        "txtNumOperacionP": $("#txtNumOperacionP").val(),
        "ficheroPago": $("#ficheroPago").val(),
        "txtUSR": $("#txtUSR").val()
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
                    LlenarInformacion();
                    
                    if(dato.cargo_file=="si"){
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

 function EnviarAdjuntoPago(nombre){

    var file_data = $('#ficheroPago').prop('files')[0];   
     var form_data = new FormData();
     var dataa = nombre;                  
     form_data.append('file', file_data);
     form_data.append('data', dataa);
     //alert(form_data);                             
     $.ajax({
         url: '../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD03_SubirVoucher.php', // point to server-side PHP script 
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




function CargarPagosReporte() {
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
            "url": "../../models/M02_Clientes/M02MD04_HojaResumen/M02MD04_Listar.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function(d) {
                return $.extend({}, d, {
					"ReturnListaPagos2": true,
            		"txtFiltroDocumentoEC": $("#txtFiltroDocumentoEC").val(),
            		"bxFiltroLoteEC": $("#bxFiltroLoteEC").val()             
                });
            }
        },
        "columns": [
            { "data": "fecha_pago" },
			{ "data": "estado_pago"},
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fecha_vencimiento" },
            { "data": "letra" },			
			{ "data": "tipo_moneda" },
			{ "data": "importe" },
			{ "data": "tipo_cambio" },
			{ "data": "pagado" },
			{ "data": "mora" },
            { "data": "banco" },
            { "data": "medio_pago" },
            { "data": "tipo_comprobante" },
            { "data": "boleta" },
            { "data": "nro_operacion" }

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

function CargarDatosAdicionales(documento, lote){
     var data = {
        "CargarDatos": true,
        "txtFiltroDocumentoEC": documento,
        "bxFiltroLoteEC": lote
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                $("#txtDocCliente").val(dato.documento);
                $("#txtNomCliente").val(dato.nombres);
                $("#txtApePaternoCliente").val(dato.apellido_paterno);
                $("#txtApeMaternoCliente").val(dato.apellido_materno);
            

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}





function RegistrarPago(){
     var data = {
        "btnRegistrarPago": true,
        "txtidVenta": $('#txtidVenta').val(),
        "bxNroCuotas": $('#bxNroCuotas').val(),
        "bxTipoMoneda2": $('#bxTipoMoneda2').val(),
        "txtMontoPagado": $('#txtMontoPagado').val(),
        "bxMedioPago": $('#bxMedioPago').val(),
        "bxTipoComprobante": $('#bxTipoComprobante').val(),
        "bxAgenciaBancaria": $('#bxAgenciaBancaria').val(),
        "txtNumeroOperacion": $('#txtNumeroOperacion').val(),
        "txtFechaPago": $('#txtFechaPago').val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M04_Cobranzas/M04MD01_Cobranzas/M04MD02_ListarVentas.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("\u00A1Correcto!", dato.data, "success"); 

                     $("#txtMontoPagar").val("");
                     $("#txtFechaPago").val("");
                     $("#txtNumeroOperacion").val("");
                     $("#txtMontoPagado").val("");
                     document.getElementById('bxNroCuotas').selectedIndex = 0;
                     document.getElementById('bxTipoMoneda2').selectedIndex = 0; 
                     document.getElementById('bxMedioPago').selectedIndex = 0; 
                     document.getElementById('bxTipoComprobante').selectedIndex = 0; 
                     document.getElementById('bxAgenciaBancaria').selectedIndex = 0; 
                     document.getElementById('bxTipoMoneda1').selectedIndex = 0;  
                     document.getElementById('bxListaLote').selectedIndex = 0;        

                }, 100);
                return;
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
        "btnIrPagosRealizados": true,
        "txtuser": $('#txtuser').val()
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

function ValidarFechas(){
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
                $("#txtFiltroFecIniPago").val(dato.primero);
                $("#txtFiltroFecFinPago").val(dato.ultimo);   
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



