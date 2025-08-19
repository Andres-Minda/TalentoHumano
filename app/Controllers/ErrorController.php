<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ErrorController extends Controller
{
    public function accessDenied()
    {
        return view('errors/403', ['titulo' => 'Acceso Denegado']);
    }

    public function notFound()
    {
        return view('errors/404', ['titulo' => 'Página No Encontrada']);
    }

    public function serverError()
    {
        return view('errors/500', ['titulo' => 'Error del Servidor']);
    }
}
