<?php 
use PHPUnit\Framework\TestCase;
class FormulagetFormulaTest extends TestCase
{
    private $formulas;

    protected function setUp(): void
    {
        $this->formulas = new Formulas;
        $this->formulas->set_Testing(true);
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider GetFormulaProvider
     */
    public function testGetSueldo($id_formula, $expected_result, $caso){
    
        $resp = $this->formulas->get_formula_s(
            $id_formula
        );

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "get_formula") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function GetFormulaProvider()
    {
        return [

            //test caso 1 Eliminar formula valido
            ["52","get_formula", 1],
            //test caso 2 Eliminar formula valido
            ["formula","error", 2],
            //test caso 3 Eliminar formula inexistente
            ["999","error", 3],
            //test caso 4 Eliminar formula id nulo
            [NULL,"error", 4],
            //test caso 5 Eliminar formula id vacío
            ["","error", 5],
        ];
    }
}