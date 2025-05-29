document.addEventListener('DOMContentLoaded', () => {
  const showMsg = (icon, title, msg, duration = 1500) => {
    Swal.fire({ icon, title, html: msg, timer: duration, showConfirmButton: false });
  };


document.querySelectorAll('.btn-eliminar-deseo').forEach(button => {
  button.addEventListener('click', function () {
    const idLista = this.getAttribute('data-id-lista');

    if (!idLista) {
      Swal.fire('Error', 'ID del producto no válido.', 'error');
      return;
    }

    Swal.fire({
      title: '¿Estás seguro?',
      text: "Este producto se eliminará de tu lista de deseos.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('?pagina=listadeseo', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            accion: 'eliminar',
            id_lista: idLista
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: '¡Eliminado!',
              text: 'Producto eliminado de tu lista.',
              showConfirmButton: false,
              timer: 1500
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire('Error', data.message || 'No se pudo eliminar.', 'error');
          }
        })
        .catch(error => {
          console.error('Error al eliminar:', error);
          Swal.fire('Error', 'No se pudo realizar la acción.', 'error');
        });
      }
    });
  });
});




  // Vaciar toda la lista
  const btnVaciar = document.getElementById('btn-vaciar-lista');
  if (btnVaciar) {
    btnVaciar.addEventListener('click', () => {
      Swal.fire({
        icon: 'warning',
        title: '¿Vaciar lista?',
        text: 'Se eliminarán todos los productos de tu lista de deseos.',
        showCancelButton: true,
        confirmButtonText: 'Sí, vaciar',
        cancelButtonText: 'Cancelar'
      }).then(result => {
        if (result.isConfirmed) {
          fetch('?pagina=listadeseo', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'accion=vaciar'
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              location.reload();
            } else {
              showMsg('error', 'Error', data.message || 'No se pudo vaciar la lista.');
            }
          })
          .catch(() => showMsg('error', 'Error', 'Error al procesar la solicitud.'));
        }
      });
    });
  }
});
