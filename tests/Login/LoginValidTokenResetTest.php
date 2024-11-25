<?php 
/*
error_reporting(E_ALL);
use PHPUnit\Framework\TestCase;
class LoginValidTokenResetTest extends TestCase
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
    /*
    public function testValidTokenReset($user,$pass, $expected_result, $caso){
    
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
            
        ];
    }
}