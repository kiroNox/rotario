<?php

class restaurar_bd extends Conexion
{

    function __construct($con = '')
    {
        if(!($con instanceof PDO)){
            $this->con = $this->conecta();
        }
    }

    public function exportar_bd()
    {
        try {

            $this->validar_conexion($this->con);
            $tables = [];
            $result = $this->con->query("SHOW TABLES");
            
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            
            $sqlDump = "";
            
            foreach ($tables as $table) {
                $createTableStmt = $this->con->query("SHOW CREATE TABLE `$table`;")->fetch(PDO::FETCH_ASSOC);
                $sqlDump .= $createTableStmt['Create Table'] . ";\n\n";
                
                $rows = $this->con->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($rows as $row) {
                    $sqlDump .= "INSERT INTO `$table` VALUES(";
                    $values = [];
                    
                    foreach ($row as $value) {
                        $values[] = $this->con->quote($value);
                    }
                    
                    $sqlDump .= implode(", ", $values) . ");\n";
                }
                
                $sqlDump .= "\n\n";
            }
            
            $filePath = "backup/" . BD_NAME . "_backup_" . date("Y-m-d_H-i-s") . ".sql";
            file_put_contents($filePath, $sqlDump);
            
            return ['resultado' => 'exito', 'mensaje' => 'Base de datos exportada correctamente', 'archivo' => $filePath];
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => 'Error al exportar la base de datos: ' . $e->getMessage()];
        }
    }

    public function restaurar_bd($filePath)
    {
        try {
            $sqlDump = file_get_contents($filePath);
            $this->con->exec($sqlDump);
            return ['resultado' => 'exito', 'mensaje' => 'Base de datos restaurada correctamente'];
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => 'Error al restaurar la base de datos: ' . $e->getMessage()];
        }
    }
}