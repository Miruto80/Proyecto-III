function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

document.getElementById("registrar").addEventListener("click", function () {
    actualizarTasadeldia();
});

function actualizarTasadeldia() {
let tasadeldia = document.getElementById("valor").value;
localStorage.setItem("tasadeldia",tasadeldia);
muestraMensaje("success", 1000, "Se ha registrado con éxito");
setTimeout(() => location.reload(), 1000);
console.log(tasadeldia);

}