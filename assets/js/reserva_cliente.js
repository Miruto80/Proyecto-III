document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formReserva");
  const btn = document.getElementById("btn-guardar-reserva");
  const check = document.getElementById("check_terminos");
  const imagen = document.getElementById("imagen");

  // Activar botón al aceptar términos
  check.addEventListener("change", () => {
    btn.disabled = !check.checked;
  });

  // ✅ Vista previa y validación de la imagen
  imagen.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const formatosPermitidos = ["image/jpeg", "image/png", "image/webp"];
    if (!formatosPermitidos.includes(file.type)) {
      Swal.fire("Error", "Solo se permiten imágenes JPG, PNG o WEBP", "error");
      imagen.value = "";
      return;
    }

    const reader = new FileReader();
    reader.onload = (ev) => {
      const preview = document.getElementById("preview");
      preview.src = ev.target.result;
      preview.classList.remove("d-none");
    };
    reader.readAsDataURL(file);
  });

  // ✅ Validaciones de campos
  function validarReferenciaBancaria(i) {
    return /^[0-9]{4,6}$/.test(i.value.trim());
  }

  function validarTelefonoEmisor(i) {
    return /^(0412|0414|0416|0424|0426)\d{7}$/.test(i.value.trim());
  }

  function validarImagen(input) {
    return input && input.files && input.files.length > 0;
  }

  // ✅ Evento de envío
  btn.addEventListener("click", async (e) => {
    e.preventDefault();

    const ref = document.getElementById("referencia_bancaria");
    const tel = document.getElementById("telefono_emisor");
    const banco = document.getElementById("banco");
    const bancoDestino = document.getElementById("banco_destino");

    if (!validarReferenciaBancaria(ref)) {
      Swal.fire("Error", "Referencia bancaria inválida (4-6 dígitos)", "warning");
      return;
    }
    if (!validarTelefonoEmisor(tel)) {
      Swal.fire("Error", "Teléfono emisor inválido (ejemplo: 04141234567)", "warning");
      return;
    }
    if (!banco.value || banco.value.includes("Seleccione")) {
      Swal.fire("Error", "Seleccione un banco de origen", "warning");
      return;
    }
    if (!bancoDestino.value || bancoDestino.value.includes("Seleccione")) {
      Swal.fire("Error", "Seleccione un banco de destino", "warning");
      return;
    }
    if (!validarImagen(imagen)) {
      Swal.fire("Error", "Debe adjuntar un comprobante de pago", "warning");
      return;
    }
    if (!check.checked) {
      Swal.fire("Error", "Debe aceptar los términos y condiciones", "warning");
      return;
    }

    const fd = new FormData(form);

    const confirmar = await Swal.fire({
      title: "¿Confirmar Reserva?",
      text: "Se procesará su solicitud de reserva.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, reservar",
      cancelButtonText: "Cancelar",
    });

    if (!confirmar.isConfirmed) return;

    try {
      const res = await fetch("controlador/reserva_cliente.php", {
        method: "POST",
        body: fd,
      });
      const data = await res.json();

      if (data.success) {
        await Swal.fire({
          title: "¡Listo!",
          text: data.message || "Reserva enviada correctamente.",
          icon: "success",
          timer: 1500,
          showConfirmButton: false,
        });
        window.location.href = data.redirect;
      } else {
        Swal.fire(
          "Error",
          data.message || "Complete correctamente el formulario.",
          "error"
        );
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error de comunicación con el servidor", "error");
    }
  });
});
