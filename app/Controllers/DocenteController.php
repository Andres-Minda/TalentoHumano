<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DocenteController extends Controller
{
    public function __construct()
    {
        // Verificar que el usuario esté logueado y tenga el rol correcto
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 3) {
            return redirect()->to('/access-denied');
        }
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/Docente/dashboard', $data);
    }

    public function perfil()
    {
        $data = [
            'title' => 'Mi Perfil',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/Docente/perfil', $data);
    }

    public function capacitaciones()
    {
        $data = [
            'title' => 'Mis Capacitaciones',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/Docente/capacitaciones', $data);
    }
} 