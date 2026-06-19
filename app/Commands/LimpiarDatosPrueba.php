<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Comando: php spark db:limpiar-datos
 *
 * Reemplaza: clean_database.php
 * Vacía con TRUNCATE las tablas de datos de prueba.
 * NO toca usuarios ni empleados.
 * ⚠ DESTRUCTIVO: Solicita confirmación antes de ejecutar.
 */
class LimpiarDatosPrueba extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:limpiar-datos';
    protected $description = '[DESTRUCTIVO] Vacía tablas de datos de prueba (TRUNCATE). No toca usuarios ni empleados.';
    protected $usage       = 'db:limpiar-datos [--force]';
    protected $options     = ['--force' => 'Omite la confirmación interactiva.'];

    private array $tablasATruncar = [
        'inasistencias', 'capacitaciones', 'capacitaciones_empleados',
        'empleados_capacitaciones', 'solicitudes', 'solicitudes_generales',
        'evaluaciones', 'evaluaciones_empleados', 'detalles_evaluacion',
        'preguntas_evaluacion', 'asistencias', 'certificados', 'contratos',
        'documentos', 'historial_laboral', 'detalles_nomina', 'nominas',
        'postulaciones', 'postulantes', 'titulos_academicos',
        'empleados_competencias', 'competencias', 'categorias_evaluacion',
        'permisos', 'vacantes', 'candidatos', 'departamentos', 'puestos',
        'tipos_inasistencia', 'politicas_inasistencia', 'categorias',
        'periodos_academicos', 'logs_sistema', 'sesiones_activas',
    ];

    public function run(array $params): void
    {
        $db = Database::connect();

        CLI::write('');
        CLI::write(CLI::color('ADVERTENCIA: ', 'red') . 'Esta operación vaciará datos de prueba. Los usuarios/empleados NO serán afectados.');
        CLI::write('');

        if (! isset($params['force'])) {
            $confirm = CLI::prompt('¿Confirmas la limpieza? Esta acción NO se puede deshacer', ['s', 'n']);
            if (strtolower($confirm) !== 's') {
                CLI::write('Operación cancelada.', 'yellow');
                return;
            }
        }

        CLI::write('');
        CLI::write('=== LIMPIEZA DE DATOS ===', 'cyan');

        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $existing = array_map('current', $db->query('SHOW TABLES')->getResultArray());

        foreach ($this->tablasATruncar as $tabla) {
            if (in_array($tabla, $existing)) {
                $count = $db->table($tabla)->countAllResults();
                $db->query("TRUNCATE TABLE `{$tabla}`");
                CLI::write('  ' . CLI::color('✓', 'green') . " {$tabla} vaciada ({$count} registros)");
            } else {
                CLI::write('  ' . CLI::color('⚠', 'yellow') . " {$tabla} no existe, omitida");
            }
        }

        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        CLI::write('');
        CLI::write('Verificación — Empleados intactos:', 'yellow');
        $rows = $db->table('usuarios u')
            ->select('u.email, r.nombre_rol, e.nombres, e.apellidos')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->join('empleados e', 'e.id_usuario = u.id_usuario', 'left')
            ->get()->getResultArray();

        foreach ($rows as $row) {
            CLI::write('  ' . CLI::color('✓', 'green') . ' [' . $row['nombre_rol'] . '] ' . $row['nombres'] . ' ' . $row['apellidos'] . ' (' . $row['email'] . ')');
        }

        CLI::write('');
        CLI::write(CLI::color('=== LIMPIEZA COMPLETADA ===', 'cyan'));
        CLI::write('');
    }
}
