-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-07-2024 a las 13:44:52
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rotario_produccion`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_deducciones` (IN `idTrabajador` INT, IN `fecha_factura` DATE)   BEGIN
    #idTrabajador
    #fecha_factura

    --  orden aplicado:
    --  (sueldo integral * meses / semanas ) * [porcentaje,monto]  * numero de lunes en la quincena o mes [multi_dia]
    DECLARE filas_encontradas int DEFAULT 0;
    DECLARE sueldoIntegral decimal(12,2) DEFAULT 0;
    DECLARE deduc_descrip varchar (100);
    DECLARE deduc_monto decimal(12,2);
    DECLARE deduc_porcentaje boolean;
    DECLARE deduc_multi_meses int DEFAULT 0;
    DECLARE deduc_div_sem int DEFAULT 0;
    DECLARE deduc_quincena boolean;
    DECLARE deduc_multi_dia boolean;
    DECLARE deduc_sector_salud boolean;
    DECLARE deduc_islr boolean;
    DECLARE deduc_dedicada boolean;
    DECLARE deduccion_total decimal(12,2);
    DECLARE deduccion_registrar decimal(12,2) DEFAULT 0;
    DECLARE done boolean DEFAULT FALSE;
    DECLARE contador_quincena int DEFAULT 1;
    DECLARE trabajador_salud boolean;
    DECLARE apli_while boolean DEFAULT TRUE;
    DECLARE id_factura_p int;


    DECLARE lista_deducciones CURSOR FOR

    SELECT
        descripcion
        ,monto
        ,porcentaje
        ,multi_meses
        ,div_sem
        ,quincena
        ,multi_dia
        ,sector_salud
        ,islr
        ,dedicada
    FROM
        deducciones AS d
    LEFT JOIN trabajador_deducciones AS td
    ON
        td.id_deducciones = d.id_deducciones
    WHERE
        d.dedicada IS FALSE OR(
            d.dedicada IS TRUE AND td.id_trabajador_deducciones = idTrabajador
        );

    OPEN lista_deducciones; # cursor con las deducciones

    SET filas_encontradas = FOUND_ROWS();

    IF filas_encontradas > 0 THEN

        FETCH lista_deducciones INTO deduc_descrip ,deduc_monto ,deduc_porcentaje ,deduc_multi_meses ,deduc_div_sem ,deduc_quincena ,deduc_multi_dia , deduc_sector_salud , deduc_islr , deduc_dedicada;
        # optengo si el trabajador el sueldo integral del trabajador y si pertenece al sector_salud (médicos)
        SELECT (f.sueldo_integral + f.sueldo_base), t.sector_salud INTO sueldoIntegral, trabajador_salud  FROM factura as f LEFT JOIN sueldo_base as t on t.id_trabajador = f.id_trabajador WHERE f.id_trabajador = idTrabajador AND f.status IS FALSE LIMIT 1;

        -- SELECT sueldoIntegral, "sueldo integral" as otro;

        WHILE done IS NOT TRUE DO
            SET contador_quincena = 1;
            SET apli_while = TRUE;
            SET deduccion_registrar = 0;


            IF deduc_sector_salud IS TRUE AND trabajador_salud IS FALSE THEN 
            # si la deduccion es para los medicos y no es un medico se salta la misma
                SET apli_while = FALSE;
            END IF;



            IF apli_while IS TRUE THEN



                WHILE (deduc_quincena IS TRUE AND contador_quincena <= 2 ) OR (deduc_quincena IS FALSE AND contador_quincena = 1) DO

                    IF deduc_porcentaje IS TRUE THEN


                        SET deduccion_total = sueldoIntegral;
                        IF deduc_multi_meses > 0 THEN 
                            # si debe multiplicar los meses por el sueldo integral
                            SET deduccion_total = deduccion_total * deduc_multi_meses;

                          --  SELECT deduccion_total,"multiplicando meses" as otro;
                        END IF;

                        IF deduc_div_sem > 0 THEN 
                            # si debe dividir el sueldo integral 
                            SET deduccion_total = ROUND( deduccion_total / deduc_div_sem ,2);
                           -- SELECT deduccion_total,"Dividiendo semanas" as otro;
                        END IF;

                        IF deduc_porcentaje IS TRUE THEN
                            # si el monto es un porcentaje o un monto neto
                            SET deduccion_total = ROUND( (deduccion_total / 100) * deduc_monto ,2);
                            -- SELECT deduccion_total,"aplicando porcentaje" as otro;
                        ELSE
                            SET deduccion_total = deduccion_total + deduc_monto;
                        END IF;

                        IF deduc_quincena IS TRUE AND deduc_multi_dia IS TRUE THEN
                            # si la deduccion se hace por qulincena y multiplicando el numero de lunes entra en el loop y primero lo calcula por 
                            # la primera semana y despues por la segunda en el siguiente loop
                            SET deduccion_total = deduccion_total * f_contar_lunes(fecha_factura,contador_quincena);
                           -- SELECT deduccion_total,CONCAT("multiplicando por lunes",f_contar_lunes(fecha_factura,contador_quincena)) as otro;
                        ELSEIF deduc_multi_dia IS TRUE THEN
                            # si es solo multiplicando el numero de lunes pero en este caso del mes entero
                            SET deduccion_total = deduccion_total * f_contar_lunes(fecha_factura,3);
                         --   SELECT deduccion_total,CONCAT("multiplicando por lunes del mes",f_contar_lunes(fecha_factura,contador_quincena)) as otro;
                        END IF;

                    ELSE

                        SET deduccion_total = deduc_monto;

                    END IF;

                    SET contador_quincena = contador_quincena + 1;

                    set deduccion_registrar = ROUND(deduccion_registrar + deduccion_total , 2);
                   -- SELECT deduccion_registrar, "lo que guardo en cada ciclo" as otro;
                END WHILE;


                IF deduccion_registrar >= 0 THEN

                    SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;




                    INSERT INTO detalles_factura 
                            (id_factura, descripcion, monto, prima, islr) VALUES
                            (
                                id_factura_p,
                                deduc_descrip,
                                deduccion_registrar,
                                FALSE,
                                deduc_islr
                            );

                END IF;


            END IF;




            IF filas_encontradas <= 0 or filas_encontradas = 1 THEN
                SET done = TRUE;
            ELSE
                SET filas_encontradas = filas_encontradas - 1;
                FETCH lista_deducciones INTO deduc_descrip ,deduc_monto ,deduc_porcentaje ,deduc_multi_meses ,deduc_div_sem ,deduc_quincena ,deduc_multi_dia , deduc_sector_salud , deduc_islr , deduc_dedicada;
            END IF;


        END WHILE;

    END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_escalafon` (IN `idTrabajador` INT, IN `control_insert` BOOLEAN, OUT `monto_devuelto` DECIMAL(12,2))   BEGIN
DECLARE sueldo decimal(13,2);
DECLARE escala varchar(45);
DECLARE esc_porcentaje decimal(5,2);
DECLARE id_factura_p int;
SET monto_devuelto = 0;
    SELECT
        sb.sueldo_base
        ,e.escala
        ,e.monto as porcentaje_escalafon
        
        INTO
        sueldo
        ,escala
        ,esc_porcentaje
        
    FROM
        trabajadores AS t
    JOIN sueldo_base AS sb
    ON sb.id_trabajador = t.id_trabajador
    JOIN escalafon as e on e.id_escalafon = sb.id_escalafon
    WHERE t.id_trabajador = idTrabajador AND sb.sector_salud = TRUE LIMIT 1;

    IF esc_porcentaje IS NOT NULL THEN
    
        SET monto_devuelto = ROUND( ((sueldo/100) * esc_porcentaje) , 2 );
        
        IF control_insert IS TRUE THEN
        
            SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;
            
            INSERT INTO detalles_factura 
                (id_factura, descripcion, monto, prima, islr) VALUES
                (
                    id_factura_p,
                    CONCAT("Escalafon - escala ", escala ),
                    monto_devuelto,
                    TRUE,
                    FALSE
                );
            
        END IF;

    END IF;
    
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_primas` (IN `fecha_factura` DATE)   BEGIN
    DECLARE num_hijos INT;
    DECLARE id INT;
    DECLARE sueldo_trabajador DECIMAL(12,2);
    DECLARE filas_encontradas int DEFAULT 0;
    DECLARE done BOOLEAN DEFAULT FALSE;
    DECLARE id_factura_p int;
    
    
    # primas por hjo ---------------------------------
    DECLARE lista_trabajadores CURSOR FOR
        SELECT
            t.id_trabajador,
            sb.sueldo_base
        FROM
            trabajadores AS t
        JOIN sueldo_base as sb on sb.id_trabajador = t.id_trabajador
        WHERE
            t.estado_actividad = TRUE GROUP BY t.id_trabajador;
            
            
    
    OPEN lista_trabajadores; # lista detrabajadores activos con hijos con hijos

    set filas_encontradas = FOUND_ROWS();

    IF filas_encontradas > 0 THEN
    	SET fecha_factura = LAST_DAY(fecha_factura);
        FETCH lista_trabajadores INTO id,sueldo_trabajador;


WHILE done IS NOT TRUE DO
    
    
    DELETE FROM factura WHERE status is false AND id_trabajador = id;
    
    
    
    INSERT INTO factura 
    (id_trabajador,fecha , sueldo_base, sueldo_integral, sueldo_deducido, status)
    VALUES
    (
        id,
        fecha_factura,
        sueldo_trabajador,
        DEFAULT,
        DEFAULT,
        DEFAULT);
        
    

    CALL calcular_prima_hijo(id,sueldo_trabajador);    
    CALL calcular_escalafon(id,TRUE,@aqui_no_la_voy_a_usar_XD);
    CALL calcular_primas_generales(id,sueldo_trabajador);
    CALL calcular_profesionalismo(id);
    
    
    IF f_antiguedad(id) > 0 THEN
    
    	SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = id LIMIT 1;
    
        INSERT INTO detalles_factura 
        (id_factura, descripcion, monto, prima, islr) VALUES
        (
            id_factura_p,
            "Antiguedad",
            f_antiguedad(id),
            TRUE,
            FALSE
        );
    END IF;
    
    
    
    CALL calcular_deducciones(id,fecha_factura);
    
    IF filas_encontradas <= 0 or filas_encontradas = 1 THEN
        SET done = TRUE;
    ELSE
        set filas_encontradas = filas_encontradas - 1;
        FETCH lista_trabajadores INTO id,sueldo_trabajador;
    END IF;
    
END WHILE;
    
END IF;


    
    
    CLOSE lista_trabajadores;
    
    
    
-- START TRANSACTION;
-- call calcular_primas("2024-07-01");

-- SELECT * FROM factura as f WHERE f.status IS false ORDER BY f.id_factura;

