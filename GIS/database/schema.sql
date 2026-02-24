CREATE DATABASE IF NOT EXISTS agritrack;
USE agritrack;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    crop_type VARCHAR(50) NOT NULL,
    planting_date DATE NOT NULL,
    area DECIMAL(10,2) NOT NULL,
    expected_yield DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS crop_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT NOT NULL,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    progress DECIMAL(5,2) NOT NULL,
    growth_stage VARCHAR(50) NOT NULL,
    health_status VARCHAR(50) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (crop_id) REFERENCES crops(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
); 