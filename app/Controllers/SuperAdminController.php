<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // Verificar si el usuario está logueado y es super admin
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('id_rol') != 1) { // 1 = SUPER_ADMIN
            return redirect()->to('/error/403');
        }

        $data = [
            'titulo' => 'Dashboard Super Administrador',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('super_admin/dashboard', $data);
    }

    public function usuarios()
    {
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 1) {
            return redirect()->to('/login');
        }

        return view('super_admin/usuarios', ['titulo' => 'Gestión de Usuarios']);
    }

    public function backup()
    {
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 1) {
            return redirect()->to('/login');
        }

        return view('super_admin/backup', ['titulo' => 'Backup del Sistema']);
    }

    public function configuracionSistema()
    {
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 1) {
            return redirect()->to('/login');
        }

        return view('super_admin/configuracion', ['titulo' => 'Configuración del Sistema']);
    }
} 