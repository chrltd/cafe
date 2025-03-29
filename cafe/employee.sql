CREATE DATABASE brewlane_cafe;

USE brewlane_cafe;

CREATE TABLE EMPLOYEE (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, 
    name VARCHAR(100) NOT NULL
);


INSERT INTO EMPLOYEE (email, password, name) 
VALUES ('juliepearl@gmail.com', '$2y$10$XlP4iTpt3JFEq4yW7mFRme3RH3hE0itPR.l02yDW/4zgEjYPD5TuO', 'Julie Pearl');
