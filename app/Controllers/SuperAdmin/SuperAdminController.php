<?php

namespace App\Controllers\SuperAdmin;

use CodeIgniter\Controller;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
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
        $usuarioModel = new \App\Models\UsuarioModel();
        $rolModel = new \App\Models\RolModel();
        $estadisticas = $usuarioModel->getEstadisticasUsuarios();
        
        $data = [
            'title' => 'Gestión de Usuarios',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'usuarios' => $usuarioModel->getUsuariosConEmpleado(),
            'roles' => $rolModel->getRolesActivos(),
            'totalUsuarios' => $estadisticas->total_usuarios ?? 0,
            'usuariosActivos' => $estadisticas->usuarios_activos ?? 0,
            'usuariosInactivos' => $estadisticas->usuarios_inactivos ?? 0,
            'usuariosConAcceso' => $estadisticas->usuarios_con_acceso ?? 0
        ];
        
        return view('Roles/SuperAdministrador/usuarios', $data);
    }

    public function roles()
    {
        $rolModel = new \App\Models\RolModel();
        
        // Obtener roles con estadísticas
        $roles = $rolModel->getRolesConEstadisticas();
        
        // Calcular estadísticas generales
        $totalRoles = count($roles);
        $usuariosActivos = array_sum(array_column($roles, 'total_usuarios'));
        $rolMasUsado = $totalRoles > 0 ? $roles[0]['nombre_rol'] : 'N/A';
        $rolesSinUsuarios = count(array_filter($roles, function($r) { return $r['total_usuarios'] == 0; }));
        
        $estadisticas = [
            'total_roles' => $totalRoles,
            'usuarios_activos' => $usuariosActivos,
            'rol_mas_usado' => $rolMasUsado,
            'roles_sin_usuarios' => $rolesSinUsuarios
        ];
        
        $data = [
            'title' => 'Gestión de Roles - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'roles' => $roles,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/SuperAdministrador/roles', $data);
    }

    public function configuracion()
    {
        $data = [
            'title' => 'Configuración del Sistema',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/configuracion', $data);
    }

    public function respaldos()
    {
        $data = [
            'title' => 'Gestión de Respaldos',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/respaldos', $data);
    }

    public function logs()
    {
        $data = [
            'title' => 'Registros del Sistema',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/logs', $data);
    }

    public function estadisticas()
    {
        $data = [
            'title' => 'Estadísticas del Sistema',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/estadisticas', $data);
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
        
        return view('Roles/SuperAdministrador/perfil', $data);
    }

    public function cuenta()
    {
        $data = [
            'title' => 'Configuración de Cuenta',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/cuenta', $data);
    }
}
