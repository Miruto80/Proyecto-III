<?php
// modelo/notificacion.php

require_once 'modelo/conexion.php';

class Notificacion {
    private PDO $cnx;

    public function __construct() {
        $this->cnx = (new Conexion())->getConex1();
    }

    /**
     * 1) Genera notificaciones para pedidos pendientes (estado 1 o 2).
     *    Evita duplicados y devuelve cuántas nuevas insertó.
     */
    public function generarDePedidos(): array {
        $sqlPed = "SELECT id_pedido, fecha
                   FROM pedido
                   WHERE estado IN (1,2)";
        $pedidos = $this->cnx
                        ->query($sqlPed)
                        ->fetchAll(PDO::FETCH_ASSOC);

        $stmtCheck = $this->cnx->prepare(
            "SELECT COUNT(*) FROM notificaciones WHERE id_pedido = :pid"
        );
        $stmtIns = $this->cnx->prepare(
            "INSERT INTO notificaciones
             (titulo, mensaje, estado, fecha, id_pedido)
             VALUES (:t, :m, 1, :f, :pid)"
        );

        $nuevas = 0;
        foreach ($pedidos as $p) {
            $stmtCheck->execute([':pid' => $p['id_pedido']]);
            if ($stmtCheck->fetchColumn() > 0) continue;
            $stmtIns->execute([
                ':t'   => 'En espera de confirmación de pago',
                ':m'   => "Pago pendiente para el pedido #{$p['id_pedido']}",
                ':f'   => $p['fecha'],
                ':pid' => $p['id_pedido']
            ]);
            $nuevas++;
        }

        return ['ok' => true, 'nuevas' => $nuevas];
    }

    /**
     * 2) Obtiene todas las notificaciones activas (estado 1,2 o 3).
     */
    public function getAll(): array {
        $sql = "SELECT id_notificaciones, titulo, mensaje, estado, fecha, id_pedido
                FROM notificaciones
                WHERE estado IN (1,2,3)
                ORDER BY fecha DESC, id_notificaciones DESC";
        $data = $this->cnx
                     ->query($sql)
                     ->fetchAll(PDO::FETCH_ASSOC);
        return ['ok' => true, 'data' => $data];
    }

    /**
     * 3) Admin: marca como leída (estado → 2).
     */
    public function marcarLeida(int $id): array {
        $sql = "UPDATE notificaciones
                SET estado = 2
                WHERE id_notificaciones = :id
                  AND estado = 1";
        $ok = $this->cnx
                   ->prepare($sql)
                   ->execute([':id' => $id]);
        return ['ok' => $ok, 'id' => $id, 'accion' => 'marcarLeida'];
    }

    /**
     * 4) Asesora: marca como entregada (estado → 3).
     */
    public function entregar(int $id): array {
        $sql = "UPDATE notificaciones
                SET estado = 3
                WHERE id_notificaciones = :id
                  AND estado = 2";
        $ok = $this->cnx
                   ->prepare($sql)
                   ->execute([':id' => $id]);
        return ['ok' => $ok, 'id' => $id, 'accion' => 'entregar'];
    }

    /**
     * 5) Elimina (soft delete) solo si estado = 3.
     */
    public function eliminar(int $id): array {
        $sql  = "UPDATE notificaciones
                 SET estado = 0
                 WHERE id_notificaciones = :id
                   AND estado = 3";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute([':id' => $id]);
        $rows = $stmt->rowCount();
        if ($rows > 0) {
            return ['ok' => true, 'id' => $id, 'accion' => 'eliminar'];
        } else {
            return ['ok' => false, 'error' => 'Solo se pueden borrar notificaciones entregadas'];
        }
    }

    /**
     * 6) Vaciar todas las entregadas (estado → 0).
     */
    public function vaciarEntregadas(): array {
        $sql   = "UPDATE notificaciones SET estado = 0 WHERE estado = 3";
        $count = $this->cnx->exec($sql);
        if ($count !== false) {
            return ['ok' => true, 'deleted' => $count, 'accion' => 'vaciar'];
        } else {
            return ['ok' => false, 'error' => 'Error al vaciar notificaciones'];
        }
    }
}
