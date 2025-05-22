document.addEventListener('DOMContentLoaded', function () {
    // Validación para el campo 'nombre'
    $("#nombre").on("keypress", function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    
    $("#nombre").on("keyup", function(){
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
    });

    // Validación para el campo 'descripcion'
    $("#descripcion").on("keypress", function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    
    $("#descripcion").on("keyup", function(){
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#sdescripcion"), "Solo letras entre 3 y 30 caracteres");
    });

    // Registrar método de pago
    document.getElementById('registrar').addEventListener('click', function () {
        const nombre = document.getElementById('nombre').value;
        const descripcion = document.getElementById('descripcion').value;

        if (!validarenvio()) {
            return; // Si la validación falla, no enviar el formulario
        }

        const datos = new FormData();
        datos.append('nombre', nombre);
        datos.append('descripcion', descripcion);
        datos.append('registrar', 'registrar');
        enviaAjax(datos);
    });

    // Modificar método de pago
    document.getElementById('btnModificar').addEventListener('click', function () {
        const id_metodopago = document.getElementById('id_metodopago_modificar').value;
        const nombre = document.getElementById('nombre_modificar').value;
        const descripcion = document.getElementById('descripcion_modificar').value;

        if (!validarModificacion()) {
            return; // Si la validación falla, no enviar el formulario
        }

        const datos = new FormData();
        datos.append('id_metodopago', id_metodopago);
        datos.append('nombre', nombre);
        datos.append('descripcion', descripcion);
        datos.append('modificar', 'modificar');
        enviaAjax(datos);
    });
});

// Función para abrir el modal de modificación
function abrirModalModificar(id_metodopago, nombre, descripcion) {
    document.getElementById('id_metodopago_modificar').value = id_metodopago;
    document.getElementById('nombre_modificar').value = nombre;
    document.getElementById('descripcion_modificar').value = descripcion;
    $('#modificar').modal('show');
}

// Función para eliminar un método de pago
function eliminarMetodoPago(id_metodopago) {
    if (confirm('¿Estás seguro de que deseas eliminar este método de pago?')) {
        const datos = new FormData();
        datos.append('id_metodopago', id_metodopago);
        datos.append('eliminar', 'eliminar');
        enviaAjax(datos);
    }
}

// Función para mostrar mensajes
function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

// Función para enviar los datos por Ajax
function enviaAjax(datos) {
    fetch('', {
        method: 'POST',
        body: datos,
    })
    .then(response => response.json())
    .then(data => {
        if (data.accion === 'incluir') {
            if (data.respuesta === 1) {
                document.getElementById('nombre').value = '';
                document.getElementById('descripcion').value = '';
                muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 1000, "ERROR", "ERROR al registrar");
            }
        } else if (data.accion === 'actualizar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Se ha modificado con éxito", "Su registro se ha actualizado exitosamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 2000, "ERROR", "ERROR al modificar");
            }
        } else if (data.accion === 'eliminar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 2000, "ERROR", "ERROR al eliminar");
            }
        }
    })
    .catch(error => {
        muestraMensaje("error", 2000, "Error", "ERROR: " + error);
    });
}

// Función para validar la entrada con keypress
function validarkeypress(er, e){
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault();
    }
}

// Función para validar la entrada con keyup
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

// Función para validar los campos antes de enviar
function validarenvio() {
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo nombre");
        return false;
    }

    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#descripcion"), $("#sdescripcion"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo descripción");
        return false;
    }

    return true;
}

function validarModificacion() {
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre_modificar"), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo nombre");
        return false;
    }

    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#descripcion_modificar"), $("#sdescripcion_modificar"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo descripción");
        return false;
    }

    return true;
}
