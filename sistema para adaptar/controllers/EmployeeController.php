<?php
// controllers/EmployeeController.php

require_once '../config/Database.php'; // Incluye la configuración de la base de datos
require_once '../models/Employee.php'; // Incluye el modelo de Empleado

/**
 * Controlador para la gestión de Empleados (CRUD).
 */
class EmployeeController {
    private $db; // Conexión a la base de datos
    private $employee; // Objeto del modelo Employee

    /**
     * Constructor del controlador.
     * Inicializa la conexión a la base de datos y el modelo de Empleado.
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->employee = new Employee($this->db);
    }

    /**
     * Muestra la lista de empleados.
     */
    public function index() {
        $stmt = $this->employee->readAll(); // Obtiene todos los empleados
        $num = $stmt->rowCount(); // Cuenta el número de registros

        include_once '../views/employees/list.php'; // Carga la vista de lista
    }

    /**
     * Muestra el formulario para crear un nuevo empleado o procesa la creación.
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asigna los valores del formulario a las propiedades del objeto empleado
            $this->employee->tipo_empleado = $_POST['tipo_empleado'];
            $this->employee->cedula = $_POST['cedula'];
            $this->employee->nombres = $_POST['nombres'];
            $this->employee->apellidos = $_POST['apellidos'];
            $this->employee->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $this->employee->genero = $_POST['genero'];
            $this->employee->estado_civil = $_POST['estado_civil'];
            $this->employee->direccion = $_POST['direccion'];
            $this->employee->telefono = $_POST['telefono'];
            $this->employee->email = $_POST['email'];
            $this->employee->fecha_ingreso = $_POST['fecha_ingreso'];
            $this->employee->activo = isset($_POST['activo']) ? 1 : 0; // Checkbox, 1 si está marcado, 0 si no
            $this->employee->foto_url = $_POST['foto_url'];

            // Intenta crear el empleado
            if ($this->employee->create()) {
                echo "<p class='success-message'>Empleado creado exitosamente.</p>";
            } else {
                echo "<p class='error-message'>No se pudo crear el empleado.</p>";
            }
        }
        include_once '../views/employees/create.php'; // Carga la vista del formulario de creación
    }

    /**
     * Muestra el formulario para editar un empleado existente o procesa la actualización.
     */
    public function edit() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID de empleado no proporcionado.');

        // Lee los datos del empleado
        $employee_data = $this->employee->readOne($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asigna los valores del formulario a las propiedades del objeto empleado
            $this->employee->id_empleado = $id;
            $this->employee->tipo_empleado = $_POST['tipo_empleado'];
            $this->employee->cedula = $_POST['cedula'];
            $this->employee->nombres = $_POST['nombres'];
            $this->employee->apellidos = $_POST['apellidos'];
            $this->employee->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $this->employee->genero = $_POST['genero'];
            $this->employee->estado_civil = $_POST['estado_civil'];
            $this->employee->direccion = $_POST['direccion'];
            $this->employee->telefono = $_POST['telefono'];
            $this->employee->email = $_POST['email'];
            $this->employee->fecha_ingreso = $_POST['fecha_ingreso'];
            $this->employee->activo = isset($_POST['activo']) ? 1 : 0;
            $this->employee->foto_url = $_POST['foto_url'];

            // Intenta actualizar el empleado
            if ($this->employee->update()) {
                echo "<p class='success-message'>Empleado actualizado exitosamente.</p>";
                // Recarga los datos actualizados para mostrarlos en el formulario
                $employee_data = $this->employee->readOne($id);
            } else {
                echo "<p class='error-message'>No se pudo actualizar el empleado.</p>";
            }
        }
        include_once '../views/employees/edit.php'; // Carga la vista del formulario de edición
    }

    /**
     * Elimina un empleado.
     */
    public function delete() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID de empleado no proporcionado.');

        $this->employee->id_empleado = $id;

        // Intenta eliminar el empleado
        if ($this->employee->delete()) {
            echo "<p class='success-message'>Empleado eliminado exitosamente.</p>";
            // Después de eliminar, redirigir o recargar la lista de empleados
            // En una aplicación AJAX, podríamos simplemente recargar la sección de la lista.
            $this->index(); // Recarga la lista de empleados
        } else {
            echo "<p class='error-message'>No se pudo eliminar el empleado.</p>";
        }
    }
}
?>
