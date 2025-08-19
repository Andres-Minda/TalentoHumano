<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Verifica que el usuario tenga el rol requerido
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si el usuario está logueado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Si no se especifican roles, permitir acceso
        if (empty($arguments)) {
            return;
        }
        
        // Obtener el rol del usuario
        $userRole = session()->get('id_rol');
        $userRoleName = session()->get('nombre_rol');
        
        // Verificar si el usuario tiene alguno de los roles requeridos
        foreach ($arguments as $requiredRole) {
            if ($this->checkRole($userRole, $userRoleName, $requiredRole)) {
                return; // Usuario tiene el rol requerido
            }
        }
        
        // Usuario no tiene el rol requerido
        return redirect()->to('/error/403')->with('error', 'No tiene permisos para acceder a esta página.');
    }

    /**
     * Verifica si el usuario tiene el rol requerido
     */
    private function checkRole($userRole, $userRoleName, $requiredRole)
    {
        // Mapeo de roles por ID
        $roleMap = [
            1 => 'SUPER_ADMIN',
            2 => 'ADMIN_TH',
            3 => 'DOCENTE',
            4 => 'ADMINISTRATIVO',
            5 => 'DIRECTIVO',
            6 => 'AUXILIAR'
        ];
        
        // Verificar por ID de rol
        if (isset($roleMap[$userRole]) && $roleMap[$userRole] === $requiredRole) {
            return true;
        }
        
        // Verificar por nombre de rol
        if ($userRoleName === $requiredRole) {
            return true;
        }
        
        return false;
    }

    /**
     * No necesitamos hacer nada después de la respuesta
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después de la respuesta
    }
}
