<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Comando: php spark usuarios:crear-seed
 *
 * Reemplaza el script raíz: crear_usuarios_faltantes.php
 * Crea los usuarios de prueba para todos los roles del sistema.
 * Si el usuario ya existe (por cédula), actualiza su contraseña.
 * Crea el registro de empleado correspondiente si es usuario nuevo.
 */
class CrearUsuariosSeed extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'usuarios:crear-seed';
    protected $description = 'Crea (o actualiza contraseñas de) los usuarios de prueba para todos los roles del sistema.';
    protected $usage       = 'usuarios:crear-seed';

    /**
     * Definición de usuarios a crear/actualizar.
     */
    private array $usuarios = [
        [
            'cedula'    => '9999999999',
            'email'     => 'superadmin@itsi.edu.ec',
            'password'  => 'superadmin123',
            'id_rol'    => 1,
            'nombres'   => 'Super',
            'apellidos' => 'Administrador',
        ],
        [
            'cedula'    => '8888888888',
            'email'     => 'admin.th@itsi.edu.ec',
            'password'  => 'adminth123',
            'id_rol'    => 2,
            'nombres'   => 'Ana',
            'apellidos' => 'García',
        ],
        [
            'cedula'    => '7777777777',
            'email'     => 'docente@itsi.edu.ec',
            'password'  => 'docente123',
            'id_rol'    => 3,
            'nombres'   => 'Carlos',
            'apellidos' => 'Pérez',
        ],
        [
            'cedula'    => '6666666666',
            'email'     => 'administrativo@itsi.edu.ec',
            'password'  => 'admin123',
            'id_rol'    => 6,
            'nombres'   => 'María',
            'apellidos' => 'López',
        ],
        [
            'cedula'    => '5555555555',
            'email'     => 'directivo@itsi.edu.ec',
            'password'  => 'directivo123',
            'id_rol'    => 7,
            'nombres'   => 'Roberto',
            'apellidos' => 'Martínez',
        ],
        [
            'cedula'    => '4444444444',
            'email'     => 'auxiliar@itsi.edu.ec',
            'password'  => 'auxiliar123',
            'id_rol'    => 8,
            'nombres'   => 'Patricia',
            'apellidos' => 'Rodríguez',
        ],
    ];

    /** Mapeo de rol a datos de empleado */
    private array $rolEmpleado = [
        1 => ['tipo_empleado' => 'ADMINISTRATIVO', 'departamento' => 'Departamento ITSI',  'tipo_docente' => null],
        2 => ['tipo_empleado' => 'ADMINISTRATIVO', 'departamento' => 'Recursos Humanos',    'tipo_docente' => null],
        3 => ['tipo_empleado' => 'DOCENTE',         'departamento' => 'Departamento General','tipo_docente' => 'Tiempo completo'],
        6 => ['tipo_empleado' => 'ADMINISTRATIVO', 'departamento' => 'Administrativo',       'tipo_docente' => null],
        7 => ['tipo_empleado' => 'DIRECTIVO',       'departamento' => 'Departamento ITSI',   'tipo_docente' => null],
        8 => ['tipo_empleado' => 'AUXILIAR',        'departamento' => 'Departamento ITSI',   'tipo_docente' => null],
    ];

    /** Nombres de rol para el resumen final */
    private array $nombreRol = [
        1 => 'Super Administrador',
        2 => 'Administrador Talento Humano',
        3 => 'Docente',
        6 => 'Administrativo',
        7 => 'Directivo',
        8 => 'Auxiliar',
    ];

    public function run(array $params): void
    {
        $db = Database::connect();

        CLI::write('');
        CLI::write('=== CREACIÓN / ACTUALIZACIÓN DE USUARIOS SEED ===', 'cyan');
        CLI::write('');

        foreach ($this->usuarios as $usuario) {
            // Verificar si el usuario ya existe por cédula
            $existing = $db->table('usuarios')
                ->where('cedula', $usuario['cedula'])
                ->get()->getRowArray();

            $hash = password_hash($usuario['password'], PASSWORD_DEFAULT);

            if ($existing === null) {
                // ── Crear nuevo usuario ──────────────────────────────────
                $db->table('usuarios')->insert([
                    'cedula'        => $usuario['cedula'],
                    'email'         => $usuario['email'],
                    'password_hash' => $hash,
                    'id_rol'        => $usuario['id_rol'],
                    'activo'        => 1,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
                $idUsuario = $db->insertID();

                // Crear empleado correspondiente
                $emp = $this->rolEmpleado[$usuario['id_rol']];
                $db->table('empleados')->insert([
                    'id_usuario'          => $idUsuario,
                    'tipo_empleado'       => $emp['tipo_empleado'],
                    'nombres'             => $usuario['nombres'],
                    'apellidos'           => $usuario['apellidos'],
                    'fecha_nacimiento'    => '1980-01-01',
                    'genero'              => 'Masculino',
                    'estado_civil'        => 'Soltero',
                    'direccion'           => 'Dirección de prueba',
                    'telefono'            => '0987654321',
                    'fecha_ingreso'       => '2020-01-01',
                    'activo'              => 1,
                    'estado'              => 'Activo',
                    'periodo_academico_id'=> 1,
                    'tipo_docente'        => $emp['tipo_docente'],
                    'departamento'        => $emp['departamento'],
                    'created_at'          => date('Y-m-d H:i:s'),
                    'updated_at'          => date('Y-m-d H:i:s'),
                ]);

                CLI::write(
                    '  ' . CLI::color('✓ CREADO:', 'green') .
                    ' ' . $usuario['nombres'] . ' ' . $usuario['apellidos'] .
                    ' (' . $usuario['email'] . ')'
                );
            } else {
                // ── Actualizar contraseña del usuario existente ──────────
                $db->table('usuarios')
                    ->where('cedula', $usuario['cedula'])
                    ->update([
                        'password_hash' => $hash,
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);

                CLI::write(
                    '  ' . CLI::color('↺ ACTUALIZADO:', 'yellow') .
                    ' ' . $usuario['nombres'] . ' ' . $usuario['apellidos'] .
                    ' (' . $usuario['email'] . ') — contraseña reseteada'
                );
            }
        }

        // ── Mostrar resumen de credenciales ─────────────────────────────
        CLI::write('');
        CLI::write('Usuarios procesados exitosamente.', 'green');
        CLI::write('');
        CLI::write('CREDENCIALES DE ACCESO AL SISTEMA', 'cyan');
        CLI::write(str_repeat('=', 50));
        CLI::write('');

        foreach ($this->usuarios as $usuario) {
            $rolNombre = $this->nombreRol[$usuario['id_rol']] ?? 'Desconocido';
            CLI::write(CLI::color('► ' . $usuario['nombres'] . ' ' . $usuario['apellidos'], 'white'));
            CLI::write('  Email    : ' . $usuario['email']);
            CLI::write('  Password : ' . CLI::color($usuario['password'], 'yellow'));
            CLI::write('  Rol      : ' . $rolNombre);
            CLI::write('');
        }
    }
}
