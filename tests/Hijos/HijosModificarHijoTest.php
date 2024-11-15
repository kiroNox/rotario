<?php

use PHPUnit\Framework\TestCase;

class HijosModificarTest extends TestCase
{
    private $hijos;

    protected function setUp(): void {
        $this->hijos = new Hijos;
        $this->hijos->set_Testing(true);
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider modificarHijosProvider 
     */
    public function testModificarHijos($id, $cedula_madre, $cedula_padre, $nombre, 
        $fecha_nacimiento, $genero, $discapacidad, $observacion, $expected_result,$intento)
    {
        $resp = $this->hijos->modificar_hijo_s(
            $id,
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

    public function modificarHijosProvider()
    {
        return [
            // Casos válidos
            ["7", "V-27250544", "V-12434091", "Maria Jose Perez", "2020-01-01", "F", true, "Observación del registro", "modificar_hijo", 1],
            [8, "V-27250544", "", "Pedro Antonio", "2020-02-15", "M", false, "", "modificar_hijo", 2],
            [7, "", "V-12434091", "Ana Maria", "2020-03-20", "F", true, "Hijo registrado", "modificar_hijo", 3],
            
            // Cedula madre inválida
            [7, "27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", true, "", "is-invalid", 4],
            [8, "V-2725", "V-12434091", "Juan Perez", "2020-01-01", "M", true, "", "is-invalid", 5],
            
            // Cedula padre inválida
            [7, "V-27250544", "12434091", "Juan Perez", "2020-01-01", "M", true, "", "is-invalid", 6],
            [8, "V-27250544", "V-1544", "Juan Perez", "2020-01-01", "M", true, "", "is-invalid", 7],
            
            // Nombre inválido
            [7, "V-27250544", "V-12434091", "Juan123", "2020-01-01", "M", true, "", "is-invalid", 8],
            [8, "V-27250544", "V-12434091", "", "2020-01-01", "M", true, "", "is-invalid", 9],
            [7, "V-27250544", "V-12434091", str_repeat("a", 61), "2020-01-01", "M", true, "", "is-invalid", 10],

            // genero invalido
            [7, "", "V-12434091", "Ana Maria", "2020-03-20", "X", true, "Hijo registrado", "is-invalid", 3],
            [7, "", "V-12434091", "Ana Maria", "2020-03-20", "7", true, "Hijo registrado", "is-invalid", 3],
            [7, "", "V-12434091", "Ana Maria", "2020-03-20", 7, true, "Hijo registrado", "is-invalid", 3],
            [7, "", "V-12434091", "Ana Maria", "2020-03-20", NULL, true, "Hijo registrado", "is-invalid", 3],
            
            // Fecha de nacimiento inválida
            [7, "V-27250544", "V-12434091", "Juan Perez", "2020-13-01", "M", true, "", "is-invalid", 11],
            [8, "V-27250544", "V-12434091", "Juan Perez", "2020-00-01", "M", true, "", "is-invalid", 12],
            [3, "V-27250544", "V-12434091", "Juan Perez", "2020-12-32", "M", true, "", "is-invalid", 13],
            
            // Discapacidad inválida
            [7, "V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", "yes", "", "error", 14],
            [8, "V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", 1, "", "error", 15],
            
            // Observación inválida
            [7, "V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", true, str_repeat("a", 101), "is-invalid", 16],
            [8, "V-27250544", "V-12434091", "Juan Perez", "2020-01-01", "M", true, "@@##$$<script>", "is-invalid", 17],
            // id invalido
            [100, "V-27250544", "V-12434091", "Maria Jose Perez", "2020-01-01", "F", true, "Observación del registro", "error", 1],
            ["trabajador", "V-27250544", "V-12434091", "Maria Jose Perez", "2020-01-01", "F", true, "Observación del registro", "error", 1],
            [NULL, "V-27250544", "V-12434091", "Maria Jose Perez", "2020-01-01", "F", true, "Observación del registro", "error", 1],
        ];
    }
}