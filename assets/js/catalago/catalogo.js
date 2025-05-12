
document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll('.filtro-checkbox');
    const productos = document.querySelectorAll('.product-item[data-categoria]');
  
    checkboxes.forEach(cb => {
      cb.addEventListener('change', () => {
        const categoriasSeleccionadas = Array.from(checkboxes)
          .filter(c => c.checked)
          .map(c => c.value);
  
        productos.forEach(prod => {
          // Buscar el contenedor padre que es la columna de la grilla
          const container = prod.closest('.col');
          const categoria = prod.getAttribute('data-categoria');
          if (categoriasSeleccionadas.length === 0 || categoriasSeleccionadas.includes(categoria)) {
            container.style.display = ''; // Se muestra la columna
          } else {
            container.style.display = 'none'; // Se oculta y no ocupa espacio
          }
        });
      });
    });
  });
  


function openModal(element) {
    const nombre = element.dataset.nombre;
    const precio = element.dataset.precio;
    const imagen = element.dataset.imagen;
    const marca = element.dataset.marca || 'N/A';
    const descripcion = element.dataset.descripcion || 'N/A';
    const cantidadMayor = element.dataset.cantidadMayor || 'N/A';
    const precioMayor = element.dataset.precioMayor || 'N/A';
    const stockDisponible = element.dataset.stockDisponible || 'N/A';

    // Insertar datos en el modal
    document.getElementById('modal-title').textContent = nombre;
    document.getElementById('modal-precio').textContent = "$" + precio;
    document.getElementById('modal-imagen').src = imagen;
    document.getElementById('modal-marca').textContent = marca;
    document.getElementById('modal-descripcion').textContent = descripcion;
    document.getElementById('modal-cantidad-mayor').textContent = cantidadMayor;
    document.getElementById('modal-precio-mayor').textContent = "$" + precioMayor;
    document.getElementById('modal-stock-disponible').textContent = stockDisponible;

    // Rellenar formulario oculto
    document.getElementById('form-id').value = element.dataset.id;
    document.getElementById('form-nombre').value = nombre;
    document.getElementById('form-precio-detal').value = precio;
    document.getElementById('form-precio-mayor').value = precioMayor;
    document.getElementById('form-cantidad-mayor').value = cantidadMayor;
    document.getElementById('form-imagen').value = imagen;
}

document.addEventListener("DOMContentLoaded", () => {
    const formCarrito = document.getElementById("form-carrito");
    const btnAgregarCarrito = document.getElementById("btn-agregar-carrito");

    if (btnAgregarCarrito && formCarrito) {
        btnAgregarCarrito.addEventListener("click", (e) => {
            e.preventDefault();

            const formData = new FormData(formCarrito);

            fetch("controlador/carrito.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ulCarrito = document.querySelector('.carrito-dropdown');
                    if (!ulCarrito) {
                        console.error("No se encontró el <ul> del carrito en el HTML.");
                        return;
                    }

                    const liVacio = ulCarrito.querySelector('li.text-center');
                    if (liVacio) {
                        liVacio.remove();
                    }

                    const id = data.producto.id;
                    let itemExistente = ulCarrito.querySelector(`li[data-id="${id}"]`);

                    if (!itemExistente) {
                       
                        const item = document.createElement('li');
                        item.className = 'list-group-item d-flex justify-content-between lh-sm';
                        item.setAttribute('data-id', id);

                        item.innerHTML = `
                            <div>
                                <h6 class="fs-5 fw-normal my-0">${data.producto.nombre}</h6>
                                <small class="text-muted cantidad-texto">${data.producto.cantidad} x $${data.producto.precio_unitario}</small>
                            </div>
                            <div class="text-end">
                                <span class="text-body-secondary subtotal-texto">$${data.producto.subtotal}</span><br>
                                <button class="btn-eliminar btn btn-sm btn-outline-danger mt-1" data-id="${id}">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                        `;

                        ulCarrito.insertBefore(item, ulCarrito.lastElementChild); 

                      
                        item.querySelector('.btn-eliminar').addEventListener('click', function (e) {
                            e.preventDefault();
                            eliminarProducto(id);
                        });
                        setTimeout(() => location.reload(), 500);
                    } else {
                      
                        itemExistente.querySelector('small.cantidad-texto').textContent = `${data.producto.cantidad} x $${data.producto.precio_unitario}`;
                        itemExistente.querySelector('span.subtotal-texto').textContent = `$${data.producto.subtotal}`;
                    }

                    // Actualizar contador
                    const contador = document.querySelector('.contador');
                    if (contador) {
                       
                        if (!itemExistente) {
                            contador.textContent = parseInt(contador.textContent) + 1;
                        }
                    }

                    const totalGeneral = document.getElementById('total-general');
                    if (totalGeneral) {
                        totalGeneral.textContent = data.total_general; 
                    }
                } else {
                    alert("Error: " + data.mensaje);
                }
            })
            .catch(error => console.error("Error al procesar la solicitud:", error));
        });
    }
});
