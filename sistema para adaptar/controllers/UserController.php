<?php
// controllers/UserController.php

/**
 * Controlador para la gestión de usuarios (en este caso, el administrador).
 * Maneja la lógica para editar el perfil del administrador.
 */
class UserController {
    // Nota: En una aplicación real, necesitarías un modelo de usuario (UserModel.php)
    // y una conexión a la base de datos para cargar y guardar los datos del perfil.

    /**
     * Muestra el formulario para editar el perfil del administrador o procesa la actualización.
     */
    public function editProfile() {
        // Lógica para obtener los datos del usuario actual (administrador) desde la base de datos.
        // Por simplicidad, aquí solo cargamos la vista del formulario.
        // En un sistema real, harías algo como:
        // $database = new Database();
        // $db = $database->getConnection();
        // $userModel = new UserModel($db);
        // $userData = $userModel->getCurrentUser($_SESSION['user_id']); // Suponiendo autenticación

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lógica para procesar la actualización del perfil
            // En un sistema real, se validarían y guardarían los datos en la DB.
            $name = htmlspecialchars($_POST['name'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $message = "<p class='success-message'>Perfil actualizado exitosamente para: " . $name . " (" . $email . ")</p>";
            // Aquí llamarías a un método del modelo para guardar: $userModel->updateProfile($data);
        } else {
            $message = ""; // No hay mensaje al cargar inicialmente el formulario
            // Datos de ejemplo para el formulario si no hay POST (simulan datos del admin)
            $admin_data = [
                'name' => 'Administrador',
                'email' => 'admin@talento.com',
                'current_password' => '', // No se muestra la contraseña actual, solo se pide para cambiar
            ];
        }

        // Pasa el mensaje y los datos a la vista
        include_once '../views/modules/edit_profile.php';
    }

    /**
     * Muestra la vista de configuración del usuario.
     */
    public function settings() {
        include_once '../views/modules/settings.php';
    }
}
?>
