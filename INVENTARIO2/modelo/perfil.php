<?php
require_once('modelo/datos.php');
class perfil extends datos{
    // Propiedades privadas del perfil
    private $cedula;
    private $nombre;
    private $apellido;
    private $correo;
    private $telefono;
    private $nombre_usuario;
    private $contraseña;
    private $imagen;

    // Métodos para establecer los valores de las propiedades
    function set_cedula($valor){
        $this->cedula = $valor;
    }
    function set_nombre($valor){
        $this->nombre = $valor;
    }
    function set_apellido($valor){
        $this->apellido = $valor;
    }
    function set_correo($valor){
        $this->correo = $valor;
    }
    function set_telefono($valor){
        $this->telefono = $valor;
    }
    function set_nombre_usuario($valor){
        $this->nombre_usuario = $valor;
    }
    function set_contraseña($valor){
        $this->contraseña = $valor;
    }
    function set_imagen($valor){
        $this->imagen = $valor;
    }

    // Método para cargar el perfil del usuario
    function cargarPerfil($id){
        $co = $this->conecta(); // Conectar a la base de datos
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            // Consultar los datos del usuario por ID
            $resultado = $co->query("SELECT * FROM usuarios WHERE id = '$id'");
            $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
            unset($usuario['contraseña']); // Eliminar la contraseña del resultado
            return ['resultado' => 'ok', 'datos' => $usuario]; // Retornar datos del usuario
        } catch(Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()]; // Manejo de errores
        }
    }

    // Método para modificar el perfil del usuario
    function modificarPerfil($id){
        $co = $this->conecta(); // Conectar a la base de datos
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            // Obtener la imagen actual del usuario
            $resultado = $co->query("SELECT imagen FROM usuarios WHERE id = '$id'");
            $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
            $imagen_actual = $usuario['imagen'];

            // Preparar la consulta de actualización
            $query = "UPDATE usuarios SET 
            nombre = '$this->nombre',
            apellido = '$this->apellido',
            correo = '$this->correo',
            telefono = '$this->telefono',
            nombre_usuario = '$this->nombre_usuario'";

            // Si se proporciona una nueva contraseña, se encripta
            if(!empty($this->contraseña)) {
                $contraseña_hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
                $query .= ", contraseña = '$contraseña_hash'";
            }
            
            // Si se proporciona una nueva imagen
            if(!empty($this->imagen)) {
                $query .= ", imagen = '$this->imagen'";
                // Eliminar la imagen anterior si existe y no es la imagen por defecto
                if ($imagen_actual && $imagen_actual != 'otros/img/usuarios/default.jpg' && file_exists($imagen_actual)) {
                    unlink($imagen_actual); // Eliminar archivo de imagen
                }
                $nueva_imagen = $this->imagen; // Asignar nueva imagen
            } else {
                $nueva_imagen = $imagen_actual; // Mantener imagen actual
            }
            
            $query .= " WHERE id = '$id'"; // Condición para la actualización
            
            $co->query($query); // Ejecutar la consulta
            
            return ['resultado' => 'ok', 'mensaje' => '¡Perfil actualizado con éxito!', 'nueva_imagen' => $nueva_imagen]; // Retornar éxito
        } catch(Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()]; // Manejo de errores
        }
    }
}
?>
