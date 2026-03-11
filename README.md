# MedTrack – Healthcare Management MVP

![MedTrack Logo](assets/img/logo-placeholder.png) <!-- optional placeholder -->

MedTrack is a lightweight, secure, and modular healthcare management prototype built with PHP, MySQL, Bootstrap, and vanilla JavaScript. It follows the **Minimum Viable Product (MVP)** philosophy – delivering core features for patients and doctors while maintaining a clean, extensible architecture.

This project was developed for academic and portfolio purposes, showcasing structured thinking, secure coding practices, and a focus on user experience.

---

## ✨ Key Features

### For Patients
- 🔐 **Secure Authentication** – Registration, login, logout with password hashing and CSRF protection.
- 👤 **Profile Management** – Edit personal details, emergency contact.
- 📋 **Medical History** – Add, view, export (PDF/CSV) past illnesses and treatments.
- 📅 **Appointments** – Book, view, and cancel appointments (AJAX‑powered).
- 💰 **Billing** – Automatic 7.5% VAT calculation, payment tracking, invoice history.
- 💊 **Medication Reminders** – Track medications, dosages, schedules.
- 📊 **Dashboard** – Quick overview of upcoming appointments, unpaid bills, active medications.
- 📈 **Health Trends** – Blood pressure chart (Chart.js) with data from API.
- 🌗 **Dark Mode** – Toggle between light and dark themes (persisted in localStorage).

### For Doctors
- 👨‍⚕️ **Doctor Dashboard** – See today's appointments, total patients, upcoming visits.
- 📋 **Patient List** – View all patients who have booked with you, with last visit date.
- 📅 **Appointment Management** – Filter upcoming/all appointments.
- 📜 **Patient History** – View full medical and appointment history for a patient.

### General
- 🔄 **Multiple Layouts** – Choose between sidebar, top navigation, or compact mode.
- 🔔 **Notifications** – Real‑time notification badge for unread alerts.
- 📤 **Export** – Export tables to PDF (jsPDF) or CSV.
- 🛡️ **Security** – Prepared statements, CSRF tokens, rate limiting (login attempts), session regeneration.
- 📱 **Responsive** – Works on desktop, tablet, and mobile.

---

## 🛠️ Tech Stack

| Layer          | Technology                                    |
|----------------|-----------------------------------------------|
| Frontend       | HTML5, CSS3, Bootstrap 5, JavaScript (ES6)   |
| Backend        | PHP 8+ (procedural with modular includes)    |
| Database       | MySQL (InnoDB, utf8mb4)                       |
| Libraries      | Chart.js, jsPDF, jsPDF‑autotable, FontAwesome |
| Server         | Apache (XAMPP / WAMP / LAMP)                  |
| Version Control| Git                                           |

---

## 📁 Folder Structure
medtrack/
│
├── assets/
│ ├── css/
│ │ ├── style.css # Base styles, variables, cards, tables
│ │ ├── dark.css # Dark mode overrides
│ │ └── layouts.css # Layout‑specific styles
│ ├── js/
│ │ ├── theme.js # Dark mode toggle
│ │ ├── ajax.js # Appointment booking, bill payment
│ │ ├── charts.js # Chart.js initialisation
│ │ ├── export.js # PDF / CSV export
│ │ └── script.js # General utilities
│ └── img/ # (optional) images
│
├── includes/
│ ├── config/
│ │ └── db.php # PDO database connection
│ ├── layout/
│ │ ├── layout_selector.php
│ │ ├── sidebar_layout.php
│ │ ├── topnav_layout.php
│ │ ├── compact_layout.php
│ │ ├── patient_sidebar.php
│ │ └── doctor_sidebar.php
│ ├── header.php # Common <head> and opening <body>
│ ├── footer.php # Scripts and closing tags
│ ├── functions.php # Helper functions (auth, CSRF, IDs)
│ └── breadcrumbs.php # Dynamic breadcrumb trail
│
├── auth/
│ ├── login.php # Login with rate limiting
│ ├── register.php # Registration with transaction
│ └── logout.php # Session destroy
│
├── pages/
│ ├── patient/ # Patient pages (single file each)
│ │ ├── dashboard.php
│ │ ├── profile.php
│ │ ├── medical_history.php
│ │ ├── appointments.php
│ │ ├── billing.php
│ │ ├── medications.php
│ │ └── recommendations.php
│ └── doctor/ # Doctor pages
│ ├── dashboard.php
│ ├── patient_list.php
│ ├── appointments.php
│ └── history.php
│
├── api/ # JSON/HTML endpoints
│ ├── appointments.php
│ ├── charts_data.php
│ ├── notifications.php
│ └── pay_bill.php
│
├── medtrack.sql # Full database dump (schema + sample data)
├── index.php # Landing page / redirect
├── unauthorized.php # 403 access denied
└── README.md # You are here

## 🗄️ Database Schema

![ER Diagram](assets/img/er-diagram-placeholder.png) <!-- optional diagram -->

### Tables

| Table           | Description                                 |
|-----------------|---------------------------------------------|
| `users`         | Authentication, roles (patient/doctor)      |
| `patients`      | Patient profile data (extends users)        |
| `doctors`       | (optional) – currently doctor info stored in `users` with role |
| `medical_history`| Patient illnesses, diagnoses, treatments   |
| `appointments`  | Appointments with doctor, date, time, status |
| `bills`         | Invoices with VAT calculation               |
| `medications`   | Prescribed medications, dosages, schedules  |
| `notifications` | System alerts for users                     |
| `bp_readings`   | Blood pressure readings (for charts)        |

