<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AdminTHController extends Controller
{
    public function __construct()
    {
        // Verificar que el usuario esté logueado y tenga el rol correcto
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 2) {
            return redirect()->to('/access-denied');
        }
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard - Administrador Talento Humano',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/AdminTalentoHumano/dashboard', $data);
    }

    public function empleados()
    {
        $data = [
            'title' => 'Gestión de Empleados',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/AdminTalentoHumano/empleados', $data);
    }

    public function nominas()
    {
        $data = [
            'title' => 'Gestión de Nóminas',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/AdminTalentoHumano/nominas', $data);
    }

    public function evaluaciones()
    {
        $data = [
            'title' => 'Gestión de Evaluaciones',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/AdminTalentoHumano/evaluaciones', $data);
    }
} 