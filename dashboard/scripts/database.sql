-- Create database
CREATE DATABASE IF NOT EXISTS dentalcare;
USE dentalcare;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'dentist', 'assistant', 'receptionist') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create patients table
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    date_of_birth DATE,
    address TEXT,
    medical_history TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create procedures table
CREATE TABLE IF NOT EXISTS procedures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration VARCHAR(20) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    procedure_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration VARCHAR(20) NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled', 'no-show') DEFAULT 'scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (procedure_id) REFERENCES procedures(id) ON DELETE CASCADE
);

-- Create payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    appointment_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('cash', 'credit_card', 'insurance', 'other') NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
);

-- Create appointment_logs table for tracking wait times
CREATE TABLE IF NOT EXISTS appointment_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    scheduled_time TIME NOT NULL,
    actual_start_time TIME,
    actual_end_time TIME,
    wait_time INT, -- in minutes
    log_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);

-- Create activities table for tracking recent activities
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activity_type ENUM('patient', 'appointment', 'payment', 'other') NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, first_name, last_name, role)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@dentalcare.com', 'Admin', 'User', 'admin');

-- Insert sample procedures
INSERT INTO procedures (name, description, duration, price) VALUES
('Dental Cleaning', 'Regular dental cleaning and checkup', '1 hour', 150.00),
('Root Canal', 'Root canal treatment', '1.5 hours', 800.00),
('Consultation', 'Initial consultation with dentist', '30 minutes', 75.00),
('Filling', 'Dental filling procedure', '45 minutes', 200.00),
('Crown', 'Dental crown placement', '1 hour', 1200.00),
('Extraction', 'Tooth extraction', '45 minutes', 250.00);

-- Insert sample patients
INSERT INTO patients (first_name, last_name, email, phone, date_of_birth, address) VALUES
('Sarah', 'Johnson', 'sarah.johnson@example.com', '555-123-4567', '1985-06-15', '123 Main St, Anytown, USA'),
('Michael', 'Brown', 'michael.brown@example.com', '555-234-5678', '1978-09-22', '456 Oak Ave, Somewhere, USA'),
('Emily', 'Davis', 'emily.davis@example.com', '555-345-6789', '1990-03-10', '789 Pine Rd, Nowhere, USA'),
('David', 'Wilson', 'david.wilson@example.com', '555-456-7890', '1982-11-05', '101 Elm St, Anywhere, USA'),
('Sarah', 'Wilson', 'sarah.wilson@example.com', '555-567-8901', '1975-07-20', '202 Maple Dr, Everywhere, USA');

-- Insert sample appointments
INSERT INTO appointments (patient_id, procedure_id, appointment_date, appointment_time, duration, notes) VALUES
(1, 1, CURDATE(), '09:00:00', '1 hour', 'Regular cleaning'),
(2, 2, CURDATE(), '10:30:00', '1.5 hours', 'Patient reported pain in lower right molar'),
(3, 3, CURDATE(), '13:00:00', '30 minutes', 'New patient consultation'),
(4, 4, CURDATE(), '14:00:00', '45 minutes', 'Filling for cavity in upper left molar'),
(5, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '09:30:00', '1 hour', 'Regular cleaning');

-- Insert sample activities
INSERT INTO activities (activity_type, title, description, created_at) VALUES
('patient', 'New patient registered', 'Emily Johnson completed registration', DATE_SUB(NOW(), INTERVAL 10 MINUTE)),
('appointment', 'Appointment rescheduled', 'Michael Brown moved to tomorrow at 2:00 PM', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('payment', 'Payment received', 'Sarah Wilson paid $150 for dental cleaning', DATE_SUB(NOW(), INTERVAL 3 HOUR));

-- Insert sample payments
INSERT INTO payments (patient_id, appointment_id, amount, payment_method, payment_date) VALUES
(5, 5, 150.00, 'credit_card', DATE_SUB(NOW(), INTERVAL 3 HOUR));

-- Insert sample appointment logs for wait time calculation
INSERT INTO appointment_logs (appointment_id, scheduled_time, actual_start_time, actual_end_time, wait_time, log_date) VALUES
(1, '09:00:00', '09:10:00', '10:05:00', 10, DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
(2, '10:30:00', '10:45:00', '12:10:00', 15, DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
(3, '13:00:00', '13:20:00', '13:45:00', 20, DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
(4, '14:00:00', '14:05:00', '14:50:00', 5, DATE_SUB(CURDATE(), INTERVAL 1 DAY));
