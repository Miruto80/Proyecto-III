document.addEventListener('DOMContentLoaded', function () {

    // Añadir a pedido
    document.querySelectorAll('.btnAñadirPedido').forEach(button => {
        button.addEventListener('click', function () {
            const id_lista = this.getAttribute('data-id-lista');
            const id_persona = this.getAttribute('data-id-persona');
            const datos = new FormData();
            datos.append('id_lista', id_lista);
            datos.append('id_persona', id_persona);
            datos.append('añadir_a_pedido', 'añadir_a_pedido');
            enviaAjax(datos, 'añadir');
        });
    });

    // Eliminar de la lista de deseos
    document.querySelectorAll('.btnEliminarListaDeseo').forEach(button => {
        button.addEventListener('click', function () {
            const id_lista = this.getAttribute('data-id-lista');
            const datos = new FormData();
            datos.append('id_lista', id_lista);
            datos.append('eliminar_lista', 'eliminar_lista');
            enviaAjax(datos, 'eliminar');
        });
    });

});

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

// Función para enviar solicitudes Ajax
function enviaAjax(datos, accion) {
    fetch('', {
        method: 'POST',
        body: datos,
    })
    .then(response => response.json())
    .then(data => {
        if (accion === 'añadir') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Se ha añadido al pedido con éxito", "El artículo se ha añadido correctamente al pedido");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 1000, "ERROR", "ERROR al añadir al pedido");
            }
        } else if (accion === 'eliminar') {
            if (data.respuesta === 1) {
                muestraMensaje("success", 1000, "Se ha eliminado con éxito", "El artículo ha sido eliminado de la lista de deseos");
                setTimeout(() => location.reload(), 1000);
            } else {
                muestraMensaje("error", 1000, "ERROR", "ERROR al eliminar");
            }
        }
    })
    .catch(error => {
        muestraMensaje("error", 2000, "Error", "ERROR: " + error);
    });
}
