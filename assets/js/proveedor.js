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
  $('#id_proveedor').val('');
  $('#accion').val('registrar');
  $('#modalTitle').text('Registrar Proveedor');
  
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
        if (validarkeyup(/^[0-9]{7,9}$/, $("#numero_documento"), $("#snumero_documento"), "Ingrese una cédula válida") === 0) {
            valido = false;
        }

        if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") === 0) {
            valido = false;
        }

        if (validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $("#correo"), $("#scorreo"), "El formato debe incluir @ y un dominio con punto") === 0) {
            valido = false;
        }

        if (validarkeyup(/^(04|02)[0-9]{9}$/, $("#telefono"), $("#stelefono"), "Debe comenzar en 04 o 02 y tener el formato 04xx-XXXXXXX") === 0) {
            valido = false;
        }

        if (validarkeyup(/^.{3,70}$/, $("#direccion"), $("#sdireccion"), "La dirección debe tener entre 3 y 100 caracteres") === 0) {
            valido = false;
        }
    } else if (formId === 'formModificar') {
        if (validarkeyup(/^[0-9]{7,9}$/, $("#numero_documento_modificar"), $("#snumero_documento_modificar"), "Ingrese una cédula válida") === 0) {
            valido = false;
        }

        if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre_modificar"), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres") === 0) {
            valido = false;
        }

        if (validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $("#correo_modificar"), $("#scorreo_modificar"), "El formato debe incluir @ y un dominio con punto") === 0) {
            valido = false;
        }

        if (validarkeyup(/^(04|02)[0-9]{9}$/, $("#telefono_modificar"), $("#stelefono_modificar"), "Debe comenzar en 04 o 02 y tener el formato 04xx-XXXXXXX") === 0) {
            valido = false;
        }

        if (validarkeyup(/^.{3,70}$/, $("#direccion_modificar"), $("#sdireccion_modificar"), "La dirección debe tener entre 3 y 100 caracteres") === 0) {
            valido = false;
        }
    }

    return valido;
}

// Función para abrir el modal de modificar y cargar datos
function abrirModalModificar(id_proveedor) {
    // Consultar datos del proveedor por ID
    const datos = new FormData();
    datos.append('id_proveedor', id_proveedor);
    datos.append('consultar_proveedor', 'consultar_proveedor');
    
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
        document.getElementById('id_proveedor_modificar').value = data.id_proveedor;
        document.getElementById('tipo_documento_modificar').value = data.tipo_documento || '';
        document.getElementById('numero_documento_modificar').value = data.numero_documento || '';
        document.getElementById('nombre_modificar').value = data.nombre || '';
        document.getElementById('correo_modificar').value = data.correo || '';
        document.getElementById('telefono_modificar').value = data.telefono || '';
        document.getElementById('direccion_modificar').value = data.direccion || '';
        
        $('#formModificar .is-invalid').removeClass('is-invalid');
        $('#formModificar .is-valid').removeClass('is-valid');
        $('#formModificar span.text-danger').text('');
        
        $('#modificar').modal('show');
    })
    .catch(error => {
        muestraMensaje("error", 2000, "Error", "ERROR al cargar los datos del proveedor: " + error);
    });
}

// Función para eliminar un proveedor
function eliminarProveedor(id_proveedor) {
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
            datos.append('id_proveedor', id_proveedor);
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

                muestraMensaje("success", 1500, "Se ha registrado con éxito", "El proveedor se ha registrado exitosamente");
                setTimeout(() => location.reload(), 1500);
            } else {
                muestraMensaje("error", 2000, "ERROR", "ERROR al registrar el proveedor");
            }
        } else if (data.accion === 'actualizar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1500, "Se ha Modificado con éxito", "El proveedor se ha actualizado exitosamente");
                setTimeout(() => location.reload(), 1500);
            } else {
                muestraMensaje("error", 2000, "ERROR", "ERROR al modificar el proveedor");
            }
        } else if (data.accion === 'eliminar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1500, "Se ha eliminado con éxito", "El proveedor se ha eliminado correctamente");
                setTimeout(() => location.reload(), 1500);
            } else {
                muestraMensaje("error", 2000, "ERROR", "ERROR al eliminar el proveedor");
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
// Validar número de documento según el tipo seleccionado
$("#tipo_documento").on("change", function () {
    let tipo = $(this).val();
    let maxDigitos;

    switch (tipo) {
        case "V":
            maxDigitos = 8; // Cédula Venezolana
            break;
        case "J":
        case "G":
            maxDigitos = 9; // RIF Jurídico y Gubernamental
            break;
        case "E":
            maxDigitos = 9; // Cédula Extranjera
            break;
        default:
            maxDigitos = 9; // Valor por defecto
    }

    $("#numero_documento").attr("maxlength", maxDigitos); // Ajusta dinámicamente el límite
    $("#numero_documento").val(""); // Limpia el campo para evitar valores erróneos
    $("#snumero_documento").text(`Ingrese ${maxDigitos} dígitos`);
});

$("#nombre").on("keypress", function(e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#nombre").on("keyup", function() {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
});

$("#correo").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9._%+-@\b]*$/, e);
});
$("#correo").on("keyup", function() {
    validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $(this), $("#scorreo"), "El formato debe incluir @ y un dominio con punto (ej: proveedor@dominio.com)");
});

