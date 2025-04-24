function mensaje_alerta(titulo, mensaje, tipo) {
    swal(titulo, mensaje, tipo);
}
function mensaje_condicionalUNO(titulo, detalle, fun, fun1, a) {
    swal({
        title: titulo,
        text: detalle,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#4285f4",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun(a);
        } else {
            fun1();
        }
    });
}
function mensaje_condicionalDOS(titulo, detalle, fun, a) {
    swal({
        title: titulo,
        text: detalle,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#4285f4",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun(a);
        } 
    });
}
function mensaje_condicional(titulo, detalle, fun, fun1, a, b, c) {
    swal({
        title: titulo,
        text: detalle,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#4285f4",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun(a, b, c);
        } else {
            fun1();
        }
    });
}

function mensaje_condicional_SinParametros(titulo, detalle, fun, fun1) {
    swal({
        title: titulo,
        text: detalle,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#4285f4",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            fun1();
        }
    });
}

function mensaje_elimina(detalle,fun) {
    swal({
        title: "¿Desea eliminar?",
        text: detalle,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sí,Eliminar!",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}

function mensaje_eliminar_parametro(detalle,fun,valor) {
    swal({
        title: "¡IMPORTANTE!",
        text: detalle,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun(valor);
        } else {
            //Ninguno();
        }
    });
}



function mensaje_baja(detalle, fun) {
    swal({
        title: "¿Desea dar de baja?",
        text: detalle,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sí, Dar de Baja!",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}

function mensaje_suspender(detalle, fun) {
    swal({
        title: "¿Desea suspender?",
        text: detalle,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sí, Suspender!",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}

function mensaje_cosulta_agregar(detalle, fun,fun1) {
    swal({
        title: "¡El PRODUCTO ya fue agregado!",
        text: detalle,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sí, Agregar!",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            fun1();
        }
    });
}
function mensaje_actualizacion_item(detalle, fun) {
    swal({
        title: "Actualizar Items de Documento",
        text: detalle,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}

function Mensaje_Anular(detalle, campo, fun ) {
    swal({
        title: "¿Desea Anularlo?",
        text: detalle,
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sí,Anular!",
        animation: "slide-from-top",
        inputPlaceholder: "Escriba Razón/Motivo",
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("¡Escriba Razón / Motivo para Anular!"); return false
        } else {
            $(campo).val(inputValue);
            fun();
        }
        
    });
}
function Mensaje_Anular_Contabilidad(detalle, campo, fun) {
    swal({
        title: "¿Desea Anularlo?",
        text: detalle,
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sí,Anular!",
        animation: "slide-from-top",
        inputPlaceholder: "Escriba Razón/Motivo",
    }, function (inputValue) {
        if (inputValue === false) return false;

        $(campo).val(inputValue);
        fun();


    });
}

function Modal_ConEntaradaTexto(title, detalle, type, confirmButtonColor, cancelButtonText, confirmButtonText, inputPlaceholder, SMSError, campo, fun) {
    swal({
        title: title,
        text: detalle,
        type: type,
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonColor: confirmButtonColor,
        cancelButtonText: cancelButtonText,
        confirmButtonText: confirmButtonText,
        animation: "slide-from-top",
        inputPlaceholder: inputPlaceholder,
    }, function (inputValue) {
        if (inputValue === false) return false;
        emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
        if (inputValue === "") {
            swal.showInputError(SMSError); return false
        } else if (!emailRegex.test(inputValue)) {
            swal.showInputError("Asegúrate de que la dirección: " + inputValue +" tenga el formato correcto."); return false
        }else {
            $(campo).val(inputValue);
            fun();
        }

    });
}

function mensaje_error_1_periodo(detalle,fun) {
    swal({
        title: "¡Error al iniciar apertura!",
        text: detalle,
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Ir al módulo",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}


function mensaje_error_2_periodo(detalle,fun) {
    swal({
        title: "¡Error en la Configuración!",
        text: detalle,
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Completar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}


function mensaje_error_tareo(detalle,fun) {
    swal({
        title: "¡Error al procesar Asistencias!",
        text: detalle,
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Ir al modulo",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}


function mensaje_iniciar_apertura(detalle,fun) {
    swal({
        title: "¡Correcto!",
        text: detalle,
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Ir a Apertura",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}

function mensaje_eliminar_concepto(detalle,fun) {
    swal({
        title: "¿Seguro que desea eliminar el concepto?",
        text: detalle,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Si",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}


function mensaje_guardar_concepto(detalle,fun) {
    swal({
        title: "¿Desea actualizar el concepto?",
        text: detalle,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Continuar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}


function mensaje_faltan_datos(detalle,fun) {
    swal({
        title: "¡No existe informacion del personal!",
        text: detalle,
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Ir al modulo",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}


function mensaje_eliminar_afp(detalle,fun) {
    swal({
        title: "¿Seguro que desea eliminar los registros de AFP para el periodo seleccionado?",
        text: detalle,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Si",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}

function mensaje_eliminar_renta(detalle,fun) {
    swal({
        title: "¿Seguro que desea eliminar los registros de Renta 5ta Cat. para el periodo seleccionado?",
        text: detalle,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#1F5AAF",
        confirmButtonText: "Si",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            fun();
        } else {
            //Ninguno();
        }
    });
}

function mensaje_sesion(detalle, fun, dato) {
    swal({
        title: "ATENCI\u00D3N!",
        text: detalle,
        type: "warning",
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "De acuerdo",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            fun(dato);
        } else {
            //Ninguno();
        }
    });
}
