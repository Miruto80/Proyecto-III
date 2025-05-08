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

// Manejar el botón de modificar
document.getElementById('btnModificar').addEventListener('click', function () {
    // Validar campos requeridos
    if (!validarFormulario('formModificar')) {
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
    
    campos.forEach(campo => {
        if (!campo.value.trim()) {
            campo.classList.add('is-invalid');
            valido = false;
        } else {
            campo.classList.remove('is-invalid');
        }
    });
    
    if (!valido) {
        muestraMensaje("warning", 3000, "Campos incompletos", "Por favor, complete todos los campos obligatorios");
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
        // Verificar si la respuesta es JSON válido
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // Si no es JSON, mostramos un error y rechazamos la promesa
            throw new Error('La respuesta no es un JSON válido.');
        }
    })
    .then(data => {
        // Llenar el formulario con los datos obtenidos
        document.getElementById('id_proveedor_modificar').value = data.id_proveedor;
        document.getElementById('tipo_documento_modificar').value = data.tipo_documento || '';
        document.getElementById('numero_documento_modificar').value = data.numero_documento || '';
        document.getElementById('nombre_modificar').value = data.nombre || '';
        document.getElementById('correo_modificar').value = data.correo || '';
        document.getElementById('telefono_modificar').value = data.telefono || '';
        document.getElementById('direccion_modificar').value = data.direccion || '';
        
        // Mostrar el modal
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
        // Verificar primero si la respuesta es JSON válido
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // Si no es JSON, convertimos la respuesta a texto para ver qué está devolviendo
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
        // Mostrar mensaje de error más informativo
        console.error("Error en la solicitud AJAX:", error);
        muestraMensaje("error", 5000, "Error de comunicación", 
            "ERROR en la comunicación con el servidor: " + error.message);
    });
}