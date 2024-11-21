<?php 
use PHPUnit\Framework\TestCase;
class SueldoNewCargoTest extends TestCase
{
    private $sueldo;

    protected function setUp(): void
    {
        $this->sueldo = new Sueldo;
        $this->sueldo->set_Testing(true);
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider NewCargoProvider
     */
    public function testNewCargo($codigo,$cargo,$replace, $expected_result, $caso){
    
        $resp = $this->sueldo->new_cargo_s(
            $codigo,
            $cargo,
            $replace
        );

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "asignar_sueldo") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function NewCargoProvider()
    {
        return [

            //test caso 1 asignacion valida
            ["999", "Abogado", false, "new_cargo", 1],

            // test caso 2 remplazar cargo enfermero por camillero
            ["748", "camillero", true, "new_cargo",2],

            // test caso 3 remplazar cargo enfermero por camillero pero sin permiso para remplazar
            ["748", "camillero", false, "old_cargo_found",3],

            // test caso 4 codigo de cargo invalido
            ["abc", "camillero", false, "is-invalid",4],

            // test caso 5 cargo invalido
            ["999", "123", false, "is-invalid",5],

            // test caso 6 codigo de cargo vacio
            ["", "camillero", false, "is-invalid",6],

            // test caso 7 cargo vacio
            ["999", "", false, "is-invalid",7],

            // test caso 8 repalce vacio
            ["999", "camillero", "", "is-invalid",8],

            // test caso 9 codigo de cargo null
            [null, "camillero", false, "is-invalid",9],

            // test caso 10 cargo null
            ["999", null, false, "is-invalid",10],

            // test caso 11 repalce null
            ["999", "camillero", null, "is-invalid",11],

            
            
            


            

        ];
    }
}