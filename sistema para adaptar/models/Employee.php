<?php
// models/Employee.php

require_once 'BaseModel.php'; // Incluye la clase BaseModel

/**
 * Modelo para la tabla 'empleados'.
 * Extiende BaseModel para heredar la conexión a la base de datos y métodos básicos.
 */
class Employee extends BaseModel {
    // Propiedades de la tabla empleados
    public $id_empleado;
    public $tipo_empleado;
    public $cedula;
    public $nombres;
    public $apellidos;
    public $fecha_nacimiento;
    public $genero;
    public $estado_civil;
    public $direccion;
    public $telefono;
    public $email;
    public $fecha_ingreso;
    public $activo;
    public $foto_url;
    public $created_at;
    public $updated_at;

    /**
     * Constructor de la clase Employee.
     * @param PDO $db Objeto de conexión a la base de datos.
     */
    public function __construct($db) {
        parent::__construct($db, 'empleados'); // Llama al constructor de BaseModel con la tabla 'empleados'
    }

    /**
     * Crea un nuevo empleado.
     * @return bool True si se crea correctamente, False en caso contrario.
     */
    public function create() {
        // Consulta para insertar un nuevo registro
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    tipo_empleado=:tipo_empleado, cedula=:cedula, nombres=:nombres,
                    apellidos=:apellidos, fecha_nacimiento=:fecha_nacimiento, genero=:genero,
                    estado_civil=:estado_civil, direccion=:direccion, telefono=:telefono,
                    email=:email, fecha_ingreso=:fecha_ingreso, activo=:activo, foto_url=:foto_url";

        // Prepara la consulta
        $stmt = $this->conn->prepare($query);

        // Limpia y enlaza los valores
        $this->tipo_empleado = htmlspecialchars(strip_tags($this->tipo_empleado));
        $this->cedula = htmlspecialchars(strip_tags($this->cedula));
        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->estado_civil = htmlspecialchars(strip_tags($this->estado_civil));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fecha_ingreso = htmlspecialchars(strip_tags($this->fecha_ingreso));
        $this->activo = $this->activo; // Booleano, no necesita strip_tags
        $this->foto_url = htmlspecialchars(strip_tags($this->foto_url));

        // Enlaza los parámetros
        $stmt->bindParam(":tipo_empleado", $this->tipo_empleado);
        $stmt->bindParam(":cedula", $this->cedula);
        $stmt->bindParam(":nombres", $this->nombres);
        $stmt->bindParam(":apellidos", $this->apellidos);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":genero", $this->genero);
        $stmt->bindParam(":estado_civil", $this->estado_civil);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":fecha_ingreso", $this->fecha_ingreso);
        $stmt->bindParam(":activo", $this->activo, PDO::PARAM_BOOL); // Enlazar como booleano
        $stmt->bindParam(":foto_url", $this->foto_url);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Actualiza un empleado existente.
     * @return bool True si se actualiza correctamente, False en caso contrario.
     */
    public function update() {
        // Consulta para actualizar un registro
        $query = "UPDATE " . $this->table_name . "
                  SET
                    tipo_empleado=:tipo_empleado,
                    cedula=:cedula,
                    nombres=:nombres,
                    apellidos=:apellidos,
                    fecha_nacimiento=:fecha_nacimiento,
                    genero=:genero,
                    estado_civil=:estado_civil,
                    direccion=:direccion,
                    telefono=:telefono,
                    email=:email,
                    fecha_ingreso=:fecha_ingreso,
                    activo=:activo,
                    foto_url=:foto_url
                  WHERE
                    id_empleado = :id_empleado";

        // Prepara la consulta
        $stmt = $this->conn->prepare($query);

        // Limpia y enlaza los valores (similar a create)
        $this->id_empleado = htmlspecialchars(strip_tags($this->id_empleado));
        $this->tipo_empleado = htmlspecialchars(strip_tags($this->tipo_empleado));
        $this->cedula = htmlspecialchars(strip_tags($this->cedula));
        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->estado_civil = htmlspecialchars(strip_tags($this->estado_civil));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fecha_ingreso = htmlspecialchars(strip_tags($this->fecha_ingreso));
        $this->activo = $this->activo;
        $this->foto_url = htmlspecialchars(strip_tags($this->foto_url));

        // Enlaza los parámetros
        $stmt->bindParam(':id_empleado', $this->id_empleado);
        $stmt->bindParam(':tipo_empleado', $this->tipo_empleado);
        $stmt->bindParam(':cedula', $this->cedula);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
        $stmt->bindParam(':genero', $this->genero);
        $stmt->bindParam(':estado_civil', $this->estado_civil);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fecha_ingreso', $this->fecha_ingreso);
        $stmt->bindParam(':activo', $this->activo, PDO::PARAM_BOOL);
        $stmt->bindParam(':foto_url', $this->foto_url);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Elimina un empleado.
     * @return bool True si se elimina correctamente, False en caso contrario.
     */
    public function delete() {
        // Consulta para eliminar un registro
        $query = "DELETE FROM " . $this->table_name . " WHERE id_empleado = ?";

        // Prepara la consulta
        $stmt = $this->conn->prepare($query);

        // Limpia el ID
        $this->id_empleado = htmlspecialchars(strip_tags($this->id_empleado));

        // Enlaza el ID
        $stmt->bindParam(1, $this->id_empleado);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
