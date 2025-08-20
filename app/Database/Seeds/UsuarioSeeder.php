<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Primero insertar roles si no existen
        $roles = [
            [
                'id_rol' => 1,
                'nombre_rol' => 'SUPER_ADMIN',
                'descripcion' => 'Super Administrador del Sistema',
                'activo' => 1
            ],
            [
                'id_rol' => 2,
                'nombre_rol' => 'ADMIN_TH',
                'descripcion' => 'Administrador de Talento Humano',
                'activo' => 1
            ],
            [
                'id_rol' => 3,
                'nombre_rol' => 'DOCENTE',
                'descripcion' => 'Docente del Instituto',
                'activo' => 1
            ]
        ];
        
        foreach ($roles as $rol) {
            $db->table('roles')->insert($rol);
        }
        
        // Insertar usuarios de prueba
        $usuarios = [
            [
                'cedula' => '1234567890',
                'email' => 'superadmin@instituto.edu.ec',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'id_rol' => 1,
                'activo' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'cedula' => '0987654321',
                'email' => 'adminth@instituto.edu.ec',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'id_rol' => 2,
                'activo' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'cedula' => '1122334455',
                'email' => 'docente@instituto.edu.ec',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'id_rol' => 3,
                'activo' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($usuarios as $usuario) {
            $db->table('usuarios')->insert($usuario);
        }
        
        echo "✅ Usuarios de prueba creados exitosamente!\n";
        echo "📋 Credenciales de acceso:\n\n";
        echo "🔑 SUPER ADMIN:\n";
        echo "   Usuario: superadmin@instituto.edu.ec\n";
        echo "   Contraseña: admin123\n\n";
        echo "🔑 ADMIN TH:\n";
        echo "   Usuario: adminth@instituto.edu.ec\n";
        echo "   Contraseña: admin123\n\n";
        echo "🔑 DOCENTE:\n";
        echo "   Usuario: docente@instituto.edu.ec\n";
        echo "   Contraseña: admin123\n\n";
    }
}
