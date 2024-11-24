<?php

class generar extends Conexion
{
    private $id,$fecha_desde,$fecha_hasta,$tipo,$con;

    function __construct($con = '')
    {
        if(!($con instanceof PDO)){
            $this->con = $this->conecta();
        }
    }

    public function obtenerDatosTrabajador($id_trabajador) {
		$this->validar_conexion($this->con);
        $sql = "SELECT t.nombre, t.apellido, t.cedula, t.creado,c.cargo FROM trabajadores as t LEFT JOIN sueldo_base as sb on sb.id_trabajador = t.id_trabajador LEFT JOIN cargos as c on c.codigo = sb.cargo   WHERE t.id_trabajador = :id_trabajador";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
	public function obtenerDatosJefe() {

        $sql = "SELECT nombre, apellido, cedula, creado FROM trabajadores WHERE id_trabajador = :id_trabajador";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    PUBLIC function listar_usuarios(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT cedula,nombre,apellido,telefono,correo, r.descripcion as rol, numero_cuenta,  NULL as extra, p.id_trabajador FROM trabajadores as p left join rol as r on r.id_rol = p.id_rol WHERE estado_actividad = 1;")->fetchall(PDO::FETCH_NUM);
			

			
			$r['resultado'] = 'listar';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] = $consulta;
			$this->con->commit();
		
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


	public function generar_balance_s($fecha_desde,$fecha_hasta,$tipo=false){

		$this->set_fecha_desde($fecha_desde);
		$this->set_fecha_hasta($fecha_hasta);

		$this->set_tipo($tipo);

		return $this->generar_balance();

	}

	private function generar_balance(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			
			Validaciones::fecha($this->fecha_desde,"fecha 'desde'");
			Validaciones::fecha($this->fecha_hasta,"fecha 'hasta'");

			if($this->tipo===false){
				$consulta = $this->con->prepare("SELECT 1 FROM factura WHERE YEAR(fecha) = YEAR(:fecha) AND MONTH(fecha) = MONTH(:fecha) and status = 1");
				$consulta->execute([":fecha"=>$this->fecha_desde]);

				$r["resultado"] = "generar_balance";
				if($consulta->fetch()){
					$r["found"] = true;
				}
				else{
					$r["found"] = false;
				}

				return $r;
			}
			else{
				if($this->tipo === "primas"){
					$consulta = $this->con->prepare("SELECT
					    pg.descripcion
					    ,SUM(fp.monto) as total
					    ,SUM(IF(f.quincena = 1,fp.monto,0)) as quincena_uno
					    ,SUM(IF(f.quincena = 2,fp.monto,0)) as quincena_dos
					FROM
					    factura_primas_generales AS fp
					JOIN factura AS f
					ON
					    f.id_factura = fp.id_factura
					JOIN primas_generales as pg on pg.id_primas_generales = fp.id_primas_generales
					WHERE
					    YEAR(f.fecha) = YEAR(:fecha) AND MONTH(f.fecha) = MONTH(:fecha) AND f.status = 1 
					GROUP BY pg.id_primas_generales
					UNION
					SELECT 
						'TOTAL'
						,SUM(fp2.monto)
						,SUM(IF(f2.quincena = 1,fp2.monto,0))
						,SUM(IF(f2.quincena = 2,fp2.monto,0)) 
					FROM factura_primas_generales as fp2 
					JOIN 
						factura as f2 
					on f2.id_factura = fp2.id_factura 
					WHERE 
						YEAR(f2.fecha) = YEAR(:fecha) AND MONTH(f2.fecha) = MONTH(:fecha) and f2.status = 1
						HAVING SUM(fp2.monto) IS NOT NULL
					");

					$consulta->execute([":fecha"=>$this->fecha_desde]);



					$timestamp = strtotime($this->fecha_desde);

					// Extraemos el mes (en formato numérico, 1-12)
					$mes_numero = date('m', $timestamp);




					$mes = MESES[(intval($mes_numero) - 1)];
					$lista = $consulta->fetchall(PDO::FETCH_ASSOC);

					if(!$lista){
						throw new Exception("No hay pagos culminados en el mes de la fecha ingresada", 1);
					}

					ob_start();
					
					echo "<pre>\n";
					var_dump($lista);
					echo "</pre>";
					
					$valor = ob_get_clean();
					
					$r["resultado"] = "console";
					$r["mensaje"] = $valor;
					
					// echo $valor; exit;
					echo json_encode($r);
					//return $r;

					ob_start();
					require_once 'assets/templates/balancePrimas.php';
					$html = ob_get_clean();

					$pdf = new Dompdf\Dompdf();
					
					// Definimos el tamaño y orientación del papel que queremos.
					$pdf->set_paper("A4", "portrait");
					
					// Cargamos el contenido HTML.
					$pdf->load_html($html);
					
					// Renderizamos el documento PDF.
					$pdf->render();

					$pdfOutput = $pdf->output();
					$base64Data = base64_encode($pdfOutput);

					$r["resultado"] = "generar_balance";
					$r["tipo"] = "primas";
					$r["fecha"] = date("Y")."-".$mes;
					$r["blob"] = $base64Data;
					return $r; 
					

				}
			}
			
			$r['resultado'] = 'console';
			$r['titulo'] = 'Éxito';
			$r['mensaje'] =  "";
			//$this->con->commit();
			Bitacora::reg($this->con,"Genero un balance de primas de la fecha $this->desde");
			$this->close_bd($this->con);
		
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
			$consulta = null;
		}
		return $r;
		if($this->tipo===false){

		}
	}

    public function get_id(){
        return $this->id;
    }

    public function set_id($value){
        $this->id = $value;
    }

    public function get_con(){
        return $this->con;
    }

    public function set_con($value){
        $this->con = $value;
    }

    PUBLIC function get_tipo(){
    	return $this->tipo;
    }
    PUBLIC function set_tipo($value){
    	$this->tipo = $value;
    }

    PUBLIC function get_fecha_desde(){
    	return $this->fecha_desde;
    }
    PUBLIC function set_fecha_desde($value){
    	$this->fecha_desde = $value;
    }
    PUBLIC function get_fecha_hasta(){
    	return $this->fecha_hasta;
    }
    PUBLIC function set_fecha_hasta($value){
    	$this->fecha_hasta = $value;
    }
}
?>
