<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Comando: php spark db:reset-completo
 *
 * Reemplaza: reset_all.php
 * Elimina TODOS los usuarios y empleados, resetea auto_increment
 * y crea un usuario AdminTH y un usuario Empleado de ejemplo.
 *
 * ⚠⚠ MUY DESTRUCTIVO: Requiere confirmación doble.
 */
class ResetCompleto extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:reset-completo';
    protected $description = '[MUY DESTRUCTIVO] Borra todos los usuarios/empleados y crea 2 de cero. Requiere confirmación doble.';
    protected $usage       = 'db:reset-completo [--force]';
    protected $options     = ['--force' => 'Omite las confirmaciones interactivas (PELIGROSO).'];

    public function run(array $params): void
    {
        $db = Database::connect();

        CLI::write('');
        CLI::write(CLI::color('╔══════════════════════════════════════════╗', 'red'));
        CLI::write(CLI::color('║          RESET COMPLETO DE USUARIOS      ║', 'red'));
        CLI::write(CLI::color('╚══════════════════════════════════════════╝', 'red'));
        CLI::write('');
        CLI::write(CLI::color('⚠⚠  ADVERTENCIA CRÍTICA  ⚠⚠', 'red'));
        CLI::write('Este comando eliminará TODOS los usuarios y empleados de la base de datos.');
        CLI::write('');

        if (! isset($params['force'])) {
            $confirm1 = CLI::prompt('¿Estás seguro? Escribe "CONFIRMAR" para continuar', null);
            if ($confirm1 !== 'CONFIRMAR') {
                CLI::write('Operación cancelada.', 'yellow');
                return;
            }

            $confirm2 = CLI::prompt('Última advertencia. ¿Proceder con el reset total?', ['s', 'n']);
            if (strtolower($confirm2) !== 's') {
                CLI::write('Operación cancelada.', 'yellow');
                return;
            }
        }

        $nuevaPassword = CLI::prompt('Nueva contraseña para los usuarios creados', 'Admin2026!');
        $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

        CLI::write('');
        CLI::write('=== RESET COMPLETO ===', 'cyan');

        // 1. Desactivar FK
        $db->query('SET FOREIGN_KEY_CHECKS = 0');

        // 2. Limpiar tablas dependientes
        $db->query('DELETE FROM sesiones_activas');
        CLI::write('  ' . CLI::color('✓', 'green') . ' sesiones_activas limpiada');

        $db->query('DELETE FROM logs_sistema');
        CLI::write('  ' . CLI::color('✓', 'green') . ' logs_sistema limpiada');

        // 3. Eliminar empleados y usuarios
        $db->query('DELETE FROM empleados');
        CLI::write('  ' . CLI::color('✓', 'green') . ' Todos los empleados eliminados');

        $db->query('DELETE FROM usuarios');
        CLI::write('  ' . CLI::color('✓', 'green') . ' Todos los usuarios eliminados');

        // 4. Resetear auto_increment
        $db->query('ALTER TABLE usuarios AUTO_INCREMENT = 1');
        $db->query('ALTER TABLE empleados AUTO_INCREMENT = 1');

        // 5. Crear usuario Admin TH
        $db->table('usuarios')->insert([
            'cedula'           => '0802829192',
            'email'            => 'admin@mail.com',
            'password_hash'    => $hash,
            'id_rol'           => 2,
            'activo'           => 1,
            'password_changed' => 1,
        ]);
        $adminId = $db->insertID();

        $db->table('empleados')->insert([
            'id_usuario'   => $adminId,
            'nombres'      => 'Leonardo',
            'apellidos'    => 'Minda',
            'tipo_empleado'=> 'ADMINISTRATIVO',
            'departamento' => 'Talento Humano',
            'fecha_ingreso'=> date('Y-m-d'),
            'estado'       => 'ACTIVO',
        ]);

        CLI::write('');
        CLI::write(CLI::color('✓ Usuario Admin TH creado (ID: ' . $adminId . ')', 'green'));
        CLI::write('  Cédula     : 0802829192');
        CLI::write('  Email      : admin@mail.com');
        CLI::write('  Contraseña : ' . CLI::color($nuevaPassword, 'yellow'));
        CLI::write('  Empleado   : Leonardo Minda');

        // 6. Crear usuario Empleado de ejemplo
        $db->table('usuarios')->insert([
            'cedula'           => '0900000001',
            'email'            => 'empleado@mail.com',
            'password_hash'    => $hash,
            'id_rol'           => 3,
            'activo'           => 1,
            'password_changed' => 1,
        ]);
        $empId = $db->insertID();

        $db->table('empleados')->insert([
            'id_usuario'   => $empId,
            'nombres'      => 'Empleado',
            'apellidos'    => 'Prueba',
            'tipo_empleado'=> 'DOCENTE',
            'departamento' => 'Departamento General',
            'fecha_ingreso'=> date('Y-m-d'),
            'estado'       => 'ACTIVO',
        ]);

        CLI::write('');
        CLI::write(CLI::color('✓ Usuario Empleado creado (ID: ' . $empId . ')', 'green'));
        CLI::write('  Cédula     : 0900000001');
        CLI::write('  Email      : empleado@mail.com');
        CLI::write('  Contraseña : ' . CLI::color($nuevaPassword, 'yellow'));
        CLI::write('  Empleado   : Empleado Prueba');

        // 7. Reactivar FK
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        // 8. Verificación final
        CLI::write('');
        CLI::write('=== VERIFICACIÓN FINAL ===', 'cyan');
        $rows = $db->table('usuarios u')
            ->select('u.id_usuario, u.cedula, u.email, r.nombre_rol, e.nombres, e.apellidos')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->join('empleados e', 'e.id_usuario = u.id_usuario', 'left')
            ->get()->getResultArray();

        foreach ($rows as $row) {
            $pass_ok = password_verify($nuevaPassword, $db->table('usuarios')->select('password_hash')->where('id_usuario', $row['id_usuario'])->get()->getRowArray()['password_hash'])
                ? CLI::color('OK', 'green') : CLI::color('FALLO', 'red');
            CLI::write('  [' . $row['nombre_rol'] . '] ' . $row['nombres'] . ' ' . $row['apellidos'] . ' | ' . $row['email'] . ' | Pass: ' . $pass_ok);
        }

        $total = $db->table('usuarios')->countAllResults();
        CLI::write('');
        CLI::write("Total usuarios: {$total}");
        CLI::write(CLI::color('=== RESET COMPLETADO ===', 'cyan'));
        CLI::write('');
    }
}
