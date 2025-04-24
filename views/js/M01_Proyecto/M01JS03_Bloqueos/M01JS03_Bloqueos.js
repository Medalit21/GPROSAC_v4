var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    
    LlenarProyectos();
    LLenarZonas();    
    ListarLotes();
    ListarLotesReporte();
    
	
	$('#btnBuscarRegistro').click(function() {
        ListarLotes();
        $('#TablaLotesBloqueosReportes').DataTable().ajax.reload();
    });
    
    $('#btnLimpiar').click(function() {
        document.getElementById('bxFiltroProyectoBloqueos').selectedIndex = 0;
        document.getElementById('bxFiltroZonaBloqueos').selectedIndex = 0;
        document.getElementById('bxFiltroManzanaBloqueos').selectedIndex = 0;
        document.getElementById('bxFiltroLoteBloqueos').selectedIndex = 0;
        document.getElementById('bxFiltroEstadoBloqueos').selectedIndex = 0;
        document.getElementById('bxFiltroMotivoBloqueos').selectedIndex = 0;
        ListarLotes();
        $('#TablaLotesBloqueosReportes').DataTable().ajax.reload();
    });

    $('#bxFiltroProyectoBloqueos').change(function () {
        $("#bxFiltroZonaBloqueos").val("");
        $("#bxFiltroManzanaBloqueos").val("");
        var url = '../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_ListarTipos.php';
        var datos = {
            "ListarZonas": true,
            "idproyecto": $('#bxFiltroProyectoBloqueos').val()
        }
        llenarCombo(url, datos, "bxFiltroZonaBloqueos");
        document.getElementById('bxFiltroManzanaBloqueos').selectedIndex = 0;
    });

    $('#bxFiltroZonaBloqueos').change(function () {
        $("#bxFiltroManzanaBloqueos").val("");
        $("#bxFiltroLoteBloqueos").val("");
        var url = '../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_ListarTipos.php';
        var datos = {
            "ListarManzanas": true,
            "idzona": $('#bxFiltroZonaBloqueos').val()
        }
        llenarCombo(url, datos, "bxFiltroManzanaBloqueos");
        document.getElementById('bxFiltroLoteBloqueos').selectedIndex = 0;
    });

    $('#bxFiltroManzanaBloqueos').change(function () {
        $("#bxFiltroLoteBloqueos").val("");
        var url = '../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_ListarTipos.php';
        var datos = {
            "ListarLotes": true,
            "idmanzana": $('#bxFiltroManzanaBloqueos').val()
        };
        llenarCombo(url, datos, "bxFiltroLoteBloqueos");
    });
    
    $('#btnDesbloquearLote').click(function() {
        DesbloquearLote();        
    });

    $('#btnBloquearLote').click(function() {
        BloquearLote();        
    });
    
    $('#btnConsultarClave').click(function() {
        EnviarMensajeWts();        
    });

}


function LlenarProyectos() {
    var url = '../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_ListarTipos.php';
    var datos = {
        "ListarProyectosDefecto": true
    }
    llenarCombo(url, datos, "bxFiltroProyectoBloqueos");    
}

function LLenarZonas() {
    var url = '../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_ListarTipos.php';
    var datos = {
        "ListarZonasDefecto": true,
        "idproy": $('#bxFiltroProyectoBloqueos').val()
    }
    llenarCombo(url, datos, "bxFiltroZonaBloqueos");
}

function ListarLotes() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_ListarLotes.php";
    var dato = {
        "ReturnListaLotes": true,
        "bxFiltroProyectoBloqueos": $("#bxFiltroProyectoBloqueos").val(),
        "bxFiltroZonaBloqueos": $("#bxFiltroZonaBloqueos").val(),
        "bxFiltroManzanaBloqueos": $("#bxFiltroManzanaBloqueos").val(),
        "bxFiltroLoteBloqueos": $("#bxFiltroLoteBloqueos").val(),
        "bxFiltroEstadoBloqueos": $("#bxFiltroEstadoBloqueos").val(),
        "bxFiltroMotivoBloqueos": $("#bxFiltroMotivoBloqueos").val()
    };
    realizarJsonPost(url, dato, respuestaListarLotes, null, 10000, null);
}
 
function respuestaListarLotes(dato) {
    desbloquearPantalla();
    console.log(dato);
    LlenarListarLotes(dato.data);
}

var getTablaListarLotes = null;

