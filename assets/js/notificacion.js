document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.btn-leer').forEach(button => {
        button.addEventListener('click', function() {
            let idNotificacion = this.getAttribute('data-id');

            fetch('', { // Asegura que la ruta sea correcta
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `accion=leer&id_notificaciones=${idNotificacion}`
            })
            .then(response => response.text())
            .then(data => {
                try {
                    let parsedData = JSON.parse(data);
                    if (parsedData.respuesta === 1) {
                        Swal.fire({
                            title: "¡Éxito!",
                            text: "Notificación marcada como leída.",
                            icon: "success",
                            confirmButtonText: "Aceptar"
                        });
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "No se pudo marcar como leída.",
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                    }
                } catch (error) {
                    console.error("Error en la respuesta del servidor:", data);
                    Swal.fire({
                        title: "Error",
                        text: "Problema en la comunicación con el servidor.",
                        icon: "warning",
                        confirmButtonText: "Aceptar"
                    });
                }
            });
        });
    });
});



document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function() {
            let idNotificacion = this.getAttribute('data-id');

            fetch('', { // Ruta corregida
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `accion=eliminar&id_notificaciones=${idNotificacion}`
            })
            .then(response => response.text())  // Cambia a .text() para visualizar la respuesta
            .then(data => {
                console.log("Respuesta del servidor:", data); // Depuración
                try {
                    let parsedData = JSON.parse(data);

                    if (parsedData.respuesta === 1) {
                        document.querySelector(`#notificacion-${idNotificacion}`).remove(); // Elimina de la vista
                        Swal.fire({
                            title: "¡Éxito!",
                            text: "Notificación eliminada correctamente.",
                            icon: "success",
                            confirmButtonText: "Aceptar"
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: parsedData.mensaje || "No se pudo eliminar la notificación.",
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                    }
                } catch (error) {
                    console.error("Error al procesar JSON:", data);
                    Swal.fire({
                        title: "Error",
                        text: "Problema en la comunicación con el servidor.",
                        icon: "warning",
                        confirmButtonText: "Aceptar"
                    });
                }
            });
        });
    });
});
