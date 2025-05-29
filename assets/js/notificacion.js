fetch('notificacion.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `accion=leer&id_notificaciones=${idNotificacion}`
})
.then(response => response.text()) // Obtén la respuesta como texto antes de convertirla
.then(data => {
    console.log("Respuesta del servidor:", data); // Verifica qué está devolviendo
    return JSON.parse(data); // Intenta parsear después de comprobar
})
.then(parsedData => {
    console.log("JSON válido:", parsedData); // JSON correctamente parseado
})
.catch(error => console.error("Error en el JSON:", error));


document.querySelector('.btn-vaciar').addEventListener('click', function() {
    fetch('notificacion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'accion=vaciar'
    })
    .then(response => response.json())
    .then(data => {
        if (data.respuesta === 1) {
            alert("Notificaciones leídas eliminadas correctamente.");
            location.reload();
        } else {
            alert("Hubo un error al vaciar las notificaciones.");
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    // Botón de leer
    document.querySelectorAll('.btn-leer').forEach(button => {
        button.addEventListener('click', function() {
            let idNotificacion = this.getAttribute('data-id');

            fetch('notificacion.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `accion=leer&id_notificaciones=${idNotificacion}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.respuesta === 1) {
                    let row = document.querySelector(`#notificacion-${idNotificacion} .estado`);
                    row.textContent = 'Leída';
                    alert("Notificación marcada como leída.");
                } else {
                    alert("Error al marcar como leída.");
                }
            });
        });
    });

    // Botón de eliminar
    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function() {
            let idNotificacion = this.getAttribute('data-id');

            fetch('notificacion.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `accion=eliminar&id_notificaciones=${idNotificacion}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.respuesta === 1) {
                    document.querySelector(`#notificacion-${idNotificacion}`).remove();
                    alert("Notificación eliminada correctamente.");
                } else {
                    alert("Error al eliminar la notificación.");
                }
            });
        });
    });
});
