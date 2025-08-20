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
        log_message('info', 'RoleFilter - INICIANDO FILTRO para URL: ' . $request->getUri()->getPath());
        
        // Verificar si el usuario está logueado
        if (!session()->get('isLoggedIn')) {
            log_message('warning', 'RoleFilter - Usuario no logueado, redirigiendo a login');
            return redirect()->to('/login');
        }
        
        // Si no se especifican roles, permitir acceso
        if (empty($arguments)) {
            log_message('info', 'RoleFilter - No se especificaron roles, acceso permitido');
            return;
        }
        
        // Obtener el rol del usuario
        $userRoleId = session()->get('id_rol');
        $userRoleName = session()->get('nombre_rol');
        
        // Debug: mostrar información del usuario
        log_message('info', 'RoleFilter - User ID: ' . $userRoleId . ', Role Name: ' . $userRoleName);
        log_message('info', 'RoleFilter - Required roles: ' . implode(', ', $arguments));
        
        // Verificar si el usuario tiene alguno de los roles requeridos
        foreach ($arguments as $requiredRole) {
            log_message('info', 'RoleFilter - Verificando rol requerido: ' . $requiredRole);
            $roleCheck = $this->checkRole($userRoleId, $userRoleName, $requiredRole);
            log_message('info', 'RoleFilter - Resultado de verificación: ' . $requiredRole . ' = ' . ($roleCheck ? 'PERMITIDO' : 'DENEGADO'));
            
            if ($roleCheck) {
                log_message('info', 'RoleFilter - Access granted for role: ' . $requiredRole);
                return; // Usuario tiene el rol requerido
            }
        }
        
        log_message('warning', 'RoleFilter - Access denied for user ID: ' . $userRoleId . ', required: ' . implode(', ', $arguments));
        
        // Usuario no tiene el rol requerido - redirigir a página de acceso denegado
        return redirect()->to('/index.php/error/403')->with('error', 'No tiene permisos para acceder a esta página.');
    }

    /**
     * Verifica si el usuario tiene el rol requerido
     */
    private function checkRole($userRoleId, $userRoleName, $requiredRole)
    {
        log_message('info', 'RoleFilter - checkRole: userRoleId=' . $userRoleId . ', userRoleName=' . $userRoleName . ', requiredRole=' . $requiredRole);
        
        // Si se especifica un ID de rol
        if (is_numeric($requiredRole)) {
            $result = $userRoleId == $requiredRole;
            log_message('info', 'RoleFilter - Comparando ID: ' . $userRoleId . ' == ' . $requiredRole . ' = ' . ($result ? 'true' : 'false'));
            return $result;
        }
        
        // Si se especifica el nombre exacto del rol del usuario
        if (strtoupper($userRoleName) == strtoupper($requiredRole)) {
            log_message('info', 'RoleFilter - Nombre exacto coincide: ' . $userRoleName . ' == ' . $requiredRole);
            return true;
        }
        
        // Mapeo de roles por nombre (para compatibilidad)
        $roleMap = [
            'SUPER_ADMIN' => 'SuperAdministrador',
            'ADMIN_TH' => 'AdministradorTalentoHumano',
            'EMPLEADO' => 'Docente',
            'DOCENTE' => 'Docente', // Agregar mapeo directo
            'ADMINISTRATIVO' => 'ADMINISTRATIVO',
            'DIRECTIVO' => 'DIRECTIVO',
            'AUXILIAR' => 'AUXILIAR'
        ];
        
        // Si se especifica un nombre de rol mapeado
        if (isset($roleMap[strtoupper($requiredRole)])) {
            $mappedRole = $roleMap[strtoupper($requiredRole)];
            $result = strtoupper($userRoleName) == strtoupper($mappedRole);
            log_message('info', 'RoleFilter - Mapeo: ' . $requiredRole . ' -> ' . $mappedRole . ' = ' . ($result ? 'true' : 'false'));
            return $result;
        }
        
        log_message('info', 'RoleFilter - No se encontró coincidencia para: ' . $requiredRole);
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
