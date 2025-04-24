var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
    ValidarFechas();
    LlenarTablaPagosComprobanteReporte();
  
    $('#btnBuscarRegistroCV').click(function() {
        CargarPagosComprobante();
        $('#TablaPagoComprobanteReporte').DataTable().ajax.reload();   
    });
  
    $('#btnLimpiarCV').click(function() {
        $('#txtFiltroDocumentoCV').val(null).trigger('change');
        $("#txtFiltroDesdeCV").val("");
        $("#txtFiltroHastaCV").val("");
        document.getElementById('cbxFiltroBancoCV').selectedIndex = 0;
        document.getElementById('cbxTipoPagoC').selectedIndex = 0;
        document.getElementById('cbxEstadoC').selectedIndex = 0;
        document.getElementById('cbxTipoComprobanteSunat').selectedIndex = 0;
        document.getElementById('cbxOrdenar').selectedIndex = 0;
        ValidarFechas();
        $('#TablaPagoComprobanteReporte').DataTable().ajax.reload();  
    }); 
  
    $('#btnGuardarPagoCV').click(function() {
        GuardarComprobante();
    });

}

//AGREGAR FECHA INICIO Y TERMINO DEL MES ACTUAL
function ValidarFechas(){
    var data = {
       "btnValidarFechas": true
   };
   $.ajax({
       type: "POST",
       url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
       data: data,
       dataType: "json",
       success: function (dato) {
           desbloquearPantalla();
           if (dato.status == "ok") {
               $("#txtFiltroDesdeCV").val(dato.primero);
               $("#txtFiltroHastaCV").val(dato.ultimo);   
           } 
           CargarPagosComprobante();
           $('#TablaPagoComprobanteReporte').DataTable().ajax.reload();  

       },
       error: function (jqXHR, textStatus, errorThrown) {
           console.log(textStatus + ': ' + errorThrown);
           desbloquearPantalla();
       },
       timeout: timeoutDefecto
   });   
}

//LLENAR TABLA CONTENEDORA
function CargarPagosComprobante() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php";
    var dato = {
        "btnListarTablaPagosComprobante": true,
		"txtFiltroDocumentoCV": $("#txtFiltroDocumentoCV").val(),
		"txtFiltroDesdeCV": $("#txtFiltroDesdeCV").val(),
		"txtFiltroHastaCV": $("#txtFiltroHastaCV").val(),
		"cbxFiltroBancoCV": $("#cbxFiltroBancoCV").val(),
		"cbxTipoPagoC": $("#cbxTipoPagoC").val(),
		"cbxEstadoC": $("#cbxEstadoC").val(),
        "cbxTipoComprobanteSunat": $("#cbxTipoComprobanteSunat").val(),
        "cbxOrdenar": $("#cbxOrdenar").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarPagosComprobante, null, 10000, null);
}

function respuestaBuscarPagosComprobante(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaPagosComprobante(dato.data);
}

