<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ReadOnlyFilter implements FilterInterface
{
    /**
     * Aplica el filtro de Solo Lectura si el contexto del periodo es histórico.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cargar helper de periodo para tener acceso a is_periodo_historico()
        helper('periodo');

        // 1. Verificar si estamos visualizando un periodo histórico
        if (is_periodo_historico()) {
            
            // 2. Si el método HTTP no es GET (es decir, es POST, PUT, DELETE, PATCH, etc.)
            $metodo = strtolower($request->getMethod());
            if ($metodo !== 'get') {
                
                // 3. Obtener la ruta de la petición
                $path = current_url(true)->getPath();
                
                // 4. Excepciones: Rutas que contienen 'reporte', 'exportar' o 'descargar'
                // También debemos permitir el cambio de periodo en sí ('cambiar-periodo' o 'switch')
                $excepciones = ['reporte', 'exportar', 'descargar', 'cambiar-periodo', 'switch'];
                
                foreach ($excepciones as $exc) {
                    if (stripos($path, $exc) !== false) {
                        return; // Permitir continuar la ejecución
                    }
                }
                
                // 5. Bloquear y retornar redirect o JSON (si es AJAX)
                if ($request->isAJAX()) {
                    return \Config\Services::response()->setJSON([
                        'success' => false,
                        'message' => 'Estás visualizando un periodo histórico. No se pueden modificar registros.',
                        'error'   => 'READ_ONLY_MODE_ACTIVE'
                    ])->setStatusCode(403);
                } else {
                    return redirect()->back()->with('error', 'Estás visualizando un periodo histórico. No se pueden modificar registros.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada
    }
}
