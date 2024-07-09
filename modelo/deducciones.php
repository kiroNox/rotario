<?php

class Deducciones extends Conexion
{
	PRIVATE $cedula,$correo,$pass,$con;
	function __construct($con = '')
	{
		// al instanciar la clase puede hacerce con una conexion vieja o no 
		// se pasaria como argumento (para controlar transacciones)
		// "con" = conexion
		if(!($con instanceof PDO)){// si "con" no es una instancia de PDO
			$this->con = $this->conecta();// crea la conexion 
		}

	}

	PUBLIC function load_deducciones(){

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			$consulta = $this->con->prepare('SELECT
		    d.id_deducciones
		    ,d.descripcion
		    ,IF(d.porcentaje IS TRUE,CONCAT(d.monto,"%"),CONCAT(d.monto," Bs") ) as monto
		    ,IF(d.quincena IS true,"Quincenal","Mensual") as tiempo
		    ,IF(d.sector_salud IS true,"Si","No") as medic_only
		    ,IF(d.islr IS true,"Si","No") as islr_temp
		    ,IF(d.dedicada IS FALSE,"Todos",IF(dt.total_trabajadores IS NULL,0,dt.total_trabajadores)) as dedic
		    ,NULL as extra
		    
		FROM
		    deducciones as d 
		    LEFT JOIN 
		    (
		        SELECT COUNT(td.id_deducciones)as total_trabajadores, td.id_deducciones 
		        FROM trabajador_deducciones as td 
		        WHERE td.status IS TRUE GROUP BY td.id_deducciones
		    ) AS dt ON dt.id_deducciones = d.id_deducciones
		WHERE
		    1;');

		    $consulta->execute();
			
			$r['resultado'] = 'load_deducciones';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_ASSOC);
			//$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['titulo'] = 'Error';
			$r['mensaje'] =  $e->getMessage();
			//$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			//$this->con = null;
		}
		return $r;

	}

	// setters y getters 

} 