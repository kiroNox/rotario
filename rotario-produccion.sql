-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-07-2024 a las 10:35:26
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rotario-produccion`
--
CREATE DATABASE IF NOT EXISTS `rotario-produccion` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `rotario-produccion`;

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_deducciones` (IN `idTrabajador` INT, IN `fecha_factura` DATE)  BEGIN
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
    DECLARE id_deducciones_DD int;


    DECLARE lista_deducciones CURSOR FOR

    SELECT
        d.id_deducciones
        ,descripcion
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

        FETCH lista_deducciones INTO id_deducciones_DD, deduc_descrip ,deduc_monto ,deduc_porcentaje ,deduc_multi_meses ,deduc_div_sem ,deduc_quincena ,deduc_multi_dia , deduc_sector_salud , deduc_islr , deduc_dedicada;
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




                    -- INSERT INTO detalles_factura 
                    --         (id_factura, descripcion, monto, prima, islr) VALUES
                    --         (
                    --             id_factura_p,
                    --             deduc_descrip,
                    --             deduccion_registrar,
                    --             FALSE,
                    --             deduc_islr
                    --         );
                    INSERT INTO `factura_deducciones`
                    (`id_deduccion`, `id_factura`, `monto`) 
                    VALUES 
                    (id_deducciones_DD,id_factura_p,deduccion_registrar);

                END IF;


            END IF;




            IF filas_encontradas <= 0 or filas_encontradas = 1 THEN
                SET done = TRUE;
            ELSE
                SET filas_encontradas = filas_encontradas - 1;
                FETCH lista_deducciones INTO id_deducciones_DD, deduc_descrip ,deduc_monto ,deduc_porcentaje ,deduc_multi_meses ,deduc_div_sem ,deduc_quincena ,deduc_multi_dia , deduc_sector_salud , deduc_islr , deduc_dedicada;
            END IF;


        END WHILE;

    END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_detalles` (IN `factura_ID` INT)  NO SQL
BEGIN
SELECT pp.descripcion,fp.monto FROM factura_profesionalismo fp 
LEFT JOIN prima_profesionalismo as pp on pp.id_prima_profesionalismo = fp.id_profesionalismo
LEFT JOIN factura f on f.id_factura = fp.id_factura
WHERE f.id_factura = factura_ID
UNION ALL
SELECT pg.descripcion,fpg.monto FROM factura_primas_generales fpg 
LEFT JOIN primas_generales as pg on pg.id_primas_generales = fpg.id_primas_generales 
LEFT JOIN factura f on f.id_factura = fpg.id_factura
WHERE f.id_factura = factura_ID
UNION ALL
SELECT ph.descripcion,fh.monto FROM factura_hijos fh 
LEFT JOIN primas_hijos ph on ph.id_prima_hijos = fh.id_prima_hijos 
LEFT JOIN factura f on f.id_factura = fh.id_factura
WHERE f.id_factura = factura_ID
UNION ALL
SELECT CONCAT('Escalafón - Escala ',e.escala) as descripcion,fe.monto FROM factura_escalafon fe 
LEFT JOIN escalafon e on e.id_escalafon = fe.id_escalafon 
LEFT JOIN factura f on f.id_factura = fe.id_factura
WHERE f.id_factura = factura_ID
UNION ALL
SELECT 'Antiguedad',fa.monto FROM factura_antiguedad fa 
LEFT JOIN prima_antiguedad pa on pa.id_prima_antiguedad = fa.id_prima_antiguedad 
LEFT JOIN factura f on f.id_factura = fa.id_factura
WHERE f.id_factura = factura_ID
UNION ALL
SELECT d.descripcion, CONCAT('-',fd.monto) FROM factura_deducciones fd 
LEFT JOIN deducciones d on d.id_deducciones = fd.id_deduccion 
LEFT JOIN factura f on f.id_factura = fd.id_factura
WHERE f.id_factura = factura_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_escalafon` (IN `idTrabajador` INT, IN `control_insert` BOOLEAN, OUT `monto_devuelto` DECIMAL(12,2))  BEGIN
DECLARE sueldo decimal(13,2);
DECLARE escala varchar(45);
DECLARE esc_porcentaje decimal(5,2);
DECLARE id_factura_p int;
DECLARE id_escala_DD int;
SET monto_devuelto = 0;
    SELECT
        sb.sueldo_base
        ,e.escala
        ,e.monto as porcentaje_escalafon
        ,e.id_escalafon
        
        INTO
        sueldo
        ,escala
        ,esc_porcentaje
        ,id_escala_DD
        
        FROM
            trabajadores AS t
        JOIN sueldo_base AS sb
        ON sb.id_trabajador = t.id_trabajador
        JOIN escalafon as e on e.id_escalafon = sb.id_escalafon
        WHERE t.id_trabajador = 2 AND sb.sector_salud = TRUE LIMIT 1;

    IF esc_porcentaje IS NOT NULL THEN
    
        SET monto_devuelto = ROUND( ((sueldo/100) * esc_porcentaje) , 2 );
        
        IF control_insert IS TRUE THEN
        
            SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;
            
            -- INSERT INTO detalles_factura 
            --     (id_factura, descripcion, monto, prima, islr) VALUES
            --     (
            --         id_factura_p,
            --         CONCAT("Escalafon - escala ", escala ),
            --         monto_devuelto,
            --         TRUE,
            --         FALSE
            --     );


            INSERT INTO `factura_escalafon`
            (`id_escalafon`, `id_factura`, `monto`) VALUES 
            (id_escala_DD, id_factura_p, monto_devuelto);
            
        END IF;

    END IF;
    
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_primas` (IN `fecha_factura` DATE)  BEGIN
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
        0);
        
   

    CALL calcular_prima_hijo(id,sueldo_trabajador);
    CALL calcular_escalafon(id,TRUE,@aqui_no_la_voy_a_usar_XD);
    CALL calcular_primas_generales(id,sueldo_trabajador);
    CALL calcular_profesionalismo(id);
     
    
    
    
    IF f_antiguedad(id) > 0 THEN
    
    	SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = id LIMIT 1;
    
        -- INSERT INTO detalles_factura 
        -- (id_factura, descripcion, monto, prima, islr) VALUES
        -- (
        --     id_factura_p,
        --     "Antiguedad",
        --     f_antiguedad(id),
        --     TRUE,
        --     FALSE
        -- );
        

        INSERT INTO `factura_antiguedad`
        (`id_prima_antiguedad`, `id_factura`, `monto`) 
        VALUES 
        (f_antiguedad_ID(id),id_factura_p, f_antiguedad(id));
        
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_primas_generales` (IN `idTrabajador` INT, IN `sueldo_base` DECIMAL(12,2))  BEGIN 
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
    DECLARE id_prima_DD int;


    DECLARE primas_generales CURSOR FOR
        
        SELECT 
            p.id_primas_generales
            ,p.descripcion
            ,p.monto
            ,p.porcentaje
            ,p.sector_salud 
        from primas_generales as p WHERE p.dedicada IS false;
    DECLARE primas_generales_dedicadas CURSOR FOR

        SELECT
            p.id_primas_generales
            ,p.descripcion
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

        FETCH primas_generales INTO id_prima_DD, prima_descrip, prima_monto, prima_porcen, prima_salud;

        WHILE done IS NOT TRUE DO # las primas no dedicadas



            IF prima_porcen IS TRUE THEN
                set sueldo_total = (sueldo_base / 100  ) * prima_monto;
            ELSE
                set sueldo_total = prima_monto;
            END IF;
            
            

            IF prima_salud IS false OR (prima_salud IS TRUE AND trabajador_salud IS TRUE) THEN 
            
            
            SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;
            
                -- INSERT INTO detalles_factura 
                --     (id_factura, descripcion, monto, prima, islr) VALUES
                --     (
                --         id_factura_p,
                --         prima_descrip,
                --         sueldo_total,
                --         TRUE,
                --         FALSE
                --     );

                INSERT INTO `factura_primas_generales`
                    (`id_primas_generales`, `id_factura`, `monto`) VALUES 
                    (id_prima_DD,id_factura_p,sueldo_total);
            END IF;


            
            #SELECT * FROM factura as f JOIN detalles_factura as df on df.id_factura = f.id_factura WHERE f.status is FALSE ORDER BY f.id_factura,df.descripcion;
            
            IF filas_encontradas_1 <= 0 or filas_encontradas_1 = 1 THEN
                SET done = TRUE;
            ELSE
                set filas_encontradas_1 = filas_encontradas_1 - 1;
                FETCH primas_generales INTO id_prima_DD, prima_descrip, prima_monto, prima_porcen, prima_salud;
            END IF;
            
        END WHILE;

    END IF;

    set done = FALSE;





    IF filas_encontradas_2 > 0 THEN

        FETCH primas_generales_dedicadas INTO id_prima_DD, prima_descrip, prima_monto, prima_porcen;

        WHILE done IS NOT TRUE DO # las primas no dedicadas


            IF prima_porcen IS TRUE THEN
                set sueldo_total = (sueldo_base / 100  ) * prima_monto;
            ELSE
                set sueldo_total = prima_monto;
            END IF;
            
            
            SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;
            
            -- INSERT INTO detalles_factura 
            --     (id_factura, descripcion, monto, prima, islr) VALUES
            --     (
            --         id_factura_p,
            --         prima_descrip,
            --         sueldo_total,
            --         TRUE,
            --         FALSE
            --     );

            INSERT INTO `factura_primas_generales`
                (`id_primas_generales`, `id_factura`, `monto`) VALUES 
                (id_prima_DD,id_factura_p,sueldo_total);


            
            #SELECT * FROM factura as f JOIN detalles_factura as df on df.id_factura = f.id_factura WHERE f.status is FALSE ORDER BY f.id_factura,df.descripcion;
            
            IF filas_encontradas_2 <= 0 or filas_encontradas_2 = 1 THEN
                SET done = TRUE;
            ELSE
                set filas_encontradas_2 = filas_encontradas_2 - 1;
                FETCH primas_generales_dedicadas INTO id_prima_DD, prima_descrip, prima_monto, prima_porcen;
            END IF;
            
        END WHILE;

    END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_prima_hijo` (IN `id_padre` INT, IN `sueldo` DECIMAL(12,2))  proc_Exit:BEGIN
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
    DECLARE id_prima_hijo_DD int;


    DECLARE lista_hijos_and_primas CURSOR FOR
        SELECT 
        ph.id_prima_hijos,
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
        id_prima_hijo_DD,
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
            

            INSERT INTO `factura_hijos`
            (`id_prima_hijos`, `id_factura`, `monto`) 
            VALUES (
                id_prima_hijo_DD,
                id_factura_p,
                sueldo_total
                );

            
      

#           SELECT "hola";

        END IF;

    END LOOP;

    CLOSE lista_hijos_and_primas;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_profesionalismo` (IN `idTrabajador` INT)  BEGIN
	# idTrabajador

	DECLARE prima_descrip VARCHAR(150);
	DECLARE prima_porcent DECIMAL(12,2);
	DECLARE id_factura_p INT;
	DECLARE prima_profesionalismo_id_DD int;

	SELECT
		pp.id_prima_profesionalismo
	    ,CONCAT('Profesionalización - ',PP.descripcion) AS descriptcion
	    ,pp.incremento
	    INTO
	    prima_profesionalismo_id_DD
	    ,prima_descrip
	    ,prima_porcent
	FROM
	    trabajadores AS t
	LEFT JOIN prima_profesionalismo AS pp
	ON pp.id_prima_profesionalismo = t.id_prima_profesionalismo
	WHERE
	    t.id_trabajador = idTrabajador;


	IF prima_porcent > 0 THEN

		SELECT id_factura INTO id_factura_p FROM factura WHERE factura.status IS FALSE AND factura.id_trabajador = idTrabajador LIMIT 1;
    
        -- INSERT INTO detalles_factura 
        -- (id_factura, descripcion, monto, prima, islr) VALUES
        -- (
        --     id_factura_p,
        --     prima_descrip,
        --     f_profesionalismo(idTrabajador),
        --     TRUE,
        --     FALSE
        -- );

        INSERT INTO `factura_profesionalismo`
        (`id_profesionalismo`, `id_factura`, `monto`) 
        VALUES 
        (prima_profesionalismo_id_DD, id_factura_p,f_profesionalismo(idTrabajador));




	END IF;
