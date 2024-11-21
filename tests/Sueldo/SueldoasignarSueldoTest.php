<?php 
use PHPUnit\Framework\TestCase;
class SueldoasignarSueldoTest extends TestCase
{
    private $sueldo;

    protected function setUp(): void
    {
        $this->sueldo = new Sueldo;
        $this->sueldo->set_Testing(true);
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider asignarSueldoProvider
     */
    public function testAsignarSueldo($id_trabajador, $sueldo_base, $cargo, $sector_salud, $id_escalafon, $tipo_nomina, $expected_result, $caso){
    
        $resp = $this->sueldo->asignar_sueldo_s(
            $id_trabajador, 
            $sueldo_base, 
            $cargo, 
            $sector_salud,
            $id_escalafon, 
            $tipo_nomina
        );

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "asignar_sueldo") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function asignarSueldoProvider()
    {
        return [

            //test caso 1 asignacion valida
            ["3", "22.500.000,00", "748",true, "13","1", "asignar_sueldo", 1],

            //test caso 2 id trabajador invalido
            ["abc", "22.500.000,00", "748",true, "13","1", "is-invalid", 2],

            //test caso 3 sueldo base invalido
            ["3", "abc", "748",true, "13","1", "is-invalid", 3],

            //test caso 4 cargo invalido
            ["3", "22.500.000,00", "abc",true, "13","1", "is-invalid", 4],
            
            //test caso 5 sector salud invalido
            ["3", "22.500.000,00", "748","abc", "13","1", "is-invalid", 5],
            
            //test caso 6 id escalafon invalido
            ["3", "22.500.000,00", "748",true, "abc","1", "is-invalid", 6],
            
            //test caso 7 tipo nomina invalido
            ["3", "22.500.000,00", "748",true, "13","abc", "is-invalid", 7],
            
            //test caso 8 id trabajador vacio
            ["", "22.500.000,00", "748",true, "13","1", "is-invalid", 8],
            
            //test caso 9 sueldo base vacio
            ["3", "", "748",true, "13","1", "is-invalid", 9],
            
            //test caso 10 cargo vacio
            ["3", "22.500.000,00", "",true, "13","1", "is-invalid", 10],
            
            //test caso 11 sector salud vacio
            ["3", "22.500.000,00", "748","", "13","1", "is-invalid", 11],
            
            //test caso 12 id escalafon vacio
            ["3", "22.500.000,00", "748",true, "", "1", "is-invalid", 12],
            
            //test caso 13 tipo nomina vacio
            ["3", "22.500.000,00", "748",true, "13", "", "is-invalid", 13],

            //test caso 14 id trabajador Null
            [null, "22.500.000,00", "748",true, "13","1", "is-invalid", 14],
            
            //test caso 15 sueldo base Null
            ["3", null, "748",true, "13","1", "is-invalid", 15],
            
            //test caso 16 cargo Null
            ["3", "22.500.000,00", null,true, "13","1", "is-invalid", 16],
            
            //test caso 17 sector salud Null
            ["3", "22.500.000,00", "748",null, "13","1", "is-invalid", 17],
            
            //test caso 18 id escalafon Null
            ["3", "22.500.000,00", "748",true, null,"1", "is-invalid", 18],
            
            //test caso 19 tipo nomina Null
            ["3", "22.500.000,00", "748",true, "13", null, "is-invalid", 19],

        ];
    }
}