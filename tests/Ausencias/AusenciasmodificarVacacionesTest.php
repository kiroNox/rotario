<?php 
//require_once '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
class AusenciasmodificarVacacionesTest extends TestCase
{
    private $ausencias;

    protected function setUp(): void {
    
        $this->ausencias= new administrar_empleados;
        $this->ausencias->set_Testing(true);
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
        if(isset($resp["mensaje"]) and $resp["resultado"] != "modificar"){
            $mensaje = "($caso)".$resp["mensaje"];
        }


    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function ModificarVacacionesProvider(){
        return [
            // test caso 1 modificación valida
            ["2024-09-05","2024-09-07","2","queso","20","modificar",1],
            
            // test caso 2 fecha de inicio inválida
            ["2024-02-30","2024-09-07","2","queso","20","is-invalid",2],
            
            // test caso 3 fecha de fin inválida
            ["2024-09-05","2024-13-07","2","queso","20","is-invalid",3],
            
            // test caso 4 fecha de inicio y fin iguales
            ["2024-09-05","2024-09-05","2","queso","20","error",4],
            
            // test caso 5 fecha de inicio posterior a la fecha de fin
            ["2024-09-07","2024-09-05","2","queso","20","error",5],
            
            // test caso 6 fecha de inicio vacía
            ["","2024-09-07","2","queso","2","is-invalid",6],
            
            // test caso 7 fecha de fin vacía
            ["2024-09-05","","2","queso","20","is-invalid",7],
            
            // test caso 8 fecha de inicio y fin vacías
            ["","","2","queso","20","is-invalid",8],
            
            // test caso 9 fecha de inicio nula
            [null,"2024-09-07","2","queso","20","is-invalid",9],
            
            // test caso 10 fecha de fin nula
            ["2024-09-05",null,"2","queso","20","is-invalid",10],
            
            // test caso 11 fecha de inicio y fin nulas
            [null,null,"2","queso","20","is-invalid",11],
            
            // test caso 12 descripción vacía
            ["2024-09-05","2024-09-07","2","","20","is-invalid",12],
            
            // test caso 13 descripción nula
            ["2024-09-05","2024-09-07","2",null,"20","is-invalid",13],
            
            // test caso 14 id_tabla vacío
            ["2024-09-05","2024-09-07","20","queso","","is-invalid",14],
            
            // test caso 15 id_tabla nulo
            ["2024-09-05","2024-09-07","20","queso",null,"is-invalid",15],
            // test caso 16 id_tabla invalido
            ["2024-09-05","2024-09-07","20","queso","id del registro","is-invalid",16],
            // test caso 17 id_tabla inexistente
            ["2024-09-05","2024-09-07","2","queso",999,"error",17],
            
        ];
    }
}