-- SmileDesk Database Schema
CREATE DATABASE IF NOT EXISTS smiledesk;
USE smiledesk;

-- Clinics table
CREATE TABLE clinics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(255),
    working_hours JSON,
    settings JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users table (dentists, assistants, admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'dentist', 'assistant', 'receptionist') NOT NULL,
    phone VARCHAR(20),
    specialization VARCHAR(100),
    license_number VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE
);

-- Patients table
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    patient_number VARCHAR(20) UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    address TEXT,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    medical_history TEXT,
    allergies TEXT,
    insurance_provider VARCHAR(100),
    insurance_number VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE
);

-- Service categories
CREATE TABLE service_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE
);

-- Services table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT NOT NULL, -- in minutes
    price DECIMAL(10, 2) NOT NULL,
    requires_tooth_selection BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE SET NULL
);

-- Appointments table
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    patient_id INT NOT NULL,
    service_id INT NOT NULL,
    dentist_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration INT NOT NULL, -- in minutes
    selected_teeth JSON, -- for tooth-specific procedures
    status ENUM('scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    notes TEXT,
    reminder_sent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (dentist_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Invoices table
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    patient_id INT NOT NULL,
    appointment_id INT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    tax_amount DECIMAL(10, 2) DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    due_date DATE,
    paid_date TIMESTAMP NULL,
    payment_method ENUM('cash', 'credit_card', 'bank_transfer', 'insurance', 'other'),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
);

-- Invoice items table
CREATE TABLE invoice_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    service_id INT,
    description VARCHAR(255) NOT NULL,
    quantity INT DEFAULT 1,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
);

-- Documents table
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    patient_id INT,
    appointment_id INT,
    type ENUM('xray', 'treatment_plan', 'consent_form', 'report', 'prescription', 'other') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(500),
    file_size INT,
    mime_type VARCHAR(100),
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Treatment history table
CREATE TABLE treatment_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    patient_id INT NOT NULL,
    appointment_id INT,
    service_id INT NOT NULL,
    dentist_id INT NOT NULL,
    teeth_treated JSON,
    diagnosis TEXT,
    treatment_notes TEXT,
    treatment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (dentist_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Activities/Logs table
CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    user_id INT,
    type ENUM('patient_added', 'appointment_scheduled', 'appointment_completed', 'appointment_cancelled', 'payment_received', 'document_uploaded', 'other') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Appointment logs for wait time tracking
CREATE TABLE appointment_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    appointment_id INT NOT NULL,
    scheduled_time TIME NOT NULL,
    actual_start_time TIME,
    actual_end_time TIME,
    wait_time INT, -- in minutes
    log_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);

-- Notifications table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    user_id INT,
    type ENUM('appointment_reminder', 'payment_due', 'system_alert', 'other') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- SMS settings and logs
CREATE TABLE sms_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    patient_id INT NOT NULL,
    appointment_id INT,
    phone_number VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'sent', 'delivered', 'failed') DEFAULT 'pending',
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
);

-- Insert sample data
INSERT INTO clinics (name, address, phone, email) VALUES 
('SmileDesk Demo Clinic', '123 Dental Street, Casablanca, Morocco', '+212-522-123456', 'info@smiledesk-demo.com');

INSERT INTO users (clinic_id, email, password, first_name, last_name, role) VALUES 
(1, 'admin@smiledesk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin'),
(1, 'dr.smith@smiledesk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Smith', 'dentist'),
(1, 'assistant@smiledesk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah', 'Johnson', 'assistant');

INSERT INTO service_categories (clinic_id, name, description) VALUES 
(1, 'Preventive Care', 'Regular checkups and cleanings'),
(1, 'Restorative', 'Fillings, crowns, and repairs'),
(1, 'Cosmetic', 'Whitening and aesthetic procedures'),
(1, 'Oral Surgery', 'Extractions and surgical procedures'),
(1, 'Orthodontics', 'Braces and alignment treatments');

INSERT INTO services (clinic_id, category_id, name, description, duration, price, requires_tooth_selection) VALUES 
(1, 1, 'Regular Cleaning', 'Professional dental cleaning and examination', 60, 150.00, FALSE),
(1, 1, 'Deep Cleaning', 'Scaling and root planing', 90, 300.00, FALSE),
(1, 2, 'Composite Filling', 'Tooth-colored filling', 45, 200.00, TRUE),
(1, 2, 'Dental Crown', 'Porcelain crown placement', 90, 1200.00, TRUE),
(1, 2, 'Root Canal Treatment', 'Endodontic therapy', 120, 800.00, TRUE),
(1, 4, 'Tooth Extraction', 'Simple tooth extraction', 30, 250.00, TRUE),
(1, 3, 'Teeth Whitening', 'Professional whitening treatment', 60, 400.00, FALSE),
(1, 1, 'Consultation', 'Initial examination and consultation', 30, 75.00, FALSE);

INSERT INTO patients (clinic_id, patient_number, first_name, last_name, email, phone, date_of_birth, gender) VALUES 
(1, 'P001', 'Ahmed', 'Benali', 'ahmed.benali@email.com', '+212-661-123456', '1985-06-15', 'male'),
(1, 'P002', 'Fatima', 'Alaoui', 'fatima.alaoui@email.com', '+212-662-234567', '1990-03-22', 'female'),
(1, 'P003', 'Omar', 'Tazi', 'omar.tazi@email.com', '+212-663-345678', '1978-11-08', 'male'),
(1, 'P004', 'Aicha', 'Mansouri', 'aicha.mansouri@email.com', '+212-664-456789', '1995-09-12', 'female');

-- Insert sample appointments
INSERT INTO appointments (clinic_id, patient_id, service_id, dentist_id, appointment_date, appointment_time, duration, status) VALUES 
(1, 1, 1, 2, CURDATE(), '09:00:00', 60, 'scheduled'),
(1, 2, 3, 2, CURDATE(), '10:30:00', 45, 'scheduled'),
(1, 3, 8, 2, CURDATE(), '14:00:00', 30, 'scheduled'),
(1, 4, 1, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '09:00:00', 60, 'scheduled');

-- Insert sample activities
INSERT INTO activities (clinic_id, type, title, description) VALUES 
(1, 'patient_added', 'New patient registered', 'Ahmed Benali completed registration'),
(1, 'appointment_scheduled', 'Appointment scheduled', 'Cleaning appointment for Fatima Alaoui'),
(1, 'payment_received', 'Payment received', 'Omar Tazi paid for consultation');
