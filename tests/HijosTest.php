<?php 
require_once '../vendor/autoload.php';




use PHPUnit\Framework\TestCase;
class HijosTest extends TestCase
{
    private $hijos, $trabajadores;

    protected function setUp(): void {
    
        $this->hijos= new Hijos;
        $this->trabajadores = new Usuarios;
    }

    // public function testIntegracionHijosTrabajadores(){

    // 	//valores
	//     	$cedula = "V-55555555";
	//     	$nombre = "Haxcel Antonio";
	//     	$apellido = "Perez Delgado" ;
	//     	$telefono = "0414-5555555";
	//     	$correo = "algo@pedro.com";
	//     	$id_rol = "1";
	//     	$pass = "PasS1234";
	//     	$numero_cuenta = "01027777777777777777";
	//     	$nivel_profesional = "2";
	//     	$creado = "2022-06-15";
	//     	$comision_servicios = "false";
	// 	    $discapacitado = false;
	// 	    $discapacidad = "";
	// 	    $genero_trabajador = "M";

    // 	$resp_trab = $this->trabajadores->registrar_usuario_s(
	//     	$cedula,
	//     	$nombre,
	//     	$apellido,
	//     	$telefono,
	//     	$correo,
	//     	$id_rol,
	//     	$pass,
	//     	$numero_cuenta,
	//     	$nivel_profesional,
	//     	$creado,
	//     	$comision_servicios,
	//     	$discapacitado,
	//     	$discapacidad,
	//     	$genero_trabajador
    // 	);

    // 	$this->assertNotNull($resp_trab);
	// 	$this->assertIsArray($resp_trab);
    // 	$this->assertEquals("registrar", $resp_trab["resultado"]);

	// 		$cedula_madre = '';
	// 		$cedula_padre = $cedula ;
	// 		$nombre = "Maria Perez";
	// 		$fecha_nacimiento = "2009-04-12";
	// 		$genero = 'F';
	// 		$discapacidad = false;
	// 		$observacion = '';

    // 	$resp_hijo = $this->hijos->registrar_hijo_s(
    // 		$cedula_madre,
    // 		$cedula_padre,
	// 		$nombre,
	// 		$fecha_nacimiento,
	// 		$genero,
	// 		$discapacidad,
	// 		$observacion
	// 	);

	// 	$this->assertNotNull($resp_hijo);
	// 	$this->assertIsArray($resp_hijo);
	// 	$this->assertEquals("registrar_hijo", $resp_hijo["resultado"]);
    // }











    /**
     * @dataProvider miFuncionProveedora
     */
    public function testvalid_parent_s_dataProvi($cedula,$expected_result){




        $resp = $this->hijos->valid_parent_s($cedula);


    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals($expected_result, $resp["resultado"]);
    }

    public function miFuncionProveedora(){
    	return [
    		["V-27250544","valid_parent"],
    		["27250544","is-invalid"],
    		["V-2725","is-invalid"],
    		["holaMundo","is-invalid"],
    		["","is-invalid"]
    	];
    }

    // public function testvalid_parent_s(){
    //     $funString = str_replace([get_class($this),'::',"test",'_s'], [""], __METHOD__);
    //     // output : valid_parent

    //     $resp = $this->hijos->valid_parent_s("V-27250544");

    //     $this->assertNotNull($resp);
    //     $this->assertIsArray($resp);

    //     $this->assertEquals($funString, $resp["resultado"]);
    // }
    
}



 