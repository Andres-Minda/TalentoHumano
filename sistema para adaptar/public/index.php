<?php
// public/index.php - Punto de entrada principal

// Incluir archivos de configuración y controladores
require_once '../config/Database.php';
require_once '../controllers/DashboardController.php';
require_once '../controllers/EmployeeController.php';
require_once '../controllers/DepartmentController.php';
require_once '../controllers/AttendanceController.php';
require_once '../controllers/PayrollController.php';
require_once '../controllers/EvaluationController.php';
require_once '../controllers/TrainingController.php';
require_once '../controllers/RecruitmentController.php';
require_once '../controllers/ReportController.php';
require_once '../controllers/UserController.php';

// Obtener la acción y el controlador de la URL
// Ejemplo: index.php?controller=Employee&action=index
$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'DashboardController';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'home';

// Si la solicitud es AJAX, solo cargamos el contenido de la vista
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // Validar y crear instancia del controlador
    if (file_exists('../controllers/' . $controllerName . '.php')) {
        $controller = new $controllerName();
        // Llama a la acción si existe, de lo contrario, muestra un error 404.
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            // Error: Acción no encontrada
            http_response_code(404);
            echo "Error 404: Acción '" . htmlspecialchars($actionName) . "' no encontrada en el controlador '" . htmlspecialchars($controllerName) . "'.";
        }
    } else {
        // Error: Controlador no encontrado
        http_response_code(404);
        echo "Error 404: Controlador '" . htmlspecialchars($controllerName) . "' no encontrado.";
    }
} else {
    // Si no es una solicitud AJAX, cargamos el dashboard completo.
    $dashboardController = new DashboardController();
    $dashboardController->index();
}
?>
