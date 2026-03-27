<?php

if (!function_exists('is_readonly')) {
    /**
     * Verifica si el sistema está en modo de "solo lectura" para el usuario actual.
     * Esto ocurre cuando el periodo académico seleccionado en sesión está Cerrado
     * o expirado.
     *
     * @return bool
     */
    function is_readonly()
    {
        return session()->get('periodo_readonly') === true;
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
     * Útil par filtrar consultas (Data Scoping).
     *
     * @return int|null
     */
    function id_periodo_actual()
    {
        return session()->get('id_periodo');
    }
}
