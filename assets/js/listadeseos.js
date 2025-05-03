document.addEventListener('DOMContentLoaded', function () {
    cargarListaDeseos();
  
    function cargarListaDeseos() {
      fetch('../controlador/listadeseos.php?accion=listar')
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tablaListaDeseos tbody');
          tbody.innerHTML = '';
          data.forEach(item => {
            tbody.innerHTML += `
              <tr>
                <td>${item.id_lista}</td>
                <td>${item.producto}</td>
                <td>${item.fecha_agregado}</td>
                <td>
                  <button class="btn btn-success btn-sm" onclick="agregarAPedido(${item.id_lista}, '${item.producto}')">Añadir a Pedido</button>
                  <button class="btn btn-danger btn-sm" onclick="eliminarDeseo(${item.id_lista})">Eliminar</button>
                </td>
              </tr>
            `;
          });
        });
    }
  
    window.eliminarDeseo = function (id_lista) {
      fetch('../controlador/listadeseos.php?accion=eliminar', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id_lista=${id_lista}`
      })
      .then(res => res.json())
      .then(() => cargarListaDeseos());
    }
  
    window.agregarAPedido = function (id_lista, nombreProducto) {
      fetch('../controlador/listadeseos.php?accion=agregar_pedido', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id_producto=${id_lista}` // Ajusta si el ID correcto es diferente
      })
      .then(res => res.json())
      .then(() => {
        alert(`Producto "${nombreProducto}" añadido al pedido.`);
        eliminarDeseo(id_lista); // También lo quitamos de la lista
      });
    }
  });
  