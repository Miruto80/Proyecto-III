const passwordInput = document.getElementById('password');
const showPasswordButton = document.getElementById('show-password');

showPasswordButton.addEventListener('click', () => {
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    showPasswordButton.classList.remove('fa-eye');
    showPasswordButton.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    showPasswordButton.classList.remove('fa-eye-slash');
    showPasswordButton.classList.add('fa-eye'); 
  }
});



document.getElementById('formRegistrarCliente').addEventListener('submit', function(e) {
    const cedula = document.querySelector('[name="cedula"]').value.trim();
    const nombre = document.querySelector('[name="nombre"]').value.trim();
    const apellido = document.querySelector('[name="apellido"]').value.trim();
    const correo = document.querySelector('[name="correo"]').value.trim();
    const telefono = document.querySelector('[name="telefono"]').value.trim();
    const clave = document.querySelector('[name="clave"]').value.trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const soloLetras = /^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/;
    const soloNumeros = /^\d+$/;

    let errores = [];

    if (!cedula.match(soloNumeros)) {
        errores.push("La cédula debe contener solo números.");
    }
    if (!nombre.match(soloLetras)) {
        errores.push("El nombre debe contener solo letras.");
    }
    if (!apellido.match(soloLetras)) {
        errores.push("El apellido debe contener solo letras.");
    }
    if (!emailRegex.test(correo)) {
        errores.push("El correo electrónico no es válido.");
    }
    if (!telefono.match(soloNumeros)) {
        errores.push("El teléfono debe contener solo números.");
    }
    if (clave.length < 6) {
        errores.push("La contraseña debe tener al menos 6 caracteres.");
    }

    if (errores.length > 0) {
        e.preventDefault();
        Swal.fire({
            title: 'Errores en el formulario',
            html: errores.join("<br>"),
            icon: 'warning',
            confirmButtonText: 'Corregir',
            confirmButtonColor: '#fa48f2'
        });
    }
});

