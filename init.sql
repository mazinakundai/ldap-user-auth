CREATE DATABASE IF NOT EXISTS prescient_db;
USE prescient_db;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL
);

INSERT INTO users (uid, first_name, last_name, email, department, company) VALUES
('jdoe', 'John', 'Doe', 'john.doe@example.com', 'Sales', 'Acme Corp'),
('asmith', 'Alice', 'Smith', 'alice.smith@example.com', 'Marketing', 'Acme Corp'),
('bjones', 'Bob', 'Jones', 'bob.jones@example.com', 'IT', 'Beta LLC'),
('ccarter', 'Carol', 'Carter', 'carol.carter@example.com', 'HR', 'Beta LLC'),
('mjohnson', 'Michael', 'Johnson', 'michael.johnson@example.com', 'Sales', 'Acme Corp'),
('lmartin', 'Laura', 'Martin', 'laura.martin@example.com', 'Marketing', 'Acme Corp'),
('dlee', 'David', 'Lee', 'david.lee@example.com', 'IT', 'Beta LLC'),
('ekim', 'Emma', 'Kim', 'emma.kim@example.com', 'HR', 'Beta LLC'),
('hgarcia', 'Henry', 'Garcia', 'henry.garcia@example.com', 'Sales', 'Acme Corp'),
('jwilson', 'Jane', 'Wilson', 'jane.wilson@example.com', 'Marketing', 'Acme Corp'),
('rroberts', 'Richard', 'Roberts', 'richard.roberts@example.com', 'IT', 'Beta LLC'),
('rclark', 'Rachel', 'Clark', 'rachel.clark@example.com', 'HR', 'Beta LLC'),
('slopez', 'Steven', 'Lopez', 'steven.lopez@example.com', 'Sales', 'Gamma Inc'),
('kallen', 'Karen', 'Allen', 'karen.allen@example.com', 'Marketing', 'Gamma Inc'),
('gmurphy', 'George', 'Murphy', 'george.murphy@example.com', 'IT', 'Gamma Inc'),
('jreed', 'Jessica', 'Reed', 'jessica.reed@example.com', 'HR', 'Gamma Inc'),
('pwatson', 'Paul', 'Watson', 'paul.watson@example.com', 'Sales', 'Gamma Inc'),
('swhite', 'Sara', 'White', 'sara.white@example.com', 'Marketing', 'Gamma Inc'),
('tthomas', 'Tom', 'Thomas', 'tom.thomas@example.com', 'IT', 'Gamma Inc'),
('nking', 'Nancy', 'King', 'nancy.king@example.com', 'HR', 'Gamma Inc');
