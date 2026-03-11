-- --------------------------------------------------------
-- MedTrack – Complete Database Schema with Sample Data
-- --------------------------------------------------------

-- Drop database if exists (for clean import)
DROP DATABASE IF EXISTS medtrack;

-- Create database with proper character set
CREATE DATABASE medtrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE medtrack;

-- --------------------------------------------------------
-- Table: users (authentication & roles)
-- --------------------------------------------------------
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('patient','doctor','admin') DEFAULT 'patient',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: patients (extends users for patient role)
-- --------------------------------------------------------
CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NULL,
    gender ENUM('Male','Female','Other') NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    emergency_contact VARCHAR(100) NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: medical_history
-- --------------------------------------------------------
CREATE TABLE medical_history (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    illness_name VARCHAR(255) NOT NULL,
    diagnosis_date DATE NULL,
    doctor_notes TEXT NULL,
    treatment TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    INDEX idx_patient (patient_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: appointments
-- --------------------------------------------------------
CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,  -- doctor's username from users table
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason TEXT NULL,
    status ENUM('scheduled','completed','cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    INDEX idx_patient_date (patient_id, appointment_date),
    INDEX idx_doctor_date (doctor_name, appointment_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: bills
-- --------------------------------------------------------
CREATE TABLE bills (
    bill_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    vat_rate DECIMAL(5,2) DEFAULT 7.5,
    vat_amount DECIMAL(10,2) GENERATED ALWAYS AS (amount * vat_rate / 100) STORED,
    total_amount DECIMAL(10,2) GENERATED ALWAYS AS (amount + (amount * vat_rate / 100)) STORED,
    status ENUM('unpaid','paid') DEFAULT 'unpaid',
    issue_date DATE NOT NULL,
    paid_date DATE NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    INDEX idx_patient_status (patient_id, status),
    INDEX idx_paid_date (paid_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: medications
-- --------------------------------------------------------
CREATE TABLE medications (
    med_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    medication_name VARCHAR(255) NOT NULL,
    dosage VARCHAR(100) NULL,
    frequency VARCHAR(100) NULL,
    start_date DATE NULL,
    end_date DATE NULL,
    notes TEXT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    INDEX idx_patient_active (patient_id, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: notifications
-- --------------------------------------------------------
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: bp_readings (for chart demo)
-- --------------------------------------------------------
CREATE TABLE bp_readings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reading_date DATE NOT NULL,
    systolic INT NOT NULL,
    diastolic INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, reading_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample Data
-- --------------------------------------------------------

-- Passwords: all sample users have password = 'password'
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT INTO users (username, email, password_hash, role) VALUES
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient'),
('jane_patient', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient'),
('dr_smith', 'smith@clinic.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor'),
('dr_ndlovu', 'ndlovu@clinic.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor');

-- Patient profiles
INSERT INTO patients (user_id, full_name, date_of_birth, gender, phone, address, emergency_contact) VALUES
(1, 'John Doe', '1990-05-15', 'Male', '+27791234567', '123 Main St, Johannesburg', 'Jane Doe: 0821234567'),
(2, 'Jane Smith', '1985-08-22', 'Female', '+27831239876', '456 Oak Ave, Pretoria', 'John Smith: 0839876543');

-- Medical history for John (patient_id=1)
INSERT INTO medical_history (patient_id, illness_name, diagnosis_date, doctor_notes, treatment) VALUES
(1, 'Hypertension', '2024-01-10', 'BP slightly high. Prescribed lifestyle changes.', 'Reduce salt, exercise'),
(1, 'Seasonal Flu', '2024-03-20', 'Fever and cough.', 'Rest, paracetamol, fluids');

-- Medical history for Jane (patient_id=2)
INSERT INTO medical_history (patient_id, illness_name, diagnosis_date, doctor_notes, treatment) VALUES
(2, 'Asthma', '2023-11-05', 'Mild persistent asthma.', 'Inhaler as needed, avoid triggers'),
(2, 'Migraine', '2024-02-14', 'Frequent migraines.', 'Prescribed sumatriptan, avoid bright lights');

-- Appointments
INSERT INTO appointments (patient_id, doctor_name, appointment_date, appointment_time, reason, status) VALUES
(1, 'dr_smith', '2025-04-15', '10:30:00', 'Annual checkup', 'scheduled'),
(1, 'dr_smith', '2025-03-28', '14:15:00', 'Follow-up on BP', 'completed'),
(2, 'dr_ndlovu', '2025-04-10', '09:00:00', 'Asthma review', 'scheduled'),
(2, 'dr_ndlovu', '2025-02-20', '11:30:00', 'Migraine consultation', 'completed');

-- Bills
INSERT INTO bills (patient_id, amount, issue_date, status) VALUES
(1, 450.00, '2025-03-01', 'unpaid'),
(1, 1200.50, '2025-02-15', 'paid'),
(1, 75.00, '2025-04-01', 'unpaid'),
(2, 320.00, '2025-03-10', 'unpaid'),
(2, 890.00, '2025-01-25', 'paid');

-- Medications
INSERT INTO medications (patient_id, medication_name, dosage, frequency, start_date, end_date, notes) VALUES
(1, 'Lisinopril', '10mg', 'once daily', '2025-01-01', '2025-06-01', 'Take in the morning'),
(1, 'Ibuprofen', '400mg', 'as needed', '2025-03-15', '2025-04-15', 'For pain, max 3x/day'),
(2, 'Salbutamol Inhaler', '100mcg', 'as needed', '2023-11-05', NULL, 'Use for shortness of breath'),
(2, 'Sumatriptan', '50mg', 'at onset', '2024-02-14', NULL, 'Take at first sign of migraine');

-- Notifications
INSERT INTO notifications (user_id, message, is_read) VALUES
(1, 'Your appointment with Dr. Smith is tomorrow at 10:30.', 0),
(1, 'You have an unpaid bill of R450.00 due.', 0),
(2, 'Dr. Ndlovu confirmed your appointment on 2025-04-10.', 1),
(3, 'New patient John Doe added to your list.', 0);

-- Blood pressure readings for John (user_id=1)
INSERT INTO bp_readings (user_id, reading_date, systolic, diastolic) VALUES
(1, '2025-03-01', 120, 80),
(1, '2025-03-08', 122, 81),
(1, '2025-03-15', 118, 79),
(1, '2025-03-22', 125, 82),
(1, '2025-03-29', 121, 80),
(1, '2025-04-05', 119, 78);

-- Blood pressure readings for Jane (user_id=2)
INSERT INTO bp_readings (user_id, reading_date, systolic, diastolic) VALUES
(2, '2025-03-02', 115, 75),
(2, '2025-03-09', 117, 76),
(2, '2025-03-16', 113, 74),
(2, '2025-03-23', 118, 77),
(2, '2025-03-30', 116, 75),
(2, '2025-04-06', 114, 73);

-- --------------------------------------------------------
-- Additional Indexes for Performance
-- --------------------------------------------------------
CREATE INDEX idx_appointments_doctor_date ON appointments(doctor_name, appointment_date);
CREATE INDEX idx_medical_history_diagnosis ON medical_history(diagnosis_date);
CREATE INDEX idx_medications_end_date ON medications(end_date);

-- --------------------------------------------------------
-- Optional: Create a view for easy patient summary
-- --------------------------------------------------------
CREATE VIEW patient_summary AS
SELECT 
    p.patient_id,
    u.username,
    p.full_name,
    p.date_of_birth,
    p.phone,
    (SELECT COUNT(*) FROM appointments a WHERE a.patient_id = p.patient_id AND a.status = 'scheduled') AS upcoming_appointments,
    (SELECT COUNT(*) FROM bills b WHERE b.patient_id = p.patient_id AND b.status = 'unpaid') AS unpaid_bills,
    (SELECT COUNT(*) FROM medications m WHERE m.patient_id = p.patient_id AND (m.end_date IS NULL OR m.end_date >= CURDATE())) AS active_meds
FROM patients p
JOIN users u ON p.user_id = u.user_id;

-- --------------------------------------------------------
-- End of Schema
-- --------------------------------------------------------