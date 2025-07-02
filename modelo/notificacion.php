<?php

require_once 'modelo/conexion.php';

class Notificacion {


public function generarDePedidos(): array {
    // Abre la conexión
    $c = (new Conexion())->getConex1();

    try {
        // 1) Obtiene solo pedidos con estado 1 o 2 y método de pago 1 o 2
        $sqlPed = "
            SELECT id_pedido, fecha
            FROM pedido
            WHERE estado IN (1,2)
              AND id_metodopago IN (1,2)
        ";
        $pedidos = $c->query($sqlPed)
                     ->fetchAll(PDO::FETCH_ASSOC);

        // 2) Prepara chequear existencias y insertar nuevos
        $stmtCheck = $c->prepare(
            "SELECT COUNT(*) 
             FROM notificaciones 
             WHERE id_pedido = :pid"
        );
        $stmtIns = $c->prepare(
            "INSERT INTO notificaciones
             (titulo, mensaje, estado, fecha, id_pedido)
             VALUES (:t, :m, 1, :f, :pid)"
        );

        // 3) Itera y crea notificaciones solo si no existen
        $nuevas = 0;
        foreach ($pedidos as $p) {
            $stmtCheck->execute(['pid' => $p['id_pedido']]);
            if ($stmtCheck->fetchColumn() > 0) {
                continue;
            }

            $stmtIns->execute([
                't'   => 'En espera de confirmación de pago',
                'm'   => "Pago pendiente para el pedido #{$p['id_pedido']}",
                'f'   => $p['fecha'],
                'pid' => $p['id_pedido']
            ]);
            $nuevas++;
        }

        return ['ok' => true, 'nuevas' => $nuevas];
    } finally {
        // Cierra la conexión
        $c = null;
    }
}


    /**
     * 2) Obtiene todas las notificaciones activas (estado 1,2 o 3).
     */
    public function getAll(): array {
        $c = (new Conexion())->getConex1();
        try {
            $sql = "SELECT id_notificaciones, titulo, mensaje, estado, fecha, id_pedido
                    FROM notificaciones
                    WHERE estado IN (1,2,3)
                    ORDER BY fecha DESC, id_notificaciones DESC";
            $data = $c->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return ['ok'=>true, 'data'=>$data];
        } finally {
            $c = null;
        }
    }

    /**
     * 3) Admin: marca como leída (estado → 2).
     */
    public function marcarLeida(int $id): array {
        $c = (new Conexion())->getConex1();
        try {
            $sql = "UPDATE notificaciones
                    SET estado = 2
                    WHERE id_notificaciones = :id
                      AND estado = 1";
            $ok = $c->prepare($sql)->execute(['id'=>$id]);
            return ['ok'=>$ok, 'id'=>$id, 'accion'=>'marcarLeida'];
        } finally {
            $c = null;
        }
    }

    /**
     * 4) Asesora: marca como entregada (estado → 3).
     */
    public function entregar(int $id): array {
        $c = (new Conexion())->getConex1();
        try {
            $sql = "UPDATE notificaciones
                    SET estado = 3
                    WHERE id_notificaciones = :id
                      AND estado = 2";
            $ok = $c->prepare($sql)->execute(['id'=>$id]);
            return ['ok'=>$ok, 'id'=>$id, 'accion'=>'entregar'];
        } finally {
            $c = null;
        }
    }

    /**
     * 5) Elimina (soft delete) solo si estado = 3.
     */
    public function eliminar(int $id): array {
        $c = (new Conexion())->getConex1();
        try {
            $sql  = "UPDATE notificaciones
                     SET estado = 0
                     WHERE id_notificaciones = :id
                       AND estado = 3";
            $stmt = $c->prepare($sql);
            $stmt->execute(['id'=>$id]);
            $rows = $stmt->rowCount();
            if ($rows > 0) {
                return ['ok'=>true, 'id'=>$id, 'accion'=>'eliminar'];
            } else {
                return ['ok'=>false, 'error'=>'Solo se pueden borrar notificaciones entregadas'];
            }
        } finally {
            $c = null;
        }
    }

    /**
     * 6) Vaciar todas las entregadas (estado → 0).
     */
    public function vaciarEntregadas(): array {
        $c = (new Conexion())->getConex1();
        try {
            $sql   = "UPDATE notificaciones SET estado = 0 WHERE estado = 3";
            $count = $c->exec($sql);
            if ($count !== false) {
                return ['ok'=>true, 'deleted'=>$count, 'accion'=>'vaciar'];
            } else {
                return ['ok'=>false, 'error'=>'Error al vaciar notificaciones'];
            }
        } finally {
            $c = null;
        }
    }

    /**
     * 7) Contar notificaciones nuevas para admin (estado = 1).
     */
    public function contarNuevas(): int {
        $c = (new Conexion())->getConex1();
        try {
            $sql = "SELECT COUNT(*) FROM notificaciones WHERE estado = 1";
            return (int)$c->query($sql)->fetchColumn();
        } finally {
            $c = null;
        }
    }

    /**
     * 8) Contar notificaciones pendientes para asesora (estado = 2).
     */
    public function contarParaAsesora(): int {
        $c = (new Conexion())->getConex1();
        try {
            $sql = "SELECT COUNT(*) FROM notificaciones WHERE estado = 2";
            return (int)$c->query($sql)->fetchColumn();
        } finally {
            $c = null;
        }
    }
}
