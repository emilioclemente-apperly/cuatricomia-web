-- Estructura de Base de Datos para la Federación de Clubes Sociales

CREATE TABLE IF NOT EXISTS estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS clubes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    estado_id INT NOT NULL,
    direccion_mapas VARCHAR(255),
    logo_url VARCHAR(255),
    FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS concesionarios_internos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    club_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    rubro VARCHAR(100),
    telefono VARCHAR(50),
    whatsapp VARCHAR(20),
    horario VARCHAR(100),
    FOREIGN KEY (club_id) REFERENCES clubes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS aliados_comerciales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    estado_id INT NOT NULL,
    descuento_info TEXT,
    direccion_mapas VARCHAR(255),
    whatsapp VARCHAR(20),
    categoria VARCHAR(100),
    FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserción de Estados de Venezuela
INSERT INTO estados (nombre) VALUES 
('Amazonas'), ('Anzoátegui'), ('Apure'), ('Aragua'), ('Barinas'), 
('Bolívar'), ('Carabobo'), ('Cojedes'), ('Delta Amacuro'), ('Distrito Capital'), 
('Falcón'), ('Guárico'), ('Lara'), ('Mérida'), ('Miranda'), 
('Monagas'), ('Nueva Esparta'), ('Portuguesa'), ('Sucre'), ('Táchira'), 
('Trujillo'), ('La Guaira'), ('Yaracuy'), ('Zulia')
ON DUPLICATE KEY UPDATE nombre=nombre;