function LlenarListarLotes(datos) {
    if (getTablaListarLotes) {
        getTablaListarLotes.destroy();
        getTablaListarLotes = null;
    }

    getTablaListarLotes = $('#TablaLotesBloqueos').DataTable({
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
            {"data": "id",
            "render": function (data, type, row) {
                if(row.descMotivo == "BLOQUEADO"){
                    return '<button class="btn btn-info btn-delete-action"  onclick="ModalDesbloquear(\'' + data + '\')"><i class="fas fa-lock"></i></button>';
                }else{
                    return '<button class="btn btn-info btn-edit-action"  onclick="ModalBloquear(\'' + data + '\')"><i class="fas fa-lock-open"></i></button>';
                }
            }},
            { "data": "zona" },
            { "data": "manzana" },
            { "data": "lote" },
            { "data": "area",
                 "render": function (data, type, row) {
                return data+' <label>m<sup>2</sup></label>';
            } },
            { "data": "tipoMoneda" },
			{ "data": "valorlotesolo" },
			{ "data": "valorlotecasa" },
            {
                "data": "estado",
                "render": function(data, type, row) {
                    var html = "";
                    if(row.descEstado == row.descMotivo){
                        html = '<span class="badge" style="background-color:'+ row.colorEstado +'; color: black; font-weight: bold;">' + row.descEstado + '</span>';
                    }else{
                        html = '<span class="badge" style="background-color:'+ row.colorEstado +'; color: black; font-weight: bold;">' + row.descEstado + '</span> ( <span class="badge" style="background-color:'+ row.colorMotivo +'; color: black; font-weight: bold;">' + row.descMotivo + '</span> )';
                    }
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

function  EnviarMensajeWts(){
    var data = {
        "btnEnviarMensajeWts": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
                //console.log(dato);               
                mensaje_alerta("\u00A1CORRECTO!", "Se envi\u00F3 el c\u00F3digo al nro de celular de gerencia.", "success");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });   
}

function ModalDesbloquear(id) {
   $("#txtIdLote").val(id);
   $("#txtCodigoDesbloqueoLote").val("");
   $('#modalDesbloquear').modal('show');           
}

function DesbloquearLote() {
    
    bloquearPantalla("Buscando...");
      var data = {
          "btnDesbloquearLote": true,
          "txtIdLote": $('#txtIdLote').val(),
          "txtCodigoDesbloqueoLote": $('#txtCodigoDesbloqueoLote').val()
      };
      $.ajax({
          type: "POST",
          url: "../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_Procesos.php",
          data: data,
          dataType: "json",
          success: function(dato) {
              desbloquearPantalla();
              if (dato.status == "ok") {
                    mensaje_alerta("\u00A1Correcto!", dato.data, "success");
                    $("#txtCodigoDesbloqueoLote").val("");
                    ListarLotes();
                    $('#TablaLotesBloqueosReportes').DataTable().ajax.reload();
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

function ModalBloquear(id) {
   $("#txtIdLote").val(id);
   $("#txtCodigoBloqueoLote").val("");
   $('#modalBloquear').modal('show');     
}

function BloquearLote() {
    
    bloquearPantalla("Buscando...");
      var data = {
          "btnBloquearLote": true,
          "txtIdLote": $('#txtIdLote').val(),
          "txtCodigoBloqueoLote": $('#txtCodigoBloqueoLote').val()
      };
      $.ajax({
          type: "POST",
          url: "../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_Procesos.php",
          data: data,
          dataType: "json",
          success: function(dato) {
              desbloquearPantalla();
              if (dato.status == "ok") {
                    mensaje_alerta("\u00A1Correcto!", dato.data, "success");
                    $("#txtCodigoBloqueoLote").val("");
                    ListarLotes();
                    $('#TablaLotesBloqueosReportes').DataTable().ajax.reload();
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

function ListarLotesReporte() {
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
            "url": "../../models/M01_Proyecto/M01MD06_Bloqueos/M01MD06_ListarLotes.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnListaLotes": true,
                    "bxFiltroProyectoBloqueos": $("#bxFiltroProyectoBloqueos").val(),
                    "bxFiltroZonaBloqueos": $("#bxFiltroZonaBloqueos").val(),
                    "bxFiltroManzanaBloqueos": $("#bxFiltroManzanaBloqueos").val(),
                    "bxFiltroLoteBloqueos": $("#bxFiltroLoteBloqueos").val(),
                    "bxFiltroEstadoBloqueos": $("#bxFiltroEstadoBloqueos").val(),
                    "bxFiltroMotivoBloqueos": $("#bxFiltroMotivoBloqueos").val()
                });
            }
        },
        "columns": [
            { "data": "zona" },
            { "data": "manzana" },
            { "data": "lote" },
            { "data": "area" },
            { "data": "tipoMoneda" },
			{ "data": "valorlotesolo" },
			{ "data": "valorlotecasa" },
            { "data": "descEstado"}
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

    tablaEmpresas = $('#TablaLotesBloqueosReportes').DataTable(options);
}







