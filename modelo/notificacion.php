<?php

require_once __DIR__ . '/conexion.php';

class Notificacion extends Conexion
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 1) Generar notificaciones a partir de pedidos
     */
public function generarDePedidos(): int
{
    $conex = $this->getConex1();

    // 1) Traer sólo nuevos pedidos web (2) o reservas (3)
    $sql = "
      SELECT p.id_pedido, p.fecha, p.tipo, p.precio_total_bs 
        FROM pedido p
   LEFT JOIN notificaciones n ON n.id_pedido = p.id_pedido
       WHERE p.tipo IN (2,3)
         AND n.id_pedido IS NULL
    ";
    $stmt    = $conex->query($sql);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2) Preparar insert y contador
    $ins     = $conex->prepare("
        INSERT INTO notificaciones
           (titulo, mensaje, estado, fecha, id_pedido)
        VALUES (:titulo, :mensaje, 1, :fecha, :id_pedido)
    ");
    $nuevas = 0;

    foreach ($pedidos as $p) {
        // 3) Diferenciar por tipo
        if ((int)$p['tipo'] === 2) {
            $titulo  = 'Nuevo pedido web';
            $mensaje = "Pedido #{$p['id_pedido']} por Bs. {$p['precio_total_bs']}";
        } else {
            $titulo  = 'Nueva reserva';
            $mensaje = "Reserva #{$p['id_pedido']} registrada el {$p['fecha']}";
        }

        $ins->execute([
            'titulo'     => $titulo,
            'mensaje'    => $mensaje,
            'fecha'      => $p['fecha'],
            'id_pedido'  => $p['id_pedido']
        ]);
        $nuevas++;
    }

    return $nuevas;
}


/**
 * Cuenta notificaciones para el administrador
 * (estado = 1 nuevas  o estado = 4 leídas sólo por asesora)
 */
public function contarParaAdmin(): int
{
    $sql = "SELECT COUNT(*) 
              FROM notificaciones 
             WHERE estado IN (1,4)";
    return (int)$this->getConex1()
                     ->query($sql)
                     ->fetchColumn();
}
/**
 * Marca una notificación como leída sólo por la asesora (1 → 4)
 */
public function marcarLeidaAsesora(int $idNoti): bool
{
    $sql = "
      UPDATE notificaciones
         SET estado = 4
       WHERE id_notificacion = :id
         AND estado = 1
    ";
    return $this->getConex1()
                ->prepare($sql)
                ->execute(['id' => $idNoti]);
}


    /**
     * 2) Obtener todas las notificaciones activas
     */
public function getAll(): array
{
    $conex = $this->getConex1();
    $sql = "
        SELECT id_notificacion,
               titulo,
               mensaje,
               fecha,
               estado,
               id_pedido
          FROM notificaciones
         WHERE estado IN (1,2,3,4)   -- ADDED 4
         ORDER BY fecha DESC, id_notificacion DESC
    ";
    return $conex->query($sql)
                 ->fetchAll(PDO::FETCH_ASSOC);
}


public function marcarLeida(int $idNoti): bool
{
    $sql = "
      UPDATE notificaciones
         SET estado = 2
       WHERE id_notificacion = :id
         AND estado IN (1,4)
    ";
    return $this->getConex1()
                ->prepare($sql)
                ->execute(['id' => $idNoti]);
}


/**
 * Devuelve pedidos web nuevos (tipo=2) con id_pedido > $lastId
 */
public function getNuevosPedidos(int $lastId): array
{
    $sql = "
      SELECT id_pedido,
             fecha,
             precio_total_bs AS total,
             id_persona,
             tipo
        FROM pedido
       WHERE tipo IN (2,3)
         AND id_pedido > :lastId
       ORDER BY id_pedido ASC
    ";
    $stmt = $this->getConex1()->prepare($sql);
    $stmt->execute(['lastId' => $lastId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




    /**
     * 7) Contar nuevas (estado = 1)
     */
    public function contarNuevas(): int
    {
        $conex = $this->getConex1();
        return (int)$conex
            ->query("SELECT COUNT(*) FROM notificaciones WHERE estado = 1")
            ->fetchColumn();
    }

    /**
     * 8) Contar pendientes (estado = 2)
     */
    public function contarParaAsesora(): int
    {
        $conex = $this->getConex1();
        return (int)$conex
            ->query("SELECT COUNT(*) FROM notificaciones WHERE estado = 2")
            ->fetchColumn();
    }
}
