<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ReadOnlyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Validar métodos que modifican estado
        $metodosModificacion = ['post', 'put', 'delete', 'patch'];
        
        if (in_array(strtolower($request->getMethod()), $metodosModificacion)) {
            
            // 2. Revisar si estamos en modo read-only
            if (session()->get('periodo_readonly') === true) {
                
                // 3. Dejar pasar ciertas rutas (ej. auth, password) - Exclusión
                $path = current_url(true)->getPath();
                
                $exclusiones = [
                    'auth', 
                    'login',
                    'logout',
                    'cambiar-contrasena',
                    'periodo/switch',
                    'evaluacion-estudiantil', // pública
                    'postulacion'             // pública
                ];
                
                foreach ($exclusiones as $ex) {
                    if (strpos($path, $ex) !== false) {
                        return; // Dejar pasar
                    }
                }
                
                // 4. Bloquear y responder
                if ($request->isAJAX()) {
                    return \Config\Services::response()->setJSON([
                        'success' => false,
                        'message' => 'No puedes modificar datos de un periodo histórico cerrado.',
                        'error'   => 'READ_ONLY_MODE_ACTIVE'
                    ])->setStatusCode(403);
                } else {
                    return redirect()->back()->with('error', 'Acción denegada: Sistema en modo de solo lectura para el periodo histórico seleccionado.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
