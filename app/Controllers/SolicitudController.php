<?php

namespace App\Controllers;

use App\Models\SolicitudModel;
use App\Models\EmpleadoModel;

class SolicitudController extends BaseController
{
    protected $solicitudModel;
    protected $empleadoModel;

    public function __construct()
    {
        $this->solicitudModel = new SolicitudModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    // ==========================================
    // MÉTODOS PARA EMPLEADOS (MIS SOLICITUDES)
    // ==========================================

    private function renderizarVistaEmpleado($tipo, $vista)
    {
        $idUsuario = session()->get('id_usuario');
        $empleado  = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return redirect()->to(base_url('login'))->with('error', 'Usuario no asociado a un empleado.');
        }

        $solicitudes = $this->solicitudModel->where('id_empleado', $empleado['id_empleado'])
                                            ->where('tipo_solicitud', $tipo)
                                            ->orderBy('fecha_solicitud', 'DESC')
                                            ->findAll();

        $data = [
            'titulo' => 'Mis ' . $tipo,
            'usuario' => [
                'nombres'  => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol'      => session()->get('nombre_rol')
            ],
            'empleado_id' => $empleado['id_empleado'],
            'empleado'    => $empleado,
            'solicitudes' => $solicitudes
        ];

        return view('Roles/Empleado/solicitudes/' . $vista, $data);
    }

    public function misVacaciones()
    {
        $idUsuario = session()->get('id_usuario');
        $empleado  = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return redirect()->to(base_url('login'))->with('error', 'Usuario no asociado a un empleado.');
        }

        // Verificar y resetear saldo si corresponde (aniversario laboral)
        $resultado = $this->empleadoModel->verificarYActualizarSaldoVacaciones($empleado['id_empleado']);

        // Recargar empleado con saldo actualizado
        $empleado = $this->empleadoModel->find($empleado['id_empleado']);

        $solicitudes = $this->solicitudModel->where('id_empleado', $empleado['id_empleado'])
                                            ->where('tipo_solicitud', 'Vacaciones')
                                            ->orderBy('fecha_solicitud', 'DESC')
                                            ->findAll();

        $data = [
            'titulo'      => 'Mis Vacaciones',
            'usuario'     => [
                'nombres'   => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol'       => session()->get('nombre_rol')
            ],
            'empleado_id'       => $empleado['id_empleado'],
            'empleado'          => $empleado,
            'solicitudes'       => $solicitudes,
            'saldo_reseteado'   => $resultado['reseteado'],   // para mostrar aviso en vista
            'dias_disponibles'  => $resultado['dias_disponibles'],
        ];

