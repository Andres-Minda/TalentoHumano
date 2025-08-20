# Sistema de Talento Humano - Implementación del Sistema ITSI

## 📋 Descripción General

Este proyecto ha sido completamente refactorizado para implementar el estilo visual y la arquitectura del sistema ITSI (Bienestar Estudiantil). Se ha mantenido toda la funcionalidad existente del sistema de Talento Humano, pero ahora con una interfaz visual moderna y consistente.

## 🎯 Características Implementadas

### ✨ Estilo Visual del Sistema ITSI
- **Layout moderno**: Estructura `page-wrapper` con sidebar fijo y contenido principal
- **Colores corporativos**: Paleta de colores basada en `#00367c` (azul ITSI)
- **Sidebar responsive**: Navegación lateral con 300px de ancho y funcionalidad móvil
- **Navbar profesional**: Header con foto de perfil y menú desplegable
- **Footer corporativo**: Pie de página con logo ITSI y copyright

### 🏗️ Arquitectura del Sistema
- **Layout base unificado**: `app/Views/layouts/base.php` como plantilla principal
- **Sistema de partials**: Componentes reutilizables (navbar, footer, sidebars)
- **Sidebars dinámicos**: Carga automática según el rol del usuario
- **Gestión de sesiones**: Control automático del tipo de sidebar

### 🎨 Estilos y CSS
- **CSS principal**: `public/sistema/assets/css/styles.min.css` con estilos base
- **CSS personalizado**: `public/sistema/assets/css/custom.css` con estilos específicos
- **Variables CSS**: Sistema de colores y dimensiones centralizado
- **Responsive design**: Adaptación automática para dispositivos móviles

### ⚡ JavaScript y Funcionalidad
- **Sidebar interactivo**: `public/sistema/assets/js/sidebarmenu.js` con funcionalidad completa
- **Aplicación principal**: `public/sistema/assets/js/app.min.js` con funciones globales
- **Dashboard**: `public/sistema/assets/js/dashboard.js` para estadísticas y gráficos
- **Eventos y animaciones**: Sistema completo de interacciones del usuario

### 📚 Librerías Integradas
- **jQuery 3.7.1**: Para funcionalidad JavaScript
- **Bootstrap 5.3.0**: Framework CSS y componentes
- **SimpleBar**: Scrollbar personalizado para el sidebar
- **ApexCharts**: Gráficos y visualizaciones (preparado)
- **SweetAlert2**: Notificaciones y alertas modernas

## 🗂️ Estructura de Archivos

### Layouts y Vistas
```
app/Views/
├── layouts/
│   └── base.php                 # Layout principal del sistema
├── partials/
│   ├── navbar.php               # Header con navegación
│   ├── footer.php               # Footer corporativo
│   ├── sidebar_empleado.php     # Sidebar para empleados
│   ├── sidebar_admin_th.php     # Sidebar para admin TH
│   └── sidebar_super_admin.php  # Sidebar para super admin
└── Roles/
    └── Docente/                 # Vistas específicas del rol
        ├── dashboard.php
        ├── mi_perfil.php
        ├── titulos_academicos.php
        ├── permisos_vacaciones.php
        └── documentos.php
```

### Assets y Recursos
```
public/sistema/assets/
├── css/
│   ├── styles.min.css           # Estilos principales
│   └── custom.css               # Estilos personalizados
├── js/
│   ├── sidebarmenu.js          # Funcionalidad del sidebar
│   ├── app.min.js              # Funciones principales
│   └── dashboard.js            # Funciones del dashboard
└── libs/
    ├── jquery/dist/            # jQuery
    ├── bootstrap/dist/          # Bootstrap
    └── simplebar/dist/          # SimpleBar
```

### Configuración
```
app/Config/
├── Database.php                 # Configuración de BD (bienestar_estudiantil_db)
├── App.php                     # Configuración de la aplicación
├── Filters.php                 # Filtros del sistema
├── Autoload.php                # Autocarga de clases y helpers
└── Routes.php                  # Definición de rutas
```

## 🚀 Instalación y Configuración

### Requisitos Previos
- PHP 8.1+
- CodeIgniter 4.4+
- MySQL/MariaDB 10.4+
- XAMPP o servidor web similar

### Base de Datos
El sistema está configurado para usar la base de datos `bienestar_estudiantil_db`. Asegúrate de que exista antes de ejecutar el sistema.

