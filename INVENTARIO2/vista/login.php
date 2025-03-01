<?php require_once("comunes/encabezado.php"); ?> <!-- Incluye el encabezado común -->

<body>
    <div class="login-wrap cover"> <!-- Contenedor principal para el formulario de inicio de sesión -->
        <div class="container-login">
            <p class="text-center" id="lh"> <!-- Icono de usuario centrado -->
                <i class="zmdi zmdi-account-circle"></i>
            </p>
            <p class="text-center text-condensedLight">Iniciar Sesión</p> <!-- Título del formulario -->
            <form id="loginForm"> <!-- Formulario para el inicio de sesión -->
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <!-- Campo para nombre de usuario -->
                    <input class="mdl-textfield__input" type="text" id="nombre_usuario" name="nombre_usuario" required>
                    <label class="mdl-textfield__label" for="nombre_usuario">Nombre de Usuario</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <!-- Campo para contraseña -->
                    <input class="mdl-textfield__input" type="password" id="contraseña" name="contraseña" required>
                    <label class="mdl-textfield__label" for="contraseña">Contraseña</label>
                </div>
                <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect" id="lb"> <!-- Botón para enviar el formulario -->
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/login.js"></script> <!-- Script para la funcionalidad del inicio de sesión -->
</body>
</html>