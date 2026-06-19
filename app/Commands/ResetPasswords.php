<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Comando: php spark usuarios:reset-passwords
 *
 * Reemplaza: reset_credentials.php + actualizar_passwords.php
 * Regenera el hash bcrypt de todos los usuarios activos con una
 * nueva contraseña. Muestra verificación por cada usuario.
 *
 * Uso con contraseña por argumento:
 *   php spark usuarios:reset-passwords NuevaPass123!
 *
 * Sin argumento pide la contraseña interactivamente.
 */
class ResetPasswords extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'usuarios:reset-passwords';
    protected $description = 'Regenera el hash bcrypt de todos los usuarios activos con una nueva contraseña.';
    protected $usage       = 'usuarios:reset-passwords [nueva_password]';
    protected $arguments   = [
        'nueva_password' => '(Opcional) Nueva contraseña. Si no se indica, se pedirá de forma interactiva.',
    ];

    public function run(array $params): void
    {
        $db = Database::connect();

        CLI::write('');
        CLI::write('=== RESET DE CONTRASEÑAS DE USUARIOS ===', 'cyan');
        CLI::write('');

        // Obtener contraseña — argumento CLI o prompt interactivo
        $nuevaPassword = $params[0] ?? null;

        if (empty($nuevaPassword)) {
            $nuevaPassword = CLI::prompt('Nueva contraseña para todos los usuarios activos');
        }

        if (empty($nuevaPassword)) {
            CLI::error('No se proporcionó una contraseña. Operación cancelada.');
            return;
        }

        // Generar y verificar hash ANTES de aplicarlo
        $hash = password_hash($nuevaPassword, PASSWORD_BCRYPT);

        if (! password_verify($nuevaPassword, $hash)) {
            CLI::error('El hash generado no verifica correctamente. Abortando por seguridad.');
            return;
        }

        CLI::write('Contraseña   : ' . CLI::color($nuevaPassword, 'yellow'));
        CLI::write('Hash (preview): ' . substr($hash, 0, 30) . '...');
        CLI::write('Verificación  : ' . CLI::color('OK', 'green'));
        CLI::write('');

        // Actualizar todos los usuarios activos
        $db->table('usuarios')
            ->where('activo', 1)
            ->update([
                'password_hash' => $hash,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);

        $afectados = $db->affectedRows();
        CLI::write("Usuarios actualizados: {$afectados}", 'green');
        CLI::write('');

        // Listar y verificar cada usuario
        CLI::write('DETALLE DE USUARIOS ACTUALIZADOS:', 'yellow');
        CLI::write(str_repeat('─', 70));
        CLI::write(sprintf('%-5s %-15s %-30s %-20s %-6s', 'ID', 'CÉDULA', 'EMAIL', 'ROL', 'PASS'));
        CLI::write(str_repeat('─', 70));

        $rows = $db->table('usuarios u')
            ->select('u.id_usuario, u.cedula, u.email, u.password_hash, r.nombre_rol')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->where('u.activo', 1)
            ->orderBy('u.id_rol')
            ->get()->getResultArray();

        foreach ($rows as $row) {
            $passOk  = password_verify($nuevaPassword, $row['password_hash']);
            $passStr = $passOk ? CLI::color('OK', 'green') : CLI::color('FALLO', 'red');

            CLI::write(sprintf(
                '%-5s %-15s %-30s %-20s ',
                $row['id_usuario'],
                $row['cedula'],
                $row['email'],
                substr($row['nombre_rol'] ?? 'N/A', 0, 20)
            ) . $passStr);
        }

        CLI::write(str_repeat('─', 70));
        CLI::write('');
        CLI::write(CLI::color('✓ Proceso completado.', 'green') . ' Contraseña activa: ' . CLI::color($nuevaPassword, 'yellow'));
        CLI::write('');
    }
}
