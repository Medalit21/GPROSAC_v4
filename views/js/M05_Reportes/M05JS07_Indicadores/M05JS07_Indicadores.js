var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function() {
    Control();
});


function Control() {
    //REPORTE 001

    VerTabla1();
    VerTablaGrafica1();
    //InicializarTablaTotales();
    

    VerTabla2();
    VerTablaGrafica2();
    
    
    VerTabla3();
    VerTablaGrafica3();


    VerTabla4();
    VerTablaGrafica4();

    
    
    
    $('#btnBuscarRegistro').click(function() {
        VerTabla1();
        VerTablaGrafica1()
        //$('#TablaUsuarioReporte').DataTable().ajax.reload();
        
        VerTabla2();
        VerTablaGrafica2();      

    });
    
    $('#btnLimpiar').click(function() {
        document.getElementById('bxFiltroPeriodo').selectedIndex = 0;
       /* $("#txtdocumentoFiltro").val("");
        $("#txtNombresFiltro").val("");
        $("#txtApellidoFiltro").val("");*/
        
        VerTabla1();
        VerTablaGrafica1()
        //$('#TablaUsuarioReporte').DataTable().ajax.reload();
        
        VerTabla2();
        VerTablaGrafica2();  
    });
    
    $('#btnBuscarRegistro2').click(function() {
        
        VerTabla3();
        VerTablaGrafica3();

        VerTabla4();
        VerTablaGrafica4();


    });
    
    $('#btnLimpiar2').click(function() {
        document.getElementById('bxFiltroPeriodo2').selectedIndex = 0;
        /*$("#txtdocumentoFiltro").val("");
        $("#txtNombresFiltro").val("");
        $("#txtApellidoFiltro").val("");*/
        
        /*ListarCasa();
        $('#TablaCasaReporte').DataTable().ajax.reload();
        
        ListarUsuariosCasa();
        $('#TablaUsuarioCasaReporte').DataTable().ajax.reload();*/
		
		VerTabla3();
        VerTablaGrafica3();

        VerTabla4();
        VerTablaGrafica4();
        
        
    });
    
    $('#btnExportarPdf').click(function() {
        //IrReportePDF();
    });  
    
    
}


