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


    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function() {
            let idNotificacion = this.getAttribute('data-id');

            fetch('', { // Ajusta la ruta
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `accion=eliminar&id_notificaciones=${idNotificacion}`
            })
            .then(response => response.text())
            .then(data => {
                try {
                    let parsedData = JSON.parse(data);
                    if (parsedData.respuesta === 1) {
                        alert("Notificación eliminada correctamente.");
                        location.reload();
                    } else {
                        alert("Error al eliminar la notificación.");
                    }
                } catch (error) {
                    console.error("Error en la respuesta del servidor:", data);
                    alert("Error en la comunicación con el servidor.");
                }
            });
        });
    });
