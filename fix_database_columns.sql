-- Script para arreglar las columnas faltantes en la base de datos
-- Ejecuta este script para agregar las columnas que faltan

-- 1. Agregar columnas faltantes a la tabla puestos
ALTER TABLE `puestos` 
ADD COLUMN IF NOT EXISTS `salario_min` DECIMAL(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `salario_max` DECIMAL(10,2) DEFAULT 0.00;

-- 2. Actualizar los valores de salario_min y salario_max basados en salario_base
UPDATE `puestos` SET 
    `salario_min` = `salario_base` * 0.8,
    `salario_max` = `salario_base` * 1.2 
WHERE `salario_min` = 0 OR `salario_max` = 0;

-- 3. Agregar columnas faltantes a la tabla vacantes
ALTER TABLE `vacantes` 
ADD COLUMN IF NOT EXISTS `nombre` VARCHAR(200) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `salario_min` DECIMAL(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `salario_max` DECIMAL(10,2) DEFAULT 0.00;

-- 4. Actualizar los valores de las vacantes basados en los puestos
UPDATE `vacantes` v 
JOIN `puestos` p ON v.id_puesto = p.id_puesto 
SET 
    v.nombre = p.nombre,
    v.salario_min = p.salario_min,
    v.salario_max = p.salario_max
WHERE v.nombre IS NULL;

-- 5. Agregar columnas faltantes a la tabla empleados
ALTER TABLE `empleados` 
ADD COLUMN IF NOT EXISTS `salario` DECIMAL(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `foto_url` VARCHAR(255) DEFAULT NULL;

-- 6. Actualizar los salarios de empleados basados en los puestos
UPDATE `empleados` e 
JOIN `puestos` p ON e.id_puesto = p.id_puesto 
SET e.salario = p.salario_base 
WHERE e.salario = 0 OR e.salario IS NULL;

-- 7. Agregar columnas faltantes a la tabla asistencias
ALTER TABLE `asistencias` 
ADD COLUMN IF NOT EXISTS `horas_trabajadas` DECIMAL(4,2) DEFAULT NULL;

-- 8. Calcular horas trabajadas basadas en entrada y salida
UPDATE `asistencias` 
SET `horas_trabajadas` = 
    CASE 
        WHEN `hora_entrada` IS NOT NULL AND `hora_salida` IS NOT NULL 
        THEN TIMESTAMPDIFF(MINUTE, `hora_entrada`, `hora_salida`) / 60.0
        ELSE NULL 
    END
WHERE `horas_trabajadas` IS NULL;

-- 9. Agregar índices para mejor rendimiento
CREATE INDEX IF NOT EXISTS `idx_empleados_activo` ON `empleados` (`activo`);
CREATE INDEX IF NOT EXISTS `idx_usuarios_activo` ON `usuarios` (`activo`);
CREATE INDEX IF NOT EXISTS `idx_capacitaciones_fecha` ON `capacitaciones` (`fecha_inicio`, `fecha_fin`);
CREATE INDEX IF NOT EXISTS `idx_asistencias_fecha` ON `asistencias` (`fecha`);

-- 10. Verificar que todas las tablas tengan las columnas necesarias
SELECT 'Verificación completada' as status; 