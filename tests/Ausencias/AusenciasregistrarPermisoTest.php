<?php
//require_once '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class AusenciasregistrarPermisoTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    /**
     * @dataProvider RegistrarPermisosProvider
     */
    public function testRegistrarPermiso($id_trabajador, $tipo_permiso, $descripcion, $desde, $expected_result, $caso){
    
        $resp = $this->administrarEmpleados->registrar_permiso(
            $id_trabajador, 
            $tipo_permiso, 
            $descripcion, 
            $desde
        );

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "modificar") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function RegistrarPermisosProvider()
    {
        return [
            // Test caso 1  registro valido
            ["2","Ausencia","Razón del permiso","2024-09-12","registrar",1],
            // Test caso 2 registro valido
            ["2","falta","Razón del permiso","2024-09-12","registrar",2],
            // Test caso 3: id_trabajador no válido
            ["abc","Ausencia","Razón del permiso","2024-09-12","is-invalid",3],
            // Test caso 4: tipo_permiso no válido
            ["2","<script>code</script>","Razón del permiso","2024-09-12","is-invalid",4],
            // Test caso 5: descripción vacía
            ["2","Ausencia","","2024-09-12","is-invalid",5],
            // Test caso 6: fecha no válida
            ["2","Ausencia","Razón del permiso","2024-02-30","is-invalid",6],
            // Test caso 7: fecha formato invalido
            ["2","Ausencia","Razón del permiso","13-12-2024","is-invalid",7],
            // Test caso 8: id del trabajador inexistente
            ["200","Ausencia","Razón del permiso","2024-09-12","error",8],
            // Test caso 9: id_trabajador vacío
            ["", "Ausencia", "Razón del permiso", "2024-09-12", "is-invalid", 9],
            // Test caso 10: id_trabajador nulo
            [null, "Ausencia", "Razón del permiso", "2024-09-12", "is-invalid", 10],
            // Test caso 11: tipo_permiso vacío
            ["2", "", "Razón del permiso", "2024-09-12", "is-invalid", 11],
            // Test caso 12: tipo_permiso nulo
            ["2", null, "Razón del permiso", "2024-09-12", "is-invalid", 12],
            // Test caso 13: descripción vacía y tipo_permiso vacío
            ["2", "", "", "2024-09-12", "is-invalid", 13],
            // Test caso 14: descripción nula y tipo_permiso nulo
            ["2", null, null, "2024-09-12", "is-invalid", 14],
            // Test caso 15: fecha vacía
            ["2", "Ausencia", "Razón del permiso", "", "is-invalid", 15],
            // Test caso 16: fecha nula
            ["2", "Ausencia", "Razón del permiso", null, "is-invalid", 16],
            // Test caso 17  fecha de permiso ya registrada de otro trabajador
            ["2","Ausencia","Razón del permiso","2024-11-19","registrar",17],
            // Test caso 18  fecha de permiso ya registrada del mismo trabajador
            ["4","Ausencia","Razón del permiso","2024-11-19","error",18],
        ];
    }
}