function SoloNumeros1_9() {
    var key = window.event ? event.which : event.keyCode;
    if (key < 48 || key > 57) {
        event.preventDefault();
    }
}

function SoloLetras() {
    var regex = new RegExp("^[a-zA-ZñÑáéíóúüÜÁÉÍÓÚ ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function SoloNumeros_Punto() {
    var key = window.event ? event.which : event.keyCode;
    if (key < 48 || key > 57) {
        if (key !== 46) {
            event.preventDefault();
        }
    }
}

function SoloNumerosYGuion() {
    var key = window.event ? event.which : event.keyCode;
    if ((key < 48 || key > 57)) {
        if (key != 45) {
            event.preventDefault();
        }
    }
}

//******************************************** VALIDACION DE LETRAS - ASTERISCO **********************************
function SoloLetras_Asterisco() {
    var key = window.event ? event.which : event.keyCode;
    if (key < 65 || key > 90 && key < 97 || key > 122) {
        if (key !== 42) {
            event.preventDefault();
        }
    }
}
//******************************************** VALIDACION DE NUMEROS - ASTERISCO **********************************
function SoloNumeros_Asterisco() {
    var key = window.event ? event.which : event.keyCode;
    if (key < 48 || key > 57) {
        if (key !== 42) {
            event.preventDefault();
        }
    }
}

function calcularEdad(fechaA, fechaB) {
    var AnioA = fechaA.substring(0, 4);
    var MesA = fechaA.substring(5, 7);
    var DiaA = fechaA.substring(8, 10);

    var AnioB = fechaB.substring(0, 4);
    var MesB = fechaB.substring(5, 7);
    var DiaB = fechaB.substring(8, 10);
    var edad = parseInt(AnioB) - parseInt(AnioA);

    if (parseInt(MesA) >= parseInt(MesB)) {
        if (parseInt(MesA) === parseInt(MesB)) {
            if (DiaA > DiaB) {
                edad--;
                return edad;
            } else {
                return edad;
            }
        }
        edad--;
        return edad;
    }
    return edad;
}

function delayTime(callback, ms) {
    var timer = 0;
    return function() {
        var context = this,
            args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() {
            callback.apply(context, args);
        }, ms || 0);
    };
}

function bloquearPantalla(mensaje) {
    miMensaje = '<div class="cargando">' +
        ' <div class="pre-cargando">' +
        ' <div class="spinner-layer pl-deep-purple">' +
        ' <div class="circle-clipper left">' +
        ' <div class="circle"></div>' +
        ' </div>' +
        ' <div class="circle-clipper right">' +
        ' <div class="circle"></div>' +
        ' </div>' +
        ' </div>' +
        ' </div>' +
        ' </div>';
    if (mensaje) {
        miMensaje += '<p style="color:#000b70;font-size:14px;font-weight:bold;">' + mensaje + "</p>";
    } else {
        miMensaje += '<p style="color:#000b70;font-size:14px;font-weight:bold;">  </p>';
    }
    miMensaje += "</div>";

    $.blockUI({
        baseZ: 3000,
        message: miMensaje,
        css: {
            top: ($(window).height() - 101) / 2 + 'px',
            left: ($(window).width() - 128) / 2 + 'px',
            width: '128px',
            border: 'none'
        }
    });
}


function desbloquearPantalla() {
    $.unblockUI();
}

//***************************************** VALIDAR GRILLA *************************************//
function validarError(xhr) {
    if (xhr.status === 403) {
        var datoError = $.parseJSON(xhr.responseText);
        location.href = datoError.urlLogin;
    } else {
        alert('Ocurrio un error, contactar con soporte');
    }
}

function validarErrorGrilla(xhr, textStatus, error) {
    validarError(xhr);
}

//***************************************** LLENAR COMBO *************************************//
function llenarCombo(url, datos, idCombo) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: datos,
        success: function(dato) {
            var resutado = dato.data;
            $('#' + idCombo)
                .find('option')
                .remove()
                .end();

            for (i = 0; i < resutado.length; i++) {
                var option = resutado[i];
                $('#' + idCombo)
                    .append('<option value="' + option.valor + '">' + option.texto + '</option>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            /*if (fError) {
                fError(jqXHR, textStatus, errorThrown);
            }*/

        }
    });
}

