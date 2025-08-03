<?php
// views/dashboard/index.php

// Este es el archivo principal de la vista del dashboard.
// Aquí se incluyen las partes fijas como el header y el footer,
// mientras que el contenido dinámico se carga en '#content-area'.

// Incluye la cabecera que contiene el <html>, <head>, y el inicio del <body> y .dashboard-container
include_once '../views/includes/header.php';

// El contenido del dashboard (por ejemplo, el resumen ejecutivo)
// será cargado por AJAX en el div con id="content-area"
// cuando la página se cargue por primera vez (ver public/js/main.js).

// Incluye el pie de página que cierra las etiquetas HTML abiertas
include_once '../views/includes/footer.php';
?>
