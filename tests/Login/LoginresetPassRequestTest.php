<?php 
error_reporting(E_ALL);
session_start();
use PHPUnit\Framework\TestCase;
class LoginresetPassRequestTest extends TestCase
{
	private $login;

	protected function setUp(): void
	{
		$this->login = new Loging;
		$this->login->set_Testing(true);

	}

	/**
	 * @dataProvider loginProvider
	 */
	public function testLoginReseTRequest($correo, $expected_result, $caso){
	
		$resp = $this->login->reset_pass_request_s($correo);

		
		$this->assertNotNull($resp);
		$this->assertIsArray($resp);
		$this->assertArrayHasKey("testLogin", $resp);
		
		$mensaje = "caso ($caso)";
		if (isset($resp["mensaje"]) and $resp["testLogin"] != "success") {
			$mensaje = "($caso)" . $resp["mensaje"];
		}

		$this->assertEquals($expected_result, $resp["testLogin"], $mensaje);
	}

	public function loginProvider()
	{
		return [
			//test caso 1 correo valido y registrado
			["uptaebxavier@gmail.com", "success",1],
			//test caso 2 login invalido correo invalido
			["uptaebxaviergmail.com", "fail",2],
			//test caso 3 login invalido correo incorrecto (no registrado)
			["xavier@gmail.com", "fail",3],
			//test caso 4 login correo vacía
			["", "fail",4],
			//test caso 5 login correo nula
			[null, "fail",5],
		];
	}
}