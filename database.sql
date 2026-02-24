CREATE DATABASE IF NOT EXISTS agritrack;
USE agritrack;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('farmer', 'admin', 'viewer') NOT NULL DEFAULT 'farmer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    crop_type VARCHAR(50) NOT NULL,
    field_size DECIMAL(10,2) NOT NULL,
    planting_date DATE NOT NULL,
    harvest_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    status ENUM('planted', 'growing', 'harvested') NOT NULL DEFAULT 'planted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@agritrack.com', '$2y$10$8KzWmJ3XgTZxYVYQ9fQZ0uJfQZ0uJfQZ0uJfQZ0uJfQZ0uJfQZ0', 'admin');

CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_crop_user ON crops(user_id);
CREATE INDEX idx_crop_status ON crops(status); 