/*******************************************************************************
   Drop database if it exists
********************************************************************************/
DROP DATABASE IF EXISTS `iesprimitiva`;


/*******************************************************************************
   Create database
********************************************************************************/
CREATE DATABASE `iesprimitiva`;


USE `iesprimitiva`;

CREATE TABLE `Empleado`
(
    `DNI` NVARCHAR(9) NOT NULL,
    `NOMBRE` NVARCHAR(40) NOT NULL,
    `APELLIDO` NVARCHAR(20) NOT NULL,
    `EMAIL` NVARCHAR(60) NOT NULL,
	CONSTRAINT `PK_Empleado` PRIMARY KEY  (`DNI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `Apostante`
(
    `DNI` NVARCHAR(9) NOT NULL,
    `NOMBRE` NVARCHAR(40) NOT NULL,
    `APELLIDO` NVARCHAR(20) NOT NULL,
    `EMAIL` NVARCHAR(60) NOT NULL,
	`SALDO` NUMERIC(10,2) NOT NULL,
	CONSTRAINT `PK_Apostante` PRIMARY KEY  (`DNI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Sorteo`
(
    `NSORTEO` NVARCHAR(4) NOT NULL,
    `FECHA` TIMESTAMP NOT NULL,
    `RECAUDACION` NUMERIC(20) NOT NULL,
	`RECAUDACION_PREMIOS` NUMERIC(20) NOT NULL,
	`DNI` NVARCHAR(9) NOT NULL,
	`ACTIVO` NVARCHAR(1) NOT NULL,
	`COMBINACION_GANADORA` NVARCHAR(40) NOT NULL,
   	CONSTRAINT `PK_Sorteo` PRIMARY KEY  (`NSORTEO`),
	CONSTRAINT `FK_Sorteo_Empleado` FOREIGN KEY  (`DNI`) REFERENCES Empleado (DNI)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Apuestas`
(
    `NAPUESTA` INT(6) AUTO_INCREMENT NOT NULL,
	`DNI` NVARCHAR(9) NOT NULL,
	`NSORTEO` NVARCHAR(4) NOT NULL,
	`FECHA` TIMESTAMP NOT NULL,
	`N1` NUMERIC(2) NOT NULL,
	`N2` NUMERIC(2) NOT NULL,
	`N3` NUMERIC(2) NOT NULL,
	`N4` NUMERIC(2) NOT NULL,
	`N5` NUMERIC(2) NOT NULL,
	`N6` NUMERIC(2) NOT NULL,
	`R`  NUMERIC(2) NOT NULL,
	`IMPORTE_PREMIO`  NUMERIC(20,2),
	`CATEGORIA_PREMIO` ENUM('6','5C','5','4','3','6R','5CR','5R','4R','3R','R'),
	CONSTRAINT `PK_Apuestas` PRIMARY KEY  (NAPUESTA),
	CONSTRAINT `FK_Apuestas_Apostante` FOREIGN KEY  (`DNI`) REFERENCES Apostante (DNI),
	CONSTRAINT `FK_Apuestas_Sorteo` FOREIGN KEY  (`NSORTEO`) REFERENCES Sorteo (NSORTEO)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	