<?php 
//require_once '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
class AusenciasmodificarVacacionesTest extends TestCase
{
    private $ausencias;

    protected function setUp(): void {
    
        $this->ausencias= new administrar_empleados;
    }
    /**
     * @dataProvider ModificarVacacionesProvider
     */
    public function testModificarVacaciones($desde, $hasta, $dias_totales, $descripcion, $id_tabla, $expected_result,$caso){

    	


        $resp = $this->ausencias->modificar_vacaciones(
        	$desde,
        	$hasta,
        	$dias_totales,
        	$descripcion,
        	$id_tabla
        );



        $mensaje = "caso ($caso)";
        if(isset($resp["mensaje"]) and $resp["resultado"] != "exito"){
            $mensaje = "($caso)".$resp["mensaje"];
        }


    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function ModificarVacacionesProvider(){
    	return [
    		// test caso 1 modificación valida
    		["2024-09-05","2024-09-07","2","queso","2","exito",1],
    	];
    }
}