### Key Relationships
- `patients.user_id` → `users.user_id`
- `medical_history.patient_id` → `patients.patient_id`
- `appointments.patient_id` → `patients.patient_id`
- `bills.patient_id` → `patients.patient_id`
- `medications.patient_id` → `patients.patient_id`
- `notifications.user_id` → `users.user_id`
- `bp_readings.user_id` → `users.user_id`

## 🚀 Installation Guide (XAMPP)

1. **Download / Clone** this repository into your XAMPP `htdocs` folder:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/TDMolete/medtrack.git
   Start Apache and MySQL from the XAMPP control panel.

Create the database:

Open phpMyAdmin (http://localhost/phpmyadmin).

Create a new database named medtrack.

Import the provided medtrack.sql file.

Configure database connection:

Edit includes/config/db.php:

php
$host = 'localhost';
$dbname = 'medtrack';
$username = 'root';      // default XAMPP user
$password = '';          // default XAMPP password (empty)
Set base path (if needed):

If your project is not at the server root, adjust the base URLs in all files (e.g., /medtrack/).

For local development with http://localhost/medtrack/, all paths are already set to /medtrack/.

Access the application:

Open your browser and go to http://localhost/medtrack/.

Login with sample users:

Patient: john_doe / password

Doctor: dr_smith / password

🔧 Configuration
Dark Mode: Toggle from the user dropdown – preference saved in localStorage.

Layout Switching: Use the buttons on the dashboard to change layout (sidebar/topnav/compact).

Notification Polling: The app checks for new notifications every 30 seconds (AJAX).

🌐 API Endpoints
All endpoints return JSON (except appointments.php GET which returns HTML).

Endpoint	Method	Description	Auth Required
/api/appointments.php	GET	Returns HTML list of upcoming appointments (dashboard)	✅ (patient)
/api/appointments.php	POST	Books a new appointment (expects form data)	✅ (patient)
/api/charts_data.php	GET	Returns BP readings as JSON for Chart.js	✅ (patient)
/api/notifications.php	GET	Returns unread notification count	✅ (any)
/api/pay_bill.php	POST	Marks a bill as paid (requires CSRF token)	✅ (patient)
🛡️ Security Features
Password Hashing: password_hash() with default algorithm.

Prepared Statements: All database queries use PDO prepared statements (prevents SQL injection).

CSRF Protection: Tokens generated and validated on all state‑changing forms.

Session Management: Session regeneration after login, secure logout.

Rate Limiting: 5 failed login attempts → 15‑minute lockout.

Input Validation: Email format, password strength, required fields.

Role‑Based Access: Patients cannot access doctor pages, and vice versa.

🧪 Testing Credentials
Role	Username	Password	Notes
Patient	john_doe	password	Sample patient with full data
Doctor	dr_smith	password	Sample doctor
📸 Screenshots
Patient Dashboard	Appointments
https://assets/img/screenshot-dashboard.png	https://assets/img/screenshot-appointments.png
(Replace with actual screenshots – place in assets/img/)

🔮 Future Enhancements
Email / SMS appointment reminders.

Prescription uploads (file attachments).

Admin panel for clinic management.

Integration with external health APIs (e.g., fitness trackers).

Two‑factor authentication.

More advanced charting (multiple vitals).

👨‍💻 Author
Tumisang David Molete

GitHub

LinkedIn

Email: tumisangmolete322@gmail.com

📄 License
This project is for educational and portfolio purposes only. It is not intended for clinical or production use. Feel free to use, modify, and learn from it.

---

## 🗄️ `medtrack.sql` (Complete Database Dump)

```sql
-- --------------------------------------------------------
-- MedTrack – Full Database Schema with Sample Data
-- --------------------------------------------------------

-- Drop database if exists (use with caution in development)
DROP DATABASE IF EXISTS medtrack;
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
    doctor_name VARCHAR(100) NOT NULL,  -- simplified: doctor's name from users.username
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason TEXT NULL,
    status ENUM('scheduled','completed','cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    INDEX idx_patient_date (patient_id, appointment_date),
    INDEX idx_doctor_date (doctor_name, appointment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: bills
-- --------------------------------------------------------
CREATE TABLE bills (
    bill_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    vat_rate DECIMAL(5,2) DEFAULT 7.5,
    vat_amount DECIMAL(10,2) AS (amount * vat_rate / 100) STORED,
    total_amount DECIMAL(10,2) AS (amount + (amount * vat_rate / 100)) STORED,
    status ENUM('unpaid','paid') DEFAULT 'unpaid',
    issue_date DATE NOT NULL,
    paid_date DATE NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    INDEX idx_patient_status (patient_id, status)
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
    INDEX idx_user_read (user_id, is_read)
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

-- Passwords are 'password' (hashed with password_hash())
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

-- Appointments (patient_id 1 with Dr. Smith, patient_id 2 with Dr. Ndlovu)
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

-- Notifications (sample)
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
-- Optional: Create indexes for performance
-- --------------------------------------------------------
CREATE INDEX idx_appointments_doctor_date ON appointments(doctor_name, appointment_date);
CREATE INDEX idx_bills_paid ON bills(paid_date);
CREATE INDEX idx_medications_end ON medications(end_date);