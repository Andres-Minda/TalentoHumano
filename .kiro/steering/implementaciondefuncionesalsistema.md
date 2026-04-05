---
inclusion: always
---
# Reglas Inteligentes para Implementación de Funciones

## CONFIGURACIÓN DE BASE DE DATOS

### Información de Conexión
- **Sistema**: MariaDB (phpMyAdmin - XAMPP)
- **Host**: localhost
- **Puerto**: 3306
- **Usuario**: root  
- **Password**: `` (VACÍO - sin contraseña)
- **Base de datos**: bienestar_estudiantil_db
- **Charset**: utf8mb4

### Rutas de Acceso
- **MySQL CLI**: `C:\xampp\mysql\bin\mysql.exe`
- **MySQLDump**: `C:\xampp\mysql\bin\mysqldump.exe`
- **phpMyAdmin**: http://localhost/phpmyadmin

## PROTOCOLO DE IMPLEMENTACIÓN DE FUNCIONES

### PASO 1: ANÁLISIS AUTOMÁTICO DE BASE DE DATOS
**ANTES de implementar cualquier nueva función, SIEMPRE:**

1. **Revisar estado actual de la BD:**
```bash
# Ver todas las tablas existentes
php db_manager.php show-tables

# Analizar estructura de tablas relevantes
php db_manager.php describe --table "nombre_tabla"
```

2. **Identificar qué necesita la nueva función:**
   - ¿Qué datos necesita almacenar/recuperar?
   - ¿Qué tablas necesita consultar?
   - ¿Necesita nuevas tablas, columnas o relaciones?
   - ¿Requiere índices para optimización?

3. **Analizar impacto en datos existentes:**
   - ¿Los cambios afectan datos actuales?
   - ¿Se necesita migración de datos?
   - ¿Hay que mantener compatibilidad?

### PASO 2: PLANIFICACIÓN DE CAMBIOS
**Si se necesitan cambios en BD, crear plan estructurado:**

```markdown
## Análisis para función: [NOMBRE_FUNCIÓN]

### Tablas actuales relevantes:
- tabla1: [descripción de campos relevantes]
- tabla2: [descripción de campos relevantes]

### Cambios necesarios:
1. **Nuevas tablas:**
   - tabla_nueva: [campos y tipos]
   
2. **Modificaciones a tablas existentes:**
   - tabla_existente: agregar columna_nueva (tipo)
   
3. **Nuevas relaciones:**
   - FK entre tabla1.id y tabla2.tabla1_id

### Plan de ejecución:
1. Backup automático
2. Crear/modificar estructura
3. Insertar datos iniciales (si es necesario)
4. Verificar integridad
```

### PASO 3: IMPLEMENTACIÓN CON BACKUP AUTOMÁTICO

**Siempre crear backup antes de cambios importantes:**
```bash
# Backup automático antes de cambios
php db_manager.php backup
```

**Ejecutar cambios de BD ANTES del código PHP:**
```bash
# Ejemplo de cambios típicos
php db_manager.php execute --sql "CREATE TABLE nueva_tabla (id INT PRIMARY KEY AUTO_INCREMENT, nombre VARCHAR(100))"
php db_manager.php add-column --table "usuarios" --column "nueva_columna" --type "VARCHAR(50)"
```

### PASO 4: VERIFICACIÓN POST-CAMBIOS
```bash
# Verificar que los cambios se aplicaron correctamente
php db_manager.php show-tables
php db_manager.php describe --table "tabla_modificada"
```

## COMANDOS ESPECÍFICOS PARA ANÁLISIS

### Comandos de Diagnóstico Rápido
```bash
# Ver estructura completa de una tabla
C:\xampp\mysql\bin\mysql.exe -u root bienestar_estudiantil_db -e "DESCRIBE tabla_name;"

# Ver relaciones/foreign keys
C:\xampp\mysql\bin\mysql.exe -u root bienestar_estudiantil_db -e "SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = 'bienestar_estudiantil_db';"

# Ver índices de una tabla
C:\xampp\mysql\bin\mysql.exe -u root bienestar_estudiantil_db -e "SHOW INDEX FROM tabla_name;"

# Contar registros en tablas
C:\xampp\mysql\bin\mysql.exe -u root bienestar_estudiantil_db -e "SELECT COUNT(*) as total FROM tabla_name;"
```

