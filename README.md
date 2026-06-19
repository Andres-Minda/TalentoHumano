Markdown
# 🏢 Sistema de Gestión de Talento Humano (SGD-TH)

Un sistema integral de planificación de recursos humanos y gestión académica desarrollado bajo el patrón MVC estricto. Diseñado específicamente para administrar el ciclo de vida del personal administrativo y docente, evaluaciones de desempeño, capacitaciones y control de asistencia.

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

## 🚀 Características Principales

El sistema está dividido por perfiles de usuario estrictos (Administrador de Talento Humano, Docentes, Empleados), garantizando la seguridad y privacidad de la información:

* **👥 Gestión de Personal y Roles:** Administración completa de perfiles, contratos, puestos y asignación de departamentos.
* **📅 Control de Asistencias:** Registro de incidencias, faltas, y módulo de subida de justificaciones (certificados médicos/personales) con flujo de aprobación.
* **📊 Evaluaciones 360°:** Soporte para evaluaciones de pares, autoevaluaciones y evaluaciones estudiantiles con integración de rúbricas.
* **🎓 Capacitaciones:** Gestión de necesidades de formación, solicitudes de cursos, y seguimiento de certificados obtenidos.
* **💰 Nómina:** Módulo de cálculo y estructuración de nómina.
* **☁️ Integración Cloud:** Exportación de reportes y documentos respaldados a través de la API de Google Drive (OAuth2).
* **🔒 Seguridad Robusta:** Protección CSRF, enrutamiento estricto por filtros de roles y sanitización de datos contra XSS/SQL Injection.

## 🛠️ Stack Tecnológico

* **Backend:** PHP 8.1+, Framework CodeIgniter 4.
* **Frontend:** HTML5, CSS3, maquetación responsive con Bootstrap 5 y Tabler Icons.
* **Base de Datos:** MySQL / MariaDB (Query Builder & ORM nativo de CI4).
* **Integraciones:** Google API Client.

## ⚙️ Requisitos del Servidor

* PHP >= 8.1
* Extensiones PHP habilitadas: `intl`, `mbstring`, `json`, `mysqlnd`, `libcurl`.
* Servidor Web (Apache/Nginx) apuntando obligatoriamente al directorio `/public`.

## 📦 Instalación y Configuración

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/Andres-Minda/TalentoHumano.git](https://github.com/Andres-Minda/TalentoHumano.git)
   cd TalentoHumano
Instalar dependencias:

Bash
composer install
Configurar entorno:
Duplica el archivo env nativo y renómbralo a .env. Ajusta las siguientes variables:

Fragmento de código
CI_ENVIRONMENT = development # Cambiar a 'production' en despliegue
app.baseURL = 'http://localhost/TalentoHumano/public/'

database.default.hostname = 127.0.0.1
database.default.database = nombre_de_tu_bd
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
Migraciones y Datos Base:
Para inicializar la base de datos con la estructura y los usuarios semilla, puedes utilizar los comandos nativos del sistema:

Bash
php spark migrate
php spark usuarios:crear-seed
💻 Comandos CLI Personalizados (Spark)
El sistema cuenta con herramientas de mantenimiento ejecutables desde la terminal para garantizar la integridad de la base de datos sin exponer endpoints web:

php spark db:diagnosticar - Diagnóstico completo de las 43 tablas de la base de datos y mapeo de usuarios.

php spark usuarios:crear-seed - Genera usuarios de prueba (AdminTH, Docentes, etc.) haciendo upsert.

php spark db:limpiar-datos - Herramienta destructiva (con doble confirmación) para limpiar datos de prueba conservando el core.

php spark usuarios:reset-passwords - Reseteo interactivo de credenciales por consola.

🛡️ Estructura y Seguridad
public/: Único directorio expuesto al navegador web. El acceso a la raíz del proyecto está bloqueado.

app/Commands/: Contiene la lógica de mantenimiento CLI.

app/Filters/: Contiene AuthFilter y RoleFilter, garantizando que ninguna ruta quede expuesta a usuarios no autenticados o sin los privilegios de su rol.

writable/: Almacenamiento seguro fuera de la red pública para logs, exportaciones de base de datos y caché.

Desarrollado para la gestión institucional del Instituto Superior Tecnológico Ibarra (ITSI).
