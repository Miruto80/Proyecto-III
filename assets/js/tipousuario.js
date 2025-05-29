// Esperar que DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Registrar tipo usuario
    document.getElementById('registrar').addEventListener('click', function () {
        if (!validarFormulario('formRegistrar')) return;

        const nombre = document.getElementById('nombre').value;
        const nivel = document.getElementById('nivel').value;
        const estatus = document.getElementById('estatus').value;
        const datos = new FormData();
        datos.append('nombre', nombre);
        datos.append('nivel', nivel);
        datos.append('estatus', estatus);
        datos.append('registrar', 'registrar');
        enviaAjax(datos);
    });

    // Modificar tipo usuario
    document.getElementById('btnModificar').addEventListener('click', function () {
        if (!validarFormulario('formModificar')) return;

        const id_tipo = document.getElementById('id_tipo_modificar').value;
        const nombre = document.getElementById('nombre_modificar').value;
        const nivel = document.getElementById('nivel_modificar').value;
        const estatus = document.getElementById('estatus_modificar').value;
        const datos = new FormData();
        datos.append('id_tipo', id_tipo);
        datos.append('nombre', nombre);
        datos.append('nivel', nivel);
        datos.append('estatus', estatus);
        datos.append('modificar', 'modificar');
        enviaAjax(datos);
    });
});

// Función para abrir modal modificar y limpiar validaciones
function abrirModalModificar(id_tipo, nombre, nivel, estatus) {
    document.getElementById('id_tipo_modificar').value = id_tipo;
    document.getElementById('nombre_modificar').value = nombre;
    document.getElementById('nivel_modificar').value = nivel;
    document.getElementById('estatus_modificar').value = estatus;

    // Limpiar mensajes y clases de error/éxito
    $('#formModificar .is-invalid, #formModificar .is-valid').removeClass('is-invalid is-valid');
    $('#formModificar span.text-danger').text('');

    $('#modificar').modal('show');
}

// Función eliminar tipo usuario con confirmación usando SweetAlert
function eliminarTipoUsuario(id_tipo) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const datos = new FormData();
            datos.append('id_tipo', id_tipo);
            datos.append('eliminar', 'eliminar');
            enviaAjax(datos);
        }
    });
}

// Mostrar mensajes SweetAlert
function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

// Enviar AJAX y manejar respuesta
function enviaAjax(datos) {
    fetch('', {
        method: 'POST',
        body: datos,
    })
    .then(resp => resp.json())
    .then(data => {
        if (data.accion === 'incluir') {
            if (data.respuesta === 1) {
                document.getElementById('formRegistrar').reset();
                limpiarFormulario('formRegistrar');
                muestraMensaje("success", 1000, "Se ha registrado con éxito", "tipousuario se ha registrado exitosamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 2000, "Error", "Error al registrar tipousuario");
            }
        } else if (data.accion === 'actualizar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Se ha Modificado con éxito", "El proveedor se ha actualizado exitosamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 2000, "Error", "Error al modificar tipousuario");
            }
        } else if (data.accion === 'eliminar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Se ha eliminado con éxito", "tipousuario se ha eliminado correctamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 2000, "Error", "Error al eliminar tipousuario");
            }
        }
    })
    .catch(error => {
        muestraMensaje("error", 3000, "Error", "Error de comunicación: " + error);
    });
}

// Validaciones para nombre registro y modificar con bloqueo de teclas y mensajes
function configurarValidacionesNombre() {
    $("#nombre, #nombre_modificar").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombre").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
    });
    $("#nombre_modificar").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres");
    });
}

// Validar keypress aceptando solo letras, espacios, ñ, etc
function validarkeypress(er, e) {
    let key = e.keyCode || e.which;
    let tecla = String.fromCharCode(key);
    if (!er.test(tecla)) {
        e.preventDefault();
    }
}

// Validar keyup con expresión, actualizar span con mensaje o limpiarlo, retorna 1 si válido, 0 si no
function validarkeyup(er, etiqueta, spnMensaje, mensaje) {
    let valor = etiqueta.val();
    if (valor.trim() === '') {
        etiqueta.removeClass('is-valid').addClass('is-invalid');
        spnMensaje.text("Este campo es obligatorio");
        return 0;
    }
    if (er.test(valor)) {
        spnMensaje.text('');
        etiqueta.removeClass('is-invalid').addClass('is-valid');
        return 1;
    } else {
        spnMensaje.text(mensaje);
        etiqueta.removeClass('is-valid').addClass('is-invalid');
        return 0;
    }
}

// Validar formulario completo por ID y llamar validaciones específicas para nombre y nivel
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    const campos = form.querySelectorAll('[required]');
    let valido = true;

    // Validar campos vacíos y marcar errores
    campos.forEach(campo => {
        if (!campo.value.trim()) {
            campo.classList.add('is-invalid');
            valido = false;
        } else {
            campo.classList.remove('is-invalid');
        }
    });

    // Validar específicamente nombre y nivel con mensajes debajo
    if (formId === 'formRegistrar') {
        if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") === 0) {
            valido = false;
        }
        const nivelVal = $("#nivel").val();
        if (!/^[0-3]$/.test(nivelVal)) {
            $("#nivel").addClass("is-invalid");
            $("#snivel").text("El nivel debe ser 0, 1, 2 o 3");
            valido = false;
        } else {
            $("#nivel").removeClass("is-invalid");
            $("#snivel").text("");
        }
    } else if (formId === 'formModificar') {
        if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre_modificar"), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres") === 0) {
            valido = false;
        }
        const nivelModVal = $("#nivel_modificar").val();
        if (!/^[0-3]$/.test(nivelModVal)) {
            $("#nivel_modificar").addClass("is-invalid");
            $("#snivel_modificar").text("El nivel debe ser 0, 1, 2 o 3");
            valido = false;
        } else {
            $("#nivel_modificar").removeClass("is-invalid");
            $("#snivel_modificar").text("");
        }
    }

    return valido;
}

// Limpiar clases y mensajes de validación de un formulario por su ID
function limpiarFormulario(formId) {
    const form = document.getElementById(formId);
    // Remover clases de validación
    $(form).find('.is-invalid').removeClass('is-invalid');
    $(form).find('.is-valid').removeClass('is-valid');
    // Limpiar mensajes
    $(form).find('span.text-danger').text('');
}

// Inicializar validaciones
configurarValidacionesNombre();
