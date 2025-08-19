<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si el usuario está logueado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Verificar si la sesión no ha expirado (opcional)
        if (session()->get('login_time') && (time() - session()->get('login_time')) > 3600) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.');
        }
    }

    /**
     * We don't have anything to do here.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después de la respuesta
    }
}
