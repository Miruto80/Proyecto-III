<?php 
require_once("comunes/encabezado.php"); //Incluye el encabezado común 
require_once('comunes/menu.php'); //Incluye el menú común 
?> 

<div class="container">
    <h1><i class="zmdi zmdi-account-circle" style="color: black;"></i> Editar Perfil</h1> <!-- Título de la página -->
    <p style="text-align: justify;">"En la sección de Perfil podrás actualizar tu información personal y credenciales de acceso. Mantén tus datos de contacto actualizados y cambia tu contraseña periódicamente para mayor seguridad. También puedes personalizar tu perfil con una imagen que te identifique en el sistema."</p>

    <div class="modal-dialog modal-lg" role="document"> <!-- Diálogo modal para editar perfil -->
        <div class="modal-content">
            <div class="container" id="mtm">  
                <form method="post" id="f" autocomplete="off" enctype="multipart/form-data"> <!-- Formulario para editar perfil -->
                    <input autocomplete="off" type="text" class="form-control" name="accion" id="accion"> <!-- Campo oculto para la acción -->
                    <div class="container"> 
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="cedula">Cedula</label>
                                <input class="form-control" type="text" id="cedula" title="No se puede modificar la cedula..."/> <!-- Campo de cédula no editable -->
                                <span id="scedula"></span> <!-- Mensaje de error para cédula -->
                            </div>
                            <div class="col-md-4">
                                <label for="nombre">Nombre</label>
                                <input class="form-control" type="text" id="nombre"  placeholder="Nombre obligatorio"/> <!-- Campo de nombre -->
                                <span id="snombre"></span> <!-- Mensaje de error para nombre -->
                            </div>
                            <div class="col-md-4">
                                <label for="apellido">Apellido</label>
                                <input class="form-control" type="text" id="apellido" placeholder="Apellido obligatorio" /> <!-- Campo de apellido -->
                                <span id="sapellido"></span> <!-- Mensaje de error para apellido -->
                            </div>      
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="correo">Correo</label>
                                <input class="form-control" type="text" id="correo" placeholder="Ejemplo: Correo@email.com" /> <!-- Campo de correo -->
                                <span id="scorreo"></span> <!-- Mensaje de error para correo -->
                            </div>
                            <div class="col-md-6">
                                <label for="telefono">Teléfono</label>
                                <input class="form-control" type="text" id="telefono" placeholder="Ejemplo: 04123456789"/> <!-- Campo de teléfono -->
                                <span id="stelefono"></span> <!-- Mensaje de error para teléfono -->
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre_usuario">Nombre de usuario</label>
                                <input class="form-control" type="text" id="nombre_usuario" placeholder="Ejemplo: Usuario24" /> <!-- Campo de nombre de usuario -->
                                <span id="snombre_usuario"></span> <!-- Mensaje de error para nombre de usuario -->
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_usuario">Tipo de usuario</label>
                                <select class="form-control" id="tipo_usuario" title="No se puede modificar el tipo de usuario..."> <!-- Selección de tipo de usuario -->
                                    <option value="usuario">Usuario</option>
                                    <option value="administrador">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="contraseña">Contraseña</label>
                                <input class="form-control" type="text" id="contraseña" placeholder="Solo si desea actualizar la contraseña..."/> <!-- Campo de contraseña -->
                                <span id="scontraseña"></span> <!-- Mensaje de error para contraseña -->
                            </div>
                            <div class="col-md-6">
                                <label for="repetir_contraseña">Repetir Contraseña</label>
                                <input class="form-control" type="password" id="repetir_contraseña"/> <!-- Campo para repetir contraseña -->
                                <span id="srepetir_contraseña"></span> <!-- Mensaje de error para repetir contraseña -->
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6" title="Solo si desea actualizar la imagen...">
                                <label for="imagen">Imagen del Usuario</label>
                                <input class="col-md-11 inpImg" type="file" id="imagen" name="imagen" accept=".png,.jpg,.jpeg" /> <!-- Campo para subir imagen -->                  
                            </div>
                            <div class="col-md-6">
                                <span id="simagen"></span> <!-- Mensaje de error para imagen -->
                                <img id="imagen_actual" src="" alt="Imagen del usuario" class="img"/><!-- Imagen actual del usuario -->
                            </div>
                        </div>
                        <div class="modal-footer bg-light justify-content-center">
                            <button type="button" class="btn btn-success bi bi-check-square" id="proceso">MODIFICAR</button> <!-- Botón para modificar perfil -->
                        </div>
                    </div>  
                </form>
            </div> 
        </div>
    </div>
</div> 
</section>
</section>
<script type="text/javascript" src="js/perfil.js"></script> <!-- Script para manejar la lógica del perfil -->
</body>
</html>