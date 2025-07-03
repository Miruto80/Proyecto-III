<?php 

require_once __DIR__ . '/../modelo/conexion.php';


class metodoentrega extends Conexion {
   
 

    public function __construct() {
        parent::__construct(); 
    }


    public function procesarMetodoEntrega($jsonDatos){
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos ['operacion']?? '';
        $datosProcesar = $datos ['datos']?? [];

        try{
            switch($operacion){
                case 'incluir':
                    return $this->registrar($datosProcesar['nombre'], $datosProcesar['descripcion']);
        
                case 'modificar':
                    return $this->modificar(
                        $datosProcesar['id_entrega'],
                        $datosProcesar['nombre'],
                        $datosProcesar['descripcion']
                    );
                case 'eliminar':
                    return $this->eliminar($datosProcesar['id_entrega']);
        
                 default:
                 return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];  

            }
        }catch(Exception $e){
            return ['respuesta' => 0, 'accion' => $operacion, 'error' => $e->getMessage()];
        }
    }
   

    private function registrar($nombre,$descripcion) {
        $sql = "INSERT INTO metodo_entrega(nombre, descripcion, estatus) VALUES (:nombre, :descripcion, 1)";
        $stmt = $this->getConex1()->prepare($sql);
        $result = $stmt->execute([
            'nombre' =>$nombre,
            'descripcion'=>$descripcion
        ]);
        return $result ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }

    private function modificar($id_entrega , $nombre , $descripcion) {
        $sql = "UPDATE metodo_entrega SET nombre = :nombre, descripcion = :descripcion WHERE id_entrega = :id_entrega";
        $stmt = $this->getConex1()->prepare($sql);
        $result = $stmt->execute([
           'id_entrega'=>$id_entrega ,
           'nombre'=>$nombre,
           'descripcion'=>$descripcion 
        ]);
        return $result ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
    }


    private function eliminar($id_entrega) {
        $sql = "UPDATE metodo_entrega SET estatus = 0 WHERE id_entrega = :id_entrega";
        $stmt = $this->getConex1()->prepare($sql);
        $result = $stmt->execute(['id_entrega'=>$id_entrega]);
        return $result ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
    }

    public function consultar() {
        $sql = "SELECT * FROM metodo_entrega WHERE estatus = 1";
        $stmt = $this->getConex1()->prepare($sql);
         $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}

?>