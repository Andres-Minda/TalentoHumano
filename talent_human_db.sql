-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 07:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `talent_human_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL CHECK (`tipo` in ('Normal','Vacaciones','Licencia','Permiso')),
  `estado` varchar(20) DEFAULT NULL CHECK (`estado` in ('Puntual','Tardanza','Ausente','Justificado','Injustificado')),
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `horas_trabajadas` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asistencias`
--

INSERT INTO `asistencias` (`id_asistencia`, `id_empleado`, `fecha`, `hora_entrada`, `hora_salida`, `tipo`, `estado`, `observaciones`, `created_at`, `horas_trabajadas`) VALUES
(1, 1, '2025-08-01', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, '2025-08-03 04:16:45', NULL),
(2, 1, '2025-08-02', '08:15:00', '17:00:00', 'Normal', 'Tardanza', 'Tráfico', '2025-08-03 04:16:45', NULL),
(3, 1, '2025-08-03', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, '2025-08-03 04:16:45', NULL),
(4, 2, '2025-08-01', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, '2025-08-03 04:16:45', NULL),
(5, 2, '2025-08-02', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, '2025-08-03 04:16:45', NULL),
(6, 2, '2025-08-03', '08:00:00', '17:00:00', 'Normal', 'Puntual', NULL, '2025-08-03 04:16:45', NULL),
(7, 3, '2025-08-01', '07:30:00', '16:30:00', 'Normal', 'Puntual', NULL, '2025-08-03 04:16:45', NULL),
(8, 3, '2025-08-02', '07:30:00', '16:30:00', 'Normal', 'Puntual', NULL, '2025-08-03 04:16:45', NULL),
(9, 3, '2025-08-03', '07:30:00', '16:30:00', 'Normal', 'Puntual', NULL, '2025-08-03 04:16:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `beneficios`
--

CREATE TABLE `beneficios` (
  `id_beneficio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficios`
--

INSERT INTO `beneficios` (`id_beneficio`, `nombre`, `descripcion`, `tipo`, `created_at`) VALUES
(1, 'Seguro Médico', 'Cobertura médica completa para empleado y familia', 'Salud', '2025-08-03 04:16:43'),
(2, 'Seguro de Vida', 'Protección financiera para la familia', 'Seguridad', '2025-08-03 04:16:43'),
(3, 'Vale de Alimentación', 'Subsidio para gastos de alimentación', 'Alimentación', '2025-08-03 04:16:43'),
(4, 'Transporte', 'Subsidio de transporte público', 'Movilidad', '2025-08-03 04:16:43'),
(5, 'Gimnasio', 'Acceso a instalaciones deportivas', 'Bienestar', '2025-08-03 04:16:43'),
(6, 'Capacitación', 'Presupuesto anual para desarrollo profesional', 'Desarrollo', '2025-08-03 04:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `beneficios_empleados`
--

CREATE TABLE `beneficios_empleados` (
  `id_beneficio_empleado` int(11) NOT NULL,
  `id_beneficio` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficios_empleados`
--

INSERT INTO `beneficios_empleados` (`id_beneficio_empleado`, `id_beneficio`, `id_empleado`, `fecha_inicio`, `fecha_fin`, `observaciones`, `created_at`) VALUES
(1, 1, 1, '2010-01-15', NULL, 'Cobertura completa', '2025-08-03 04:16:43'),
(2, 2, 1, '2010-01-15', NULL, 'Protección familiar', '2025-08-03 04:16:43'),
(3, 3, 1, '2010-01-15', NULL, 'Vale mensual', '2025-08-03 04:16:43'),
(4, 1, 2, '2015-03-20', NULL, 'Cobertura completa', '2025-08-03 04:16:43'),
(5, 2, 2, '2015-03-20', NULL, 'Protección familiar', '2025-08-03 04:16:43'),
(6, 3, 2, '2015-03-20', NULL, 'Vale mensual', '2025-08-03 04:16:43'),
(7, 1, 3, '2018-09-01', NULL, 'Cobertura básica', '2025-08-03 04:16:43'),
(8, 3, 3, '2018-09-01', NULL, 'Vale mensual', '2025-08-03 04:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `candidatos`
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
-- Dumping data for table `candidatos`
--

INSERT INTO `candidatos` (`id_candidato`, `nombres`, `apellidos`, `cedula`, `email`, `telefono`, `cv_url`, `estado`, `created_at`) VALUES
(1, 'Juan', 'Pérez', '1234567890', 'juan.perez@email.com', '0987654321', 'cv_juan_perez.pdf', 'En revisión', '2025-08-03 04:16:46'),
(2, 'María', 'González', '0987654321', 'maria.gonzalez@email.com', '1234567890', 'cv_maria_gonzalez.pdf', 'Entrevista', '2025-08-03 04:16:46'),
(3, 'Carlos', 'Rodríguez', '1122334455', 'carlos.rodriguez@email.com', '5566778899', 'cv_carlos_rodriguez.pdf', 'Rechazado', '2025-08-03 04:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `capacitaciones`
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
-- Dumping data for table `capacitaciones`
--

INSERT INTO `capacitaciones` (`id_capacitacion`, `nombre`, `descripcion`, `tipo`, `fecha_inicio`, `fecha_fin`, `costo`, `proveedor`, `created_at`, `estado`, `cupo_maximo`, `periodo_academico_id`) VALUES
(1, 'Gestión de Recursos Humanos', 'Curso completo de gestión del talento humano', 'Presencial', '2025-09-01', '2025-09-30', 500.00, 'Instituto de Desarrollo Profesional', '2025-08-03 04:16:43', 'Planificada', 20, 1),
(2, 'Desarrollo Web Moderno', 'Curso de desarrollo con tecnologías actuales', 'Virtual', '2025-08-15', '2025-10-15', 300.00, 'Academia Digital', '2025-08-03 04:16:43', 'Planificada', 20, 1),
(3, 'Liderazgo Efectivo', 'Programa de desarrollo de habilidades de liderazgo', 'Híbrido', '2025-09-15', '2025-11-15', 400.00, 'Centro de Liderazgo', '2025-08-03 04:16:43', 'Planificada', 20, 1),
(4, 'Gestión de Proyectos', 'Metodologías ágiles y gestión tradicional', 'Presencial', '2025-10-01', '2025-12-01', 600.00, 'Instituto de Proyectos', '2025-08-03 04:16:43', 'Planificada', 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`, `activo`, `created_at`) VALUES
(1, 'Documentos Personales', 'Cédula, pasaporte, documentos de identidad', 1, '2025-08-03 05:44:08'),
(2, 'Documentos Académicos', 'Títulos, certificados, diplomas', 1, '2025-08-03 05:44:08'),
(3, 'Documentos Laborales', 'Contratos, certificados laborales', 1, '2025-08-03 05:44:08'),
(4, 'Documentos Médicos', 'Certificados médicos, exámenes', 1, '2025-08-03 05:44:08'),
(5, 'Otros', 'Otros tipos de documentos', 1, '2025-08-03 05:44:08');

-- --------------------------------------------------------

--
-- Table structure for table `certificados`
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
-- Table structure for table `competencias`
--

CREATE TABLE `competencias` (
  `id_competencia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competencias`
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
-- Table structure for table `contratos`
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
-- Dumping data for table `contratos`
--

INSERT INTO `contratos` (`id_contrato`, `id_empleado`, `id_puesto`, `tipo_contrato`, `fecha_inicio`, `fecha_fin`, `salario`, `horas_semanales`, `archivo_url`, `created_at`) VALUES
(1, 1, 3, 'Indefinido', '2010-01-15', NULL, 2800.00, 40, NULL, '2025-08-03 04:16:43'),
(2, 2, 1, 'Indefinido', '2015-03-20', NULL, 2500.00, 40, NULL, '2025-08-03 04:16:43'),
(3, 3, 5, 'Indefinido', '2018-09-01', NULL, 1500.00, 30, NULL, '2025-08-03 04:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `departamentos`
--

CREATE TABLE `departamentos` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_jefe` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departamentos`
--

INSERT INTO `departamentos` (`id_departamento`, `nombre`, `descripcion`, `id_jefe`, `created_at`) VALUES
(1, 'Recursos Humanos', 'Gestión del talento humano y desarrollo organizacional', 2, '2025-08-03 04:16:42'),
(2, 'Tecnología de la Información', 'Soporte técnico y desarrollo de sistemas', 1, '2025-08-03 04:16:42'),
(3, 'Académico', 'Gestión académica y desarrollo curricular', 3, '2025-08-03 04:16:42'),
(4, 'Administrativo', 'Gestión administrativa y financiera', 2, '2025-08-03 04:16:42'),
(5, 'Bienestar Estudiantil', 'Atención y apoyo a estudiantes', 2, '2025-08-03 04:16:42'),
(6, 'Recursos Humanos', 'Gestión del talento humano y desarrollo organizacional', NULL, '2025-08-03 04:40:40'),
(7, 'Tecnología de la Información', 'Soporte técnico y desarrollo de sistemas', NULL, '2025-08-03 04:40:40'),
(8, 'Administración', 'Gestión administrativa y financiera', NULL, '2025-08-03 04:40:40'),
(9, 'Académico', 'Coordinación académica y docencia', NULL, '2025-08-03 04:40:40'),
(10, 'Bienestar Estudiantil', 'Atención y apoyo a estudiantes', NULL, '2025-08-03 04:40:40'),
(11, 'Recursos Humanos', 'Gestión del talento humano y desarrollo organizacional', NULL, '2025-08-03 04:50:31'),
(12, 'Tecnología de la Información', 'Soporte técnico y desarrollo de sistemas', NULL, '2025-08-03 04:50:31'),
(13, 'Administración', 'Gestión administrativa y financiera', NULL, '2025-08-03 04:50:31'),
(14, 'Académico', 'Coordinación académica y docencia', NULL, '2025-08-03 04:50:31'),
(15, 'Bienestar Estudiantil', 'Atención y apoyo a estudiantes', NULL, '2025-08-03 04:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `detalles_evaluacion`
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
-- Dumping data for table `detalles_evaluacion`
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
-- Table structure for table `detalles_nomina`
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
-- Dumping data for table `detalles_nomina`
--

INSERT INTO `detalles_nomina` (`id_detalle`, `id_nomina`, `id_empleado`, `salario_base`, `horas_extras`, `valor_horas_extras`, `bonos`, `comisiones`, `otros_ingresos`, `salud`, `pension`, `prestamos`, `otros_descuentos`, `neto_pagar`, `created_at`) VALUES
(1, 1, 1, 2800.00, 10.00, 140.00, 200.00, 0.00, 0.00, 112.00, 112.00, 0.00, 0.00, 2916.00, '2025-08-03 04:16:45'),
(2, 1, 2, 2500.00, 5.00, 70.00, 150.00, 0.00, 0.00, 100.00, 100.00, 0.00, 0.00, 2520.00, '2025-08-03 04:16:45'),
(3, 1, 3, 1500.00, 0.00, 0.00, 100.00, 0.00, 0.00, 60.00, 60.00, 0.00, 0.00, 1480.00, '2025-08-03 04:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `documentos`
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
-- Dumping data for table `documentos`
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
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_empleado` varchar(20) NOT NULL CHECK (`tipo_empleado` in ('Docente','Administrativo')),
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
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `id_usuario`, `tipo_empleado`, `nombres`, `apellidos`, `fecha_nacimiento`, `genero`, `estado_civil`, `direccion`, `telefono`, `fecha_ingreso`, `activo`, `foto_url`, `created_at`, `updated_at`, `id_departamento`, `id_puesto`, `salario`, `estado`, `periodo_academico_id`) VALUES
(1, 1, 'Administrativo', 'Super', 'Admin', '1980-01-01', NULL, NULL, NULL, NULL, '2010-01-15', 1, NULL, '2025-08-03 01:08:51', '2025-08-03 05:44:08', NULL, NULL, NULL, 'Activo', 1),
(2, 2, 'Administrativo', 'Ana', 'García', '1985-05-10', NULL, NULL, NULL, NULL, '2015-03-20', 1, NULL, '2025-08-03 01:08:51', '2025-08-03 05:44:08', NULL, NULL, NULL, 'Activo', 1),
(3, 3, 'Docente', 'Carlos', 'Pérez', '1990-11-25', NULL, NULL, NULL, NULL, '2018-09-01', 1, NULL, '2025-08-03 01:08:51', '2025-08-03 05:44:08', NULL, NULL, NULL, 'Activo', 1);

-- --------------------------------------------------------

--
-- Table structure for table `empleados_beneficios`
--

CREATE TABLE `empleados_beneficios` (
  `id_empleado_beneficio` int(11) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_beneficio` int(11) DEFAULT NULL,
  `fecha_asignacion` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Vencido') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empleados_capacitaciones`
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
-- Dumping data for table `empleados_capacitaciones`
--

INSERT INTO `empleados_capacitaciones` (`id_empleado_capacitacion`, `id_capacitacion`, `id_empleado`, `asistio`, `aprobo`, `certificado_url`, `created_at`) VALUES
(1, 1, 2, 1, 1, 'certificado_rrhh_empleado_2.pdf', '2025-08-03 04:16:46'),
(2, 2, 1, 1, 1, 'certificado_web_empleado_1.pdf', '2025-08-03 04:16:46'),
(3, 3, 1, 1, 1, 'certificado_liderazgo_empleado_1.pdf', '2025-08-03 04:16:46'),
(4, 3, 2, 1, 1, 'certificado_liderazgo_empleado_2.pdf', '2025-08-03 04:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `empleados_competencias`
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
-- Table structure for table `evaluaciones`
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
-- Dumping data for table `evaluaciones`
--

INSERT INTO `evaluaciones` (`id_evaluacion`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`) VALUES
(1, 'Evaluación Anual 2025', 'Evaluación de desempeño anual', '2025-01-01', '2025-12-31', 'En curso', '2025-08-03 04:16:44'),
(2, 'Evaluación de Competencias', 'Evaluación de competencias laborales', '2025-06-01', '2025-08-31', 'Planificada', '2025-08-03 04:16:44'),
(3, 'Evaluación de Proyectos', 'Evaluación de proyectos realizados', '2025-09-01', '2025-11-30', 'Planificada', '2025-08-03 04:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `evaluaciones_empleados`
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
-- Dumping data for table `evaluaciones_empleados`
--

INSERT INTO `evaluaciones_empleados` (`id_evaluacion_empleado`, `id_evaluacion`, `id_empleado`, `id_evaluador`, `fecha_evaluacion`, `puntaje_total`, `observaciones`, `created_at`) VALUES
(1, 1, 1, 2, '2025-01-15', 85.50, 'Excelente desempeño en gestión de TI', '2025-08-03 04:16:44'),
(2, 1, 2, 1, '2025-01-20', 90.00, 'Liderazgo excepcional en RRHH', '2025-08-03 04:16:44'),
(3, 1, 3, 2, '2025-01-25', 78.50, 'Buen desempeño académico', '2025-08-03 04:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `historial_laboral`
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
-- Table structure for table `nominas`
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
-- Dumping data for table `nominas`
--

INSERT INTO `nominas` (`id_nomina`, `periodo`, `fecha_generacion`, `fecha_pago`, `estado`, `created_at`) VALUES
(1, '2025-08', '2025-08-01', '2025-08-05', 'Pagada', '2025-08-03 04:16:45'),
(2, '2025-07', '2025-07-01', '2025-07-05', 'Pagada', '2025-08-03 04:16:45'),
(3, '2025-06', '2025-06-01', '2025-06-05', 'Pagada', '2025-08-03 04:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `periodos_academicos`
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
-- Dumping data for table `periodos_academicos`
--

INSERT INTO `periodos_academicos` (`id_periodo`, `nombre`, `fecha_inicio`, `fecha_fin`, `estado`, `descripcion`, `created_at`) VALUES
(1, 'Periodo 2025-1', '2025-01-15', '2025-06-30', 'Activo', 'Primer periodo académico del año 2025', '2025-08-03 05:44:08'),
(2, 'Periodo 2025-2', '2025-07-15', '2025-12-15', 'Inactivo', 'Segundo periodo académico del año 2025', '2025-08-03 05:44:08');

-- --------------------------------------------------------

--
-- Table structure for table `permisos`
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
-- Dumping data for table `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `id_empleado`, `tipo_permiso`, `fecha_inicio`, `fecha_fin`, `dias`, `estado`, `motivo`, `archivo_url`, `created_at`) VALUES
(1, 1, 'Vacaciones', '2025-09-01', '2025-09-15', 15, 'Aprobado', 'Vacaciones familiares', NULL, '2025-08-03 04:16:45'),
(2, 2, 'Permiso Personal', '2025-08-10', '2025-08-10', 1, 'Aprobado', 'Cita médica', NULL, '2025-08-03 04:16:45'),
(3, 3, 'Vacaciones', '2025-10-01', '2025-10-07', 7, 'Solicitado', 'Descanso', NULL, '2025-08-03 04:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `postulaciones`
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
-- Dumping data for table `postulaciones`
--

INSERT INTO `postulaciones` (`id_postulacion`, `id_vacante`, `id_candidato`, `fecha_postulacion`, `puntaje_prueba`, `observaciones`, `created_at`) VALUES
(1, 1, 1, '2025-08-05', 85.50, 'Buen candidato, experiencia técnica sólida', '2025-08-03 04:16:46'),
(2, 1, 2, '2025-08-06', 92.00, 'Excelente candidato, muy recomendado', '2025-08-03 04:16:46'),
(3, 2, 3, '2025-08-07', 65.00, 'No cumple requisitos mínimos', '2025-08-03 04:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `puestos`
--

CREATE TABLE `puestos` (
  `id_puesto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `salario_base` decimal(10,2) NOT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `salario_min` decimal(10,2) DEFAULT 0.00,
  `salario_max` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `puestos`
--

INSERT INTO `puestos` (`id_puesto`, `nombre`, `descripcion`, `salario_base`, `id_departamento`, `created_at`, `salario_min`, `salario_max`) VALUES
(1, 'Director de Recursos Humanos', 'Dirige las políticas de talento humano', 2500.00, 1, '2025-08-03 04:16:43', 2000.00, 3000.00),
(2, 'Analista de Recursos Humanos', 'Gestiona procesos de selección y desarrollo', 1800.00, 1, '2025-08-03 04:16:43', 1440.00, 2160.00),
(3, 'Director de TI', 'Dirige el área de tecnología', 2800.00, 2, '2025-08-03 04:16:43', 2240.00, 3360.00),
(4, 'Desarrollador de Sistemas', 'Desarrolla aplicaciones y sistemas', 2000.00, 2, '2025-08-03 04:16:43', 1600.00, 2400.00),
(5, 'Docente', 'Imparte clases y desarrolla contenido académico', 1500.00, 3, '2025-08-03 04:16:43', 1200.00, 1800.00),
(6, 'Coordinador Académico', 'Coordina actividades académicas', 2200.00, 3, '2025-08-03 04:16:43', 1760.00, 2640.00),
(7, 'Administrador', 'Gestiona procesos administrativos', 1600.00, 4, '2025-08-03 04:16:43', 1280.00, 1920.00),
(8, 'Asistente Administrativo', 'Apoya procesos administrativos', 1200.00, 4, '2025-08-03 04:16:43', 960.00, 1440.00),
(9, 'Coordinador de Bienestar', 'Coordina actividades de bienestar estudiantil', 1800.00, 5, '2025-08-03 04:16:43', 1440.00, 2160.00);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`, `created_at`) VALUES
(1, 'SuperAdministrador', 'Acceso total a todas las funcionalidades del sistema.', '2025-08-03 01:08:51'),
(2, 'AdministradorTalentoHumano', 'Acceso a la gestión de empleados, nóminas, reclutamiento y evaluaciones.', '2025-08-03 01:08:51'),
(3, 'Docente', 'Acceso limitado a su información personal, capacitaciones y evaluaciones.', '2025-08-03 01:08:51');

-- --------------------------------------------------------

--
-- Table structure for table `solicitudes`
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
-- Table structure for table `usuarios`
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `cedula`, `email`, `password_hash`, `id_rol`, `activo`, `last_login`, `created_at`, `updated_at`) VALUES
(1, '9999999999', 'superadmin@universidad.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NULL, '2025-08-03 01:08:51', '2025-08-03 01:08:51'),
(2, '8888888888', 'admin.th@universidad.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 1, NULL, '2025-08-03 01:08:51', '2025-08-03 01:08:51'),
(3, '7777777777', 'docente.prueba@universidad.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 1, NULL, '2025-08-03 01:08:51', '2025-08-03 01:08:51');

-- --------------------------------------------------------

--
-- Table structure for table `vacantes`
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
-- Dumping data for table `vacantes`
--

INSERT INTO `vacantes` (`id_vacante`, `id_puesto`, `fecha_publicacion`, `fecha_cierre`, `estado`, `descripcion`, `requisitos`, `created_at`, `nombre`, `salario_min`, `salario_max`) VALUES
(1, 4, '2025-08-01', '2025-08-31', 'Abierta', 'Desarrollador Full Stack', 'Experiencia en PHP, JavaScript, MySQL', '2025-08-03 04:16:45', NULL, 0.00, 0.00),
(2, 6, '2025-08-01', '2025-08-31', 'Abierta', 'Coordinador Académico', 'Maestría en Educación, experiencia en gestión académica', '2025-08-03 04:16:45', NULL, 0.00, 0.00),
(3, 8, '2025-08-01', '2025-08-31', 'Abierta', 'Asistente Administrativo', 'Bachillerato, experiencia en administración', '2025-08-03 04:16:45', NULL, 0.00, 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `idx_asistencias_fecha` (`fecha`);

--
-- Indexes for table `beneficios`
--
ALTER TABLE `beneficios`
  ADD PRIMARY KEY (`id_beneficio`);

--
-- Indexes for table `beneficios_empleados`
--
ALTER TABLE `beneficios_empleados`
  ADD PRIMARY KEY (`id_beneficio_empleado`),
  ADD KEY `id_beneficio` (`id_beneficio`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indexes for table `candidatos`
--
ALTER TABLE `candidatos`
  ADD PRIMARY KEY (`id_candidato`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indexes for table `capacitaciones`
--
ALTER TABLE `capacitaciones`
  ADD PRIMARY KEY (`id_capacitacion`),
  ADD KEY `fk_capacitaciones_periodo` (`periodo_academico_id`),
  ADD KEY `idx_capacitaciones_fecha` (`fecha_inicio`,`fecha_fin`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id_certificado`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_capacitacion` (`id_capacitacion`);

--
-- Indexes for table `competencias`
--
ALTER TABLE `competencias`
  ADD PRIMARY KEY (`id_competencia`);

--
-- Indexes for table `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id_contrato`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_puesto` (`id_puesto`);

--
-- Indexes for table `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id_departamento`),
  ADD KEY `id_jefe` (`id_jefe`);

--
-- Indexes for table `detalles_evaluacion`
--
ALTER TABLE `detalles_evaluacion`
  ADD PRIMARY KEY (`id_detalle_evaluacion`),
  ADD KEY `id_evaluacion_empleado` (`id_evaluacion_empleado`),
  ADD KEY `id_competencia` (`id_competencia`);

--
-- Indexes for table `detalles_nomina`
--
ALTER TABLE `detalles_nomina`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_nomina` (`id_nomina`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indexes for table `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_empleados_periodo` (`periodo_academico_id`),
  ADD KEY `idx_empleados_activo` (`activo`);

--
-- Indexes for table `empleados_beneficios`
--
ALTER TABLE `empleados_beneficios`
  ADD PRIMARY KEY (`id_empleado_beneficio`),
  ADD UNIQUE KEY `unique_empleado_beneficio` (`id_empleado`,`id_beneficio`),
  ADD KEY `id_beneficio` (`id_beneficio`);

--
-- Indexes for table `empleados_capacitaciones`
--
ALTER TABLE `empleados_capacitaciones`
  ADD PRIMARY KEY (`id_empleado_capacitacion`),
  ADD KEY `id_capacitacion` (`id_capacitacion`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indexes for table `empleados_competencias`
--
ALTER TABLE `empleados_competencias`
  ADD PRIMARY KEY (`id_empleado_competencia`),
  ADD UNIQUE KEY `unique_empleado_competencia` (`id_empleado`,`id_competencia`),
  ADD KEY `id_competencia` (`id_competencia`);

--
-- Indexes for table `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id_evaluacion`);

--
-- Indexes for table `evaluaciones_empleados`
--
ALTER TABLE `evaluaciones_empleados`
  ADD PRIMARY KEY (`id_evaluacion_empleado`),
  ADD KEY `id_evaluacion` (`id_evaluacion`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_evaluador` (`id_evaluador`);

--
-- Indexes for table `historial_laboral`
--
ALTER TABLE `historial_laboral`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_puesto_anterior` (`id_puesto_anterior`),
  ADD KEY `id_puesto_nuevo` (`id_puesto_nuevo`);

--
-- Indexes for table `nominas`
--
ALTER TABLE `nominas`
  ADD PRIMARY KEY (`id_nomina`);

--
-- Indexes for table `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indexes for table `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indexes for table `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD PRIMARY KEY (`id_postulacion`),
  ADD KEY `id_vacante` (`id_vacante`),
  ADD KEY `id_candidato` (`id_candidato`);

--
-- Indexes for table `puestos`
--
ALTER TABLE `puestos`
  ADD PRIMARY KEY (`id_puesto`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indexes for table `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `resuelto_por` (`resuelto_por`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `idx_usuarios_activo` (`activo`);

--
-- Indexes for table `vacantes`
--
ALTER TABLE `vacantes`
  ADD PRIMARY KEY (`id_vacante`),
  ADD KEY `id_puesto` (`id_puesto`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `beneficios`
--
ALTER TABLE `beneficios`
  MODIFY `id_beneficio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `beneficios_empleados`
--
ALTER TABLE `beneficios_empleados`
  MODIFY `id_beneficio_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `candidatos`
--
ALTER TABLE `candidatos`
  MODIFY `id_candidato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `capacitaciones`
--
ALTER TABLE `capacitaciones`
  MODIFY `id_capacitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id_certificado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competencias`
--
ALTER TABLE `competencias`
  MODIFY `id_competencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `detalles_evaluacion`
--
ALTER TABLE `detalles_evaluacion`
  MODIFY `id_detalle_evaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `detalles_nomina`
--
ALTER TABLE `detalles_nomina`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `empleados_beneficios`
--
ALTER TABLE `empleados_beneficios`
  MODIFY `id_empleado_beneficio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empleados_capacitaciones`
--
ALTER TABLE `empleados_capacitaciones`
  MODIFY `id_empleado_capacitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `empleados_competencias`
--
ALTER TABLE `empleados_competencias`
  MODIFY `id_empleado_competencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `evaluaciones_empleados`
--
ALTER TABLE `evaluaciones_empleados`
  MODIFY `id_evaluacion_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `historial_laboral`
--
ALTER TABLE `historial_laboral`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nominas`
--
ALTER TABLE `nominas`
  MODIFY `id_nomina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `postulaciones`
--
ALTER TABLE `postulaciones`
  MODIFY `id_postulacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `puestos`
--
ALTER TABLE `puestos`
  MODIFY `id_puesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vacantes`
--
ALTER TABLE `vacantes`
  MODIFY `id_vacante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `beneficios_empleados`
--
ALTER TABLE `beneficios_empleados`
  ADD CONSTRAINT `beneficios_empleados_ibfk_1` FOREIGN KEY (`id_beneficio`) REFERENCES `beneficios` (`id_beneficio`),
  ADD CONSTRAINT `beneficios_empleados_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `capacitaciones`
--
ALTER TABLE `capacitaciones`
  ADD CONSTRAINT `fk_capacitaciones_periodo` FOREIGN KEY (`periodo_academico_id`) REFERENCES `periodos_academicos` (`id_periodo`) ON DELETE SET NULL;

--
-- Constraints for table `certificados`
--
ALTER TABLE `certificados`
  ADD CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `certificados_ibfk_2` FOREIGN KEY (`id_capacitacion`) REFERENCES `capacitaciones` (`id_capacitacion`);

--
-- Constraints for table `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `contratos_ibfk_2` FOREIGN KEY (`id_puesto`) REFERENCES `puestos` (`id_puesto`);

--
-- Constraints for table `departamentos`
--
ALTER TABLE `departamentos`
  ADD CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`id_jefe`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `detalles_evaluacion`
--
ALTER TABLE `detalles_evaluacion`
  ADD CONSTRAINT `detalles_evaluacion_ibfk_1` FOREIGN KEY (`id_evaluacion_empleado`) REFERENCES `evaluaciones_empleados` (`id_evaluacion_empleado`),
  ADD CONSTRAINT `detalles_evaluacion_ibfk_2` FOREIGN KEY (`id_competencia`) REFERENCES `competencias` (`id_competencia`);

--
-- Constraints for table `detalles_nomina`
--
ALTER TABLE `detalles_nomina`
  ADD CONSTRAINT `detalles_nomina_ibfk_1` FOREIGN KEY (`id_nomina`) REFERENCES `nominas` (`id_nomina`),
  ADD CONSTRAINT `detalles_nomina_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_empleados_periodo` FOREIGN KEY (`periodo_academico_id`) REFERENCES `periodos_academicos` (`id_periodo`) ON DELETE SET NULL;

--
-- Constraints for table `empleados_beneficios`
--
ALTER TABLE `empleados_beneficios`
  ADD CONSTRAINT `empleados_beneficios_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `empleados_beneficios_ibfk_2` FOREIGN KEY (`id_beneficio`) REFERENCES `beneficios` (`id_beneficio`);

--
-- Constraints for table `empleados_capacitaciones`
--
ALTER TABLE `empleados_capacitaciones`
  ADD CONSTRAINT `empleados_capacitaciones_ibfk_1` FOREIGN KEY (`id_capacitacion`) REFERENCES `capacitaciones` (`id_capacitacion`),
  ADD CONSTRAINT `empleados_capacitaciones_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `empleados_competencias`
--
ALTER TABLE `empleados_competencias`
  ADD CONSTRAINT `empleados_competencias_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `empleados_competencias_ibfk_2` FOREIGN KEY (`id_competencia`) REFERENCES `competencias` (`id_competencia`);

--
-- Constraints for table `evaluaciones_empleados`
--
ALTER TABLE `evaluaciones_empleados`
  ADD CONSTRAINT `evaluaciones_empleados_ibfk_1` FOREIGN KEY (`id_evaluacion`) REFERENCES `evaluaciones` (`id_evaluacion`),
  ADD CONSTRAINT `evaluaciones_empleados_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `evaluaciones_empleados_ibfk_3` FOREIGN KEY (`id_evaluador`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `historial_laboral`
--
ALTER TABLE `historial_laboral`
  ADD CONSTRAINT `historial_laboral_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `historial_laboral_ibfk_2` FOREIGN KEY (`id_puesto_anterior`) REFERENCES `puestos` (`id_puesto`),
  ADD CONSTRAINT `historial_laboral_ibfk_3` FOREIGN KEY (`id_puesto_nuevo`) REFERENCES `puestos` (`id_puesto`);

--
-- Constraints for table `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD CONSTRAINT `postulaciones_ibfk_1` FOREIGN KEY (`id_vacante`) REFERENCES `vacantes` (`id_vacante`),
  ADD CONSTRAINT `postulaciones_ibfk_2` FOREIGN KEY (`id_candidato`) REFERENCES `candidatos` (`id_candidato`);

--
-- Constraints for table `puestos`
--
ALTER TABLE `puestos`
  ADD CONSTRAINT `puestos_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`);

--
-- Constraints for table `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`resuelto_por`) REFERENCES `empleados` (`id_empleado`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Constraints for table `vacantes`
--
ALTER TABLE `vacantes`
  ADD CONSTRAINT `vacantes_ibfk_1` FOREIGN KEY (`id_puesto`) REFERENCES `puestos` (`id_puesto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