        return view('Roles/Empleado/solicitudes/vacaciones', $data);
    }

    public function misPermisos()
    {
        return $this->renderizarVistaEmpleado('Permisos', 'permisos');
    }

    public function misCertificados()
    {
        return $this->renderizarVistaEmpleado('Certificados', 'certificados');
    }

    public function guardarSolicitud()
    {
        $idEmpleado = (int) $this->request->getPost('empleado_id');
        $tipo       = $this->request->getPost('tipo_solicitud');
        $esAjax     = $this->request->isAJAX();

        $dataIns = [
            'id_empleado'        => $idEmpleado,
            'tipo_solicitud'     => $tipo,
            'titulo'             => 'Solicitud de ' . $tipo,
            'fecha_solicitud'    => date('Y-m-d H:i:s'),
            'estado'             => 'Pendiente',
            'motivo_descripcion' => $this->request->getPost('motivo_descripcion'),
            'activo'             => 1,
        ];

        if (in_array($tipo, ['Vacaciones', 'Permisos'])) {
            $dataIns['fecha_inicio'] = $this->request->getPost('fecha_inicio');
            $dataIns['fecha_fin']    = $this->request->getPost('fecha_fin');
        }

        if ($tipo === 'Vacaciones') {
            $fechaInicio     = $dataIns['fecha_inicio'] ?? null;
            $fechaFin        = $dataIns['fecha_fin']    ?? null;
            $diasSolicitados = (int) $this->request->getPost('dias_solicitados');

            // Recalcular días en servidor para evitar manipulación del cliente.
            // Convención: del 04/04 al 11/04 = 7 días (diff sin +1).
            if ($fechaInicio && $fechaFin) {
                $inicio          = new \DateTime($fechaInicio);
                $fin             = new \DateTime($fechaFin);
                $diasSolicitados = (int) $inicio->diff($fin)->days;
            }

            $dataIns['dias_solicitados'] = $diasSolicitados;

            // Verificar saldo (también resetea si corresponde)
            $this->empleadoModel->verificarYActualizarSaldoVacaciones($idEmpleado);
            $empleado = $this->empleadoModel->find($idEmpleado);

            if (!$empleado) {
                $msg = 'Empleado no encontrado.';
                if ($esAjax) return $this->response->setJSON(['success' => false, 'message' => $msg]);
                session()->setFlashdata('error', $msg);
                return redirect()->back();
            }

            $saldoActual = (int) $empleado['dias_vacaciones_disponibles'];

            if ($diasSolicitados <= 0) {
                $msg = 'El rango de fechas no es válido.';
                if ($esAjax) return $this->response->setJSON(['success' => false, 'message' => $msg]);
                session()->setFlashdata('error', $msg);
                return redirect()->back();
            }

            if ($diasSolicitados > $saldoActual) {
                $msg = "No tienes suficientes días de vacaciones. Saldo actual: {$saldoActual} día(s). Estás solicitando: {$diasSolicitados} día(s).";
                if ($esAjax) return $this->response->setJSON(['success' => false, 'message' => $msg]);
                session()->setFlashdata('error', $msg);
                return redirect()->back();
            }
        }

        try {
            $this->solicitudModel->insert($dataIns);
            $msg = 'Solicitud enviada correctamente. Será revisada por Talento Humano.';
            if ($esAjax) return $this->response->setJSON(['success' => true, 'message' => $msg]);
            session()->setFlashdata('success', $msg);
        } catch (\Exception $e) {
            $msg = 'Error al enviar la solicitud: ' . $e->getMessage();
            if ($esAjax) return $this->response->setJSON(['success' => false, 'message' => $msg]);
            session()->setFlashdata('error', $msg);
        }

        $rutas = [
            'Vacaciones'  => 'empleado/mis-solicitudes/vacaciones',
            'Permisos'    => 'empleado/mis-solicitudes/permisos',
            'Certificados' => 'empleado/mis-solicitudes/certificados',
        ];

        return redirect()->to(base_url($rutas[$tipo] ?? 'empleado/dashboard'));
    }


    // ==========================================
    // MÉTODOS PARA ADMIN TH (GESTIÓN)
    // ==========================================

    private function renderizarVistaAdmin($tipo, $vista)
    {
        // Traer todas las solicitudes de este tipo, haciendo JOIN con empleados
        $db = \Config\Database::connect();
        $builder = $db->table('solicitudes s');
        $builder->select('s.*, e.nombres, e.apellidos, e.departamento');
        $builder->join('empleados e', 'e.id_empleado = s.id_empleado');
        $builder->where('s.tipo_solicitud', $tipo);
        $builder->orderBy('s.fecha_solicitud', 'DESC');
        $solicitudes = $builder->get()->getResultArray();

        $data = [
            'titulo' => 'Gestión de ' . $tipo,
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'solicitudes' => $solicitudes
        ];

        return view('Roles/AdminTH/solicitudes/' . $vista, $data);
    }

    public function adminVacaciones()
    {
        return $this->renderizarVistaAdmin('Vacaciones', 'vacaciones');
    }

    public function adminPermisos()
    {
        return $this->renderizarVistaAdmin('Permisos', 'permisos');
    }

    public function adminCertificados()
    {
        return $this->renderizarVistaAdmin('Certificados', 'certificados');
    }

    /**
     * Detalle de solicitud general para el AdminTH (sin restricción de empleado)
     */
    public function detalleSolicitudGeneralAdmin(int $id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $db = \Config\Database::connect();
        $solicitud = $db->table('solicitudes s')
            ->select('s.*, e.nombres, e.apellidos, e.departamento')
            ->join('empleados e', 'e.id_empleado = s.id_empleado', 'left')
            ->where('s.id_solicitud', $id)
            ->get()
            ->getRowArray();

        if (!$solicitud) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no encontrada.']);
        }

        return $this->response->setJSON(['success' => true, 'data' => $solicitud]);
    }

    public function adminSolicitudesGenerales()
    {
        $db      = \Config\Database::connect();
        $excluir = ['Vacaciones', 'Permisos', 'Certificados'];

        $builder = $db->table('solicitudes s');
        $builder->select('s.*, e.nombres, e.apellidos, e.departamento');
        $builder->join('empleados e', 'e.id_empleado = s.id_empleado');
        $builder->whereNotIn('s.tipo_solicitud', $excluir);
        $builder->where('s.activo', 1);
        $builder->orderBy('s.fecha_solicitud', 'DESC');
        $solicitudes = $builder->get()->getResultArray();

        // Stats
        $total     = count($solicitudes);
        $pendientes = count(array_filter($solicitudes, fn($s) => $s['estado'] === 'Pendiente'));
        $aprobadas  = count(array_filter($solicitudes, fn($s) => $s['estado'] === 'Aprobada'));
        $rechazadas = count(array_filter($solicitudes, fn($s) => $s['estado'] === 'Rechazada'));

        $data = [
            'titulo'      => 'Solicitudes Generales',
            'usuario'     => [
                'nombres'   => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol'       => session()->get('nombre_rol')
            ],
            'solicitudes' => $solicitudes,
            'stats'       => compact('total', 'pendientes', 'aprobadas', 'rechazadas'),
        ];

        return view('Roles/AdminTH/solicitudes/generales', $data);
    }

    /**
     * Resolver una solicitud general (AJAX POST)
     */
    public function resolverSolicitudGeneral($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $solicitud = $this->solicitudModel->find($id);
        if (!$solicitud) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no encontrada.']);
        }

        $estado      = $this->request->getPost('estado');
        $comentarios = $this->request->getPost('comentarios') ?? '';

        $estadosValidos = ['Aprobada', 'Rechazada', 'En revisión', 'Cancelada'];
        if (!in_array($estado, $estadosValidos)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Estado no válido.']);
        }

        $adminEmpleado = $this->empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));

        $this->solicitudModel->update($id, [
            'estado'                 => $estado,
            'comentarios_resolucion' => $comentarios,
            'resuelto_por'           => $adminEmpleado['id_empleado'] ?? null,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => "Solicitud marcada como: {$estado}."
        ]);
    }

    public function cancelarVacaciones($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $solicitud = $this->solicitudModel->find($id);

        if (!$solicitud) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no encontrada.']);
        }

        if ($solicitud['tipo_solicitud'] !== 'Vacaciones') {
            return $this->response->setJSON(['success' => false, 'message' => 'Esta solicitud no es de vacaciones.']);
        }

        if (!in_array($solicitud['estado'], ['Aprobada', 'En revisión'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solo se pueden interrumpir vacaciones aprobadas.']);
        }

        // Fecha de cancelación: la envía el admin o se usa hoy
        $fechaCancelacionStr = $this->request->getPost('fecha_cancelacion') ?? date('Y-m-d');

        $fechaCancelacion = new \DateTime($fechaCancelacionStr);
        $fechaInicio      = new \DateTime($solicitud['fecha_inicio']);
        $fechaFin         = new \DateTime($solicitud['fecha_fin']);

        // Normalizar horas para comparaciones limpias
        $fechaCancelacion->setTime(0, 0, 0);
        $fechaInicio->setTime(0, 0, 0);
        $fechaFin->setTime(0, 0, 0);

        // Caso 1: cancelación DESPUÉS de que ya terminaron → no hay nada que devolver
        if ($fechaCancelacion > $fechaFin) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Las vacaciones ya finalizaron el ' . $fechaFin->format('d/m/Y') . '. No hay días que devolver.'
            ]);
        }

        // Caso 2: cancelación ANTES de que empiecen → devolver TODOS los días
        if ($fechaCancelacion <= $fechaInicio) {
            $diasADevolver = (int) $solicitud['dias_solicitados'];
            $nuevoEstado   = 'Cancelada';
            $nuevaFechaFin = $solicitud['fecha_inicio']; // no llegó a empezar
        } else {
            // Caso 3: cancelación DURANTE las vacaciones → devolver días restantes
            // Días ya disfrutados = cancelación - inicio
            // Días a devolver    = fin - cancelación
            $diasADevolver = (int) $fechaCancelacion->diff($fechaFin)->days;
            $nuevoEstado   = 'Cancelada';
            $nuevaFechaFin = $fechaCancelacionStr;
        }

        // Actualizar la solicitud
        $this->solicitudModel->update($id, [
            'estado'   => $nuevoEstado,
            'fecha_fin' => $nuevaFechaFin,
            'comentarios_resolucion' => 'Vacaciones interrumpidas el ' . $fechaCancelacion->format('d/m/Y') . '. Días devueltos: ' . $diasADevolver . '.',
        ]);

        // Devolver días al saldo del empleado (tope máximo 15)
        if ($diasADevolver > 0) {
            $empleado   = $this->empleadoModel->find($solicitud['id_empleado']);
            $nuevoSaldo = min(15, (int) $empleado['dias_vacaciones_disponibles'] + $diasADevolver);
            $this->empleadoModel->update($solicitud['id_empleado'], [
                'dias_vacaciones_disponibles' => $nuevoSaldo
            ]);
        }

        return $this->response->setJSON([
            'success'        => true,
            'dias_devueltos' => $diasADevolver,
            'message'        => "Vacaciones interrumpidas. Se devolvieron {$diasADevolver} día(s) al empleado."
        ]);
    }

    public function cambiarEstado($id)
    {
        $estado = $this->request->getPost('estado');
        $comentarios = $this->request->getPost('comentarios');
        $idAdmin = session()->get('id_usuario');

        // Buscar qué empleado soy yo basado en id_usuario
        $admin = $this->empleadoModel->getEmpleadoByUsuarioId($idAdmin);

        $dataUpdate = [
            'estado' => $estado,
            'resuelto_por' => $admin ? $admin['id_empleado'] : null,
            'comentarios_resolucion' => $comentarios
        ];

        try {
            // Obtener la solicitud original ANTES de actualizar
            $solicitudOriginal = $this->solicitudModel->find($id);

            $this->solicitudModel->update($id, $dataUpdate);

            // Descuento al APROBAR vacaciones — solo si la solicitud NO estaba ya aprobada
            if (
                $solicitudOriginal &&
                $solicitudOriginal['tipo_solicitud'] === 'Vacaciones' &&
                $estado === 'Aprobada' &&
                $solicitudOriginal['estado'] !== 'Aprobada'   // ← evita doble descuento
            ) {
                $empleadoIdToUpdate = $solicitudOriginal['id_empleado'];
                $diasADescontar     = (int) $solicitudOriginal['dias_solicitados'];

                $empleado = $this->empleadoModel->find($empleadoIdToUpdate);
                if ($empleado) {
                    $nuevoSaldo = max(0, (int) $empleado['dias_vacaciones_disponibles'] - $diasADescontar);
                    $this->empleadoModel->update($empleadoIdToUpdate, [
                        'dias_vacaciones_disponibles' => $nuevoSaldo
                    ]);
                }
            }

            // Devolver días si se RECHAZA o CANCELA una solicitud que estaba Aprobada
            if (
                $solicitudOriginal &&
                $solicitudOriginal['tipo_solicitud'] === 'Vacaciones' &&
                in_array($estado, ['Rechazada', 'Cancelada']) &&
                $solicitudOriginal['estado'] === 'Aprobada'   // ← solo si estaba aprobada
            ) {
                $empleadoIdToUpdate = $solicitudOriginal['id_empleado'];
                $diasADevolver      = (int) $solicitudOriginal['dias_solicitados'];

                $empleado = $this->empleadoModel->find($empleadoIdToUpdate);
                if ($empleado) {
                    $nuevoSaldo = min(15, (int) $empleado['dias_vacaciones_disponibles'] + $diasADevolver);
                    $this->empleadoModel->update($empleadoIdToUpdate, [
                        'dias_vacaciones_disponibles' => $nuevoSaldo
                    ]);
                }
            }
            
            // Retornar JSON si fue AJAX, de lo contrario un redirect
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Estado actualizado a ' . $estado]);
            }
            session()->setFlashdata('success', 'Solicitud actualizada.');

        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
            session()->setFlashdata('error', 'Error al actualizar: ' . $e->getMessage());
        }

        return redirect()->back();
    }
}
