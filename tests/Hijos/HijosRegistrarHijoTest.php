<?php
//require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class HijosRegistrarTest extends TestCase
{
    private $hijos;

    protected function setUp(): void {
        $this->hijos = new Hijos;
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider registrarHijosProvider 
     */
    public function testRegistrarHijos($cedula_madre, $cedula_padre, $nombre, 
        $fecha_nacimiento, $genero, $discapacidad, $observacion, $expected_result,$intento)
    {
        $resp = $this->hijos->registrar_hijo_s(
            $cedula_madre,
            $cedula_padre, 
            $nombre,
            $fecha_nacimiento,
            $genero,
            $discapacidad,
            $observacion
        );

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);
        $mensaje = "intento ($intento)";
        if(isset($resp["mensaje"])){
            $mensaje = "($intento)".$resp["mensaje"]." line:".$resp["line"];
        }
        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function registrarHijosProvider()
{
    return [
        // Valid cases
        ["V-27250544", "V-12434091", "Maria Jose Perez", "2020-01-01", "F", true, "Observación del registro", "registrar_hijo",1],
        ["V-27250544", "", "Pedro Antonio", "2020-02-15", "M", false, "", "registrar_hijo",2],
        ["", "V-12434091", "Ana Maria", "2020-03-20", "F", true, "Hijo registrado", "registrar_hijo",3],
        
        // Invalid cedula format
        ["27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", true, "", "is-invalid",4],
        ["V-27250544", "12434091", "Juan Perez", "2020-01-01", "M", true, "", "is-invalid",5],
        ["V-2725", "V-12434091", "Juan Perez", "2020-01-01", "M", true, "", "is-invalid",6],
        ["V-27250544", "V-1544", "Juan Perez", "2020-01-01", "M", true, "", "is-invalid",7],
        
        // Both parents empty
        ["", "", "Juan Perez", "2020-01-01", "M", true, "", "error",8],
        
        // Invalid names
        ["V-27250544", "V-12434091", "Juan123", "2020-01-01", "M", true, "", "is-invalid",9],
        ["V-27250544", "V-12434091", "", "2020-01-01", "M", true, "", "is-invalid",19],
        ["V-27250544", "V-12434091", str_repeat("a", 61), "2020-01-01", "M", true, "", "is-invalid",20],
        
        // Invalid dates
        ["V-27250544", "V-12434091", "Juan Perez", "2020-13-01", "M", true, "", "is-invalid",10],
        ["V-27250544", "V-12434091", "Juan Perez", "2020-00-01", "M", true, "", "is-invalid",11],
        ["V-27250544", "V-12434091", "Juan Perez", "2020-12-32", "M", true, "", "is-invalid",12],
        ["V-27250544", "V-12434091", "Juan Perez", "1899-12-01", "M", true, "", "is-invalid",13],
        ["V-27250544", "V-12434091", "Juan Perez", "2020/12/01", "M", true, "", "registrar_hijo",14],
        
        // Invalid disability
        ["V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", "yes", "", "error",15],
        ["V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", 1, "", "error",21],
        ["V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", null, "", "error",16],
        
        // Invalid observations
        ["V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", true, str_repeat("a", 101), "is-invalid",17],
        ["V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", true, "@@##$$", "is-invalid",18]
    ];
}
}