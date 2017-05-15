use webpao;


#DROP TABLE auditoria;
CREATE TABLE IF NOT EXISTS auditoria(
	codigo INT PRIMARY KEY AUTO_INCREMENT, 
    fecha DATE NOT NULL, 
    pagina_web varchar(255), 
    numero_registros INT,
	estado varchar(10) CHECK (estado='pendiente' OR City='finalizado'), 
    errores INT
);


#DROP TABLE aspecto;
#DROP TABLE registros;

CREATE TABLE IF NOT EXISTS registros(
	codigo INT PRIMARY KEY AUTO_INCREMENT, 
    Cod_Auditoria INT NOT NULL,
	nomre varchar(50),
    icon_URL varchar(300),
    vida varchar(100),
    danno varchar(100),
    movimiento varchar(100),
    regeneracion varchar(100),
    armadura varchar(100),
    rest_magica varchar(100),
    descripcion varchar(300),
    url_ab_1 varchar(200),
    url_ab_2 varchar(200),
    url_ab_3 varchar(200),
    url_ab_4 varchar(200),
    url_ab_5 varchar(200),
    
    FOREIGN KEY (Cod_Auditoria)
        REFERENCES auditoria(codigo)
);

CREATE TABLE IF NOT EXISTS aspecto(
	champ INT,
    url_Aspecto varchar(300),
    
    FOREIGN KEY (champ)
        REFERENCES registros(codigo)
);

INSERT INTO auditoria(fecha, pagina_web, estado) values (NOW(),"test","pendiente");

SELECT * FROM auditoria;

SELECT * FROM registros;

SELECT * FROM aspecto;