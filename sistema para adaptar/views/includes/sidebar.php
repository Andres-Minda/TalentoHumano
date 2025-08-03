<?php
// views/includes/sidebar.php

// Este archivo contiene la estructura del menú lateral del dashboard.
?>
<aside class="sidebar">
    <div class="logo">
        Talento Humano <span style="color: var(--primary-blue);">System</span>
    </div>
    <nav>
        <ul>
            <li>
                <!-- El atributo data-path se utiliza para el enrutamiento AJAX -->
                <a href="#" class="sidebar-link" data-path="controller=Dashboard&action=home">
                    <span class="icon"><i class="fas fa-home"></i></span>
                    <span class="label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link" data-path="controller=Employee&action=index">
                    <span class="icon"><i class="fas fa-users"></i></span>
                    <span class="label">Empleados</span>
                </a>
            </li>
            <li>
                <!-- Nuevo enlace para Departamentos -->
                <a href="#" class="sidebar-link" data-path="controller=Department&action=index">
                    <span class="icon"><i class="fas fa-building"></i></span>
                    <span class="label">Departamentos</span>
                </a>
            </li>
            <li>
                <!-- Nuevo enlace para Asistencia -->
                <a href="#" class="sidebar-link" data-path="controller=Attendance&action=index">
                    <span class="icon"><i class="fas fa-clock"></i></span>
                    <span class="label">Asistencia</span>
                </a>
            </li>
            <li>
                <!-- Nuevo enlace para Nóminas -->
                <a href="#" class="sidebar-link" data-path="controller=Payroll&action=index">
                    <span class="icon"><i class="fas fa-money-check-alt"></i></span>
                    <span class="label">Nómina</span>
                </a>
            </li>
            <li>
                <!-- Nuevo enlace para Evaluaciones -->
                <a href="#" class="sidebar-link" data-path="controller=Evaluation&action=index">
                    <span class="icon"><i class="fas fa-chart-line"></i></span>
                    <span class="label">Evaluaciones</span>
                </a>
            </li>
            <li>
                <!-- Nuevo enlace para Capacitaciones -->
                <a href="#" class="sidebar-link" data-path="controller=Training&action=index">
                    <span class="icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="label">Capacitaciones</span>
                </a>
            </li>
            <li>
                <!-- Nuevo enlace para Reclutamiento -->
                <a href="#" class="sidebar-link" data-path="controller=Recruitment&action=index">
                    <span class="icon"><i class="fas fa-user-plus"></i></span>
                    <span class="label">Reclutamiento</span>
                </a>
            </li>
            <li>
                <!-- Nuevo enlace para Reportes -->
                <a href="#" class="sidebar-link" data-path="controller=Report&action=index">
                    <span class="icon"><i class="fas fa-file-alt"></i></span>
                    <span class="label">Reportes</span>
                </a>
            </li>
            <!-- Agrega más opciones aquí según sea necesario -->
        </ul>
    </nav>
    <button class="toggle-btn" id="sidebar-toggle-btn">
        <i class="fas fa-bars"></i>
    </button>
</aside>
