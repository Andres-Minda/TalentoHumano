<?php
// views/includes/header.php

// Este archivo contiene la cabecera HTML común a todas las páginas.
// Incluye la nueva barra de navegación horizontal superior y el inicio del layout del dashboard.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Talento Humano</title>
    <!-- Incluye los estilos CSS principales -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Incluye Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Barra de Navegación Horizontal Superior -->
    <nav class="horizontal-nav">
        <div class="horizontal-nav-left">
            <div class="logo-top">
                <i class="fas fa-users-gear"></i> Talento Humano
            </div>
            <span class="system-name">| Sistema de Gestión</span>
        </div>
        <div class="horizontal-nav-right">
            <!-- Selector de Período Académico -->
            <div class="dropdown">
                <button class="dropbtn">Elegir Período Académico <i class="fas fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href="#">Período 2024-2025</a>
                    <a href="#">Período 2023-2024</a>
                    <a href="#">Período 2022-2023</a>
                </div>
            </div>

            <!-- Menú Mi Perfil -->
            <div class="dropdown profile-dropdown">
                <button class="dropbtn">
                    <i class="fas fa-user-circle"></i> Mi Perfil <i class="fas fa-caret-down"></i>
                </button>
                <div class="dropdown-content profile-dropdown-content">
                    <!-- ENLACE ACTUALIZADO PARA EDITAR PERFIL -->
                    <a href="#" class="sidebar-link" data-path="controller=User&action=editProfile"><i class="fas fa-user-edit"></i> Editar Perfil</a>
                    <a href="#" class="sidebar-link" data-path="controller=User&action=settings"><i class="fas fa-cog"></i> Configuración</a>
                    <a href="#" class="logout-link"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- El sidebar se incluye aquí -->
        <?php include_once 'sidebar.php'; ?>

        <div class="main-content">
            <!-- El header principal dentro del contenido ahora se ajusta -->
            <header class="main-header">
                <h1>Dashboard Principal</h1>
                <!-- Aquí podrías añadir información del usuario logueado, notificaciones, etc. -->
            </header>
            <!-- El contenido dinámico se cargará en esta área -->
            <div id="content-area">
                <!-- Spinner de carga -->
                <div id="loader-spinner" class="loader-spinner"></div>
                <!-- El contenido se cargará aquí vía AJAX -->
            </div>
