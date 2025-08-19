<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestController extends Controller
{
    public function index()
    {
        return "Test Controller funcionando correctamente";
    }

    public function testRespaldo()
    {
        return "Test Respaldo desde TestController";
    }

    public function testReportes()
    {
        return "Test Reportes desde TestController";
    }
} 