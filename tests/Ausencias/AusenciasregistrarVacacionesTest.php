<?php 
//require_once '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
class AusenciasregistrarVacacionesTest extends TestCase
{
    private $ausencias;

    protected function setUp(): void {
    
        $this->ausencias= new administrar_empleados;
        $this->ausencias->set_Testing(true);
    }
    /**
     * @dataProvider RegistrarVacacionesProvider
     */
    public function testRegistrarVacaciones($desde, $hasta, $dias_totales, $descripcion, $id_trabajador, $expected_result,$caso){

    	


        $resp = $this->ausencias->registrar_vacaciones(
        	$desde,
        	$hasta,
        	$dias_totales,
        	$descripcion,
        	$id_trabajador
        );



        $mensaje = "caso ($caso)";
        if(isset($resp["mensaje"]) and $resp["resultado"] != "exito"){
            $mensaje = "($caso)".$resp["mensaje"];
        }


    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function RegistrarVacacionesProvider(){
        return [
            // test caso 1 registro válido
            ["2024-09-05","2024-09-07","2","queso","2","registrar",1],
            // test caso 2 fecha de inicio inválida
            ["2024-02-30","2024-09-07","2","queso","2","is-invalid",2],
            // test caso 3 fecha de fin inválida
            ["2024-09-05","2024-13-07","2","queso","2","is-invalid",3],
            // test caso 4 fecha de inicio y fin iguales
            ["2024-09-05","2024-09-05","2","queso","2","error",4],
            // test caso 5 fecha de inicio posterior a la fecha de fin
            ["2024-09-07","2024-09-05","2","queso","2","error",5],
            // test caso 6 fecha de inicio vacía
            ["","2024-09-07","2","queso","2","is-invalid",6],
            // test caso 7 fecha de fin vacía
            ["2024-09-05","","2","queso","2","is-invalid",7],
            // test caso 8 fecha de inicio y fin vacías
            ["","","2","queso","2","is-invalid",8],
            // test caso 9 fecha de inicio nula
            [null,"2024-09-07","2","queso","2","is-invalid",9],
            // test caso 10 fecha de fin nula
            ["2024-09-05",null,"2","queso","2","is-invalid",10],
            // test caso 11 fecha de inicio y fin nulas
            [null,null,"2","queso","2","is-invalid",11],
            // test caso 12 fecha de inicio con formato inválido
            ["05/09/2024","2024-09-07","2","queso","2","is-invalid",12],
            // test caso 13 fecha de fin con formato inválido
            ["2024-09-05","07/09/2024","2","queso","2","is-invalid",13],
            // test caso 14 descripción vacía
            ["2024-09-05","2024-09-07","2","","2","is-invalid",14],
            // test caso 15 descripción nula
            ["2024-09-05","2024-09-07","2",null,"2","is-invalid",15],
            // test caso 16 id_trabajador vacío
            ["2024-09-05","2024-09-07","2","queso","","is-invalid",16],
            // test caso 17 id_trabajador nulo
            ["2024-09-05","2024-09-07","2","queso",null,"is-invalid",17],
            // test caso 18 dias totales invalido
            ["2024-09-05","2024-09-07","hola","queso","2","is-invalid",18],
            // test caso 19 dias totales incorrecto
            //["2024-09-05","2024-09-07","9",25,"2","is-invalid",19],

        ];
    }
}