var timeoutDefecto = 1000 * 60;
var ListaSeleccionada = Array();
$(document).ready(function () {
    Control();
});


function Control() {
    ControlSesion();

    $('.modal').on("hidden.bs.modal", function (e) {
        if ($('.modal:visible').length) {
            $('body').addClass('modal-open');
        }
    });

    /* ------------- INICIALIZANDO --------- */


    $('#nuevo').click(function () {
        ControlSesion();
        Nuevo();       
    });

    $('#modificar').click(function () {
        ControlSesion();
        Modificar();       
    });

    $('#guardar').click(function () {  
        ControlSesion();      
        Guardar();      
    });

    $('#cancelar').click(function () {
        ControlSesion();
        Listar();
    });

    $('#busqueda_avanzada').click(function () {
        ControlSesion();
        Listar();
    });

  

    $('#btnFinalizarRegProy').click(function () {
        ControlSesion();
        mensaje_alerta("CORRECTO!", "Se ha completado el registro del Proyecto.", "success");
        $('#BusquedaRegistro').show();
        $('#ControlRegistro').hide();
    });

    Ninguno();


    $('#panel_zonas').hide();
    $('#panel_manzanas').hide();
    $('#panel_lotes').hide();

    $('#panel_zonasc').hide();
    $('#panel_manzanasc').hide();
    $('#panel_lotesc').hide();

    $('#btnproyectoc').click(function () {
        $('#panel_proyectoc').show();
        $('#panel_zonasc').hide();
        $('#panel_manzanasc').hide();
        $('#panel_lotesc').hide();

        $("#btnproyectoc").removeClass("btn-info");    
        $("#btnproyectoc").addClass("btn-success"); 
        
        $("#btnzonasc").removeClass("btn-success");    
        $("#btnzonasc").addClass("btn-info"); 

        $("#btnmanzanasc").removeClass("btn-success");    
        $("#btnmanzanasc").addClass("btn-info"); 

        $("#btnlotesc").removeClass("btn-success");    
        $("#btnlotesc").addClass("btn-info"); 
    });

    $('#btnzonasc').click(function () {
        $('#panel_proyectoc').hide();
        $('#panel_zonasc').show();
        $('#panel_manzanasc').hide();
        $('#panel_lotesc').hide();

        $("#btnzonasc").removeClass("btn-success");    
        $("#btnzonasc").addClass("btn-info"); 
        
        $("#btnzonasc").removeClass("btn-info");    
        $("#btnzonasc").addClass("btn-success"); 

        $("#btnmanzanasc").removeClass("btn-success");    
        $("#btnmanzanasc").addClass("btn-info"); 

        $("#btnlotesc").removeClass("btn-success");    
        $("#btnlotesc").addClass("btn-info"); 

        ListarZonasc();
        ListarZonasReportec();
    });

    $('#btnmanzanasc').click(function () {
        $('#panel_proyectoc').hide();
        $('#panel_zonasc').hide();
        $('#panel_manzanasc').show();
        $('#panel_lotesc').hide();

        $("#btnzonasc").removeClass("btn-success");    
        $("#btnzonasc").addClass("btn-info"); 
        
        $("#btnzonasc").removeClass("btn-success");    
        $("#btnzonasc").addClass("btn-info"); 

        $("#btnmanzanasc").removeClass("btn-info");    
        $("#btnmanzanasc").addClass("btn-success"); 

        $("#btnlotesc").removeClass("btn-success");    
        $("#btnlotesc").addClass("btn-info");
        
        ListarManzanasc();
        ListarManzanasReportec();
    });

    $('#btnlotesc').click(function () {
        $('#panel_proyectoc').hide();
        $('#panel_zonasc').hide();
        $('#panel_manzanasc').hide();
        $('#panel_lotesc').show();

        $("#btnzonasc").removeClass("btn-success");    
        $("#btnzonasc").addClass("btn-info"); 
        
        $("#btnzonasc").removeClass("btn-success");    
        $("#btnzonasc").addClass("btn-info"); 

        $("#btnmanzanasc").removeClass("btn-success");    
        $("#btnmanzanasc").addClass("btn-info"); 

        $("#btnlotesc").removeClass("btn-info");    
        $("#btnlotesc").addClass("btn-success"); 
    });

    $('#txtArea,#txtAreaZona,#txtAreaManzana,#txtAreaLote').keypress(function() {
        SoloNumeros_Punto();
    });

    /* ------------ PROYECTO ------------------------ */

    //UBIGEO 01
    $('#cbxDepartamentoDir').change(function () {
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


    $('#cbxProvinciaDir').change(function () {
        $("#cbxDistritoDir").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaDistritos": true,
            "ubigeo": $('#cbxProvinciaDir').val()
        };
        llenarCombo(url, datos, "cbxDistritoDir");
        $("#cbxDistritoDir").prop("disabled", false);
    });


    //UBIGEO 02

    $('#cbxDepartamentoDirc').change(function () {
        $("#cbxProvinciaDirc").val("");
        $("#cbxDistritoDirc").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaProvincia": true,
            "ubigeo": $('#cbxDepartamentoDirc').val()
        }
        llenarCombo(url, datos, "cbxProvinciaDirc");
        document.getElementById('cbxDistritoDirc').selectedIndex = 0;
        $("#cbxDistritoDirc").prop("disabled", true);
    });

    $('#cbxProvinciaDirc').change(function () {
        $("#cbxDistritoDirc").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaDistritos": true,
            "ubigeo": $('#cbxProvinciaDirc').val()
        };
        llenarCombo(url, datos, "cbxDistritoDirc");
        $("#cbxDistritoDirc").prop("disabled", false);
    });
    

    //UBIGEO 03

    $('#bxFiltroDepartamento').change(function () {
        $("#bxFiltroProvincia").val("");
        $("#bxFiltroDistrito").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaProvincia": true,
            "ubigeo": $('#bxFiltroDepartamento').val()
        }
        llenarCombo(url, datos, "bxFiltroProvincia");
        document.getElementById('bxFiltroDistrito').selectedIndex = 0;
        $("#bxFiltroDistrito").prop("disabled", true);
    });

    $('#bxFiltroProvincia').change(function () {
        $("#bxFiltroDistrito").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaDistritos": true,
            "ubigeo": $('#bxFiltroProvincia').val()
        };
        llenarCombo(url, datos, "bxFiltroDistrito");
        $("#bxFiltroDistrito").prop("disabled", false);
    });


    //UBIGEO 04

    $('#bxDepartamentoPopup').change(function () {
        $("#bxProvinciaPopup").val("");
        $("#bxDistritoPopup").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaProvincia": true,
            "ubigeo": $('#bxDepartamentoPopup').val()
        }
        llenarCombo(url, datos, "bxProvinciaPopup");
        document.getElementById('bxDistritoPopup').selectedIndex = 0;
        $("#bxDistritoPopup").prop("disabled", true);
    });

    $('#bxProvinciaPopup').change(function () {
        $("#bxDistritoPopup").val("");
        var url = '../../../models/General/BusquedaUbigeo.php';
        var datos = {
            "ReturnListaDistritos": true,
            "ubigeo": $('#bxProvinciaPopup').val()
        };
        llenarCombo(url, datos, "bxDistritoPopup");
        $("#bxDistritoPopup").prop("disabled", false);
    });



    $('#txtNombrecc').keyup(delayTime(function (e) {  
        CargarCodigoProy();
    }, 1000));  



    document.getElementById('txtPlano').onchange = function (e) {
        let reader = new FileReader();
        reader.readAsDataURL(e.target.files[0]);
        reader.onload = function () {
            let preview = document.getElementById('preview');
            image = document.createElement('img');
            image.src = reader.result;
            image.style.width = "200px";
            preview.innerHTML = '';
            preview.append(image);
        }
    }


    $('#btnproyecto').click(function () {
        $('#panel_proyecto').show();
        $('#panel_zonas').hide();
        $('#panel_manzanas').hide();
        $('#panel_lotes').hide();
    });


    $('#btnGuardarProyecto').click(function () {
        RegistrarProyecto();
    });


    $('#btnBuscarRegistro').click(function () {
        ControlSesion();
        ListarProyectos();
        $('#TablaProyectosReporte').DataTable().ajax.reload();               
    });


    $('#btnLimpiar').click(function () {
        ControlSesion();
        document.getElementById('txtFiltroNombre').selectedIndex = 0;
        document.getElementById('bxFiltroDepartamento').selectedIndex = 0;
        document.getElementById('bxFiltroProvincia').selectedIndex = 0;
        document.getElementById('bxFiltroDistrito').selectedIndex = 0;
        ListarProyectos();
        $('#TablaProyectosReporte').DataTable().ajax.reload();                    
    });



    /* ------------ ZONAS ---------------- */ 



    $('#txtNombreZona').keyup(delayTime(function (e) {  
        CargarCodigoZona();
    }, 1000));  
    
 
    $('#btnAñadirZona').click(function () {
        AñadirNuevaZona();
    });

    $('#btnGuardarZona').click(function () {
        ConfirmarContinuarZona();
    });


    /* -------- MANZANAS ---------*/

    $('#txtNombreManzana').keyup(delayTime(function (e) {  
        CargarCodigoMz();
    }, 1000)); 

    $('#cbxZonas').change(function () {
        BuscarZonas();
    });

    $('#btnGuardarManzana').click(function () {
        RegistrarManzanas();
    });

    $('#btnAgregarManzana').click(function () {
        ControlSesion();
        AgregarNuevaManzana();
    });


    let radios = document.querySelectorAll("#txtGeneracionManzanas");
    radios.forEach((x) => {
        x.dataset.val = x.checked; // guardamos el estado del radio button dentro del elemento
        x.addEventListener('click', (e) => {
            let element = e.target;
            if (element.dataset.val == 'false') {
                element.dataset.val = 'true';
                element.checked = true;
                $("#txtCodigoGeneracionManzanas").prop('disabled', false);
                $("#txtNumManzanasGeneradas").prop('disabled', false);
                $("#txtGeneracionManzanas").val("1");
            } else {
                element.dataset.val = 'false';
                element.checked = false;
                $("#txtCodigoGeneracionManzanas").prop('disabled', true);
                $("#txtNumManzanasGeneradas").prop('disabled', true);
                $("#txtGeneracionManzanas").val("0");
                $("#txtCodigoGeneracionManzanas").val("");
                $("#txtNumManzanasGeneradas").val("");
            }
        }, true);
    });



   /* ---------- LOTES ------------- */

    //SELECCION DE ZONAS Y MANZANAS PARA LOTES

    $('#cbxZonaslt').change(function () {
        $("#cbxManzanaslt").val("");
        var url = '../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_ListarManzanas.php';
        var datos = {
            "ReturnListaManzanas": true,
            "ubigeo": $('#cbxZonaslt').val()
        }
        llenarCombo(url, datos, "cbxManzanaslt");
    });

	/************* CARGA CORRELATIVO LOTE **************/
    $('#txtNombreLotee').keyup(delayTime(function (e) {  
        CargarCodigoLt();
    }, 1000));  
	
    $('#btnGuardarManzana').click(function () {
        ControlSesion();
        RegistrarManzanas();
    });

    $('#cbxManzanaslt').change(function () {
        ControlSesion();
        BuscarManzanas();
    });

    $('#btnAgregarLote').click(function () {
        ControlSesion();
        AgregarNuevoLote();
    });
  

    let radioss = document.querySelectorAll("#txtGeneracionLotes");
    radioss.forEach((x) => {
        x.dataset.val = x.checked; // guardamos el estado del radio button dentro del elemento
        x.addEventListener('click', (e) => {
            let element = e.target;
            if (element.dataset.val == 'false') {
                element.dataset.val = 'true';
                element.checked = true;
                $("#txtExtensionNombreLote").prop('disabled', false);
                $("#txtNroLotesGenerar").prop('disabled', false);
                $("#txtGeneracionLotes").val("1");
            } else {
                element.dataset.val = 'false';
                element.checked = false;
                $("#txtExtensionNombreLote").prop('disabled', true);
                $("#txtNroLotesGenerar").prop('disabled', true);
                $("#txtExtensionNombreLote").val("");
                $("#txtNroLotesGenerar").val("");
                $("#txtGeneracionLotes").val("0");
            }
        }, true);
    });

    

     // ----------------------------------- PARTE DE EDICION O MODIFICACION DE DATOS  ---------------------------------------
  

    //POPUP ZONAS

        $('#btnGuardarActualizado').click(function () {
            ControlSesion();
            ActualizarDatosProyecto();
        });

        $('#txtNombreZonacc').keyup(delayTime(function (e) {  
        CargarCorrelativoZona();
        }, 1000));  

        $('#btnAgregarZonaP').click(function () {
            ControlSesion();
            AgregarZonaPopup();
        });

        $('#btnLimpiarZonaP').click(function () {
            ControlSesion();
            LimpiarCamposZonas();
        });

        $('#btnGuardarZonaascc').click(function () {
            ControlSesion();
            GuardarDatosZonaPopup();
        });

 

    //POPUP MANZANAS

        $('#cbxZonascc').change(function () {
            CargarParametrosZona();
            $("#txtNombreManzanacc").prop("disabled", false);
            $("#txtAreaManzanacc").prop("disabled", false);
            $("#txtNumLotescc").prop("disabled", false);
            $("#txtGeneracionManzanascc").prop("disabled", false);
            $("#btnAgregarManzanacc").prop("disabled", false);
            $("#btnLimpiarManzanacc").prop("disabled", false);
            $("#cbxGeneracionAutom").prop("disabled", false);        
            BuscarManzanasPopup();  
        });

         //Generar informacion de correlativo de manzana para el codigo
        $('#txtNombreManzanacc').keyup(delayTime(function (e) {  
            CargarCorrelativoManzana();
        }, 1000)); 

        $('#btnAgregarManzanacc').click(function () {
            ControlSesion();
            AgregarManzanaPopup();
        });

        $('#btnGuardarManzanascc').click(function () {
            ControlSesion();
            GuardarDatosManzanaPopup();
        });

          //Boton de agregar nueva manzana
        $('#btnLimpiarManzanacc').click(function () {
            ControlSesion();
            LimpiarCamposManzanas();
        });

        $('#cbxGeneracionAutom').change(function () {
            ControlSesion();
            HabilitarGeneracionAutom();           
        });


     //POPUP LOTES

        $('#bxZonaslte').change(function () {
            $("#bxManzanaslte").val("");
            var url = '../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_ListarManzanas.php';
            var datos = {
                "ReturnListaManzanas": true,
                "ubigeo": $('#bxZonaslte').val()
            }
            llenarCombo(url, datos, "bxManzanaslte");
        });

         
        $('#bxManzanaslte').change(function () {
            CargarParametrosManzana();
            $("#txtNombreLotee").prop("disabled", false);
            $("#txtAreaLotee").prop("disabled", false);
            $("#cbxTipoMonedaLotee").prop("disabled", false);
            $("#txtValorCCLotee").prop("disabled", false);
            $("#txtValorSCLotee").prop("disabled", false);
            $("#cbxGeneracionAutomLote").prop("disabled", false);
            $("#btnAgregarLotees").prop("disabled", false);  
            $("#btnLimpiarLotees").prop("disabled", false);          
            BuscarLotesPopup();  
        });
		 
		$("#txtArea").on({
			"focus": function (event) {
				$(event.target).select();
			},
			"keyup": function (event) {
				$(event.target).val(function (index, value ) {
					return value.replace(/\D/g, "")
							.replace(/([0-9])([0-9]{2})$/, '$1.$2')
							.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
				});
			}
		});
		/*********** AGREGAR LOTE ***********/
		$('#btnAgregarLotees').click(function () {
            ControlSesion();
            AgregarLotePopup();
        });
       
        $('#btnLimpiarLotees').click(function () {
            ControlSesion();
            LimpiarCamposLotes();
        });

        $('#cbxGeneracionAutomLote').change(function () {
            HabilitarGeneracionAutomLote();           
        });

        $('#btnGuardarLotescc').click(function () {
            ControlSesion();
            GuardarDatosLotePopup();
        });

    //TIPO CASA
        LLenarTipoCasa();
        
        $('#btnAgregarTipoCasa').click(function () {
            ControlSesion();
            //AgregarLotePopup();
        });

        $('#cbxZonaTC').change(function () {
            $("#cbxManzanaTC").val("");
            var url = '../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_ListarManzanas.php';
            var datos = {
                "ReturnListaManzanas": true,
                "ubigeo": $('#cbxZonaTC').val()
            }
            llenarCombo(url, datos, "cbxManzanaTC");
        });
         
        $('#cbxManzanaTC').change(function () {
            $("#cbxTipoCasaTC").prop("disabled", false);
            document.getElementById('cbxTipoCasaTC').selectedIndex = 0;
            $("#cbxTipoCasaTC").focus();
            BuscarTiposCasa();
        });

        $('#btnAgregarTC').click(function () {
            AgregarTipoCasaMz();
        });

        $('#btnNuevoAgregarTC').click(function () {
            AbrirNuevoTipoCasa();
        });    

        limitarTextArea();

        // Nuevo tipo casa popup
        $('#btnNuevoTipoCasa').click(function () {
            NuevoTipoCasa();
        });    
		/********* POPUP *******/
        $('#btnGuardarTipoCasa').click(function () {
            GuardarNuevoTipoCasa();
        });
		/********* POPUP *******/
		
		/********* EDITAR *******/
		$('#btnGuardarTipCasEdit').click(function () {          
            GuardarTipoCasaEdit();
        });		
        /********* EDITAR *******/

}

