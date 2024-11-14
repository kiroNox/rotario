<?php 
use PHPUnit\Framework\TestCase;
class TrabajadorRegistrarTrabajadorTest extends TestCase
{
    private $usuarios;

    protected function setUp(): void
    {
        $this->usuarios = new Usuarios();
        $_SESSION['usuario_rotario'] = 2;

    }

    /**
     * @dataProvider registrarUsuarioDataProvider
     */
    public function testRegistrarUsuario($cedula, $nombre, $apellido, $telefono, $correo, $id_rol, $pass, $numero_cuenta, $nivel_profesional, $creado, $comision_servicios, $discapacitado, $discapacidad, $genero_trabajador, $expectedResult,$intento)
    {
        $result = $this->usuarios->registrar_usuario_s(
        	$cedula, 
        	$nombre, 
        	$apellido, 
        	$telefono, 
        	$correo, 
        	$id_rol, 
        	$pass, 
        	$numero_cuenta, 
        	$nivel_profesional, 
        	$creado, 
        	$comision_servicios, 
        	$discapacitado, 
        	$discapacidad, 
        	$genero_trabajador);


        	$this->assertNotNull($result);
        	$this->assertIsArray($result);

        	$mensaje = "intento ($intento)";
        	if(isset($result["mensaje"])){
        	    $mensaje = "($intento)".$result["mensaje"]." line:".$result["line"];
        	}



        	$this->assertEquals($expectedResult, $result["resultado"],$mensaje);
    }

    public function registrarUsuarioDataProvider()
    {
        return [
            // Test case 1: Valid data
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'registrar',
                1
            ],
            // Test case 2: Invalid cedula
            [
                '12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passwoRd1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'is-invalid',
                2
            ],
            // Test case 3: Invalid nombre
            [
                'V-12345678',
                'John777',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'is-invalid',
                3
            ],
            // Test case 4: Invalid apellido
            [
                'V-12345678',
                'John',
                'Doe777',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'is-invalid',
                4
            ],
            // Test case 5: Invalid telefono
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'is-invalid',
                5
            ],
            // Test case 6: Invalid email
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'invalid-email',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'is-invalid',
                6
            ],
            // Test case 7: invalid rol no existe
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                100,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'error',
                7
            ],
            // test case 8: invalid password
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'password',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'is-invalid',
                8
            ],
            // test case 9: invalid num cuenta
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '1234567890123456', // menos de 20 caracteres
                1,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'is-invalid',
                9
            ],
            // test case 10: invalid nivel profesional
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                100,
                '2022-01-01',
                true,
                false,
                '',
                'M',
                'error',
                10
            ],
            // test case 11: invalid fecha
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                'hola',
                true,
                false,
                '',
                'M',
                'is-invalid',
                11
            ],
            // test case 12: invalid comision de servicios
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                7,
                false,
                '',
                'M',
                'error',
                12
            ],
            // test case 13: invalid discapacitado
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                8,
                '',
                'M',
                'error',
                13
            ],
            // test case 14: invalid discapacidad
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                [],
                'M',
                'is-invalid',
                14
            ],
            // test case 15: invalid genero
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                "",
                NULL,
                'is-invalid',
                15
            ],
            // test case 16: invalid ya existe cedula
            [
                'V-27250544',
                'John',
                'Doe',
                '0414-5555555',
                'john.doe@example.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                "",
                "M",
                'error',
                16
            ],
            // test case 17: invalid ya existe correo
            [
                'V-12345678',
                'John',
                'Doe',
                '0414-5555555',
                'david40ene@hotmail.com',
                1,
                'passworD1',
                '12345678901234567890',
                1,
                '2022-01-01',
                true,
                false,
                "",
                "M",
                'error',
                17
            ],

        ];
    }
}