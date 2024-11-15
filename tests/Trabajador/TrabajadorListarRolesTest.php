<?php 

use PHPUnit\Framework\TestCase;
class TrabajadorListarRolesTest extends TestCase
{
    private $trabajadores;

    protected function setUp(): void {
    
        $this->trabajadores= new Usuarios;
        $this->trabajadores->set_Testing(true);
    }
   
    public function testGetRoles(){




        $resp = $this->trabajadores->get_roles();



    	

    	$this->assertNotNull($resp);
    	$this->assertIsArray($resp);

    	$this->assertEquals("get_roles", $resp["resultado"]);
        $this->assertIsArray($resp["mensaje"]);
    }
   
} 