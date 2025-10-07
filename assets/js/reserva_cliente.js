document.addEventListener("DOMContentLoaded", () => {

  // ---- Funciones de error ----
  function mostrarError(campo, mensaje) {
    campo.classList.add("is-invalid");
    let span = campo.nextElementSibling;
    if (!span || !span.classList.contains("invalid-feedback")) {
      span = document.createElement("span");
      span.classList.add("invalid-feedback");
      span.style.color = "red";
      campo.insertAdjacentElement("afterend", span);
    }
    span.textContent = mensaje;
  }

  function limpiarError(campo) {
    campo.classList.remove("is-invalid");
    const span = campo.nextElementSibling;
    if (span && span.classList.contains("invalid-feedback")) {
      span.textContent = "";
    }
  }

  // ---- Validaciones individuales ----
  function validarReferenciaBancaria(input) {
    const valor = input.value.trim();
    const valido = /^[0-9]{4,6}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, "Debe tener entre 4 y 6 dígitos.");
    return valido;
  }

  function validarTelefonoEmisor(input) {
    const valor = input.value.trim();
    const valido = /^(0414|0424|0412|0416|0426)[0-9]{7}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, "Formato válido: 04141234567");
    return valido;
  }

  function validarSelect(select, mensaje) {
    const valido = select.value && !select.value.includes("Seleccione");
    valido ? limpiarError(select) : mostrarError(select, mensaje);
    return valido;
  }

  // ---- Inputs y botones ----
  const ref = document.getElementById("referencia_bancaria");
  const tel = document.getElementById("telefono_emisor");
  const pago = document.getElementById("metodopago");
  const banco = document.getElementById("banco");
  const bancoDestino = document.getElementById("banco_destino");
  const form = document.getElementById("formReserva");
  const btnGuardar = document.getElementById("btn-guardar-reserva");
  const checkTerminos = document.getElementById("che");

  // ---- Restricciones de entrada ----
  ref.addEventListener("input", (e) => {
    e.target.value = e.target.value.replace(/\D/g, "").slice(0, 6);
  });
  tel.addEventListener("input", (e) => {
    e.target.value = e.target.value.replace(/\D/g, "").slice(0, 11);
  });

  // ---- Validación en tiempo real ----
  ref.addEventListener("input", () => validarReferenciaBancaria(ref));
  tel.addEventListener("input", () => validarTelefonoEmisor(tel));
  [pago, banco, bancoDestino].forEach(sel => {
    sel.addEventListener("change", () => validarSelect(sel, "Seleccione un valor válido"));
  });

  // ---- Botón Guardar Reserva ----
  btnGuardar.addEventListener("click", (e) => {
    e.preventDefault();

    // Validaciones previas
    if (!validarSelect(pago, "Seleccione un método de pago válido.")) {
      return Swal.fire("Error", "Seleccione un método de pago válido", "warning");
    }
    if (!ref.value || !validarReferenciaBancaria(ref)) {
      return Swal.fire("Error", "Ingrese una referencia bancaria válida", "warning");
    }
    if (!tel.value || !validarTelefonoEmisor(tel)) {
      return Swal.fire("Error", "Ingrese un teléfono del emisor válido", "warning");
    }
    if (!validarSelect(banco, "Seleccione un banco de origen.")) {
      return Swal.fire("Error", "Seleccione un banco de origen", "warning");
    }
    if (!validarSelect(bancoDestino, "Seleccione un banco de destino.")) {
      return Swal.fire("Error", "Seleccione un banco de destino", "warning");
    }
    if (!checkTerminos.checked) {
      return Swal.fire("Error", "Debe aceptar los términos y condiciones", "warning");
    }

    // Confirmación
    Swal.fire({
      title: "¿Confirmar Reserva?",
      text: "Se procesará su reserva.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, reservar",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (!result.isConfirmed) return;

      // ---- Envío con XMLHttpRequest ----
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "controlador/reserva_cliente.php", true);
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

      xhr.onload = function () {
        let data;
        try {
          data = JSON.parse(xhr.responseText);
        } catch {
          Swal.fire("Error", "Respuesta del servidor no válida", "error");
          return;
        }

        if (xhr.status === 200 && data.success) {
          Swal.fire({
            title: "¡Listo!",
            text: "Su reserva fue realizada. Recuerde retirarla en el local.",
            icon: "success",
            timer: 1500,
            showConfirmButton: false,
            timerProgressBar: true
          }).then(() => {
            setTimeout(() => window.location.href = "?pagina=catalogo", 1500);
          });
        } else {
          Swal.fire("Error", data.message || "Error al registrar la reserva.", "error");
        }
      };

      xhr.onerror = function () {
        Swal.fire("Error", "Comunicación fallida", "error");
      };

      xhr.send(new FormData(form));
    });
  });
});
