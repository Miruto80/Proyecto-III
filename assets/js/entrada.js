  document.addEventListener('DOMContentLoaded', function() {
    // Función para calcular el precio total
    function calcularPrecioTotal(row) {
      const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
      const precioUnitario = parseFloat(row.querySelector('.precio-input').value) || 0;
      const precioTotal = cantidad * precioUnitario;
      row.querySelector('.precio-total').value = precioTotal.toFixed(2);
    }
    
    // Agregar producto en el formulario de registro
    document.getElementById('agregar-producto').addEventListener('click', function() {
      const productosContainer = document.getElementById('productos-container');
      const filaProducto = document.querySelector('.producto-fila').cloneNode(true);
      
      // Limpiar los campos del clon
      filaProducto.querySelector('.producto-select').selectedIndex = 0;
      filaProducto.querySelector('.cantidad-input').value = 1;
      filaProducto.querySelector('.precio-input').value = 0.00;
      filaProducto.querySelector('.precio-total').value = 0.00;
      
      // Añadir eventos a los inputs de cantidad y precio para calcular el total
      filaProducto.querySelector('.cantidad-input').addEventListener('input', function() {
        calcularPrecioTotal(filaProducto);
      });
      
      filaProducto.querySelector('.precio-input').addEventListener('input', function() {
        calcularPrecioTotal(filaProducto);
      });
      
      // Añadir evento al botón de remover
      filaProducto.querySelector('.remover-producto').addEventListener('click', function() {
        if (document.querySelectorAll('.producto-fila').length > 1) {
          this.closest('.producto-fila').remove();
        } else {
          alert('Debe tener al menos un producto en la entrada');
        }
      });
      
      productosContainer.appendChild(filaProducto);
    });
    
    // Agregar producto en los formularios de edición
    document.querySelectorAll('.agregar-producto-edit').forEach(function(boton) {
      boton.addEventListener('click', function() {
        const containerId = this.getAttribute('data-container');
        const productosContainer = document.getElementById(containerId);
        const filaProducto = productosContainer.querySelector('.producto-fila').cloneNode(true);
        
        // Limpiar los campos del clon
        filaProducto.querySelector('.producto-select').selectedIndex = 0;
        filaProducto.querySelector('.cantidad-input').value = 1;
        filaProducto.querySelector('.precio-input').value = 0.00;
        filaProducto.querySelector('.precio-total').value = 0.00;
        
        // Añadir eventos a los inputs de cantidad y precio para calcular el total
        filaProducto.querySelector('.cantidad-input').addEventListener('input', function() {
          calcularPrecioTotal(filaProducto);
        });
        
        filaProducto.querySelector('.precio-input').addEventListener('input', function() {
          calcularPrecioTotal(filaProducto);
        });
        
        // Añadir evento al botón de remover
        filaProducto.querySelector('.remover-producto').addEventListener('click', function() {
          if (productosContainer.querySelectorAll('.producto-fila').length > 1) {
            this.closest('.producto-fila').remove();
          } else {
            alert('Debe tener al menos un producto en la entrada');
          }
        });
        
        productosContainer.appendChild(filaProducto);
      });
    });
    
    // Añadir eventos a los inputs existentes
    document.querySelectorAll('.producto-fila').forEach(function(fila) {
      fila.querySelector('.cantidad-input').addEventListener('input', function() {
        calcularPrecioTotal(fila);
      });
      
      fila.querySelector('.precio-input').addEventListener('input', function() {
        calcularPrecioTotal(fila);
      });
      
      // Calcular el precio total inicial
      calcularPrecioTotal(fila);
    });
    
    // Añadir eventos a los botones de remover existentes
    document.querySelectorAll('.remover-producto').forEach(function(boton) {
      boton.addEventListener('click', function() {
        const container = this.closest('#productos-container') || this.closest('[id^="productos-container-edit"]');
        if (container.querySelectorAll('.producto-fila').length > 1) {
          this.closest('.producto-fila').remove();
        } else {
          alert('Debe tener al menos un producto en la entrada');
        }
      });
    });
  });