var getTablaPagosComprobante = null;
function LlenarTablaPagosComprobante(datos) {
    if (getTablaPagosComprobante) {
        getTablaPagosComprobante.destroy();
        getTablaPagosComprobante = null;
    }

    getTablaPagosComprobante = $('#TablaPagoComprobante').DataTable({
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
        columns: [
            { "data": "fecha_pago" },
            { 
                "data": "voucher",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.voucher==""){
                        html="Ninguno";
                    }else{
                        //html='<a href="'+"../../M04_Cobranzas/M04SM01_Cobranzas/archivos/"+row.voucher+'" download="'+row.fech_pago+"_"+row.lote_nom+"_"+row.cliente+'_PAGOLETRA">Voucher <i class="fas fa-arrow-alt-circle-down"></i></a>';
                    
                        html = '<a href="javascript:void(0)" onclick="VerVoucher(\'' + row.id + '\')">Voucher</a>';
                    }
                    return html;
                }
            },
            {
                "data": "estado_pago",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.estado_pago=='POR VALIDAR'){
                        html = '<span class="badge" style="background-color: #F09900; color: white; font-weight: bold;">' + row.estado_pago + '</span>';
                    }else{
                        if(row.estado_pago=='APROBADO'){
                            html = '<span class="badge" style="background-color: #00BC17; color: white; font-weight: bold;">' + row.estado_pago + '</span>';
                        }else{
                            html = '<span class="badge" style="background-color: #E31800; color: white; font-weight: bold;">' + row.estado_pago + '</span>';
                        }
                    }
                    return html;
                } 
            },
            { "data": "fecha_emision" },
            { "data": "tipo_comprobante_sunat" },            
            { "data": "serie" },
            { "data": "numero" },
            { 
                "data": "comprobante",
                "render": function(data, type, row, host) {
                    var html="";
                    if(row.comprobante==""){
                        html="Falta adjunto";
                    }else{
                      // html='<a href="'+"../../M07_Contabilidad/M07SM02_ComprobanteSunat/archivos/"+row.comprobante+'" download="'+row.fech_pago+"_"+row.lote_nom+"_"+row.cliente+'_PAGOLETRA">Comprobante <i class="fas fa-arrow-alt-circle-down"></i></a>';
                   
                        html = '<a href="javascript:void(0)" onclick="VerComprobante(\'' + row.comprobante + '\')">Comprobante</a>';
                    }
                    return html;
                }
            },	
            {
                "data": "estado_cierre",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.estado_cierre=="PROCESANDO"){
                        html = '<span class="badge" style="background-color: #F09900; color: white; font-weight: bold;">' + row.estado_cierre + '</span>';
                    }else{                       
                        html = '<span class="badge" style="background-color: #00BC17; color: white; font-weight: bold;">' + row.estado_cierre + '</span>';   
                    }
                    return html;
                } 
            }, 		
            { "data": "cliente" },
            { "data": "lote" },
            { "data": "fecha_vencimiento" },
            { "data": "letra" },
            { "data": "mora" },
            { "data": "tipo_comprobante" },
			{ "data": "tipo_moneda" },
			{ "data": "importe" },
			{ "data": "tipo_cambio" },
			{ "data": "pagado" },
            { "data": "banco" },
            { "data": "medio_pago" }, 
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
      url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            console.log(dato);
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
 
function ObservacionesPago(id) {  
    var data = {
      btnVerObservaciones: true,
      "idRegistro": id
    };
    $.ajax({
      type: "POST",
      url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
      data: data,
      dataType: "json",
      success: function (dato) {
        desbloquearPantalla();
        if (dato.status == "ok") {
            //console.log(dato);
            var resultado = dato.data;
            if(resultado.estado == '0'){
                $("#btnGuardarObservacion").show();
                $("#btnConforme").hide();
                $("#txtObservacion").prop('disabled', false);
            }else{
                if(resultado.estado == '1'){
                    $("#btnGuardarObservacion").hide();
                    $("#btnConforme").show();
                    $("#txtObservacion").prop('disabled', true);
                }else{
                    $("#btnGuardarObservacion").hide();
                    $("#btnConforme").hide();
                }
            }
            $("#_ID_PAGO_DETALLE").val(resultado.id);
            $("#txtObservacion").val(resultado.observacion);
            $("#txtRespuesta").val(resultado.respuesta);
            
            $("#modalObservaciones").modal("show"); 
        } 
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus + ": " + errorThrown);
        desbloquearPantalla();
      },
    });
 }

function ConformidadPago(){
    var data = {
        "btnConformidadPago": true,
        "_ID_PAGO_DETALLE": $("#_ID_PAGO_DETALLE").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
                    CargarPagosComprobante();  
                    $("#modalObservaciones").modal("hide");                
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

function ObservarPago(){
    var data = {
        "btnObservarPago": true,
        "_ID_PAGO_DETALLE": $("#_ID_PAGO_DETALLE").val(),
        "txtObservacion": $("#txtObservacion").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
                    CargarPagosComprobante();
                    $("#modalObservaciones").modal("hide");                   
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
 
 
 
 
 
 function VerComprobante(comprobante) {  
    
    var html2 = "";
    var documento2 = "../../M07_Contabilidad/M07SM02_ComprobanteSunat/archivos/"+comprobante+"";
    html2 += "<object class='pdfview' type='application/pdf' data='" +documento2 +"' style='width: 100%'></object> ";
    $("#my_img_doc_com").html(html2);
    $("#modalVerComprobante").modal("show");        
         
 }

var tablaPagosReporte = null;
function LlenarTablaPagosComprobanteReporte() {
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
            "url": "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "btnListarTablaPagosComprobante": true,
                    "txtFiltroDocumentoCV": $("#txtFiltroDocumentoCV").val(),
                    "txtFiltroDesdeCV": $("#txtFiltroDesdeCV").val(),
                    "txtFiltroHastaCV": $("#txtFiltroHastaCV").val(),
                    "cbxFiltroBancoCV": $("#cbxFiltroBancoCV").val(),
                    "cbxTipoPagoC": $("#cbxTipoPagoC").val(),
                    "cbxEstadoC": $("#cbxEstadoC").val(),
                    "cbxTipoComprobanteSunat": $("#cbxTipoComprobanteSunat").val(),
                    "cbxOrdenar": $("#cbxOrdenar").val()
                });
            }
        },
        "columns": [
        { "data": "fecha_pago" },
        { "data": "voucher" },
        { "data": "estado_pago" },
        { "data": "fecha_emision" },
        { "data": "tipo_comprobante_sunat" },            
        { "data": "serie" },
        { "data": "numero" },
        { "data": "comprobante" },	
        { "data": "estado_cierre" }, 		
        { "data": "cliente" },
        { "data": "lote" },
        { "data": "fecha_vencimiento" },
        { "data": "letra" },
        { "data": "mora" },
        { "data": "tipo_comprobante" },
        { "data": "tipo_moneda" },
        { "data": "importe" },
        { "data": "tipo_cambio" },
        { "data": "pagado" },
        { "data": "banco" },
        { "data": "medio_pago" },
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
            }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        }

    });

    tablaPagosReporte = $('#TablaPagoComprobanteReporte').DataTable(options);
}

