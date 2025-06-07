-- Crear base de datos
CREATE DATABASE IF NOT EXISTS parroquia_db;
USE parroquia_db;

-- Tabla parroquias
CREATE TABLE parroquias (
    id_parroquia INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20)
);

CREATE TABLE feligreses (
    id_feligres INT PRIMARY KEY AUTO_INCREMENT,
    id_parroquia INT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100),
    fecha_nacimiento DATE,
    genero ENUM('M', 'F'),
    direccion VARCHAR(255),
    telefono VARCHAR(20) default null,
    estado_civil ENUM('soltero', 'casado', 'viudo', 'separado') default null,
    matrimonio JSON, -- <-- Aquí se guarda información sobre el matrimonio
    FOREIGN KEY (id_parroquia) REFERENCES parroquias(id_parroquia)
);

-- Tabla sacramentos (bautismo, comunión, confirmación, matrimonio)
CREATE TABLE sacramentos (
    id_sacramento INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL -- Ej: bautismo, comunion, confirmacion, matrimonio
);

 
-- Relación feligrés-sacramento (registro individual de sacramentos)
CREATE TABLE feligres_sacramento (
    id_feligres INT,
    id_sacramento INT,
    fecha DATE,
    lugar VARCHAR(255),
    observaciones TEXT,
    PRIMARY KEY (id_feligres, id_sacramento),
    FOREIGN KEY (id_feligres) REFERENCES feligreses(id_feligres),
    FOREIGN KEY (id_sacramento) REFERENCES sacramentos(id_sacramento)
);

-- Tabla parientes (padres, padrinos u otros responsables)
CREATE TABLE parientes (
    id_pariente INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100),
    telefono VARCHAR(20),
    tipo_pariente ENUM('padre', 'madre', 'padrino', 'madrina', 'otro'),
    datos_adicionales JSON
);

-- Relación feligrés-parientes
CREATE TABLE feligres_parientes (
    id_feligres INT,
    id_pariente INT,
    tipo_relacion ENUM('padre', 'madre', 'padrino_bautismo', 'padrino_confirmacion', 'otro'),
    id_sacramento INT DEFAULT NULL,
    PRIMARY KEY (id_feligres, id_pariente, tipo_relacion),
    FOREIGN KEY (id_feligres) REFERENCES feligreses(id_feligres),
    FOREIGN KEY (id_pariente) REFERENCES parientes(id_pariente),
    FOREIGN KEY (id_sacramento) REFERENCES sacramentos(id_sacramento)
);

-- Tabla catequesis (formación religiosa)
CREATE TABLE catequesis (
    id_catequesis INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    descripcion TEXT
);

-- Relación feligrés-catequesis
CREATE TABLE feligres_catequesis (
    id_feligres INT,
    id_catequesis INT,
    fecha_inscripcion DATE,
    PRIMARY KEY (id_feligres, id_catequesis),
    FOREIGN KEY (id_feligres) REFERENCES feligreses(id_feligres),
    FOREIGN KEY (id_catequesis) REFERENCES catequesis(id_catequesis)
);

-- Relación parientes-catequesis (padres/padrinos que participan)
CREATE TABLE pariente_catequesis (
    id_pariente INT,
    id_catequesis INT,
    fecha_inscripcion DATE,
    PRIMARY KEY (id_pariente, id_catequesis),
    FOREIGN KEY (id_pariente) REFERENCES parientes(id_pariente),
    FOREIGN KEY (id_catequesis) REFERENCES catequesis(id_catequesis)
);

-- Tabla cursos dentro de catequesis
CREATE TABLE cursos (
    id_curso INT PRIMARY KEY AUTO_INCREMENT,
    id_catequesis INT,
    nombre VARCHAR(100),
    descripcion TEXT,
    fecha_inicio DATE,
    fecha_fin DATE,
    FOREIGN KEY (id_catequesis) REFERENCES catequesis(id_catequesis)
);

-- Tabla catequistas
CREATE TABLE catequistas (
    id_catequista INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

-- Relación curso-catequistas
CREATE TABLE curso_catequistas (
    id_curso INT,
    id_catequista INT,
    PRIMARY KEY (id_curso, id_catequista),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso),
    FOREIGN KEY (id_catequista) REFERENCES catequistas(id_catequista)
);
