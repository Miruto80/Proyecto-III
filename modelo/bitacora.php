<?php

require_once 'conexion.php';

class Bitacora extends Conexion {

    private $conex;
    private $id_bitacora;
    private $accion;
    private $fecha_hora;
    private $descripcion;
    private $id_persona;

    // Constantes para tipos de acciones
    const CREAR = 'CREAR';
    const MODIFICAR = 'MODIFICAR';
    const ELIMINAR = 'ELIMINAR';
    const ACCESO_MODULO = 'ACCESO A MÓDULO';
    const CAMBIO_ESTADO = 'CAMBIO_ESTADO';

    // Mapeo de acciones por módulo
    private $accionesModulos = [
        'usuario' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR
        ],
        'producto' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR
        ],
        'categoria' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR
        ],
        'cliente' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR
        ]
    ];

    function __construct(){ 
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
        $this->detectarAccion();
    } 

    private function detectarAccion() {
        if (!isset($_SESSION['id'])) return;

        // Obtener el módulo actual de la URL
        $modulo = isset($_GET['pagina']) ? $_GET['pagina'] : '';
        if (empty($modulo)) return;

        // Detectar la acción del POST
        foreach ($_POST as $key => $value) {
            if (isset($this->accionesModulos[$modulo][$key])) {
                $accion = $this->accionesModulos[$modulo][$key];
                $this->registrarAccionAutomatica($accion, $modulo, $_POST);
                break;
            }
        }

        // Registrar acceso al módulo
        if (empty($_POST) && $modulo != 'bitacora') {
            $this->registrarOperacion(
                self::ACCESO_MODULO,
                ucfirst($modulo)
            );
        }
    }

    private function registrarAccionAutomatica($accion, $modulo, $datos) {
        $detalle = $this->generarDetalle($modulo, $accion, $datos);
        $this->registrarOperacion($accion, ucfirst($modulo), $detalle);
    }

    private function generarDetalle($modulo, $accion, $datos) {
        $detalle = '';
        switch ($modulo) {
            case 'usuario':
                if (isset($datos['cedula'])) {
                    $detalle = 'Usuario con Cédula: ' . $datos['cedula'];
                    if (isset($datos['nombre']) && isset($datos['apellido'])) {
                        $detalle .= ' - Nombre completo: ' . $datos['nombre'] . ' ' . $datos['apellido'];
                    }
                    if (isset($datos['correo'])) {
                        $detalle .= ' - Correo: ' . $datos['correo'];
                    }
                } else if (isset($datos['eliminar'])) {
                    $detalle = 'Usuario con ID: ' . $datos['eliminar'];
                }
                break;
            case 'producto':
                if (isset($datos['codigo'])) {
                    $detalle = 'Producto con Código: ' . $datos['codigo'];
                    if (isset($datos['nombre'])) {
                        $detalle .= ' - Nombre: ' . $datos['nombre'];
                    }
                    if (isset($datos['marca'])) {
                        $detalle .= ' - Marca: ' . $datos['marca'];
                    }
                    if (isset($datos['precio'])) {
                        $detalle .= ' - Precio: ' . $datos['precio'];
                    }
                }
                break;
            case 'categoria':
                if (isset($datos['nombre'])) {
                    $detalle = 'Categoría: ' . $datos['nombre'];
                    if (isset($datos['descripcion'])) {
                        $detalle .= ' - Descripción: ' . $datos['descripcion'];
                    }
                }
                break;
            case 'cliente':
                if (isset($datos['cedula'])) {
                    $detalle = 'Cliente con Cédula: ' . $datos['cedula'];
                    if (isset($datos['nombre']) && isset($datos['apellido'])) {
                        $detalle .= ' - Nombre completo: ' . $datos['nombre'] . ' ' . $datos['apellido'];
                    }
                    if (isset($datos['correo'])) {
                        $detalle .= ' - Correo: ' . $datos['correo'];
                    }
                    if (isset($datos['telefono'])) {
                        $detalle .= ' - Teléfono: ' . $datos['telefono'];
                    }
                }
                break;
        }
        return $detalle;
    }

    public function registrarOperacion($accion, $modulo, $detalle = '') {
        if (!isset($_SESSION['id'])) {
            return false;
        }

        try {
            $fecha = date('Y-m-d H:i:s');
            
            // Formatear la descripción
            switch ($accion) {
                case self::CREAR:
                    $descripcion = "Se ha registrado exitosamente un nuevo registro en " . $modulo;
                    if ($detalle) {
                        $descripcion .= " con los siguientes datos: " . $detalle;
                    }
                    break;
                case self::MODIFICAR:
                    $descripcion = "Se ha modificado exitosamente un registro en " . $modulo;
                    if ($detalle) {
                        $descripcion .= " con los siguientes datos actualizados: " . $detalle;
                    }
                    break;
                case self::ELIMINAR:
                    $descripcion = "Se ha eliminado exitosamente un registro en " . $modulo;
                    if ($detalle) {
                        $descripcion .= " con los siguientes datos: " . $detalle;
                    }
                    break;
                case self::ACCESO_MODULO:
                    $descripcion = "El usuario ha accedido exitosamente al módulo " . $modulo;
                    break;
                case self::CAMBIO_ESTADO:
                    $descripcion = "Se ha cambiado exitosamente el estado en " . $modulo;
                    if ($detalle) {
                        $descripcion .= " para: " . $detalle;
                    }
                    break;
                default:
                    $descripcion = $detalle;
            }

            // Agregar el módulo al final de la descripción
            $descripcion .= " [" . $modulo . "]";

            $registro = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                        VALUES (:accion, :fecha_hora, :descripcion, :id_persona)";
            
            $stmt = $this->conex->prepare($registro);
            $stmt->bindParam(':accion', $accion);
            $stmt->bindParam(':fecha_hora', $fecha);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id_persona', $_SESSION['id']);
            
            $result = $stmt->execute();
            
            if ($result) {
                return array('respuesta' => 1, 'mensaje' => 'Operación registrada exitosamente');
            } else {
                return array('respuesta' => 0, 'mensaje' => 'Error al registrar la operación');
            }
        } catch (PDOException $e) {
            return array('respuesta' => 0, 'mensaje' => 'Error: ' . $e->getMessage());
        }
    }

    public function consultar(){
        $registro = "SELECT b.*, p.nombre, p.apellido, ru.nombre AS nombre_usuario
                     FROM bitacora b
                     INNER JOIN personas p ON b.id_persona = p.id_persona
                     INNER JOIN rol_usuario ru ON p.id_tipo = ru.id_tipo
                     ORDER BY b.fecha_hora DESC";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul){
            return $datos;
        } else{
            return $res = 0;
        }
    }

    public function obtenerRegistro($id_bitacora) {
        try {
            $query = "SELECT b.*, p.nombre, p.apellido, ru.nombre AS nombre_usuario,
                            DATE_FORMAT(b.fecha_hora, '%d/%m/%Y %H:%i:%s') as fecha_hora
                     FROM bitacora b
                     INNER JOIN personas p ON b.id_persona = p.id_persona
                     INNER JOIN rol_usuario ru ON p.id_tipo = ru.id_tipo
                     WHERE b.id_bitacora = :id_bitacora";
            
            $stmt = $this->conex->prepare($query);
            $stmt->bindParam(':id_bitacora', $id_bitacora);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => 'Error al obtener el registro: ' . $e->getMessage());
        }
    }

    public function eliminar(){
        try {
            $registro = "DELETE FROM bitacora WHERE id_bitacora = :id_bitacora";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_bitacora', $this->id_bitacora);
            $result = $strExec->execute();
            if ($result){
                return array('respuesta'=>1,'mensaje'=>'Registro eliminado correctamente');
            } else{
                return array('respuesta'=>0,'mensaje'=>'Error al eliminar el registro');
            }
        } catch (PDOException $e) {
            return array('respuesta'=>0,'mensaje'=>'Error: ' . $e->getMessage());
        }
    }

    // Getters y Setters
    public function get_Idbitacora() {
        return $this->id_bitacora;
    }

    public function set_Idbitacora($id_bitacora) {
        $this->id_bitacora = $id_bitacora;
    }

    public function get_Accion() {
        return $this->accion;
    }

    public function set_Accion($accion) {
        $this->accion = $accion;
    }

    public function get_Descripcion() {
        return $this->descripcion;
    }

    public function set_Descripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function get_IdPersona() {
        return $this->id_persona;
    }

    public function set_IdPersona($id_persona) {
        $this->id_persona = $id_persona;
    }
}