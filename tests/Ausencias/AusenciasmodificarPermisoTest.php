<?php 
use PHPUnit\Framework\TestCase;
class AusenciasmodificarPermisoTest extends TestCase
{
    private $administrarEmpleados;

    protected function setUp(): void
    {
        $this->administrarEmpleados = new administrar_empleados;
        $this->administrarEmpleados->set_Testing(true);
    }

    /**
     * @dataProvider ModificarPermisosProvider
     */
    public function testModificarPermiso($tipo_permiso, $descripcion, $desde, $id_tabla, $expected_result, $caso){
    
        $resp = $this->administrarEmpleados->modificar_permiso(
            $tipo_permiso, 
            $descripcion, 
            $desde, 
            $id_tabla
        );

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "modificar") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function ModificarPermisosProvider()
    {
        return [
            // Test caso 1: modificación válida
            ["Ausencia", "Razón del permiso", "2024-09-12", 15, "modificar", 1],
            // Test caso 2: tipo_permiso no válido
            ["<script>code</script>", "Razón del permiso", "2024-09-12", 15, "is-invalid", 2],
            // Test caso 3: descripción vacía
            ["Ausencia", "", "2024-09-12", 15, "is-invalid", 3],
            // Test caso 4: fecha no válida
            ["Ausencia", "Razón del permiso", "2024-02-30", 15, "is-invalid", 4],
            // Test caso 5: fecha formato invalido
            ["Ausencia", "Razón del permiso", "13-12-2024", 15, "is-invalid", 5],
            // Test caso 6: id_tabla no válido
            ["Ausencia", "Razón del permiso", "2024-09-12", "abc", "is-invalid", 6],
            // Test caso 7: id_tabla vacío
            ["Ausencia", "Razón del permiso", "2024-09-12", "", "is-invalid", 7],
            // Test caso 8: id_tabla nulo
            ["Ausencia", "Razón del permiso", "2024-09-12", null, "is-invalid", 8],
            // Test caso 9: modificación a permiso existente
            ["Ausencia", "Razón del permiso", "2024-11-19", 25, "error", 9],
            // Test caso 10: id_tabla no existe en la base de datos
            ["Ausencia", "Razón del permiso", "2024-09-12", 99999, "error", 10],
            // Test caso 11: tipo_permiso vacío
            ["", "Razón del permiso", "2024-09-12", 15, "is-invalid", 11],

            // Test caso 12: tipo_permiso nulo
            [null, "Razón del permiso", "2024-09-12", 15, "is-invalid", 12],

            // Test caso 13: descripción vacía
            ["Ausencia", "", "2024-09-12", 15, "is-invalid", 13],

            // Test caso 14: descripción nula
            ["Ausencia", null, "2024-09-12", 15, "is-invalid", 14],

            // Test caso 15: fecha vacía
            ["Ausencia", "Razón del permiso", "", 15, "is-invalid", 15],

            // Test caso 16: fecha nula
            ["Ausencia", "Razón del permiso", null, 15, "is-invalid", 16],

            // Test caso 17: id_tabla vacío
            ["Ausencia", "Razón del permiso", "2024-09-12", "", "is-invalid", 17],

            // Test caso 18: id_tabla nulo
            ["Ausencia", "Razón del permiso", "2024-09-12", null, "is-invalid", 18],

            // Test caso 19: tipo_permiso demasiado largo
            ["Ausencia muy larga que supera el límite de caracteres", "Razón del permiso", "2024-09-12", 15, "is-invalid", 19],

            // Test caso 20: descripción demasiado larga
            ["Ausencia", "Razón del permiso muy larga que supera el límite de caracteres", "2024-09-12", 15, "is-invalid", 20],
            // Test caso 23: id_tabla no existe en la base de datos
            ["Ausencia", "Razón del permiso", "2024-09-12", 99999, "error", 23],
            // Test caso 24: descripción contiene caracteres especiales
            ["Ausencia", "Razón de perm caracter especial !@#$%^&*()", "2024-09-12", 15, "modificar", 24],
            // Test caso 25: fecha contiene caracteres especiales
            ["Ausencia", "Razón del permiso", "2024-09-12!", 15, "is-invalid", 25],
            // Test caso 26: id_tabla contiene caracteres especiales
            ["Ausencia", "Razón del permiso", "2024-09-12", "1!", "is-invalid", 26],

        ];
    }
}