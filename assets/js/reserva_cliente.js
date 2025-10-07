$(document).ready(function () {

  function mostrarError(campo, mensaje) {
    campo.addClass("is-invalid");
    let span = campo.next(".invalid-feedback");
    if (!span.length) {
      span = $('<span class="invalid-feedback" style="color:red;"></span>');
      campo.after(span);
    }
    span.text(mensaje);
  }

  function limpiarError(campo) {
    campo.removeClass("is-invalid");
    campo.next(".invalid-feedback").text("");
  }

  function validarReferenciaBancaria(input) {
    const valor = input.val().trim();
    const valido = /^[0-9]{4,6}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, "Debe tener entre 4 y 6 dígitos.");
    return valido;
  }

  function validarTelefonoEmisor(input) {
    const valor = input.val().trim();
    const valido = /^(0414|0424|0412|0416|0426)[0-9]{7}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, "Formato válido: 04141234567");
    return valido;
  }

  function validarSelect(input, mensaje) {
    const valor = input.val();
    const valido = valor !== "" && !valor.includes("Seleccione");
    valido ? limpiarError(input) : mostrarError(input, mensaje);
    return valido;
  }

  function validarFormulario() {
    const ref = $("#referencia_bancaria");
    const tel = $("#telefono_emisor");
    const pago = $("#metodopago");
    const banco = $("#banco");
    const bancoDestino = $("#banco_destino");
    const terminos = $("#che");

    const validaciones = [
      validarReferenciaBancaria(ref),
      validarTelefonoEmisor(tel),
      validarSelect(pago, "Seleccione un método de pago válido."),
      validarSelect(banco, "Seleccione un banco de origen."),
      validarSelect(bancoDestino, "Seleccione un banco de destino.")
    ];

    if (!terminos.is(":checked")) {
      Swal.fire("Error", "Debe aceptar los términos y condiciones", "warning");
      return false;
    }

    return validaciones.every(v => v);
  }

  // Solo números y límites
  $("#referencia_bancaria").on("input", function () {
    let v = this.value.replace(/\D/g, "").slice(0, 6);
    $(this).val(v);
  });

  $("#telefono_emisor").on("input", function () {
    let v = this.value.replace(/\D/g, "").slice(0, 11);
    $(this).val(v);
  });

  // Validación en tiempo real
  $("#referencia_bancaria").on("input", () => validarReferenciaBancaria($("#referencia_bancaria")));
  $("#telefono_emisor").on("input", () => validarTelefonoEmisor($("#telefono_emisor")));
  $("select").on("change", () => validarFormulario());

  // Envío del formulario
  $("#btn-guardar-reserva").on("click", function (e) {
    e.preventDefault();
    if (!validarFormulario()) return;

    Swal.fire({
      title: "¿Confirmar reserva?",
      text: "Se registrará su orden y pago.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, confirmar",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (!result.isConfirmed) return;

      const fd = new FormData($("#formReserva")[0]);
      $.ajax({
        url: "controlador/reserva_cliente.php",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        dataType: "json",
        success(res) {
          if (res.success) {
            Swal.fire({
              title: "¡Reserva realizada!",
              text: "Recuerde que debe retirarlo en el local.",
              icon: "success",
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              setTimeout(() => window.location.href = "?pagina=catalogo", 2000);
            });
          } else {
            Swal.fire("Error", res.message || "Error al registrar la reserva.", "error");
          }
        },
        error(xhr) {
          console.error("Error de servidor:", xhr.responseText);
          Swal.fire("Error", "Comunicación fallida con el servidor.", "error");
        }
      });
    });
  });
});
