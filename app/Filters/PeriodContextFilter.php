<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PeriodContextFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Solo aplica si el usuario está autenticado
        if (!session()->get('isLoggedIn')) {
            return;
        }

        $db = \Config\Database::connect();
        $hoy = date('Y-m-d');

        // 1. Automatización: Verificación general y cierre automático del periodo activo
        $periodoActivo = $db->table('periodos_academicos')->where('estado', 'Activo')->get()->getRowArray();
        
        if ($periodoActivo && $periodoActivo['fecha_fin'] < $hoy) {
            // El periodo activo ya expiró -> Cambiar a 'Cerrado'
            $db->table('periodos_academicos')
               ->where('id_periodo', $periodoActivo['id_periodo'])
               ->update(['estado' => 'Cerrado']);
               
            // Buscar el siguiente periodo planificado (Inactivo) pero que ya debería iniciar
            $siguiente = $db->table('periodos_academicos')
                ->where('estado', 'Inactivo')
                ->where('fecha_inicio <=', $hoy)
                ->orderBy('fecha_inicio', 'ASC')
                ->get()->getRowArray();
                
            if ($siguiente) {
                $db->table('periodos_academicos')
                   ->where('id_periodo', $siguiente['id_periodo'])
                   ->update(['estado' => 'Activo']);
                $periodoActivo = $siguiente;
            } else {
                $periodoActivo = null; // No hay activo
            }
        }

        // 2. Asignación de contexto a la sesión si no lo tiene
        if (!session()->has('id_periodo')) {
            if ($periodoActivo) {
                session()->set([
                    'id_periodo'       => $periodoActivo['id_periodo'],
                    'periodo_nombre'   => $periodoActivo['nombre'],
                    'periodo_readonly' => false
                ]);
            } else {
                // Si no hay ninguno Activo, intenta agarrar el último creado
                $ultimo = $db->table('periodos_academicos')->orderBy('id_periodo', 'DESC')->get()->getRowArray();
                if ($ultimo) {
                    session()->set([
                        'id_periodo'       => $ultimo['id_periodo'],
                        'periodo_nombre'   => $ultimo['nombre'],
                        'periodo_readonly' => ($ultimo['estado'] === 'Cerrado' || $ultimo['fecha_fin'] < $hoy)
                    ]);
                }
            }
        }
        
        // 3. Forzar re-verificación de read-only si el estado en BD cambió
        if (session()->has('id_periodo')) {
            $context = $db->table('periodos_academicos')
                          ->where('id_periodo', session()->get('id_periodo'))
                          ->get()->getRowArray();
                          
            if ($context) {
                $isReadOnly = ($context['estado'] === 'Cerrado' || $context['fecha_fin'] < $hoy);
                
                // Actualizar sesión si el contexto cambió desde la última vez (ej. otro admin lo cerró)
                if (session()->get('periodo_readonly') !== $isReadOnly || session()->get('periodo_nombre') !== $context['nombre']) {
                    session()->set([
                        'periodo_nombre'   => $context['nombre'],
                        'periodo_readonly' => $isReadOnly
                    ]);
                }
            }
        }
        
        // Hacer que los periodos estén disponibles globalmente para la vista superior (navbar)
        $todosLosPeriodos = $db->table('periodos_academicos')
                               ->orderBy('fecha_inicio', 'DESC')
                               ->get()->getResultArray();
                               
        // Al inyectarlo en views global access properties, no funciona tan fácil desde el filter a veces sin service
        // Lo guardaremos temporalmente o lo inyectamos de otra manera en el layout.
        // Un helper "get_all_periodos()" puede ser útil, o en Controller.
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