-- SELECT t.id_trabajador,t.nombre,df.descripcion,df.monto,df.prima,df.islr FROM detalles_factura as df LEFT JOIN factura as f on f.id_factura = df.id_factura LEFT JOIN trabajadores as t on t.id_trabajador = f.id_trabajador WHERE f.status IS false ORDER BY f.id_factura, t.id_trabajador, df.prima;
-- ROLLBACK;
    
    
    
    
    
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_primas_generales` (IN `idTrabajador` INT, IN `sueldo_base` DECIMAL(12,2))   BEGIN 
    #sueldo_base
    #idTrabajador

    DECLARE id int;
    DECLARE done boolean DEFAULT false;
    DECLARE filas_encontradas_1 int DEFAULT 0;
    DECLARE filas_encontradas_2 int DEFAULT 0;
    DECLARE sueldo_total decimal(12,2);
    DECLARE prima_descrip varchar(100);
    DECLARE prima_monto decimal(12,2);
    DECLARE prima_porcen boolean;
    DECLARE prima_salud boolean;
    DECLARE trabajador_salud boolean;
    DECLARE id_factura_p int;


    DECLARE primas_generales CURSOR FOR
        
        SELECT 
            p.descripcion
            ,p.monto
            ,p.porcentaje
            ,p.sector_salud 
        from primas_generales as p WHERE p.dedicada IS false;
    DECLARE primas_generales_dedicadas CURSOR FOR

        SELECT
            p.descripcion
            ,p.monto
            ,p.porcentaje
        FROM
            trabajador_prima_general AS tp
        JOIN primas_generales AS p
        ON
            p.id_primas_generales = tp.id_primas_generales
        WHERE tp.status = 1 AND tp.id_trabajador = idTrabajador;

    OPEN primas_generales;

    SET filas_encontradas_1 = FOUND_ROWS();

    OPEN primas_generales_dedicadas;

    SET filas_encontradas_2 = FOUND_ROWS();

    IF filas_encontradas_1 > 0 THEN
        SELECT sb.sector_salud INTO trabajador_salud FROM sueldo_base as sb WHERE sb.id_trabajador = idTrabajador;

        FETCH primas_generales INTO prima_descrip, prima_monto, prima_porcen, prima_salud;

        WHILE done IS NOT TRUE DO # las primas no dedicadas



            IF prima_porcen IS TRUE THEN
                set sueldo_total = (sueldo_base / 100  ) * prima_monto;
            ELSE
                set sueldo_total = prima_monto;
            END IF;
            
            

            IF prima_salud IS false OR (prima_salud IS TRUE AND trabajador_salud IS TRUE) THEN 
            
            
            SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;
            
                INSERT INTO detalles_factura 
                    (id_factura, descripcion, monto, prima, islr) VALUES
                    (
                        id_factura_p,
                        prima_descrip,
                        sueldo_total,
                        TRUE,
                        FALSE
                    );
            END IF;


            
            #SELECT * FROM factura as f JOIN detalles_factura as df on df.id_factura = f.id_factura WHERE f.status is FALSE ORDER BY f.id_factura,df.descripcion;
            
            IF filas_encontradas_1 <= 0 or filas_encontradas_1 = 1 THEN
                SET done = TRUE;
            ELSE
                set filas_encontradas_1 = filas_encontradas_1 - 1;
                FETCH primas_generales INTO prima_descrip, prima_monto, prima_porcen, prima_salud;
            END IF;
            
        END WHILE;

    END IF;

    set done = FALSE;





    IF filas_encontradas_2 > 0 THEN

        FETCH primas_generales_dedicadas INTO prima_descrip, prima_monto, prima_porcen;

        WHILE done IS NOT TRUE DO # las primas no dedicadas


            IF prima_porcen IS TRUE THEN
                set sueldo_total = (sueldo_base / 100  ) * prima_monto;
            ELSE
                set sueldo_total = prima_monto;
            END IF;
            
            
            SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;
            
            INSERT INTO detalles_factura 
                (id_factura, descripcion, monto, prima, islr) VALUES
                (
                    id_factura_p,
                    prima_descrip,
                    sueldo_total,
                    TRUE,
                    FALSE
                );


            
            #SELECT * FROM factura as f JOIN detalles_factura as df on df.id_factura = f.id_factura WHERE f.status is FALSE ORDER BY f.id_factura,df.descripcion;
            
            IF filas_encontradas_2 <= 0 or filas_encontradas_2 = 1 THEN
                SET done = TRUE;
            ELSE
                set filas_encontradas_2 = filas_encontradas_2 - 1;
                FETCH primas_generales_dedicadas INTO prima_descrip, prima_monto, prima_porcen;
            END IF;
            
        END WHILE;

    END IF;




END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_prima_hijo` (IN `id_padre` INT, IN `sueldo` DECIMAL(12,2))   proc_Exit:BEGIN
    DECLARE hijo INT;
    DECLARE descrip varchar(100);
    DECLARE porcentaje BOOLEAN;
    DECLARE monto decimal(12,2);
    DECLARE control_menor BOOLEAN;
    DECLARE control_discapacidad BOOLEAN;
    DECLARE done BOOLEAN DEFAULT FALSE;
    DECLARE hijo_discapacidad BOOLEAN;
    DECLARE hijo_menor BOOLEAN;
    DECLARE control_func_var BOOLEAN DEFAULT FALSE;
    DECLARE sueldo_total decimal(12,2) DEFAULT 0;
    DECLARE id_factura_p int;


    DECLARE lista_hijos_and_primas CURSOR FOR
        SELECT 
        
        h.discapacidad,
        (IF(TIMESTAMPDIFF(YEAR, h.fecha_nacimiento ,CURRENT_DATE) < 18,TRUE,FALSE)) as menor_edad,
        ph.descripcion,
        ph.menor_edad,
        ph.discapacidad,
        ph.porcentaje,
        ph.monto,
        h.id_hijo

        FROM hijos as h 
        CROSS JOIN primas_hijos as ph 
        WHERE h.id_trabajador_madre = id_padre OR h.id_trabajador_padre = id_padre
        ORDER BY h.nombre, ph.descripcion;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    IF FOUND_ROWS() <= 0 THEN
    LEAVE proc_Exit;
    END IF;

    OPEN lista_hijos_and_primas;

    read_loop: LOOP
        SET control_func_var = FALSE;
        FETCH lista_hijos_and_primas INTO
        hijo_discapacidad,
        hijo_menor,
        descrip,
        control_menor,
        control_discapacidad,
        porcentaje,
        monto,
        hijo;

        IF done THEN
            LEAVE read_loop;
        END IF;

        IF control_discapacidad = hijo_discapacidad and control_menor = hijo_menor THEN
            SET control_func_var = TRUE;
        ELSEIF control_menor IS TRUE and hijo_menor IS TRUE and control_discapacidad IS FALSE THEN
            SET control_func_var = TRUE;
        ELSEIF control_discapacidad IS TRUE and hijo_discapacidad IS TRUE and control_menor IS FALSE THEN
            SET control_func_var = TRUE;
        ELSEIF control_discapacidad IS FALSE and control_menor IS FALSE THEN
            SET control_func_var = TRUE;
        END IF;

        IF control_func_var IS TRUE THEN

            IF porcentaje IS TRUE THEN 
                SET sueldo_total = ((sueldo/100) * monto);
            ELSE
                SET sueldo_total = monto;
            END IF;
            
            SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = id_padre LIMIT 1;
            

            INSERT INTO detalles_factura 
            (id_factura, descripcion, monto, prima, islr) VALUES
            (
                (id_factura_p),
                descrip,
                sueldo_total,
                TRUE,
                FALSE
            );
            
      

