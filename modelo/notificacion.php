<?php
// modelo/Notificacion.php

require_once 'modelo/Conexion.php';

class Notificacion extends Conexion
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Registra una entrada en la bitácora (usa getConex2())
     */
    public function registrarBitacora(string $jsonDatos): bool
    {
        $datos = json_decode($jsonDatos, true);
        return $this->ejecutarSentenciaBitacora($datos);
    }

    private function ejecutarSentenciaBitacora(array $datos): bool
    {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();

            $sql = "INSERT INTO bitacora 
                        (accion, fecha_hora, descripcion, id_persona) 
                    VALUES 
                        (:accion, NOW(), :descripcion, :id_persona)";
            $stmt = $conex->prepare($sql);
            $stmt->execute($datos);

            $conex->commit();
            return true;
        } catch (PDOException $e) {
            $conex->rollBack();
            throw $e;
        } finally {
            $conex = null;
        }
    }

    /**
     * 1) Generar notificaciones a partir de pedidos
     */
    public function generarDePedidos(): int
    {
        $conex = $this->getConex1();

        $sqlPed = "
            SELECT id_pedido, fecha
              FROM pedido
             WHERE estado  IN (1,2)
               AND id_pago IN (1,2)
        ";
        $pedidos = $conex->query($sqlPed)
                        ->fetchAll(PDO::FETCH_ASSOC);

        $chk = $conex->prepare(
            "SELECT COUNT(*) 
               FROM notificaciones 
              WHERE id_pedido = :pid"
        );
        $ins = $conex->prepare(
            "INSERT INTO notificaciones
             (titulo, mensaje, estado, fecha, id_pedido)
             VALUES (:t, :m, 1, :f, :pid)"
        );

        $nuevas = 0;
        foreach ($pedidos as $p) {
            $chk->execute(['pid' => $p['id_pedido']]);
            if ($chk->fetchColumn() > 0) continue;

            $ins->execute([
                't'   => 'En espera de confirmación de pago',
                'm'   => "Pago pendiente para el pedido #{$p['id_pedido']}",
                'f'   => $p['fecha'],
                'pid' => $p['id_pedido'],
            ]);
            $nuevas++;
        }

        return $nuevas;
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
             WHERE estado IN (1,2,3)
             ORDER BY fecha DESC, id_notificacion DESC
        ";
        return $conex->query($sql)
                     ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3) Admin: marcar como leída (1 → 2)
     */
    public function marcarLeida(int $idNoti): bool
    {
        $conex = $this->getConex1();
        $sql = "
            UPDATE notificaciones
               SET estado = 2
             WHERE id_notificacion = :id
               AND estado = 1
        ";
        return $conex->prepare($sql)
                     ->execute(['id' => $idNoti]);
    }



    /**
     * 5) Soft‐delete si estado = 3 (3 → 0)
     */
    public function eliminar(int $idNoti): bool
    {
        $conex = $this->getConex1();
        $sql = "
            UPDATE notificaciones
               SET estado = 0
             WHERE id_notificacion = :id
               AND estado = 3
        ";
        $stmt = $conex->prepare($sql);
        $stmt->execute(['id' => $idNoti]);
        return $stmt->rowCount() > 0;
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
