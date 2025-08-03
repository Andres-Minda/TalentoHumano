<?php
// views/modules/payrolls.php

// Vista para la sección de Nóminas.
// Aquí se procesarían y gestionarían los pagos a empleados.
?>

<h2 class="section-title">Gestión de Nóminas</h2>

<p>Administra los procesos de pago, generación de recibos y detalles salariales de los empleados.</p>

<div class="flex justify-end mb-4">
    <button class="btn btn-primary">
        <i class="fas fa-dollar-sign"></i> Generar Nómina
    </button>
</div>

<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Nómina</th>
                <th>Período</th>
                <th>Fecha Generación</th>
                <th>Fecha Pago</th>
                <th>Estado</th>
                <th class="actions">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de fila de datos (se llenaría con PHP desde la DB) -->
            <tr>
                <td>1</td>
                <td>Junio 2025</td>
                <td>2025-06-25</td>
                <td>2025-06-30</td>
                <td>Generada</td>
                <td class="actions">
                    <a href="#" title="Ver Detalles"><i class="fas fa-info-circle"></i></a>
                    <a href="#" title="Marcar como Pagada"><i class="fas fa-check-circle"></i></a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Mayo 2025</td>
                <td>2025-05-25</td>
                <td>2025-05-30</td>
                <td>Pagada</td>
                <td class="actions">
                    <a href="#" title="Ver Detalles"><i class="fas fa-info-circle"></i></a>
                </td>
            </tr>
            <!-- Más filas... -->
        </tbody>
    </table>
</div>

<p class="info-message mt-4">Próximamente: Detalle de nóminas, cálculos y reportes financieros.</p>
