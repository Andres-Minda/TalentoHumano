<?php
$dashboard = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/dashboard.php');

$startIdx = strpos($dashboard, '<!-- Modal Ver Perfil de Empleado -->');
$endIdx = strpos($dashboard, '</div>', strpos($dashboard, '<!-- Fin Modal -->', $startIdx));
if ($endIdx === false) {
    // If Fin Modal is not there, just grab 1000 characters or find <script>
    $endIdx = strpos($dashboard, '<script', $startIdx);
}

if ($startIdx !== false && $endIdx !== false) {
    $modalHTML = substr($dashboard, $startIdx, $endIdx - $startIdx);
    $index = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php');
    if (strpos($index, 'id="modalPerfilEmpleado"') === false) {
        $index = str_replace('<?= $this->endSection() ?>', $modalHTML . "\n<?= \$this->endSection() ?>", $index);
        file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php', $index);
        echo "Modal added to index.php. ";
    }
}

$script = <<<JS
    // Lógica AJAX para Ver Perfil de Empleado
    const botonesVerPerfil = document.querySelectorAll('.btn-ver-perfil');
    botonesVerPerfil.forEach(btn => {
        btn.addEventListener('click', function() {
            const empleadoId = this.getAttribute('data-id');
            const tooltip = bootstrap.Tooltip.getInstance(this);
            if(tooltip) tooltip.hide();
            
            // Mostrar modal y spinner
            const modal = new bootstrap.Modal(document.getElementById('modalPerfilEmpleado'));
            const contenido = document.getElementById('contenidoPerfil');
            contenido.innerHTML = '<div class="text-center py-4 text-primary"><div class="spinner-border" role="status"></div><p class="mt-2">Cargando datos...</p></div>';
            modal.show();
            
            fetch(`<?= site_url('admin-th/inasistencias/perfil-empleado/') ?>\${empleadoId}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        contenido.innerHTML = `
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item"><strong>Nombre:</strong> \${data.nombre_completo}</li>
                                <li class="list-group-item"><strong>Cédula:</strong> \${data.cedula ?? 'N/A'}</li>
                                <li class="list-group-item"><strong>Departamento:</strong> \${data.departamento ?? 'N/A'}</li>
                                <li class="list-group-item"><strong>Cargo / Rol:</strong> \${data.tipo_empleado ?? 'N/A'}</li>
                                <li class="list-group-item"><strong>Email:</strong> <a href="mailto:\${data.correo}">\${data.correo ?? 'N/A'}</a></li>
                                <li class="list-group-item"><strong>Teléfono:</strong> <a href="tel:\${data.telefono}">\${data.telefono ?? 'N/A'}</a></li>
                                <li class="list-group-item"><strong>Contratación:</strong> \${data.fecha_contratacion ?? 'N/A'}</li>
                            </ul>
                            <div class="text-center">
                                <a href="<?= site_url('admin-th/empleados/editar/') ?>\${empleadoId}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i>Gestionar Perfil Completo</a>
                            </div>
                        `;
                    } else {
                        contenido.innerHTML = `<div class="alert alert-danger">\${data.message || 'No se encontró el empleado.'}</div>`;
                    }
                })
                .catch(err => {
                    contenido.innerHTML = '<div class="alert alert-danger">Error de conexión con el servidor.</div>';
                });
        });
    });
JS;

$index = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php');
if (strpos($index, 'botonesVerPerfil.forEach') === false) {
    if (preg_match('/function eliminarInasistencia.*?}/s', $index, $mat)) {
        $index = str_replace($mat[0], $mat[0] . "\n\n" . $script, $index);
        file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php', $index);
        echo "Script added.";
    }
}
