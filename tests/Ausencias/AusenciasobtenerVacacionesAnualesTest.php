<?php 
use PHPUnit\Framework\TestCase;
class AusenciasobtenerVacacionesAnualesTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    /**
     * @dataProvider obtenerVacacionesAnualesProvider
     */
    public function testObtenerVacacionesAnuales($year, $caso)
    {
        if($caso !=1){
            $this->expectException("Exception");    
        }
        
        $resp = $this->administrarEmpleados->obtener_vacaciones_anuales($year);

        $mensaje = "caso ($caso)";
        

        $this->assertNotNull($resp,$mensaje);
        $this->assertIsArray($resp,$mensaje);
    }

    public function obtenerVacacionesAnualesProvider()
    {
        return [
            // test caso 1: año válido
            [1998, 1],

            // test caso 2: año invalido
            [999, 2],

            // test caso 3: año vacio vacío
            ["", 3],

            // test caso 4: año nulo
            [null, 4],

            // test caso 5: año no es un número
            ["a", 5],

            // test caso 6: año es un número negativo
            [-1, 6],

            // test caso 7: año es un número muy grande
            [PHP_INT_MAX + 1, 7],

            // test caso 8: id_trabajador es un número decimal
            [1.5, 8],

            // test caso 9: id_trabajador sin vacaciones anuales
            [2,  9],

        ];
    }
}