function CargaComprobante(id){
    var data = {
        "btnEditarComprobante": true,
        "idRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                $("#_ID_PAGO_CV").val(dato.data.id);
                $("#txtFechaEmisionCV").val(dato.data.fecha_emision);
                $("#cbxTipoComprobanteCV").val(dato.data.tipoComprobante);
                $("#txtSerieCV").val(dato.data.serie);
                $("#txtNumeroCV").val(dato.data.numero);

                $('#modalCargaComprobante').modal('show');
 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   

}

function GuardarComprobante(){
    var data = {
        "btnGuardarComprobante": true,
        "_ID_PAGO_CV": $("#_ID_PAGO_CV").val(),
        "txtFechaEmisionCV": $("#txtFechaEmisionCV").val(),
        "cbxTipoComprobanteCV": $("#cbxTipoComprobanteCV").val(),
        "txtSerieCV": $("#txtSerieCV").val(),
        "txtNumeroCV": $("#txtNumeroCV").val(),
        "ComprobanteCV": $("#ComprobanteCV").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
                     
                    var dat = $("#ComprobanteCV").val();
                    if(dat!=""){
                        var nombre = dato.name;
                        EnviarAdjuntoPago(nombre);                        
                    }

                    CargarPagosComprobante();
                    $('#TablaPagoComprobanteReporte').DataTable().ajax.reload(); 

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

    var file_data = $('#ComprobanteCV').prop('files')[0];   
     var form_data = new FormData();
     var dataa = nombre;                  
     form_data.append('file', file_data);
     form_data.append('data', dataa);
     //alert(form_data);                             
     $.ajax({
         url: '../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php', // point to server-side PHP script 
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

function FinalizarCarga(id){
    var data = {
        "btnCierreComprobante": true,
        "_ID_PAGO_CV": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
					if(parseInt(dato.variable) == 1){
    					InsertarPagsCab(dato.iddato); 
						CargarPagosComprobante();                 
						mensaje_alerta("\u00A1CORRECTO!", dato.data, "success"); 
					}else{
                        mensaje_alerta("\u00A1Error de Registro!", dato.data, "info");
                    }
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

function InsertarPagsCab(id){

	var data = {
		"btnProcesarIngEg": true,
		"id": id
	};  
	$.ajax({
		type: "POST",
		url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
		data: data,
		dataType: "json",
		success: function (dato) { 
			console.log(dato);
		},
		error: function (jqXHR, textStatus, errorThrown) {
		
			console.log(textStatus + ': ' + errorThrown);
			desbloquearPantalla();
		},
		timeout: timeoutDefecto
	});
}

function RestablecerCarga(id){
    var data = {
        "btnRestablecerComprobante": true,
        "_ID_PAGO_CV": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);
                if (dato.status == "ok") {
					var dato = data;
					ElimPagoCab(dato._ID_PAGO_CV);
                    CargarPagosComprobante();                 
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

function ElimPagoCab(id){
	var data = {
		"btnElimPago": true,
		"id": id
	};
	$.ajax({
		type: "POST",
		url: "../../models/M08_Administracion/M08MD02_RegPagos/M08MD02_RegPagos.php",
		data: data,
		dataType: "json",
		success: function (dato) {
			console.log(dato);
		},
		error: function (jqXHR, textStatus, errorThrown) {
		
			console.log(textStatus + ': ' + errorThrown);
			desbloquearPantalla();
		},
		timeout: timeoutDefecto
	});
}

