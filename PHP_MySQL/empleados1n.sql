CREATE DATABASE empleados1n;
USE empleados1n;

CREATE TABLE departamento
(cod_dpto      VARCHAR(4),
 nombre_dpto   VARCHAR(40),
 CONSTRAINT pk_departamento 
 PRIMARY KEY (cod_dpto)) ENGINE=InnoDB;
 
CREATE TABLE empleado 
(dni VARCHAR(9), 
 nombre VARCHAR(40),
 apellidos VARCHAR(40), 
 fecha_nac DATE, 
 salario DOUBLE, 
 cod_dpto  VARCHAR(4),
CONSTRAINT pk_empleado PRIMARY KEY (dni), 
CONSTRAINT fk_empleado FOREIGN KEY (cod_dpto) REFERENCES departamento(cod_dpto)) ENGINE=InnoDB;