### Script de Análisis Completo
```php
<?php
// db_analyzer.php - Usar antes de implementar funciones
class DatabaseAnalyzer extends DatabaseManager
{
    public function analyzeForFunction($functionName, $requirements)
    {
        echo "=== ANÁLISIS PARA FUNCIÓN: $functionName ===\n\n";
        
        // 1. Estado actual
        echo "TABLAS EXISTENTES:\n";
        $tables = $this->getAllTables();
        foreach ($tables as $table) {
            echo "- $table\n";
            $structure = $this->getTableStructure($table);
            foreach ($structure as $field) {
                echo "  * {$field['Field']} ({$field['Type']})\n";
            }
            echo "\n";
        }
        
        // 2. Análisis de requerimientos
        echo "REQUERIMIENTOS DE LA FUNCIÓN:\n";
        foreach ($requirements as $req) {
            echo "- $req\n";
        }
        
        // 3. Recomendaciones
        echo "\nRECOMENDACIONES:\n";
        $this->generateRecommendations($requirements, $tables);
    }
    
    private function generateRecommendations($requirements, $existingTables)
    {
        // Lógica para recomendar cambios basada en requerimientos
        echo "- Revisar si las tablas existentes cubren los requerimientos\n";
        echo "- Identificar nuevas tablas necesarias\n";
        echo "- Planificar relaciones entre tablas\n";
    }
    
    public function checkDataIntegrity()
    {
        echo "=== VERIFICACIÓN DE INTEGRIDAD ===\n";
        
        $tables = $this->getAllTables();
        foreach ($tables as $table) {
            $count = $this->connection->query("SELECT COUNT(*) as total FROM `$table`")->fetch_assoc();
            echo "$table: {$count['total']} registros\n";
        }
    }
}
?>
```

## FLUJO DE TRABAJO OBLIGATORIO

### Para CADA nueva función solicitada:

```markdown
1. **EJECUTAR ANÁLISIS:**
   ```bash
   php db_manager.php show-tables
   # Revisar tablas existentes relevantes para la función
   ```

2. **EVALUAR NECESIDADES:**
   - ¿La función requiere nuevos datos?
   - ¿Necesita modificar estructura existente?
   - ¿Hay que crear relaciones nuevas?

3. **SI SE NECESITAN CAMBIOS:**
   ```bash
   # Backup automático
   php db_manager.php backup
   
   # Ejecutar cambios necesarios
   php db_manager.php execute --sql "SQL_COMMANDS_HERE"
   ```

4. **VERIFICAR CAMBIOS:**
   ```bash
   php db_manager.php describe --table "tabla_afectada"
   ```

5. **IMPLEMENTAR CÓDIGO PHP/CodeIgniter:**
   - Crear/modificar controladores
   - Actualizar modelos con nuevos campos/relaciones
   - Crear/actualizar vistas

6. **DOCUMENTAR CAMBIOS:**
   ```bash
   # Actualizar documentación de BD
   php database_info.php
   ```
```

## REGLAS ESPECÍFICAS DE CODEIGNITER

### Modelos - Siempre actualizar después de cambios de BD:
```php
// Agregar nuevos campos a $fillable si existen
protected $fillable = ['campo1', 'campo2', 'nuevo_campo'];

// Actualizar relaciones si se agregaron FKs
public function relacion_nueva() {
    return $this->belongsTo('Modelo_relacionado');
}
```

### Controladores - Validar nuevos campos:
```php
$this->form_validation->set_rules('nuevo_campo', 'Nuevo Campo', 'required');
```

## CASOS ESPECIALES

### Funciones que NO requieren cambios de BD:
- Reportes con datos existentes
- Cambios de UI/UX solamente  
- Nuevas validaciones en frontend
- Modificaciones de lógica de negocio sin nuevos datos

### Funciones que SÍ requieren cambios de BD:
- Nuevos módulos/funcionalidades
- Campos adicionales en formularios
- Nuevos tipos de usuarios/roles
- Sistemas de notificaciones
- Logs/auditoría
- Configuraciones de sistema

## MENSAJE DE CONFIRMACIÓN

**SIEMPRE incluir en la respuesta:**
```
✅ ANÁLISIS DE BD COMPLETADO
📊 Estado actual revisado
🔧 Cambios necesarios: [LISTAR]
💾 Backup creado: [nombre_archivo]
⚡ Cambios aplicados exitosamente
📝 Código implementado y probado
```

## RECORDATORIOS CRÍTICOS
```php
// En db_manager.php - cambiar línea:
private $database = 'bienestar_estudiantil_db';
```

### Comandos corregidos con el nombre real de la BD:
```bash
# Mostrar todas las tablas de bienestar_estudiantil_db
C:\xampp\mysql\bin\mysql.exe -u root bienestar_estudiantil_db -e "SHOW TABLES;"

# Backup de la base de datos específica
C:\xampp\mysql\bin\mysqldump.exe -u root bienestar_estudiantil_db > backup_bienestar_estudiantil.sql

# Conectar directamente a la BD
C:\xampp\mysql\bin\mysql.exe -u root bienestar_estudiantil_db
```

- ✅ **Password de BD**: VACÍO (sin contraseña)
- ✅ **Siempre analizar BD antes de implementar**
- ✅ **Backup automático antes de cambios**
- ✅ **Verificar integridad post-cambios**  
- ✅ **Documentar todos los cambios realizados**
- ✅ **Usar comandos específicos de MariaDB/MySQL**