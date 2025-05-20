document.addEventListener('DOMContentLoaded', function () {
    const btnVaciar = document.getElementById('btn-vaciar-deseos');
    if (btnVaciar) {
        btnVaciar.addEventListener('click', function () {
            if (confirm("¿Estás seguro de que quieres vaciar tu lista de deseos?")) {
                fetch('vista/tienda/catalogo_favorito.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'accion=vaciar'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exito) {
                        alert('Lista de deseos vaciada correctamente');
                        location.reload(); // Opcional: recarga para actualizar vista
                    } else {
                        alert('No se pudo vaciar la lista de deseos');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
});



