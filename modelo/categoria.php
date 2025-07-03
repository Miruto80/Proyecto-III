<?php
require_once 'conexion.php';

class Categoria extends Conexion {
  /**
   * Inserta un registro en la bitácora.
   * Recibe un JSON con keys: id_persona, accion, descripcion
   */
  public function registrarBitacora(string $jsonDatos): bool {
    $datos = json_decode($jsonDatos, true);
    $sql   = "INSERT INTO bitacora
                (accion, fecha_hora, descripcion, id_persona)
              VALUES
                (:accion, NOW(), :descripcion, :id_persona)";
    $stmt = $this->getConex2()->prepare($sql);
    return $stmt->execute([
      'accion'      => $datos['accion']      ?? '',
      'descripcion' => $datos['descripcion'] ?? '',
      'id_persona'  => $datos['id_persona']  ?? 0
    ]);
  }

  /**
   * Procesa operaciones JSON-driven: incluir, actualizar, eliminar
   */
  public function procesarCategoria(string $jsonDatos): array {
    $payload   = json_decode($jsonDatos, true);
    $operacion = $payload['operacion'] ?? '';
    $d         = $payload['datos']    ?? [];

    try {
      switch ($operacion) {
        case 'incluir':    return $this->insertar($d);
        case 'actualizar': return $this->actualizar($d);
        case 'eliminar':   return $this->eliminarLogico($d);
        default:
          return ['respuesta'=>0,'accion'=>$operacion,'mensaje'=>'Operación inválida'];
      }
    } catch (PDOException $e) {
      return ['respuesta'=>0,'accion'=>$operacion,'mensaje'=>$e->getMessage()];
    }
  }

  private function insertar(array $d): array {
    $sql  = "INSERT INTO categoria (nombre,estatus) VALUES (:nombre,1)";
    $stmt = $this->getConex1()->prepare($sql);
    $ok   = $stmt->execute(['nombre'=>$d['nombre']]);
    return [
      'respuesta'=> $ok?1:0,
      'accion'   =>'incluir',
      'mensaje'  => $ok?'Categoría creada':'Error al crear'
    ];
  }

  private function actualizar(array $d): array {
    $sql  = "UPDATE categoria SET nombre=:nombre WHERE id_categoria=:id";
    $stmt = $this->getConex1()->prepare($sql);
    $ok   = $stmt->execute([
      'id'     => $d['id_categoria'],
      'nombre' => $d['nombre']
    ]);
    return [
      'respuesta'=> $ok?1:0,
      'accion'   =>'actualizar',
      'mensaje'  => $ok?'Categoría actualizada':'Error al actualizar'
    ];
  }

  private function eliminarLogico(array $d): array {
    $sql  = "UPDATE categoria SET estatus=0 WHERE id_categoria=:id";
    $stmt = $this->getConex1()->prepare($sql);
    $ok   = $stmt->execute(['id'=>$d['id_categoria']]);
    return [
      'respuesta'=> $ok?1:0,
      'accion'   =>'eliminar',
      'mensaje'  => $ok?'Categoría eliminada':'Error al eliminar'
    ];
  }

  public function consultarActivas(): array {
    $sql  = "SELECT id_categoria,nombre FROM categoria WHERE estatus=1";
    return $this->getConex1()->query($sql)
                ->fetchAll(PDO::FETCH_ASSOC);
  }
}
