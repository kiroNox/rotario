<?php 
//error_reporting(E_ALL);
use PHPUnit\Framework\TestCase;
class SueldoeliminarSueldoTest extends TestCase
{
    private $sueldo;

    protected function setUp(): void
    {
        $this->sueldo = new Sueldo;
        $this->sueldo->set_Testing(true);
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider eliminarSueldoProvider
     */
    public function testEliminarSueldo($id_trabajador, $expected_result, $caso){
    
        $resp = $this->sueldo->eliminar_sueldo_s(
            $id_trabajador
        );

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "eliminar_sueldo") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function eliminarSueldoProvider()
    {
        return [

            //test caso 1 Eliminar sueldo valido
            ["2","eliminar_sueldo", 1],
            //test caso 2 Eliminar sueldo valido
            ["trabajador","is-invalid", 2],
            //test caso 3 Eliminar sueldo inexistente
            ["999","error", 3],
            //test caso 4 Eliminar sueldo id nulo
            [NULL,"is-invalid", 4],
            //test caso 5 Eliminar sueldo id vacío
            ["","is-invalid", 5],
        ];
    }
}