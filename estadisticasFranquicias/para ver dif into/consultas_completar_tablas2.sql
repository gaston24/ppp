--1
TRUNCATE TABLE SJ_BI_MES_HISTORIAL_ANIOS_ANTERIORES
GO
INSERT INTO SJ_BI_MES_HISTORIAL_ANIOS_ANTERIORES 
SELECT * FROM
(
SELECT MES, CAST(SUM(IMPORTE) AS INT) IMPORTE, SUM(ARTICULOS)ARTICULOS, SUM(COMP)COMP,
CAST(SUM(IMPORTE)/SUM(COMP) AS DECIMAL(10,2))PROM_TICKET, 
SUM(CANT_TICKET_2DO)CANT_TICKET_2DO, CAST(CAST(SUM(CANT_TICKET_2DO) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PROM_2DO,
SUM(CANT_TICKET_3ER)CANT_TICKET_3ER, CAST(CAST(SUM(CANT_TICKET_3ER) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PROM_3ER,
SUM(CANT_CAMBIOS)CANT_CAMBIOS, CAST(CAST(SUM(CANT_CAMBIOS) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PORC_CAMBIOS,
CAST(AVG(PORC_INCREM) AS DECIMAL(10,2))PORC_INCREM
FROM SJ_BI_MES_HISTORIAL A
WHERE CAST(MES AS VARCHAR) NOT LIKE '2019%'
GROUP BY MES
)A
ORDER BY 1


--2
TRUNCATE TABLE SJ_BI_MES_HISTORIAL_ANIOS_ANTERIORES_LOCALES
GO
INSERT INTO SJ_BI_MES_HISTORIAL_ANIOS_ANTERIORES_LOCALES 
SELECT * FROM
(
SELECT A.MES, A.NRO_SUCURS, B.DESC_SUCURSAL, IMPORTE, ARTICULOS, COMP, PROM_TICKET, CANT_TICKET_2DO, PROM_2DO, 
CANT_TICKET_3ER, PROM_3ER, CANT_CAMBIOS, PORC_CAMBIOS, PORC_INCREM 
FROM SJ_BI_MES_HISTORIAL A 
INNER JOIN SUCURSAL B
ON A.NRO_SUCURS = B.NRO_SUCURSAL
WHERE CAST(MES AS VARCHAR) NOT LIKE '2019%'
)A
ORDER BY 1


--3
TRUNCATE TABLE SJ_BI_MES_HISTORIAL_SEM_ANTERIORES_LOCALES
GO
INSERT INTO SJ_BI_MES_HISTORIAL_SEM_ANTERIORES_LOCALES 
SELECT * FROM
(
	SELECT * FROM 
	(
		SELECT 
		DATEPART(YEAR, FECHA)ANIO, B.SEMANA, B.PRIMER, B.ULTIMO, CAST(SUM(IMPORTE) AS INT) IMPORTE, SUM(ARTICULOS)ARTICULOS, SUM(COMP)COMP,
		SUM(IMPORTE)/SUM(COMP) PROM_TICKET, 
		SUM(CANT_TICKET_2DO)CANT_TICKET_2DO, CAST(CAST(SUM(CANT_TICKET_2DO) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PROM_2DO,
		SUM(CANT_TICKET_3ER)CANT_TICKET_3ER, CAST(CAST(SUM(CANT_TICKET_3ER) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PROM_3ER,
		SUM(CANT_CAMBIOS)CANT_CAMBIOS, CAST(CAST(SUM(CANT_CAMBIOS) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PORC_CAMBIOS,
		CAST(AVG(PORC_INCREM) AS DECIMAL(10,2))PORC_INCREM

		FROM SJ_BI_DIA_HISTORIAL A

		INNER JOIN SJ_SEMANA B
		ON DATEPART(WEEK, A.FECHA) = B.SEMANA AND DATEPART(YEAR, A.FECHA) = B.ANIO

		GROUP BY DATEPART(YEAR, FECHA), B.SEMANA, B.PRIMER, B.ULTIMO
	)A
	WHERE ANIO != 2019
	
)A


--4


TRUNCATE TABLE SJ_BI_DIA_HISTORIAL_LOCALES
GO
INSERT INTO SJ_BI_DIA_HISTORIAL_LOCALES 
SELECT * FROM
(
	SELECT ANIO, SEMANA, NRO_SUCURS, DESC_SUCURSAL, IMPORTE, ARTICULOS, COMP,
	IMPORTE/COMP PROM_TICKET,
	CANT_TICKET_2DO, PROM_2DO, CANT_TICKET_3ER, PROM_3ER, CANT_CAMBIOS, PORC_CAMBIOS, PORC_INCREM
	FROM
	(
		SELECT 
		DATEPART(YEAR, A.FECHA)ANIO, DATEPART(WEEK, A.FECHA)SEMANA, A.NRO_SUCURS, C.DESC_SUCURSAL, CAST(SUM(IMPORTE) AS INT) IMPORTE, SUM(ARTICULOS)ARTICULOS, SUM(COMP)COMP,
		SUM(CANT_TICKET_2DO)CANT_TICKET_2DO, CAST(CAST(SUM(CANT_TICKET_2DO) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PROM_2DO,
		SUM(CANT_TICKET_3ER)CANT_TICKET_3ER, CAST(CAST(SUM(CANT_TICKET_3ER) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PROM_3ER,
		SUM(CANT_CAMBIOS)CANT_CAMBIOS, CAST(CAST(SUM(CANT_CAMBIOS) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PORC_CAMBIOS,
		CAST(AVG(PORC_INCREM) AS DECIMAL(10,2))PORC_INCREM
		FROM SJ_BI_DIA_HISTORIAL A
		INNER JOIN SUCURSAL C
		ON A.NRO_SUCURS = C.NRO_SUCURSAL
		WHERE DATEPART(YEAR, A.FECHA) != 2019
		GROUP BY DATEPART(YEAR, A.FECHA), DATEPART(WEEK, A.FECHA), A.NRO_SUCURS, C.DESC_SUCURSAL
	)A
)A
ORDER BY 1, 2, 3