<?php
// config/Database.php

/**
 * Clase para la conexión a la base de datos MySQL.
 * Utiliza PDO para una conexión segura y flexible.
 */
class Database {
    private $host = "localhost"; // Host de la base de datos
    private $db_name = "talent_human_db"; // Nombre de la base de datos
    private $username = "root"; // Nombre de usuario de la base de datos (por defecto en XAMPP)
    private $password = ""; // Contraseña de la base de datos (por defecto en XAMPP, vacía)
    public $conn; // Objeto de conexión PDO

    /**
     * Obtiene la conexión a la base de datos.
     * @return PDO|null Objeto PDO si la conexión es exitosa, null en caso de error.
     */
    public function getConnection() {
        $this->conn = null; // Inicializa la conexión como nula

        try {
            // Crea una nueva instancia de PDO
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Establece el modo de error de PDO a excepción para manejo de errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Establece el juego de caracteres a UTF8
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            // Captura y muestra cualquier error de conexión
            echo "Error de conexión a la base de datos: " . $exception->getMessage();
        }

        return $this->conn; // Devuelve el objeto de conexión
    }
}
?>