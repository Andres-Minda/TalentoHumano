-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-08-2025 a las 16:31:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `talent_human_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL CHECK (`tipo` in ('Normal','Vacaciones','Licencia','Permiso')),
  `estado` varchar(20) DEFAULT NULL CHECK (`estado` in ('Puntual','Tardanza','Ausente','Justificado','Injustificado')),
  `tipo_inasistencia` enum('Justificada','Injustificada','Permiso','Vacaciones','Licencia Médica') DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `horas_trabajadas` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id_asistencia`, `id_empleado`, `fecha`, `hora_entrada`, `hora_salida`, `tipo`, `estado`, `tipo_inasistencia`, `observaciones`, `created_at`, `horas_trabajadas`) VALUES
(1, 1, '2025-08-01', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, NULL, '2025-08-03 04:16:45', 9.00),
(2, 1, '2025-08-02', '08:15:00', '17:00:00', 'Normal', 'Tardanza', NULL, 'Tráfico', '2025-08-03 04:16:45', 8.75),
(3, 1, '2025-08-03', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, NULL, '2025-08-03 04:16:45', 9.00),
(4, 2, '2025-08-01', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, NULL, '2025-08-03 04:16:45', 9.00),
(5, 2, '2025-08-02', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, NULL, '2025-08-03 04:16:45', 9.00),
(6, 2, '2025-08-03', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, NULL, '2025-08-03 04:16:45', 9.00),
(7, 3, '2025-08-01', '07:30:00', '16:30:00', 'Normal', 'Puntual', NULL, NULL, '2025-08-03 04:16:45', 9.00),
(8, 3, '2025-08-02', '07:30:00', '16:30:00', 'Normal', 'Puntual', NULL, NULL, '2025-08-03 04:16:45', 9.00),
(9, 3, '2025-08-03', '07:30:00', '16:30:00', 'Normal', 'Puntual', NULL, NULL, '2025-08-03 04:16:45', 9.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `candidatos`
--

CREATE TABLE `candidatos` (
  `id_candidato` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cv_url` varchar(255) DEFAULT NULL,
  `estado` varchar(20) NOT NULL CHECK (`estado` in ('En revisión','Entrevista','Contratado','Rechazado')),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `candidatos`
--

INSERT INTO `candidatos` (`id_candidato`, `nombres`, `apellidos`, `cedula`, `email`, `telefono`, `cv_url`, `estado`, `created_at`) VALUES
(1, 'Juan', 'Pérez', '1234567890', 'juan.perez@email.com', '0987654321', 'cv_juan_perez.pdf', 'En revisión', '2025-08-03 04:16:46'),
(2, 'María', 'González', '0987654321', 'maria.gonzalez@email.com', '1234567890', 'cv_maria_gonzalez.pdf', 'Entrevista', '2025-08-03 04:16:46'),
(3, 'Carlos', 'Rodríguez', '1122334455', 'carlos.rodriguez@email.com', '5566778899', 'cv_carlos_rodriguez.pdf', 'Rechazado', '2025-08-03 04:16:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitaciones`
--

CREATE TABLE `capacitaciones` (
  `id_capacitacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Planificada','En curso','Completada','Cancelada') DEFAULT 'Planificada',
  `cupo_maximo` int(11) DEFAULT 20,
  `periodo_academico_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `capacitaciones`
--

INSERT INTO `capacitaciones` (`id_capacitacion`, `nombre`, `descripcion`, `tipo`, `fecha_inicio`, `fecha_fin`, `costo`, `proveedor`, `created_at`, `estado`, `cupo_maximo`, `periodo_academico_id`) VALUES
(1, 'Gestión de Recursos Humanos', 'Curso completo de gestión del talento humano', 'Presencial', '2025-09-01', '2025-09-30', 500.00, 'Instituto de Desarrollo Profesional', '2025-08-03 04:16:43', 'Planificada', 20, 1),
(2, 'Desarrollo Web Moderno', 'Curso de desarrollo con tecnologías actuales', 'Virtual', '2025-08-15', '2025-10-15', 300.00, 'Academia Digital', '2025-08-03 04:16:43', 'Planificada', 20, 1),
(3, 'Liderazgo Efectivo', 'Programa de desarrollo de habilidades de liderazgo', 'Híbrido', '2025-09-15', '2025-11-15', 400.00, 'Centro de Liderazgo', '2025-08-03 04:16:43', 'Planificada', 20, 1),
(4, 'Gestión de Proyectos', 'Metodologías ágiles y gestión tradicional', 'Presencial', '2025-10-01', '2025-12-01', 600.00, 'Instituto de Proyectos', '2025-08-03 04:16:43', 'Planificada', 20, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitaciones_disponibles`
--

CREATE TABLE `capacitaciones_disponibles` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `institucion` varchar(255) NOT NULL,
  `tipo_capacitacion` enum('CURSO','DIPLOMADO','CERTIFICACION','CONFERENCIA','SEMINARIO','WORKSHOP') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `horas_totales` int(11) NOT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `cupos_disponibles` int(11) DEFAULT 0,
  `estado` enum('ACTIVA','INACTIVA','COMPLETADA') DEFAULT 'ACTIVA',
  `requisitos` text DEFAULT NULL,
  `beneficios` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitaciones_empleados`
--

CREATE TABLE `capacitaciones_empleados` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `nombre_capacitacion` varchar(255) NOT NULL,
  `institucion` varchar(255) NOT NULL,
  `tipo_capacitacion` enum('CURSO','DIPLOMADO','CERTIFICACION','CONFERENCIA','SEMINARIO','WORKSHOP') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `horas_totales` int(11) NOT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `estado` enum('EN_PROGRESO','COMPLETADA','CANCELADA') DEFAULT 'EN_PROGRESO',
  `certificado_url` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`, `activo`, `created_at`) VALUES
(1, 'Documentos Personales', 'Cédula, pasaporte, documentos de identidad', 1, '2025-08-03 05:44:08'),
(2, 'Documentos Académicos', 'Títulos, certificados, diplomas', 1, '2025-08-03 05:44:08'),
(3, 'Documentos Laborales', 'Contratos, certificados laborales', 1, '2025-08-03 05:44:08'),
(4, 'Documentos Médicos', 'Certificados médicos, exámenes', 1, '2025-08-03 05:44:08'),
(5, 'Otros', 'Otros tipos de documentos', 1, '2025-08-03 05:44:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_evaluacion`
--

CREATE TABLE `categorias_evaluacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `peso` decimal(3,2) DEFAULT 1.00,
  `activa` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_evaluacion`
--

INSERT INTO `categorias_evaluacion` (`id`, `nombre`, `descripcion`, `peso`, `activa`, `created_at`, `updated_at`) VALUES
(1, 'Gesti¾n', 'Capacidad de gesti¾n y administraci¾n', 1.00, 1, '2025-08-18 15:54:21', '2025-08-18 15:54:21'),
(2, 'Investigaci¾n', 'Desarrollo de investigaci¾n y proyectos', 1.00, 1, '2025-08-18 15:54:21', '2025-08-18 15:54:21'),
(3, 'MetodologÝa', 'MetodologÝa de ense±anza y aprendizaje', 1.00, 1, '2025-08-18 15:54:21', '2025-08-18 15:54:21'),
(4, 'Vinculaci¾n', 'Vinculaci¾n con la sociedad y empresas', 1.00, 1, '2025-08-18 15:54:21', '2025-08-18 15:54:21'),
(5, 'TecnologÝa', 'Uso y aplicaci¾n de tecnologÝas', 1.00, 1, '2025-08-18 15:54:21', '2025-08-18 15:54:21'),
(6, 'Liderazgo', 'Capacidad de liderazgo y trabajo en equipo', 1.00, 1, '2025-08-18 15:54:21', '2025-08-18 15:54:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificados`
--

CREATE TABLE `certificados` (
  `id_certificado` int(11) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_capacitacion` int(11) DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `institucion` varchar(200) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `url_certificado` varchar(255) DEFAULT NULL,
  `estado` enum('Vigente','Vencido','Próximo a vencer') DEFAULT 'Vigente',
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencias`
--

CREATE TABLE `competencias` (
  `id_competencia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `competencias`
--

INSERT INTO `competencias` (`id_competencia`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'Liderazgo', 'Capacidad para dirigir equipos y proyectos', '2025-08-03 04:16:43'),
(2, 'Comunicación', 'Habilidad para transmitir ideas efectivamente', '2025-08-03 04:16:43'),
(3, 'Trabajo en Equipo', 'Capacidad para colaborar en grupos', '2025-08-03 04:16:43'),
(4, 'Resolución de Problemas', 'Habilidad para analizar y resolver situaciones', '2025-08-03 04:16:43'),
(5, 'Innovación', 'Capacidad para generar ideas creativas', '2025-08-03 04:16:43'),
(6, 'Gestión del Tiempo', 'Habilidad para organizar y priorizar tareas', '2025-08-03 04:16:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `id_contrato` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_puesto` int(11) NOT NULL,
  `tipo_contrato` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `salario` decimal(10,2) NOT NULL,
  `horas_semanales` int(11) DEFAULT NULL,
  `archivo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`id_contrato`, `id_empleado`, `id_puesto`, `tipo_contrato`, `fecha_inicio`, `fecha_fin`, `salario`, `horas_semanales`, `archivo_url`, `created_at`) VALUES
(1, 1, 3, 'Indefinido', '2010-01-15', NULL, 2800.00, 40, NULL, '2025-08-03 04:16:43'),
(2, 2, 1, 'Indefinido', '2015-03-20', NULL, 2500.00, 40, NULL, '2025-08-03 04:16:43'),
(3, 3, 5, 'Indefinido', '2018-09-01', NULL, 1500.00, 30, NULL, '2025-08-03 04:16:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_jefe` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `responsable` varchar(100) DEFAULT NULL,
  `email_contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `ubicacion` varchar(200) DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Suspendido') DEFAULT 'Activo',
  `activo` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id_departamento`, `nombre`, `descripcion`, `id_jefe`, `created_at`, `responsable`, `email_contacto`, `telefono`, `ubicacion`, `estado`, `activo`, `updated_at`) VALUES
(4, 'Administrativo', 'Gestión administrativa y financiera', 2, '2025-08-03 04:16:42', NULL, NULL, NULL, NULL, 'Activo', 1, '2025-08-21 00:28:23'),
(11, 'Recursos Humanos', 'Gestión del talento humano y desarrollo organizacional', NULL, '2025-08-03 04:50:31', NULL, NULL, NULL, NULL, 'Activo', 1, '2025-08-21 00:28:23'),
(12, 'Tecnología de la Información', 'Soporte técnico y desarrollo de sistemas', NULL, '2025-08-03 04:50:31', '', '', '1231232131312', '', 'Activo', 1, '2025-08-21 00:47:06'),
(13, 'Administración', 'Gestión administrativa y financiera', NULL, '2025-08-03 04:50:31', NULL, NULL, NULL, NULL, 'Activo', 1, '2025-08-21 00:28:23'),
(14, 'Académico', 'Coordinación académica y docencia', NULL, '2025-08-03 04:50:31', NULL, NULL, NULL, NULL, 'Activo', 1, '2025-08-21 00:28:23'),
(15, 'Bienestar Estudiantil', 'Atención y apoyo a estudiantes', NULL, '2025-08-03 04:50:31', NULL, NULL, NULL, NULL, 'Activo', 1, '2025-08-21 00:28:23'),
(16, 'ASDSADASDA', 'SADASDASDSADD', NULL, '2025-08-21 00:46:37', 'DSADASDASDASD', 'SDASDASDASD@SADASD.COM', '+593987297841', 'ASDASDASD', 'Activo', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_evaluacion`
--

CREATE TABLE `detalles_evaluacion` (
  `id_detalle_evaluacion` int(11) NOT NULL,
  `id_evaluacion_empleado` int(11) NOT NULL,
  `id_competencia` int(11) NOT NULL,
  `puntaje` decimal(5,2) NOT NULL,
  `comentarios` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_evaluacion`
--

INSERT INTO `detalles_evaluacion` (`id_detalle_evaluacion`, `id_evaluacion_empleado`, `id_competencia`, `puntaje`, `comentarios`, `created_at`) VALUES
(1, 1, 1, 85.00, 'Demuestra buen liderazgo en proyectos', '2025-08-03 04:16:44'),
(2, 1, 2, 90.00, 'Excelente comunicación con el equipo', '2025-08-03 04:16:44'),
(3, 1, 3, 80.00, 'Colabora efectivamente en equipos', '2025-08-03 04:16:44'),
(4, 2, 1, 95.00, 'Liderazgo excepcional en la organización', '2025-08-03 04:16:44'),
(5, 2, 2, 88.00, 'Comunicación clara y efectiva', '2025-08-03 04:16:44'),
(6, 2, 3, 92.00, 'Excelente trabajo en equipo', '2025-08-03 04:16:44'),
(7, 3, 1, 75.00, 'Desarrollo de liderazgo en progreso', '2025-08-03 04:16:44'),
(8, 3, 2, 80.00, 'Buena comunicación con estudiantes', '2025-08-03 04:16:44'),
(9, 3, 3, 85.00, 'Trabaja bien en equipo académico', '2025-08-03 04:16:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_nomina`
--

CREATE TABLE `detalles_nomina` (
  `id_detalle` int(11) NOT NULL,
  `id_nomina` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `salario_base` decimal(10,2) NOT NULL,
  `horas_extras` decimal(10,2) DEFAULT 0.00,
  `valor_horas_extras` decimal(10,2) DEFAULT 0.00,
  `bonos` decimal(10,2) DEFAULT 0.00,
  `comisiones` decimal(10,2) DEFAULT 0.00,
  `otros_ingresos` decimal(10,2) DEFAULT 0.00,
  `salud` decimal(10,2) DEFAULT 0.00,
  `pension` decimal(10,2) DEFAULT 0.00,
  `prestamos` decimal(10,2) DEFAULT 0.00,
  `otros_descuentos` decimal(10,2) DEFAULT 0.00,
  `neto_pagar` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_nomina`
--

INSERT INTO `detalles_nomina` (`id_detalle`, `id_nomina`, `id_empleado`, `salario_base`, `horas_extras`, `valor_horas_extras`, `bonos`, `comisiones`, `otros_ingresos`, `salud`, `pension`, `prestamos`, `otros_descuentos`, `neto_pagar`, `created_at`) VALUES
(1, 1, 1, 2800.00, 10.00, 140.00, 200.00, 0.00, 0.00, 112.00, 112.00, 0.00, 0.00, 2916.00, '2025-08-03 04:16:45'),
(2, 1, 2, 2500.00, 5.00, 70.00, 150.00, 0.00, 0.00, 100.00, 100.00, 0.00, 0.00, 2520.00, '2025-08-03 04:16:45'),
(3, 1, 3, 1500.00, 0.00, 0.00, 100.00, 0.00, 0.00, 60.00, 60.00, 0.00, 0.00, 1480.00, '2025-08-03 04:16:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id_documento` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `tipo_documento` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `archivo_url` varchar(255) NOT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id_documento`, `id_empleado`, `tipo_documento`, `nombre`, `descripcion`, `archivo_url`, `fecha_emision`, `fecha_vencimiento`, `created_at`) VALUES
(1, 1, 'Contrato', 'Contrato de Trabajo', 'Contrato laboral indefinido', 'contrato_empleado_1.pdf', '2010-01-15', NULL, '2025-08-03 04:16:46'),
(2, 1, 'Certificado', 'Certificado de Estudios', 'Certificado de educación superior', 'certificado_empleado_1.pdf', '2009-12-01', NULL, '2025-08-03 04:16:46'),
(3, 2, 'Contrato', 'Contrato de Trabajo', 'Contrato laboral indefinido', 'contrato_empleado_2.pdf', '2015-03-20', NULL, '2025-08-03 04:16:46'),
(4, 2, 'Certificado', 'Certificado de Estudios', 'Certificado de educación superior', 'certificado_empleado_2.pdf', '2014-12-01', NULL, '2025-08-03 04:16:46'),
(5, 3, 'Contrato', 'Contrato de Trabajo', 'Contrato laboral indefinido', 'contrato_empleado_3.pdf', '2018-09-01', NULL, '2025-08-03 04:16:46'),
(6, 3, 'Certificado', 'Certificado de Estudios', 'Certificado de educación superior', 'certificado_empleado_3.pdf', '2017-12-01', NULL, '2025-08-03 04:16:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_empleado` varchar(20) NOT NULL,
  `tipo_docente` enum('Tiempo completo','Medio tiempo','Tiempo parcial') DEFAULT NULL,
  `departamento` varchar(255) DEFAULT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` varchar(15) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `foto_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_departamento` int(11) DEFAULT NULL,
  `id_puesto` int(11) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Vacaciones','Licencia') DEFAULT 'Activo',
  `periodo_academico_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `id_usuario`, `tipo_empleado`, `tipo_docente`, `departamento`, `nombres`, `apellidos`, `fecha_nacimiento`, `genero`, `estado_civil`, `direccion`, `telefono`, `fecha_ingreso`, `activo`, `foto_url`, `created_at`, `updated_at`, `id_departamento`, `id_puesto`, `salario`, `estado`, `periodo_academico_id`) VALUES
(1, 1, 'ADMINISTRATIVO', NULL, 'Departamento ITSI', 'Super', 'Admin', '1980-01-01', NULL, NULL, NULL, NULL, '2010-01-15', 1, NULL, '2025-08-03 01:08:51', '2025-08-21 00:12:26', NULL, NULL, 0.00, 'Inactivo', 1),
(2, 2, 'ADMINISTRATIVO', NULL, 'Recursos Humanos', 'Ana', 'García', '1985-05-10', NULL, NULL, NULL, NULL, '2015-03-20', 1, NULL, '2025-08-03 01:08:51', '2025-08-20 00:06:26', NULL, NULL, NULL, 'Activo', 1),
(3, 3, 'DOCENTE', 'Tiempo completo', 'Departamento General', 'Carlos', 'Pérez', '1990-11-25', NULL, NULL, NULL, NULL, '2018-09-01', 1, NULL, '2025-08-03 01:08:51', '2025-08-20 00:06:26', NULL, NULL, NULL, 'Activo', 1),
(4, 5, 'ADMINISTRATIVO', NULL, 'Administrativo', 'María', 'López', '1980-01-01', 'Masculino', 'Soltero', 'Dirección de prueba', '0987654321', '2020-01-01', 1, NULL, '2025-08-20 00:14:41', '2025-08-20 00:20:14', NULL, NULL, NULL, 'Activo', 1),
(7, 6, 'DIRECTIVO', NULL, 'Departamento ITSI', 'Roberto', 'Martínez', '1980-01-01', 'Masculino', 'Soltero', 'Dirección de prueba', '0987654321', '2020-01-01', 1, NULL, '2025-08-20 00:20:14', '2025-08-20 00:20:14', NULL, NULL, NULL, 'Activo', 1),
(8, 7, 'AUXILIAR', NULL, 'Departamento ITSI', 'Patricia', 'Rodríguez', '1980-01-01', 'Masculino', 'Soltero', 'Dirección de prueba', '0987654321', '2020-01-01', 1, NULL, '2025-08-20 00:20:14', '2025-08-20 00:20:14', NULL, NULL, NULL, 'Activo', 1),
(9, 8, 'DOCENTE', NULL, 'SADSADAS', 'ASasAS', 'AsaSAsASASsaSsSasSAsSSsASD', '2000-08-20', 'No especificado', 'Soltero', 'Por definir', 'Por definir', '2025-08-20', 1, NULL, '2025-08-20 23:12:09', '2025-08-20 23:12:09', NULL, NULL, 2000.00, 'Activo', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados_capacitaciones`
--

CREATE TABLE `empleados_capacitaciones` (
  `id_empleado_capacitacion` int(11) NOT NULL,
  `id_capacitacion` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `asistio` tinyint(1) DEFAULT 0,
  `aprobo` tinyint(1) DEFAULT 0,
  `certificado_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados_capacitaciones`
--

INSERT INTO `empleados_capacitaciones` (`id_empleado_capacitacion`, `id_capacitacion`, `id_empleado`, `asistio`, `aprobo`, `certificado_url`, `created_at`) VALUES
(1, 1, 2, 1, 1, 'certificado_rrhh_empleado_2.pdf', '2025-08-03 04:16:46'),
(2, 2, 1, 1, 1, 'certificado_web_empleado_1.pdf', '2025-08-03 04:16:46'),
(3, 3, 1, 1, 1, 'certificado_liderazgo_empleado_1.pdf', '2025-08-03 04:16:46'),
(4, 3, 2, 1, 1, 'certificado_liderazgo_empleado_2.pdf', '2025-08-03 04:16:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados_competencias`
--

CREATE TABLE `empleados_competencias` (
  `id_empleado_competencia` int(11) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_competencia` int(11) DEFAULT NULL,
  `nivel_actual` enum('Básico','Intermedio','Avanzado','Experto') DEFAULT 'Básico',
  `fecha_evaluacion` date DEFAULT NULL,
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id_evaluacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` varchar(20) NOT NULL CHECK (`estado` in ('Planificada','En curso','Finalizada')),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evaluaciones`
--

INSERT INTO `evaluaciones` (`id_evaluacion`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`) VALUES
(1, 'Evaluación Anual 2025', 'Evaluación de desempeño anual', '2025-01-01', '2025-12-31', 'En curso', '2025-08-03 04:16:44'),
(2, 'Evaluación de Competencias', 'Evaluación de competencias laborales', '2025-06-01', '2025-08-31', 'Planificada', '2025-08-03 04:16:44'),
(3, 'Evaluación de Proyectos', 'Evaluación de proyectos realizados', '2025-09-01', '2025-11-30', 'Planificada', '2025-08-03 04:16:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones_empleados`
--

CREATE TABLE `evaluaciones_empleados` (
  `id_evaluacion_empleado` int(11) NOT NULL,
  `id_evaluacion` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_evaluador` int(11) NOT NULL,
  `fecha_evaluacion` date NOT NULL,
  `puntaje_total` decimal(5,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evaluaciones_empleados`
--

INSERT INTO `evaluaciones_empleados` (`id_evaluacion_empleado`, `id_evaluacion`, `id_empleado`, `id_evaluador`, `fecha_evaluacion`, `puntaje_total`, `observaciones`, `created_at`) VALUES
(1, 1, 1, 2, '2025-01-15', 85.50, 'Excelente desempeño en gestión de TI', '2025-08-03 04:16:44'),
(2, 1, 2, 1, '2025-01-20', 90.00, 'Liderazgo excepcional en RRHH', '2025-08-03 04:16:44'),
(3, 1, 3, 2, '2025-01-25', 78.50, 'Buen desempeño académico', '2025-08-03 04:16:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_laboral`
--

CREATE TABLE `historial_laboral` (
  `id_historial` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_puesto_anterior` int(11) DEFAULT NULL,
  `id_puesto_nuevo` int(11) NOT NULL,
  `tipo_cambio` varchar(50) NOT NULL,
  `fecha_cambio` date NOT NULL,
  `motivo` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inasistencias`
--

CREATE TABLE `inasistencias` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `fecha_inasistencia` date NOT NULL,
  `hora_inasistencia` time DEFAULT NULL,
  `motivo` text NOT NULL,
  `justificada` tinyint(1) DEFAULT 0,
  `tipo_inasistencia` enum('Justificada','Injustificada','Permiso','Vacaciones','Licencia Médica') DEFAULT NULL,
  `archivo_justificacion` varchar(255) DEFAULT NULL,
  `registrado_por` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-01-01-000004', 'App\\Database\\Migrations\\CreateBasicTables', 'default', 'App', 1755695337, 1),
(2, '2025-08-20-133039', 'App\\Database\\Migrations\\AddTipoInasistenciaToInasistencias', 'default', 'App', 1755696665, 2),
(3, '2025-08-20-141130', 'App\\Database\\Migrations\\CreateInasistenciaTables', 'default', 'App', 1755699244, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nominas`
--

CREATE TABLE `nominas` (
  `id_nomina` int(11) NOT NULL,
  `periodo` varchar(20) NOT NULL,
  `fecha_generacion` date NOT NULL,
  `fecha_pago` date NOT NULL,
  `estado` varchar(20) NOT NULL CHECK (`estado` in ('Generada','Pagada','Anulada')),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nominas`
--

INSERT INTO `nominas` (`id_nomina`, `periodo`, `fecha_generacion`, `fecha_pago`, `estado`, `created_at`) VALUES
(1, '2025-08', '2025-08-01', '2025-08-05', 'Pagada', '2025-08-03 04:16:45'),
(2, '2025-07', '2025-07-01', '2025-07-05', 'Pagada', '2025-08-03 04:16:45'),
(3, '2025-06', '2025-06-01', '2025-06-05', 'Pagada', '2025-08-03 04:16:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_academicos`
--

CREATE TABLE `periodos_academicos` (
  `id_periodo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('Activo','Inactivo','Cerrado') DEFAULT 'Inactivo',
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodos_academicos`
--

INSERT INTO `periodos_academicos` (`id_periodo`, `nombre`, `fecha_inicio`, `fecha_fin`, `estado`, `descripcion`, `created_at`) VALUES
(1, 'Periodo 2025-1', '2025-01-15', '2025-06-30', 'Activo', 'Primer periodo académico del año 2025', '2025-08-03 05:44:08'),
(2, 'Periodo 2025-2', '2025-07-15', '2025-12-15', 'Inactivo', 'Segundo periodo académico del año 2025', '2025-08-03 05:44:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `tipo_permiso` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `dias` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL CHECK (`estado` in ('Solicitado','Aprobado','Rechazado')),
  `motivo` text DEFAULT NULL,
  `archivo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `id_empleado`, `tipo_permiso`, `fecha_inicio`, `fecha_fin`, `dias`, `estado`, `motivo`, `archivo_url`, `created_at`) VALUES
(1, 1, 'Vacaciones', '2025-09-01', '2025-09-15', 15, 'Aprobado', 'Vacaciones familiares', NULL, '2025-08-03 04:16:45'),
(2, 2, 'Permiso Personal', '2025-08-10', '2025-08-10', 1, 'Aprobado', 'Cita médica', NULL, '2025-08-03 04:16:45'),
(3, 3, 'Vacaciones', '2025-10-01', '2025-10-07', 7, 'Solicitado', 'Descanso', NULL, '2025-08-03 04:16:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `politicas_inasistencia`
--

CREATE TABLE `politicas_inasistencia` (
  `id_politica` int(11) UNSIGNED NOT NULL,
  `nombre_politica` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `max_inasistencias_mes` int(11) NOT NULL DEFAULT 3,
  `max_inasistencias_trimestre` int(11) NOT NULL DEFAULT 9,
  `max_inasistencias_anio` int(11) NOT NULL DEFAULT 36,
  `requiere_accion_disciplinaria` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `politicas_inasistencia`
--

INSERT INTO `politicas_inasistencia` (`id_politica`, `nombre_politica`, `descripcion`, `max_inasistencias_mes`, `max_inasistencias_trimestre`, `max_inasistencias_anio`, `requiere_accion_disciplinaria`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Política General de Inasistencias', 'Política estándar para el control de inasistencias', 3, 9, 36, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulaciones`
--

CREATE TABLE `postulaciones` (
  `id_postulacion` int(11) NOT NULL,
  `id_vacante` int(11) NOT NULL,
  `id_candidato` int(11) NOT NULL,
  `fecha_postulacion` date NOT NULL,
  `puntaje_prueba` decimal(5,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `postulaciones`
--

INSERT INTO `postulaciones` (`id_postulacion`, `id_vacante`, `id_candidato`, `fecha_postulacion`, `puntaje_prueba`, `observaciones`, `created_at`) VALUES
(1, 1, 1, '2025-08-05', 85.50, 'Buen candidato, experiencia técnica sólida', '2025-08-03 04:16:46'),
(2, 1, 2, '2025-08-06', 92.00, 'Excelente candidato, muy recomendado', '2025-08-03 04:16:46'),
(3, 2, 3, '2025-08-07', 65.00, 'No cumple requisitos mínimos', '2025-08-03 04:16:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulantes`
--

CREATE TABLE `postulantes` (
  `id_postulante` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_puesto` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('Masculino','Femenino','No especificado') NOT NULL,
  `estado_civil` enum('Soltero','Casado','Divorciado','Viudo','Unión libre') NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `nacionalidad` varchar(100) NOT NULL,
  `estado_postulacion` enum('Pendiente','En revisión','Aprobada','Rechazada','Contratado') DEFAULT 'Pendiente',
  `fecha_postulacion` date NOT NULL,
  `cv_path` varchar(500) DEFAULT NULL,
  `carta_motivacion` text DEFAULT NULL,
  `experiencia_laboral` text DEFAULT NULL,
  `educacion` text DEFAULT NULL,
  `habilidades` text DEFAULT NULL,
  `idiomas` text DEFAULT NULL,
  `certificaciones` text DEFAULT NULL,
  `referencias` text DEFAULT NULL,
  `disponibilidad_inmediata` enum('Sí','No','En 2 semanas','En 1 mes') NOT NULL,
  `expectativa_salarial` decimal(10,2) DEFAULT NULL,
  `notas_admin` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_evaluacion`
--

CREATE TABLE `preguntas_evaluacion` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `pregunta` text NOT NULL,
  `tipo_pregunta` enum('OPCION_MULTIPLE','ESCALA_LIKERT','TEXTO_LIBRE','VERDADERO_FALSO') NOT NULL,
  `opciones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`opciones`)),
  `peso` decimal(3,2) DEFAULT 1.00,
  `activa` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

CREATE TABLE `puestos` (
  `id_puesto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `salario_base` decimal(10,2) NOT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `salario_min` decimal(10,2) DEFAULT 0.00,
  `salario_max` decimal(10,2) DEFAULT 0.00,
  `titulo` varchar(200) NOT NULL,
  `tipo_contrato` enum('Tiempo Completo','Tiempo Parcial','Contrato Fijo','Contrato Indefinido','Por Proyecto','Prácticas') DEFAULT NULL,
  `experiencia_requerida` text DEFAULT NULL,
  `educacion_requerida` text DEFAULT NULL,
  `habilidades_requeridas` text DEFAULT NULL,
  `responsabilidades` text DEFAULT NULL,
  `beneficios` text DEFAULT NULL,
  `estado` enum('Abierto','Cerrado','En Revisión','Pausado') DEFAULT 'Abierto',
  `activo` tinyint(1) DEFAULT 1,
  `url_postulacion` varchar(500) DEFAULT NULL,
  `fecha_limite` date DEFAULT NULL,
  `vacantes_disponibles` int(11) DEFAULT 1,
  `nivel_experiencia` enum('Sin Experiencia','Junior (1-2 años)','Semi-Senior (3-5 años)','Senior (5+ años)','Experto (8+ años)') DEFAULT NULL,
  `modalidad_trabajo` enum('Presencial','Remoto','Híbrido') DEFAULT 'Presencial',
  `ubicacion_trabajo` varchar(200) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `puestos`
--

INSERT INTO `puestos` (`id_puesto`, `nombre`, `descripcion`, `salario_base`, `id_departamento`, `created_at`, `salario_min`, `salario_max`, `titulo`, `tipo_contrato`, `experiencia_requerida`, `educacion_requerida`, `habilidades_requeridas`, `responsabilidades`, `beneficios`, `estado`, `activo`, `url_postulacion`, `fecha_limite`, `vacantes_disponibles`, `nivel_experiencia`, `modalidad_trabajo`, `ubicacion_trabajo`, `updated_at`) VALUES
(1, 'Director de Recursos Humanos', 'Asistente administrativo con experiencia en gestión de documentos y atención al cliente.', 2500.00, 11, '2025-08-03 04:16:43', 1500.00, 2500.00, 'Asistente Administrativo', 'Tiempo Completo', 'Mínimo 1 año en funciones administrativas', 'Bachillerato o técnico en administración', 'Manejo de Office, atención al cliente, organización', 'Gestionar documentos, atender visitantes, apoyar en tareas administrativas', 'Seguro médico, vacaciones pagadas', 'Abierto', 1, 'postulacion-1-asistente-administrativo', '2025-12-31', 1, '', 'Presencial', 'Oficina Central', '2025-08-21 04:18:06'),
(2, 'Analista de Recursos Humanos', 'Auxiliar de contabilidad para apoyar en el registro y control de operaciones financieras.', 1800.00, 11, '2025-08-03 04:16:43', 1800.00, 2800.00, 'Auxiliar de Contabilidad', 'Tiempo Completo', 'Mínimo 2 años en contabilidad', 'Técnico en contabilidad o afines', 'Manejo de software contable, Excel, conocimientos básicos de contabilidad', 'Registrar operaciones, conciliar cuentas, preparar reportes', 'Seguro médico, bonos por resultados', 'Abierto', 1, 'postulacion-2-auxiliar-de-contabilidad', '2025-12-31', 1, '', 'Presencial', 'Oficina Central', '2025-08-21 04:18:06'),
(3, 'Director de TI', 'Técnico de soporte técnico para resolver problemas de hardware y software.', 2800.00, 12, '2025-08-03 04:16:43', 2000.00, 3200.00, 'Técnico de Soporte', 'Tiempo Completo', 'Mínimo 2 años en soporte técnico', 'Técnico en informática o afines', 'Windows, Office, hardware básico, redes', 'Resolver tickets de soporte, mantener equipos, instalar software', 'Seguro médico, capacitaciones técnicas', 'Abierto', 1, 'postulacion-3-t-cnico-de-soporte', '2025-12-31', 2, '', 'Presencial', 'Centro de Soporte', '2025-08-21 04:18:06'),
(4, 'Desarrollador de Sistemas', 'Coordinador de proyectos para gestionar iniciativas estratégicas de la organización.', 2000.00, 12, '2025-08-03 04:16:43', 3000.00, 4500.00, 'Coordinador de Proyectos', 'Tiempo Completo', 'Mínimo 4 años en gestión de proyectos', 'Ingeniería o licenciatura en administración', 'Metodologías ágiles, gestión de equipos, planificación', 'Planificar proyectos, coordinar equipos, reportar avances', 'Seguro médico, horario flexible, bonos por resultados', 'Abierto', 1, 'postulacion-4-coordinador-de-proyectos', '2025-12-31', 1, '', 'Híbrido', 'Oficina Central', '2025-08-21 04:18:06'),
(5, 'Docente', 'Especialista en marketing digital para desarrollar estrategias de promoción online.', 1500.00, 14, '2025-08-03 04:16:43', 2500.00, 3800.00, 'Especialista en Marketing Digital', 'Tiempo Completo', 'Mínimo 3 años en marketing digital', 'Marketing, publicidad o afines', 'Redes sociales, Google Ads, análisis de datos', 'Desarrollar campañas, analizar métricas, optimizar estrategias', 'Seguro médico, bonos por resultados, desarrollo profesional', 'Abierto', 1, 'postulacion-5-especialista-en-marketing-digital', '2025-12-31', 1, '', 'Híbrido', 'Oficina Central', '2025-08-21 04:18:06'),
(6, 'Coordinador Académico', 'Analista de datos para interpretar información y generar insights para la toma de decisiones.', 2200.00, 14, '2025-08-03 04:16:43', 2800.00, 4200.00, 'Analista de Datos', 'Tiempo Completo', 'Mínimo 3 años en análisis de datos', 'Estadística, ingeniería o afines', 'SQL, Python, Excel, visualización de datos', 'Analizar datos, crear reportes, generar insights', 'Seguro médico, horario flexible, capacitaciones', 'Abierto', 1, 'postulacion-6-analista-de-datos', '2025-12-31', 1, '', 'Híbrido', 'Oficina Central', '2025-08-21 04:18:06'),
(7, 'Administrador', 'Diseñador gráfico para crear material visual atractivo y profesional.', 1600.00, 4, '2025-08-03 04:16:43', 2200.00, 3500.00, 'Diseñador Gráfico', 'Tiempo Completo', 'Mínimo 2 años en diseño gráfico', 'Diseño gráfico o afines', 'Adobe Creative Suite, diseño web, branding', 'Crear diseños, mantener identidad visual, colaborar con equipos', 'Seguro médico, horario flexible, desarrollo creativo', 'Abierto', 1, 'postulacion-7-dise-ador-gr-fico', '2025-12-31', 1, '', 'Híbrido', 'Oficina Central', '2025-08-21 04:18:06'),
(8, 'Asistente Administrativo', 'Especialista en RRHH para gestionar procesos de selección y desarrollo del personal.', 1200.00, 4, '2025-08-03 04:16:43', 2400.00, 3600.00, 'Especialista en Recursos Humanos', 'Tiempo Completo', 'Mínimo 3 años en RRHH', 'Psicología, administración o afines', 'Reclutamiento, evaluación, desarrollo organizacional', 'Gestionar selección, evaluar desempeño, desarrollar políticas', 'Seguro médico, bonos por resultados, desarrollo profesional', 'Abierto', 1, 'postulacion-8-especialista-en-recursos-humanos', '2025-12-31', 1, '', 'Presencial', 'Oficina Central', '2025-08-21 04:18:06'),
(9, 'Coordinador de Bienestar', 'Asistente de ventas para apoyar en la gestión comercial y atención al cliente.', 1800.00, 15, '2025-08-03 04:16:43', 1600.00, 2600.00, 'Asistente de Ventas', 'Tiempo Completo', 'Mínimo 1 año en ventas o atención al cliente', 'Bachillerato o técnico en ventas', 'Atención al cliente, ventas, comunicación', 'Atender clientes, procesar ventas, dar seguimiento', 'Seguro médico, comisiones por ventas', 'Abierto', 1, 'postulacion-9-asistente-de-ventas', '2025-12-31', 2, '', 'Presencial', 'Oficina Central', '2025-08-21 04:18:06'),
(12, '', 'Desarrollador web con experiencia en tecnologías frontend y backend, capaz de crear aplicaciones web completas y responsivas.', 0.00, 4, '2025-08-21 03:37:13', 2500.00, 4000.00, 'Desarrollador Web Full Stack', 'Tiempo Completo', 'Mínimo 2 años en desarrollo web', 'Ingeniería en Sistemas o afines', 'HTML, CSS, JavaScript, PHP, MySQL, React, Node.js', 'Desarrollar aplicaciones web, mantener código existente, colaborar con el equipo', 'Seguro médico, vacaciones pagadas, capacitaciones', 'Abierto', 1, 'postulacion-12-desarrollador-web-full-stack', '2025-12-31', 2, '', 'Híbrido', 'Oficina Central', '2025-08-21 04:44:37'),
(13, '', 'Profesional encargado de gestionar el capital humano de la organización, incluyendo reclutamiento, selección y desarrollo.', 0.00, 11, '2025-08-21 03:37:13', 2000.00, 3500.00, 'Analista de Recursos Humanos', 'Tiempo Completo', 'Mínimo 3 años en RRHH', 'Psicología, Administración o afines', 'Gestión de personal, reclutamiento, evaluación de desempeño', 'Reclutar personal, gestionar nómina, evaluar desempeño', 'Seguro médico, bonos por resultados, desarrollo profesional', 'Abierto', 1, 'postulacion-13-analista-de-recursos-humanos', '2025-12-31', 1, '', 'Presencial', 'Oficina Central', '2025-08-21 04:18:06'),
(14, '', 'Profesional responsable de mantener y optimizar la infraestructura tecnológica de la organización.', 0.00, 12, '2025-08-21 03:37:13', 2800.00, 4500.00, 'Administrador de Sistemas', 'Tiempo Completo', 'Mínimo 4 años en administración de sistemas', 'Ingeniería en Sistemas o afines', 'Linux, Windows Server, redes, virtualización, seguridad', 'Mantener servidores, gestionar redes, implementar seguridad', 'Seguro médico, horario flexible, capacitaciones técnicas', 'Abierto', 1, 'postulacion-14-administrador-de-sistemas', '2025-12-31', 1, '', 'Presencial', 'Centro de Datos', '2025-08-21 04:18:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`, `created_at`) VALUES
(1, 'SuperAdministrador', 'Acceso total al sistema', '2025-08-03 01:08:51'),
(2, 'AdministradorTalentoHumano', 'Gestión de talento humano', '2025-08-03 01:08:51'),
(3, 'Docente', 'Personal docente', '2025-08-03 01:08:51'),
(6, 'ADMINISTRATIVO', 'Personal administrativo', '2025-08-18 15:54:25'),
(7, 'DIRECTIVO', 'Directivos de la institución', '2025-08-18 15:54:25'),
(8, 'AUXILIAR', 'Personal auxiliar', '2025-08-18 15:54:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id_solicitud` int(11) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `tipo_solicitud` enum('Permiso','Capacitación','Beneficio','Cambio de horario','Otro') DEFAULT 'Otro',
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_resolucion` timestamp NULL DEFAULT NULL,
  `estado` enum('Pendiente','En revisión','Aprobada','Rechazada','Cancelada') DEFAULT 'Pendiente',
  `resuelto_por` int(11) DEFAULT NULL,
  `comentarios_resolucion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_generales`
--

CREATE TABLE `solicitudes_generales` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `tipo_solicitud` enum('SOLICITUD_INFORMACION','SOLICITUD_DOCUMENTO','SOLICITUD_CAMBIO','SOLICITUD_APOYO','OTRO') NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('PENDIENTE','EN_REVISION','APROBADA','RECHAZADA','COMPLETADA') DEFAULT 'PENDIENTE',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `respuesta_admin` text DEFAULT NULL,
  `respondido_por` int(11) DEFAULT NULL,
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  `prioridad` enum('BAJA','MEDIA','ALTA','URGENTE') DEFAULT 'MEDIA',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_inasistencia`
--

CREATE TABLE `tipos_inasistencia` (
  `id_tipo` int(11) UNSIGNED NOT NULL,
  `nombre_tipo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `requiere_justificacion` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_inasistencia`
--

INSERT INTO `tipos_inasistencia` (`id_tipo`, `nombre_tipo`, `descripcion`, `requiere_justificacion`, `activo`, `created_at`) VALUES
(1, 'Justificada', 'Inasistencia con justificación válida', 1, 1, '2025-08-20 14:14:04'),
(2, 'Injustificada', 'Inasistencia sin justificación', 0, 1, '2025-08-20 14:14:04'),
(3, 'Permiso', 'Permiso autorizado', 1, 1, '2025-08-20 14:14:04'),
(4, 'Vacaciones', 'Días de vacaciones', 0, 1, '2025-08-20 14:14:04'),
(5, 'Licencia Médica', 'Licencia por enfermedad', 1, 1, '2025-08-20 14:14:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulos_academicos`
--

CREATE TABLE `titulos_academicos` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `universidad` varchar(255) NOT NULL,
  `tipo_titulo` enum('BACHILLER','LICENCIADO','INGENIERO','MASTER','DOCTOR','POSTDOCTOR') NOT NULL,
  `nombre_titulo` varchar(255) NOT NULL,
  `fecha_obtencion` date NOT NULL,
  `pais` varchar(100) NOT NULL,
  `archivo_certificado` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password_changed` tinyint(1) DEFAULT 0 COMMENT '0: contrase±a original, 1: contrase±a cambiada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `cedula`, `email`, `password_hash`, `id_rol`, `activo`, `last_login`, `created_at`, `updated_at`, `password_changed`) VALUES
(1, '9999999999', 'superadmin@itsi.edu.ec', '$2y$10$RNT4hTJ0QnRT3SMuDA6FlOP2HabRZwblMj4ty40Ie17kYnZzZHyRS', 1, 1, NULL, '2025-08-03 01:08:51', '2025-08-20 19:42:24', 0),
(2, '8888888888', 'admin.th@itsi.edu.ec', '$2y$10$RNT4hTJ0QnRT3SMuDA6FlOP2HabRZwblMj4ty40Ie17kYnZzZHyRS', 2, 1, NULL, '2025-08-03 01:08:51', '2025-08-20 19:42:24', 0),
(3, '7777777777', 'docente@itsi.edu.ec', '$2y$10$RNT4hTJ0QnRT3SMuDA6FlOP2HabRZwblMj4ty40Ie17kYnZzZHyRS', 3, 1, NULL, '2025-08-03 01:08:51', '2025-08-20 19:42:24', 0),
(5, '6666666666', 'administrativo@itsi.edu.ec', '$2y$10$RNT4hTJ0QnRT3SMuDA6FlOP2HabRZwblMj4ty40Ie17kYnZzZHyRS', 6, 1, NULL, '2025-08-20 00:05:46', '2025-08-20 19:42:24', 0),
(6, '5555555555', 'directivo@itsi.edu.ec', '$2y$10$RNT4hTJ0QnRT3SMuDA6FlOP2HabRZwblMj4ty40Ie17kYnZzZHyRS', 7, 1, NULL, '2025-08-20 00:06:30', '2025-08-20 19:42:24', 0),
(7, '4444444444', 'auxiliar@itsi.edu.ec', '$2y$10$RNT4hTJ0QnRT3SMuDA6FlOP2HabRZwblMj4ty40Ie17kYnZzZHyRS', 8, 1, NULL, '2025-08-20 00:07:34', '2025-08-20 19:42:24', 0),
(8, '123213123123', '123213123123@itsi.edu.ec', '$2y$10$uswUCbacuUDI8404Ie.Gvuir/G5Wgpp4vOV4ACWCFLxh5zUlnEpl.', 3, 1, NULL, '2025-08-20 23:12:09', '2025-08-20 23:12:09', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacantes`
--

CREATE TABLE `vacantes` (
  `id_vacante` int(11) NOT NULL,
  `id_puesto` int(11) NOT NULL,
  `fecha_publicacion` date NOT NULL,
  `fecha_cierre` date DEFAULT NULL,
  `estado` varchar(20) NOT NULL CHECK (`estado` in ('Abierta','Cerrada','Cancelada')),
  `descripcion` text DEFAULT NULL,
  `requisitos` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `nombre` varchar(200) DEFAULT NULL,
  `salario_min` decimal(10,2) DEFAULT 0.00,
  `salario_max` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacantes`
--

INSERT INTO `vacantes` (`id_vacante`, `id_puesto`, `fecha_publicacion`, `fecha_cierre`, `estado`, `descripcion`, `requisitos`, `created_at`, `nombre`, `salario_min`, `salario_max`) VALUES
(1, 4, '2025-08-01', '2025-08-31', 'Abierta', 'Desarrollador Full Stack', 'Experiencia en PHP, JavaScript, MySQL', '2025-08-03 04:16:45', 'Desarrollador de Sistemas', 1600.00, 2400.00),
(2, 6, '2025-08-01', '2025-08-31', 'Abierta', 'Coordinador Académico', 'Maestría en Educación, experiencia en gestión académica', '2025-08-03 04:16:45', 'Coordinador Académico', 1760.00, 2640.00),
(3, 8, '2025-08-01', '2025-08-31', 'Abierta', 'Asistente Administrativo', 'Bachillerato, experiencia en administración', '2025-08-03 04:16:45', 'Asistente Administrativo', 960.00, 1440.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `idx_asistencias_fecha` (`fecha`);

--
-- Indices de la tabla `candidatos`
--
ALTER TABLE `candidatos`
  ADD PRIMARY KEY (`id_candidato`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `capacitaciones`
--
ALTER TABLE `capacitaciones`
  ADD PRIMARY KEY (`id_capacitacion`),
  ADD KEY `fk_capacitaciones_periodo` (`periodo_academico_id`),
  ADD KEY `idx_capacitaciones_fecha` (`fecha_inicio`,`fecha_fin`);

--
-- Indices de la tabla `capacitaciones_disponibles`
--
ALTER TABLE `capacitaciones_disponibles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `capacitaciones_empleados`
--
ALTER TABLE `capacitaciones_empleados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `categorias_evaluacion`
--
ALTER TABLE `categorias_evaluacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id_certificado`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_capacitacion` (`id_capacitacion`);

--
-- Indices de la tabla `competencias`
--
ALTER TABLE `competencias`
  ADD PRIMARY KEY (`id_competencia`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id_contrato`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_puesto` (`id_puesto`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id_departamento`),
  ADD KEY `id_jefe` (`id_jefe`);

--
-- Indices de la tabla `detalles_evaluacion`
--
ALTER TABLE `detalles_evaluacion`
  ADD PRIMARY KEY (`id_detalle_evaluacion`),
  ADD KEY `id_evaluacion_empleado` (`id_evaluacion_empleado`),
  ADD KEY `id_competencia` (`id_competencia`);

--
-- Indices de la tabla `detalles_nomina`
--
ALTER TABLE `detalles_nomina`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_nomina` (`id_nomina`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_empleados_periodo` (`periodo_academico_id`),
  ADD KEY `idx_empleados_activo` (`activo`);

--
-- Indices de la tabla `empleados_capacitaciones`
--
ALTER TABLE `empleados_capacitaciones`
  ADD PRIMARY KEY (`id_empleado_capacitacion`),
  ADD KEY `id_capacitacion` (`id_capacitacion`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `empleados_competencias`
--
ALTER TABLE `empleados_competencias`
  ADD PRIMARY KEY (`id_empleado_competencia`),
  ADD UNIQUE KEY `unique_empleado_competencia` (`id_empleado`,`id_competencia`),
  ADD KEY `id_competencia` (`id_competencia`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id_evaluacion`);

--
-- Indices de la tabla `evaluaciones_empleados`
--
ALTER TABLE `evaluaciones_empleados`
  ADD PRIMARY KEY (`id_evaluacion_empleado`),
  ADD KEY `id_evaluacion` (`id_evaluacion`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_evaluador` (`id_evaluador`);

--
-- Indices de la tabla `historial_laboral`
--
ALTER TABLE `historial_laboral`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_puesto_anterior` (`id_puesto_anterior`),
  ADD KEY `id_puesto_nuevo` (`id_puesto_nuevo`);

--
-- Indices de la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleado_id` (`empleado_id`),
  ADD KEY `registrado_por` (`registrado_por`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD PRIMARY KEY (`id_nomina`);

--
-- Indices de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `politicas_inasistencia`
--
ALTER TABLE `politicas_inasistencia`
  ADD PRIMARY KEY (`id_politica`);

--
-- Indices de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD PRIMARY KEY (`id_postulacion`),
  ADD KEY `id_vacante` (`id_vacante`),
  ADD KEY `id_candidato` (`id_candidato`);

--
-- Indices de la tabla `postulantes`
--
ALTER TABLE `postulantes`
  ADD PRIMARY KEY (`id_postulante`),
  ADD UNIQUE KEY `unique_postulacion` (`id_usuario`,`id_puesto`),
  ADD KEY `idx_puesto` (`id_puesto`),
  ADD KEY `idx_usuario` (`id_usuario`),
  ADD KEY `idx_estado` (`estado_postulacion`),
  ADD KEY `idx_fecha` (`fecha_postulacion`);

--
-- Indices de la tabla `preguntas_evaluacion`
--
ALTER TABLE `preguntas_evaluacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD PRIMARY KEY (`id_puesto`),
  ADD UNIQUE KEY `url_postulacion` (`url_postulacion`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `resuelto_por` (`resuelto_por`);

--
-- Indices de la tabla `solicitudes_generales`
--
ALTER TABLE `solicitudes_generales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleado_id` (`empleado_id`),
  ADD KEY `respondido_por` (`respondido_por`);

--
-- Indices de la tabla `tipos_inasistencia`
--
ALTER TABLE `tipos_inasistencia`
  ADD PRIMARY KEY (`id_tipo`),
  ADD UNIQUE KEY `nombre_tipo` (`nombre_tipo`);

--
-- Indices de la tabla `titulos_academicos`
--
ALTER TABLE `titulos_academicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `idx_usuarios_activo` (`activo`);

--
-- Indices de la tabla `vacantes`
--
ALTER TABLE `vacantes`
  ADD PRIMARY KEY (`id_vacante`),
  ADD KEY `id_puesto` (`id_puesto`),
  ADD KEY `idx_vacantes_fecha` (`fecha_publicacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `candidatos`
--
ALTER TABLE `candidatos`
  MODIFY `id_candidato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `capacitaciones`
--
ALTER TABLE `capacitaciones`
  MODIFY `id_capacitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `capacitaciones_disponibles`
--
ALTER TABLE `capacitaciones_disponibles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `capacitaciones_empleados`
--
ALTER TABLE `capacitaciones_empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias_evaluacion`
--
ALTER TABLE `categorias_evaluacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id_certificado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `competencias`
--
ALTER TABLE `competencias`
  MODIFY `id_competencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `detalles_evaluacion`
--
ALTER TABLE `detalles_evaluacion`
  MODIFY `id_detalle_evaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `detalles_nomina`
--
ALTER TABLE `detalles_nomina`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `empleados_capacitaciones`
--
ALTER TABLE `empleados_capacitaciones`
  MODIFY `id_empleado_capacitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `empleados_competencias`
--
ALTER TABLE `empleados_competencias`
  MODIFY `id_empleado_competencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `evaluaciones_empleados`
--
ALTER TABLE `evaluaciones_empleados`
  MODIFY `id_evaluacion_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historial_laboral`
--
ALTER TABLE `historial_laboral`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `nominas`
--
ALTER TABLE `nominas`
  MODIFY `id_nomina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `politicas_inasistencia`
--
ALTER TABLE `politicas_inasistencia`
  MODIFY `id_politica` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  MODIFY `id_postulacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `postulantes`
--
ALTER TABLE `postulantes`
  MODIFY `id_postulante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `preguntas_evaluacion`
--
ALTER TABLE `preguntas_evaluacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `puestos`
--
ALTER TABLE `puestos`
  MODIFY `id_puesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitudes_generales`
--
ALTER TABLE `solicitudes_generales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_inasistencia`
--
ALTER TABLE `tipos_inasistencia`
  MODIFY `id_tipo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `titulos_academicos`
--
ALTER TABLE `titulos_academicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `vacantes`
--
ALTER TABLE `vacantes`
  MODIFY `id_vacante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `capacitaciones`
--
ALTER TABLE `capacitaciones`
  ADD CONSTRAINT `fk_capacitaciones_periodo` FOREIGN KEY (`periodo_academico_id`) REFERENCES `periodos_academicos` (`id_periodo`) ON DELETE SET NULL;

--
-- Filtros para la tabla `capacitaciones_empleados`
--
ALTER TABLE `capacitaciones_empleados`
  ADD CONSTRAINT `capacitaciones_empleados_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `certificados_ibfk_2` FOREIGN KEY (`id_capacitacion`) REFERENCES `capacitaciones` (`id_capacitacion`);

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `contratos_ibfk_2` FOREIGN KEY (`id_puesto`) REFERENCES `puestos` (`id_puesto`);

--
-- Filtros para la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`id_jefe`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `detalles_evaluacion`
--
ALTER TABLE `detalles_evaluacion`
  ADD CONSTRAINT `detalles_evaluacion_ibfk_1` FOREIGN KEY (`id_evaluacion_empleado`) REFERENCES `evaluaciones_empleados` (`id_evaluacion_empleado`),
  ADD CONSTRAINT `detalles_evaluacion_ibfk_2` FOREIGN KEY (`id_competencia`) REFERENCES `competencias` (`id_competencia`);

--
-- Filtros para la tabla `detalles_nomina`
--
ALTER TABLE `detalles_nomina`
  ADD CONSTRAINT `detalles_nomina_ibfk_1` FOREIGN KEY (`id_nomina`) REFERENCES `nominas` (`id_nomina`),
  ADD CONSTRAINT `detalles_nomina_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_empleados_periodo` FOREIGN KEY (`periodo_academico_id`) REFERENCES `periodos_academicos` (`id_periodo`) ON DELETE SET NULL;

--
-- Filtros para la tabla `empleados_capacitaciones`
--
ALTER TABLE `empleados_capacitaciones`
  ADD CONSTRAINT `empleados_capacitaciones_ibfk_1` FOREIGN KEY (`id_capacitacion`) REFERENCES `capacitaciones` (`id_capacitacion`),
  ADD CONSTRAINT `empleados_capacitaciones_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `empleados_competencias`
--
ALTER TABLE `empleados_competencias`
  ADD CONSTRAINT `empleados_competencias_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `empleados_competencias_ibfk_2` FOREIGN KEY (`id_competencia`) REFERENCES `competencias` (`id_competencia`);

--
-- Filtros para la tabla `evaluaciones_empleados`
--
ALTER TABLE `evaluaciones_empleados`
  ADD CONSTRAINT `evaluaciones_empleados_ibfk_1` FOREIGN KEY (`id_evaluacion`) REFERENCES `evaluaciones` (`id_evaluacion`),
  ADD CONSTRAINT `evaluaciones_empleados_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `evaluaciones_empleados_ibfk_3` FOREIGN KEY (`id_evaluador`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `historial_laboral`
--
ALTER TABLE `historial_laboral`
  ADD CONSTRAINT `historial_laboral_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `historial_laboral_ibfk_2` FOREIGN KEY (`id_puesto_anterior`) REFERENCES `puestos` (`id_puesto`),
  ADD CONSTRAINT `historial_laboral_ibfk_3` FOREIGN KEY (`id_puesto_nuevo`) REFERENCES `puestos` (`id_puesto`);

--
-- Filtros para la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  ADD CONSTRAINT `inasistencias_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `inasistencias_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD CONSTRAINT `postulaciones_ibfk_1` FOREIGN KEY (`id_vacante`) REFERENCES `vacantes` (`id_vacante`),
  ADD CONSTRAINT `postulaciones_ibfk_2` FOREIGN KEY (`id_candidato`) REFERENCES `candidatos` (`id_candidato`);

--
-- Filtros para la tabla `postulantes`
--
ALTER TABLE `postulantes`
  ADD CONSTRAINT `fk_postulantes_puesto` FOREIGN KEY (`id_puesto`) REFERENCES `puestos` (`id_puesto`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_postulantes_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntas_evaluacion`
--
ALTER TABLE `preguntas_evaluacion`
  ADD CONSTRAINT `preguntas_evaluacion_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_evaluacion` (`id`);

--
-- Filtros para la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD CONSTRAINT `fk_puestos_departamento` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `puestos_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`resuelto_por`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `solicitudes_generales`
--
ALTER TABLE `solicitudes_generales`
  ADD CONSTRAINT `solicitudes_generales_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `solicitudes_generales_ibfk_2` FOREIGN KEY (`respondido_por`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `titulos_academicos`
--
ALTER TABLE `titulos_academicos`
  ADD CONSTRAINT `titulos_academicos_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `vacantes`
--
ALTER TABLE `vacantes`
  ADD CONSTRAINT `vacantes_ibfk_1` FOREIGN KEY (`id_puesto`) REFERENCES `puestos` (`id_puesto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