#           SELECT "hola";

        END IF;

    END LOOP;

    CLOSE lista_hijos_and_primas;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_profesionalismo` (IN `idTrabajador` INT)   BEGIN
	# idTrabajador

	DECLARE prima_descrip VARCHAR(150);
	DECLARE prima_porcent DECIMAL(12,2);
	DECLARE id_factura_p INT;

	SELECT
	    CONCAT('Profesionalización - ',PP.descripcion) AS descriptcion
	    ,pp.incremento
	    INTO
	    prima_descrip
	    ,prima_porcent
	FROM
	    trabajadores AS t
	LEFT JOIN prima_profesionalismo AS pp
	ON pp.id_prima_profesionalismo = t.id_prima_profesionalismo
	WHERE
	    t.id_trabajador = idTrabajador;


	IF prima_porcent > 0 THEN

		SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;
    
        INSERT INTO detalles_factura 
        (id_factura, descripcion, monto, prima, islr) VALUES
        (
            id_factura_p,
            prima_descrip,
            f_profesionalismo(idTrabajador),
            TRUE,
            FALSE
        );




	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_and_notify_salaries` ()   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE trabajador_id INT;
    DECLARE trabajador_cedula VARCHAR(12);

    DECLARE cur CURSOR FOR 
        SELECT t.id_trabajador, t.cedula
        FROM trabajadores t
        LEFT JOIN sueldo_base s ON t.id_trabajador = s.id_trabajador
        WHERE s.id_trabajador IS NULL;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO trabajador_id, trabajador_cedula;
        IF done THEN
            LEAVE read_loop;
        END IF;

        INSERT INTO notificaciones (id_usuario, status, mensaje, fecha)
        VALUES (trabajador_id, 0, CONCAT('El trabajador con cédula ', trabajador_cedula, ' no tiene sueldo asignado.'), NOW());
        
    END LOOP;

    CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_and_notify_vacations` ()   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE vacacion_id INT;
    DECLARE trabajador_id INT;
    DECLARE vacacion_descripcion VARCHAR(45);
    DECLARE vacacion_hasta DATE;
    DECLARE trabajador_cedula VARCHAR(12);
    DECLARE diff INT;

    DECLARE cur CURSOR FOR 
        SELECT v.id_vacaciones, v.id_trabajador, v.descripcion, v.hasta, t.cedula
        FROM vacaciones v
        INNER JOIN trabajadores t ON v.id_trabajador = t.id_trabajador
        WHERE v.hasta IS NOT NULL AND v.hasta > CURDATE();

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO vacacion_id, trabajador_id, vacacion_descripcion, vacacion_hasta, trabajador_cedula;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SET diff = DATEDIFF(vacacion_hasta, CURDATE());

        CASE diff
            WHEN 5 THEN
                INSERT INTO notificaciones (id_usuario, status, mensaje, fecha)
                VALUES (trabajador_id, 0, CONCAT('La vacación del trabajador con cédula ', trabajador_cedula, ' termina en 5 días.'), NOW());
            WHEN 1 THEN
                INSERT INTO notificaciones (id_usuario, status, mensaje, fecha)
                VALUES (trabajador_id, 0, CONCAT('La vacación  del trabajador con cédula ', trabajador_cedula, ' termina mañana.'), NOW());
            WHEN 0 THEN
                INSERT INTO notificaciones (id_usuario, status, mensaje, fecha)
                VALUES (trabajador_id, 0, CONCAT('La vacación  del trabajador con cédula ', trabajador_cedula, ' termina hoy.'), NOW());
            ELSE
                -- No se especifica ELSE ya que no queremos realizar ninguna acción adicional.
                SET diff = diff;  -- No-op
        END CASE;

    END LOOP;

    CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `verificar_vacaciones_proximas` ()   BEGIN
    DECLARE fecha_actual DATE;
    DECLARE id_trabajador INT;
    DECLARE id_vacacion INT;
    DECLARE fecha_hasta DATE;
    DECLARE mensaje VARCHAR(255);
    DECLARE done INT DEFAULT FALSE;



    -- Obtener las vacaciones que cumplen la condición
    DECLARE vacaciones_cursor CURSOR FOR
        SELECT id, id_trabajador, hasta
        FROM vacaciones
        WHERE hasta > fecha_actual;

    -- Handler para finalizar el cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Abrir el cursor
    OPEN vacaciones_cursor;

    -- Iniciar el bucle para recorrer las vacaciones
    vacaciones_loop:LOOP
        FETCH vacaciones_cursor INTO id_vacacion, id_trabajador, fecha_hasta;

        IF done THEN
            LEAVE vacaciones_loop;
        END IF;

        -- Calcular la diferencia de días
        IF DATEDIFF(fecha_hasta, fecha_actual) = 5 THEN
            SET mensaje = CONCAT('El empleado con ID ', id_trabajador, ' regresará de vacaciones en 5 días.');

            -- Insertar notificación
            INSERT INTO notificaciones (id_trabajador, mensaje, fecha_notificacion)
            VALUES (id_trabajador, mensaje, fecha_actual);
        END IF;
    END LOOP;

    -- Cerrar el cursor
    CLOSE vacaciones_cursor;
END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `f_antiguedad` (`idTrabajador` INT) RETURNS DECIMAL(12,2)  BEGIN
    DECLARE trabajador_antiguedad int DEFAULT 0;
    DECLARE sueldo_base decimal(12,2);
    DECLARE resultado decimal(12,2) DEFAULT 0;
    DECLARE prima_monto decimal(5,2) DEFAULT 0;


    SELECT TIMESTAMPDIFF(YEAR, t.creado ,CURRENT_DATE), sb.sueldo_base INTO trabajador_antiguedad, sueldo_base  FROM trabajadores as t JOIN sueldo_base as sb on sb.id_trabajador = t.id_trabajador WHERE t.id_trabajador = idTrabajador;

    IF sueldo_base IS NOT NULL THEN
        SELECT
            a.monto
            INTO
            prima_monto
        FROM
            prima_antiguedad AS a
        WHERE
            a.anios_antiguedad <= trabajador_antiguedad
        ORDER BY
            a.anios_antiguedad
        DESC
        LIMIT 1;
    END IF;
    
    SELECT (
        ((
            sueldo_base +
            f_escalafon(idTrabajador) + 
            f_compensacion_eval(idTrabajador) +
            f_dedicacionSectorSalud(idTrabajador)
         ) /100 ) * prima_monto
    )
    INTO
    
   	resultado;

    

    RETURN resultado;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `f_compensacion_eval` (`idTrabajador` INT) RETURNS DECIMAL(12,2)  BEGIN
    DECLARE resultado decimal(12,2) DEFAULT 0;
    DECLARE prima_monto decimal(12,2) DEFAULT 0;
    DECLARE prima_porcen decimal(5,2);
    DECLARE prima_salud boolean;
    DECLARE prima_dedicada boolean;
    DECLARE prima_id int;
    DECLARE sueldo_base decimal(12,2);
    DECLARE sector_salud_trabajador boolean;
	DECLARE temp_cond int DEFAULT 0;
    SELECT 
    p.id_primas_generales
    ,p.monto
    ,p.porcentaje
    ,p.sector_salud
    ,p.dedicada
    INTO
    prima_id
    ,prima_monto
    ,prima_porcen
    ,prima_salud
    ,prima_dedicada
    FROM primas_generales as p 
    WHERE descripcion = 'Compensación Por Evaluación' 
    LIMIT 1;

    SELECT sb.sueldo_base, sb.sector_salud INTO sueldo_base, sector_salud_trabajador FROM sueldo_base as sb WHERE sb.id_trabajador = idTrabajador;

    IF prima_id IS NOT NULL AND sueldo_base IS NOT NULL THEN
        IF prima_dedicada = 1 THEN
            #idTrabajador

            
            
            

            SELECT 1 INTO temp_cond FROM trabajador_prima_general WHERE id_primas_generales = prima_id AND id_trabajador = idTrabajador;
            IF temp_cond = 1 THEN
                IF prima_porcen IS TRUE THEN
                    IF (prima_salud IS TRUE AND sector_salud_trabajador IS TRUE) OR prima_salud IS FALSE THEN
                        SET resultado = ROUND( (sueldo_base / 100) * prima_monto ,2);
                    END IF;
                ELSE
                    IF (prima_salud IS TRUE AND sector_salud_trabajador IS TRUE) OR prima_salud IS FALSE THEN
                        SET resultado = prima_monto;
                    END IF;

                END IF;
            END IF;
        ELSE
            IF prima_porcen IS TRUE THEN
                IF (prima_salud IS TRUE AND sector_salud_trabajador IS TRUE) OR prima_salud IS FALSE THEN
                    SET resultado = ROUND( (sueldo_base / 100) * prima_monto ,2);
                END IF;
            ELSE
                IF (prima_salud IS TRUE AND sector_salud_trabajador IS TRUE) OR prima_salud IS FALSE THEN
                    SET resultado = prima_monto;
                END IF;

            END IF;
        END IF;
    END IF;



    RETURN resultado;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `f_contar_lunes` (`fecha_lunes` DATE, `quincena` INT) RETURNS INT(11) NO SQL BEGIN

    #fecha_lunes date
    #quincena int => 1 = 30 dias, 2 = primeros 15 dias, 3 siguientes 15 dias

    DECLARE inicio DATE;
    DECLARE fin DATE;
    DECLARE contador int DEFAULT 0;

    SET inicio = DATE_FORMAT(fecha_lunes, '%Y-%m-01');

    IF quincena = 3 THEN # cuenta los lunes del 01 al ultimo del mes
        SET fin = LAST_DAY(inicio);
    ELSEIF quincena = 1 THEN # al 15 del mes
        SET fin = DATE_FORMAT(inicio, '%Y-%m-15');
    ELSEIF quincena = 2 THEN # desde el 16 hasta el ultimo del mes
        SET fin = LAST_DAY(inicio);
        SET inicio = DATE_FORMAT(inicio, '%Y-%m-16');
    ELSEIF quincena = 0 THEN
    RETURN 1;
    ELSE
        SIGNAL SQLSTATE '1USER' SET MESSAGE_TEXT = 'El segundo argumento para la función f_contar_lunes no es valido (1, 2, 3)';
    END IF;


    WHILE inicio <= fin DO
        IF DAYOFWEEK(inicio) = 2 THEN
            SET contador = contador + 1;
        END IF;

        SET inicio = inicio + INTERVAL 1 DAY;
    END WHILE;

    RETURN contador;

END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `f_dedicacionSectorSalud` (`idTrabajador` INT) RETURNS DECIMAL(12,2)  BEGIN
    DECLARE prima_id int;
    DECLARE prima_monto decimal(12,2);
    DECLARE prima_porcen decimal(12,2);
    DECLARE prima_salud boolean;
    DECLARE prima_dedicada boolean;
    DECLARE sueldo_base decimal(12,2);
    DECLARE sector_salud_trabajador boolean;
    DECLARE temp_cond int DEFAULT 0;
    DECLARE resultado decimal(12,2) DEFAULT 0;

    SELECT
        `id_primas_generales`,
        `monto`,
        `porcentaje`,
        `sector_salud`,
        `dedicada`
        INTO
        prima_id
        ,prima_monto
        ,prima_porcen
        ,prima_salud
        ,prima_dedicada
    FROM
        `primas_generales`
    WHERE
        descripcion = 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud';

        SELECT sb.sueldo_base, sb.sector_salud INTO sueldo_base, sector_salud_trabajador FROM sueldo_base as sb WHERE sb.id_trabajador = idTrabajador;

    IF prima_id IS NOT NULL AND sueldo_base IS NOT NULL THEN
        IF prima_dedicada = 1 THEN
            #idTrabajador

            SET temp_cond = null;

            SELECT 1 INTO temp_cond FROM trabajador_prima_general WHERE id_primas_generales = prima_id AND id_trabajador = idTrabajador;
            IF temp_cond = 1 THEN
                IF prima_porcen IS TRUE THEN
                    IF (prima_salud IS TRUE AND sector_salud_trabajador IS TRUE) OR prima_salud IS FALSE THEN
                        SET resultado = ROUND( (sueldo_base / 100) * prima_monto ,2);
                    END IF;
                ELSE
                    IF (prima_salud IS TRUE AND sector_salud_trabajador IS TRUE) OR prima_salud IS FALSE THEN
                        SET resultado = prima_monto;
                    END IF;

                END IF;
            END IF;
        ELSE
            IF prima_porcen IS TRUE THEN
                IF (prima_salud IS TRUE AND sector_salud_trabajador IS TRUE) OR prima_salud IS FALSE THEN
                    SET resultado = ROUND( (sueldo_base / 100) * prima_monto ,2);
                END IF;
            ELSE
                IF (prima_salud IS TRUE AND sector_salud_trabajador IS TRUE) OR prima_salud IS FALSE THEN
                    SET resultado = prima_monto;
                END IF;

            END IF;
        END IF;
    END IF;

    RETURN resultado;

END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `f_escalafon` (`idTrabajador` INT) RETURNS DECIMAL(12,2)  BEGIN
    DECLARE sueldo decimal(13,2);
    DECLARE esc_porcentaje decimal(5,2);
    # idTrabajador
    SELECT
        sb.sueldo_base
        ,e.monto as porcentaje_escalafon
        
        INTO
        sueldo
        ,esc_porcentaje
        
    FROM
        trabajadores AS t
    JOIN sueldo_base AS sb
    ON sb.id_trabajador = t.id_trabajador
    JOIN escalafon as e on e.id_escalafon = sb.id_escalafon
    WHERE t.id_trabajador = idTrabajador AND sb.sector_salud = TRUE LIMIT 1;
    
    IF sueldo IS NOT NULL THEN
    	RETURN ROUND( ((sueldo/100) * esc_porcentaje) , 2 );
    ELSE 
        RETURN 0.00;
    END IF;

END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `f_profesionalismo` (`idTrabajador` INT) RETURNS DECIMAL(12,2)  BEGIN

    DECLARE prima_monto decimal(12,2);
    DECLARE sueldo_base decimal(12,2);
    DECLARE resultado decimal(12,2) DEFAULT 0;


    
    
    SELECT sb.sueldo_base,pp.incremento INTO sueldo_base, prima_monto FROM trabajadores as t JOIN sueldo_base as sb on t.id_trabajador = sb.id_trabajador LEFT JOIN prima_profesionalismo as pp on pp.id_prima_profesionalismo = t.id_prima_profesionalismo WHERE sb.id_trabajador = idTrabajador LIMIT 1;

    IF sueldo_base IS NOT NULL THEN
    
        SELECT (
            ((
                sueldo_base +
                f_escalafon(idTrabajador) + 
                f_compensacion_eval(idTrabajador) +
                f_dedicacionSectorSalud(idTrabajador) + 
                f_antiguedad(idTrabajador)
             ) /100 ) * prima_monto
        )
        INTO
        
        resultado;

    END IF;





    RETURN resultado;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `codigo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id_area`, `descripcion`, `codigo`) VALUES
(1, '00002', 'Departamento Administracion'),
(2, '0003', 'Comedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL,
  `id_trabajador_area` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_trabajador` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_trabajador`, `fecha`, `descripcion`) VALUES
(2, '2024-06-26 13:08:16', 'Inicio de sesión'),
(2, '2024-06-26 13:08:16', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:11:54', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:12:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:13:08', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:13:10', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:14:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:16:40', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:16:54', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:17:40', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:19:01', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:19:07', 'Ingreso en el modulo (Areas)'),
(2, '2024-06-26 13:19:15', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:21:09', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:21:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:22:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:22:37', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:26:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:27:13', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:38:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:39:03', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:39:35', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-06-26 13:41:27', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-06-26 13:42:17', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-06-26 13:44:15', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-06-26 14:10:31', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:11:36', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:11:57', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:12:31', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:14:43', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:14:55', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:16:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:16:43', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:17:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:17:37', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:17:50', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:18:18', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 15:19:47', 'Ingreso en el modulo (Permisos)'),
(2, '2024-06-26 15:20:54', 'Ingreso en el modulo (Permisos)'),
(2, '2024-06-26 15:20:57', 'cambio los permiso de un rol'),
(2, '2024-06-26 15:20:58', 'cambio los permiso de un rol'),
(2, '2024-06-26 15:20:59', 'cambio los permiso de un rol'),
(2, '2024-06-26 15:21:00', 'cambio los permiso de un rol'),
(2, '2024-06-26 16:31:35', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 17:36:10', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 17:36:50', 'Registro al usuarios (V-2725054)'),
(2, '2024-06-26 23:52:28', 'Inicio de sesión'),
(2, '2024-06-26 23:52:28', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:52:59', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:53:09', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:54:01', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:54:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:58:01', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:58:44', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:08:16', 'Inicio de sesión'),
(2, '2024-06-26 13:08:16', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:11:54', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:12:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:13:08', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:13:10', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:14:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:16:40', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:16:54', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:17:40', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:19:01', 'Ingreso en el modulo (2)'),
(2, '2024-06-26 13:19:07', 'Ingreso en el modulo (Areas)'),
(2, '2024-06-26 13:19:15', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:21:09', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:21:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:22:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:22:37', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:26:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:27:13', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:38:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:39:03', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 13:39:35', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-06-26 13:41:27', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-06-26 13:42:17', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-06-26 13:44:15', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-06-26 14:10:31', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:11:36', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:11:57', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:12:31', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:14:43', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:14:55', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:16:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:16:43', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:17:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:17:37', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:17:50', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 14:18:18', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 15:19:47', 'Ingreso en el modulo (Permisos)'),
(2, '2024-06-26 15:20:54', 'Ingreso en el modulo (Permisos)'),
(2, '2024-06-26 15:20:57', 'cambio los permiso de un rol'),
(2, '2024-06-26 15:20:58', 'cambio los permiso de un rol'),
(2, '2024-06-26 15:20:59', 'cambio los permiso de un rol'),
(2, '2024-06-26 15:21:00', 'cambio los permiso de un rol'),
(2, '2024-06-26 16:31:35', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 17:36:10', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 17:36:50', 'Registro al usuarios (V-2725054)'),
(2, '2024-06-26 23:52:28', 'Inicio de sesión'),
(2, '2024-06-26 23:52:28', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:52:59', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:53:09', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:54:01', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:54:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:58:01', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-26 23:58:44', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-27 03:27:00', 'Inicio de sesión'),
(2, '2024-06-27 03:27:01', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-06-27 03:28:12', 'Registro al usuarios (V-12434091)'),
(2, '2024-07-01 15:07:43', 'Inicio de sesión'),
(2, '2024-07-01 15:07:44', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-01 15:15:15', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-01 15:17:30', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-01 15:17:33', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-01 15:17:36', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-01 15:21:42', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-01 15:21:45', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-01 15:23:57', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-01 15:30:41', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:50:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:51:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:51:53', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:52:45', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:54:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:55:39', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:57:11', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:57:22', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 02:59:40', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:00:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:03:30', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:07:33', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:16:29', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:17:41', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:19:29', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:20:37', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:29:02', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:35:19', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:35:46', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:36:43', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:37:36', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:37:51', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:40:11', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:41:06', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:42:09', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:42:49', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 03:43:08', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:00:18', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:00:42', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:00:54', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:02:51', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:03:25', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:03:50', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:04:13', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:04:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:23:47', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:24:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:25:10', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:29:28', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:31:15', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:31:52', 'Registro al usuarios (V-2725051)'),
(2, '2024-07-05 04:35:23', 'Elimino al usuario (V-2725051)'),
(2, '2024-07-05 04:35:49', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:37:12', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:37:40', 'Registro al usuarios (V-2725051)'),
(2, '2024-07-05 04:41:24', 'Elimino al usuario (V-2725051)'),
(2, '2024-07-05 04:47:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 04:51:05', 'Registro al usuarios (V-2725051)'),
(2, '2024-07-05 05:01:16', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:03:30', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:09:44', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:13:52', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:21:28', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:22:53', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:23:07', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:24:23', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:25:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:25:29', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:25:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:25:44', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:27:20', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:27:28', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:31:10', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:31:57', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:33:11', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:34:16', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:35:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:36:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:37:07', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:38:17', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:45:19', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:45:30', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:45:42', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:45:52', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:47:36', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:47:49', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:47:58', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:48:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:48:40', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:51:30', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:51:40', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:51:49', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:55:37', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:56:37', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:57:11', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:57:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:58:05', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 05:58:20', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 05:59:56', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 06:00:23', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 06:00:41', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 06:00:59', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:01:41', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 06:02:16', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:03:08', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 06:03:26', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:07:48', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:07:55', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:08:21', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:08:40', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-05 06:15:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 06:19:27', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:19:36', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:19:42', 'Modifico al usuario (V-2725051)'),
(2, '2024-07-05 06:20:26', 'Elimino al usuario (V-2725051)'),
(2, '2024-07-05 06:20:35', 'Elimino al usuario (V-2725054)'),
(2, '2024-07-05 06:20:47', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 06:23:00', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 06:23:07', 'Elimino al usuario (V-2725054)'),
(2, '2024-07-05 06:25:23', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 06:25:30', 'Elimino al usuario (V-2725054)'),
(2, '2024-07-05 06:33:52', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-05 06:33:56', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-05 06:34:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-05 06:34:11', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-05 06:51:33', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-05 06:52:04', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-05 06:53:01', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-05 06:53:36', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-05 06:56:36', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-05 06:56:41', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:11:56', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:12:31', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:12:59', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:13:47', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:36:38', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 07:37:13', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 07:37:55', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 07:38:10', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 07:39:37', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 07:40:08', 'registro un hijo para V-2725054'),
(2, '2024-07-05 07:40:08', 'registro un hijo para V-27250544'),
(2, '2024-07-05 07:42:00', 'registro un hijo para V-2725054'),
(2, '2024-07-05 07:42:00', 'registro un hijo para V-27250544'),
(2, '2024-07-05 07:42:28', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:42:50', 'Elimino un hijo del registro'),
(2, '2024-07-05 07:42:53', 'Elimino un hijo del registro'),
(2, '2024-07-05 07:43:13', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:43:52', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 07:43:54', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:44:20', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:48:21', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 07:48:28', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 07:48:33', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:10:46', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 08:11:22', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 08:11:32', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:12:10', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:12:49', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:15:43', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:16:03', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:16:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:17:44', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:19:24', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:19:43', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:21:21', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:25:16', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:31:44', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:34:46', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:35:59', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:40:32', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:44:54', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:45:50', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:45:53', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:49:31', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-05 08:55:13', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 08:55:47', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:21:51', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:22:51', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:25:05', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:25:35', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:27:48', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:28:22', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:29:30', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:32:42', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:33:26', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:34:08', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:35:58', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:38:49', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:40:11', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:41:00', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:41:17', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:42:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:43:46', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:44:19', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:44:41', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:45:28', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:45:39', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:46:20', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:46:55', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:49:54', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:51:28', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:51:48', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:52:34', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:52:53', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:56:33', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:56:46', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 09:57:07', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:00:04', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:00:36', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:02:13', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:03:17', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:04:26', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:05:09', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:07:33', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:10:03', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:11:56', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:13:12', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:14:24', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:15:27', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:15:58', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:18:08', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:19:28', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:20:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:23:43', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:25:39', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:27:21', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:30:03', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:33:00', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:34:14', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:35:10', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:35:46', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:37:12', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:38:54', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 10:39:10', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 11:30:53', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 11:36:29', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 11:37:17', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 11:38:08', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 11:38:40', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:25:33', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:49:11', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:50:17', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:50:52', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:51:29', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:53:12', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:53:18', 'Asigno el sueldo del trabajador Array'),
(2, '2024-07-05 12:55:06', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:55:13', 'Asigno el sueldo del trabajador V-27250544'),
(2, '2024-07-05 12:56:27', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:57:17', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:57:24', 'Asigno el sueldo del trabajador V-27250544'),
(2, '2024-07-05 12:58:13', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 12:58:20', 'Asigno el sueldo del trabajador V-27250544'),
(2, '2024-07-05 12:58:40', 'Asigno el sueldo del trabajador V-12434091'),
(2, '2024-07-05 13:01:19', 'Borro el sueldo del trabajador V-12434091'),
(2, '2024-07-05 13:03:52', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 13:04:52', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 13:04:57', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 13:05:16', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-05 13:05:41', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 01:14:42', 'Asigno el sueldo del trabajador V-12434091'),
(2, '2024-07-06 01:23:46', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 01:24:10', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 01:36:47', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 01:36:52', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 01:38:55', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 01:40:40', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 01:40:47', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:42:17', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:42:33', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:43:53', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:44:48', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:45:59', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:46:25', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:46:54', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:47:32', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:51:43', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 01:52:17', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 02:19:18', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 02:19:21', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 02:20:20', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 02:24:51', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 02:27:15', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 04:52:43', 'Inicio de sesión'),
(2, '2024-07-06 04:54:18', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 04:54:25', 'Inicio de sesión'),
(2, '2024-07-06 04:54:30', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 04:54:38', 'Inicio de sesión'),
(2, '2024-07-06 04:54:45', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 04:54:52', 'Inicio de sesión'),
(2, '2024-07-06 04:54:52', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 04:54:58', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 04:55:24', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 04:55:30', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 04:55:55', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 04:56:53', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 04:57:17', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 05:00:17', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 05:00:56', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 05:06:57', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 05:09:39', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 05:16:43', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 05:22:43', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 05:31:38', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 05:33:30', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 05:34:53', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 06:04:47', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-06 06:04:58', 'Asigno el sueldo del trabajador V-2725054'),
(2, '2024-07-06 06:14:26', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 20:13:38', 'Inicio de sesión'),
(2, '2024-07-06 20:13:38', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 20:14:16', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 20:18:53', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-06 20:18:56', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:27:22', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:27:52', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:28:33', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:29:02', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:32:12', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:32:27', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:32:35', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:33:00', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:37:25', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:37:33', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:43:06', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:43:48', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:45:17', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:47:10', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:48:46', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:49:10', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 20:49:59', 'Ingreso en el modulo (Asignaciones)'),
(2, '2024-07-06 21:20:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:21:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:23:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:24:16', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:25:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:25:48', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:26:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:26:55', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:28:04', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:28:53', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:29:26', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:41:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:43:52', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:44:40', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:45:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:45:35', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:45:57', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:52:56', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 21:53:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:16:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:17:28', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:18:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:19:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:20:20', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:22:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:22:28', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:48:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:49:09', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:49:43', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:50:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:52:19', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:55:07', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:55:35', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:56:28', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 22:59:13', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 23:29:01', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 23:34:19', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 23:35:04', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 23:41:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 23:41:56', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 23:42:25', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 23:42:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-06 23:50:22', 'Inicio de sesión'),
(2, '2024-07-06 23:50:22', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 23:51:49', 'Inicio de sesión'),
(2, '2024-07-06 23:51:49', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 23:51:58', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 23:54:55', 'Inicio de sesión'),
(2, '2024-07-06 23:54:56', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-06 23:55:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 00:14:40', 'Inicio de sesión'),
(2, '2024-07-07 00:14:40', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:15:58', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:16:08', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:16:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:16:31', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:17:40', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:18:12', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:23:46', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:25:35', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:26:00', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:29:23', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:35:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:35:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:42:58', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:46:12', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:46:29', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:47:18', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:47:28', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:47:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:48:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:54:05', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:54:29', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:55:18', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:57:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:57:49', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:57:52', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 00:58:23', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 01:03:10', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 01:08:50', 'Inicio de sesión'),
(2, '2024-07-07 01:08:50', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-07 01:08:56', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-07 01:09:02', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-07 01:10:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 01:27:11', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 01:27:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 01:28:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 01:29:23', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 01:29:36', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 01:39:52', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:41:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:42:53', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:43:04', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:45:59', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:46:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:46:34', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:46:58', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:47:06', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:47:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:47:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:50:55', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:51:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:51:20', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:52:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:53:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:55:50', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:56:46', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 02:57:51', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 03:03:54', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 03:11:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 03:12:11', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 03:22:17', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-07 03:22:58', 'Asigno el sueldo del trabajador V-2725054'),
(2, '2024-07-07 03:23:22', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-07 03:23:32', 'Asigno el sueldo del trabajador V-2725054'),
(2, '2024-07-07 03:23:48', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-07 03:24:11', 'Asigno el sueldo del trabajador V-2725054'),
(2, '2024-07-07 03:26:15', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-07 03:26:22', 'Asigno el sueldo del trabajador V-2725054'),
(2, '2024-07-07 03:27:58', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-07 03:46:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 03:47:49', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 03:57:51', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 03:59:07', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:03:08', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:06:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:06:58', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:19:34', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:29:53', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:29:59', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:32:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:42:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:45:57', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 04:46:13', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:03:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:03:30', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:03:36', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:32:04', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:32:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:33:40', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:50:15', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:53:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:55:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 05:55:37', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 06:00:46', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 06:06:13', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 06:07:15', 'Registro la prima por hijo (Prima por hijo)'),
(2, '2024-07-07 06:07:37', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 06:08:17', 'Registro la prima por hijo (Prima por hijo)'),
(2, '2024-07-07 06:08:39', 'Registro la prima por hijo (Prima por hijo)'),
(2, '2024-07-07 06:19:17', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 06:20:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-07 06:20:21', 'Elimino la prima (Prima por hijo)'),
(2, '2024-07-07 06:20:25', 'Elimino la prima (Prima por hijo)'),
(2, '2024-07-09 21:33:12', 'Inicio de sesión'),
(2, '2024-07-09 21:33:12', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 21:33:25', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-09 21:34:09', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-09 21:36:39', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-09 21:37:04', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-09 21:37:11', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 21:37:15', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-09 21:37:19', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:23', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:30', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:30', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:31', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:31', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:34', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-09 21:37:42', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-09 21:37:46', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-09 21:37:49', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:51', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:52', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:37:55', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-09 21:38:43', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-09 21:38:58', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:39:00', 'cambio los permiso de un rol'),
(2, '2024-07-09 21:39:21', 'Ingreso en el modulo (2)'),
(2, '2024-07-09 21:39:24', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-09 21:40:26', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-09 21:40:43', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-09 21:40:55', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-09 21:43:40', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:44:17', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-09 21:44:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:44:45', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:47:19', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:47:26', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:50:56', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-09 21:56:09', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-09 21:56:26', 'Borro el sueldo del trabajador V-12434091'),
(2, '2024-07-09 22:06:48', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-09 22:06:50', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-09 22:07:08', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-09 22:08:18', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:09:12', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 21:49:56', 'Inicio de sesión'),
(2, '2024-07-13 21:49:56', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-13 21:50:03', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 21:50:07', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-07-13 21:50:09', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 21:50:14', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-13 22:02:41', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:05:37', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:08:51', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:09:37', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:11:27', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:11:42', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:11:44', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:26:31', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:27:31', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:28:27', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:30:02', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:32:58', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:35:02', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:40:14', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:40:47', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:44:19', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:48:38', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:49:28', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:49:36', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:52:49', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:54:38', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:56:59', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:58:39', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 22:59:24', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 23:00:23', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 23:00:23', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 23:00:58', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 23:02:10', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 23:03:20', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 23:03:42', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 23:04:16', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-13 23:10:15', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 01:08:47', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 03:18:43', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 03:35:03', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 03:51:20', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 03:52:35', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 03:53:38', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 15:30:04', 'Inicio de sesión'),
(2, '2024-07-14 15:30:04', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-14 15:54:33', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 15:57:07', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 15:57:51', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 16:00:21', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 16:00:22', 'Ingreso en el modulo (2)'),
(2, '2024-07-14 16:03:22', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 16:04:38', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 16:22:14', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 16:22:27', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-07-14 16:22:35', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 16:22:41', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-14 16:22:44', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-14 17:26:08', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-15 04:38:20', 'Inicio de sesión'),
(2, '2024-07-15 04:38:20', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-15 04:38:23', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:35:44', 'Inicio de sesión'),
(2, '2024-07-16 01:35:45', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-16 01:35:50', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:40:34', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:41:28', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:41:30', 'Ingreso en el modulo (2)'),
(2, '2024-07-16 01:42:54', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:44:33', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:48:05', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:51:01', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:53:16', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:54:29', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 01:59:31', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 02:03:09', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 02:05:05', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 02:05:54', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 02:07:28', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 02:07:28', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 02:36:39', 'Ingreso en el modulo (2)'),
(2, '2024-07-16 02:38:04', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-16 03:12:08', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 01:51:30', 'Inicio de sesión'),
(2, '2024-07-17 01:51:30', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 01:51:33', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 01:55:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 01:57:12', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 02:35:37', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 03:30:33', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 03:30:57', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 03:34:27', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 03:34:45', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 03:34:53', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 03:35:56', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 03:36:30', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 03:37:01', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 16:05:05', 'Inicio de sesión'),
(2, '2024-07-17 16:05:06', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 16:05:09', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 16:54:32', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 16:58:29', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:06:23', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:06:53', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:07:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:08:25', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:08:59', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:09:23', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:20:04', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:33:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:34:10', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:39:32', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:40:10', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:40:24', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:41:52', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 17:42:18', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:10:20', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:11:39', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:14:39', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:15:13', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:19:29', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:24:04', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:24:56', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:36:30', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:49:14', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 18:49:41', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:05:03', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:07:58', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:08:39', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:08:52', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:11:34', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:14:33', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:20:09', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:24:13', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:28:32', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:28:46', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-17 19:29:00', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:30:36', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:30:44', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:31:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:36:45', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:38:23', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:40:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:40:59', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:42:01', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:42:22', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:44:10', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:44:39', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:44:39', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:44:58', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:46:09', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:47:25', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 19:50:44', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 20:39:41', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 20:57:30', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 20:57:54', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 20:59:46', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:00:53', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:03:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:04:01', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:07:07', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:08:26', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:08:53', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:09:30', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:14:45', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:16:33', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:19:21', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:19:58', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:21:03', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:23:25', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:25:13', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:26:02', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:26:43', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:30:12', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:31:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:31:39', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:32:01', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:36:53', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:37:34', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:38:41', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:39:39', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:40:48', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:41:56', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:43:56', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:44:29', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 21:45:01', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:09:59', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:11:55', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:14:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:21:36', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:24:01', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:25:36', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:26:45', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:27:51', 'Ingreso en el modulo (Areas)');
INSERT INTO `bitacora` (`id_trabajador`, `fecha`, `descripcion`) VALUES
(2, '2024-07-17 22:38:36', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 03:01:11', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 05:19:42', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 05:19:56', 'Ingreso en el modulo (asistencias)'),
(2, '2024-07-18 05:20:04', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 05:20:12', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 05:20:17', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 16:11:20', 'Inicio de sesión'),
(2, '2024-07-18 16:11:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:11:24', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-18 16:11:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:11:33', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-18 16:11:35', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:11:51', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-18 16:11:55', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:17:13', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 16:19:01', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:19:48', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 16:20:29', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 16:20:46', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:21:37', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:22:43', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:30:24', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:31:47', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:33:00', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:34:10', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:36:24', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:36:51', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:46:45', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:47:19', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:47:54', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:48:29', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:49:37', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:50:10', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:53:54', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 16:59:29', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:01:47', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:04:44', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 17:04:45', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 17:21:59', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:35:45', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:50:36', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:53:37', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:56:14', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:57:34', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:58:08', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:59:25', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 17:59:49', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 18:00:01', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 18:11:40', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 18:11:51', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 18:12:22', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 18:14:01', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 18:14:01', 'Ingreso en el modulo (areasTrabajador)'),
(2, '2024-07-18 18:19:37', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-18 18:19:39', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 18:19:43', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-18 18:19:46', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-18 18:19:50', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 18:53:41', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-18 18:53:47', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 19:44:28', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 19:44:42', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 19:45:03', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 19:45:12', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 19:48:25', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 19:48:34', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 20:10:18', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 21:05:36', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 21:27:28', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:29:16', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:29:53', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:32:19', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:34:46', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:35:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:37:32', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:40:29', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:41:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:41:33', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 21:41:44', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:43:45', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:46:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:47:46', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:48:04', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:51:42', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:53:25', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:53:36', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 21:55:08', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 22:15:23', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 22:15:52', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 22:20:57', 'Inicio de sesión'),
(2, '2024-07-18 22:20:59', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 22:21:29', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 22:36:05', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 22:40:59', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 22:41:57', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 22:44:46', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 23:05:55', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 23:06:08', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 23:07:39', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:10:56', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 23:11:59', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 23:25:20', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 23:25:25', 'Ingreso en el modulo (2)'),
(2, '2024-07-18 23:26:55', 'Ingreso en el modulo (2)'),
(2, '2024-07-18 23:27:01', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:29:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:29:51', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:30:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:31:09', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:33:48', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:34:06', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:34:25', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:34:49', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:35:55', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:36:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:36:25', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:37:05', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:37:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:43:01', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:43:50', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:45:02', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:46:19', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:46:30', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:47:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:48:09', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-18 23:48:24', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:54:58', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:55:47', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:56:56', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:57:25', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:58:12', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-18 23:59:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:01:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 00:01:19', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:01:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:04:10', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:09:21', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:11:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:13:59', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:14:47', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:15:00', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:20:56', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:21:04', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:39:03', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:39:03', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:40:21', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:40:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:41:20', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:41:20', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:46:33', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:46:33', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:46:36', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 00:46:59', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 01:16:34', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 01:24:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 01:24:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 01:58:59', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:07:45', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:07:45', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:08:02', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:08:02', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:08:06', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:08:06', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:12:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:12:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:12:21', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:12:23', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:12:23', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:16:25', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:16:25', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:16:29', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:16:29', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:19:24', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:19:24', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:19:39', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:19:40', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:19:52', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:19:53', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:20:10', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:20:10', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:20:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:20:23', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:20:45', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:20:45', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:20:49', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 02:21:37', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 02:21:59', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 02:22:05', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 02:22:08', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:22:08', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:22:11', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 02:27:04', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 02:27:07', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:27:07', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:37:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:52:46', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:52:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:53:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:54:03', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:55:25', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:55:40', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 02:55:45', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 02:55:50', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:56:20', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:56:29', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:58:06', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:58:47', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:58:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:59:03', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:00:39', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:03:33', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:04:04', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:04:34', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:08:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:08:59', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:12:00', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:16:51', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:19:00', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:20:24', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:23:18', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:23:37', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:26:46', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:27:21', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:28:28', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 03:31:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:31:15', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:31:28', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:34:42', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:35:12', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 03:52:09', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 03:52:54', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 03:53:55', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 03:56:36', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 03:57:09', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:57:22', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 03:59:30', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 04:00:11', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 04:08:34', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 04:08:43', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 04:11:05', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 04:11:39', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:11:39', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:11:42', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 04:15:59', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 04:16:38', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 04:18:36', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 04:19:43', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 04:20:17', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 04:20:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:21:58', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:22:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:22:29', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:22:29', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:22:38', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 04:27:30', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:27:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:27:56', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:27:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:28:01', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 04:33:38', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 04:34:21', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:34:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:08:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:08:59', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:10:00', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:10:01', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:19:45', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:19:45', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:20:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:20:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:21:59', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:21:59', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:24:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:24:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:24:50', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:24:50', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:25:40', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:25:40', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:27:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:27:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:27:46', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:27:46', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:30:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:30:12', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:30:55', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:30:56', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:21:50', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:21:50', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:27:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:27:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:29:46', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:29:46', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:30:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:30:32', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:30:48', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:30:49', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:31:04', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:34:53', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 06:38:56', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 06:39:40', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 06:40:35', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 06:54:13', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 06:54:59', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 06:57:18', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 06:57:29', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:02:34', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:02:59', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:03:03', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:04:16', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:23:47', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:23:51', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 07:25:27', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 07:26:30', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 07:32:41', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 07:43:36', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:45:03', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:46:01', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:46:58', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:47:10', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:47:24', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:51:09', 'Inicio de sesión'),
(2, '2024-07-19 07:51:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 07:51:41', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-19 07:52:28', 'Registro al usuarios (V-27250343)'),
(2, '2024-07-19 07:52:33', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 07:52:45', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 07:52:58', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 07:52:58', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 07:53:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 07:53:41', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-19 10:45:46', 'Inicio de sesión'),
(2, '2024-07-19 10:45:48', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:45:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:46:40', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:46:55', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 10:47:12', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 10:47:37', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:47:37', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:47:43', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:47:47', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 10:48:40', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-19 10:48:44', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-19 10:48:45', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:48:53', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:49:00', 'Inicio de sesión'),
(2, '2024-07-19 10:49:01', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:53:16', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:54:56', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:55:32', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 10:56:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:56:25', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 10:56:38', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 10:59:37', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:00:03', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:00:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:00:55', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:02:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:02:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:02:25', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 11:02:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:03:02', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:03:03', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:05:30', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:06:32', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:07:20', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 11:17:15', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-19 11:17:23', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 11:36:12', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 19:21:25', 'Inicio de sesión'),
(2, '2024-07-19 19:21:36', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 19:21:42', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 19:21:49', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 20:31:29', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 20:34:05', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 20:34:09', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-19 20:37:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 20:57:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 20:57:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:01:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:01:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:01:55', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:01:55', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:02:15', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:02:15', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:02:23', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:02:23', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:03:49', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:03:49', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:10:32', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:10:33', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:11:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:11:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:11:55', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:11:55', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:12:08', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:12:09', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:20:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:20:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:21:06', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:21:06', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:21:21', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:21:21', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:23:43', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:23:43', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:24:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:24:11', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:24:18', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:24:18', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:26:07', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 21:26:07', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:10:34', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:10:35', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:11:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:13:43', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:13:43', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:13:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:14:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:14:17', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:15:41', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:15:41', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 22:17:50', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:17:51', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:18:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:18:07', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:18:55', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:19:05', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:19:07', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:19:09', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:19:11', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:19:16', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:19:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:23:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:23:02', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:23:13', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:25:27', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:25:27', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:25:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:26:40', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:26:40', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:26:57', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:26:57', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:27:28', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:39:19', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:39:19', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:39:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:40:24', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:40:24', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 22:40:42', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:12:02', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:12:02', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:12:48', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:12:48', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:13:38', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:13:38', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:14:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:14:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:14:51', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:14:51', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:15:04', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:15:04', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:16:02', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:16:02', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:16:19', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:16:19', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:16:35', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:16:35', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:17:40', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:17:40', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:18:49', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:18:49', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:19:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:19:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:20:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:20:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:21:56', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:21:57', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:22:15', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:22:15', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:22:27', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:22:27', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:23:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:23:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:23:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:23:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:24:11', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:24:12', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:26:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:26:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:26:52', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:26:52', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:28:13', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:28:13', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:28:50', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:28:50', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:28:58', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:28:59', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:29:51', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:29:51', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:30:29', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:30:30', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:30:43', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:30:43', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:31:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:31:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:32:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:32:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:32:38', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:32:38', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:33:00', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:33:00', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:34:24', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:34:26', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:35:32', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:35:32', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:36:00', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:36:00', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:37:06', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:37:16', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:37:29', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:40:13', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:40:14', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:40:36', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:40:36', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:40:58', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:40:58', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:46:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:46:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:46:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:46:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:46:30', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:47:36', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:47:36', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:47:36', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:47:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:35', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:47', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:47', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:47', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:50:47', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:51:32', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:51:32', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:51:32', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-19 23:51:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:05:28', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:05:28', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:05:28', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:05:28', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:06:44', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:06:45', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:06:46', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:06:46', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:06:46', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:07:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:07:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:07:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:07:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:07:35', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:09', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:09', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:09', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:09', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:09', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:09:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:17', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:24', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:24', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:29', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:29', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:29', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:30', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:11:30', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:14', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:14', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:14', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:15', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:15', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:25', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:26', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:26', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:26', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:26', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:39', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:39', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:39', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:39', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:39', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:55', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:55', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:55', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:56', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:14:56', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:23', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:34', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:40', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:40', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:40', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:41', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:15:41', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:16:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:16:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:16:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:16:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:16:33', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:17:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:17:01', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:17:02', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:17:02', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:17:03', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:18:57', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:18:57', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:18:57', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:18:57', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:18:57', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:20:04', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:20:04', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:20:05', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:20:05', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:20:05', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:35:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:35:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:35:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:35:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:35:37', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:35:38', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:35:38', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:41:21', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:41:21', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:41:21', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:41:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:41:22', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:42:55', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:42:55', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:42:55', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:42:56', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:42:56', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:43:05', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:47:08', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-20 00:59:42', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:59:42', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:59:42', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:59:42', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 00:59:42', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 03:00:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 03:00:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 03:00:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 03:00:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 03:00:53', 'Ingreso en el modulo (Estadistica)'),
(2, '2024-07-20 03:00:58', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:01:05', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:01:07', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:02:11', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:02:14', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-20 03:02:17', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:02:21', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:02:56', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:03:06', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:03:11', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:03:18', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:04:21', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:04:27', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:05:44', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:05:48', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 03:17:22', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:18:10', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:29:55', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:30:14', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:31:00', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:32:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:33:01', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:33:05', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:33:26', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:34:33', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:34:42', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:36:19', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:36:41', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:40:41', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:43:13', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:43:49', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:43:52', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:45:46', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:45:50', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:45:54', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:46:02', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:46:38', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:47:06', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:47:12', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:49:08', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:52:38', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:55:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:56:16', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:57:00', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 03:57:24', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:57:35', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 03:58:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-20 03:58:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-20 03:59:52', 'Registro al usuarios (V-28406750)'),
(2, '2024-07-20 04:00:25', 'Inicio de sesión'),
(2, '2024-07-20 04:00:26', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 04:01:59', 'Inicio de sesión'),
(2, '2024-07-20 04:02:00', 'Ingreso en el modulo (Bitacora)'),
(6, '2024-07-20 04:02:39', 'Inicio de sesión'),
(6, '2024-07-20 04:02:40', 'Ingreso en el modulo (Bitacora)'),
(6, '2024-07-20 04:02:49', 'Ingreso en el modulo (Notificaciones)'),
(6, '2024-07-20 04:05:26', 'Ingreso en el modulo (2)'),
(6, '2024-07-20 04:05:37', 'Ingreso en el modulo (Roles)'),
(6, '2024-07-20 04:05:41', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 04:33:08', 'Inicio de sesión'),
(2, '2024-07-20 04:33:13', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 04:56:54', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 05:15:41', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 05:18:20', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 05:18:33', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:18:41', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:19:17', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:19:47', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:19:50', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:19:54', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:20:12', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:22:56', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:23:15', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:24:13', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 05:24:18', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 06:05:26', 'Inicio de sesión'),
(2, '2024-07-20 06:05:27', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 06:05:36', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 06:05:40', 'Ingreso en el modulo (Notificaciones)'),
(2, '2024-07-20 06:14:30', 'Inicio de sesión'),
(2, '2024-07-20 06:14:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 06:21:09', 'Inicio de sesión'),
(2, '2024-07-20 06:21:10', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 07:10:15', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-20 07:17:42', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-20 07:17:45', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-20 07:17:48', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-20 07:20:27', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-20 09:16:18', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-20 09:21:35', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-20 09:23:25', 'Ingreso en el modulo (Areas)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario`
--

