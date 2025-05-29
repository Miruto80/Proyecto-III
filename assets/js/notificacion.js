document.querySelectorAll(".btn-leer").forEach(btn => {
    btn.addEventListener("click", function () {
        let id = this.getAttribute("data-id");
        
        fetch("controlador.php", {
            method: "POST",
            body: new URLSearchParams({accion: "leer", id_notificaciones: id}),
            headers: {"Content-Type": "application/x-www-form-urlencoded"}
        })
        .then(res => res.json())
        .then(data => {
            if (data.respuesta === 1) {
                let estadoElement = document.querySelector(`#notificacion-${id} .estado`);
                estadoElement.textContent = estadoElement.textContent === "Leída" ? "No leída" : "Leída";
            } else if (data.mensaje) {
                alert(data.mensaje);
            }
        });
    });
});
