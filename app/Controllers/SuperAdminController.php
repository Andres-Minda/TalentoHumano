<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        // Verificar que el usuario esté logueado y tenga el rol correcto
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 1) {
            return redirect()->to('/access-denied');
        }
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/dashboard', $data);
    }

    public function usuarios()
    {
        $data = [
            'title' => 'Gestión de Usuarios',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/usuarios', $data);
    }

    public function roles()
    {
        $data = [
            'title' => 'Gestión de Roles',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/roles', $data);
    }
} 