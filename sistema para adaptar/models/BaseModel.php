<?php
// models/BaseModel.php

/**
 * Clase base para todos los modelos.
 * Provee funcionalidad común como la conexión a la base de datos.
 */
class BaseModel {
    protected $conn; // Conexión a la base de datos
    protected $table_name; // Nombre de la tabla asociada al modelo

    /**
     * Constructor del modelo base.
     * @param PDO $db Objeto de conexión a la base de datos.
     * @param string $table_name Nombre de la tabla a la que se asocia el modelo.
     */
    public function __construct($db, $table_name) {
        $this->conn = $db;
        $this->table_name = $table_name;
    }

    /**
     * Lee todos los registros de la tabla asociada.
     * @return PDOStatement|bool Objeto PDOStatement con los resultados o false si hay un error.
     */
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Lee un solo registro por ID.
     * @param int $id El ID del registro a leer.
     * @return array|bool Array asociativo con los datos del registro o false si no se encuentra.
     */
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_" . rtrim($this->table_name, 's') . " = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    // Puedes añadir métodos genéricos para crear, actualizar, eliminar aquí si es necesario
    // Sin embargo, para mayor flexibilidad, se pueden implementar en los modelos específicos.
}
?>