function IrReportePDF(){
    
     var data = {
        "Reporte1": true,
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //console.log(dato);
		    if (dato.status == "ok") {
		        window.open('M05SM07_Reporte.php?Prd='+dato.param); 
		    }else{
		        mensaje_alerta("\u00A1IMPORTANTE!", "No se encontr\u00F3 informaci\u00F3n para los datos ingresados", "info");
		    }
        
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
    
}


function ListarUsuarios() {

    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php";
    var dato = {
        "ReturnListaClientes": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "txtNombresFiltro": $("#txtNombresFiltro").val(),
        "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    realizarJsonPost(url, dato, respuestaListarUsuarios, null, 10000, null);
}

function respuestaListarUsuarios(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarUsuarios(dato.data);
}

var getTablaBusquedaCabGenerado = null;

function LlenarTablaListarUsuarios(datos) {
    if (getTablaBusquedaCabGenerado) {
        getTablaBusquedaCabGenerado.destroy();
        getTablaBusquedaCabGenerado = null;
    }

    getTablaBusquedaCabGenerado = $('#TablaUsuario').DataTable({
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
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

function ListarUsuariosReporte() {
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
            "url": "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                   "ReturnListaClientes": true,
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
                    "txtNombresFiltro": $("#txtNombresFiltro").val(),
                    "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
                    "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
                    
                });
            }
        },
        "columns": [
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

    tablaEmpresas = $('#TablaUsuarioReporte').DataTable(options);
}


function VerTabla1(){
      // bloquearPantalla("Buscando...");
    var data = {
        "ReturnListaClientes": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "txtNombresFiltro": $("#txtNombresFiltro").val(),
        "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            //console.log(dato);
            var resultado = dato.data;
            CompletarTabla(resultado);
            return;
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

var LlenarTabla = null;
function CompletarTabla(datos) {
    if (LlenarTabla) {
        LlenarTabla.destroy();
        LlenarTabla = null;
    }

    LlenarTabla = $('#TablaUsuario').DataTable({
        "data": datos,
        "columnDefs": [],
        ordering: false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "paging": false,
        destroy: true,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        columns: [
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();
            nb_cols = api.columns().nodes().length;
            var bb = 1;
            while (bb < nb_cols) {
                var pageTotal = api
                    .column(bb, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(bb).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                bb++;
            }
            var cc = 2;
            while (cc < nb_cols) {
                var pageTotal = api
                    .column(cc, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(cc).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                cc++;
            }
            var d = 3;
            while (d < nb_cols) {
                var pageTotal = api
                    .column(d, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(d).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                d++;
            }
            var e = 4;
            while (e < nb_cols) {
                var pageTotal = api
                    .column(e, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(e).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                e++;
            }
            var f = 5;
            while (f < nb_cols) {
                var pageTotal = api
                    .column(f, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(f).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                f++;
            }
            var k = 6;
            while (k < nb_cols) {
                var pageTotal = api
                    .column(k, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(k).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                k++;
            }
            var l = 7;
            while (l < nb_cols) {
                var pageTotal = api
                    .column(l, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(l).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                l++;
            }
            var m = 8;
            while (m < nb_cols) {
                var pageTotal = api
                    .column(m, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(m).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                m++;
            }
            var n = 9;
            while (n < nb_cols) {
                var pageTotal = api
                    .column(n, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(n).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                n++;
            }
            var g = 10;
            while (g < nb_cols) {
                var pageTotal = api
                    .column(g, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(g).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                g++;
            }
            var h = 11;
            while (h < nb_cols) {
                var pageTotal = api
                    .column(h, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(h).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                h++;
            }
            var i = 12;
            while (i < nb_cols) {
                var pageTotal = api
                    .column(i, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(i).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                i++;
            }
            var j = 13;
            while (j < nb_cols) {
                var pageTotal = api
                    .column(j, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(j).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                j++;
            }
        },
        "order": [
            [2, 'asc']
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
}

function VerTablaGrafica1(){
      // bloquearPantalla("Buscando...");
    var data = {
        "ReturnListaClientes1": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "txtNombresFiltro": $("#txtNombresFiltro").val(),
        "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            //console.log(dato);
            var resultado = dato.data;
            Grafica1(resultado);
            return;
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function Grafica1(dato){

    var lista_LoteSolo = Array();
    var lista_LoteCasa = Array();
    var anio = $("#bxFiltroPeriodo").val();

     for (var i = 0; i < dato.length; i++) {
        console.log(dato);
        if (i === 0) {
            lista_LoteCasa = [dato[i].monto_enero,
                dato[i].monto_febrero,
                dato[i].monto_marzo,
                dato[i].monto_abril,
                dato[i].monto_mayo,
                dato[i].monto_junio,
                dato[i].monto_julio,
                dato[i].monto_agosto,
                dato[i].monto_septiembre,
                dato[i].monto_octubre,
                dato[i].monto_noviembre,
                dato[i].monto_diciembre
            ];
           
            console.log(lista_LoteCasa);
        } else {
            lista_LoteSolo = [dato[i].monto_enero,
                dato[i].monto_febrero,
                dato[i].monto_marzo,
                dato[i].monto_abril,
                dato[i].monto_mayo,
                dato[i].monto_junio,
                dato[i].monto_julio,
                dato[i].monto_agosto,
                dato[i].monto_septiembre,
                dato[i].monto_octubre,
                dato[i].monto_noviembre,
                dato[i].monto_diciembre
            ];
        }
    }
    
    Highcharts.chart('Grafica1', {
    chart: {
            type: 'column'
    },
    title: {
        text: 'VENTAS MENSUALES SEGUN TIPO DE INMUEBLE - '+anio
    },
    subtitle: {
        text: 'Por Importe'
    },
    xAxis: {
        categories: [
            'Ene',
            'Feb',
            'Mar',
            'Abr',
            'May',
            'Jun',
            'Jul',
            'Ago',
            'Sep',
            'Oct',
            'Nov',
            'Dic'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Importe (dolares)'
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
        '<td style="padding:0"><b>{point.y:,.2f} dolares</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:,.0f}'
            }
        }
    },
    series: [{
        name: 'LOTE + CASA',
        data: lista_LoteCasa
                                        
    }, {
        name: 'SOLO LOTE',
        data: lista_LoteSolo
        }]
    });    
}












function ListarUsuariosConteo() {

    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php";
    var dato = {
        "ReturnListaClientesConteo": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "txtNombresFiltro": $("#txtNombresFiltro").val(),
        "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    realizarJsonPost(url, dato, respuestaListarUsuariosConteo, null, 10000, null);
}

function respuestaListarUsuariosConteo(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarUsuariosConteo(dato.data);
}

var getTablaBusquedaCabGeneradoConteo = null;

function LlenarTablaListarUsuariosConteo(datos) {
    if (getTablaBusquedaCabGeneradoConteo) {
        getTablaBusquedaCabGeneradoConteo.destroy();
        getTablaBusquedaCabGeneradoConteo = null;
    }

    getTablaBusquedaCabGeneradoConteo = $('#TablaUsuarioConteo').DataTable({
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
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

function ListarUsuariosConteoReporte() {
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
            "url": "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                   "ReturnListaClientesConteo": true,
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
                    "txtNombresFiltro": $("#txtNombresFiltro").val(),
                    "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
                    "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
                    
                });
            }
        },
        "columns": [
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

    tablaEmpresas = $('#TablaUsuarioConteoReporte').DataTable(options);
}


function VerTabla2(){
      // bloquearPantalla("Buscando...");
    var data = {
        "ReturnListaClientesConteo": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "txtNombresFiltro": $("#txtNombresFiltro").val(),
        "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            //console.log(dato);
            var resultado = dato.data;
            CompletarTabla2(resultado);
            return;
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

var LlenarTabla2 = null;
function CompletarTabla2(datos) {
    if (LlenarTabla2) {
        LlenarTabla2.destroy();
        LlenarTabla2 = null;
    }

    LlenarTabla2 = $('#TablaUsuarioConteo').DataTable({
        "data": datos,
        "columnDefs": [],
        ordering: false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "paging": false,
        destroy: true,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        columns: [
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();
            nb_cols = api.columns().nodes().length;
            var bb = 1;
            while (bb < nb_cols) {
                var pageTotal = api
                    .column(bb, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(bb).footer()).html(pageTotal);
                bb++;
            }
            var cc = 2;
            while (cc < nb_cols) {
                var pageTotal = api
                    .column(cc, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(cc).footer()).html(pageTotal);
                cc++;
            }
            var d = 3;
            while (d < nb_cols) {
                var pageTotal = api
                    .column(d, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(d).footer()).html(pageTotal);
                d++;
            }
            var e = 4;
            while (e < nb_cols) {
                var pageTotal = api
                    .column(e, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(e).footer()).html(pageTotal);
                e++;
            }
            var f = 5;
            while (f < nb_cols) {
                var pageTotal = api
                    .column(f, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(f).footer()).html(pageTotal);
                f++;
            }
            var k = 6;
            while (k < nb_cols) {
                var pageTotal = api
                    .column(k, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(k).footer()).html(pageTotal);
                k++;
            }
            var l = 7;
            while (l < nb_cols) {
                var pageTotal = api
                    .column(l, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(l).footer()).html(pageTotal);
                l++;
            }
            var m = 8;
            while (m < nb_cols) {
                var pageTotal = api
                    .column(m, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(m).footer()).html(pageTotal);
                m++;
            }
            var n = 9;
            while (n < nb_cols) {
                var pageTotal = api
                    .column(n, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(n).footer()).html(pageTotal);
                n++;
            }
            var g = 10;
            while (g < nb_cols) {
                var pageTotal = api
                    .column(g, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(g).footer()).html(pageTotal);
                g++;
            }
            var h = 11;
            while (h < nb_cols) {
                var pageTotal = api
                    .column(h, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(h).footer()).html(pageTotal);
                h++;
            }
            var i = 12;
            while (i < nb_cols) {
                var pageTotal = api
                    .column(i, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(i).footer()).html(pageTotal);
                i++;
            }
            var j = 13;
            while (j < nb_cols) {
                var pageTotal = api
                    .column(j, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(j).footer()).html(pageTotal);
                j++;
            }
        },
        "order": [
            [2, 'asc']
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
}

function VerTablaGrafica2(){
      // bloquearPantalla("Buscando...");
    var data = {
        "ReturnListaClientesConteo2": true,
        "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
        "txtNombresFiltro": $("#txtNombresFiltro").val(),
        "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
        "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            //console.log(dato);
            var resultado = dato.data;
            Grafica2(resultado);
            return;
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function Grafica2(dato){

    var lista_LoteSolo2 = Array();
    var lista_LoteCasa2 = Array();
    var anio = $("#bxFiltroPeriodo").val();

     for (var i = 0; i < dato.length; i++) {
        console.log(dato);
        if (i === 0) {
            lista_LoteCasa2 = [dato[i].monto_enero,
                dato[i].monto_febrero,
                dato[i].monto_marzo,
                dato[i].monto_abril,
                dato[i].monto_mayo,
                dato[i].monto_junio,
                dato[i].monto_julio,
                dato[i].monto_agosto,
                dato[i].monto_septiembre,
                dato[i].monto_octubre,
                dato[i].monto_noviembre,
                dato[i].monto_diciembre
            ];
           
            console.log(lista_LoteCasa2);
        } else {
            lista_LoteSolo2 = [dato[i].monto_enero,
                dato[i].monto_febrero,
                dato[i].monto_marzo,
                dato[i].monto_abril,
                dato[i].monto_mayo,
                dato[i].monto_junio,
                dato[i].monto_julio,
                dato[i].monto_agosto,
                dato[i].monto_septiembre,
                dato[i].monto_octubre,
                dato[i].monto_noviembre,
                dato[i].monto_diciembre
            ];
        }
    }
    
    Highcharts.chart('Grafica2', {
    chart: {
            type: 'column'
    },
    title: {
        text: 'VENTAS MENSUALES SEGUN TIPO DE INMUEBLE - '+anio
    },
    subtitle: {
        text: 'Por Cantidad de Lotes'
    },
    xAxis: {
        categories: [
            'Ene',
            'Feb',
            'Mar',
            'Abr',
            'May',
            'Jun',
            'Jul',
            'Ago',
            'Sep',
            'Oct',
            'Nov',
            'Dic'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Cantidad (Nro Lotes)'
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
        '<td style="padding:0;"> <b>{point.y} lotes</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },
    series: [{
        name: 'LOTE + CASA',
        data: lista_LoteCasa2
                                        
    }, {
        name: 'SOLO LOTE',
        data: lista_LoteSolo2
        }]
    });     
}









function ListarCasa() {

    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php";
    var dato = {
        "ReturnListaCasa": true,
        "bxFiltroPeriodo2": $("#bxFiltroPeriodo2").val()
    };
    realizarJsonPost(url, dato, respuestaListarCasa, null, 10000, null);
}

function respuestaListarCasa(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarCasa(dato.data);
}

var getTablaBusquedaCabCasa = null;

function LlenarTablaListarCasa(datos) {
    if (getTablaBusquedaCabCasa) {
        getTablaBusquedaCabCasa.destroy();
        getTablaBusquedaCabCasa = null;
    }

    getTablaBusquedaCabCasa = $('#TablaCasa').DataTable({
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
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

function ListarCasaReporte() {
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
            "url": "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                   "ReturnListaCasa": true,
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
                    "txtNombresFiltro": $("#txtNombresFiltro").val(),
                    "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
                    "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
                    
                });
            }
        },
        "columns": [
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

    tablaEmpresas = $('#TablaCasaReporte').DataTable(options);
}

function VerTabla3(){
      // bloquearPantalla("Buscando...");
    var data = {
        "ReturnListaCasa": true,
        "bxFiltroPeriodo2": $("#bxFiltroPeriodo2").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            //console.log(dato);
            var resultado = dato.data;
            CompletarTabla3(resultado);
            return;
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

var LlenarTabla3 = null;
function CompletarTabla3(datos) {
    if (LlenarTabla3) {
        LlenarTabla3.destroy();
        LlenarTabla3 = null;
    }

    LlenarTabla3 = $('#TablaCasa').DataTable({
        "data": datos,
        "columnDefs": [],
        ordering: false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "paging": false,
        destroy: true,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        columns: [
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();
            nb_cols = api.columns().nodes().length;
            var bb = 1;
            while (bb < nb_cols) {
                var pageTotal = api
                    .column(bb, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(bb).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                bb++;
            }
            var cc = 2;
            while (cc < nb_cols) {
                var pageTotal = api
                    .column(cc, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(cc).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                cc++;
            }
            var d = 3;
            while (d < nb_cols) {
                var pageTotal = api
                    .column(d, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(d).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                d++;
            }
            var e = 4;
            while (e < nb_cols) {
                var pageTotal = api
                    .column(e, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(e).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                e++;
            }
            var f = 5;
            while (f < nb_cols) {
                var pageTotal = api
                    .column(f, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(f).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                f++;
            }
            var k = 6;
            while (k < nb_cols) {
                var pageTotal = api
                    .column(k, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(k).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                k++;
            }
            var l = 7;
            while (l < nb_cols) {
                var pageTotal = api
                    .column(l, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(l).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                l++;
            }
            var m = 8;
            while (m < nb_cols) {
                var pageTotal = api
                    .column(m, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(m).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                m++;
            }
            var n = 9;
            while (n < nb_cols) {
                var pageTotal = api
                    .column(n, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(n).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                n++;
            }
            var g = 10;
            while (g < nb_cols) {
                var pageTotal = api
                    .column(g, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(g).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                g++;
            }
            var h = 11;
            while (h < nb_cols) {
                var pageTotal = api
                    .column(h, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(h).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                h++;
            }
            var i = 12;
            while (i < nb_cols) {
                var pageTotal = api
                    .column(i, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(i).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                i++;
            }
            var j = 13;
            while (j < nb_cols) {
                var pageTotal = api
                    .column(j, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(j).footer()).html(numeroConComas(pageTotal.toFixed(2)));
                j++;
            }
        },
        "order": [
            [2, 'asc']
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
}

function VerTablaGrafica3(){
      // bloquearPantalla("Buscando...");
    var data = {
       "ReturnListaCasa2": true,
        "bxFiltroPeriodo2": $("#bxFiltroPeriodo2").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            //console.log(dato);
            var resultado = dato.data;
            Grafica3(resultado);
            return;
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function Grafica3(dato){
    
    var lista_countryCA = Array();
    var lista_ocean = Array();
    var lista_invertida = Array();
    var anio = $("#bxFiltroPeriodo2").val();

     for (var i = 0; i < dato.length; i++) {
  
        if (i === 0) {
            lista_countryCA = [dato[i].monto_enero,
                dato[i].monto_febrero,
                dato[i].monto_marzo,
                dato[i].monto_abril,
                dato[i].monto_mayo,
                dato[i].monto_junio,
                dato[i].monto_julio,
                dato[i].monto_agosto,
                dato[i].monto_septiembre,
                dato[i].monto_octubre,
                dato[i].monto_noviembre,
                dato[i].monto_diciembre
            ];
           
      
        } else {
           if (i === 1) {
                lista_ocean = [dato[i].monto_enero,
                    dato[i].monto_febrero,
                    dato[i].monto_marzo,
                    dato[i].monto_abril,
                    dato[i].monto_mayo,
                    dato[i].monto_junio,
                    dato[i].monto_julio,
                    dato[i].monto_agosto,
                    dato[i].monto_septiembre,
                    dato[i].monto_octubre,
                    dato[i].monto_noviembre,
                    dato[i].monto_diciembre
                ];
               
            } else {
                lista_invertida = [dato[i].monto_enero,
                    dato[i].monto_febrero,
                    dato[i].monto_marzo,
                    dato[i].monto_abril,
                    dato[i].monto_mayo,
                    dato[i].monto_junio,
                    dato[i].monto_julio,
                    dato[i].monto_agosto,
                    dato[i].monto_septiembre,
                    dato[i].monto_octubre,
                    dato[i].monto_noviembre,
                    dato[i].monto_diciembre
                ];
            }
        }
    }
    

    Highcharts.chart('Grafica3', {
    chart: {
            type: 'column'
    },
    title: {
        text: 'VENTAS MENSUALES SEGUN TIPO DE CASA - '+anio
    },
    subtitle: {
        text: 'Por Importe'
    },
    xAxis: {
        categories: [
            'Ene',
            'Feb',
            'Mar',
            'Abr',
            'May',
            'Jun',
            'Jul',
            'Ago',
            'Sep',
            'Oct',
            'Nov',
            'Dic'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Importe (dolares)'
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
        '<td style="padding:0"><b>{point.y:,.0f} dolares</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:,.0f}'
            }
        }
    },
    series: [{
        name: 'COUNTRY CA',
        data: lista_countryCA
                                        
    },{
        name: 'OCEAN',
        data: lista_ocean
    }, {
        name: 'INVERTIDA',
        data: lista_invertida
        }]
    });      
}













function ListarUsuariosCasa() {

    bloquearPantalla("Buscando...");
    var url = "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php";
    var dato = {
        "ReturnListaClientesCasa": true,
        "bxFiltroPeriodo2": $("#bxFiltroPeriodo2").val()
    };
    realizarJsonPost(url, dato, respuestaListarUsuariosCasa, null, 10000, null);
}

function respuestaListarUsuariosCasa(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTablaListarUsuariosCasa(dato.data);
}

var getTablaBusquedaCabGeneradoCasa = null;

function LlenarTablaListarUsuariosCasa(datos) {
    if (getTablaBusquedaCabGeneradoCasa) {
        getTablaBusquedaCabGeneradoCasa.destroy();
        getTablaBusquedaCabGeneradoCasa = null;
    }

    getTablaBusquedaCabGeneradoCasa = $('#TablaUsuarioCasa').DataTable({
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
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

function ListarUsuariosCasaReporte() {
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
            "url": "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                   "ReturnListaClientesCasa": true,
                    "txtdocumentoFiltro": $("#txtdocumentoFiltro").val(),
                    "txtNombresFiltro": $("#txtNombresFiltro").val(),
                    "txtApellidoFiltro": $("#txtApellidoFiltro").val(),
                    "bxFiltroPeriodo": $("#bxFiltroPeriodo").val()
                    
                });
            }
        },
        "columns": [
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
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

    tablaEmpresas = $('#TablaUsuarioCasaReporte').DataTable(options);
}

function VerTabla4(){
      // bloquearPantalla("Buscando...");
    var data = {
        "ReturnListaClientesCasa2": true,
        "bxFiltroPeriodo2": $("#bxFiltroPeriodo2").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            //console.log(dato);
            var resultado = dato.data;
            CompletarTabla4(resultado);
            return;
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

var LlenarTabla4 = null;
function CompletarTabla4(datos) {
    if (LlenarTabla4) {
        LlenarTabla4.destroy();
        LlenarTabla4 = null;
    }

    LlenarTabla4 = $('#TablaUsuarioCasa').DataTable({
        "data": datos,
        "columnDefs": [],
        ordering: false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "paging": false,
        destroy: true,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        columns: [
            { "data": "tipo" },
            { "data": "monto_enero" },
            { "data": "monto_febrero" },
            { "data": "monto_marzo" },
            { "data": "monto_abril" },
            { "data": "monto_mayo"},
            { "data": "monto_junio"},
            { "data": "monto_julio"},
            { "data": "monto_agosto"},
            { "data": "monto_septiembre"},
            { "data": "monto_octubre"},
            { "data": "monto_noviembre"},
            { "data": "monto_diciembre"},
            { "data": "total"}
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();
            nb_cols = api.columns().nodes().length;
            var bb = 1;
            while (bb < nb_cols) {
                var pageTotal = api
                    .column(bb, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(bb).footer()).html(pageTotal);
                bb++;
            }
            var cc = 2;
            while (cc < nb_cols) {
                var pageTotal = api
                    .column(cc, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(cc).footer()).html(pageTotal);
                cc++;
            }
            var d = 3;
            while (d < nb_cols) {
                var pageTotal = api
                    .column(d, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(d).footer()).html(pageTotal);
                d++;
            }
            var e = 4;
            while (e < nb_cols) {
                var pageTotal = api
                    .column(e, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(e).footer()).html(pageTotal);
                e++;
            }
            var f = 5;
            while (f < nb_cols) {
                var pageTotal = api
                    .column(f, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(f).footer()).html(pageTotal);
                f++;
            }
            var k = 6;
            while (k < nb_cols) {
                var pageTotal = api
                    .column(k, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(k).footer()).html(pageTotal);
                k++;
            }
            var l = 7;
            while (l < nb_cols) {
                var pageTotal = api
                    .column(l, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(l).footer()).html(pageTotal);
                l++;
            }
            var m = 8;
            while (m < nb_cols) {
                var pageTotal = api
                    .column(m, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(m).footer()).html(pageTotal);
                m++;
            }
            var n = 9;
            while (n < nb_cols) {
                var pageTotal = api
                    .column(n, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(n).footer()).html(pageTotal);
                n++;
            }
            var g = 10;
            while (g < nb_cols) {
                var pageTotal = api
                    .column(g, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(g).footer()).html(pageTotal);
                g++;
            }
            var h = 11;
            while (h < nb_cols) {
                var pageTotal = api
                    .column(h, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(h).footer()).html(pageTotal);
                h++;
            }
            var i = 12;
            while (i < nb_cols) {
                var pageTotal = api
                    .column(i, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(i).footer()).html(pageTotal);
                i++;
            }
            var j = 13;
            while (j < nb_cols) {
                var pageTotal = api
                    .column(j, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                $(api.column(j).footer()).html(pageTotal);
                j++;
            }
        },
        "order": [
            [2, 'asc']
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
}

function VerTablaGrafica4(){
      // bloquearPantalla("Buscando...");
    var data = {
       "ReturnListaClientesCasa2": true,
        "bxFiltroPeriodo2": $("#bxFiltroPeriodo2").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M05_Reportes/M05MD07_Indicadores/M05MD07_ListarIndicadores.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            //console.log(dato);
            var resultado = dato.data;
            Grafica4(resultado);
            return;
        },
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function Grafica4(dato){

    var lista_countryCA = Array();
    var lista_ocean = Array();
    var lista_invertida = Array();
    var anio = $("#bxFiltroPeriodo2").val();

    for (var i = 0; i < dato.length; i++) {
  
        if (i === 0) {
            lista_countryCA = [dato[i].monto_enero,
                dato[i].monto_febrero,
                dato[i].monto_marzo,
                dato[i].monto_abril,
                dato[i].monto_mayo,
                dato[i].monto_junio,
                dato[i].monto_julio,
                dato[i].monto_agosto,
                dato[i].monto_septiembre,
                dato[i].monto_octubre,
                dato[i].monto_noviembre,
                dato[i].monto_diciembre
            ];
           
      
        } else {
           if (i === 1) {
                lista_ocean = [dato[i].monto_enero,
                    dato[i].monto_febrero,
                    dato[i].monto_marzo,
                    dato[i].monto_abril,
                    dato[i].monto_mayo,
                    dato[i].monto_junio,
                    dato[i].monto_julio,
                    dato[i].monto_agosto,
                    dato[i].monto_septiembre,
                    dato[i].monto_octubre,
                    dato[i].monto_noviembre,
                    dato[i].monto_diciembre
                ];
               
            } else {
                lista_invertida = [dato[i].monto_enero,
                    dato[i].monto_febrero,
                    dato[i].monto_marzo,
                    dato[i].monto_abril,
                    dato[i].monto_mayo,
                    dato[i].monto_junio,
                    dato[i].monto_julio,
                    dato[i].monto_agosto,
                    dato[i].monto_septiembre,
                    dato[i].monto_octubre,
                    dato[i].monto_noviembre,
                    dato[i].monto_diciembre
                ];
            }
        }
    }
    
    
    Highcharts.chart('Grafica4', {
    chart: {
            type: 'column'
    },
    title: {
        text: 'VENTAS MENSUALES SEGUN TIPO DE CASA - '+anio
    },
    subtitle: {
        text: 'Por Cantidad de Lotes'
    },
    xAxis: {
        categories: [
            'Ene',
            'Feb',
            'Mar',
            'Abr',
            'May',
            'Jun',
            'Jul',
            'Ago',
            'Sep',
            'Oct',
            'Nov',
            'Dic'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Importe (dolares)'
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
        '<td style="padding:0"><b>{point.y} dolares</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },
    series: [{
        name: 'COUNTRY CA',
        data: lista_countryCA
                                        
    }, {
        name: 'OCEAN',
        data: lista_ocean
    }, {
        name: 'INVERTIDA',
        data: lista_invertida
        }]
    });       
}
















