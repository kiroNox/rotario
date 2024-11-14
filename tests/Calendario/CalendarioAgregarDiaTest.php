<?php
//require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class CalendarioAgregarDiaTest extends TestCase
{
    private $calendario;

    protected function setUp(): void {
        $this->calendario = new calendario;
        $_SESSION['usuario_rotario'] = 2;
    }

    /**
     * @dataProvider agregarDiaProvider 
     */
    public function testAgregarDia($description,$fecha,$recurrente,$expected_result,$caso)
    {
        $resp = $this->calendario->agregar_dia(
            $description,
            $fecha, 
            $recurrente
        );

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);
        $mensaje = "caso ($caso)";
        if(isset($resp["mensaje"]) and $resp["resultado"] != "exito"){
            $mensaje = "($caso)".$resp["mensaje"];
        }
        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function agregarDiaProvider()
{
    return [
        // test caso 1 registro valido recurrente
        ["dia feriado","2026-10-02","1", "exito", 1],
        // test caso 2 registro valido no recurrente
        ["dia feriado","2026-10-02","0", "exito", 2],
        // test caso 3 registro invalido (fecha no válida)
        ["dia feriado","2024-02-30","0", "error", 3],
        // test caso 4 registro invalido (descripcion vacía)
        ["","2026-10-02","0", "error", 4],
        // test caso 5 registro invalido (recurrente no válido)
        ["dia feriado","2026-10-02","2", "error", 5],
        // test caso 6 registro valido con fecha en el pasado
        ["dia feriado","2022-11-04","0", "exito", 6],
        // test caso 7 registro valido con fecha en el futuro
        ["dia feriado","2026-11-04","0", "exito", 7],
        // test caso 8 registro invalido (descripcion nula)
        [null,"2026-10-02","0", "error", 8],
        // test caso 9 registro invalido (fecha nula)
        ["dia feriado",null,"0", "error", 9],
        // test caso 10 registro invalido (recurrente nulo)
        ["dia feriado","2026-10-02",null, "error", 10],
        // test caso 11 registro invalido (descripcion vacía y fecha vacía)
        ["","2026-10-02","0", "error", 11],
        // test caso 12 registro invalido (descripcion vacía y fecha nula)
        ["",null,"0", "error", 12],
        // test caso 13 registro invalido (descripcion nula y fecha vacía)
        [null,"2026-10-02","0", "error", 13],
        // test caso 14 registro invalido (descripcion nula y fecha nula)
        [null,null,"0", "error", 14],
        // test caso 15 registro invalido (fecha repetida)
        ["dia feriado","2024-11-04","0", "error", 15],
    ];
}
}