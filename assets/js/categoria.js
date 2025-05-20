// Esperar que DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Registrar categoría
    document.getElementById('registrar').addEventListener('click', function () {
        if (!validarFormulario('formRegistrar')) return;

        const nombre = document.getElementById('nombre').value;
        const datos = new FormData();
        datos.append('id_categoria', nombre);
        datos.append('nombre', nombre);
        datos.append('registrar', 'registrar');
        enviaAjax(datos);
    });

    // Modificar categoría
    document.getElementById('btnModificar').addEventListener('click', function () {
        if (!validarFormulario('formModificar')) return;

        const id_categoria = document.getElementById('id_categoria_modificar').value;
        const nombre = document.getElementById('nombre_modificar').value;
        const datos = new FormData();
        datos.append('id_categoria', id_categoria);
        datos.append('nombre', nombre);
        datos.append('modificar', 'modificar');
        enviaAjax(datos);
    });
});

// Función para abrir modal modificar y limpiar validaciones
function abrirModalModificar(id_categoria, nombre) {
    document.getElementById('id_categoria_modificar').value = id_categoria;
    document.getElementById('nombre_modificar').value = nombre;

    // Limpiar mensajes y clases de error/éxito
    $('#formModificar .is-invalid, #formModificar .is-valid').removeClass('is-invalid is-valid');
    $('#formModificar span.text-danger').text('');

    $('#modificar').modal('show');
}

// Función eliminar categoría con confirmación usando SweetAlert
function eliminarCategoria(id_categoria) {
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
            datos.append('id_categoria', id_categoria);
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
                muestraMensaje("success", 1000, "Registro exitoso", "La categoría se ha registrado exitosamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 2000, "Error", "Error al registrar categoría");
            }
        } else if (data.accion === 'actualizar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Modificación exitosa", "La categoría se ha actualizado correctamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 2000, "Error", "Error al modificar categoría");
            }
        } else if (data.accion === 'eliminar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Eliminado", "La categoría ha sido eliminada correctamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 2000, "Error", "Error al eliminar categoría");
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

// Validar formulario completo por ID y llamar validaciones específicas
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    const campos = form.querySelectorAll('[required]');
    let valido = true;

    campos.forEach(campo => {
        if (!campo.value.trim()) {
            campo.classList.add('is-invalid');
            valido = false;
        } else {
            campo.classList.remove('is-invalid');
        }
    });

    if (formId === 'formRegistrar') {
        if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") === 0) {
            valido = false;
        }
    } else if (formId === 'formModificar') {
        if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre_modificar"), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres") === 0) {
            valido = false;
        }
    }

    return valido;
}

// Limpiar clases y mensajes de validación de un formulario por su ID
function limpiarFormulario(formId) {
    const form = document.getElementById(formId);
    $(form).find('.is-invalid').removeClass('is-invalid');
    $(form).find('.is-valid').removeClass('is-valid');
    $(form).find('span.text-danger').text('');
}

// Inicializar validaciones
configurarValidacionesNombre();