END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `f_antiguedad` (`idTrabajador` INT) RETURNS DECIMAL(12,2) BEGIN
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

CREATE DEFINER=`root`@`localhost` FUNCTION `f_antiguedad_ID` (`idTrabajador` INT) RETURNS INT(11) NO SQL
BEGIN
    DECLARE trabajador_antiguedad int DEFAULT 0;
    DECLARE sueldo_base decimal(12,2);
    DECLARE resultado decimal(12,2) DEFAULT 0;
    DECLARE prima_monto decimal(5,2) DEFAULT 0;


    SELECT TIMESTAMPDIFF(YEAR, t.creado ,CURRENT_DATE), sb.sueldo_base INTO trabajador_antiguedad, sueldo_base  FROM trabajadores as t JOIN sueldo_base as sb on sb.id_trabajador = t.id_trabajador WHERE t.id_trabajador = idTrabajador;

        SELECT
            a.id_prima_antiguedad
            
            INTO
            resultado
        FROM
            prima_antiguedad AS a
        WHERE
            a.anios_antiguedad <= trabajador_antiguedad
        ORDER BY
            a.anios_antiguedad
        DESC
        LIMIT 1;


    

    RETURN resultado;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `f_compensacion_eval` (`idTrabajador` INT) RETURNS DECIMAL(12,2) BEGIN
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

CREATE DEFINER=`root`@`localhost` FUNCTION `f_contar_lunes` (`fecha_lunes` DATE, `quincena` INT) RETURNS INT(11) NO SQL
BEGIN

    #fecha_lunes date
    #quincena int => 3 = 30 dias, 1 = primeros 15 dias, 2 siguientes 15 dias

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

CREATE DEFINER=`root`@`localhost` FUNCTION `f_dedicacionSectorSalud` (`idTrabajador` INT) RETURNS DECIMAL(12,2) BEGIN
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

CREATE DEFINER=`root`@`localhost` FUNCTION `f_escalafon` (`idTrabajador` INT) RETURNS DECIMAL(12,2) BEGIN
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

