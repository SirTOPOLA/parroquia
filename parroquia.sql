 



-- Tabla principal de personas
CREATE TABLE persona (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    genero ENUM('masculino', 'femenino', 'otro') DEFAULT NULL
);



-- Tipos de sacramentos
CREATE TABLE sacramento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre ENUM('bautismo', 'comunion', 'confirmacion', 'matrimonio') NOT NULL UNIQUE,
    descripcion TEXT
);

-- Catequesis (preparación para sacramentos)
CREATE TABLE catequesis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    sacramento_id INT NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    catequista_id INT,
    observaciones TEXT,
    FOREIGN KEY (sacramento_id) REFERENCES sacramento(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (catequista_id) REFERENCES persona(id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Relación de personas inscritas en catequesis
CREATE TABLE participante_catequesis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    catequesis_id INT NOT NULL,
    fecha_inscripcion DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (persona_id) REFERENCES persona(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (catequesis_id) REFERENCES catequesis(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Actos sacramentales celebrados
CREATE TABLE acto_sacramental (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    sacramento_id INT NOT NULL,
    parroquia_id INT,
    parroco_id INT,
    fecha DATE NOT NULL,
    libro VARCHAR(50),
    folio VARCHAR(50),
    partida VARCHAR(50),
    observaciones TEXT,
    certificado_emitido BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (persona_id) REFERENCES persona(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (sacramento_id) REFERENCES sacramento(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (parroquia_id) REFERENCES parroquia(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (parroco_id) REFERENCES persona(id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Relaciones familiares o espirituales por acto sacramental
CREATE TABLE relaciones_persona (
    id INT AUTO_INCREMENT PRIMARY KEY,
    acto_sacramental_id INT NOT NULL,
    persona_id INT NOT NULL,
    rol ENUM('padre', 'madre', 'padrino', 'madrina') NOT NULL,
    FOREIGN KEY (acto_sacramental_id) REFERENCES acto_sacramental(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (persona_id) REFERENCES persona(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Usuarios del sistema (vinculados a persona)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'secretario', 'archivista', 'parroco') NOT NULL,
    estado BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (persona_id) REFERENCES persona(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE catequistas (
    persona_id INT PRIMARY KEY,
    especialidad VARCHAR(100),
    FOREIGN KEY (persona_id) REFERENCES persona(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);


 