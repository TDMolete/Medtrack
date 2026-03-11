# MedTrack – Healthcare Management MVP

MedTrack is a lightweight healthcare management web application prototype built with PHP, MySQL, Bootstrap, and vanilla JavaScript. It demonstrates core healthcare workflows including patient registration, medical history, appointments, billing with VAT, and medication reminders. This project was developed as part of a portfolio to showcase structured thinking, secure coding practices, and MVP development methodology.

## Features

- 🔐 **Authentication** – Register/login with hashed passwords.
- 👤 **Patient Profile** – Manage personal and emergency contact info.
- 📜 **Medical History** – Add and view past illnesses, diagnoses, treatments.
- 📅 **Appointments** – Book, view, and cancel appointments.
- 💰 **Billing** – Generate bills with automatic 7.5% VAT calculation, mark as paid.
- 💊 **Medication Schedule** – Track medications with dosage and frequency.
- 🏋️ **Health Tips** – Static page with general exercise and wellness advice.
- 📊 **Dashboard** – Overview of upcoming appointments, unpaid bills, active medications.

## Tech Stack

- **Backend**: PHP (procedural with PDO)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript (vanilla)
- **Server**: Apache (XAMPP localhost)
- **Security**: Password hashing (`password_hash`), prepared statements, session validation

## Project Structure
medtrack/
├── assets/ # CSS, JS, images
├── includes/ # Reusable components (header, sidebar, footer, config)
├── auth/ # Register, login, logout
├── pages/ # All feature pages
├── index.php # (optional landing)
└── README.md

## Installation (XAMPP)

1. Clone or download this repository into `C:\xampp\htdocs\medtrack`.
2. Start Apache and MySQL from XAMPP control panel.
3. Open phpMyAdmin and create a database named `medtrack`.
4. Import the SQL script (`database.sql` – included above) to create tables and optional sample data.
5. Update database credentials in `includes/config/db.php` if needed.
6. Visit `http://localhost/medtrack/auth/login.php` to start.

Default sample user: `john_doe` / `password` (if sample data imported).

## MVP Approach

This project follows the Minimum Viable Product philosophy:

- **Problem**: Small clinics and individuals lack simple digital tools to manage health records and billing.
- **MVP Scope**: Core features needed for a patient to manage their health data – authentication, profile, medical history, appointments, billing, medication reminders.
- **Future Enhancements**:
  - Admin panel for doctors/clinics.
  - Email notifications for appointments.
  - Prescription uploads.
  - Integration with external health APIs.
  - More advanced role-based access.

## Security Considerations

- Passwords are hashed using PHP's `password_hash()`.
- All database queries use prepared statements to prevent SQL injection.
- Session validation on every page.
- Input sanitization with `htmlspecialchars` on output.

## License

This project is for educational and portfolio purposes. Feel free to use, modify, and learn from it.

**Built by Tumisang David Molete** – [GitHub](https://github.com/TDMolete) · [LinkedIn](https://linkedin.com/in/tumisang-molete-273619312)