CREATE DEFINER=`root`@`localhost` FUNCTION `f_profesionalismo` (`idTrabajador` INT) RETURNS DECIMAL(12,2) BEGIN

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL,
  `id_trabajador_area` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_trabajador` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(2, '2024-07-08 16:19:53', 'Inicio de sesión'),
(2, '2024-07-08 16:19:53', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-08 16:43:38', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-08 16:45:57', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-09 20:21:17', 'Ingreso en el modulo (2)'),
(2, '2024-07-09 20:24:11', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 20:35:03', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 20:35:56', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 20:36:06', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-09 20:38:51', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-09 20:38:57', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-09 20:41:23', 'Ingreso en el modulo (2)'),
(2, '2024-07-09 20:52:01', 'Ingreso en el modulo (2)'),
(2, '2024-07-09 20:52:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-09 20:55:56', 'Ingreso en el modulo (2)'),
(2, '2024-07-09 20:56:28', 'Ingreso en el modulo (2)'),
(2, '2024-07-09 20:56:32', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-09 20:56:37', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-09 20:56:52', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 20:58:02', 'Ingreso en el modulo (2)'),
(2, '2024-07-09 21:18:05', 'Ingreso en el modulo (2)'),
(2, '2024-07-09 21:18:09', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 21:18:20', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-09 21:18:36', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:21:45', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-09 21:46:23', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:46:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:46:56', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 21:50:52', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 22:05:23', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-09 22:11:15', 'Inicio de sesión'),
(2, '2024-07-09 22:11:16', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:11:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:14:19', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:15:22', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:15:45', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:16:27', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:19:08', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-07-09 22:28:42', 'Ingreso en el modulo (Asistencias)'),
(2, '2024-07-09 22:33:26', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:38:16', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:46:33', 'Inicio de sesión'),
(2, '2024-07-09 22:46:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:51:58', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:53:46', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:54:23', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-09 22:59:31', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-09 23:11:05', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-09 23:11:12', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-09 23:39:34', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-09 23:41:03', 'Asigno el sueldo del trabajador V-2725054'),
(2, '2024-07-09 23:50:53', 'Borro el sueldo del trabajador V-2725054'),
(2, '2024-07-09 23:56:23', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-10 02:09:09', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-11 16:56:03', 'Inicio de sesión'),
(2, '2024-07-11 16:56:03', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-11 16:56:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:09:17', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:09:37', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:12:43', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:13:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:13:51', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:15:32', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:15:41', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:16:07', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:16:16', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:20:40', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:20:43', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:27:50', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:29:14', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:31:48', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:32:11', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:33:21', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:33:34', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:34:34', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:40:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:43:30', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:45:10', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:46:17', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:46:41', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:46:42', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:46:49', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 17:47:28', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:48:24', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:49:06', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:56:37', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 17:58:27', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-11 18:10:25', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 18:21:43', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 18:22:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 18:23:04', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 18:23:40', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 18:24:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 21:52:25', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 21:53:48', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 21:54:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:17:46', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:18:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:22:42', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:23:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:28:11', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:29:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:31:37', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:32:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:35:20', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 22:40:37', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:01:51', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:03:06', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:07:11', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:08:19', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:08:57', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:09:26', 'Registro la prima por antigüedad de  (25) año'),
(2, '2024-07-11 23:11:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:15:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:15:57', 'Modificó la prima por antigüedad de  (26) año'),
(2, '2024-07-11 23:18:56', 'Modificó la prima por antigüedad de  (26) año'),
(2, '2024-07-11 23:19:36', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:19:42', 'Elimino la prima por antigüedad de 26 año(s)'),
(2, '2024-07-11 23:55:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:55:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:55:35', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:56:30', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-11 23:56:43', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:07:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:09:19', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:10:40', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:19:21', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:19:45', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:50:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:50:49', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:51:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:54:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:54:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:55:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 02:55:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 03:16:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 03:17:07', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 03:17:17', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 03:45:43', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 03:58:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 04:00:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 04:03:19', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 04:04:01', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 04:04:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-12 04:07:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 01:37:24', 'Inicio de sesión'),
(2, '2024-07-13 01:37:24', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-13 01:37:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 01:38:49', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:07:28', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:07:45', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:13:52', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:30:53', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:31:51', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:36:26', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:36:57', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:37:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:39:38', 'Registro la prima por escalafón de escala  (X'),
(2, '2024-07-13 02:51:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:59:25', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 02:59:55', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:06:02', 'Elimino la prima por antigüedad de 17 año(s)'),
(2, '2024-07-13 03:07:11', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:33:23', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:33:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:34:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:35:01', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:37:06', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:37:36', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:37:59', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:38:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:39:50', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:40:51', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:41:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:41:50', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:43:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:43:20', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:43:53', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:45:16', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:48:30', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:48:55', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:49:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:52:57', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:53:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:54:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:55:50', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 03:56:16', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:00:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:03:02', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:03:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:04:06', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:04:42', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:05:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:07:10', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:08:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:08:11', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:09:09', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:09:34', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:10:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:10:58', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:12:16', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:13:13', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:13:37', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:14:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:17:59', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:18:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:19:16', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:29:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:29:35', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:30:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:31:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:34:46', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:35:25', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:35:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:47:43', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:48:13', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:49:03', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 04:50:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:03:46', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:05:36', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:08:09', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:10:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:15:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:16:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:19:06', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:20:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:21:07', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:24:01', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:26:13', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:27:20', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:33:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:37:57', 'Ingreso en el modulo (Primas)');
INSERT INTO `bitacora` (`id_trabajador`, `fecha`, `descripcion`) VALUES
(2, '2024-07-13 05:38:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:39:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:41:45', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:41:58', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:43:25', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:43:34', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:44:40', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:46:55', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:47:48', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:49:30', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:51:28', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:55:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:56:03', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 05:56:34', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:06:04', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:06:35', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:13:48', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:20:39', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:20:44', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-13 06:21:52', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-13 06:22:42', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-13 06:22:55', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-13 06:29:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:30:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:38:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:40:31', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:40:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:41:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 06:41:35', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-13 06:41:39', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:24:17', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:24:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:26:17', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:27:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:29:43', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:32:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:34:20', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:35:08', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:35:45', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:38:08', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:41:35', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:44:08', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:51:03', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:51:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:51:50', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:53:07', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:53:09', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:54:31', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:55:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:56:08', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-13 07:58:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 09:18:04', 'Inicio de sesión'),
(2, '2024-07-15 09:18:05', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-15 09:18:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:43:08', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:43:16', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:44:55', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:45:19', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:47:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:50:23', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:51:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:53:11', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:55:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:57:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:58:07', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:59:02', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 10:59:30', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 11:00:39', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:06:00', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:08:37', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:09:28', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:18:29', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:19:40', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:20:00', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:20:43', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:20:48', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:20:49', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:20:50', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:20:53', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:20:55', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:20:57', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:20:58', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:20:59', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:21:00', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:21:01', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:21:05', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:21:11', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:21:11', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:21:13', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:23:32', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:23:42', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:23:44', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:23:46', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:24', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:24:31', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:31', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:32', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:33', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:38', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:39', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:40', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:43', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:55', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:56', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:57', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:24:57', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:41:24', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:41:44', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:42:11', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:44:10', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:44:34', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:45:25', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:50:39', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 11:54:39', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:54:41', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:54:42', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:06', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:07', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:08', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:09', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:09', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:10', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:11', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:11', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:12', 'cambio los permiso de un rol'),
(2, '2024-07-15 11:56:54', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:07:13', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:07:36', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:08:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:09:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:09:51', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:28:49', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:30:31', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:31:04', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:31:48', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:38:10', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:38:34', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:41:35', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:42:06', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:51:17', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:52:15', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:52:30', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:53:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:53:24', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:56:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 12:57:21', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:01:50', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:03:02', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:10:55', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:28:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:29:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:36:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:37:06', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:37:15', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:37:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:38:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:38:25', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:39:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:40:59', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:42:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:43:21', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:43:49', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:44:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:46:10', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:51:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:54:29', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:55:54', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 13:58:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:00:48', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:38:42', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:39:22', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:41:31', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:42:16', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:43:55', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:49:21', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:51:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:57:17', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:57:20', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 14:59:21', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:00:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:01:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:03:04', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:06:08', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:08:26', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:09:05', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:18:26', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:31:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:31:51', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:34:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:34:45', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:36:33', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:37:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:41:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:42:07', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:44:50', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:45:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:46:46', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 15:48:52', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:03:53', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:06:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:07:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:08:54', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:24:12', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-15 16:24:28', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-15 16:24:50', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:51', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:51', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:52', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:52', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:53', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:54', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:55', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:56', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:57', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:57', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:24:58', 'cambio los permiso de un rol'),
(2, '2024-07-15 16:25:05', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-15 16:25:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-15 16:25:40', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:26:39', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:44:03', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:55:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 16:56:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:08:50', 'Elimino la prima por antigüedad de 1 año(s)'),
(2, '2024-07-15 17:08:52', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:09:17', 'Registro la prima por antigüedad de  (1) año('),
(2, '2024-07-15 17:10:40', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:24:44', 'Registro la prima general (servicio completo)'),
(2, '2024-07-15 17:25:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:32:09', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:33:27', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:46:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:47:18', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:47:45', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:48:43', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:53:44', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 17:55:02', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 18:04:01', 'Registro la prima general (17)'),
(2, '2024-07-15 18:05:57', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 18:06:28', 'Eliminó la prima general (17)'),
(2, '2024-07-15 18:06:39', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 18:10:38', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 18:11:35', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 18:12:00', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-15 18:12:39', 'Eliminó la prima general (servicio completo)'),
(2, '2024-07-15 18:12:42', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-16 19:49:30', 'Inicio de sesión'),
(2, '2024-07-16 19:49:30', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-16 19:52:02', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-16 19:56:53', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-16 20:03:14', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-16 20:08:50', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-16 20:40:26', 'Ingreso en el modulo (2)'),
(2, '2024-07-16 20:42:22', 'Ingreso en el modulo (2)'),
(2, '2024-07-17 00:10:32', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:16:02', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:17:32', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:17:51', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:19:37', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:20:13', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:20:42', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:46:21', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-17 00:53:41', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-17 00:53:48', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:54:15', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:54:27', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:55:23', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:56:26', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:57:58', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:58:54', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 00:59:29', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:00:09', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:01:01', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:01:49', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:03:04', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:04:33', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:05:10', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:05:15', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-17 01:05:47', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-17 01:05:50', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:06:29', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:07:21', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:08:02', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:25:35', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:27:18', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:28:17', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:30:39', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:33:24', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:36:53', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:37:34', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:38:35', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:39:18', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:55:54', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:57:01', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 01:58:51', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:00:20', 'Inicio de sesión'),
(2, '2024-07-17 02:00:20', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:00:28', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:01:28', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:02:08', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:02:55', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:03:54', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:04:45', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:06:03', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:08:12', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:10:06', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:30:35', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:31:00', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:31:25', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:31:57', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-17 02:33:26', 'Inicio de sesión'),
(2, '2024-07-17 02:33:26', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:33:32', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-17 02:45:32', 'Inicio de sesión'),
(2, '2024-07-17 02:45:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:46:00', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:46:25', 'Inicio de sesión'),
(2, '2024-07-17 02:46:25', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:48:01', 'Inicio de sesión'),
(2, '2024-07-17 02:48:02', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:48:08', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:49:21', 'Inicio de sesión'),
(2, '2024-07-17 02:49:21', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:50:25', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:50:46', 'Inicio de sesión'),
(2, '2024-07-17 02:50:47', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:52:06', 'Inicio de sesión'),
(2, '2024-07-17 02:52:06', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 02:52:13', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 02:54:47', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:00:41', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:02:22', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:06:55', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:08:08', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:19:23', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:20:05', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:28:04', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:42:31', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:43:58', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:45:13', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-17 03:45:36', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:45:38', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:45:42', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:46:00', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:46:10', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:47:02', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:47:23', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:47:29', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:48:14', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:48:29', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:52:19', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 03:52:21', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-17 03:52:39', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:01:10', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:04:56', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:05:04', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:06:37', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:07:02', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:09:56', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:11:02', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:12:38', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:15:26', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:21:17', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 04:22:07', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:18:42', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:19:48', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:24:34', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:24:56', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:25:14', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:34:18', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:34:43', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:37:14', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:38:12', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:41:32', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:42:05', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:42:08', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:43:31', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:45:11', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:45:16', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:47:25', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:49:14', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:50:22', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:50:37', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:50:41', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:50:55', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:51:31', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:51:58', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:53:33', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:53:45', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 05:54:33', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:03:29', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:05:41', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:10:07', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:40:24', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:40:46', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:42:02', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:44:00', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:45:05', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:49:03', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 06:53:11', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 07:07:20', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 07:18:24', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 07:26:35', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 07:27:48', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 16:15:01', 'Inicio de sesión'),
(2, '2024-07-17 16:15:02', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 16:15:34', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-17 16:46:06', 'Ingreso en el modulo (Deducciones)'),
(2, '2024-07-17 17:00:02', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-17 17:00:41', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-17 17:02:09', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-17 22:28:09', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:34:27', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-17 22:34:34', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 22:54:22', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-17 22:58:50', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-17 23:01:56', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:02:00', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-17 23:02:08', 'Registro de nuevo rol (trabajador)'),
(2, '2024-07-17 23:02:17', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-17 23:05:33', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-17 23:05:43', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-17 23:05:53', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:05:54', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:05:54', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:05:55', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:06:00', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:06:00', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:06:03', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:06:03', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:06:04', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:06:04', 'cambio los permiso de un rol'),
(2, '2024-07-17 23:08:13', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-17 23:43:51', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:47:16', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:47:45', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:48:30', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:50:25', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:50:50', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:51:02', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:53:03', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:55:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-17 23:57:34', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:01:08', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:01:59', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:02:28', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:02:58', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:03:31', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:03:55', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:05:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:06:00', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:14:38', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:46:22', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:46:48', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:47:10', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:48:06', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:48:42', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:49:12', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 00:49:58', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:01:05', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:06:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:07:17', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:07:46', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:09:04', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:09:46', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:10:31', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:11:20', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:13:57', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:16:11', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:16:49', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:19:19', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:20:33', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:20:41', 'Registró el nivel educativo (prueba)'),
(2, '2024-07-18 01:20:47', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:20:50', 'Eliminó el nivel educativo (prueba)'),
(2, '2024-07-18 01:22:24', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 01:22:33', 'Modificó el nivel educativo (prueva)'),
(2, '2024-07-18 01:22:52', 'Registró el nivel educativo (Bachiller)'),
(2, '2024-07-18 01:28:39', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 03:34:03', 'Ingreso en el modulo (Nivel Educativo)'),
(2, '2024-07-18 03:34:52', 'Ingreso en el modulo (Nivel Educativo)'),
(2, '2024-07-18 03:37:04', 'Ingreso en el modulo (Nivel Educativo)'),
(2, '2024-07-18 03:37:38', 'Ingreso en el modulo (Nivel Educativo)'),
(2, '2024-07-18 03:38:00', 'Ingreso en el modulo (Nivel Educativo)'),
(2, '2024-07-18 03:38:16', 'Ingreso en el modulo (Nivel Educativo)'),
(2, '2024-07-18 03:38:32', 'Ingreso en el modulo (2)'),
(2, '2024-07-18 03:41:40', 'Ingreso en el modulo (2)'),
(2, '2024-07-18 03:41:47', 'Ingreso en el modulo (2)'),
(2, '2024-07-18 04:30:03', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-18 04:30:10', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-18 04:30:22', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 04:43:14', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 04:43:49', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 04:44:24', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 04:53:19', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 04:53:30', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 04:53:49', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 05:27:39', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 05:28:05', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 05:28:58', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 05:30:19', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 05:30:52', 'cambio los permiso de un rol'),
(2, '2024-07-18 05:30:53', 'cambio los permiso de un rol'),
(2, '2024-07-18 05:30:54', 'cambio los permiso de un rol'),
(2, '2024-07-18 05:30:54', 'cambio los permiso de un rol'),
(2, '2024-07-18 05:31:42', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 05:32:14', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 05:36:55', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 05:41:48', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 05:43:27', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 05:48:45', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 05:49:06', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 05:52:00', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 05:52:39', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 06:08:56', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 06:09:41', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 06:10:11', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 06:10:39', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 07:54:53', 'Inicio de sesión'),
(2, '2024-07-18 07:54:54', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 07:54:58', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 07:55:03', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 07:55:42', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 07:56:00', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:00:51', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:12:27', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:15:33', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:22:41', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:27:08', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:29:18', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:29:53', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:30:18', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:32:48', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:35:25', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:36:25', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:37:46', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:45:11', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:48:50', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:49:23', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:50:19', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:51:45', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 08:53:33', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:00:01', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:02:44', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:08:08', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:09:05', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:11:00', 'Ingreso en el modulo (2)'),
(2, '2024-07-18 09:13:45', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:29:22', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:29:59', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:32:50', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:36:43', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:37:07', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 09:37:52', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:09:03', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:09:24', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:14:44', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:18:59', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:20:58', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:24:16', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:26:18', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:29:51', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 10:30:15', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 10:54:56', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:56:27', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:57:08', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 10:58:56', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:22:04', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:28:43', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:29:46', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:30:21', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:32:30', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:34:20', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:35:19', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:36:02', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:36:42', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 12:37:43', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 12:38:07', 'Modifico al usuario (V-12434091)'),
(2, '2024-07-18 12:38:48', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-18 13:37:24', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 13:38:42', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-18 13:39:07', 'cambio los permiso de un rol'),
(2, '2024-07-18 13:39:07', 'cambio los permiso de un rol'),
(2, '2024-07-18 13:39:08', 'cambio los permiso de un rol'),
(2, '2024-07-18 13:39:09', 'cambio los permiso de un rol'),
(2, '2024-07-18 13:39:33', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 13:39:49', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 13:40:24', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 13:41:55', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:08:12', 'Ingreso en el modulo (Primas)'),
(2, '2024-07-18 14:11:15', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:11:44', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:23:19', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:23:54', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:26:41', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:27:15', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:27:33', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:27:55', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 14:29:04', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:06:04', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:09:22', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:12:30', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:13:20', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:13:57', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:35:28', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:36:25', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:36:36', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:37:23', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:37:52', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:38:47', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:45:22', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:45:49', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:47:27', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 15:48:20', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 16:15:19', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:17:32', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:17:47', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:19:25', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:19:50', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:20:29', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:20:59', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:22:52', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:27:23', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:28:42', 'Registro al usuarios (V-27250512)'),
(2, '2024-07-18 16:29:46', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:30:08', 'Elimino al usuario (V-2725054)'),
(2, '2024-07-18 16:32:15', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:32:39', 'Elimino al usuario (V-12434091)'),
(2, '2024-07-18 16:32:46', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:32:53', 'Elimino al usuario (V-27250512)'),
(2, '2024-07-18 16:35:14', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:37:54', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-18 16:48:02', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 16:48:13', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 16:49:11', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 16:49:49', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 16:50:12', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 16:50:42', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:24:08', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:24:35', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:24:55', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:25:31', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:26:09', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:26:33', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:26:54', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:33:03', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:34:19', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:34:44', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:35:19', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:35:55', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:36:13', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:36:49', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:37:04', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:38:01', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:38:19', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:38:57', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:40:04', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:41:47', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:55:23', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 17:55:52', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:02:52', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:03:20', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:05:23', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-18 18:19:11', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:24:25', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:25:02', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:26:03', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:29:03', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:32:34', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:34:22', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:34:59', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:36:46', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:37:28', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:37:53', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:38:32', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:40:09', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:41:09', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:41:47', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 18:42:42', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 19:42:45', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 19:44:41', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 20:27:21', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 20:30:59', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:00:07', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:04:09', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:05:07', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:05:36', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:06:34', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:07:21', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:08:38', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:16:12', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:25:28', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:25:41', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:32:25', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:40:25', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:40:59', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:42:03', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:43:24', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:44:29', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:49:07', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:52:45', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:53:41', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:56:13', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:57:19', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 21:58:11', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 22:01:22', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 22:25:05', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 23:50:19', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-18 23:50:29', 'Ingreso en el modulo (Nivel Educativo)'),
(2, '2024-07-18 23:58:40', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 00:10:46', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-19 00:44:21', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 00:52:49', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 02:11:42', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 02:11:51', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 02:13:16', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 02:13:29', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 02:15:14', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 02:15:38', 'Inicio de sesión'),
(2, '2024-07-19 02:16:32', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:16:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:16:54', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 02:49:46', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 02:51:22', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 03:25:08', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-19 03:25:18', 'Inicio de sesión'),
(2, '2024-07-19 03:25:22', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:26:04', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:26:14', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:27:53', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:27:53', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:37:12', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:55:43', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:57:01', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 03:58:04', 'Ingreso en el modulo (2)'),
(2, '2024-07-19 03:58:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 04:21:39', 'Inicio de sesión'),
(2, '2024-07-19 04:21:40', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:15:31', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:15:55', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:16:07', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 05:36:29', 'Inicio de sesión'),
(2, '2024-07-19 05:36:30', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 07:20:28', 'Inicio de sesión'),
(2, '2024-07-19 07:20:32', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 07:21:04', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 07:21:05', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 07:24:08', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 07:25:38', 'Ingreso en el modulo (Areas)'),
(2, '2024-07-19 07:27:45', 'Ingreso en el modulo (Hijos)'),
(2, '2024-07-19 23:26:46', 'Inicio de sesión'),
(2, '2024-07-19 23:26:49', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 23:31:20', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-19 23:31:25', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-19 23:31:37', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-19 23:31:55', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:38:23', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:39:12', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:39:44', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:40:01', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:40:43', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:41:23', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:41:51', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:42:00', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:51:48', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:53:09', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:53:33', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-19 23:55:11', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-20 00:01:16', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-20 00:06:41', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-20 00:10:23', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-20 00:18:29', 'Ingreso en el modulo (Roles)'),
(2, '2024-07-20 00:19:02', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-20 00:28:37', 'Ingreso en el modulo (Permisos)'),
(2, '2024-07-20 04:08:51', 'Inicio de sesión'),
(2, '2024-07-20 04:08:57', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 04:09:14', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:04:00', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:10:29', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:12:05', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:14:24', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:17:56', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:18:28', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:20:19', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:21:01', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:21:30', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:22:35', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:23:07', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:23:41', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:24:47', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:26:19', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:27:50', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:28:40', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:30:15', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:31:37', 'Ingreso en el modulo (Gestionar Facturas)'),
(2, '2024-07-20 05:33:55', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 05:34:49', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 05:36:21', 'Inicio de sesión'),
(2, '2024-07-20 05:36:24', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 05:37:20', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 05:48:09', 'Ingreso en el modulo (Bitacora)');
INSERT INTO `bitacora` (`id_trabajador`, `fecha`, `descripcion`) VALUES
(2, '2024-07-20 05:48:10', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 06:19:19', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 06:19:20', 'Ingreso en el modulo (Bitacora)'),
(2, '2024-07-20 06:19:21', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 06:27:28', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 06:30:34', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:28:17', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:44:42', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:47:54', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:49:24', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:50:25', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:50:45', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:50:50', 'La liquidacion Nº1 fue eliminada'),
(2, '2024-07-20 07:50:54', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:52:58', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:53:39', 'Ingreso en el modulo (Liquidación)'),
(2, '2024-07-20 07:54:36', 'Registró la liquidación con el Nº2 '),
(2, '2024-07-20 07:54:50', 'La liquidacion Nº2 fue eliminada'),
(2, '2024-07-20 07:55:41', 'Ingreso en el modulo (Usuarios)'),
(2, '2024-07-20 07:56:37', 'Registro al usuarios (V-15447800)'),
(2, '2024-07-20 07:57:01', 'Ingreso en el modulo (Bitácora)'),
(2, '2024-07-20 07:57:24', 'Asigno el sueldo del trabajador V-15447800');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario`
--

