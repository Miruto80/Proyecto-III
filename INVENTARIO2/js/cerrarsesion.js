$(document).ready(function(){

cerrarSesion();

function cerrarSesion() {
/*Cerrar sesion*/
    document.getElementById('cerrarSesion').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Deseas cerrar la sesión?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, cerrar sesión",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                // Aquí va el código para cerrar la sesión
                window.location.href = '?pagina=login'; // Ajusta esta URL según tu configuración
            }
        });
    });
}

});