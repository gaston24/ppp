SET DATEFORMAT YMD


--ACTUAL
SELECT COD_CLIENTE, CASE WHEN FECHA IS NULL THEN 'OK' ELSE 'NO' END PLAZO, RAZON_SOCIAL, ES_GRUPO, PPP, CUPO_CRED, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA, (CUPO_CRED - TOTAL_DEUDA) TOTAL_DISPONIBLE FROM
(
	SELECT COD_CLIENTE, D.FECHA, RAZON_SOCIAL, ES_GRUPO, CAST(PPP AS INT)PPP, CAST(CUPO_CREDI AS int) CUPO_CRED, 
	CAST(SALDO_CC AS INT)SALDO_CC, 
	CAST(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END AS INT)CHEQUE, 
	CAST(CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END AS INT)CHEQUES_10_DIAS, 
	CAST((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)) AS INT) TOTAL_DEUDA
	FROM
	(
		SELECT 
		CASE WHEN GRUPO_EMPR IS NULL OR GRUPO_EMPR = '' THEN COD_CLIENTE ELSE GRUPO_EMPR END COD_CLIENTE, 
		CASE WHEN GRUPO_EMPR IS NULL OR GRUPO_EMPR = '' THEN RAZON_SOCIAL ELSE NOMBRE_GRU END RAZON_SOCIAL, 
		CASE WHEN GRUPO_EMPR IS NULL OR GRUPO_EMPR = '' THEN 'NO' ELSE 'SI' END ES_GRUPO,
		AVG(PPP)PPP, AVG(DIAS)DIAS, CUPO_CREDI, SUM(SALDO_CC) SALDO_CC
		FROM
		(
			SELECT COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, C.NOMBRE_GRU , 
			CAST(AVG(PPP) AS decimal(10,2)) PPP, CAST(AVG(DIAS) AS INT) DIAS, B.CUPO_CREDI, B.SALDO_CC
			FROM GC_VIEW_PPP A
			INNER JOIN GVA14 B
			ON A.COD_CLIENTE = B.COD_CLIENT
			LEFT JOIN GVA62 C
			ON B.GRUPO_EMPR = C.GRUPO_EMPR
			WHERE B.FECHA_INHA = '1800-01-01'
			AND B.COD_CLIENT LIKE 'FR%'
			AND FECHA_RECIBO >= GETDATE()-365
			GROUP BY COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, C.NOMBRE_GRU, B.CUPO_CREDI, B.SALDO_CC
		)A
		GROUP BY (CASE WHEN GRUPO_EMPR IS NULL OR GRUPO_EMPR = '' THEN COD_CLIENTE ELSE GRUPO_EMPR END), 
		(CASE WHEN GRUPO_EMPR IS NULL OR GRUPO_EMPR = '' THEN RAZON_SOCIAL ELSE NOMBRE_GRU END), (CASE WHEN GRUPO_EMPR IS NULL OR GRUPO_EMPR = '' THEN 'NO' ELSE 'SI' END), 
		CUPO_CREDI
	)A
	LEFT JOIN
	(SELECT CLIENTE, SUM(CHEQUES)CHEQUES FROM ( SELECT CASE WHEN GRUPO_EMPR = '' THEN CLIENTE ELSE GRUPO_EMPR END CLIENTE, CHEQUES 
	FROM ( SELECT CLIENTE, GRUPO_EMPR, SUM(IMPORTE_CH)CHEQUES FROM SBA14 A INNER JOIN GVA14 B ON A.CLIENTE = B.COD_CLIENT
	WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') AND COD_CLIENT LIKE 'FR%' GROUP BY CLIENTE, GRUPO_EMPR )A )A GROUP BY CLIENTE) B
	ON A.COD_CLIENTE = B.CLIENTE
	LEFT JOIN
	(SELECT CLIENTE, SUM(CHEQUES)CHEQUES_PRONTO FROM ( SELECT CASE WHEN GRUPO_EMPR = '' THEN CLIENTE ELSE GRUPO_EMPR END CLIENTE, CHEQUES 
	FROM ( SELECT CLIENTE, GRUPO_EMPR, SUM(IMPORTE_CH)CHEQUES FROM SBA14 A INNER JOIN GVA14 B ON A.CLIENTE = B.COD_CLIENT
	WHERE (FECHA_CHEQ >= GETDATE() AND FECHA_CHEQ <= GETDATE()+10) AND ESTADO NOT IN ('X', 'R') AND COD_CLIENT LIKE 'FR%' GROUP BY CLIENTE, GRUPO_EMPR )A )A GROUP BY CLIENTE) C
	ON A.COD_CLIENTE = C.CLIENTE
	LEFT JOIN
	(SELECT MIN(FECHA)FECHA, COD_CLIENT FROM ( SELECT FECHA, CASE WHEN GRUPO_EMPR = '' THEN COD_CLIENT ELSE GRUPO_EMPR END COD_CLIENT FROM (
	SELECT MIN(FECHA_EMIS)FECHA, A.COD_CLIENT, B.GRUPO_EMPR FROM GVA12 A INNER JOIN GVA14 B ON A.COD_CLIENT = B.COD_CLIENT WHERE FECHA_EMIS >= GETDATE()-45 
	AND A.COD_CLIENT LIKE 'FR%' AND A.T_COMP = 'FAC' AND ESTADO = 'PEN' GROUP BY A.COD_CLIENT, B.GRUPO_EMPR )A WHERE FECHA < GETDATE()-30 )A GROUP BY COD_CLIENT)D
	ON A.COD_CLIENTE = D.COD_CLIENT
)A

--VIEJO
SELECT COD_CLIENTE, CASE WHEN FECHA IS NULL THEN 'OK' ELSE 'NO' END PLAZO, RAZON_SOCIAL, PPP, CUPO_CRED, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA, (CUPO_CRED - TOTAL_DEUDA) TOTAL_DISPONIBLE FROM
(
	SELECT COD_CLIENTE, D.FECHA, RAZON_SOCIAL, CAST(PPP AS INT)PPP, CAST(CUPO_CREDI AS int) CUPO_CRED, 
	CAST(SALDO_CC AS INT)SALDO_CC, 
	CAST(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END AS INT)CHEQUE, 
	CAST(CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END AS INT)CHEQUES_10_DIAS, 
	CAST((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)) AS INT) TOTAL_DEUDA
	FROM
	(
		SELECT COD_CLIENTE, RAZON_SOCIAL,
		CAST(AVG(PPP) AS decimal(10,2)) PPP, CAST(AVG(DIAS) AS INT) DIAS, B.CUPO_CREDI, B.SALDO_CC
		FROM GC_VIEW_PPP A
		INNER JOIN GVA14 B
		ON A.COD_CLIENTE = B.COD_CLIENT
		WHERE B.FECHA_INHA = '1800-01-01'
		AND B.COD_CLIENT LIKE 'FR%'
		AND FECHA_RECIBO >= GETDATE()-365
		GROUP BY COD_CLIENTE, RAZON_SOCIAL, B.CUPO_CREDI, B.SALDO_CC
	)A
	LEFT JOIN
	(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES FROM SBA14 WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) B
	ON A.COD_CLIENTE = B.CLIENTE
	LEFT JOIN
	(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES_PRONTO FROM SBA14 WHERE (FECHA_CHEQ >= GETDATE() AND FECHA_CHEQ <= GETDATE()+10) AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) C
	ON A.COD_CLIENTE = C.CLIENTE
	LEFT JOIN
	(SELECT FECHA, COD_CLIENT FROM (SELECT MIN(FECHA_EMIS)FECHA, COD_CLIENT FROM GVA12 A WHERE FECHA_EMIS >= GETDATE()-45 
	AND COD_CLIENT LIKE 'FR%' AND A.T_COMP = 'FAC' AND ESTADO = 'PEN' GROUP BY COD_CLIENT )A WHERE FECHA < GETDATE()-30)D
	ON A.COD_CLIENTE = D.COD_CLIENT
)A


--DETALLE GRUPO
SELECT COD_CLIENTE, GRUPO_EMPR, CASE WHEN FECHA IS NULL THEN 'OK' ELSE 'NO' END PLAZO, RAZON_SOCIAL, PPP, CUPO_CRED, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA, (CUPO_CRED - TOTAL_DEUDA) TOTAL_DISPONIBLE FROM
(
	SELECT COD_CLIENTE, D.FECHA, RAZON_SOCIAL, GRUPO_EMPR, CAST(PPP AS INT)PPP, CAST(CUPO_CREDI AS int) CUPO_CRED, 
	CAST(SALDO_CC AS INT)SALDO_CC, 
	CAST(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END AS INT)CHEQUE, 
	CAST(CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END AS INT)CHEQUES_10_DIAS, 
	CAST((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)) AS INT) TOTAL_DEUDA
	FROM
	(
		SELECT COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR,
		CAST(AVG(PPP) AS decimal(10,2)) PPP, CAST(AVG(DIAS) AS INT) DIAS, B.CUPO_CREDI, B.SALDO_CC
		FROM GC_VIEW_PPP A
		INNER JOIN GVA14 B
		ON A.COD_CLIENTE = B.COD_CLIENT
		WHERE B.FECHA_INHA = '1800-01-01'
		AND B.COD_CLIENT LIKE 'FR%'
		AND FECHA_RECIBO >= GETDATE()-365
		GROUP BY COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, B.CUPO_CREDI, B.SALDO_CC
	)A
	LEFT JOIN
	(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES FROM SBA14 WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) B
	ON A.COD_CLIENTE = B.CLIENTE
	LEFT JOIN
	(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES_PRONTO FROM SBA14 WHERE (FECHA_CHEQ >= GETDATE() AND FECHA_CHEQ <= GETDATE()+10) AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) C
	ON A.COD_CLIENTE = C.CLIENTE
	LEFT JOIN
	(SELECT FECHA, COD_CLIENT FROM (SELECT MIN(FECHA_EMIS)FECHA, COD_CLIENT FROM GVA12 A WHERE FECHA_EMIS >= GETDATE()-45 
	AND COD_CLIENT LIKE 'FR%' AND A.T_COMP = 'FAC' AND ESTADO = 'PEN' GROUP BY COD_CLIENT )A WHERE FECHA < GETDATE()-30)D
	ON A.COD_CLIENTE = D.COD_CLIENT
)A



--CHEQUES AGRUPADOS

SELECT COD_CLIENT, RAZON_SOCI, SUM(IMPORTE_CH)TOTAL_CHEQUES
FROM (
SELECT B.COD_CLIENT, B.RAZON_SOCI, B.GRUPO_EMPR, CAST (FECHA_CHEQ AS DATE)FECHA_CHEQ, N_COMP_REC, CAST(IMPORTE_CH AS INT)IMPORTE_CH 
FROM SBA14 A
INNER JOIN GVA14 B
ON A.CLIENTE = B.COD_CLIENT
WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') 
AND B.GRUPO_EMPR = 'FRDODI'
)A
GROUP BY COD_CLIENT, RAZON_SOCI
order by 1;