CREATE TABLE `calendario` (
  `id` int(5) NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
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
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id_cargo` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `tipo_nomina` enum('Alto Nivel','Contratado','Comision de Servicios','Obrero Fijo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `deducciones`
--

INSERT INTO `deducciones` (`id_deducciones`, `descripcion`, `monto`, `porcentaje`, `multi_meses`, `div_sem`, `quincena`, `multi_dia`, `sector_salud`, `islr`, `dedicada`) VALUES
(1, 'Perdida involuntaria de empleo', '0.50', 1, 12, 52, 1, 1, 0, 0, 0),
(2, 'prueva dedicadas', '125.00', 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_factura`
--

CREATE TABLE `detalles_factura` (
  `id_detalles` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `prima` tinyint(1) NOT NULL,
  `islr` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalles_factura`
--

INSERT INTO `detalles_factura` (`id_detalles`, `id_factura`, `descripcion`, `monto`, `prima`, `islr`) VALUES
(1, 30, 'prima hijos 2', '21.00', 1, 0),
(2, 30, 'prima hijos 4', '23.00', 1, 0),
(3, 30, 'prima hijos 1', '20.00', 1, 0),
(4, 30, 'prima hijos 2', '21.00', 1, 0),
(5, 30, 'prima hijos 3', '22.00', 1, 0),
(6, 30, 'prima hijos 4', '23.00', 1, 0),
(7, 30, 'Escalafon - escala I', '7.01', 1, 0),
(8, 30, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '15.00', 1, 0),
(9, 30, 'Compensación Por Evaluación', '37.50', 1, 0),
(10, 30, 'prueba dedicada', '22.50', 1, 0),
(11, 30, 'dia del padre', '12.50', 1, 0),
(12, 30, 'Profesionalización - Técnico Superior Universitario', '43.58', 1, 0),
(13, 30, 'Antiguedad', '8.38', 1, 0),
(14, 30, 'Perdida involuntaria de empleo', '1.96', 0, 0),
(15, 30, 'prueva dedicadas', '125.00', 0, 0),
(16, 31, 'Escalafon - escala VI', '70.00', 1, 0),
(17, 31, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '25.00', 1, 0),
(18, 31, 'Compensación Por Evaluación', '62.50', 1, 0),
(19, 31, 'Profesionalización - Doctorado', '163.00', 1, 0),
(20, 31, 'Perdida involuntaria de empleo', '2.64', 0, 0),
(21, 32, 'prima hijos 2', '21.00', 1, 0),
(22, 32, 'prima hijos 4', '23.00', 1, 0),
(23, 32, 'prima hijos 1', '20.00', 1, 0),
(24, 32, 'prima hijos 2', '21.00', 1, 0),
(25, 32, 'prima hijos 3', '22.00', 1, 0),
(26, 32, 'prima hijos 4', '23.00', 1, 0),
(27, 32, 'Escalafon - escala I', '7.01', 1, 0),
(28, 32, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '15.00', 1, 0),
(29, 32, 'Compensación Por Evaluación', '37.50', 1, 0),
(30, 32, 'prueba dedicada', '22.50', 1, 0),
(31, 32, 'dia del padre', '12.50', 1, 0),
(32, 32, 'Profesionalización - Técnico Superior Universitario', '43.58', 1, 0),
(33, 32, 'Antiguedad', '8.38', 1, 0),
(34, 32, 'Perdida involuntaria de empleo', '1.96', 0, 0),
(35, 32, 'prueva dedicadas', '125.00', 0, 0),
(36, 33, 'Escalafon - escala VI', '70.00', 1, 0),
(37, 33, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '25.00', 1, 0),
(38, 33, 'Compensación Por Evaluación', '62.50', 1, 0),
(39, 33, 'Profesionalización - Doctorado', '163.00', 1, 0),
(40, 33, 'Perdida involuntaria de empleo', '2.64', 0, 0),
(41, 34, 'prima hijos 2', '21.00', 1, 0),
(42, 34, 'prima hijos 4', '23.00', 1, 0),
(43, 34, 'prima hijos 1', '20.00', 1, 0),
(44, 34, 'prima hijos 2', '21.00', 1, 0),
(45, 34, 'prima hijos 3', '22.00', 1, 0),
(46, 34, 'prima hijos 4', '23.00', 1, 0),
(47, 34, 'Escalafon - escala I', '7.01', 1, 0),
(48, 34, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '15.00', 1, 0),
(49, 34, 'Compensación Por Evaluación', '37.50', 1, 0),
(50, 34, 'prueba dedicada', '22.50', 1, 0),
(51, 34, 'dia del padre', '12.50', 1, 0),
(52, 34, 'Profesionalización - Técnico Superior Universitario', '43.58', 1, 0),
(53, 34, 'Antiguedad', '8.38', 1, 0),
(54, 34, 'Perdida involuntaria de empleo', '2.45', 0, 0),
(55, 34, 'prueva dedicadas', '125.00', 0, 0),
(56, 35, 'Escalafon - escala VI', '70.00', 1, 0),
(57, 35, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '25.00', 1, 0),
(58, 35, 'Compensación Por Evaluación', '62.50', 1, 0),
(59, 35, 'Profesionalización - Doctorado', '163.00', 1, 0),
(60, 35, 'Perdida involuntaria de empleo', '3.30', 0, 0),
(61, 36, 'prima hijos 2', '21.00', 1, 0),
(62, 36, 'prima hijos 4', '23.00', 1, 0),
(63, 36, 'prima hijos 1', '20.00', 1, 0),
(64, 36, 'prima hijos 2', '21.00', 1, 0),
(65, 36, 'prima hijos 3', '22.00', 1, 0),
(66, 36, 'prima hijos 4', '23.00', 1, 0),
(67, 36, 'Escalafon - escala I', '7.01', 1, 0),
(68, 36, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '15.00', 1, 0),
(69, 36, 'Compensación Por Evaluación', '37.50', 1, 0),
(70, 36, 'prueba dedicada', '22.50', 1, 0),
(71, 36, 'dia del padre', '12.50', 1, 0),
(72, 36, 'Profesionalización - Técnico Superior Universitario', '43.58', 1, 0),
(73, 36, 'Antiguedad', '8.38', 1, 0),
(74, 36, 'Perdida involuntaria de empleo', '1.96', 0, 0),
(75, 36, 'prueva dedicadas', '125.00', 0, 0),
(76, 37, 'Escalafon - escala VI', '70.00', 1, 0),
(77, 37, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '25.00', 1, 0),
(78, 37, 'Compensación Por Evaluación', '62.50', 1, 0),
(79, 37, 'Profesionalización - Doctorado', '163.00', 1, 0),
(80, 37, 'Perdida involuntaria de empleo', '2.64', 0, 0),
(81, 38, 'prima hijos 2', '21.00', 1, 0),
(82, 38, 'prima hijos 4', '23.00', 1, 0),
(83, 38, 'prima hijos 1', '20.00', 1, 0),
(84, 38, 'prima hijos 2', '21.00', 1, 0),
(85, 38, 'prima hijos 3', '22.00', 1, 0),
(86, 38, 'prima hijos 4', '23.00', 1, 0),
(87, 38, 'Escalafon - escala I', '7.01', 1, 0),
(88, 38, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '15.00', 1, 0),
(89, 38, 'Compensación Por Evaluación', '37.50', 1, 0),
(90, 38, 'prueba dedicada', '22.50', 1, 0),
(91, 38, 'dia del padre', '12.50', 1, 0),
(92, 38, 'Profesionalización - Técnico Superior Universitario', '43.58', 1, 0),
(93, 38, 'Antiguedad', '8.38', 1, 0),
(94, 38, 'Perdida involuntaria de empleo', '1.96', 0, 0),
(95, 38, 'prueva dedicadas', '125.00', 0, 0),
(96, 39, 'Escalafon - escala VI', '70.00', 1, 0),
(97, 39, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '25.00', 1, 0),
(98, 39, 'Compensación Por Evaluación', '62.50', 1, 0),
(99, 39, 'Profesionalización - Doctorado', '163.00', 1, 0),
(100, 39, 'Perdida involuntaria de empleo', '2.64', 0, 0),
(101, 40, 'prima hijos 2', '21.00', 1, 0),
(102, 40, 'prima hijos 4', '23.00', 1, 0),
(103, 40, 'prima hijos 1', '20.00', 1, 0),
(104, 40, 'prima hijos 2', '21.00', 1, 0),
(105, 40, 'prima hijos 3', '22.00', 1, 0),
(106, 40, 'prima hijos 4', '23.00', 1, 0),
(107, 40, 'Escalafon - escala I', '7.01', 1, 0),
(108, 40, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '15.00', 1, 0),
(109, 40, 'Compensación Por Evaluación', '37.50', 1, 0),
(110, 40, 'prueba dedicada', '22.50', 1, 0),
(111, 40, 'dia del padre', '12.50', 1, 0),
(112, 40, 'Profesionalización - Técnico Superior Universitario', '43.58', 1, 0),
(113, 40, 'Antiguedad', '8.38', 1, 0),
(114, 40, 'Perdida involuntaria de empleo', '2.45', 0, 0),
(115, 40, 'prueva dedicadas', '125.00', 0, 0),
(116, 41, 'Escalafon - escala VI', '70.00', 1, 0),
(117, 41, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '25.00', 1, 0),
(118, 41, 'Compensación Por Evaluación', '62.50', 1, 0),
(119, 41, 'Profesionalización - Doctorado', '163.00', 1, 0),
(120, 41, 'Perdida involuntaria de empleo', '3.30', 0, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `escalafon`
--

INSERT INTO `escalafon` (`id_escalafon`, `anios_servicio`, `escala`, `monto`) VALUES
(1, '1 - 2', 'I', '4.67'),
(2, '3 - 5', 'II', '9.33'),
(3, '6 - 8', 'III', '14.00'),
(4, '9 - 11', 'IV', '18.66'),
(5, '12 - 14', 'V', '23.33'),
(6, '15 - 17', 'VI', '28.00'),
(7, '18 - 20', 'VII', '32.66'),
(8, '21 - 23', 'VIII', '37.33'),
(9, '24 - 26', 'IX', '42.00'),
(10, '27 - 29', 'X', '46.66'),
(11, '30 - 32', 'XI', '51.33'),
(12, '33 En Adelante', 'XII', '55.99'),
(17, '1 - 3', 'XIV', '10.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `sueldo_base` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sueldo_integral` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sueldo_deducido` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `id_trabajador`, `fecha`, `sueldo_base`, `sueldo_integral`, `sueldo_deducido`, `status`) VALUES
(30, 2, '2024-02-29', '150.00', '276.47', '126.96', 1),
(31, 4, '2024-02-29', '250.00', '320.50', '2.64', 1),
(32, 2, '2024-03-31', '150.00', '276.47', '126.96', 1),
(33, 4, '2024-03-31', '250.00', '320.50', '2.64', 1),
(34, 2, '2024-04-30', '150.00', '276.47', '127.45', 1),
(35, 4, '2024-04-30', '250.00', '320.50', '3.30', 1),
(36, 2, '2024-05-31', '150.00', '276.47', '126.96', 1),
(37, 4, '2024-05-31', '250.00', '320.50', '2.64', 1),
(38, 2, '2024-06-30', '150.00', '276.47', '126.96', 1),
(39, 4, '2024-06-30', '250.00', '320.50', '2.64', 1),
(40, 2, '2024-07-31', '150.00', '276.47', '127.45', 1),
(41, 4, '2024-07-31', '250.00', '320.50', '3.30', 1),
(75, 2, '2024-07-31', '150.00', '276.47', '127.45', 0),
(76, 4, '2024-07-31', '250.00', '257.51', '2.95', 0);

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
-- Estructura de tabla para la tabla `factura_antiguedad`
--

CREATE TABLE `factura_antiguedad` (
  `id_prima_antiguedad` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura_antiguedad`
--

INSERT INTO `factura_antiguedad` (`id_prima_antiguedad`, `id_factura`, `monto`) VALUES
(4, 75, '8.38');

--
-- Disparadores `factura_antiguedad`
--
DELIMITER $$
CREATE TRIGGER `AI_f_antiguedad` AFTER INSERT ON `factura_antiguedad` FOR EACH ROW BEGIN
   	UPDATE factura
    SET sueldo_integral = sueldo_integral + NEW.monto 
    WHERE factura.id_factura = NEW.id_factura;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_deducciones`
--

CREATE TABLE `factura_deducciones` (
  `id_deduccion` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura_deducciones`
--

INSERT INTO `factura_deducciones` (`id_deduccion`, `id_factura`, `monto`) VALUES
(1, 75, '2.45'),
(1, 76, '2.95'),
(2, 75, '125.00');

--
-- Disparadores `factura_deducciones`
--
DELIMITER $$
CREATE TRIGGER `AI_f_deducciones` AFTER INSERT ON `factura_deducciones` FOR EACH ROW BEGIN
UPDATE factura
    SET sueldo_deducido = sueldo_deducido + NEW.monto 
WHERE factura.id_factura = NEW.id_factura;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_escalafon`
--

CREATE TABLE `factura_escalafon` (
  `id_escalafon` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura_escalafon`
--

INSERT INTO `factura_escalafon` (`id_escalafon`, `id_factura`, `monto`) VALUES
(1, 75, '7.01'),
(1, 76, '7.01');

--
-- Disparadores `factura_escalafon`
--
DELIMITER $$
CREATE TRIGGER `AI_f_escalafon` AFTER INSERT ON `factura_escalafon` FOR EACH ROW BEGIN
UPDATE factura
    SET sueldo_integral = sueldo_integral + NEW.monto 
WHERE factura.id_factura = NEW.id_factura;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_hijos`
--

CREATE TABLE `factura_hijos` (
  `id_factura_hijos` int(11) NOT NULL,
  `id_prima_hijos` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura_hijos`
--

INSERT INTO `factura_hijos` (`id_factura_hijos`, `id_prima_hijos`, `id_factura`, `monto`) VALUES
(55, 2, 75, '21.00'),
(56, 4, 75, '23.00'),
(57, 1, 75, '20.00'),
(58, 2, 75, '21.00'),
(59, 3, 75, '22.00'),
(60, 4, 75, '23.00');

--
-- Disparadores `factura_hijos`
--
DELIMITER $$
CREATE TRIGGER `AI_f_hijos` AFTER INSERT ON `factura_hijos` FOR EACH ROW BEGIN
UPDATE factura
    SET sueldo_integral = sueldo_integral + NEW.monto 
WHERE factura.id_factura = NEW.id_factura;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_primas_generales`
--

CREATE TABLE `factura_primas_generales` (
  `id_primas_generales` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura_primas_generales`
--

INSERT INTO `factura_primas_generales` (`id_primas_generales`, `id_factura`, `monto`) VALUES
(1, 75, '15.00'),
(1, 76, '25.00'),
(2, 75, '37.50'),
(2, 76, '62.50'),
(3, 75, '22.50'),
(4, 75, '12.50');

--
-- Disparadores `factura_primas_generales`
--
DELIMITER $$
CREATE TRIGGER `AI_f_generales` AFTER INSERT ON `factura_primas_generales` FOR EACH ROW BEGIN
UPDATE factura
    SET sueldo_integral = sueldo_integral + NEW.monto 
WHERE factura.id_factura = NEW.id_factura;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_profesionalismo`
--

CREATE TABLE `factura_profesionalismo` (
  `id_profesionalismo` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura_profesionalismo`
--

INSERT INTO `factura_profesionalismo` (`id_profesionalismo`, `id_factura`, `monto`) VALUES
(2, 75, '43.58'),
(6, 76, '163.00');

--
-- Disparadores `factura_profesionalismo`
--
DELIMITER $$
CREATE TRIGGER `AI_f_profesionalismo` BEFORE INSERT ON `factura_profesionalismo` FOR EACH ROW BEGIN
UPDATE factura
    SET sueldo_integral = sueldo_integral + NEW.monto 
WHERE factura.id_factura = NEW.id_factura;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulos` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulos`, `nombre`, `descripcion`) VALUES
(1, 'usuarios', 'Gestionar Trabajadores'),
(2, 'areas', 'Gestionar Areas'),
(3, 'permisos', 'Gestionar Permisos'),
(4, 'asistencias', 'Control de asistencias'),
(5, 'hijos', 'Gestionar Hijos'),
(6, 'bitacora', 'no'),
(7, 'roles', 'Gestionar Roles'),
(8, 'sueldo', 'Gestionar Sueldo'),
(9, 'deducciones', 'Gestionar Deducciones'),
(10, 'primas', 'Gestionar Primas'),
(11, 'educacion', 'Gestionar Nivel Educativo'),
(12, 'liquidacion', 'Gestionar Liquidaciones'),
(13, 'facturas', 'Gestionar Facturas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `mensaje` varchar(250) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `crear` tinyint(4) NOT NULL DEFAULT '1',
  `modificar` tinyint(4) NOT NULL DEFAULT '1',
  `eliminar` tinyint(4) NOT NULL DEFAULT '1',
  `consultar` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 10, 1, 1, 1, 1),
(1, 11, 1, 1, 1, 1),
(1, 12, 1, 1, 1, 1),
(1, 13, 1, 1, 1, 1),
(2, 1, 0, 0, 0, 0),
(2, 11, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_trabajador`
--

CREATE TABLE `permisos_trabajador` (
  `id_permisos` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo_de_permiso` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `primas_generales`
--

INSERT INTO `primas_generales` (`id_primas_generales`, `descripcion`, `monto`, `porcentaje`, `sector_salud`, `dedicada`) VALUES
(1, 'Dedicacion A La Actividad Del Sistema Publico Unico Nacional de salud', '10.00', 1, 1, 0),
(2, 'Compensación Por Evaluación', '25.00', 1, 1, 0),
(3, 'prueba dedicada', '15.00', 1, 0, 1),
(4, 'dia del padre', '12.50', 0, 0, 1),
(5, 'dia de la madre', '12.50', 0, 0, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `primas_hijos`
--

INSERT INTO `primas_hijos` (`id_prima_hijos`, `descripcion`, `menor_edad`, `porcentaje`, `monto`, `discapacidad`) VALUES
(1, 'prima hijos 1', 1, 0, '20.00', 1),
(2, 'prima hijos 2', 1, 0, '21.00', 0),
(3, 'prima hijos 3', 0, 0, '22.00', 1),
(4, 'prima hijos 4', 0, 0, '23.00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prima_antiguedad`
--

CREATE TABLE `prima_antiguedad` (
  `id_prima_antiguedad` int(11) NOT NULL,
  `anios_antiguedad` int(11) NOT NULL,
  `monto` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `prima_antiguedad`
--

INSERT INTO `prima_antiguedad` (`id_prima_antiguedad`, `anios_antiguedad`, `monto`) VALUES
(2, 2, '2.00'),
(3, 3, '3.00'),
(4, 4, '4.00'),
(5, 5, '5.00'),
(6, 6, '6.20'),
(7, 7, '7.40'),
(8, 8, '8.60'),
(9, 9, '9.80'),
(10, 10, '11.00'),
(11, 11, '12.40'),
(12, 12, '13.80'),
(13, 13, '15.20'),
(14, 14, '16.60'),
(15, 15, '18.00'),
(16, 16, '19.60'),
(18, 18, '22.80'),
(19, 19, '24.40'),
(20, 20, '26.00'),
(21, 21, '27.80'),
(22, 22, '29.60'),
(23, 23, '30.00'),
(24, 1, '1.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prima_profesionalismo`
--

CREATE TABLE `prima_profesionalismo` (
  `id_prima_profesionalismo` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `incremento` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `prima_profesionalismo`
--

INSERT INTO `prima_profesionalismo` (`id_prima_profesionalismo`, `descripcion`, `incremento`) VALUES
(1, 'prueba', '0.00'),
(2, 'Técnico Superior Universitario', '20.00'),
(3, 'Profesional', '25.00'),
(4, 'Especialista', '30.00'),
(5, 'Maestria', '35.00'),
(6, 'Doctorado', '40.00'),
(12, 'Bachiller', '0.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reposo`
--

CREATE TABLE `reposo` (
  `id_reposo` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo_reposo` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `descripcion`) VALUES
(1, 'Administrador'),
(2, 'trabajador');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sueldo_base`
--

INSERT INTO `sueldo_base` (`id_sueldo_base`, `id_trabajador`, `sueldo_base`, `cargo`, `sector_salud`, `id_escalafon`, `tipo_nomina`) VALUES
(1, 2, '150.00', 'Enfermero', 1, 1, 'Alto Nivel'),
(12, 4, '250.00', 'Enfermero', 1, 6, 'Contratado'),
(13, 5, '150.00', 'Enfermero', 0, NULL, 'Alto Nivel');

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
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sueldo_base_historial`
--

INSERT INTO `sueldo_base_historial` (`id_sueldo_base`, `sueldo_base`, `cargo`, `sector_salud`, `tipo_nomina`, `fecha`) VALUES
(1, '100.00', 'Enfermero', 1, 'Alto Nivel', '2024-06-27 02:54:29'),
(1, '150.00', 'Enfermero', 1, 'Alto Nivel', '2024-06-28 18:32:30'),
(12, '250.00', 'Enfermero', 1, 'Contratado', '2024-07-06 01:14:42'),
(13, '150.00', 'Enfermero', 0, 'Alto Nivel', '2024-07-20 07:57:24');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`id_trabajador`, `id_prima_profesionalismo`, `id_rol`, `cedula`, `numero_cuenta`, `creado`, `nombre`, `apellido`, `telefono`, `correo`, `clave`, `token`, `estado_actividad`, `comision_servicios`, `discapacitado`, `discapacidad`) VALUES
(2, 2, 1, 'V-27250544', '00000000000000000000', '2020-06-19', 'Xavier David', 'Suarez Sanchez', '0414-5555555', 'uptaebxavier@gmail.com', '$2y$10$RMrtnT5gpHIhIKQDfAThFerj/4yU.S3PABZj.AxnALU2yFAsyrbjC', '$2y$10$Ki4yyA3IIJM.s9L8Cm/H..lcZbgQo9pBuKeFOKK0p0YBttxqVhujK', 1, 0, 0, ''),
(3, 5, 1, 'V-2725054', '00000000000000000000', '2024-06-27', 'Anabel Teresa', 'Alberto Nuñes', '0414-5555555', 'david40ene@hotmail.com', '$2y$10$Mh5AEfPtMwS4x7cQq7mGX.nEWmSEJyVIFPMgXIWycKpZHGh/Cw1MO', '1', 1, 0, 0, ''),
(4, 6, 1, 'V-12434091', '00000000000000000000', '2024-06-26', 'Valeria Valentina', 'Camacaro Sanchez', '0414-5555555', 'aguilarvzla2@gmail.com', '$2y$10$TSJZitcQrUt2BiYRNw1Cmu9O4I2zFYIsQINVQweInmJzH1POHZx8K', '1', 1, 0, 0, ''),
(5, 3, 2, 'V-15447800', '00000000000000000000', '2024-07-13', 'José Luis', 'Camacaro Sanchez', '0414-5555555', 'algo@algo.com', '$2y$10$PYypHr88RRrVMT6IE9G8a.gtGc91sXOpiNNubO1CZXrnp0yZwCoIm', '1', 1, 0, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_area`
--

CREATE TABLE `trabajador_area` (
  `id_trabajador_area` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador_deducciones`
--

CREATE TABLE `trabajador_deducciones` (
  `id_trabajador_deducciones` int(11) NOT NULL,
  `id_deducciones` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `trabajador_deducciones`
--

INSERT INTO `trabajador_deducciones` (`id_trabajador_deducciones`, `id_deducciones`, `id_trabajador`) VALUES
(1, 2, 2),
(2, 2, 4);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  ADD PRIMARY KEY (`id_detalles`),
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
-- Indices de la tabla `factura_antiguedad`
--
ALTER TABLE `factura_antiguedad`
  ADD PRIMARY KEY (`id_prima_antiguedad`,`id_factura`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `factura_deducciones`
--
ALTER TABLE `factura_deducciones`
  ADD PRIMARY KEY (`id_deduccion`,`id_factura`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `factura_escalafon`
--
ALTER TABLE `factura_escalafon`
  ADD PRIMARY KEY (`id_escalafon`,`id_factura`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `factura_hijos`
--
ALTER TABLE `factura_hijos`
  ADD PRIMARY KEY (`id_factura_hijos`),
  ADD KEY `id_prima_hijos` (`id_prima_hijos`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `factura_primas_generales`
--
ALTER TABLE `factura_primas_generales`
  ADD PRIMARY KEY (`id_primas_generales`,`id_factura`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `factura_profesionalismo`
--
ALTER TABLE `factura_profesionalismo`
  ADD PRIMARY KEY (`id_profesionalismo`,`id_factura`),
  ADD KEY `id_factura` (`id_factura`);

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
  ADD UNIQUE KEY `correo` (`correo`),
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
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  MODIFY `id_detalles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT de la tabla `escalafon`
--
ALTER TABLE `escalafon`
  MODIFY `id_escalafon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `factura_hijos`
--
ALTER TABLE `factura_hijos`
  MODIFY `id_factura_hijos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `hijos`
--
ALTER TABLE `hijos`
  MODIFY `id_hijo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  MODIFY `id_liquidacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id_prima_antiguedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `prima_profesionalismo`
--
ALTER TABLE `prima_profesionalismo`
  MODIFY `id_prima_profesionalismo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `reposo`
--
ALTER TABLE `reposo`
  MODIFY `id_reposo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sueldo_base`
--
ALTER TABLE `sueldo_base`
  MODIFY `id_sueldo_base` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `trabajador_area`
--
ALTER TABLE `trabajador_area`
  MODIFY `id_trabajador_area` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_vacaciones` int(11) NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `factura_antiguedad`
--
ALTER TABLE `factura_antiguedad`
  ADD CONSTRAINT `factura_antiguedad_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  ADD CONSTRAINT `factura_antiguedad_ibfk_2` FOREIGN KEY (`id_prima_antiguedad`) REFERENCES `prima_antiguedad` (`id_prima_antiguedad`);

--
-- Filtros para la tabla `factura_deducciones`
--
ALTER TABLE `factura_deducciones`
  ADD CONSTRAINT `factura_deducciones_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`);

--
-- Filtros para la tabla `factura_escalafon`
--
ALTER TABLE `factura_escalafon`
  ADD CONSTRAINT `factura_escalafon_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`);

--
-- Filtros para la tabla `factura_hijos`
--
ALTER TABLE `factura_hijos`
  ADD CONSTRAINT `factura_hijos_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  ADD CONSTRAINT `factura_hijos_ibfk_2` FOREIGN KEY (`id_prima_hijos`) REFERENCES `primas_hijos` (`id_prima_hijos`);

--
-- Filtros para la tabla `factura_primas_generales`
--
ALTER TABLE `factura_primas_generales`
  ADD CONSTRAINT `factura_primas_generales_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`);

--
-- Filtros para la tabla `factura_profesionalismo`
--
ALTER TABLE `factura_profesionalismo`
  ADD CONSTRAINT `factura_profesionalismo_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`);

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
  ADD CONSTRAINT `fk_trabajador_deducciones_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajador_deducciones_deducciones1` FOREIGN KEY (`id_deducciones`) REFERENCES `deducciones` (`id_deducciones`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajador_prima_general`
--
ALTER TABLE `trabajador_prima_general`
  ADD CONSTRAINT `fk_trabajador_prima_general_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trabajador_prima_general_primas_generales1` FOREIGN KEY (`id_primas_generales`) REFERENCES `primas_generales` (`id_primas_generales`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD CONSTRAINT `fk_Vacaciones_Trabajadores1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
