<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Comando: php spark db:diagnosticar
 *
 * Reemplaza el script raíz: fix_database.php
 * Diagnostica la base de datos, verifica tablas requeridas,
 * las crea si faltan y muestra el estado de usuarios/empleados.
 */
class DiagnosticarBD extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:diagnosticar';
    protected $description = 'Diagnostica la BD: verifica tablas requeridas (sesiones_activas, logs_sistema) y las crea si no existen.';
    protected $usage       = 'db:diagnosticar';

    public function run(array $params): void
    {
        $db = Database::connect();

        CLI::write('');
        CLI::write('=== DIAGNÓSTICO DE BASE DE DATOS ===', 'cyan');
        CLI::write('');

        // 1. Listar tablas existentes
        $tables = $db->query('SHOW TABLES')->getResultArray();
        $tableNames = array_map('current', $tables);

        CLI::write('Tablas existentes (' . count($tableNames) . '):', 'yellow');
        foreach ($tableNames as $t) {
            CLI::write('  - ' . $t);
        }
        CLI::write('');

        // 2. Verificar tablas requeridas
        $required = ['sesiones_activas', 'logs_sistema', 'usuarios', 'empleados', 'roles'];
        CLI::write('Verificando tablas requeridas:', 'yellow');
        foreach ($required as $table) {
            $exists = in_array($table, $tableNames);
            $icon   = $exists ? '✓' : '✗';
            $color  = $exists ? 'green' : 'red';
            $status = $exists ? 'EXISTE' : 'NO EXISTE';
            CLI::write('  ' . CLI::color($icon . ' ' . $table . ': ' . $status, $color));
        }
        CLI::write('');

        // 3. Crear tabla sesiones_activas si no existe
        if (! in_array('sesiones_activas', $tableNames)) {
            CLI::write('Creando tabla sesiones_activas...', 'yellow');
            $db->query("
                CREATE TABLE sesiones_activas (
                    id_sesion INT AUTO_INCREMENT PRIMARY KEY,
                    id_usuario INT NOT NULL,
                    token_sesion VARCHAR(255) NOT NULL,
                    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
                    fecha_ultima_actividad DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    ip_address VARCHAR(45) DEFAULT NULL,
                    user_agent TEXT DEFAULT NULL,
                    activa TINYINT(1) DEFAULT 1,
                    INDEX idx_usuario (id_usuario),
                    INDEX idx_token (token_sesion),
                    INDEX idx_activa (activa),
                    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            CLI::write('  ' . CLI::color('✓ Tabla sesiones_activas creada', 'green'));
        }

        // 4. Crear tabla logs_sistema si no existe
        if (! in_array('logs_sistema', $tableNames)) {
            CLI::write('Creando tabla logs_sistema...', 'yellow');
            $db->query("
                CREATE TABLE logs_sistema (
                    id_log INT AUTO_INCREMENT PRIMARY KEY,
                    id_usuario INT DEFAULT NULL,
                    accion VARCHAR(100) NOT NULL,
                    modulo VARCHAR(50) NOT NULL,
                    descripcion TEXT DEFAULT NULL,
                    fecha_accion DATETIME DEFAULT CURRENT_TIMESTAMP,
                    ip_address VARCHAR(45) DEFAULT NULL,
                    INDEX idx_usuario (id_usuario),
                    INDEX idx_fecha (fecha_accion),
                    INDEX idx_modulo (modulo),
                    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            CLI::write('  ' . CLI::color('✓ Tabla logs_sistema creada', 'green'));
        }

        // 5. Verificar estructura de tabla empleados
        CLI::write('');
        CLI::write('Estructura de tabla empleados:', 'yellow');
        $cols = $db->query('SHOW COLUMNS FROM empleados')->getResultArray();
        foreach ($cols as $col) {
            $null = $col['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
            CLI::write('  ' . $col['Field'] . ' (' . $col['Type'] . ') ' . $null);
        }

        // 6. Verificar estructura de tabla usuarios
        CLI::write('');
        CLI::write('Estructura de tabla usuarios:', 'yellow');
        $cols = $db->query('SHOW COLUMNS FROM usuarios')->getResultArray();
        foreach ($cols as $col) {
            $null = $col['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
            CLI::write('  ' . $col['Field'] . ' (' . $col['Type'] . ') ' . $null);
        }

        // 7. Verificar datos actuales de usuarios + empleados
        CLI::write('');
        CLI::write('Usuarios con sus datos de empleado:', 'yellow');
        $rows = $db->table('usuarios u')
            ->select('u.id_usuario, u.cedula, u.email, u.id_rol, r.nombre_rol, e.id_empleado, e.nombres, e.apellidos, e.tipo_empleado')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->join('empleados e', 'e.id_usuario = u.id_usuario', 'left')
            ->where('u.activo', 1)
            ->orderBy('u.id_rol')->orderBy('u.id_usuario')
            ->get()->getResultArray();

        foreach ($rows as $row) {
            $empInfo = $row['id_empleado']
                ? 'Empleado #' . $row['id_empleado'] . ': ' . $row['nombres'] . ' ' . $row['apellidos'] . ' (' . $row['tipo_empleado'] . ')'
                : CLI::color('SIN REGISTRO EN EMPLEADOS', 'red');
            CLI::write('  User ' . $row['id_usuario'] . ' (' . $row['email'] . ') - Rol: ' . $row['nombre_rol'] . ' - ' . $empInfo);
        }

        CLI::write('');
        CLI::write(CLI::color('=== DIAGNÓSTICO COMPLETADO ===', 'cyan'));
        CLI::write('');
    }
}