CREATE TABLE `calendario` (
  `id` int(5) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` date NOT NULL,
  `recurrente` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `calendario`
--

INSERT INTO `calendario` (`id`, `descripcion`, `fecha`, `recurrente`) VALUES
(1, 'Hola wapo1', '2026-09-19', 0),
(2, 'Hola wwww', '0000-09-04', 0),
(3, 'Un dia como hoy mi cassas', '2024-05-09', 0),
(18, '9rywe89rhwe98hrwe', '2024-06-11', 0),
(19, 'Dia de la raza', '2024-06-07', 1),
(22, 'Dia de la raza', '2024-06-07', 1),
(25, '31312312', '2024-06-10', 0),
(26, 'Dia de la madre', '2024-05-10', 0),
(27, 'aass', '2025-06-09', 0),
(0, 'Dia de la raza', '2024-07-16', 0),
(0, 'Feriado 2', '2024-07-18', 0),
(0, 'feriado 4', '2024-07-22', 0),
(0, 'feriado nose', '2024-07-25', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deducciones`
--

CREATE TABLE `deducciones` (
  `id_deducciones` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `porcentaje` tinyint(1) NOT NULL,
  `multi_meses` int(11) NOT NULL,
  `div_sem` int(11) NOT NULL,
  `quincena` tinyint(1) NOT NULL,
  `multi_dia` tinyint(1) NOT NULL,
  `sector_salud` tinyint(1) NOT NULL,
  `islr` tinyint(1) NOT NULL,
  `dedicada` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `deducciones`
--

INSERT INTO `deducciones` (`id_deducciones`, `descripcion`, `monto`, `porcentaje`, `multi_meses`, `div_sem`, `quincena`, `multi_dia`, `sector_salud`, `islr`, `dedicada`) VALUES
(1, 'Perdida involuntaria de empleo', 0.50, 1, 12, 52, 1, 1, 0, 0, 0),
(2, 'prueva dedicadas', 125.00, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_factura`
--

CREATE TABLE `detalles_factura` (
  `id_factura` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `prima` tinyint(1) NOT NULL,
  `islr` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Disparadores `detalles_factura`
--
DELIMITER $$
CREATE TRIGGER `BD_detalles_factura` BEFORE DELETE ON `detalles_factura` FOR EACH ROW BEGIN 
    IF OLD.prima IS TRUE THEN
        UPDATE factura 
        SET sueldo_integral = sueldo_integral - OLD.monto
        WHERE factura.id_factura = OLD.id_factura;
    ELSE
        UPDATE factura 
        SET sueldo_deducido = sueldo_deducido - OLD.monto
        WHERE factura.id_factura = OLD.id_factura;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `BI_detalles_factura` BEFORE INSERT ON `detalles_factura` FOR EACH ROW BEGIN
	IF NEW.prima IS TRUE THEN
    	UPDATE factura
        SET sueldo_integral = sueldo_integral + NEW.monto 
        WHERE factura.id_factura = NEW.id_factura;
	ELSE 
    	UPDATE factura
        SET sueldo_deducido = sueldo_deducido + NEW.monto 
        WHERE factura.id_factura = NEW.id_factura;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `BU_detalles_factura` BEFORE UPDATE ON `detalles_factura` FOR EACH ROW BEGIN
    IF OLD.prima <> NEW.prima THEN
        IF OLD.prima IS TRUE THEN
            -- significa que paso de prima a deduccion
            # restar sueldo_integral
            # sumar sueldo deduccion
            UPDATE factura
            SET sueldo_integral = sueldo_integral - OLD.monto ,
            sueldo_deducido = sueldo_deducido + NEW.monto
            WHERE factura.id_factura = OLD.id_factura;
        ELSE
            -- significa que paso de deduccio a prima
            # sumar sueldo integral
            # restar sueldo deduccion

            UPDATE factura
            SET sueldo_integral = sueldo_integral + NEW.monto ,
            sueldo_deducido = sueldo_deducido - OLD.monto
            WHERE factura.id_factura = OLD.id_factura;

        
        END IF;
    ELSEIF OLD.monto <> NEW.monto THEN
        IF OLD.prima IS TRUE THEN
            -- si no cambio el tipo sino el monto y era una prima
            # resto el monto anterior y sumo el nuevo al sueldo integral

            UPDATE factura
            SET sueldo_integral = (sueldo_integral - OLD.monto) + NEW.monto 
            WHERE factura.id_factura = OLD.id_factura;




        ELSE
            -- si no cambio el tipo sino el monto y era una deduccion
            # resto el monto anterior y sumo el nuevo al sueldo deduccion

            UPDATE factura
            SET sueldo_deducido = (sueldo_deducido - OLD.monto) + NEW.monto
            WHERE factura.id_factura = OLD.id_factura;
        END IF;
    ELSEIF OLD.id_factura <> NEW.id_factura THEN
        SIGNAL SQLSTATE '1USER' SET MESSAGE_TEXT = 'No se puede modificar el id de detalles_factura causara problemas de integridad se recomienda eliminar y crear uno nuevo';
    END IF;


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escalafon`
--

CREATE TABLE `escalafon` (
  `id_escalafon` int(11) NOT NULL,
  `anios_servicio` varchar(45) NOT NULL,
  `escala` varchar(45) NOT NULL,
  `monto` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `escalafon`
--

INSERT INTO `escalafon` (`id_escalafon`, `anios_servicio`, `escala`, `monto`) VALUES
(1, '1 - 2', 'I', 4.67),
(2, '3 - 5', 'II', 9.33),
(3, '6 - 8', 'III', 14.00),
(4, '9 - 11', 'IV', 18.66),
(5, '12 - 14', 'V', 23.33),
(6, '15 - 17', 'VI', 28.00),
(7, '18 - 20', 'VII', 32.66),
(8, '21 - 23', 'VIII', 37.33),
(9, '24 - 26', 'IX', 42.00),
(10, '27 - 29', 'X', 46.66),
(11, '30 - 32', 'XI', 51.33),
(12, '33 En Adelante', 'XII', 55.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `sueldo_base` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sueldo_integral` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sueldo_deducido` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `id_trabajador`, `fecha`, `sueldo_base`, `sueldo_integral`, `sueldo_deducido`, `status`) VALUES
(13, 3, '2024-07-01', 0.00, 0.00, 0.00, 0);

--
-- Disparadores `factura`
--
DELIMITER $$
CREATE TRIGGER `BI_factura` BEFORE INSERT ON `factura` FOR EACH ROW BEGIN
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hijos`
--

CREATE TABLE `hijos` (
  `id_hijo` int(11) NOT NULL,
  `id_trabajador_madre` int(11) DEFAULT NULL,
  `id_trabajador_padre` int(11) DEFAULT NULL,
  `nombre` varchar(60) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('F','M') NOT NULL,
  `discapacidad` tinyint(1) NOT NULL,
  `observacion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `hijos`
--

INSERT INTO `hijos` (`id_hijo`, `id_trabajador_madre`, `id_trabajador_padre`, `nombre`, `fecha_nacimiento`, `genero`, `discapacidad`, `observacion`) VALUES
(7, 2, 3, 'Anabel Teresa', '2024-05-30', 'F', 0, ''),
(8, 2, NULL, 'José Luis', '2024-06-06', 'M', 1, 'mocho');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidacion`
--

CREATE TABLE `liquidacion` (
  `id_liquidacion` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `monto` decimal(13,2) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulos` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulos`, `nombre`) VALUES
(1, 'usuarios'),
(2, 'areas'),
(3, 'permisos'),
(4, 'asistencias'),
(5, 'hijos'),
(6, 'bitacora'),
(7, 'roles'),
(8, 'sueldo'),
(9, 'deducciones'),
(10, 'primas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `mensaje` varchar(250) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `id_usuario`, `status`, `mensaje`, `fecha`) VALUES
(5, 4, 0, 'La vacación del trabajador con cédula V-12434091 termina en 5 días.', '2024-07-19 02:49:19'),
(6, 4, 1, 'Notificación de prueba', '2024-07-21 00:07:50'),
(7, 4, 0, 'Prueba 1234 Notificacion', '2024-07-20 00:30:54'),
(8, 6, 0, 'Una Notificacion', '2024-07-20 00:31:25'),
(9, 4, 1, 'El trabajador con cédula V-12434091 no tiene sueldo asignado.', '2024-07-20 01:18:03'),
(10, 5, 1, 'El trabajador con cédula V-27250343 no tiene sueldo asignado.', '2024-07-20 01:18:03'),
(11, 6, 1, 'El trabajador con cédula V-28406750 no tiene sueldo asignado.', '2024-07-20 01:18:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_rol` int(11) NOT NULL,
  `id_modulos` int(11) NOT NULL,
  `crear` tinyint(4) NOT NULL DEFAULT 1,
  `modificar` tinyint(4) NOT NULL DEFAULT 1,
  `eliminar` tinyint(4) NOT NULL DEFAULT 1,
  `consultar` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_rol`, `id_modulos`, `crear`, `modificar`, `eliminar`, `consultar`) VALUES
(1, 1, 1, 1, 1, 1),
(1, 2, 1, 1, 1, 1),
(1, 3, 1, 1, 1, 1),
(1, 4, 1, 1, 1, 1),
(1, 5, 1, 1, 1, 1),
(1, 6, 1, 1, 1, 1),
(1, 7, 1, 1, 1, 1),
(1, 8, 1, 1, 1, 1),
(1, 9, 1, 1, 1, 1),
(1, 10, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_trabajador`
--

CREATE TABLE `permisos_trabajador` (
  `id_permisos` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo_de_permiso` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `desde` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `permisos_trabajador`
--

INSERT INTO `permisos_trabajador` (`id_permisos`, `id_trabajador`, `tipo_de_permiso`, `descripcion`, `desde`) VALUES
(15, 2, 'Permiso de trabajo', 'Awwaw', '2024-07-18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `primas_generales`
--

CREATE TABLE `primas_generales` (
  `id_primas_generales` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `monto` decimal(13,2) NOT NULL,
  `porcentaje` tinyint(1) NOT NULL,
  `sector_salud` tinyint(1) NOT NULL,
  `dedicada` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `primas_generales`
--

INSERT INTO `primas_generales` (`id_primas_generales`, `descripcion`, `monto`, `porcentaje`, `sector_salud`, `dedicada`) VALUES
(1, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', 10.00, 1, 1, 0),
(2, 'Compensación Por Evaluación', 25.00, 1, 1, 0),
(3, 'prueba dedicada', 15.00, 1, 0, 1),
(4, 'dia del padre', 12.50, 0, 0, 1),
(5, 'dia de la madre', 12.50, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `primas_hijos`
--

CREATE TABLE `primas_hijos` (
  `id_prima_hijos` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `menor_edad` tinyint(1) NOT NULL,
  `porcentaje` tinyint(1) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `discapacidad` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `primas_hijos`
--

INSERT INTO `primas_hijos` (`id_prima_hijos`, `descripcion`, `menor_edad`, `porcentaje`, `monto`, `discapacidad`) VALUES
(1, 'prima hijos 1', 1, 0, 20.00, 1),
(2, 'prima hijos 2', 1, 0, 21.00, 0),
(3, 'prima hijos 3', 0, 0, 22.00, 1),
(4, 'prima hijos 4', 0, 0, 23.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prima_antiguedad`
--

CREATE TABLE `prima_antiguedad` (
  `id_prima_antiguedad` int(11) NOT NULL,
  `anios_antiguedad` int(11) NOT NULL,
  `monto` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `prima_antiguedad`
--

INSERT INTO `prima_antiguedad` (`id_prima_antiguedad`, `anios_antiguedad`, `monto`) VALUES
(1, 1, 1.00),
(2, 2, 2.00),
(3, 3, 3.00),
(4, 4, 4.00),
(5, 5, 5.00),
(6, 6, 6.20),
(7, 7, 7.40),
(8, 8, 8.60),
(9, 9, 9.80),
(10, 10, 11.00),
(11, 11, 12.40),
(12, 12, 13.80),
(13, 13, 15.20),
(14, 14, 16.60),
(15, 15, 18.00),
(16, 16, 19.60),
(17, 17, 21.20),
(18, 18, 22.80),
(19, 19, 24.40),
(20, 20, 26.00),
(21, 21, 27.80),
(22, 22, 29.60),
(23, 23, 30.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prima_profesionalismo`
--

CREATE TABLE `prima_profesionalismo` (
  `id_prima_profesionalismo` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `incremento` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `prima_profesionalismo`
--

INSERT INTO `prima_profesionalismo` (`id_prima_profesionalismo`, `descripcion`, `incremento`) VALUES
(1, 'prueva', 0.00),
(2, 'Técnico Superior Universitario', 20.00),
(3, 'Profesional', 25.00),
(4, 'Especialista', 30.00),
(5, 'Maestria', 35.00),
(6, 'Doctorado', 40.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reposo`
--

CREATE TABLE `reposo` (
  `id_reposo` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo_reposo` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `dias_totales` int(4) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `reposo`
--

INSERT INTO `reposo` (`id_reposo`, `id_trabajador`, `tipo_reposo`, `descripcion`, `dias_totales`, `desde`, `hasta`) VALUES
(1, 4, 'Cudado', 'Se cayo', 12, '0111-11-11', '1111-11-11'),
(4, 3, 'Cudado', 'Se cayo', 1, '2024-07-19', '2024-07-23'),
(5, 4, 'asdad', 'dfas', 4, '2024-07-04', '2024-07-10'),
(6, 4, 'Cudado', 'Se cayo', 0, '2024-07-18', '2024-08-07'),
(7, 2, 'Cudado', 'Se cayo', 0, '2024-07-11', '2024-08-23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `descripcion`) VALUES
(1, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sueldo_base`
--

CREATE TABLE `sueldo_base` (
  `id_sueldo_base` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `sueldo_base` decimal(12,2) DEFAULT NULL,
  `cargo` varchar(50) NOT NULL,
  `sector_salud` tinyint(1) NOT NULL,
  `id_escalafon` int(11) DEFAULT NULL,
  `tipo_nomina` enum('Alto Nivel','Contratado','Obrero fijo','Comisión de Servicios') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `sueldo_base`
--

INSERT INTO `sueldo_base` (`id_sueldo_base`, `id_trabajador`, `sueldo_base`, `cargo`, `sector_salud`, `id_escalafon`, `tipo_nomina`) VALUES
(1, 2, 150.00, 'Enfermero', 1, 1, 'Alto Nivel'),
(2, 3, 100.00, 'Enfermero', 1, 2, 'Alto Nivel');

--
-- Disparadores `sueldo_base`
--
DELIMITER $$
CREATE TRIGGER `AI_sueldo_base` AFTER INSERT ON `sueldo_base` FOR EACH ROW BEGIN

INSERT INTO sueldo_base_historial 
VALUES 
(NEW.id_sueldo_base, NEW.sueldo_base, NEW.cargo, NEW.sector_salud, NEW.tipo_nomina, DEFAULT);


END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `BU_sueldo_base` BEFORE UPDATE ON `sueldo_base` FOR EACH ROW BEGIN

	IF OLD.sueldo_base <> NEW.sueldo_base or OLD.cargo <> NEW.cargo or OLD.sector_salud <> NEW.sector_salud or OLD.id_escalafon <> NEW.id_escalafon or OLD.tipo_nomina <> NEW.tipo_nomina THEN

		insert into sueldo_base_historial values (OLD.id_sueldo_base, NEW.sueldo_base, NEW.cargo, new.sector_salud, new.tipo_nomina, DEFAULT);
	END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sueldo_base_historial`
--

CREATE TABLE `sueldo_base_historial` (
  `id_sueldo_base` int(11) NOT NULL,
  `sueldo_base` decimal(12,2) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `sector_salud` tinyint(1) NOT NULL,
  `tipo_nomina` enum('Alto Nivel','Contratado','Obrero fijo','Comisión de Servicios') NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `sueldo_base_historial`
--

INSERT INTO `sueldo_base_historial` (`id_sueldo_base`, `sueldo_base`, `cargo`, `sector_salud`, `tipo_nomina`, `fecha`) VALUES
(1, 100.00, 'Enfermero', 1, 'Alto Nivel', '2024-06-27 02:54:29'),
(2, 100.00, 'Enfermero', 1, 'Alto Nivel', '2024-06-27 17:31:40'),
(1, 150.00, 'Enfermero', 1, 'Alto Nivel', '2024-06-28 18:32:30'),
(2, 100.00, 'Enfermero', 0, 'Alto Nivel', '2024-07-06 06:04:58'),
(2, 100.00, 'Enfermero', 1, 'Alto Nivel', '2024-07-07 03:22:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `id_trabajador` int(11) NOT NULL,
  `id_prima_profesionalismo` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `numero_cuenta` varchar(45) NOT NULL,
  `creado` date NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `estado_actividad` tinyint(4) DEFAULT NULL,
  `comision_servicios` tinyint(1) NOT NULL,
  `discapacitado` tinyint(1) NOT NULL,
  `discapacidad` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`id_trabajador`, `id_prima_profesionalismo`, `id_rol`, `cedula`, `numero_cuenta`, `creado`, `nombre`, `apellido`, `telefono`, `correo`, `clave`, `token`, `estado_actividad`, `comision_servicios`, `discapacitado`, `discapacidad`) VALUES
(2, 2, 1, 'V-27250544', '00000000000000000000', '2020-06-19', 'Xavier David', 'Suarez Sanchez', '0414-5555555', 'uptaebxavier@gmail.com', '$2y$10$RMrtnT5gpHIhIKQDfAThFerj/4yU.S3PABZj.AxnALU2yFAsyrbjC', '$2y$10$enrF.N06MngTqB4q0hnVw.ZqdNB1otX10aC6tlqVLHxBcv6yv3lpi', 1, 0, 0, ''),
(3, 5, 1, 'V-2725054', '00000000000000000000', '2024-06-27', 'Anabel Teresa', 'Alberto Nuñes', '0414-5555555', 'david40ene@hotmail.com', '$2y$10$Mh5AEfPtMwS4x7cQq7mGX.nEWmSEJyVIFPMgXIWycKpZHGh/Cw1MO', '1', 1, 0, 0, ''),
(4, 6, 1, 'V-12434091', '00000000000000000000', '2024-06-26', 'Valeria Valentina', 'Camacaro Sanchez', '0414-5555555', 'xavier@gmail.com', '$2y$10$TSJZitcQrUt2BiYRNw1Cmu9O4I2zFYIsQINVQweInmJzH1POHZx8K', '1', 1, 0, 0, ''),
(5, 4, 1, 'V-27250343', '21321321212122322232', '2024-07-20', 'Gustavo', 'Gusaba', '0416-2517812', 'asdhas@gmail.com', '$2y$10$gmwYN/KEtnzdHppMh4jMf.0tqBVP5lCNATriUtB73LjDTzSymKuN6', '1', 1, 0, 0, ''),
(6, 3, 1, 'V-28406750', '01026545498468654444', '2024-07-20', 'Luis Angel', 'Colmenarez Aguilar', '0426-3525659', 'asdas@fkamfas.cpa', '$2y$10$61.LjvnQ4M5Z9N1QVYYOqu3HxowNc.L7wlgh5zqq6Epofhk6EXWsi', '$2y$10$aHxGoHht4hpaQ70qMtvHiupmJwBjGNaxOBox98.iTOu1pxQK27pTe', 1, 0, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_area`
--

CREATE TABLE `trabajador_area` (
  `id_trabajador_area` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `trabajador_area`
--

INSERT INTO `trabajador_area` (`id_trabajador_area`, `id_area`, `id_trabajador`) VALUES
(1, 1, 4),
(2, 1, 4),
(3, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_deducciones`
--

CREATE TABLE `trabajador_deducciones` (
  `id_trabajador_deducciones` int(11) NOT NULL,
  `id_deducciones` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `mensual` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `trabajador_deducciones`
--

INSERT INTO `trabajador_deducciones` (`id_trabajador_deducciones`, `id_deducciones`, `id_trabajador`, `mensual`, `status`) VALUES
(1, 2, 2, 1, 1),
(2, 2, 4, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_prima_general`
--

CREATE TABLE `trabajador_prima_general` (
  `id_trabajador_prima_general` int(11) NOT NULL,
  `id_primas_generales` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `mensual` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `trabajador_prima_general`
--

INSERT INTO `trabajador_prima_general` (`id_trabajador_prima_general`, `id_primas_generales`, `id_trabajador`, `mensual`, `status`) VALUES
(1, 3, 2, 1, 1),
(2, 4, 2, 0, 1),
(3, 4, 2, 0, 0),
(4, 5, 3, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones`
--

CREATE TABLE `vacaciones` (
  `id_vacaciones` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `dias_totales` int(99) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `vacaciones`
--

INSERT INTO `vacaciones` (`id_vacaciones`, `id_trabajador`, `descripcion`, `dias_totales`, `desde`, `hasta`) VALUES
(20, 4, 'vacaciones afnuales', 3, '2024-07-17', '2024-07-24'),
(21, 3, 'vacaciones anuales', 10, '2024-07-19', '2024-08-06'),
(22, 2, 'vacaciones anuales', 2, '2024-07-20', '2024-07-24'),
(23, 5, 'vacaciones anuales', 12, '2024-08-13', '2024-08-29');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `fk_Asistencias_Trabajador_Area1_idx` (`id_trabajador_area`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD KEY `id_trabajador` (`id_trabajador`),
  ADD KEY `fecha` (`fecha`);

--
-- Indices de la tabla `deducciones`
--
ALTER TABLE `deducciones`
  ADD PRIMARY KEY (`id_deducciones`);

--
-- Indices de la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `escalafon`
--
ALTER TABLE `escalafon`
  ADD PRIMARY KEY (`id_escalafon`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_trabajador` (`id_trabajador`);

--
-- Indices de la tabla `hijos`
--
ALTER TABLE `hijos`
  ADD PRIMARY KEY (`id_hijo`),
  ADD KEY `id_trabajador_madre` (`id_trabajador_madre`),
  ADD KEY `id_trabajador_padre` (`id_trabajador_padre`);

--
-- Indices de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD PRIMARY KEY (`id_liquidacion`),
  ADD KEY `fk_Liquidacion_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulos`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_rol`,`id_modulos`),
  ADD KEY `fk_Permisos_Rol1_idx` (`id_rol`),
  ADD KEY `fk_Permisos_modulos1_idx` (`id_modulos`);

--
-- Indices de la tabla `permisos_trabajador`
--
ALTER TABLE `permisos_trabajador`
  ADD PRIMARY KEY (`id_permisos`),
  ADD KEY `fk_Permisos_trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `primas_generales`
--
ALTER TABLE `primas_generales`
  ADD PRIMARY KEY (`id_primas_generales`);

--
-- Indices de la tabla `primas_hijos`
--
ALTER TABLE `primas_hijos`
  ADD PRIMARY KEY (`id_prima_hijos`);

--
-- Indices de la tabla `prima_antiguedad`
--
ALTER TABLE `prima_antiguedad`
  ADD PRIMARY KEY (`id_prima_antiguedad`),
  ADD UNIQUE KEY `anios_antiguedad` (`anios_antiguedad`);

--
-- Indices de la tabla `prima_profesionalismo`
--
ALTER TABLE `prima_profesionalismo`
  ADD PRIMARY KEY (`id_prima_profesionalismo`);

--
-- Indices de la tabla `reposo`
--
ALTER TABLE `reposo`
  ADD PRIMARY KEY (`id_reposo`),
  ADD KEY `fk_Reposo_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sueldo_base`
--
ALTER TABLE `sueldo_base`
  ADD PRIMARY KEY (`id_sueldo_base`),
  ADD UNIQUE KEY `id_trabajador` (`id_trabajador`),
  ADD KEY `fk_ sueldo_base_Trabajadores1_idx` (`id_trabajador`),
  ADD KEY `id_escalafon` (`id_escalafon`);

--
-- Indices de la tabla `sueldo_base_historial`
--
ALTER TABLE `sueldo_base_historial`
  ADD KEY `id_sueldo_base` (`id_sueldo_base`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`id_trabajador`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `fk_Trabajadores_prima_profesionalismo1_idx` (`id_prima_profesionalismo`),
  ADD KEY `fk_Trabajadores_Rol1_idx` (`id_rol`);

--
-- Indices de la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  ADD PRIMARY KEY (`id_trabajador_area`),
  ADD KEY `fk_Trabajador_has_Area_Areas1_idx` (`id_area`),
  ADD KEY `fk_Trabajador_Area_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `trabajador_deducciones`
--
ALTER TABLE `trabajador_deducciones`
  ADD PRIMARY KEY (`id_trabajador_deducciones`),
  ADD KEY `fk_trabajador_deducciones_deducciones1_idx` (`id_deducciones`),
  ADD KEY `fk_trabajador_deducciones_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `trabajador_prima_general`
--
ALTER TABLE `trabajador_prima_general`
  ADD PRIMARY KEY (`id_trabajador_prima_general`),
  ADD KEY `fk_trabajador_prima_general_primas_generales1_idx` (`id_primas_generales`),
  ADD KEY `fk_trabajador_prima_general_Trabajadores1_idx` (`id_trabajador`);

--
-- Indices de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD PRIMARY KEY (`id_vacaciones`),
  ADD KEY `fk_Vacaciones_Trabajadores1_idx` (`id_trabajador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `deducciones`
--
ALTER TABLE `deducciones`
  MODIFY `id_deducciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `escalafon`
--
ALTER TABLE `escalafon`
  MODIFY `id_escalafon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `hijos`
--
ALTER TABLE `hijos`
  MODIFY `id_hijo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  MODIFY `id_liquidacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `permisos_trabajador`
--
ALTER TABLE `permisos_trabajador`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `primas_generales`
--
ALTER TABLE `primas_generales`
  MODIFY `id_primas_generales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `primas_hijos`
--
ALTER TABLE `primas_hijos`
  MODIFY `id_prima_hijos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prima_antiguedad`
--
ALTER TABLE `prima_antiguedad`
  MODIFY `id_prima_antiguedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `prima_profesionalismo`
--
ALTER TABLE `prima_profesionalismo`
  MODIFY `id_prima_profesionalismo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `reposo`
--
ALTER TABLE `reposo`
  MODIFY `id_reposo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sueldo_base`
--
ALTER TABLE `sueldo_base`
  MODIFY `id_sueldo_base` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  MODIFY `id_trabajador_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `trabajador_deducciones`
--
ALTER TABLE `trabajador_deducciones`
  MODIFY `id_trabajador_deducciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `trabajador_prima_general`
--
ALTER TABLE `trabajador_prima_general`
  MODIFY `id_trabajador_prima_general` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `id_vacaciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `fk_Asistencias_Trabajador_Area1` FOREIGN KEY (`id_trabajador_area`) REFERENCES `trabajador_area` (`id_trabajador_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`);

--
-- Filtros para la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  ADD CONSTRAINT `detalles_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`) ON DELETE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`);

--
-- Filtros para la tabla `hijos`
--
ALTER TABLE `hijos`
  ADD CONSTRAINT `hijos_ibfk_1` FOREIGN KEY (`id_trabajador_madre`) REFERENCES `trabajadores` (`id_trabajador`),
  ADD CONSTRAINT `hijos_ibfk_2` FOREIGN KEY (`id_trabajador_padre`) REFERENCES `trabajadores` (`id_trabajador`);

--
-- Filtros para la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD CONSTRAINT `fk_Liquidacion_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `fk_Permisos_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Permisos_modulos1` FOREIGN KEY (`id_modulos`) REFERENCES `modulos` (`id_modulos`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos_trabajador`
--
ALTER TABLE `permisos_trabajador`
  ADD CONSTRAINT `fk_Permisos_trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reposo`
--
ALTER TABLE `reposo`
  ADD CONSTRAINT `fk_Reposo_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sueldo_base`
--
ALTER TABLE `sueldo_base`
  ADD CONSTRAINT `fk_ sueldo_base_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sueldo_base_ibfk_1` FOREIGN KEY (`id_escalafon`) REFERENCES `escalafon` (`id_escalafon`);

--
-- Filtros para la tabla `sueldo_base_historial`
--
ALTER TABLE `sueldo_base_historial`
  ADD CONSTRAINT `sueldo_base_historial_ibfk_1` FOREIGN KEY (`id_sueldo_base`) REFERENCES `sueldo_base` (`id_sueldo_base`) ON DELETE CASCADE;

--
-- Filtros para la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD CONSTRAINT `fk_Trabajadores_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Trabajadores_prima_profesionalismo1` FOREIGN KEY (`id_prima_profesionalismo`) REFERENCES `prima_profesionalismo` (`id_prima_profesionalismo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  ADD CONSTRAINT `fk_Trabajador_Area_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Trabajador_has_Area_Areas1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajador_deducciones`
--
ALTER TABLE `trabajador_deducciones`
  ADD CONSTRAINT `fk_trabajador_deducciones_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajador_deducciones_deducciones1` FOREIGN KEY (`id_deducciones`) REFERENCES `deducciones` (`id_deducciones`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajador_prima_general`
--
ALTER TABLE `trabajador_prima_general`
  ADD CONSTRAINT `fk_trabajador_prima_general_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajador_prima_general_primas_generales1` FOREIGN KEY (`id_primas_generales`) REFERENCES `primas_generales` (`id_primas_generales`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD CONSTRAINT `fk_Vacaciones_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `daily_notifications` ON SCHEDULE EVERY 6 HOUR STARTS '2024-07-20 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL check_and_notify_vacations()$$

CREATE DEFINER=`root`@`localhost` EVENT `check_and_notify` ON SCHEDULE EVERY 6 HOUR STARTS '2024-07-20 01:22:01' ON COMPLETION NOT PRESERVE ENABLE DO CALL check_and_notify_salaries()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
