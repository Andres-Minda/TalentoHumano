<?php
// views/modules/settings.php

// Vista para la sección de Configuración del usuario/sistema.
// Aquí se podrían añadir opciones de configuración general del sistema o del perfil del usuario.
?>

<h2 class="section-title">Configuración del Sistema y Perfil</h2>

<p>Desde aquí podrás ajustar las preferencias y configuraciones generales de tu cuenta y del sistema.</p>

<div class="settings-options">
    <div class="setting-card">
        <h3>Notificaciones</h3>
        <p>Configura tus preferencias de notificación por correo electrónico y en el sistema.</p>
        <button class="btn btn-secondary">Administrar Notificaciones</button>
    </div>
    <div class="setting-card">
        <h3>Seguridad</h3>
        <p>Revisa y actualiza tus opciones de seguridad, como la verificación en dos pasos.</p>
        <button class="btn btn-secondary">Opciones de Seguridad</button>
    </div>
    <div class="setting-card">
        <h3>Idioma y Región</h3>
        <p>Establece el idioma y la configuración regional para tu interfaz.</p>
        <button class="btn btn-secondary">Cambiar Preferencias</button>
    </div>
</div>

<p class="info-message mt-4">Próximamente: Más opciones de personalización y configuración avanzada.</p>

<style>
    /* Estilos específicos para la vista de Configuración */
    .settings-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .setting-card {
        background-color: var(--white);
        border: 1px solid var(--silver-gray);
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .setting-card h3 {
        color: var(--dark-blue);
        margin-bottom: 10px;
    }

    .setting-card p {
        font-size: 0.95em;
        color: var(--dark-gray);
        margin-bottom: 20px;
        flex-grow: 1; /* Permite que el párrafo ocupe el espacio disponible */
    }

    .setting-card .btn {
        width: 100%;
    }
</style>
