<?php

use App\Models\PeriodoAcademicoModel;

if (!function_exists('get_periodo_contexto')) {
    /**
     * Retorna el ID del periodo seleccionado en sesión.
     * Si no hay ninguno en sesión, busca el periodo actual vigente en la base de datos y lo retorna.
     *
     * @return int|null
     */
    function get_periodo_contexto()
    {
        $session = session();
        if ($session->has('periodo_contexto_id')) {
            return $session->get('periodo_contexto_id');
        }

        $model = new PeriodoAcademicoModel();
        $periodoActual = $model->getPeriodoActual();
        
        if ($periodoActual) {
            $id = (int)$periodoActual['id_periodo'];
            // Guardar en sesión para futuras peticiones
            $session->set('periodo_contexto_id', $id);
            $session->set('periodo_nombre', $periodoActual['nombre']);
            $session->set('periodo_readonly', false);
            return $id;
        }

        return null;
    }
}

if (!function_exists('is_periodo_historico')) {
    /**
     * Compara el ID en sesión con el ID del periodo actual real en la BD.
     * Si son diferentes, retorna TRUE (indicando que es histórico/solo lectura).
     *
     * @return bool
     */
    function is_periodo_historico()
    {
        $idSesion = get_periodo_contexto();
        if (!$idSesion) {
            return false;
        }

        $model = new PeriodoAcademicoModel();
        $periodoActual = $model->getPeriodoActual();

        if (!$periodoActual) {
            return false;
        }

        return (int)$idSesion !== (int)$periodoActual['id_periodo'];
    }
}

if (!function_exists('is_readonly')) {
    /**
     * Verifica si el sistema está en modo de "solo lectura" para el usuario actual.
     *
     * @return bool
     */
    function is_readonly()
    {
        return is_periodo_historico();
    }
}

if (!function_exists('periodo_actual_nombre')) {
    /**
     * Retorna el nombre del periodo académico que está en contexto.
     *
     * @return string
     */
    function periodo_actual_nombre()
    {
        return session()->get('periodo_nombre') ?? 'Sin periodo seleccionado';
    }
}

if (!function_exists('id_periodo_actual')) {
    /**
     * Retorna el ID del periodo académico seleccionado en sesión.
     *
     * @return int|null
     */
    function id_periodo_actual()
    {
        return get_periodo_contexto();
    }
}
