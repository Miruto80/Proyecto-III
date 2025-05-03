document.addEventListener('DOMContentLoaded', function () {
    // Registrar tipo de usuario
    document.getElementById('registrar').addEventListener('click', function () {
        const nombre = document.getElementById('nombre').value;
        const nivel = document.getElementById('nivel').value;
        const datos = new FormData();
        datos.append('nombre', nombre);
        datos.append('nivel', nivel);
        datos.append('registrar', 'registrar');
        enviaAjax(datos);
    });

    // Modificar tipo de usuario
    document.getElementById('btnModificar').addEventListener('click', function () {
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

function abrirModalModificar(id_tipo, nombre, nivel, estatus) {
    document.getElementById('id_tipo_modificar').value = id_tipo;
    document.getElementById('nombre_modificar').value = nombre;
    document.getElementById('nivel_modificar').value = nivel;
    document.getElementById('estatus_modificar').value = estatus;
    $('#modificar').modal('show');
}

function eliminarTipoUsuario(id_tipo) {
    if (confirm('¿Estás seguro de que deseas eliminar este tipo de usuario?')) {
        const datos = new FormData();
        datos.append('id_tipo', id_tipo);
        datos.append('eliminar', 'eliminar');
        enviaAjax(datos);
    }
}

function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

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
                document.getElementById('nivel').value = '';
                muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 1000, "ERROR", "ERROR al registrar");
            }
        } else if (data.accion === 'actualizar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
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