### Configuración del Servidor
1. **Apache**: Configurar mod_rewrite para URLs limpias
2. **PHP**: Habilitar extensiones mysqli y mbstring
3. **MySQL**: Crear la base de datos `bienestar_estudiantil_db`

### Instalación
1. Clonar o descargar el proyecto
2. Configurar la base de datos en `app/Config/Database.php`
3. Ejecutar migraciones si es necesario
4. Configurar el servidor web para apuntar a la carpeta `public/`

## 🎨 Personalización del Sistema

### Colores y Temas
Los colores principales están definidos en variables CSS:
```css
:root {
    --primary-color: #00367c;    /* Azul ITSI */
    --secondary-color: #6c757d;  /* Gris secundario */
    --success-color: #28a745;    /* Verde éxito */
    --warning-color: #ffc107;    /* Amarillo advertencia */
    --danger-color: #dc3545;     /* Rojo peligro */
}
```

### Sidebar y Navegación
- **Ancho del sidebar**: 300px (configurable en `custom.css`)
- **Colores del menú**: Basados en la paleta ITSI
- **Iconos**: Bootstrap Icons y Tabler Icons
- **Responsive**: Se oculta automáticamente en móviles

### Tipografía y Espaciado
- **Fuente principal**: Inter (sistema)
- **Tamaños de texto**: Sistema de escalas fs-1 a fs-8
- **Espaciado**: Sistema de márgenes mb-1 a mb-4, mt-1 a mt-4

## 🔧 Funcionalidades del Sistema

### Gestión de Usuarios
- **Roles**: Super Admin, Admin TH, Empleado
- **Autenticación**: Sistema de login seguro
- **Sesiones**: Control automático del tipo de sidebar
- **Perfiles**: Gestión de información personal

### Módulos Principales
- **Dashboard**: Estadísticas y resumen del sistema
- **Empleados**: Gestión completa de personal
- **Capacitaciones**: Sistema de formación
- **Evaluaciones**: Sistema de evaluación del personal
- **Inasistencias**: Control de asistencia
- **Documentos**: Gestión de archivos
- **Reportes**: Generación de informes

### Características Técnicas
- **MVC**: Arquitectura Model-View-Controller
- **Responsive**: Diseño adaptativo para todos los dispositivos
- **AJAX**: Comunicación asíncrona con el servidor
- **Validación**: Validación de formularios en frontend y backend
- **Seguridad**: Protección CSRF y validación de entrada

## 📱 Responsive Design

### Breakpoints
- **Desktop**: > 768px (sidebar visible)
- **Móvil**: ≤ 768px (sidebar oculto, toggle disponible)

### Comportamiento Móvil
- Sidebar se oculta automáticamente
- Botón de toggle en el header
- Navegación optimizada para touch
- Contenido se adapta al ancho de pantalla

## 🎯 Próximos Pasos

### Mejoras Sugeridas
1. **Gráficos**: Implementar ApexCharts para estadísticas
2. **Notificaciones**: Sistema de notificaciones en tiempo real
3. **Temas**: Múltiples temas visuales
4. **Internacionalización**: Soporte para múltiples idiomas
5. **API**: Endpoints REST para integración externa

### Mantenimiento
1. **Actualizaciones**: Mantener librerías actualizadas
2. **Backup**: Respaldo regular de la base de datos
3. **Logs**: Monitoreo de errores y actividad
4. **Performance**: Optimización de consultas y caché

## 🐛 Solución de Problemas

### Problemas Comunes
1. **Estilos no se cargan**: Verificar rutas de archivos CSS
2. **Sidebar no funciona**: Verificar JavaScript y jQuery
3. **Base de datos**: Verificar conexión y permisos
4. **Rutas**: Verificar configuración de .htaccess

### Debug
- Habilitar `DBDebug` en `Database.php` para errores de BD
- Verificar logs de CodeIgniter en `writable/logs/`
- Usar herramientas de desarrollo del navegador

## 📞 Soporte

Para soporte técnico o consultas sobre la implementación:
- Revisar la documentación del código
- Verificar logs del sistema
- Consultar la documentación de CodeIgniter 4

## 📄 Licencia

Este proyecto está basado en el sistema ITSI y mantiene la misma licencia y términos de uso.

---

**Sistema implementado exitosamente con el estilo visual del sistema ITSI**
**Fecha de implementación**: Agosto 2025
**Versión**: 1.0.0