function GuardarTipoCasaEdit() {
   bloquearPantalla("Buscando...");
    var data = {
        "btnGuardarTipoCasaEdit": true,
        "txtidTipoCPopupcc": $("#txtidTipoCPopupcc").val(),
        "txtTipoCasaPopup": $("#txtTipoCasaPopup").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                
					mensaje_alerta("¡Correcto!", dato.data, "success");
					  
					BuscarTiposCasa();
				  
                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error al Actualizar!", dato.data, "info");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });        
}


function limitarTextArea() {
    var texto = document.getElementById('txtAreaDescripcion').value;
    if(texto.length < 23) {
        return(true);
    }else{
        return(false);
    }
}

function LlenarCbxZonas(){
    document.getElementById('cbxZonaTC').selectedIndex = 0;
    var url = '../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarTipos.php';
    var datos = {
        "btnListarZonasPopup": true,
        "idproyecto": $("#txtidProyectocc").val()
    }
    llenarCombo(url, datos, "cbxZonaTC");     
}

var Estados = { Ninguno: "Ninguno", Nuevo: "Nuevo", Modificar: "Modificar", Guardado: "Guardado", SoloLectura: "SoloLectura", Consulta: "Consulta" };
var Estado = Estados.Ninguno;

function BloquearCamposProyecto(){
    $("#txtNombrecc").prop('disabled', true);
    $("#txtResponsablecc").prop('disabled', true);
    $("#txtAreacc").prop('disabled', true);
    $("#txtNroZonascc").prop('disabled', true);
    $("#txtDireccioncc").prop('disabled', true);
    $("#bxDepartamentoPopup").prop('disabled', true);
    $("#bxProvinciaPopup").prop('disabled', true);
    $("#bxDistritoPopup").prop('disabled', true);
}

function DesbloquearCamposProyecto(){
    $("#txtNombrecc").prop('disabled', false);
    $("#txtResponsablecc").prop('disabled', false);
    $("#txtAreacc").prop('disabled', false);
    $("#txtNroZonascc").prop('disabled', false);
    $("#txtDireccioncc").prop('disabled', false);
    $("#bxDepartamentoPopup").prop('disabled', false);
    $("#bxProvinciaPopup").prop('disabled', false);
    $("#bxDistritoPopup").prop('disabled', false);
    $("#txtNombrecc").focus();
}

function Listar() {
    Estado = Estados.Ninguno;
    $("#nuevo").prop('disabled', false);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', true);
    $("#guardar").prop('disabled', true);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', true);
    $('#BusquedaRegistro').show();
    $('#RegistroProyecto').hide();
    NuevoProyecto();
    ListarProyectos();
    $('#TablaProyectosReporte').DataTable().ajax.reload();
}

function Ninguno() {
    ControlSesion();
    Estado = Estados.Ninguno;
    $("#nuevo").prop('disabled', false);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', true);
    $("#guardar").prop('disabled', true);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', true);
    $('#BusquedaRegistro').show();
    $('#RegistroProyecto').hide();
    NuevoProyecto();
    ListarProyectos();
    ListarProyectosReporte();
}


function Modificar() {
    Estado = Estados.Modificar;
    $("#nuevo").prop('disabled', true);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', false);
    $("#guardar").prop('disabled', false);
    $("#eliminar").prop('disabled', true);
    $("#adjuntos").prop('disabled', false);
    $('#BusquedaRegistro').hide();
    $('#RegistroProyecto').show();
    $('#DetalleProyecto').show();
    DesbloquearCamposProyecto();
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

function Nuevo() {
    Estado = Estados.Nuevo;
    $("#nuevo").prop('disabled', true);
    $("#modificar").prop('disabled', true);
    $("#cancelar").prop('disabled', false);
    $("#guardar").prop('disabled', false);
    $("#eliminar").prop('disabled', true);
    NuevoProyecto();
    DesbloquearCamposProyecto();
    $('#BusquedaRegistro').hide();
    $('#RegistroProyecto').show();
    $('#DetalleProyecto').hide();
    //$("#txtDocumento").focus();
}


function NuevoProyecto(){

   $("#__ID_DATOS_PERSONALES, #__ID_DATOS_LABORALES, #txtidProyectoZona, #txtidProyectocc, #txtNombrecc, #txtCodigocc, #txtResponsablecc, #txtAreacc, #txtNroZonascc, #txtDireccioncc, #txtNombreZonacc, #txtCodigoZonacc, #txtAreaZonacc, #txtNroManzanacc, #txtAreaZonascc, #txtNroManzanascc, #txtNombreManzanacc, #txtCodigoManzanacc, #txtAreaManzanacc, #txtNumLotescc, #txtAreaMzEdicion, #txtNroLotesEdicion, #txtNombreLotee, #txtCodigoLotee, #txtAreaLotee, #txtValorCCLotee, #txtValorSCLotee, #txtExtensionNombreLotee").val("");
    document.getElementById('bxDepartamentoPopup').selectedIndex = 0;
    document.getElementById('bxProvinciaPopup').selectedIndex = 0;
    document.getElementById('bxDistritoPopup').selectedIndex = 0;
    document.getElementById('cbxZonascc').selectedIndex = 0;
    document.getElementById('bxTipoCasacc').selectedIndex = 0;
    document.getElementById('bxZonaslte').selectedIndex = 0;
    document.getElementById('bxManzanaslte').selectedIndex = 0;
    document.getElementById('cbxTipoMonedaLotee').selectedIndex = 0;
}


function LimpiarCamposZonas(){    
    $("#txtNombreZonacc").val("");
    $("#txtCodigoZonacc").val("");
    $("#txtAreaZonacc").val("");
    $("#txtNroManzanacc").val("");
}

function LimpiarCamposManzanas(){    
    document.getElementById('cbxGeneracionAutom').selectedIndex = 0;
    $("#txtNombreManzanacc").val("");
    $("#txtCodigoManzanacc").val("");
    $("#txtAreaManzanacc").val("");
    $("#txtNumLotescc").val("");
    $("#txtNroMzcc").val("");
    $("#txtNombreMzs").val("");
}

function LimpiarCamposLotes(){    
    document.getElementById('cbxGeneracionAutomLote').selectedIndex = 0;
    document.getElementById('cbxTipoMonedaLotee').selectedIndex = 0;
    $("#txtNombreLotee").val("");
    $("#txtCodigoLotee").val("");
    $("#txtAreaLotee").val("");
    $("#txtValorCCLotee").val("");
    $("#txtValorSCLotee").val("");
    $("#txtExtensionNombreLotee").val("");
    $("#txtNroLoteeGenerar").val("");
}


/*********************** ------- FUNCIONES DE OPERACION ACTUALIZAR PROYECTO ------- **********************/

/*********************** ------------------------- PROYECTO ------------------------- **********************/

function Guardar() {
    if (Estado == Estados.Nuevo) {
        RegistrarDatosProyecto();
    } else if (Estado == Estados.Modificar) {
        ActualizarDatosProyecto();
    } else {
        mensaje_alerta("\u00A1ADVERTENCIA!", "Ocurrio un problema en el registro, por favor, intente nuevamente.", "warning");
    }
}

//Llenar tabla Proyectos

function ListarProyectos() {
    bloquearPantalla("Buscando...");
    var url = "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarProyectos.php";
    var dato = {
        "ReturnListaProyectos": true,
        "txtFiltroNombre": $("#txtFiltroNombre").val(),
        "bxFiltroDepartamento": $("#bxFiltroDepartamento").val(),
        "bxFiltroProvincia": $("#bxFiltroProvincia").val(),
        "bxFiltroDistrito": $("#bxFiltroDistrito").val()
        
    };
    realizarJsonPost(url, dato, respuestaBuscarProyectosGenerados, null, 10000, null);
}

function respuestaBuscarProyectosGenerados(dato) {
    desbloquearPantalla();
    //console.log(dato);
    LlenarTabalaProyectosGenerados(dato.data);
}

var getTablaBusquedaProyectosGenerado = null;
function LlenarTabalaProyectosGenerados(datos) {
    if (getTablaBusquedaProyectosGenerado) {
        getTablaBusquedaProyectosGenerado.destroy();
        getTablaBusquedaProyectosGenerado = null;
    }

    getTablaBusquedaProyectosGenerado = $('#TablaProyectos').DataTable({
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
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="AbrirModalProyecto(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                }
            },
            { "data": "nombre" },
            { "data": "direccion" },
            { "data": "departamento" },
            { "data": "provincia" },
            { "data": "distrito" },
            { "data": "area",
                "render": function (data, type, row) {
                    return data+' <label>m<sup>2</sup></label>';
                }
            },
            { "data": "nro_zonas" },
            { "data": "nro_manzanas" },
            { "data": "nro_lotes" }
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


function ListarProyectosReporte() {
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
            "url": "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarProyectos.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "ReturnListaProyectos": true,
                    "txtFiltroNombre": $("#txtFiltroNombre").val(),
                    "bxFiltroDepartamento": $("#bxFiltroDepartamento").val(),
                    "bxFiltroProvincia": $("#bxFiltroProvincia").val(),
                    "bxFiltroDistrito": $("#bxFiltroDistrito").val()
                });
            }
        },
        "columns": [{
                "data": "id"
            },
            { "data": "nombre" },
            { "data": "direccion" },
            { "data": "departamento" },
            { "data": "provincia" },
            { "data": "distrito" },
            {
                "data": "area",
                "render": function (data, type, row) {
                    return data+' <label>m<sup>2</sup></label>';
                }
            },
            { "data": "nro_zonas" },
            { "data": "nro_manzanas" },
            { "data": "nro_lotes" }
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

    tablaEmpresas = $('#TablaProyectosReporte').DataTable(options);
}

//funcion generacion de popup para edicion de proyecto
function AbrirModalProyecto(id) {
  //$('#modalProyecto').modal('show');
   bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionarRegistro": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                 var resultado = dato.data;
                $("#txtidProyectocc").val(resultado.id);
                $("#txtNombrecc").val(resultado.nombre);
                $("#txtCodigocc").val(resultado.codigo);
                $("#txtCorrelativoccx").val(resultado.correlativo);
                $("#txtResponsablecc").val(resultado.responsable);
                $("#txtAreacc").val(resultado.area);
                $("#txtNroZonascc").val(resultado.nro_zonas);
                $("#txtDireccioncc").val(resultado.direccion);
                $("#bxDepartamentoPopup").val(resultado.departamento);
                LLenarProvinciaId(resultado.departamento, resultado.provincia);
                LLenarDistritoId(resultado.provincia, resultado.distrito);              
                $("#modificar").prop('disabled', false);
                //$('#modalProyecto').modal('show');
                BloquearCamposProyecto();
                $('#BusquedaRegistro').hide();
                $('#ControlRegistro').hide();
                $('#RegistroProyecto').show();
                $('#DetalleProyecto').show();               
                BuscarZonasPopup(resultado.id);
                BuscarManzanasPopup();
                BuscarLotesPopup();
                LlenarComboboxZonasPopup(resultado.id);                              
                LlenarComboboxZonasLotePopup(resultado.id);
                LlenarCbxZonas();
                BuscarTiposCasa();
                return;
            } else {
                mensaje_alerta("¡Error!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });         
}

//Llenar lista de provincia en base al valor del departamento
function LLenarProvinciaId(idDep, idPro) {
    var url = '../../../models/General/BusquedaUbigeo.php';
    var datos = {
        "ReturnListaProvincia": true,
        "ubigeo": idDep
    }
    llenarComboSelecionar(url, datos, "bxProvinciaPopup", idPro);
}

//Llenar lista de distrito en base al valor de la provincia
function LLenarDistritoId(idProv, idDist) {
    var url = '../../../models/General/BusquedaUbigeo.php';
    var datos = {
        "ReturnListaDistritos": true,
        "ubigeo": idProv
    };
    llenarComboSelecionar(url, datos, "bxDistritoPopup", idDist);
}

function RegistrarDatosProyecto() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnRegistrarDatosProyecto": true,
        "txtNombrecc": $("#txtNombrecc").val(),
        "txtCodigocc": $("#txtCodigocc").val(),
        "txtCorrelativoccx": $("#txtCorrelativoccx").val(),
        "txtResponsablecc": $("#txtResponsablecc").val(),
        "txtAreacc": $("#txtAreacc").val(),
        "txtNroZonascc": $("#txtNroZonascc").val(),
        "txtDireccioncc": $("#txtDireccioncc").val(),
        "bxDepartamentoPopup": $("#bxDepartamentoPopup").val(),
        "bxProvinciaPopup": $("#bxProvinciaPopup").val(),
        "bxDistritoPopup": $("#bxDistritoPopup").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Correcto!", dato.data, "success");                    
                    //$('#TablaProyectos').DataTable().ajax.reload();
                    ListarProyectos();
                    $('#TablaProyectosReporte').DataTable().ajax.reload();
                    AbrirComplementoProyecto(dato.idproy);

                    $("#nuevo").prop('disabled', false);
                    $("#modificar").prop('disabled', false);
                    $("#cancelar").prop('disabled', true);
                    $("#guardar").prop('disabled', true);
                    $("#eliminar").prop('disabled', false);

                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error al Actualizar!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function AbrirComplementoProyecto(id) {
    //$('#modalProyecto').modal('show');
     bloquearPantalla("Buscando...");
      var data = {
          "btnSeleccionarRegistro": true,
          "IdRegistro": id
      };
      $.ajax({
          type: "POST",
          url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
          data: data,
          dataType: "json",
          success: function(dato) {
              desbloquearPantalla();
              if (dato.status == "ok") {
                   var resultado = dato.data;
                  $("#txtidProyectocc").val(resultado.id);
                  LLenarProvinciaId(resultado.departamento, resultado.provincia);
                  LLenarDistritoId(resultado.provincia, resultado.distrito);              
                  $("#modificar").prop('disabled', false);
                  //$('#modalProyecto').modal('show');
                  BloquearCamposProyecto();
                  $('#DetalleProyecto').show();               
                  BuscarZonasPopup(resultado.id);
                  LlenarComboboxZonasPopup(resultado.id);                              
                  LlenarComboboxZonasLotePopup(resultado.id);
                  return;
              } else {
                  mensaje_alerta("¡Error!", dato.data + "\n" + dato.dataDB, "error");
              }
          },
          error: function(error) {
              console.log(error);
              desbloquearPantalla();
          },
          timeout: timeoutDefecto
      });         
  }

// BOTON ACTUALIZAR PROYECTO - boton Guardar en vista Popup
function ActualizarDatosProyecto() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnActualizarDatosProyecto": true,
        "txtidProyectocc": $("#txtidProyectocc").val(),
        "txtNombrecc": $("#txtNombrecc").val(),
        "txtCodigocc": $("#txtCodigocc").val(),
        "txtResponsablecc": $("#txtResponsablecc").val(),
        "txtAreacc": $("#txtAreacc").val(),
        "txtNroZonascc": $("#txtNroZonascc").val(),
        "txtDireccioncc": $("#txtDireccioncc").val(),
        "bxDepartamentoPopup": $("#bxDepartamentoPopup").val(),
        "bxProvinciaPopup": $("#bxProvinciaPopup").val(),
        "bxDistritoPopup": $("#bxDistritoPopup").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Correcto!", dato.data, "success");                    
                    //$('#TablaProyectos').DataTable().ajax.reload();
                    ListarProyectos();
                    $('#TablaProyectosReporte').DataTable().ajax.reload();
                    $("#nuevo").prop('disabled', false);
                    $("#modificar").prop('disabled', false);
                    $("#cancelar").prop('disabled', true);
                    $("#guardar").prop('disabled', true);
                    $("#eliminar").prop('disabled', true);
                    BloquearCamposProyecto();

                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error al Actualizar!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}


/*********************** ------------------------- ZONAS ------------------------- **********************/

//Llenar tabla con datos de las zonas relacionadas al proyecto seleccionado
function BuscarZonasPopup(id) {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarEdicion.php";
    var dato = {
        "ListarZonas": true,
        "txtidProyectoZona": id
    };
    realizarJsonPost(url, dato, respuestaBuscarZonas, null, 10000, null);
}

function respuestaBuscarZonas(dato) {
    desbloquearPantalla();
    if (dato.status === "ok") {
        ListaZonasPopup = dato.data;
        CargarTablaZonas(ListaZonasPopup);
        return;
    }
}

var getTablaZonasPopup = null;

function CargarTablaZonas(data) {
    if (getTablaZonasPopup) {
        getTablaZonasPopup.destroy();
        getTablaZonasPopup = null;
    }
    getTablaZonasPopup = $('#TablaZonasPopup').DataTable({
        "data": data,
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [
        ],
        "info": true,
        "searching": false,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        "bLengthChange": false,
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action"  onclick="ActualizarDatosZona(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a>\
                    <button class="btn btn-info btn-delete-action"  onclick="EliminarDatosZona(\'' + data + '\')" title="Eliminar"><i class="fas fa-trash"></i></button>';
                }
            },
            { "data": "nombre" },
            { "data": "nro_manzanas"},
            { "data": "area" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });
}

//Funcion de generacion POPUP y carga de datos de zona seleccionada
function ActualizarDatosZona(id) {
    
    //$('#modalEditarZona').modal('show');
   bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionarZona": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                 var resultado = dato.data;
                $("#txtidZonaPopupcc").val(resultado.id); 
                $("#txtNombreZonaPopup").val(resultado.nombre);          
                $("#txtNroManzanasPopup").val(resultado.nro_manzanas); 
                $("#txtAreaPopup").val(resultado.area); 

                $('#modalEditarZona').modal('show');                           

                return;
            } else {
                mensaje_alerta("¡Error!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });        
}

//Accion de guardar cambios en datos modificados de la zona
function GuardarDatosZonaPopup() {
    //$('#modalEditarZona').modal('show');
   bloquearPantalla("Buscando...");
    var data = {
        "btnGuardarDatosZona": true,
        "txtidZonaPopupcc": $("#txtidZonaPopupcc").val(),
        "txtNombreZonaPopup": $("#txtNombreZonaPopup").val(),
        "txtNroManzanasPopup": $("#txtNroManzanasPopup").val(),
        "txtAreaPopup": $("#txtAreaPopup").val(),
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Correcto!", dato.data, "success");
                    BuscarZonasPopup(dato.idproyecto);
                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error al Actualizar!", dato.data, "info");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });        
}

//Funciones para eliminar una zona, 1era: consulta de accion, 2da: Ejecucion
function EliminarDatosZona(id){
   // var idpart = id;       
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnConsultarDatosZona": true,
        "idZona": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                
                var resultado = dato.data;
                var manzanas = resultado.manzanas;
                var lotes = resultado.lotes;
                var mensaje = 'La zona que desea eliminar tiene asignado '+manzanas+' manzanas y '+lotes+' lotes. Si elimina la zona se perderá dicha información. ¿Desea eliminar la zona?';
                mensaje_eliminar_parametro(mensaje, EliminarZonaPopup, resultado.id);

            } else {
                mensaje_alerta("¡Error al Eliminar!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}
function EliminarZonaPopup(id){
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnEliminarZona": true,
        "idZona": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    var resultado = dato.data;
                    mensaje_alerta("¡Correcto!", "La zona a sido eliminada con éxito.", "success");                    
                    BuscarZonasPopup(resultado.idproyecto);
                    LlenarComboboxZonasPopup(resultado.idproyecto);                
                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error al Eliminar!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//Generacion de codigo correlativo para registro de zona
function CargarCorrelativoZona(){
    var timeoutDefecto = 1000 * 60;
    //bloquearPantalla("Procesando...");
    var data = {
        "btnCargarCodigoZona": true,
        "txtidProyectoZona": $("#txtidProyectocc").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD02_Zonas/M01MD02_CargarCodigo.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            if (dato.status == "ok") {               
                $("#txtCodigoZonacc").val(dato.codigo);
                $("#txtCorrelativoZonacc").val(dato.correlativo);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//Grabar una nueva zona
function AgregarZonaPopup(){
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnAgregarZonaPopup": true,
        "txtidProyectocc": $("#txtidProyectocc").val(),
        "txtNombreZonacc": $("#txtNombreZonacc").val(),
        "txtCodigoZonacc": $("#txtCodigoZonacc").val(),
        "txtCorrelativoZonacc": $("#txtCorrelativoZonacc").val(),
        "txtAreaZonacc": $("#txtAreaZonacc").val(),
        "txtNroManzanacc": $("#txtNroManzanacc").val(),
        "txtNroZonascc": $("#txtNroZonascc").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Correcto!", dato.data, "success");
                    BuscarZonasPopup(dato.idproyecto);
                    LlenarComboboxZonasPopup(dato.idproyecto);
                    $("#txtNombreZonacc").val("");
                    $("#txtCodigoZonacc").val("");
                    $("#txtCorrelativoZonacc").val("");
                    $("#txtAreaZonacc").val("");
                    $("#txtNroManzanacc").val("");

                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error al Registrar!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}



/*********************** ------------------------- MANZANAS ------------------------- **********************/

//Llenar lista de zonas del proyecto
function LlenarComboboxZonasPopup(id){
    document.getElementById('cbxZonascc').selectedIndex = 0;
    var url = '../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarTipos.php';
    var datos = {
        "btnListarZonasPopup": true,
        "idproyecto": id
    }
    llenarCombo(url, datos, "cbxZonascc");     
}

//Llenar la tabla manzanas relacionadas con la zona seleccionada
function BuscarManzanasPopup() {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarEdicion.php";
    var dato = {
        "ListarManzanas": true,
        "idZona": $("#cbxZonascc").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarManzanas, null, 10000, null);
}

function respuestaBuscarManzanas(dato) {
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status === "ok") {
        ListaManzanasPopup = dato.data;        
        CargarTablaManzanas(ListaManzanasPopup);
        return;
    }
}

var getTablaManzanassPopup = null;
function CargarTablaManzanas(data) {
    if (getTablaManzanassPopup) {
        getTablaManzanassPopup.destroy();
        getTablaManzanassPopup = null;
    }
    getTablaManzanassPopup = $('#TablaManzanacc').DataTable({
        "data": data,
        "order": [
            [1, "asc"]
        ],
        "columnDefs": [
        ],
        "info": true,
        "searching": false,
        "pageLength": 10,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        "bLengthChange": false,
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)" class="btn btn-edit-action" onclick="ActualizarDatosManzana(\'' + data + '\')" title="Editar"><i class="fas fa-pencil-alt"></i></a>\
                    <button class="btn btn-info btn-delete-action"  onclick="EliminarDatosManzana(\'' + data + '\')" title="Eliminar"><i class="fas fa-trash"></i></button>';
                }
            },
            { "data": "nombre" },
            { "data": "nro_lotes" },
            { "data": "area" },
            { "data": "tipo_casa" }
        ],
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        },
        "order": [
            [1, 'asc']
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

//Cargar datos adicionales relacionadas a la zona seleccionada
function CargarParametrosZona() {

    $("#txtAreaZonascc").val("");
    $("#txtNroManzanascc").val("");
    LimpiarCamposNuevaManzana();
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnCargarParametrosZona": true,
        "idZona": $("#cbxZonascc").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    var resultado = dato.data;
                    $("#txtAreaZonascc").val(resultado.area);
                    $("#txtNroManzanascc").val(resultado.manzanas);

                }, 100);
                return;
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}


//funcion para generar la vista popup de edicion de manzana
function ActualizarDatosManzana(id) {
    
    //$('#modalEditarZona').modal('show');
   bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionarManzana": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                 var resultado = dato.data;
                $("#txtidManzanaPopupcc").val(resultado.id); 
                $("#txtNombreManzanaPopup").val(resultado.nombre);          
                $("#txtNroLotesPopup").val(resultado.nro_lotes); 
                $("#txtAreaManzanaPopup").val(resultado.area); 

                $('#modalEditarManzana').modal('show');                           

                return;
            } else {
                mensaje_alerta("¡Error!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });        
}

//boton guardar cambios en el popup de editar de la manzana
function GuardarDatosManzanaPopup() {
    
    //$('#modalEditarZona').modal('show');
   bloquearPantalla("Buscando...");
    var data = {
        "btnGuardarDatosManzana": true,
        "txtidManzanaPopupcc": $("#txtidManzanaPopupcc").val(),
        "txtNombreManzanaPopup": $("#txtNombreManzanaPopup").val(),
        "txtNroLotesPopup": $("#txtNroLotesPopup").val(),
        "txtAreaManzanaPopup": $("#txtAreaManzanaPopup").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Correcto!", dato.data, "success");
                    BuscarManzanasPopup();
                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error al Actualizar!", dato.data, "info");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });        
}

//Accion de eliminar manzanas, 1ero: consulta, 2do: ejecucion
function EliminarDatosManzana(id){
   // var idpart = id;       
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnConsultarDatosManzana": true,
        "idManzana": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                
                var resultado = dato.data;
                var lotes = resultado.lotes;
                var mensaje = 'La Manzana que desea eliminar tiene asignado '+lotes+' lotes. Si elimina la manzana se perderá dicha información. ¿Desea eliminar la manzana?';
                mensaje_eliminar_parametro(mensaje, EliminarManzanaPopup, resultado.id);

            } else {
                mensaje_alerta("¡Error al Eliminar!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}
function EliminarManzanaPopup(id){
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnEliminarManzana": true,
        "idManzana": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    var resultado = dato.data;
                    mensaje_alerta("¡Correcto!", "La zona a sido eliminada con éxito.", "success");                    
                    BuscarManzanasPopup();               
                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error al Eliminar!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}


//Generacion de correlativo para insercion de nueva manzana
function CargarCorrelativoManzana(){
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnCargarCodigoMz": true,
        "cbxZonas": $("#cbxZonascc").val()

    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD03_Manzanas/M01MD03_CargarCodigo.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {               
                $("#txtCodigoManzanacc").val(dato.codigo);
                $("#txtCorrelativoManzanacc").val(dato.correlativo);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function ValidarCamposManzanas() {
    var flat = true;
    if($("#cbxGeneracionAutom").val()==0){
        if ($("#cbxZonascc").val() === "" || $("#cbxZonascc").val() === null) {
            $("#cbxZonascc").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione la ZONA a la que asignar\u00E1 los registros de manzanas.", "info");
            $("#cbxZonasccHtml").html('(Requerido)');
            $("#cbxZonasccHtml").show();
            flat = false;
        } else if ($("#txtNombreManzanacc").val() === "") {
            $("#txtNombreManzanacc").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el nombre de la manzana", "info");
            $("#txtNombreManzanaccHtml").html('(Requerido)');
            $("#txtNombreManzanaccHtml").show();
            flat = false;
        } else if ($("#txtAreaManzanacc").val() === "") {
            $("#txtAreaManzanacc").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el area de la manzana", "info");
            $("#txtAreaManzanaccHtml").html('(Requerido)');
            $("#txtAreaManzanaccHtml").show();
            flat = false;
        } else if ($("#txtNumLotescc").val() === "") {
            $("#txtNumLotescc").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Nro de Lotes que tendr\u00E1 la manzana", "info");
            $("#txtNumLotesccHtml").html('(Requerido)');
            $("#txtNumLotesccHtml").show();
            flat = false;
        } 
    }else{
        if ($("#txtAreaManzanacc").val() === "") {
            $("#txtAreaManzanacc").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el area de la manzana", "info");
            $("#txtAreaManzanaccHtml").html('(Requerido)');
            $("#txtAreaManzanaccHtml").show();
            flat = false;
        } else if ($("#txtNumLotescc").val() === "") {
            $("#txtNumLotescc").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Nro de Lotes que tendr\u00E1 la manzana", "info");
            $("#txtNumLotesccHtml").html('(Requerido)');
            $("#txtNumLotesccHtml").show();
            flat = false;
        } else if ($("#txtNroMzcc").val() === "" || $("#txtNroMzcc").val() === null) {
            $("#txtNroMzcc").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Nro de manzanas a generar.", "info");
            $("#txtNroMzccHtml").html('(Requerido)');
            $("#txtNroMzccHtml").show();
            flat = false;
        } else if ($("#txtNombreMzs").val() === "") {
            $("#txtNombreMzs").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese la extension del nombre que se asignar\u00E1 por defecto a todos los registros generados.", "info");
            $("#txtNombreMzsHtml").html('(Requerido)');
            $("#txtNombreMzsHtml").show();
            flat = false;
        }
    }
    return flat;
}

//Boton para registro de nueva manzana para la zona seleccionada
function AgregarManzanaPopup(){    
    if (ValidarCamposManzanas()) {
        var timeoutDefecto = 1000 * 60;
        bloquearPantalla("Procesando...");
        var data = {
            "btnAgregarManzanaPopup": true,
            "cbxZonascc": $("#cbxZonascc").val(),
            "txtNombreManzanacc": $("#txtNombreManzanacc").val(),
            "txtCodigoManzanacc": $("#txtCodigoManzanacc").val(),
            "txtCorrelativoManzanacc": $("#txtCorrelativoManzanacc").val(),
            "txtAreaManzanacc": $("#txtAreaManzanacc").val(),
            "txtNumLotescc": $("#txtNumLotescc").val(),
            "cbxGeneracionAutom": $("#cbxGeneracionAutom").val(),
            "txtNroMzcc": $("#txtNroMzcc").val(),
            "txtNombreMzs": $("#txtNombreMzs").val()
        };		
		//console.log("Datos enviados:", data);exit;
        $.ajax({
            type: "POST",
            url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                if (dato.status == "ok") {
                    setTimeout(function () {
                        mensaje_alerta("¡Correcto!", dato.data, "success");
						
                        //BuscarZonasPopup(dato.idproyecto);
                        //LlenarComboboxZonasPopup(dato.idproyecto);
						BuscarManzanasPopup();

                        $("#txtNombreManzanacc").val("");
                        $("#txtCodigoManzanacc").val("");
                        $("#txtCorrelativoManzanacc").val("");
                        $("#txtAreaManzanacc").val("");
                        $("#txtNumLotescc").val("");
                        //$("#cbxGeneracionAutom").val("");
                        $("#txtNroMzcc").val("");
                        $("#txtNombreMzs").val("");
                        LimpiarCamposNuevaManzana();
                    }, 100);
                    return;
                } else {
                    mensaje_alerta("¡Error al Registrar!", dato.data, "info");
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


function LimpiarCamposNuevaManzana(){

    $("#txtNombreManzanacc").val("");
    $("#txtCodigoManzanacc").val("");
    $("#txtCorrelativoManzanacc").val("");
    $("#txtAreaManzanacc").val("");
    $("#txtNumLotescc").val("");
	//$("#cbxGeneracionAutom").val("");
	$("#txtNroMzcc").val("");
	$("#txtNombreMzs").val("");
}

function HabilitarGeneracionAutom(){
    var campoHabilitar = $("#cbxGeneracionAutom").val();
    if(campoHabilitar==0){
        $("#txtNroMzcc").val("");
        $("#txtNombreMzs").val("");
        $("#txtNroMzcc").prop("disabled", true);
        $("#txtNombreMzs").prop("disabled", true);

        $("#txtNombreManzanacc").val("");
        $("#txtNombreManzanacc").prop("disabled", false);
    }else{
        $("#txtNroMzcc").val("");
        $("#txtNombreMzs").val("");
        $("#txtNroMzcc").prop("disabled", false);
        $("#txtNombreMzs").prop("disabled", false);

        $("#txtNombreManzanacc").val("");
        $("#txtNombreManzanacc").prop("disabled", true);
    }

}

/*********************** ------------------------- LOTES ------------------------- **********************/
//Llenar lista de zonas del proyecto
function LlenarComboboxZonasLotePopup(id){
    document.getElementById('bxZonaslte').selectedIndex = 0;
    var url = '../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarTipos.php';
    var datos = {
        "btnListarZonasPopup": true,
        "idproyecto": id
    }
    llenarCombo(url, datos, "bxZonaslte");     
}

//Cargar datos adicionales relacionadas a la zona seleccionada
function CargarParametrosManzana() {

    $("#txtAreaMzEdicion").val("");
    $("#txtNroLotesEdicion").val("");
    LimpiarCamposNuevaManzana();
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnCargarParametrosManzana": true,
        "idManazana": $("#bxManzanaslte").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    var resultado = dato.data;
                    $("#txtAreaMzEdicion").val(resultado.area);
                    $("#txtNroLotesEdicion").val(resultado.lotes);

                }, 100);
                return;
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//Llenar la tabla lotes relacionadas con la manzana seleccionada
function BuscarLotesPopup() {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarEdicion.php";
    var dato = {
        "ListarLotes": true,
        "idManzana": $("#bxManzanaslte").val()
    };
    realizarJsonPost(url, dato, respuestaBuscarLotes, null, 10000, null);
}

function respuestaBuscarLotes(dato) {
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status === "ok") {
        ListaLotesPopup = dato.data;        
        CargarTablaLotes(ListaLotesPopup);
        return;
    }
}

var getTablaLotessPopup = null;
function CargarTablaLotes(data) {
	
    if (getTablaLotessPopup) {
        getTablaLotessPopup.destroy();
		$('#TablaLotesEdicion tbody').empty(); // ✅ Limpia el contenido manualmente
        getTablaLotessPopup = null;
    }
	
    getTablaLotessPopup = $('#TablaLotesEdicion').DataTable({
        "data": data,
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [
        ],
        "info": true,
        "searching": false,
        "pageLength": 5,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        "bLengthChange": false,
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)"  onclick="ActualizarDatosLote(\'' + data + '\')"><img src="../../../images/editar.png" width="25px" height="25px" ></a>\
                    <button class="btn btn-info btn-delete-action"  onclick="EliminarLote(\'' + data + '\')"><i class="fas fa-trash"></i></button>';
                }
            },
            { "data": "nombre" },
            { "data": "area" },
            { "data": "tipo_moneda" },
            { "data": "valorConCasa" },
            { "data": "valorSinCasa" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });
}

//funcion para generar la vista popup de edicion de manzana
function ActualizarDatosLote(id) {
    
    //$('#modalEditarZona').modal('show');
   bloquearPantalla("Buscando...");
    var data = {
        "btnSeleccionarLote": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                 var resultado = dato.data;
                $("#txtidLotePopupcc").val(resultado.id); 
                $("#txtNombreLotePopup").val(resultado.nombre);          
                $("#txtAreaLotePopup").val(resultado.area); 
                $("#cbxTipoMonedaPopup").val(resultado.tipo_moneda);
                $("#txtValorCCasaPopup").val(resultado.valor_con_casa); 
                $("#txtValorSCasaPopup").val(resultado.valor_sin_casa);  

                $('#modalEditarLote').modal('show');                           

                return;
            } else {
                mensaje_alerta("¡Error!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });        
}

function GuardarDatosLotePopup() {
    
   bloquearPantalla("Buscando...");
    var data = {
        "btnGuardarDatosLote": true,
        "txtidLotePopupcc": $("#txtidLotePopupcc").val(),
        "txtNombreLotePopup": $("#txtNombreLotePopup").val(),
        "txtAreaLotePopup": $("#txtAreaLotePopup").val(),
        "cbxTipoMonedaPopup": $("#cbxTipoMonedaPopup").val(),
        "txtValorCCasaPopup": $("#txtValorCCasaPopup").val(),
        "txtValorSCasaPopup": $("#txtValorSCasaPopup").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                mensaje_alerta("¡Correcto!", dato.data, "success");
                 BuscarLotesPopup();
                return;
            } else {
                mensaje_alerta("¡Error al Actualizar!", dato.data, "info");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });        
}

function EliminarLote(id){
    var mensaje = 'Si elimina el lote seleccionados se perder\u00E1 todos los datos relacionados al registro, tales como datos de reservas, ventas y pagos. ¿Est\u00E1 seguro(a) de eliminar el registro?';
    mensaje_eliminar_parametro(mensaje, EliminarDatosLote, id);
}

function EliminarDatosLote(id){
    // var idpart = id;       
     var timeoutDefecto = 1000 * 60;
     bloquearPantalla("Procesando...");
     var data = {
         "btnEliminarLote": true,
         "idLote": id
     };
     $.ajax({
         type: "POST",
         url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
         data: data,
         dataType: "json",
         success: function (dato) {
             desbloquearPantalla();
             if (dato.status == "ok") {
                mensaje_alerta("Correcto!", "Se elimin\u00F3 el lote seleccionado.", "success"); 
				BuscarLotesPopup();
             } else {
                 mensaje_alerta("Error al Eliminar!", dato.data, "info");
             }
 
         },
         error: function (jqXHR, textStatus, errorThrown) {
             console.log(textStatus + ': ' + errorThrown);
             desbloquearPantalla();
         },
         timeout: timeoutDefecto
     });
 }

function ValidarCamposLotesT() {
    var flat = true;
    if($("#cbxGeneracionAutomLote").val()==0){
        if ($("#bxZonaslte").val() === "" || $("#bxZonaslte").val() === null) {
            $("#bxZonaslte").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el campo ZONA.", "info");
            $("#bxZonaslteHtml").html('(Requerido)');
            $("#bxZonaslteHtml").show();
            flat = false;
        } else if ($("#bxManzanaslte").val() === "") {
            $("#bxManzanaslte").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el campo MANZANA", "info");
            $("#bxManzanaslteHtml").html('(Requerido)');
            $("#bxManzanaslteHtml").show();
            flat = false;
        } else if ($("#txtNombreLotee").val() === "") {
            $("#txtNombreLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el nombre del lote.", "info");
            $("#txtNombreLoteeHtml").html('(Requerido)');
            $("#txtNombreLoteeHtml").show();
            flat = false;
        } else if ($("#txtAreaLotee").val() === "") {
            $("#txtAreaLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el \u00E1rea  que tendr\u00E1 el lote", "info");
            $("#txtAreaLoteeHtml").html('(Requerido)');
            $("#txtAreaLoteeHtml").show();
            flat = false;
        } else if ($("#cbxTipoMonedaLotee").val() === "") {
            $("#cbxTipoMonedaLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el tipo moneda del precio del lote", "info");
            $("#cbxTipoMonedaLoteeHtml").html('(Requerido)');
            $("#cbxTipoMonedaLoteeHtml").show();
            flat = false;
        }else if ($("#txtValorCCLotee").val() === "") {
            $("#txtValorCCLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el valor del lote + casa", "info");
            $("#txtValorCCLoteeHtml").html('(Requerido)');
            $("#txtValorCCLoteeHtml").show();
            flat = false;
        }else if ($("#txtValorSCLotee").val() === "") {
            $("#txtValorSCLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el valor del lote solo", "info");
            $("#txtValorSCLoteeHtml").html('(Requerido)');
            $("#txtValorSCLoteeHtml").show();
            flat = false;
        }
    }else{
        if ($("#bxZonaslte").val() === "" || $("#bxZonaslte").val() === null) {
            $("#bxZonaslte").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccione el campo ZONA.", "info");
            $("#bxZonaslteHtml").html('(Requerido)');
            $("#bxZonaslteHtml").show();
            flat = false;
        } else if ($("#bxManzanaslte").val() === "") {
            $("#bxManzanaslte").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Seleccionar el campo MANZANA", "info");
            $("#bxManzanaslteHtml").html('(Requerido)');
            $("#bxManzanaslteHtml").show();
            flat = false;
        } else if ($("#txtExtensionNombreLotee").val() === "") {
            $("#txtExtensionNombreLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese,la extension del nombre del lotes a generar.", "info");
            $("#txtExtensionNombreLoteeHtml").html('(Requerido)');
            $("#txtExtensionNombreLoteeHtml").show();
            flat = false;
        } else if ($("#txtAreaLotee").val() === "") {
            $("#txtAreaLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el \u00E1rea  que tendr\u00E1 el lote", "info");
            $("#txtAreaLoteeHtml").html('(Requerido)');
            $("#txtAreaLoteeHtml").show();
            flat = false;
        } else if ($("#cbxTipoMonedaLotee").val() === "") {
            $("#cbxTipoMonedaLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el tipo moneda del precio del lote", "info");
            $("#cbxTipoMonedaLoteeHtml").html('(Requerido)');
            $("#cbxTipoMonedaLoteeHtml").show();
            flat = false;
        }else if ($("#txtValorCCLotee").val() === "") {
            $("#txtValorCCLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el valor del lote + casa", "info");
            $("#txtValorCCLoteeHtml").html('(Requerido)');
            $("#txtValorCCLoteeHtml").show();
            flat = false;
        }else if ($("#txtValorSCLotee").val() === "") {
            $("#txtValorSCLotee").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el valor del lote solo", "info");
            $("#txtValorSCLoteeHtml").html('(Requerido)');
            $("#txtValorSCLoteeHtml").show();
            flat = false;
        }else if ($("#txtNroLoteeGenerar").val() === "") {
            $("#txtNroLoteeGenerar").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, Ingrese el Nro de Lotes a generar.", "info");
            $("#txtNroLoteeGenerarHtml").html('(Requerido)');
            $("#txtNroLoteeGenerarHtml").show();
            flat = false;
        }
    }
    return flat;
}

function HabilitarGeneracionAutomLote(){
    var campoHabilitar = $("#cbxGeneracionAutomLote").val();
    if(campoHabilitar==0){
        $("#txtExtensionNombreLotee").val("");
        $("#txtNroLoteeGenerar").val("");
        $("#txtExtensionNombreLotee").prop("disabled", true);
        $("#txtNroLoteeGenerar").prop("disabled", true);

        $("#txtNombreLotee").val("");
        $("#txtNombreLotee").prop("disabled", false);
    }else{
        $("#txtExtensionNombreLotee").val("");
        $("#txtNroLoteeGenerar").val("");
        $("#txtExtensionNombreLotee").prop("disabled", false);
        $("#txtNroLoteeGenerar").prop("disabled", false);

        $("#txtNombreLotee").val("");
        $("#txtNombreLotee").prop("disabled", true);
    }

}

function AgregarLotePopup(){    
    if (ValidarCamposLotesT()) {
        var timeoutDefecto = 1000 * 60;
        bloquearPantalla("Procesando...");
        var data = {
            "btnAgregarLotePopups": true,
            "bxZonaslte": $("#bxZonaslte").val(),
            "bxManzanaslte": $("#bxManzanaslte").val(),
            "txtCodigoLotee": $("#txtCodigoLotee").val(),
            "txtCorrelativoLote": $("#txtCorrelativoLote").val(),
            "txtAreaLotee": $("#txtAreaLotee").val(),
            "txtNombreLotee": $("#txtNombreLotee").val(),
            "txtValorCCLotee": $("#txtValorCCLotee").val(),
            "txtValorSCLotee": $("#txtValorSCLotee").val(),
            "cbxGeneracionAutomLote": $("#cbxGeneracionAutomLote").val(),
            "txtExtensionNombreLotee": $("#txtExtensionNombreLotee").val(),
            "txtNroLoteeGenerar": $("#txtNroLoteeGenerar").val(),
            "cbxTipoMonedaLoteess": $("#cbxTipoMonedaLoteess").val(),
			"txtAreaMzEdicion": $("#txtAreaMzEdicion").val()
        };
		//console.log(data);
        $.ajax({
            type: "POST",
            url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
            data: data,
            dataType: "json",
            success: function (dato) {
                desbloquearPantalla();
                if (dato.status == "ok") {
                    setTimeout(function () {
                        mensaje_alerta("¡Correcto!", dato.data, "success");
                        BuscarLotesPopup();
					
						$("#bxZonaslte").val("");
						$("#bxManzanaslte").val("");
						$("#txtAreaLotee").val("");
						$("#cbxTipoMonedaLoteess").val("");
						$("#txtValorCCLotee").val("");
						$("#txtValorSCLotee").val("");
						$("#txtExtensionNombreLotee").val("");
						$("#txtNroLoteeGenerar").val("");
						
						
                    }, 100);
                    return;
                } else {
                    mensaje_alerta("¡Error al Registrar!", dato.data, "info");
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

/*********************** ------------------------- TIPO CASA ------------------------- **********************/

function LLenarTipoCasa() {
  
    var url = "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php";
    var datos = {
        "ReturnTipoCasa": true
    }
    llenarCombo(url, datos, 'cbxTipoCasalista');
}

function BuscarTiposCasa() {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarEdicion.php";
    var dato = {
        "ListarTiposCasa": true,
        "idManzana": $("#cbxManzanaTC").val()
    };
    realizarJsonPost(url, dato, respuestaTipoCasa, null, 10000, null);
}

function respuestaTipoCasa(dato) {
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status === "ok") {
        ListaLotesPopup = dato.data;        
        CargarTablaTipoCasa(ListaLotesPopup);
        return;
    }
}

var getTablaTipoCasasPopup = null;
function CargarTablaTipoCasa(data) {
    if (getTablaTipoCasasPopup) {
        getTablaTipoCasasPopup.destroy();
        $('#TablaTipoCasa tbody').empty(); // ✅ Limpia el contenido manualmente
        getTablaTipoCasasPopup = null;
    }
    getTablaTipoCasasPopup = $('#TablaTipoCasa').DataTable({
        "data": data,
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [
        ],
        "info": true,
        "searching": false,
        "pageLength": 5,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        "bLengthChange": false,
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<button class="btn btn-info btn-delete-action"  onclick="EliminarTipoCasas(\'' + data + '\')"><i class="fas fa-trash"></i></button>';
                }
            },
            { "data": "contador" },
            { "data": "zona" },
            { "data": "manzana" },
            { "data": "tipoCasa" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });
}

function EliminarTipoCasas(id){
    var mensaje = 'Si elimina el Tipo Casa seleccionado se perder\u00E1 todos los datos relacionados al registro, tales como datos de Manzanas y zonas. ¿Est\u00E1 seguro(a) de eliminar el registro?';
    mensaje_eliminar_parametro(mensaje, EliminarDatosTipCasa, id);
}

function EliminarDatosTipCasa(id){
    // var idpart = id;       
     var timeoutDefecto = 1000 * 60;
     bloquearPantalla("Procesando...");
     var data = {
         "btnEliminarTipCasa": true,
         "idTipCasa": id
     };
     $.ajax({
         type: "POST",
         url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
         data: data,
         dataType: "json",
         success: function (dato) {
             desbloquearPantalla();
             if (dato.status == "ok") {
                mensaje_alerta("Correcto!", "Se elimin\u00F3 el tipo de casa seleccionado.", "success"); 
			
				BuscarTiposCasa();
             } else {
                 mensaje_alerta("Error al Eliminar!", dato.data, "info");
             }
 
         },
         error: function (jqXHR, textStatus, errorThrown) {
             console.log(textStatus + ': ' + errorThrown);
             desbloquearPantalla();
         },
         timeout: timeoutDefecto
     });
 }
 
 function ActualizarDatosTipoCasa(id) {
    
    //$('#modalEditarZona').modal('show');
   bloquearPantalla("Buscando...");
    var data = {
        "btnEditarTipoCasa": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SeleccionEdicionProyecto.php",
        data: data,
        dataType: "json",
        success: function(dato) {
			console.log(dato);

            desbloquearPantalla();
            if (dato.status == "ok") {
				
                 var resultado = dato.data;
                $("#txtidTipoCPopupcc").val(resultado.id); 
                $("#txtZonaTipoCPopup").val(resultado.zona);          
                $("#txtManzanaTipoCPopup").val(resultado.manzana);          
                $("#txtTipoCasaPopup").val(resultado.tipoCasa);          
                $('#modalEditarTipoCasa').modal('show');                           

                return;
            } else {
                mensaje_alerta("¡Error!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });        
}


function AgregarTipoCasaMz(){    
    var timeoutDefecto = 1000 * 60;
     bloquearPantalla("Procesando...");
     var data = {
         "btnAgregarTipoCasaMz": true,
         "cbxZonaTC": $("#cbxZonaTC").val(),
         "cbxManzanaTC": $("#cbxManzanaTC").val(),
         "cbxTipoCasaTC": $("#cbxTipoCasaTC").val()
     };
     $.ajax({
         type: "POST",
         url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
         data: data,
         dataType: "json",
         success: function (dato) {
             desbloquearPantalla();
             if (dato.status == "ok") {
                mensaje_alerta("CORRECTO!", dato.data, "success");
                BuscarTiposCasa();
             } else {
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

function AbrirNuevoTipoCasa(){    
    $('#modalNuevoTipoCasa').modal('show');
    var documento = "archivos/ejemplo.pdf"
    PintarPDF(documento);
    PopupTiposCasa();
    NuevoTipoCasa();
}

function PintarPDF(dato) {
    var html = "";
    html += "<object class='pdfview' type='application/pdf' data='"+dato+"'></object> ";
    $("#my_pdf_viewer").html(html);
}

/******************** POPUP NUEVO TIPO CASA *************************/ 
function PopupTiposCasa() {
    bloquearPantalla("Cargando...");
    var url = "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_ListarEdicion.php";
    var dato = {
        "ListarTiposCasaPopup": true
    };
    realizarJsonPost(url, dato, respuestaPopupTipoCasa, null, 10000, null);
}

function respuestaPopupTipoCasa(dato) {
    desbloquearPantalla();
    //console.log(dato);
    if (dato.status === "ok") {
        ListaLotesPopup = dato.data;        
        CargarPopupTipoCasa(ListaLotesPopup);
        return;
    }
}

var getTablaTipoCasasPp = null;
function CargarPopupTipoCasa(data) {
    if (getTablaTipoCasasPp) {
        getTablaTipoCasasPp.destroy();
        getTablaTipoCasasPp = null;
    }
    getTablaTipoCasasPp = $('#TablaRegTipoCasa').DataTable({
        "data": data,
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [
        ],
        "info": false,
        "searching": false,
        "pageLength": 3,
        "lengthMenu": [
            [10, -1],
            [10, "Todos"]
        ],
        "bLengthChange": false,
        "select": {
            style: 'single'
        },
        "keys": {
            keys: [13 /* ENTER */ , 38 /* UP */ , 40 /* DOWN */ ]
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)"  onclick="SeleccionarTipoCasa(\'' + data + '\')"><img src="../../../images/editar.png" width="25px" height="25px" ></a>\
                    <button class="btn btn-info btn-delete-action"  onclick="EliminarTipoCasas(\'' + data + '\')"><i class="fas fa-trash"></i></button>';
                }
            },
            { "data": "nombre" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });
}

function SeleccionarTipoCasa(id) {
 
    var data = {
        "btnSeleccionarTipoCasa": true,
        "IdRegistro": id
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            //console.log(dato);
            if (dato.status == "ok") {                 
                $("#txtidTipoCasa").val(dato.id);
                $("#txtNombreTipoCasaPop").val(dato.Nombre);          
                $("#txtNroHabitaciones").val(dato.habitacion); 
                $("#txtNroBanios").val(dato.banios);
                $("#txtNroCocina").val(dato.cocinas); 
                $("#txtNroSala").val(dato.salas);
                $("#txtAreaDescripcion").val(dato.descripcion);
                PintarPDF("archivos/"+dato.plano);       
                
                $("#btnGuardarTipoCasa").prop('disabled', true);
                $("#btnNuevoTipoCasa").prop('disabled', false);
                $("#btnModificarTipoCasa").prop('disabled', false);
            } else {
                mensaje_alerta("¡Error!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
    });        
}

function ModificarTipoCasa(id) {
    
   bloquearPantalla("Buscando...");
    var data = {
        "btnModificarTipoCasa": true,
        "txtidTipoCasa": $("#txtidTipoCasa").val(),
        "txtNombreTipoCasaPop": $("#txtNombreTipoCasaPop").val(),          
        "txtNroHabitaciones": $("#txtNroHabitaciones").val(), 
        "txtNroBanios": $("#txtNroBanios").val(),
        "txtNroCocina": $("#txtNroCocina").val(),
        "txtNroSala": $("#txtNroSala").val(),
        "txtAreaDescripcion": $("#txtAreaDescripcion").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
        data: data,
        dataType: "json",
        success: function(dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {                 
                 mensaje_alerta("CORRECTO!", dato.data, "success");
                return;
            } else {
                mensaje_alerta("ERROR!", dato.data + "\n" + dato.dataDB, "error");
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });      
}



function NuevoTipoCasa(){
    var documento = "archivos/ejemplo.pdf"
    PintarPDF(documento);
    $("#txtidTipoCasa, #txtNombreTipoCasa, #txtNroHabitaciones, #txtNroBanios, #txtNroCocina, #txtNroSala, #ficheros, #txtAreaDescripcion").val("");
    $("#btnGuardarTipoCasa").prop('disabled', false);
}

function ValidarCamposNuevoTipoCasa() {
    var flat = true;
        if ($("#txtNombreTipoCasaPopup").val() === "" || $("#txtNombreTipoCasaPopup").val() === null) {            
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese el nombre del tipo de casa a registrar.", "info");
            $("#txtNombreTipoCasaPopupHtml").html('(Requerido)');
            $("#txtNombreTipoCasaPopupHtml").show();
            $("#txtNombreTipoCasaPopup").focus();
            flat = false;
        } else if ($("#txtNroHabitaciones").val() === "") {
            $("#txtNroHabitaciones").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese el nro de habitaciones para el tipo de casa.", "info");
            $("#txtNroHabitacionesHtml").html('(Requerido)');
            $("#txtNroHabitacionesHtml").show();
            flat = false;
        } else if ($("#txtNroBanios").val() === "") {
            $("#txtNroBanios").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese el nro de ba\u00F1os para el tipo de casa.", "info");
            $("#txtNroBaniosHtml").html('(Requerido)');
            $("#txtNroBaniosHtml").show();
            flat = false;
        } else if ($("#txtNroCocina").val() === "") {
            $("#txtNroCocina").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese el nro de cocinas para el tipo de casa.", "info");
            $("#txtNroCocinaHtml").html('(Requerido)');
            $("#txtNroCocinaHtml").show();
            flat = false;
        } else if ($("#txtNroSala").val() === "") {
            $("#txtNroSala").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, ingrese el nro de salas para el tipo de casa.", "info");
            $("#txtNroSalaHtml").html('(Requerido)');
            $("#txtNroSalaHtml").show();
            flat = false;
        } else if ($("#ficheros").val() === "") {
            $("#ficheros").focus();
            mensaje_alerta("\u00A1Falta Dato!", "Por favor, seleccione el plano del tipo de casa.", "info");
            $("#ficherosHtml").html('(Requerido)');
            $("#ficherosHtml").show();
            flat = false;
        } 
    return flat;
}

function EnviarPlano(){

    var file_data = $('#ficheros').prop('files')[0];   
     var form_data = new FormData();                  
     form_data.append('file', file_data);
     //alert(form_data);                             
     $.ajax({
         url: '../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_SubirArchivo.php', // point to server-side PHP script 
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

function GuardarNuevoTipoCasa(){    
    var timeoutDefecto = 1000 * 60;
    if(ValidarCamposNuevoTipoCasa()){
     bloquearPantalla("Procesando...");
     var data = {
         "btnRegistrarNuevoTipoCasa": true,
         "txtNombreTipoCasa": $("#txtNombreTipoCasaPopup").val(),
         "txtNroHabitaciones": $("#txtNroHabitaciones").val(),
         "txtNroBanios": $("#txtNroBanios").val(),
         "txtNroCocina": $("#txtNroCocina").val(),
         "txtNroSala": $("#txtNroSala").val(),
         "txtAreaDescripcion": $("#txtAreaDescripcion").val()
     };
     $.ajax({
         type: "POST",
         url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Procesos.php",
         data: data,
         dataType: "json",
         success: function (dato) {
             desbloquearPantalla();
             if (dato.status == "ok") {
                mensaje_alerta("CORRECTO!", dato.data, "success");
                EnviarPlano();
                PopupTiposCasa();
                $("#txtidTipoCasa").val(dato.IDREGISTRO);
                PintarPDF(dato.ADJUNTO);
             } else {
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
 
}



/*********************** ------- FUNCIONES DE OPERACION REGISTRO PROYECTO ------- **********************/

/*********************** ------------------------- PROYECTO ------------------------- **********************/

function RegistrarProyecto() {

  var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnRegistrarProyecto": true,
        "txtNombre": $("#txtNombre").val(),
        "txtResponsable": $("#txtResponsable").val(),
        "txtArea": $("#txtArea").val(),
        "txtNroZonas": $("#txtNroZonas").val(),
        "txtDireccion": $("#txtDireccion").val(),
        "cbxDepartamentoDir": $("#cbxDepartamentoDir").val(),
        "cbxProvinciaDir": $("#cbxProvinciaDir").val(),
        "cbxDistritoDir": $("#cbxDistritoDir").val(),
        "txtCorrelativo": $("#txtCorrelativo").val(),
        "txtCodigo": $("#txtCodigo").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_Registrar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Registro Completado!", dato.data, "success");

                    $("#txtidProyectoZona").val(dato.id_proy);
                    $("#txtNomProyectoZona").val(dato.nombre_proy);
                    $("#txtAreaProyectoZona").val(dato.area_proy);
                    $("#txtNroZonasProyectoZona").val(dato.zonas_proy);

                    $('#panel_proyecto').hide();
                    $("#btnproyecto").prop('disabled', true);
                    $("#btnmanzanas").prop('disabled', true);
                    $("#btnlotes").prop('disabled', true);
                    $("#btnzonas").prop('disabled', false);
                    $('#panel_zonas').show();

                    $("#btnproyecto").removeClass("btn-info");    
                    $("#btnproyecto").addClass("btn-success"); 
                    
                    $("#btnzonas").removeClass("btn-secondary");    
                    $("#btnzonas").addClass("btn-info");  

                    ListarZonas();
                    ListarZonasReporte();

                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error de Registro!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

function CargarCodigoProy(){
    var timeoutDefecto = 1000 * 60;
    //bloquearPantalla("Procesando...");
    var data = {
        "btnCargarCodigoProy": true
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD01_Proyecto/M01MD01_CargarCodigo.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            //desbloquearPantalla();
            if (dato.status == "ok") {               
                $("#txtCodigocc").val(dato.codigo);
                $("#txtCorrelativoccx").val(dato.correlativo);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

/*********************** ------------------------- ZONA ------------------------- **********************/

/********----- CARGAR CODIGO DE ZONA -----********/

function CargarCodigoZona(){
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnCargarCodigoZona": true,
        "txtidProyectoZona": $("#txtidProyectoZona").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD02_Zonas/M01MD02_CargarCodigo.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {               
                $("#txtCodigoZona").val(dato.codigo);
                $("#txtCorrelativoZona").val(dato.correlativo);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}
 
//REGISTRAR ZONA
function AñadirNuevaZona() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnRegistrarZona": true,
        "txtidProyectoZona": $("#txtidProyectoZona").val(),
        "txtNroZonasProyectoZona": $("#txtNroZonasProyectoZona").val(),
        "txtNombreZona": $("#txtNombreZona").val(),
        "txtCodigoZona": $("#txtCodigoZona").val(),
        "txtAreaZona": $("#txtAreaZona").val(),
        "txtGeneracionManzanas": $("#txtGeneracionManzanas").val(),
        "txtNroManzana": $("#txtNroManzana").val(),
        "txtCodigoZona": $("#txtCodigoZona").val(),
        "txtCorrelativoZona": $("#txtCorrelativoZona").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD02_Zonas/M01MD02_Registrar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Registro Completado!", dato.data, "success");
                    $('#TablaZonas').DataTable().ajax.reload();
                    $('#TablaZonasReporte').DataTable().ajax.reload();
                    
                    $("#txtidProyectoz").val(dato.id_proy);
                    $("#txtNomProyectoz").val(dato.nombre_proy);
                    $("#txtAreaProyectoz").val(dato.area_proy);
                    $("#txtNroZonasProyectoz").val(dato.zonas_proy);

                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error de Registro!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//VALIDAR CONTINUAR
function ConfirmarContinuarZona() {
    
    var Nrozonas = document.getElementById("txtNroZonasProyectoZona").value;
    var Nrocont = document.getElementById("txtCorrelativoZona").value;
    if(Nrocont < Nrozonas){
         mensaje_condicional_SinParametros("¿Está seguro que desea CONTINUAR?", "Tener en cuenta que el proyecto fue registrado con un total de "+Nrozonas+" zonas, y aun no se han completado todas.", RegistrarZona, CancelarContinuar);
    }else{
        RegistrarZona();
    }
}

function CancelarContinuar() {
    return;
}

// SIGUIENTE ZONA
function RegistrarZona() {

    mensaje_alerta("¡Registro Completado!", "Se registraron correctamente las Zonas para el proyecto anteriormente ingresado.", "success");

    $("#btnproyecto").prop('disabled', true);
    $("#btnmanzanas").prop('disabled', false);
    $("#btnlotes").prop('disabled', true);
    $("#btnzonas").prop('disabled', true);

    $('#panel_zonas').hide();
    $('#panel_proyecto').hide();
    $('#panel_manzanas').show();
    $('#panel_lotes').hide();

    $("#btnzonas").removeClass("btn-info");    
    $("#btnzonas").addClass("btn-success"); 
    
    $("#btnmanzanas").removeClass("btn-secondary");    
    $("#btnmanzanas").addClass("btn-info"); 

    LLenarZonas();
    ListarManzanasReporte();
    ListarManzanas();
}

// LISTAR ZONAS
function ListarZonas() {

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
            "url": "../../models/M01_Proyecto/M01MD02_Zonas/M01MD02_ListarZonas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "txtidProyectoZona": $("#txtidProyectoZona").val()
                });
            }
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)"  onclick="EditarEmpresa(\'' + data + '\')"><img src="../../../images/editar.png" width="25px" height="25px" ></a>';
                }
            },
            {
                "data": "nombre"
            },
            {
                "data": "nombre"
            },
            {
                "data": "codigo"
            },
            {
                "data": "nro_manzanas"
            },
            {
                "data": "area"
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

    tablaEmpresas = $('#TablaZonas').DataTable(options);
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
        success: function (dato) {
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
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

var tablaEmpresas = null;
function ListarZonasReporte() {
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
            "url": "../../models/M01_Proyecto/M01MD02_Zonas/M01MD02_ListarZonas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "txtidProyectoZona": $("#txtidProyectoZona").val()
                });
            }
        },
        "columns": [{
                "data": "id"
            },
            {
                "data": "nombre"
            },
            {
                "data": "nombre"
            },
            {
                "data": "codigo"
            },
            {
                "data": "nro_manzanas"
            },
            {
                "data": "area"
            }
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

    tablaEmpresas = $('#TablaZonasReporte').DataTable(options);
}

/*********************** ------------------------- MANZANA ------------------------- **********************/
//CARGAR CODIGO DE MANZANAS
function CargarCodigoMz(){
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnCargarCodigoMz": true,
        "cbxZonas": $("#cbxZonas").val()

    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD03_Manzanas/M01MD03_CargarCodigo.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {               
                $("#txtCodigoManzana").val(dato.codigo);
                $("#txtCorrelativoManzana").val(dato.correlativo);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//LLENAR BOX ZONAS
function LLenarZonas() {
    var url = "../../models/M01_Proyecto/M01MD03_Manzanas/M01MD03_LlenarZonas.php";
    var datos = {
        "LlenarZonas": true,
        "txtidProyectoz": $('#txtidProyectoz').val()
    }
    llenarCombo(url, datos, 'cbxZonas');
}

//REGISTRAR MANZANA
function AgregarNuevaManzana() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnRegistrarManzana": true,
        "txtidProyectoz": $("#txtidProyectoz").val(),
        "cbxZonas": $("#cbxZonas").val(),
        "txtNroManzanas": $("#txtNroManzanas").val(),
        "txtNombreManzana": $("#txtNombreManzana").val(),
        "txtCodigoManzana": $("#txtCodigoManzana").val(),
        "txtAreaManzana": $("#txtAreaManzana").val(),
        "txtNumLotes": $("#txtNumLotes").val(),
        "txtCorrelativoManzana": $("#txtCorrelativoManzana").val(),
        "txtCodigoGeneracionManzanas": $("#txtCodigoGeneracionManzanas").val(),
        "txtGeneracionManzanas": $("#txtGeneracionManzanas").val(),
        "txtNumManzanasGeneradas": $("#txtNumManzanasGeneradas").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD03_Manzanas/M01MD03_Registrar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Registro Completado!", dato.data, "success");
                    $('#TablaManzanas').DataTable().ajax.reload();
                    $('#TablaManzanasReporte').DataTable().ajax.reload();

                    $("#txtidProyectozlt").val(dato.id_proy);
                    $("#txtNomProyectozlt").val(dato.nombre_proy);
                    $("#txtAreaProyectozlt").val(dato.area_proy);
                    $("#txtNroZonasProyectozlt").val(dato.zonas_proy);

                    $("#txtNombreManzana").val("");
                    $("#txtCodigoManzana").val("");
                    $("#txtAreaManzana").val("");
                    $("#txtNumLotes").val("");
                    $("#txtCodigoGeneracionManzanas").val("");
                    $("#txtNumManzanasGeneradas").val("");
                    $("#txtGeneracionManzanas").val("0");

                    $("#txtCodigoGeneracionManzanas").prop('disabled', true);
                    $("#txtNumManzanasGeneradas").prop('disabled', true);


                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error de Registro!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//VALIDAR CONTINUAR
function ConfirmarContinuarManzana() {   
    var Nrozonas = document.getElementById("txtNroZonasProyectoZona").value;
    var Nrocont = document.getElementById("txtCorrelativoZona").value;
    if(Nrocont < Nrozonas){
         mensaje_condicional_SinParametros("¿Está seguro que desea CONTINUAR?", "Tener en cuenta que el proyecto fue registrado con un total de "+Nrozonas+" zonas, y aun no se han completado todas.", RegistrarZona, CancelarContinuar);
    }else{
        RegistrarZona();
    }
}


//FILTRAR DATOS DE LA ZONA SELECCIONADA EN EL BOX
function BuscarZonas() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnRegistrarZona": true,
        "cbxZonas": $("#cbxZonas").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD03_Manzanas/M01MD03_BuscarDatos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                //console.log(dato);
                $("#txtNroManzanas").val(dato.numero);
                $("#txtAreaZonaa").val(dato.area);

                $("#txtNombreManzana").prop('disabled', false);
                //$("#txtCodigoManzana").prop('disabled', false);
                $("#txtAreaManzana").prop('disabled', false);
                $("#txtNumLotes").prop('disabled', false);
                $("#txtGeneracionManzanas").prop('disabled', false);
                $("#btnAgregarManzana").prop('disabled', false);

                $('#TablaManzanas').DataTable().ajax.reload();
                $('#TablaManzanasReporte').DataTable().ajax.reload();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//SIGUIENTE MANZANA
function RegistrarManzanas() {
    mensaje_alerta("¡Registro Completado!", "Se registraron correctamente las Manzanas para el proyecto", "success");
    $("#btnproyecto").prop('disabled', true);
    $("#btnzonas").prop('disabled', true);
    $("#btnmanzanas").prop('disabled', true);
    $("#btnlotes").prop('disabled', false);

        
    $('#panel_zonas').hide();
    $('#panel_proyecto').hide();
    $('#panel_manzanas').hide();
    $('#panel_lotes').show();


    $("#btnmanzanas").removeClass("btn-info");    
    $("#btnmanzanas").addClass("btn-success"); 
    
    $("#btnlotes").removeClass("btn-secondary");    
    $("#btnlotes").addClass("btn-info"); 

    LLenarZonasLotes();
  
    ListarLotesReporte();
    ListarLotes();
}

//LISTAR MANZANA
function ListarManzanas() {
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
            "url": "../../models/M01_Proyecto/M01MD03_Manzanas/M01MD03_ListarManzanas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "cbxZonas": $("#cbxZonas").val()
                });
            }
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)"  onclick="EditarEmpresa(\'' + data + '\')"><img src="../../../images/editar.png" width="25px" height="25px" ></a>';
                }
            },
            {
                "data": "nombre"
            },
            {
                "data": "nombre"
            },
            {
                "data": "codigo"
            },
            {
                "data": "nro_lotes"
            },
            {
                "data": "area"
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
    tablaEmpresas = $('#TablaManzanas').DataTable(options);
}

function EditarEmpresa(id) {
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
        success: function (dato) {
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
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

var tablaEmpresas = null;
function ListarManzanasReporte() {
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
            "url": "../../models/M01_Proyecto/M01MD03_Manzanas/M01MD03_ListarManzanas.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "cbxZonas": $("#cbxZonas").val()
                });
            }
        },
        "columns": [{
                "data": "id"
            },
            {
                "data": "nombre"
            },
            {
                "data": "nombre"
            },
            {
                "data": "codigo"
            },
            {
                "data": "nro_lotes"
            },
            {
                "data": "area"
            }
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

    tablaEmpresas = $('#TablaManzanasReporte').DataTable(options);
}

/*********************** ------------------------- LOTES ------------------------- **********************/

//CARGAR CODIGO DE LOTES
function CargarCodigoLt(){
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnCargarCodigoLt": true,
        "bxManzanaslte": $("#bxManzanaslte").val()

    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_CargarCodigo.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
			console.log("Respuesta del servidor:", dato); // Verificar contenido
            if (dato.status == "ok") {               
                $("#txtCodigoLotee").val(dato.codigo);
				$("#txtCorrelativoLote").val(dato.correlativo).prop("disabled", false).trigger("change");
				console.log("Correlativo final:", $("#txtCorrelativoLote").val());

				
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//LLENAR BOX ZONAS
function LLenarZonasLotes() {
    var url = "../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_LlenarZonas.php";
    var datos = {
        "LlenarZonas": true,
        "txtidProyectozlt": $('#txtidProyectozlt').val()
    }
    llenarCombo(url, datos, 'cbxZonaslt');
}

//FILTRAR DATOS DE LA ZONA SELECCIONADA EN EL BOX
function BuscarManzanas() {
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnRegistrarZona": true,
        "cbxManzanaslt": $("#cbxManzanaslt").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_BuscarDatos.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                //console.log(dato);
                $("#txtNroLotes").val(dato.numero);
                $("#txtAreaMz").val(dato.area);

                $("#txtNombreLote").prop('disabled', false);
                $("#txtAreaLote").prop('disabled', false);
                $("#cbxTipoMoneda").prop('disabled', false);
                $("#txtValorCCLote").prop('disabled', false);
                $("#txtValorSCLote").prop('disabled', false);
                $("#txtGeneracionLotes").prop('disabled', false);
                $("#btnAgregarLote").prop('disabled', false);

                $('#TablaLotes').DataTable().ajax.reload();
                $('#TablaLotesReporte').DataTable().ajax.reload();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

//REGISTRAR MANZANA
/*function AgregarNuevoLote() {
   
    var timeoutDefecto = 1000 * 60;
    bloquearPantalla("Procesando...");
    var data = {
        "btnRegistrarLote": true,
        "txtidProyectozlt": $("#txtidProyectozlt").val(),
        "cbxManzanaslt": $("#cbxManzanaslt").val(),
        "txtNroLotes": $("#txtNroLotes").val(),
        "txtNombreLote": $("#txtNombreLote").val(),
        "txtCodigoLote": $("#txtCodigoLote").val(),
        //"txtCorrelativoLote": $("#txtCorrelativoLote").val(),
        "txtAreaLote": $("#txtAreaLote").val(),
        "cbxTipoMoneda": $("#cbxTipoMoneda").val(),
        "txtValorCCLote": $("#txtValorCCLote").val(),
        "txtValorSCLote": $("#txtValorSCLote").val(),
        "txtGeneracionLotes": $("#txtGeneracionLotes").val(),
        "txtExtensionNombreLote": $("#txtExtensionNombreLote").val(),
        "txtNroLotesGenerar": $("#txtNroLotesGenerar").val()
    };
    $.ajax({
        type: "POST",
        url: "../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_Registrar.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            desbloquearPantalla();
            if (dato.status == "ok") {
                setTimeout(function () {
                    mensaje_alerta("¡Registro Completado!", dato.data, "success");
                    $('#TablaLotes').DataTable().ajax.reload();
                    $('#TablaLotesReporte').DataTable().ajax.reload();

                    $("#txtNombreLote").val("");
                    $("#txtCodigoLote").val("");
                    $("#txtAreaLote").val("");
                    $("#txtValorCCLote").val("");
                    $("#txtValorSCLote").val("");
                    document.getElementById('cbxTipoMoneda').selectedIndex = 0;
                    $("#txtExtensionNombreLote").val("");
                    $("#txtNroLotesGenerar").val("");
                    $("#txtGeneracionLotes").val("0");

                    $("#txtExtensionNombreLote").prop('disabled', true);
                    $("#txtNroLotesGenerar").prop('disabled', true);


                }, 100);
                return;
            } else {
                mensaje_alerta("¡Error de Registro!", dato.data, "info");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}*/

//LISTAR LOTES
var tablaBusqReservacionReport = null;
function ListarLotes() {
    if (tablaBusqReservacionReport) {
        tablaBusqReservacionReport.destroy();
        tablaBusqReservacionReport = null;
    }

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
            "url": "../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_ListarLotes.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "cbxManzanaslt": $("#cbxManzanaslt").val()
                });
            }
        },
        "columns": [{
                "data": "id",
                "render": function (data, type, row) {
                    return '<a href="javascript:void(0)"  onclick="EditarEmpresa(\'' + data + '\')"><img src="../../../images/editar.png" width="25px" height="25px" ></a>';
                }
            },
            {
                "data": "nombre"
            },
            {
                "data": "nombre"
            },
            {
                "data": "area"
            },
            {
                "data": "tipo_moneda"
            },
            {
                "data": "vcasa"
            },
            {
                "data": "vscasa"
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

    tablaBusqReservacionReport = $('#TablaLotes').DataTable(options);    
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
        success: function (dato) {
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
        error: function (error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
}

var tablaListarLoteReporte = null;
function ListarLotesReporte() {

    if (tablaListarLoteReporte) {
        tablaListarLoteReporte.destroy();
        tablaListarLoteReporte = null;
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
        "bSort": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 20, 50, 100, 150],
            [10, 20, 50, 100, 150] // change per page values here
        ],
        "pageLength": 1000000000, // default record count per page,
        "ajax": {
            "url": "../../models/M01_Proyecto/M01MD04_Lotes/M01MD04_ListarLotes.php", // ajax source
            "type": "POST",
            "error": validarErrorGrilla,
            "data": function (d) {
                return $.extend({}, d, {
                    "cbxManzanaslt": $("#cbxManzanaslt").val()
                });
            }
        },
        "columns": [{
                "data": "id"
            },
            {
                "data": "nombre"
            },
            {
                "data": "nombre"
            },
            {
                "data": "area"
            },
            {
                "data": "tipo_moneda"
            },
            {
                "data": "vcasa"
            },
            {
                "data": "vscasa"
            }
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

    tablaListarLoteReporte = $('#TablaLotesReporte').DataTable(options);
}

function ControlSesion(){ 
    var data = {
        "ReturnControlSesion": true,
        "d_u_sn": $("#d_u_sn").val()        
    };
    $.ajax({
        type: "POST",
        url: "../../models/session/control/control_sesion.php",
        data: data,
        dataType: "json",
        success: function (dato) {
            if (dato.status == "ok") {
                console.log(dato.data);
                mensaje_sesion(dato.data, irlogin, dato.url);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            desbloquearPantalla();
        },
    });
}

function irlogin(ruta){
    window.location.href = ruta;
}