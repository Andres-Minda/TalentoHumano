// Funciones básicas para el sistema de Talento Humano

// Inicializar DataTables
function initDataTables() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 25
        });
    }
}

// Inicializar tooltips de Bootstrap
function initTooltips() {
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// Inicializar modales de Bootstrap
function initModals() {
    if (typeof bootstrap !== 'undefined') {
        var modalTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="modal"]'));
        var modalList = modalTriggerList.map(function (modalTriggerEl) {
            return new bootstrap.Modal(modalTriggerEl);
        });
    }
}

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: type === 'success' ? '¡Éxito!' : type === 'error' ? 'Error' : 'Información',
            text: message,
            icon: type,
            confirmButtonText: 'Aceptar'
        });
    } else {
        alert(message);
    }
}

// Función para confirmar acciones
function confirmAction(message, callback) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Estás seguro?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    } else {
        if (confirm(message)) {
            callback();
        }
    }
}

// Función para formatear fechas
function formatDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Función para formatear moneda
function formatCurrency(amount) {
    if (amount === null || amount === undefined) return '$0.00';
    
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Función para validar formularios
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Función para limpiar formularios
function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(input => {
            input.classList.remove('is-invalid');
        });
    }
}

// Función para hacer peticiones AJAX
function makeRequest(url, method = 'GET', data = null, callback = null) {
    const xhr = new XMLHttpRequest();
    
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (callback) callback(response, null);
                } catch (e) {
                    if (callback) callback(null, 'Error al procesar la respuesta');
                }
            } else {
                if (callback) callback(null, 'Error en la petición: ' + xhr.status);
            }
        }
    };
    
    if (data && method !== 'GET') {
        xhr.send(JSON.stringify(data));
    } else {
        xhr.send();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initDataTables();
    initTooltips();
    initModals();
    
    // Agregar listeners para formularios
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this.id)) {
                e.preventDefault();
                showNotification('Por favor, complete todos los campos requeridos', 'error');
            }
        });
    });
    
    // Agregar listeners para botones de confirmación
    document.querySelectorAll('[data-confirm]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.getAttribute('data-confirm');
            const action = this.getAttribute('data-action');
            
            confirmAction(message, function() {
                if (action === 'submit') {
                    this.closest('form').submit();
                } else if (action === 'href') {
                    window.location.href = this.getAttribute('href');
                }
            }.bind(this));
        });
    });
});

// Exportar funciones para uso global
window.TalentoHumano = {
    showNotification,
    confirmAction,
    formatDate,
    formatCurrency,
    validateForm,
    clearForm,
    makeRequest
};
