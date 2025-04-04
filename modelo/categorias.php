<?php
require_once('modelo/datos.php');

class categorias extends datos {
    private $nombre; // Nombre de la categoría
    private $descripcion; // Descripción de la categoría
    private $imagen; // Ruta de la imagen de la categoría

    // Método para establecer el nombre
    function set_nombre($valor) {
        $this->nombre = $valor;
    }

    // Método para establecer la descripción
    function set_descripcion($valor) {
        $this->descripcion = $valor;
    }

    // Método para establecer la imagen
    function set_imagen($valor) {
        $this->imagen = $valor;
    }

    // Método para obtener el nombre
    function get_nombre() {
        return $this->nombre;
    }

    // Método para obtener la descripción
    function get_descripcion() {
        return $this->descripcion;
    }

    // Método para obtener la imagen
    function get_imagen() {
        return $this->imagen;
    }

    // Método para incluir una nueva categoría
    function incluir() {
        $r = array();
        if (!$this->existe($this->nombre)) { // Verifica si la categoría ya existe
            $co = $this->conecta(); // Conecta a la base de datos
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                // Inserta la nueva categoría en la base de datos
                $co->query("Insert into categorias(nombre, descripcion, imagen)
                    Values(
                    '$this->nombre',
                    '$this->descripcion',
                    '$this->imagen')");
                $r['resultado'] = 'incluir';
                $r['mensaje'] = '¡Registro guardado con exito!';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage(); // Captura errores
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe el nombre de documento'; // Mensaje si ya existe
        }
        return $r; // Retorna el resultado
    }

    // Método para modificar una categoría existente
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->nombre)) { // Verifica si la categoría existe
            try {
                // Obtiene la imagen actual de la categoría
                $resultado = $co->query("SELECT imagen FROM categorias WHERE nombre = '$this->nombre'");
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                $imagen_actual = $fila['imagen'];
                // Actualiza la categoría en la base de datos
                $co->query("UPDATE categorias SET 
                    descripcion = '$this->descripcion',
                    imagen = '$this->imagen'
                    WHERE
                    nombre = '$this->nombre'");
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Registro actualizado con exito!';
                // Elimina la imagen anterior si no es la imagen por defecto
                if ($imagen_actual && $imagen_actual != 'otros/img/categorias/default.png') {
                    if (file_exists($imagen_actual)) {
                        unlink($imagen_actual); // Elimina el archivo de imagen
                    }
                }
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage(); // Captura errores
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'nombre de documento no registrado'; // Mensaje si no existe
        }
        return $r; // Retorna el resultado
    }

    // Método para eliminar una categoría
    function eliminar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->nombre)) { // Verifica si la categoría existe
            try {
                // Obtiene la imagen de la categoría a eliminar
                $resultado = $co->query("SELECT imagen FROM categorias WHERE nombre = '$this->nombre'");
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                $imagen = $fila['imagen'];
                // Elimina la categoría de la base de datos
                $co->query("DELETE FROM categorias WHERE nombre = '$this->nombre'");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = '¡Registro eliminado con exito!';
                // Elimina la imagen si no es la imagen por defecto
                if ($imagen && $imagen != 'otros/img/categorias/default.png') {
                    if (file_exists($imagen)) {
                        unlink($imagen); // Elimina el archivo de imagen
                    }
                }
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar esta categoría porque tiene productos asociados';
                } else {
                    $r['mensaje'] = $e->getMessage();
                }
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'No existe el nombre de documento'; // Mensaje si no existe
        }
        return $r; // Retorna el resultado
    }

    // Método para consultar todas las categorías
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("Select * from categorias ORDER BY id DESC"); // Consulta todas las categorías
            if ($resultado) {
                $respuesta = '';
                $n = 1; // Contador para enumerar las categorías
                foreach ($resultado as $r) {
                    // Construye la tabla de resultados
                    $respuesta = $respuesta . "<tr class='text-center'>";
                    $respuesta = $respuesta . "<td class='align-middle'>$n</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['nombre'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['descripcion'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'><a href='" .$r['imagen'] . "' target='_blank'><img src='" . $r['imagen'] . "' alt='Imagen de la categoría' class='img'/></a></td>";           
                    $respuesta = $respuesta . "<td class='align-middle'>";
                    $respuesta = $respuesta .
                    "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar categoría'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta = $respuesta . "<button type='button'
                    class='btn-sm btn-danger w-50 small-width mt-1' 
                    onclick='pone(this,1)'
                    title='Eliminar categoría'
                    ><i class='bi bi-trash'></i></button><br/>";
                    $respuesta = $respuesta . "</td>";
                    $respuesta = $respuesta . "</tr>";
                    $n++; // Incrementa el contador
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta; // Retorna la tabla de categorías
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = ''; // Sin resultados
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage(); // Captura errores
        }
        return $r; // Retorna el resultado
    }

    // Método privado para verificar si una categoría existe
    private function existe($nombre) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("Select * from categorias where nombre='$nombre'"); // Consulta por nombre
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            if ($fila) {
                return true; // La categoría existe
            } else {
                return false; // La categoría no existe
            }
        } catch (Exception $e) {
            return false; // Captura errores
        }
    }
}
?>