<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Comando: php spark puestos:generar-urls
 *
 * Reemplaza el script raíz: limpiar_y_generar_urls.php
 * Elimina puestos con título nulo/vacío y genera el campo
 * url_postulacion para todos los puestos válidos restantes.
 */
class GenerarUrlsPuestos extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'puestos:generar-urls';
    protected $description = 'Elimina puestos con título vacío y genera el campo url_postulacion para los puestos válidos.';
    protected $usage       = 'puestos:generar-urls';

    public function run(array $params): void
    {
        $db = Database::connect();

        CLI::write('');
        CLI::write('=== GENERADOR DE URLs PARA PUESTOS ===', 'cyan');
        CLI::write('');

        // 1. Eliminar puestos con títulos vacíos o nulos
        CLI::write('Eliminando puestos con títulos vacíos...', 'yellow');
        $eliminados = $db->table('puestos')
            ->groupStart()
                ->where('titulo IS NULL')
                ->orWhere('titulo', '')
            ->groupEnd()
            ->delete();

        $countEliminados = $db->affectedRows();
        CLI::write('  ' . CLI::color('✓', 'green') . " Se eliminaron {$countEliminados} puestos vacíos.");
        CLI::write('');

        // 2. Obtener puestos válidos sin URL o con URL vacía
        CLI::write('Generando URLs para puestos válidos...', 'yellow');
        $puestos = $db->table('puestos')
            ->select('id_puesto, titulo')
            ->where('titulo IS NOT NULL')
            ->where('titulo !=', '')
            ->orderBy('id_puesto', 'ASC')
            ->get()->getResultArray();

        if (empty($puestos)) {
            CLI::write('  ' . CLI::color('⚠ No hay puestos válidos para procesar.', 'yellow'));
        } else {
            foreach ($puestos as $puesto) {
                $url = $this->slugify($puesto['titulo'], $puesto['id_puesto']);

                $db->table('puestos')
                    ->where('id_puesto', $puesto['id_puesto'])
                    ->update(['url_postulacion' => $url]);

                CLI::write(
                    '  ' . CLI::color('✓', 'green') .
                    " '{$puesto['titulo']}' (ID: {$puesto['id_puesto']}) → " .
                    CLI::color($url, 'light_cyan')
                );
            }
        }

        CLI::write('');

        // 3. Verificar estado final
        $total = $db->table('puestos')->countAllResults();
        CLI::write("Estado final de la tabla puestos: {$total} registro(s).", 'yellow');
        CLI::write('');
        CLI::write(CLI::color('✓ Proceso completado exitosamente.', 'green'));
        CLI::write('');
    }

    /**
     * Convierte un título en un slug URL-friendly.
     * Formato: postulacion-{id}-{slug-del-titulo}
     */
    private function slugify(string $titulo, int $id): string
    {
        $slug = mb_strtolower(trim($titulo), 'UTF-8');

        // Transliterar caracteres especiales
        $map = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ñ' => 'n', 'ü' => 'u', 'à' => 'a', 'è' => 'e', 'ì' => 'i',
        ];
        $slug = strtr($slug, $map);

        // Reemplazar no alfanuméricos con guión
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        return 'postulacion-' . $id . '-' . $slug;
    }
}
