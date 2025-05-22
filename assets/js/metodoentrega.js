// Manejar el botón de registro
document.getElementById('registrar').addEventListener('click', function () {
    // Validar campos requeridos
    if (!validarFormulario('formRegistrar')) {
        return;
    }

    const formData = new FormData(document.getElementById('formRegistrar'));
    formData.append('registrar', 'registrar');
    
    enviaAjax(formData);
});

$('#btnAbrirRegistrar').on('click', function () {
  // Limpiar formulario
  $('#formRegistrar')[0].reset();
  $('#id_entrega').val('');
  $('#accion').val('registrar');
  $('#modalTitle').text('Registrar Metodo de Entrega');
  
  // Limpiar validaciones y mensajes
  $('#formRegistrar .is-invalid').removeClass('is-invalid');
  $('#formRegistrar .is-valid').removeClass('is-valid');
  $('#formRegistrar span.text-danger').text('');

  $('#registro').modal('show'); // mostrar modal
});

// Manejar el botón de modificar
document.getElementById('btnModificar').addEventListener('click', function () {
    // Validar campos requeridos
    if (!validarFormulario('formModificar')) {
        // Mostrar mensaje general si la validación falla
        muestraMensaje("error", 2000, "Error", "Por favor corrige los campos con errores antes de modificar.");
        return;
    }

    const formData = new FormData(document.getElementById('formModificar'));
    formData.append('modificar', 'modificar');
    
    enviaAjax(formData);
});

// Función para validar formularios
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    const campos = form.querySelectorAll('[required]');
    let valido = true;

    // Validar campos vacíos y mostrar error visual
    campos.forEach(campo => {
        if (!campo.value.trim()) {
            campo.classList.add('is-invalid');
            valido = false;
        } else {
            campo.classList.remove('is-invalid');
        }
    });

    // Validaciones específicas por campo y mostrar mensaje en los spans correspondientes
    if (formId === 'formRegistrar') {

        if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") === 0) {
            valido = false;
        }

        if (validarkeyup(/^.{3,70}$/, $("#descripcion"), $("#sdescripcion"), "La descripcion debe tener entre 3 y 70 caracteres") === 0) {
            valido = false;
        }
    } else if (formId === 'formModificar') {

        if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre_modificar"), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres") === 0) {
            valido = false;
        }

        if (validarkeyup(/^.{3,70}$/, $("#descripcion_modificar"), $("#sdescripcion_modificar"), "La descripcion debe tener entre 3 y 70 caracteres") === 0) {
            valido = false;
        }
    }

    return valido;
}

// Función para abrir el modal de modificar y cargar datos
function abrirModalModificar(id_entrega) {
    // Consultar datos del metodoentrega por ID
    const datos = new FormData();
    datos.append('id_entrega', id_entrega);
    datos.append('consultar_metodoentrega', 'consultar_metodoentrega');
    
    fetch('', {
        method: 'POST',
        body: datos,
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            throw new Error('La respuesta no es un JSON válido.');
        }
    })
    .then(data => {
        document.getElementById('id_entrega_modificar').value = data.id_entrega;
        document.getElementById('nombre_modificar').value = data.nombre || '';
        document.getElementById('descripcion_modificar').value = data.descripcion || '';
        
        $('#formModificar .is-invalid').removeClass('is-invalid');
        $('#formModificar .is-valid').removeClass('is-valid');
        $('#formModificar span.text-danger').text('');
        
        $('#modificar').modal('show');
    })
    .catch(error => {
        muestraMensaje("error", 2000, "Error", "ERROR al cargar los datos del metodo de entrega: " + error);
    });
}

// Función para eliminar un proveedor
function eliminarMetodoEntrega(id_entrega) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const datos = new FormData();
            datos.append('id_proveedor', id_entrega);
            datos.append('eliminar', 'eliminar');
            enviaAjax(datos);
        }
    });
}

// Función para mostrar mensajes con SweetAlert
function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

// Función para enviar solicitudes AJAX
function enviaAjax(datos) {
    fetch('', {
        method: 'POST',
        body: datos,
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text().then(text => {
                throw new Error('La respuesta no es JSON válido. Respuesta recibida: ' + 
                    (text.length > 100 ? text.substring(0, 100) + '...' : text));
            });
        }
    })
    .then(data => {
        if (data.accion === 'incluir') {
            if (data.respuesta === 1) {
                document.getElementById('formRegistrar').reset();

                // Limpiar validaciones y mensajes después de reset
                $('#formRegistrar .is-invalid').removeClass('is-invalid');
                $('#formRegistrar .is-valid').removeClass('is-valid');
                $('#formRegistrar span.text-danger').text('');

                muestraMensaje("success", 1500, "Se ha registrado con éxito", "El Metodo de entrega se ha registrado exitosamente");
                setTimeout(() => location.reload(), 1500);
            } else {
                muestraMensaje("error", 2000, "ERROR", "ERROR al registrar el Metodo de entrega");
            }
        } else if (data.accion === 'actualizar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1500, "Se ha Modificado con éxito", "El Metodo de entrega se ha actualizado exitosamente");
                setTimeout(() => location.reload(), 1500);
            } else {
                muestraMensaje("error", 2000, "ERROR", "ERROR al modificar el Metodo de entrega");
            }
        } else if (data.accion === 'eliminar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1500, "Se ha eliminado con éxito", "El Metodo de entrega se ha eliminado correctamente");
                setTimeout(() => location.reload(), 1500);
            } else {
                muestraMensaje("error", 2000, "ERROR", "ERROR al eliminar el Metodo de entrega");
            }
        }
        
    })
    .catch(error => {
        console.error("Error en la solicitud AJAX:", error);
        muestraMensaje("error", 5000, "Error de comunicación", 
            "ERROR en la comunicación con el servidor: " + error.message);
    });
}

// Eventos keypress y keyup para validar ingreso correcto de caracteres y mostrar mensajes debajo, para registro

$("#nombre").on("keypress", function(e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#nombre").on("keyup", function() {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
});

$("#descripcion").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9\s\#\-\.,]*$/, e);
});
$("#descripcion").on("keyup", function() {
    validarkeyup(/^.{3,70}$/, $(this), $("#sdescripcion"), "La descripcion debe tener entre 3 y 70 caracteres");
});

// Eventos keypress y keyup para modificar (mismos patrones que registro)

$("#nombre_modificar").on("keypress", function(e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#nombre_modificar").on("keyup", function() {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres");
});

$("#descripcion_modificar").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9\s\#\-\.,]*$/, e);
});
$("#descripcion_modificar").on("keyup", function() {
    validarkeyup(/^.{3,70}$/, $(this), $("#sdescripcion_modificar"), "La descripcion debe tener entre 3 y 70 caracteres");
});

// Función para validar por keypress - permite solo caracteres que pasen la regex
function validarkeypress(er, e) {
    let key = e.keyCode || e.which;
    let tecla = String.fromCharCode(key);
    if (!er.test(tecla)) {
        e.preventDefault();
    }
}

// Función para validar por keyup - muestra mensaje y retorna 1 si válido, 0 si no
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    let valor = etiqueta.val();
    if (valor.trim() === '') {
        etiqueta.removeClass('is-valid').addClass('is-invalid');
        etiquetamensaje.text("Este campo es obligatorio");
        return 0;
    }
    if (er.test(valor)) {
        etiquetamensaje.text('');
        etiqueta.removeClass('is-invalid').addClass('is-valid');
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        etiqueta.removeClass('is-valid').addClass('is-invalid');
        return 0;
    }
}