$("#telefono").on("keypress", function(e) {
    validarkeypress(/^[0-9\b-]*$/, e);
});
$("#telefono").on("keyup", function() {
    validarkeyup(/^(04|02)[0-9]{9}$/, $(this), $("#stelefono"), "Debe comenzar en 04 o 02 y tener el formato 04xx-XXXXXXX");
});

$("#numero_documento, #numero_documento_modificar").on("keypress", function(e) {
    validarkeypress(/^[0-9]*$/, e); // Solo permite números
});

$("#direccion").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9\s\#\-\.,]*$/, e);
});
$("#direccion").on("keyup", function() {
    validarkeyup(/^.{3,70}$/, $(this), $("#sdireccion"), "La dirección debe tener entre 3 y 70 caracteres");
});

// Eventos keypress y keyup para modificar (mismos patrones que registro)
$("#tipo_documento_modificar").on("change", function () {
    let tipo = $(this).val();
    let maxDigitos;

    switch (tipo) {
        case "V":
            maxDigitos = 8;
            break;
        case "J":
        case "G":
            maxDigitos = 9;
            break;
        case "E":
            maxDigitos = 9;
            break;
        default:
            maxDigitos = 9;
    }

    $("#numero_documento_modificar").attr("maxlength", maxDigitos);
    $("#numero_documento_modificar").val(""); // Vacía el campo cuando cambia el tipo
    $("#snumero_documento_modificar").text(`Ingrese ${maxDigitos} dígitos`);
});

$("#nombre_modificar").on("keypress", function(e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#nombre_modificar").on("keyup", function() {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres");
});

$("#correo_modificar").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9._%+-@\b]*$/, e);
});
$("#correo_modificar").on("keyup", function() {
    validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $(this), $("#scorreo_modificar"), "El formato debe incluir @ y un dominio con punto (ej: proveedor@dominio.com)");
});

$("#telefono_modificar").on("keypress", function(e) {
    validarkeypress(/^[0-9\b-]*$/, e);
});
$("#telefono_modificar").on("keyup", function() {
    validarkeyup(/^(04|02)[0-9]{9}$/, $(this), $("#stelefono_modificar"), "Debe comenzar en 04 o 02 y tener el formato 04xx-XXXXXXX");
});

$("#direccion_modificar").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9\s\#\-\.,]*$/, e);
});
$("#direccion_modificar").on("keyup", function() {
    validarkeyup(/^.{3,70}$/, $(this), $("#sdireccion_modificar"), "La dirección debe tener entre 3 y 70 caracteres");
});

// Evento para validar número de documento dinámicamente
$("#numero_documento, #numero_documento_modificar").on("keyup", function() {
    let tipo = $(this).closest("form").find("[name='tipo_documento']").val();
    let maxDigitos;
    let regex;

    switch (tipo) {
        case "V":
            maxDigitos = 8;
            regex = /^[0-9]{8}$/; // Solo permite 8 números
            break;
        case "J":
        case "G":
            maxDigitos = 9;
            regex = /^[0-9]{9}$/; // Solo permite 9 números
            break;
        case "E":
            maxDigitos = 9;
            regex = /^[0-9]{9}$/; // Solo permite 9 números
            break;
        default:
            maxDigitos = 9;
            regex = /^[0-9]{7,9}$/; // Solo permite números dentro del rango
    }

    validarkeyup(regex, $(this), $(this).siblings("span"), `Debe ingresar ${maxDigitos} dígitos numéricos`);
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

