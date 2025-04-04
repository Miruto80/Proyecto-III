$(document).ready(function() {
    // Cuando el documento está listo, se configura el evento de envío del formulario de inicio de sesión
    $('#loginForm').on('submit', function(e) {
        e.preventDefault(); // Previene el comportamiento por defecto del formulario (recarga de página)
        
        var datos = new FormData(); // Crea un nuevo objeto FormData para enviar datos
        datos.append('accion', 'login'); // Agrega la acción 'login' a los datos
        datos.append('nombre_usuario', $('#nombre_usuario').val()); // Agrega el nombre de usuario
        datos.append('contraseña', $('#contraseña').val()); // Agrega la contraseña

        // Realiza una solicitud AJAX
        $.ajax({
            url: '?pagina=login', // URL a la que se envían los datos
            type: 'POST', // Método de envío
            data: datos, // Datos a enviar
            processData: false, // No procesa los datos
            contentType: false, // No establece el tipo de contenido
            success: function(respuesta) { // Función que se ejecuta si la solicitud es exitosa
                try {
                    var lee = JSON.parse(respuesta); // Intenta parsear la respuesta JSON
                    if (lee.resultado == "login_success") { // Verifica si el inicio de sesión fue exitoso
                        window.location.href = '?pagina=principal'; // Redirige a la página principal
                    } else {
                        mostrarError(lee.mensaje); // Muestra un mensaje de error
                    }
                } catch (e) {
                    mostrarError("Error en la respuesta del servidor"); // Manejo de errores en el parseo
                }
            },
            error: function() {
                mostrarError("Error en la conexión"); // Muestra un mensaje de error si la solicitud falla
            }
        });
    });

    // Función para mostrar errores utilizando SweetAlert
    function mostrarError(mensaje) {
        Swal.fire({
            html: `<p style="color: red;">${mensaje}</p>`, // Mensaje de error en rojo
            icon: 'error', // Icono de error
            showConfirmButton: false, // No muestra botón de confirmación
            timer: 5000, // Duración del mensaje
            toast: true, // Muestra como un toast
            position: 'top', // Posición del toast
            customClass: {
                popup: 'swal2-show-error', // Clase personalizada para el popup
                title: 'swal2-title-error' // Clase personalizada para el título
            }
        });
    }     
});