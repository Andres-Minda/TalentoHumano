<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestController extends Controller
{
    public function index()
    {
        echo "<h1>Test de CodeIgniter</h1>";
        echo "<p>Si puedes ver esta página, CodeIgniter está funcionando correctamente.</p>";
        echo "<p><a href='" . site_url('login') . "'>Ir al Login</a></p>";
        echo "<p><strong>Información del sistema:</strong></p>";
        echo "<ul>";
        echo "<li>PHP Version: " . PHP_VERSION . "</li>";
        echo "<li>CodeIgniter Version: " . \CodeIgniter\CodeIgniter::CI_VERSION . "</li>";
        echo "<li>Base URL: " . base_url() . "</li>";
        echo "</ul>";
    }
} 