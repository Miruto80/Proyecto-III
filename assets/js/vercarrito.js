function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
  }

document.addEventListener("DOMContentLoaded", () => {
    // Botones "+"
    document.querySelectorAll(".btn-mas").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.getAttribute("data-id");
            const stock = parseInt(btn.getAttribute("data-stock"));
            const fila = btn.closest("tr");
            const cantidadSpan = fila.querySelector(".cantidad");
            let cantidadActual = parseInt(cantidadSpan.textContent);

            if (cantidadActual < stock) {
                cantidadActual++; // Incrementar cantidad localmente
                actualizarCantidad(id, cantidadActual);
            } else {
                console.log("Stock disponible:", stock);
                muestraMensaje('error', 1000, 'Stock limitado', 'Ya has agregado el máximo permitido.');
            }
        });
    });

    // Botones "-"
    document.querySelectorAll(".btn-menos").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.getAttribute("data-id");
            const fila = btn.closest("tr");
            const cantidadSpan = fila.querySelector(".cantidad");
            let cantidadActual = parseInt(cantidadSpan.textContent);

            if (cantidadActual > 1) {
                cantidadActual--; // Decrementar cantidad localmente
                actualizarCantidad(id, cantidadActual);
            }
            // Si quieres podrías alertar que no puede bajar de 1
        });
    });

    // Botones eliminar
    document.querySelectorAll(".btn-eliminar").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.getAttribute("data-id");
            eliminarProducto(id);
        });
    });
});

function actualizarCantidad(id, cantidad) {
    fetch("controlador/vercarrito.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            accion: "actualizar",
            id: id,
            cantidad: cantidad
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const fila = document.querySelector(`tr[data-id='${id}']`);
            if (!fila) return;

            // Actualizar cantidad visual
            fila.querySelector(".cantidad").textContent = data.cantidad;

            // Actualizar precio unitario
            fila.querySelector(".precio-unitario").textContent = `$${data.precio}`;

            // Actualizar subtotal
            fila.querySelector(".subtotal").textContent = `$${data.subtotal}`;

            // Actualizar total general
            document.querySelector(".total-general").textContent = `${data.total}`;
           
        } else {
            alert(data.error || "Ocurrió un error al actualizar la cantidad.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
      
    });
}

function eliminarProducto(id) {
    fetch("controlador/vercarrito.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ accion: "eliminar", id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
          const fila = document.querySelector(`tr[data-id='${id}']`);
          if(fila) fila.remove();

          if (data.total !== undefined) {
            document.getElementById("total-carrito").textContent = `${data.total}`;
           
        }   
        } 
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error al eliminar producto.");
    });
}


