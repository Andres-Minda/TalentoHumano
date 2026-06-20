<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PeriodoAcademicoModel;

class PeriodoController extends Controller
{
    /**
     * Cambia el periodo académico en la sesión del usuario.
     * Guarda el ID en 'periodo_contexto_id' para que sea consumido por el helper y el filtro.
     *
     * @param int $id_periodo
     */
    public function cambiarPeriodo($id_periodo = null)
    {
        if (!$id_periodo) {
            return redirect()->back()->with('error', 'Periodo no especificado.');
        }

        // Si el usuario no está logueado, redirigir a login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new PeriodoAcademicoModel();
        $periodo = $model->find($id_periodo);

        if (!$periodo) {
            return redirect()->back()->with('error', 'El periodo académico especificado no existe.');
        }

        // Obtener el periodo actual (activo en BD)
        $periodoActual = $model->getPeriodoActual();
        $isReadOnly = true;

        if ($periodoActual && (int)$periodo['id_periodo'] === (int)$periodoActual['id_periodo']) {
            $isReadOnly = false;
        }

        // Guardar variables de contexto en sesión
        session()->set([
            'periodo_contexto_id' => (int)$periodo['id_periodo'],
            'id_periodo'          => (int)$periodo['id_periodo'], // Para compatibilidad
            'periodo_nombre'      => $periodo['nombre'],
            'periodo_readonly'    => $isReadOnly
        ]);

        log_message('info', 'Usuario ID ' . session()->get('id_usuario') . ' cambió contexto al periodo (cambiarPeriodo): ' . $periodo['nombre']);

        if (!$isReadOnly) {
            return redirect()->back()->with('success', 'Contexto cambiado a: ' . $periodo['nombre']);
        } else {
            session()->setFlashdata('warning', 'Ha ingresado en Modo Solo Lectura para el periodo ' . $periodo['nombre']);
            
            // Redirección dependiendo del rol
            if ((int)session()->get('id_rol') === 2) {
                return redirect()->to('admin-th/dashboard');
            } else {
                return redirect()->to('empleado/dashboard');
            }
        }
    }

    /**
     * Mantiene la compatibilidad con enlaces antiguos.
     */
    public function switchPeriod($idPeriodo = null)
    {
        return $this->cambiarPeriodo($idPeriodo);
    }
}
