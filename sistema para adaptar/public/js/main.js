// public/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    console.log('--- main.js: Documento DOM cargado. Inicializando listeners. ---');

    const contentArea = document.getElementById('content-area');
    const loaderSpinner = document.getElementById('loader-spinner');
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');

    /**
     * Función para mostrar el spinner de carga.
     */
    function showLoader() {
        if (loaderSpinner) {
            loaderSpinner.style.display = 'block';
            contentArea.style.opacity = '0.5'; // Opcional: difuminar el contenido actual
            console.log('Loader: Mostrando spinner.');
        }
    }

    /**
     * Función para ocultar el spinner de carga.
     */
    function hideLoader() {
        if (loaderSpinner) {
            loaderSpinner.style.display = 'none';
            contentArea.style.opacity = '1';
            console.log('Loader: Ocultando spinner.');
        }
    }

    /**
     * Carga el contenido dinámicamente usando AJAX en el #content-area.
     * Esta función es global (window.loadContent) para ser accesible desde cualquier script cargado.
     * @param {string} url La URL del contenido a cargar.
     */
    window.loadContent = function(url) {
        console.log(`Cargando contenido por AJAX desde: ${url}`);
        showLoader();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Indica que es una solicitud AJAX
            }
        })
        .then(response => {
            console.log(`Respuesta recibida para ${url}. Estado: ${response.status}`);
            if (!response.ok) {
                // Si la respuesta no es OK (ej. 404, 500), leemos el texto para incluirlo en el error
                return response.text().then(text => {
                    throw new Error(`HTTP error! Estado: ${response.status}. Respuesta: ${text}`);
                });
            }
            return response.text();
        })
        .then(html => {
            contentArea.innerHTML = html; // Inserta el HTML en el área de contenido
            console.log(`Contenido cargado exitosamente desde ${url}.`);
        })
        .catch(error => {
            console.error('Error al cargar el contenido:', error);
            contentArea.innerHTML = `<p class="error-message">Error al cargar la sección. Por favor, revisa la consola del navegador para más detalles. Mensaje: ${error.message}</p>`;
        })
        .finally(() => {
            hideLoader(); // Asegura que el loader siempre se oculte, incluso en caso de error
            // Después de cargar nuevo contenido (incluyendo formularios o tablas),
            // es CRÍTICO re-adjuntar los listeners a los nuevos elementos del DOM.
            window.attachAllEventListeners();
        });
    };

    /**
     * Manejador de eventos para enlaces que cargan contenido (e.g., sidebar links, "Añadir Empleado" button).
     * Esta función es global para ser re-adjuntable.
     */
    window.handleSidebarLinkClick = function(event) {
        event.preventDefault(); // Previene la recarga completa de la página
        const path = this.getAttribute('data-path'); // Obtiene la ruta del atributo data-path
        if (path) {
            const url = `index.php?${path}`; // Construye la URL para la solicitud AJAX
            window.loadContent(url);
        } else {
            console.warn('handleSidebarLinkClick: Elemento sin data-path definido:', this);
        }
    };

    /**
     * Manejador de eventos para el envío de formularios por AJAX.
     * Esta función es global para ser re-adjuntable y para delegación.
     */
    window.handleFormSubmit = function(event) {
        // Usa `event.target.matches` para asegurar que solo se activa para formularios con la clase `ajax-form`
        if (event.target.matches('form.ajax-form')) {
            event.preventDefault();
            console.log('handleFormSubmit: Formulario AJAX interceptado:', event.target.action);

            const form = event.target;
            const formData = new FormData(form);

            showLoader();

            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Indica que es una solicitud AJAX
                }
            })
            .then(response => {
                console.log(`handleFormSubmit: Respuesta de formulario recibida. Estado: ${response.status}`);
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP error! Estado: ${response.status}. Respuesta: ${text}`);
                    });
                }
                return response.text();
            })
            .then(html => {
                contentArea.innerHTML = html; // Muestra el mensaje de éxito/error del PHP
                console.log('handleFormSubmit: Respuesta de formulario cargada en el área de contenido.');

                // Lógica de recarga inteligente después de enviar un formulario de empleado/perfil
                if (form.action.includes('controller=Employee&action=create') || form.action.includes('controller=Employee&action=edit')) {
                    console.log('handleFormSubmit: Formulario de empleado enviado, recargando lista de empleados...');
                    window.loadContent('index.php?controller=Employee&action=index'); // Recargar la lista de empleados
                } else if (form.action.includes('controller=User&action=editProfile')) {
                    console.log('handleFormSubmit: Formulario de perfil actualizado.');
                    // Podrías recargar la misma vista o ir al dashboard, aquí solo muestra el mensaje
                    // window.loadContent('index.php?controller=User&action=editProfile'); // Para mantener el formulario con el mensaje
                }
            })
            .catch(error => {
                console.error('handleFormSubmit: Error al enviar el formulario:', error);
                contentArea.innerHTML = `<p class="error-message">Error al procesar el formulario: ${error.message}</p>`;
            })
            .finally(() => {
                hideLoader();
                // No se llama a attachAllEventListeners aquí porque loadContent lo hace
                // si se recarga una vista completa. Para mensajes in-situ, no es necesario.
            });
        }
    };

    /**
     * Manejador de eventos para clics en botones de eliminación por AJAX.
     * Usa delegación de eventos para elementos que se añaden dinámicamente.
     * Esta función es global.
     */
    window.handleDeleteButtonClick = function(event) {
        // Verifica si el clic fue en un elemento con clase 'delete-btn' o un hijo de este
        if (event.target.closest('.delete-btn')) {
            event.preventDefault();
            const deleteLink = event.target.closest('.delete-btn'); // Encuentra el enlace o botón principal
            
            // Usamos un modal o confirmación personalizada en lugar de alert/confirm si es posible
            if (confirm('¿Estás seguro de que quieres eliminar este registro? Esta acción no se puede deshacer.')) {
                const path = deleteLink.getAttribute('data-path');
                if (path) {
                    const url = `index.php?${path}`;
                    console.log(`handleDeleteButtonClick: Eliminando registro desde: ${url}`);
                    showLoader();

                    fetch(url, {
                        method: 'GET', // O 'POST' si tu backend espera POST para la eliminación
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log(`handleDeleteButtonClick: Respuesta de eliminación recibida. Estado: ${response.status}`);
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP error! Estado: ${response.status}. Respuesta: ${text}`);
                            });
                        }
                        return response.text();
                    })
                    .then(html => {
                        // Aquí podrías mostrar un mensaje de éxito/error y luego recargar la lista
                        contentArea.innerHTML = html; // Muestra el mensaje de éxito/error del PHP
                        console.log('handleDeleteButtonClick: Operación de eliminación completada.');
                        window.loadContent('index.php?controller=Employee&action=index'); // Recargar la lista de empleados
                    })
                    .catch(error => {
                        console.error('handleDeleteButtonClick: Error al eliminar el registro:', error);
                        contentArea.innerHTML = `<p class="error-message">Error al eliminar: ${error.message}</p>`;
                    })
                    .finally(() => {
                        hideLoader();
                    });
                } else {
                    console.warn('handleDeleteButtonClick: Botón de eliminación sin data-path definido:', deleteLink);
                }
            }
        }
    };

    /**
     * Adjunta o re-adjunta todos los event listeners necesarios.
     * Esta función es global y se llama al inicio y después de cada carga AJAX
     * para asegurar que los elementos dinámicos sean interactivos.
     */
    window.attachAllEventListeners = function() {
        console.log('--- attachAllEventListeners: Re-adjuntando todos los Event Listeners... ---');

        // Eliminar y re-adjuntar listeners para .sidebar-link
        // Usamos delegación o eliminamos/adjuntamos para cada elemento.
        // Aquí, re-adjuntamos a cada elemento para mayor claridad.
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.removeEventListener('click', window.handleSidebarLinkClick); // Evita duplicados
            link.addEventListener('click', window.handleSidebarLinkClick);
        });
        console.log('attachAllEventListeners: Listeners para .sidebar-link adjuntados.');

        // Delegación de eventos para formularios y botones de eliminación.
        // Estos se adjuntan al `document` y detectan eventos en sus hijos,
        // por lo que no necesitan ser re-adjuntados para elementos que aparecen y desaparecen.
        // Solo nos aseguramos de que el listener global esté presente y no duplicado.

        // Listener global para envíos de formularios con clase 'ajax-form'
        document.removeEventListener('submit', window.handleFormSubmit);
        document.addEventListener('submit', window.handleFormSubmit);
        console.log('attachAllEventListeners: Listener global para form.ajax-form adjuntado.');

        // Listener global para clics en elementos con clase 'delete-btn'
        document.removeEventListener('click', window.handleDeleteButtonClick);
        document.addEventListener('click', window.handleDeleteButtonClick);
        console.log('attachAllEventListeners: Listener global para .delete-btn adjuntado.');


        // Listener para el botón de alternar el sidebar (no es dinámico, pero se re-adjunta por seguridad)
        if (sidebarToggleBtn) {
            sidebarToggleBtn.removeEventListener('click', () => sidebar.classList.toggle('collapsed'));
            sidebarToggleBtn.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
            console.log('attachAllEventListeners: Listener para sidebar-toggle-btn adjuntado.');
        }
        console.log('--- attachAllEventListeners: Re-adjuntado completado. ---');
    };

    // Carga el contenido de 'home' por defecto al cargar la página
    window.loadContent('index.php?controller=Dashboard&action=home');

    // Adjuntar todos los listeners iniciales al cargar el DOM
    window.attachAllEventListeners();
});
