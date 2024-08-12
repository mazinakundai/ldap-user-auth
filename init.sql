-- create DB
CREATE DATABASE IF NOT EXISTS prescient_db;
USE prescient_db;

-- Drop user if it exists
DROP USER IF EXISTS 'pDbUser'@'%';

-- Create a new user
CREATE USER 'pDbUser'@'%' IDENTIFIED BY 'pDbPass';

-- Grant all privileges on the database to the new user
GRANT ALL PRIVILEGES ON prescient_db.* TO 'pDbUser'@'%';

-- Flush privileges to ensure the changes take effect
FLUSH PRIVILEGES;

DROP TABLE IF EXISTS users;
-- create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid VARCHAR(255) NOT NULL,
    uid_number VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    group_id VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL
);

-- insert the users
INSERT INTO users (uid,uid_number, first_name, last_name, email, group_id, department, company) VALUES
('jdoe', '1003', 'John', 'Doe', 'john.doe@example.com', '500', 'Sales', 'Acme Corp'),
('asmith', '1004', 'Alice', 'Smith', 'alice.smith@example.com', '502', 'Marketing', 'Acme Corp'),
('bjones', '1005', 'Bob', 'Jones', 'bob.jones@example.com', 'IT', '501', 'Beta LLC'),
('ccarter', '1006', 'Carol', 'Carter', 'carol.carter@example.com', '501', 'HR', 'Beta LLC'),
('mjohnson', '1007', 'Michael', 'Johnson', 'michael.johnson@example.com', '503', 'Sales', 'Acme Corp'),
('lmartin', '1008', 'Laura', 'Martin', 'laura.martin@example.com', '503', 'Marketing', 'Acme Corp'),
('dlee', '1009', 'David', 'Lee', 'david.lee@example.com', '503', 'IT', 'Beta LLC'),
('ekim', '1010', 'Emma', 'Kim', 'emma.kim@example.com', '500', 'HR', 'Beta LLC'),
('hgarcia', '1011', 'Henry', 'Garcia', 'henry.garcia@example.com', '500', 'Sales', 'Acme Corp'),
('jwilson', '1012', 'Jane', 'Wilson', 'jane.wilson@example.com', '500', 'Marketing', 'Acme Corp'),
('rroberts', '1013', 'Richard', 'Roberts', 'richard.roberts@example.com', '503', 'IT', 'Beta LLC'),
('rclark', '1014', 'Rachel', 'Clark', 'rachel.clark@example.com', '500', 'HR', 'Beta LLC'),
('slopez', '1015', 'Steven', 'Lopez', 'steven.lopez@example.com', '503', 'Sales', 'Gamma Inc'),
('kallen', '1016', 'Karen', 'Allen', 'karen.allen@example.com', '501', 'Marketing', 'Gamma Inc'),
('gmurphy', '1017', 'George', 'Murphy', 'george.murphy@example.com', '503', 'IT', 'Gamma Inc'),
('jreed', '1018', 'Jessica', 'Reed', 'jessica.reed@example.com', '500', 'HR', 'Gamma Inc'),
('pwatson', '1019', 'Paul', 'Watson', 'paul.watson@example.com','501',  'Sales', 'Gamma Inc'),
('swhite', '1020', 'Sara', 'White', 'sara.white@example.com', '502', 'Marketing', 'Gamma Inc'),
('tthomas', '1021', 'Tom', 'Thomas', 'tom.thomas@example.com', '500', 'IT', 'Gamma Inc'),
('nking', '1022', 'Nancy', 'King', 'nancy.king@example.com', '501', 'HR', 'Gamma Inc');
