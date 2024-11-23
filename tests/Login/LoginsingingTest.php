<?php 
error_reporting(E_ALL);
use PHPUnit\Framework\TestCase;
class LoginsingingTest extends TestCase
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
    public function testLogin($user,$pass, $expected_result, $caso){
    
        $resp = $this->login->singing_c($user, $pass);

        $mensaje = "caso ($caso)";
        if (isset($resp["mensaje"]) and $resp["resultado"] != "singing") {
            $mensaje = "($caso)" . $resp["mensaje"];
        }

        $this->assertNotNull($resp);
        $this->assertIsArray($resp);

        $this->assertEquals($expected_result, $resp["resultado"], $mensaje);
    }

    public function loginProvider()
    {
        return [
            //test caso 1 login valido
            ["uptaebxavier@gmail.com", "hola123","singing",1],
            //test caso 2 login invalido correo invalido
            ["uptaebxaviergmail.com", "hola123","is-invalid",2],
            //test caso 3 login invalido correo incorrecto (no registrado)
            ["xavier@gmail.com", "hola123","error",3],
            //test caso 4 login invalido contraseña incorrecta
            ["uptaebxavier@gmail.com", "hola123XXXX","error",4],
            //test caso 5 login correo vacía
            ["", "hola123","is-invalid",5],
            //test caso 6 login contraseña vacía
            ["uptaebxavier@gmail.com", "","error",6],
            //test caso 7 login correo nula
            [null, "hola123","is-invalid",7],
            //test caso 8 login contraseña nula
            ["uptaebxavier@gmail.com", null,"error",8],
        ];
    }
}