<?php

require_once 'conexion.php';

class Bitacora extends Conexion {

    private $conex1;
    private $conex2;
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
        'productos' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR,
            'cambiar_estado' => self::CAMBIO_ESTADO
        ],
        'categoria' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR,
            'cambiar_estado' => self::CAMBIO_ESTADO
        ],
        'cliente' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR,
            'cambiar_estado' => self::CAMBIO_ESTADO
        ],
        'proveedor' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR,
            'cambiar_estado' => self::CAMBIO_ESTADO
        ],
        'metodo_pago' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR,
            'cambiar_estado' => self::CAMBIO_ESTADO
        ],
        'metodo_entrega' => [
            'registrar' => self::CREAR,
            'actualizar' => self::MODIFICAR,
            'eliminar' => self::ELIMINAR,
            'cambiar_estado' => self::CAMBIO_ESTADO
        ]
    ];

    function __construct(){ 
        parent::__construct(); // Llama al constructor de la clase padre

        // Obtener las conexiones de la clase padre
        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();
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
        
        // Mapeo de campos por módulo con etiquetas personalizadas
        $camposModulo = [
            'productos' => [
                'nombre' => 'Nombre del Producto',
                'descripcion' => 'Descripción',
                'marca' => 'Marca',
                'cantidad_mayor' => 'Cantidad al Mayor',
                'precio_mayor' => 'Precio al Mayor',
                'precio_detal' => 'Precio al Detal',
                'stock_disponible' => 'Stock Disponible',
                'stock_maximo' => 'Stock Máximo',
                'stock_minimo' => 'Stock Mínimo',
                'id_categoria' => 'Categoría'
            ],
            'categoria' => [
                'nombre' => 'Nombre de Categoría',
                'estatus' => 'Estado'
            ],
            'cliente' => [
                'cedula' => 'Cédula',
                'nombre' => 'Nombre',
                'apellido' => 'Apellido',
                'correo' => 'Correo',
                'telefono' => 'Teléfono'
            ],
            'proveedor' => [
                'numero_documento' => 'RIF/Cédula',
                'tipo_documento' => 'Tipo de Documento',
                'nombre' => 'Nombre/Razón Social',
                'correo' => 'Correo',
                'telefono' => 'Teléfono',
                'direccion' => 'Dirección'
            ],
            'metodo_pago' => [
                'nombre' => 'Nombre del Método',
                'descripcion' => 'Descripción'
            ],
            'metodo_entrega' => [
                'nombre' => 'Nombre del Método',
                'descripcion' => 'Descripción'
            ]
        ];

        // Función para formatear valores específicos
        $formatearValor = function($campo, $valor) {
            switch($campo) {
                case 'estatus':
                    return $valor == 1 ? 'Activo' : 'Inactivo';
                case 'precio_mayor':
                case 'precio_detal':
                    return number_format($valor, 2) . ' $';
                default:
                    return $valor;
            }
        };

        // Generar detalle según la acción
        switch ($accion) {
            case self::CREAR:
                if (isset($camposModulo[$modulo])) {
                    $detalles = [];
                    foreach ($camposModulo[$modulo] as $campo => $etiqueta) {
                        if (isset($datos[$campo]) && !empty($datos[$campo])) {
                            $valor = $formatearValor($campo, $datos[$campo]);
                            $detalles[] = "{$etiqueta}: {$valor}";
                        }
                    }
                    $detalle = "Se ha registrado un nuevo {$modulo} con los siguientes datos: " . implode(' | ', $detalles);
                }
                break;

            case self::MODIFICAR:
                if (isset($camposModulo[$modulo])) {
                    $detalles = [];
                    foreach ($camposModulo[$modulo] as $campo => $etiqueta) {
                        if (isset($datos[$campo]) && !empty($datos[$campo])) {
                            $valor = $formatearValor($campo, $datos[$campo]);
                            $detalles[] = "{$etiqueta}: {$valor}";
                        }
                    }
                    $detalle = "Se ha modificado el {$modulo} con los siguientes datos: " . implode(' | ', $detalles);
                }
                break;

            case self::ELIMINAR:
                $identificador = '';
                if (isset($datos['id'])) {
                    $identificador = "ID: " . $datos['id'];
                } elseif (isset($datos['cedula'])) {
                    $identificador = "Cédula: " . $datos['cedula'];
                } elseif (isset($datos['numero_documento'])) {
                    $identificador = "RIF/Cédula: " . $datos['numero_documento'];
                }
                $detalle = "Se ha eliminado el {$modulo} con " . $identificador;
                break;

            case self::CAMBIO_ESTADO:
                $estado = isset($datos['estatus']) ? ($datos['estatus'] == 1 ? 'Activo' : 'Inactivo') : 'Desconocido';
                $identificador = '';
                
                if (isset($datos['nombre'])) {
                    $identificador = "Nombre: " . $datos['nombre'];
                } elseif (isset($datos['cedula'])) {
                    $identificador = "Cédula: " . $datos['cedula'];
                } elseif (isset($datos['numero_documento'])) {
                    $identificador = "RIF/Cédula: " . $datos['numero_documento'];
                }
                
                $detalle = "Se ha cambiado el estado del {$modulo} a: {$estado} | {$identificador}";
                break;

            case self::ACCESO_MODULO:
                $detalle = "El usuario ha accedido al módulo de " . ucfirst($modulo);
                break;
        }

        return $detalle;
    }

    public function registrarOperacion($accion, $modulo, $datos = []) {
        if (!isset($_SESSION['id'])) {
            return false;
        }

        try {
            $fecha = date('Y-m-d H:i:s');
            $detalle = $this->generarDetalle($modulo, $accion, $datos);
            
            // Agregar el módulo al final de la descripción
            $detalle .= " [" . ucfirst($modulo) . "]";

            $registro = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                        VALUES (:accion, :fecha_hora, :descripcion, :id_persona)";
            
            $stmt = $this->conex2->prepare($registro);
            $stmt->bindParam(':accion', $accion);
            $stmt->bindParam(':fecha_hora', $fecha);
            $stmt->bindParam(':descripcion', $detalle);
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
                     INNER JOIN usuario p ON b.id_persona = p.id_persona
                     INNER JOIN rol_usuario ru ON p.id_rol = ru.id_rol
                     ORDER BY b.fecha_hora DESC";
        $consulta = $this->conex2->prepare($registro);
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
                     INNER JOIN usuario p ON b.id_persona = p.id_persona
                     INNER JOIN rol_usuario ru ON p.id_rol = ru.id_rol
                     WHERE b.id_bitacora = :id_bitacora";
            
            $stmt = $this->conex2->prepare($query);
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
            $strExec = $this->conex2->prepare($registro);
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

    public function limpiarBitacoraAntigua($dias = 90) {
        try {
            $fecha_limite = date('Y-m-d H:i:s', strtotime("-{$dias} days"));
            $registro = "DELETE FROM bitacora WHERE fecha_hora < :fecha_limite";
            $strExec = $this->conex2->prepare($registro);
            $strExec->bindParam(':fecha_limite', $fecha_limite);
            $result = $strExec->execute();
            
            if ($result) {
                $filas_eliminadas = $strExec->rowCount();
                return array(
                    'success' => true, 
                    'message' => "Se eliminaron {$filas_eliminadas} registros de más de {$dias} días"
                );
            } else {
                return array('success' => false, 'message' => 'Error al limpiar la bitácora');
            }
        } catch (PDOException $e) {
            return array('success' => false, 'message' => 'Error: ' . $e->getMessage());
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