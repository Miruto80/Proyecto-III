function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

document.getElementById('btn-guardar-pedido').addEventListener('click', async () => {
    try {
        const form = document.getElementById('formPedido');
        const formData = new FormData(form);

        // 1. Registrar el pedido
        const resPedido = await fetch('controlador/verpedidoweb.php', {
            method: 'POST',
            body: formData
        });

        const text = await resPedido.text();
        let idPedidoGenerado;

        try {
            const dataPedido = JSON.parse(text);
            if (!dataPedido.success) throw new Error(dataPedido.message);

            idPedidoGenerado = dataPedido.id_pedido;
            console.log("ID pedido generado:", idPedidoGenerado);
        } catch (e) {
            console.error('Error al parsear JSON del pedido:', text);
            return;
        }

        // 2. Registrar detalles + preliminar
        const items = document.querySelectorAll('.row.item');

        for (const item of items) {
            const detalleData = new FormData();
            detalleData.append('id_pedido', idPedidoGenerado);
            detalleData.append('id_producto', item.dataset.idProducto);
            detalleData.append('cantidad', item.dataset.cantidad);
            detalleData.append('precio_unitario', item.dataset.precioUnitario);
            detalleData.append('subtotal', item.dataset.subtotal);
        
            const resDetalle = await fetch('controlador/verpedidoweb.php', {
                method: 'POST',
                body: detalleData
            });
        
            const textDetalle = await resDetalle.text();
            let dataDetalle;
        
            try {
                dataDetalle = JSON.parse(textDetalle);
                if (!dataDetalle.success) throw new Error("Error al registrar detalle");
        
                const idDetalleGenerado = dataDetalle.id_detalle;
        
                // Llamar a registrar_preliminar con ese ID
                const respuesta = await fetch('controlador/verpedidoweb.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        id_detalle: idDetalleGenerado,
                        condicion: 'pedido'
                    })
                });
        
                const resultado = await respuesta.json();
                if (!resultado.success) {
                    console.warn('Error al registrar preliminar:', resultado.message);
                } else {
                    console.log('Preliminar insertado con ID:', resultado.id_preliminar);
                }
        
            } catch (e) {
             
            }
        }

        // 4. Mostrar mensaje de éxito final
        muestraMensaje("success", 2000, "Su Pedido se ha registrado con éxito");

        setTimeout(() => {
            window.location.href = '?pagina=catalogo_pedido';
        }, 1000);

    } catch (err) {
        console.error('Error general en el proceso:', err.message);
    }
});
