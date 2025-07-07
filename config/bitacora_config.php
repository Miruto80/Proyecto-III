<?php
/**
 * Configuración centralizada para el sistema de bitácora
 * Este archivo proporciona funciones helper para registrar actividades
 */

// Función global para registrar en bitácora de forma simplificada
function registrarBitacora($accion, $descripcion, $id_persona = null) {
    if ($id_persona === null && isset($_SESSION['id'])) {
        $id_persona = $_SESSION['id'];
    }
    
    if ($id_persona === null) {
        return false;
    }
    
    $datos = [
        'id_persona' => $id_persona,
        'accion' => $accion,
        'descripcion' => $descripcion
    ];
    
    // Crear instancia temporal de bitácora
    require_once 'modelo/bitacora.php';
    $bitacora = new Bitacora();
    
    try {
        $conex = $bitacora->getConex2();
        $sql = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                VALUES (:accion, NOW(), :descripcion, :id_persona)";
        $stmt = $conex->prepare($sql);
        $stmt->execute($datos);
        return true;
    } catch (PDOException $e) {
        error_log("Error al registrar bitácora: " . $e->getMessage());
        return false;
    }
}

// Función para registrar acceso a módulos
function registrarAccesoModulo($modulo) {
    return registrarBitacora(
        'ACCESO A MÓDULO',
        "Usuario accedió al módulo de " . ucfirst($modulo),
        $_SESSION['id'] ?? null
    );
}

// Función para registrar operaciones CRUD
function registrarOperacionCRUD($operacion, $modulo, $detalles = '') {
    $acciones = [
        'crear' => 'CREAR',
        'registrar' => 'CREAR',
        'incluir' => 'CREAR',
        'modificar' => 'MODIFICAR',
        'actualizar' => 'MODIFICAR',
        'editar' => 'MODIFICAR',
        'eliminar' => 'ELIMINAR',
        'borrar' => 'ELIMINAR',
        'cambiar_estado' => 'CAMBIO_ESTADO',
        'activar' => 'CAMBIO_ESTADO',
        'desactivar' => 'CAMBIO_ESTADO'
    ];
    
    $accion = $acciones[strtolower($operacion)] ?? 'OTRO';
    $descripcion = "Operación {$operacion} en módulo " . ucfirst($modulo);
    
    if (!empty($detalles)) {
        $descripcion .= ": " . $detalles;
    }
    
    return registrarBitacora($accion, $descripcion);
}

// Función para registrar login/logout
function registrarSesion($tipo, $usuario = '') {
    $acciones = [
        'login' => 'Inicio de sesión',
        'logout' => 'Cierre de sesión',
        'timeout' => 'Sesión expirada'
    ];
    
    $descripcion = $acciones[$tipo] ?? 'Acción de sesión';
    if (!empty($usuario)) {
        $descripcion .= " - Usuario: " . $usuario;
    }
    
    return registrarBitacora('ACCESO A SISTEMA', $descripcion);
}

// Función para registrar errores
function registrarError($modulo, $error, $detalles = '') {
    $descripcion = "Error en módulo " . ucfirst($modulo) . ": " . $error;
    if (!empty($detalles)) {
        $descripcion .= " - " . $detalles;
    }
    
    return registrarBitacora('ERROR', $descripcion);
}

// Función para registrar exportaciones
function registrarExportacion($modulo, $formato) {
    return registrarBitacora(
        'EXPORTAR',
        "Exportación de " . ucfirst($modulo) . " en formato " . strtoupper($formato)
    );
}

// Función para registrar búsquedas
function registrarBusqueda($modulo, $termino = '') {
    $descripcion = "Búsqueda en módulo " . ucfirst($modulo);
    if (!empty($termino)) {
        $descripcion .= " - Término: " . $termino;
    }
    
    return registrarBitacora('BUSCAR', $descripcion);
}

// Constantes para acciones comunes
define('BITACORA_CREAR', 'CREAR');
define('BITACORA_MODIFICAR', 'MODIFICAR');
define('BITACORA_ELIMINAR', 'ELIMINAR');
define('BITACORA_ACCESO_MODULO', 'ACCESO A MÓDULO');
define('BITACORA_CAMBIO_ESTADO', 'CAMBIO_ESTADO');
define('BITACORA_LOGIN', 'ACCESO A SISTEMA');
define('BITACORA_ERROR', 'ERROR');
define('BITACORA_EXPORTAR', 'EXPORTAR');
define('BITACORA_BUSCAR', 'BUSCAR');

// Función para obtener estadísticas de bitácora
function obtenerEstadisticasBitacora($dias = 7) {
    require_once 'modelo/bitacora.php';
    $bitacora = new Bitacora();
    $conex = $bitacora->getConex2();
    
    try {
        $fecha_limite = date('Y-m-d H:i:s', strtotime("-{$dias} days"));
        
        // Total de registros
        $sql_total = "SELECT COUNT(*) as total FROM bitacora WHERE fecha_hora >= :fecha_limite";
        $stmt_total = $conex->prepare($sql_total);
        $stmt_total->execute(['fecha_limite' => $fecha_limite]);
        $total = $stmt_total->fetchColumn();
        
        // Registros por acción
        $sql_acciones = "SELECT accion, COUNT(*) as cantidad 
                        FROM bitacora 
                        WHERE fecha_hora >= :fecha_limite 
                        GROUP BY accion 
                        ORDER BY cantidad DESC";
        $stmt_acciones = $conex->prepare($sql_acciones);
        $stmt_acciones->execute(['fecha_limite' => $fecha_limite]);
        $acciones = $stmt_acciones->fetchAll(PDO::FETCH_ASSOC);
        
        // Registros por usuario
        $sql_usuarios = "SELECT p.nombre, p.apellido, COUNT(*) as cantidad 
                        FROM bitacora b 
                        INNER JOIN usuario p ON b.id_persona = p.id_persona 
                        WHERE b.fecha_hora >= :fecha_limite 
                        GROUP BY b.id_persona 
                        ORDER BY cantidad DESC 
                        LIMIT 10";
        $stmt_usuarios = $conex->prepare($sql_usuarios);
        $stmt_usuarios->execute(['fecha_limite' => $fecha_limite]);
        $usuarios = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'total' => $total,
            'acciones' => $acciones,
            'usuarios' => $usuarios,
            'periodo_dias' => $dias
        ];
        
    } catch (PDOException $e) {
        error_log("Error al obtener estadísticas de bitácora: " . $e->getMessage());
        return false;
    }
}
?> 