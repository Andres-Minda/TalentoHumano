<?php
$dashboard = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/dashboard.php');
$startIdx = strpos($dashboard, '<div class="modal fade" id="modalVerPerfil"');
if ($startIdx !== false) {
    // Find the end of this modal by counting divs. Or simple string split.
    $endIdx = strpos($dashboard, '<!-- Fin Modal Ver Perfil -->'); 
    if ($endIdx !== false) {
        $modalHTML = substr($dashboard, $startIdx, $endIdx - $startIdx + 30);
        $index = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php');
        if (strpos($index, 'id="modalVerPerfil"') === false) {
            $index = str_replace('<!-- Modal Ver Detalles -->', $modalHTML . "\n<!-- Modal Ver Detalles -->", $index);
            file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php', $index);
            echo "Modal added to index.php. ";
        }
    }
}

$scriptFetch = <<<JS
    const botonesVerPerfilIndex = document.querySelectorAll('.btn-ver-perfil');
    botonesVerPerfilIndex.forEach(btn => {
        btn.addEventListener('click', function() {
            const empleadoId = this.getAttribute('data-id');
            const url = `<?= site_url('admin-th/inasistencias/perfil-empleado/') ?>\${empleadoId}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('perfil-nombre').textContent = data.nombre_completo || 'N/A';
                        document.getElementById('perfil-cedula').textContent = data.cedula || 'N/A';
                        document.getElementById('perfil-tipo').textContent = data.tipo_empleado || 'N/A';
                        document.getElementById('perfil-departamento').textContent = data.departamento || 'N/A';
                        document.getElementById('perfil-correo').textContent = data.correo || 'N/A';
                        document.getElementById('perfil-telefono').textContent = data.telefono || 'N/A';
                        document.getElementById('perfil-fecha').textContent = data.fecha_contratacion || 'N/A';
                        
                        const myModal = new bootstrap.Modal(document.getElementById('modalVerPerfil'));
                        myModal.show();
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo cargar el perfil', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error fetching profile:', error);
                    Swal.fire('Error', 'Problema de conexión', 'error');
                });
        });
    });
JS;

$index = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php');
if (strpos($index, 'botonesVerPerfilIndex') === false) {
    $index = str_replace('</script>', $scriptFetch . "\n</script>", $index);
    file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php', $index);
    echo "Script added.";
}

