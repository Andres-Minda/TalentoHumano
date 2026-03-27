<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PeriodoController extends Controller
{
    /**
     * Cambia el periodo académico en la sesión del usuario.
     * Es ejecutado cuando el usuario elige un periodo diferente 
     * en el dropdown del navbar superior.
     *
     * @param int $idPeriodo
     */
    public function switchPeriod($idPeriodo = null)
    {
        if (!$idPeriodo) {
            return redirect()->back()->with('error', 'Periodo no especificado.');
        }

        // Si el usuario no está logueado, no hace nada
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        
        $periodo = $db->table('periodos_academicos')
                      ->where('id_periodo', $idPeriodo)
                      ->get()->getRowArray();
                      
        if (!$periodo) {
            return redirect()->back()->with('error', 'El periodo académico especificado no existe.');
        }

        // Determinar si es Solo Lectura (Cerrado, Inactivo o Expirado)
        $hoy = date('Y-m-d');
        $isReadOnly = ($periodo['estado'] === 'Cerrado' || $periodo['estado'] === 'Inactivo' || $periodo['fecha_fin'] < $hoy);

        // Guardar variables de contexto en sesión
        session()->set([
            'id_periodo'       => $periodo['id_periodo'],
            'periodo_nombre'   => $periodo['nombre'],
            'periodo_readonly' => $isReadOnly,
            'readonly_mode'    => $isReadOnly // Modo solicitado por el usuario
        ]);

        // Registrar en logs quien cambió el periodo por auditoría (opcional)
        log_message('info', 'Usuario ID ' . session()->get('id_usuario') . ' cambió contexto al periodo: ' . $periodo['nombre']);

        // Logica de redirección
        if ($periodo['estado'] === 'Activo' && !$isReadOnly) {
            return redirect()->back()->with('success', 'Contexto cambiado a: ' . $periodo['nombre']);
        } else {
            // Si es inactivo o cerrado, limpia 404/500 previos redirigiendo al dashboard
            session()->setFlashdata('warning', 'Ha ingresado en Modo Solo Lectura para el periodo ' . $periodo['nombre']);
            
            // Redirección dependiendo del rol
            if (session()->get('id_rol') == 2) {
                return redirect()->to('admin-th/dashboard');
            } else {
                return redirect()->to('empleado/dashboard');
            }
        }
    }
}
