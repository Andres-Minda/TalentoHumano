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
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);
        
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
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleado_id' => $empleado['id_empleado'],
            'solicitudes' => $solicitudes
        ];

        return view('Roles/Empleado/solicitudes/' . $vista, $data);
    }

    public function misVacaciones()
    {
        return $this->renderizarVistaEmpleado('Vacaciones', 'vacaciones');
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
        $idEmpleado = $this->request->getPost('empleado_id');
        $tipo = $this->request->getPost('tipo_solicitud');
        
        $dataIns = [
            'id_empleado'        => $idEmpleado,
            'tipo_solicitud'     => $tipo,
            'titulo'             => "Solicitud de " . $tipo,
            'fecha_solicitud'    => date('Y-m-d H:i:s'),
            'estado'             => 'Pendiente',
            'motivo_descripcion' => $this->request->getPost('motivo_descripcion'),
            'activo'             => 1
        ];

        if ($tipo === 'Vacaciones' || $tipo === 'Permisos') {
            $dataIns['fecha_inicio'] = $this->request->getPost('fecha_inicio');
            $dataIns['fecha_fin']    = $this->request->getPost('fecha_fin');
        }

        if ($tipo === 'Vacaciones') {
            $diasSolicitados = (int) $this->request->getPost('dias_solicitados');
            $dataIns['dias_solicitados'] = $diasSolicitados;

            // Validación Servidor: Verificar saldo suficiente
            $empleado = $this->empleadoModel->find($idEmpleado);
            if ($empleado && $diasSolicitados > $empleado['dias_vacaciones_disponibles']) {
                session()->setFlashdata('error', 'Error: Los días solicitados superan su saldo actual disponible (' . $empleado['dias_vacaciones_disponibles'] . ' días).');
                return redirect()->back();
            }
        }

        try {
            $this->solicitudModel->insert($dataIns);
            session()->setFlashdata('success', 'Solicitud enviada correctamente.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error al enviar la solicitud: ' . $e->getMessage());
        }

        // Redirección dinámica basada en el tipo
        $rutas = [
            'Vacaciones' => 'empleado/mis-solicitudes/vacaciones',
            'Permisos' => 'empleado/mis-solicitudes/permisos',
            'Certificados' => 'empleado/mis-solicitudes/certificados'
        ];

        $reRuta = $rutas[$tipo] ?? 'empleado/dashboard';
        return redirect()->to(base_url($reRuta));
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

            // Logica de Descuento si se APRUEBAN Vacaciones
            if ($solicitudOriginal && $solicitudOriginal['tipo_solicitud'] === 'Vacaciones' && $estado === 'Aprobada') {
                $empleadoIdToUpdate = $solicitudOriginal['id_empleado'];
                $diasADescontar = (int) $solicitudOriginal['dias_solicitados'];

                $empleado = $this->empleadoModel->find($empleadoIdToUpdate);
                if ($empleado) {
                    $nuevoSaldo = $empleado['dias_vacaciones_disponibles'] - $diasADescontar;
                    // Asegurar saldo no sea negativo (aunque validamos antes)
                    $nuevoSaldo = $nuevoSaldo < 0 ? 0 : $nuevoSaldo; 
                    
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
