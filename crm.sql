CREATE DATABASE crm_inmoweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crm_inmoweb;

-- Tabla de inmuebles
CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    zone VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    rooms INT NOT NULL,
    area INT NOT NULL,
    terrace BOOLEAN DEFAULT FALSE,
    garage BOOLEAN DEFAULT FALSE
);

-- Tabla de clientes
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    preferred_zones JSON NOT NULL,
    min_price DECIMAL(10,2),
    max_price DECIMAL(10,2),
    min_rooms INT,
    max_rooms INT,
    wants_terrace BOOLEAN DEFAULT FALSE,
    wants_garage BOOLEAN DEFAULT FALSE
);

-- Datos de ejemplo
INSERT INTO properties (title, zone, price, rooms, area, terrace, garage)
VALUES
('Piso luminoso con terraza en Chamberí', 'Chamberí', 420000, 3, 95, TRUE, TRUE),
('Ático moderno en Salamanca', 'Salamanca', 650000, 2, 80, TRUE, TRUE);

INSERT INTO clients (name, preferred_zones, min_price, max_price, min_rooms, max_rooms, wants_terrace, wants_garage)
VALUES
('Ana Pérez', '["Chamberí","Ríos Rosas"]', 350000, 450000, 2, 3, TRUE, TRUE),
('María López', '["Chamberí"]', 380000, 500000, 3, 4, FALSE, TRUE),
('Javier Martín', '["Salamanca"]', 500000, 800000, 2, 3, TRUE, FALSE),
('Carlos Ruiz', '["Centro"]', 200000, 300000, 1, 2, TRUE, FALSE),
('Carmen García', '["Centro", "Salamanca"]', 300000, 600000, 3, 5, FALSE, TRUE);

CREATE USER 'crmuser'@'localhost' IDENTIFIED BY 'password_crmuser';
GRANT ALL PRIVILEGES ON *.* TO 'crmuser'@'localhost';
FLUSH PRIVILEGES;
