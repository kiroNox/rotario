AusenciascalculoVacacionesTest.php

<?php 
//require_once '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
class AusenciascalculoVacacionesTest extends TestCase
{
    private $ausencias;

    protected function setUp(): void {
    
        $this->ausencias= new administrar_empleados;
    }
    /**
     * @dataProvider CalcularVacacionesProvider
     */
    public function testCalcularVacaciones($desde, $hasta, $expected_result,$caso){

    	


        $resp = $this->ausencias->calculo_vacaciones(
        	$desde,
        	$hasta,
        );



        $mensaje = "caso ($caso)";
        if(isset($resp["mensaje"]) and $resp["resultado"] != "exito"){
            $mensaje = "($caso)".$resp["mensaje"];
        }


    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function CalcularVacacionesProvider(){
        return [
            // test caso 1 registro válido
            ["2024-09-05", "2024-09-07", "exito", 1],
            // test caso 2 fecha de inicio inválida
            ["2024-02-30", "2024-09-07", "error", 2],
            // test caso 3 fecha de fin inválida
            ["2024-09-05", "2024-13-07", "error", 3],
            // test caso 4 fecha de inicio y fin iguales
            ["2024-09-05", "2024-09-05", "error", 4],
            // test caso 5 fecha de inicio posterior a la fecha de fin
            ["2024-09-07", "2024-09-05", "error", 5],
            // test caso 6 fecha de inicio vacía
            ["", "2024-09-07", "error", 6],
            // test caso 7 fecha de fin vacía
            ["2024-09-05", "", "error", 7],
            // test caso 8 fecha de inicio y fin vacías
            ["", "", "error", 8],
            // test caso 9 fecha de inicio nula
            [null, "2024-09-07", "error", 9],
            // test caso 10 fecha de fin nula
            ["2024-09-05", null, "error", 10],
            // test caso 11 fecha de inicio y fin nulas
            [null, null, "error", 11],
            // test caso 12 fecha de inicio con formato inválido
            ["05/09/2024", "2024-09-07", "error", 12],
            // test caso 13 fecha de fin con formato inválido
            ["2024-09-05", "07/09/2024", "error", 13],
        ];
    }
}