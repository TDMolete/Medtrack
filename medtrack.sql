-- Create database
CREATE DATABASE medtrack;
USE medtrack;

-- Users table (authentication)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('patient','admin') DEFAULT 'patient',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Patients profile (extends users)
CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE,
    gender ENUM('Male','Female','Other'),
    phone VARCHAR(20),
    address TEXT,
    emergency_contact VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Medical history
CREATE TABLE medical_history (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    illness_name VARCHAR(255) NOT NULL,
    diagnosis_date DATE,
    doctor_notes TEXT,
    treatment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE
);

-- Appointments
CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_name VARCHAR(100),
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason TEXT,
    status ENUM('scheduled','completed','cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE
);

-- Bills
CREATE TABLE bills (
    bill_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    vat_rate DECIMAL(5,2) DEFAULT 7.5,
    vat_amount DECIMAL(10,2) GENERATED ALWAYS AS (amount * vat_rate / 100) STORED,
    total_amount DECIMAL(10,2) GENERATED ALWAYS AS (amount + (amount * vat_rate / 100)) STORED,
    status ENUM('unpaid','paid') DEFAULT 'unpaid',
    issue_date DATE,
    paid_date DATE,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE
);

-- Medications (reminders)
CREATE TABLE medications (
    med_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    medication_name VARCHAR(255) NOT NULL,
    dosage VARCHAR(100),
    frequency VARCHAR(100),          -- e.g., "twice daily"
    start_date DATE,
    end_date DATE,
    notes TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE
);

-- Optional: Insert sample data (see "Sample Data" section)
USE medtrack;

-- Insert a test user (password = 'password')
INSERT INTO users (username, email, password_hash) VALUES ('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password is 'password'
INSERT INTO patients (user_id, full_name, date_of_birth, gender, phone, address, emergency_contact) 
VALUES (1, 'John Doe', '1990-05-15', 'Male', '+27791234567', '123 Main St, Johannesburg', 'Jane Doe: 0821234567');

-- Sample medical history
INSERT INTO medical_history (patient_id, illness_name, diagnosis_date, doctor_notes, treatment) VALUES
(1, 'Hypertension', '2024-01-10', 'BP slightly high. Prescribed lifestyle changes.', 'Reduce salt, exercise'),
(1, 'Seasonal Flu', '2024-03-20', 'Fever and cough.', 'Rest, paracetamol, fluids');

-- Sample appointments
INSERT INTO appointments (patient_id, doctor_name, appointment_date, appointment_time, reason, status) VALUES
(1, 'Dr. Smith', '2025-04-15', '10:30:00', 'Annual checkup', 'scheduled'),
(1, 'Dr. Ndlovu', '2025-03-28', '14:15:00', 'Follow-up on BP', 'completed');

-- Sample bills
INSERT INTO bills (patient_id, amount, issue_date, status) VALUES
(1, 450.00, '2025-03-01', 'unpaid'),
(1, 1200.50, '2025-02-15', 'paid'),
(1, 75.00, '2025-04-01', 'unpaid');

-- Sample medications
INSERT INTO medications (patient_id, medication_name, dosage, frequency, start_date, end_date, notes) VALUES
(1, 'Lisinopril', '10mg', 'once daily', '2025-01-01', '2025-06-01', 'Take in the morning'),
(1, 'Ibuprofen', '400mg', 'as needed', '2025-03-15', '2025-04-15', 'For pain, max 3x/day');