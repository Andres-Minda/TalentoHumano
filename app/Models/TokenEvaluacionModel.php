<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenEvaluacionModel extends Model
{
    protected $table            = 'tokens_evaluacion_estudiante';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_evaluacion', 'id_docente', 'token', 'grupo_curso',
        'usado', 'ip_address', 'fecha_uso', 'fecha_expiracion'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Generar N tokens para una evaluación + docente + curso
     */
    public function generarTokens(int $idEvaluacion, int $idDocente, string $grupoCurso, int $cantidad, string $fechaExpiracion): array
    {
        $tokens = [];

        for ($i = 0; $i < $cantidad; $i++) {
            $token = hash('sha256', random_bytes(32) . microtime(true) . $i);

            $this->insert([
                'id_evaluacion'    => $idEvaluacion,
                'id_docente'       => $idDocente,
                'token'            => $token,
                'grupo_curso'      => $grupoCurso,
                'usado'            => 0,
                'fecha_expiracion' => $fechaExpiracion,
            ]);

            $tokens[] = $token;
        }

        return $tokens;
    }

    /**
     * Validar token: existe, no usado, no expirado
     */
    public function validarToken(string $token): ?array
    {
        $registro = $this->where('token', $token)->first();

        if (!$registro) {
            return null;
        }

        if ($registro['usado'] == 1) {
            return null;
        }

        if (strtotime($registro['fecha_expiracion']) < time()) {
            return null;
        }

        return $registro;
    }

    /**
     * Marcar token como usado
     */
    public function marcarUsado(string $token, string $ip): bool
    {
        return $this->where('token', $token)->set([
            'usado'     => 1,
            'ip_address' => $ip,
            'fecha_uso' => date('Y-m-d H:i:s'),
        ])->update();
    }

    /**
     * Obtener todos los tokens con info de evaluación y docente
     */
    public function getTokensConDetalles(): array
    {
        return $this->db->table('tokens_evaluacion_estudiante t')
            ->select('t.*, e.nombre as evaluacion_nombre,
                      emp.nombres as docente_nombres, emp.apellidos as docente_apellidos')
            ->join('evaluaciones e', 'e.id_evaluacion = t.id_evaluacion', 'left')
            ->join('empleados emp', 'emp.id_empleado = t.id_docente', 'left')
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtener tokens agrupados por docente y curso
     */
    public function getTokensAgrupados(): array
    {
        $tokens = $this->getTokensConDetalles();

        $agrupados = [];
        foreach ($tokens as $t) {
            $key = $t['id_docente'] . '_' . $t['grupo_curso'] . '_' . $t['id_evaluacion'];
            if (!isset($agrupados[$key])) {
                $agrupados[$key] = [
                    'docente'     => $t['docente_nombres'] . ' ' . $t['docente_apellidos'],
                    'id_docente'  => $t['id_docente'],
                    'evaluacion'  => $t['evaluacion_nombre'],
                    'grupo_curso' => $t['grupo_curso'],
                    'tokens'      => [],
                    'total'       => 0,
                    'usados'      => 0,
                    'pendientes'  => 0,
                ];
            }
            $agrupados[$key]['tokens'][] = $t;
            $agrupados[$key]['total']++;
            if ($t['usado'] == 1) {
                $agrupados[$key]['usados']++;
            } else {
                $agrupados[$key]['pendientes']++;
            }
        }

        return array_values($agrupados);
    }
}