function llenarComboSelecionar(url, datos, idCombo, idSelect) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: datos,
        success: function(dato) {
            var resutado = dato.data;
            $('#' + idCombo)
                .find('option')
                .remove()
                .end();

            for (i = 0; i < resutado.length; i++) {
                var option = resutado[i];
                $('#' + idCombo)
                    .append('<option value="' + option.valor + '">' + option.texto + '</option>');
            }
            $('#' + idCombo).val(idSelect);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            /*if (fError) {
                fError(jqXHR, textStatus, errorThrown);
            }*/

        }
    });
}

function llenarComboSelecionarInterno(url, datos, idCombo) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: datos,
        success: function(dato) {
            var resutado = dato.data;
            $('#' + idCombo)
                .find('option')
                .remove()
                .end();

            for (i = 0; i < resutado.length; i++) {
                var option = resutado[i];
                $('#' + idCombo)
                    .append('<option value="' + option.valor + '">' + option.texto + '</option>');
            }
            $('#' + idCombo).val(dato.seleccionado);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
            /*if (fError) {
                fError(jqXHR, textStatus, errorThrown);
            }*/

        }
    });
}

function ValidarEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
}

//*BORRAR DATOS SEGUNDARIOS*//
function BorrarDatosSegunCodigo(s) {
    if (event.which == 8 || event.which == 46) {
        document.getElementById(s).value = "";
    }
}

/********************************************** SALTAR CON AL SIGUIENTE CAMPO HABILITADO **********************************/
function focosiguiente(idcampo) {
    $(document).on('keydown', idcampo, function(e) {
        var self = $(this),
            form = self.parents('form:eq(0)'),
            submit = (self.attr('type') == 'submit' || self.attr('type') == 'button'),
            focusable,
            next;
        if (e.keyCode == 13 && !submit) {
            focusable = form.find('input,a,select,button,textarea').filter(':visible:not([readonly]):not([disabled])');
            next = focusable.eq(focusable.index(this) + 1);
            if (next.length) {
                next.focus();
            } else {
                form.submit();
            }
            return false;
        }
    });
}

/***************************************************** SALTAR CAMPO SIGUIENTE ******************************************************/
function SaltarAlCampoSiguiente(campo) {
    if (event.key === 'Enter' || event.keyCode == 13) {
        $(campo).focus();
    }
}

/***************************************************** OCULTAR SCROLL DE MODAL INFERIOR ******************************************************/
function InicializarGenericoModal() {
    $('.modal').on("hidden.bs.modal", function(e) {
        if ($('.modal:visible').length) {
            $('body').addClass('modal-open');
        }
    });
}


/****************************REALIZAR POST******************************** */

function realizarJsonPost(url, datos, fSuceso, fError, timeoutAjax, noRedireccionar) {
    realizarPost(url, datos, 'json', fSuceso, fError, timeoutAjax, noRedireccionar);
}

function realizarPost(url, datos, tipoRespuesta, fSuceso, fError, timeoutAjax, noRedireccionar) {

    var timeoutDefecto = 1000 * 60;
    if (timeoutAjax) {
        timeoutDefecto = 1000 * timeoutAjax;
    }

    var xhr = $.ajax({
        type: "POST",
        url: url,
        dataType: tipoRespuesta,
        data: datos,
        success: function(data) {
            if (fSuceso) {
                fSuceso(data);
            }
        },
        error: function(error) {
            console.log(error);
            desbloquearPantalla();
        },
        timeout: timeoutDefecto
    });
    return xhr;
}

/*********************EXPORTAR GRILLA A EXCEL********************** */
function exportGrillaExcel(workbook, nombre) {
    return XLSX.writeFile(workbook, nombre);
}

/**************************cifrar con comas*************************************** */
function numeroConComas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/****************estados Control******************* */

var AccionControl = { Registrar: "REGISTRAR", Actualizar: "ACTUALIZAR", Eliminar: "ELIMINAR" };

/**********************INSERTAR AUDITORIA******************** */
function RegistrarControl(seccion, tipoAccion, valor1 = null, valor2 = null, valor3 = null, valor4 = null, valor5 = null) {
    var url = "../../../models/General/ProcesoAuditoria.php";
    var dato = {
        "ReturnInsertarAuditoria": true,
        "seccion": seccion,
        "tipoAccion": tipoAccion,
        "valor1": valor1,
        "valor2": valor2,
        "valor3": valor3,
        "valor4": valor4,
        "valor5": valor5
    };
    realizarJsonPost(url, dato, respuestaRegistrarControl, null, 10000, null);
}

function respuestaRegistrarControl(dato) {
    console.log(dato);
}