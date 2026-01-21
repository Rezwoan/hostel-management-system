# Hostel Management System

A comprehensive web-based hostel management solution built with PHP and MySQL, featuring role-based access control for administrators, hostel managers, and students. The system streamlines hostel operations including room allocation, fee management, complaint handling, and communication.

🔗 **Live Demo:** [hostel-management-system.rezwoan.me](https://hostel-management-system.rezwoan.me)

---

## Features

### Admin Panel
- **User Management** – Create and manage admin, manager, and student accounts
- **Hostel Configuration** – Set up hostels, floors, room types, rooms, and seats
- **Manager Assignment** – Assign managers to specific hostels
- **Fee Period Management** – Define billing periods and fee structures
- **Invoice & Payment Tracking** – Monitor all financial transactions
- **Complaint Categories** – Configure complaint types for the system
- **Audit Logs** – Track all system activities with detailed logs
- **Login Activity Monitoring** – View user login history and sessions
- **Notice Board** – Publish announcements for students and managers

### Manager Panel
- **Hostel Overview** – View assigned hostel details, floors, rooms, and occupancy
- **Application Processing** – Review and approve/reject student room applications
- **Room Allocation** – Assign approved students to available seats
- **Student Management** – View and manage allocated students
- **Fee & Invoice Management** – Generate invoices and record payments
- **Complaint Handling** – Respond to and resolve student complaints
- **Notice Management** – Post hostel-specific announcements

### Student Portal
- **Room Application** – Apply for hostel accommodation with hostel preference
- **Room Details** – View allocated room, seat, and roommate information
- **Fee & Invoice Tracking** – Check invoices and payment status
- **Complaint Submission** – Submit and track maintenance/service complaints
- **Notice Board** – View announcements from managers and admins
- **Profile Management** – Update personal information and profile picture
- **Password Recovery** – Reset password using email, student ID, and date of birth verification

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 8.x |
| **Database** | MySQL 8.x |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Architecture** | MVC (Model-View-Controller) |
| **Server** | Apache (XAMPP for development, cPanel for production) |
| **Deployment** | GitHub Actions CI/CD with FTP auto-deploy to cPanel |

---

## Project Structure

```
hostel-management-system/
├── app/
│   ├── Controllers/
│   │   ├── Admin/          # Admin module controllers
│   │   ├── Api/            # API endpoints for AJAX calls
│   │   ├── Auth/           # Login & signup controllers
│   │   ├── Manager/        # Manager module controllers
│   │   └── Student/        # Student module controllers
│   ├── Models/
│   │   ├── AdminModel.php      # Admin database operations
│   │   ├── AuthModel.php       # Authentication operations
│   │   ├── Database.php        # Database connection
│   │   ├── ManagerModel.php    # Manager database operations
│   │   └── StudentModel.php    # Student database operations
│   └── Views/
│       ├── Admin/          # Admin UI templates
│       ├── Auth/           # Login & signup pages
│       ├── Manager/        # Manager UI templates
│       └── Student/        # Student UI templates
├── database/
│   ├── schema.sql          # Database structure
│   ├── seed.php            # Sample data seeder
│   └── migrations/         # Database migrations
├── docs/
│   ├── CONTRIBUTING.md     # Contribution guidelines
│   └── Database ER Diagram.drawio
├── public/
│   └── assets/
│       ├── css/            # Global stylesheets
│       ├── img/            # Images
│       └── js/             # Global scripts
├── .github/
│   └── workflows/
│       └── deploy.yml      # CI/CD pipeline configuration
└── index.php               # Application entry point & router
```

---

## Installation

### Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache server (XAMPP recommended for local development)
- Git

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/Rezwoan/hostel-management-system.git
   ```

2. **Move to web server directory**
   ```bash
   # For XAMPP on Windows
   mv hostel-management-system C:/xampp/htdocs/
   
   # For XAMPP on Linux/Mac
   mv hostel-management-system /opt/lampp/htdocs/
   ```

3. **Create the database**
   ```sql
   CREATE DATABASE hostel_management_system;
   ```

4. **Import the schema**
   ```bash
   mysql -u your_username -p hostel_management_system < database/schema.sql
   ```

5. **Configure database connection**
   
   Update the database credentials in `app/Models/Database.php`:
   ```php
   define("DB_HOST", "localhost");
   define("DB_USER", "your_username");
   define("DB_PASS", "your_password");
   define("DB_NAME", "hostel_management_system");
   ```

6. **Seed sample data** (Optional)
   
   Visit in browser: `http://localhost/hostel-management-system/database/seed.php`

7. **Access the application**
   
   Open: `http://localhost/hostel-management-system`

---

## Demo Credentials

All demo accounts use the password: `password123`

| Role | Email | Password |
|------|-------|----------|
| Admin | admin1@admin.hms | password123 |
| Manager | manager1@manager.hms | password123 |
| Student | din@student.hms | password123 |

---

## CI/CD Pipeline

This project uses **GitHub Actions** for continuous deployment. On every push to the `main` branch:

1. GitHub Actions triggers the deployment workflow
2. Code is automatically deployed to the production server via FTP
3. Changes are live on [hostel-management-system.rezwoan.me](https://hostel-management-system.rezwoan.me) within minutes

The workflow configuration is located at `.github/workflows/deploy.yml`.

---

## Database Schema

The system uses a relational database with the following core tables:

- `users` – All user accounts (admin, manager, student)
- `student_profiles` – Extended student information
- `hostels` – Hostel buildings
- `floors` – Floors within hostels
- `room_types` – Room categories with pricing
- `rooms` – Individual rooms
- `seats` – Beds/seats within rooms
- `room_applications` – Student accommodation requests
- `allocations` – Room/seat assignments
- `fee_periods` – Billing periods
- `invoices` – Payment invoices
- `payments` – Payment records
- `complaints` – Student complaints
- `complaint_categories` – Complaint types
- `complaint_messages` – Complaint thread messages
- `notices` – Announcements
- `audit_logs` – System activity logs
- `login_sessions` – User login tracking

Full ER diagram available at: `docs/Database ER Diagram.drawio`

---

## Security Features

- **Session Security** – Secure session configuration with HTTP-only cookies
- **Session Regeneration** – Periodic session ID regeneration to prevent fixation
- **Prepared Statements** – All database queries use PDO/MySQLi prepared statements
- **Role-Based Access Control** – Server-side role verification for all protected routes
- **Password Hashing** – Secure password storage using PHP's `password_hash()`
- **Remember Me Tokens** – Secure token-based persistent login
- **Input Validation** – Server-side validation for all user inputs
- **XSS Prevention** – Output escaping with `htmlspecialchars()`

---

## Contributing

Contributions are welcome! Please read the [Contributing Guide](docs/CONTRIBUTING.md) for details on our development workflow and code standards.

---

## License

This project is open source and available under the [MIT License](LICENSE).

---

## Acknowledgements

This project was developed as part of **CSC 3215: Web Technologies** course at American International University-Bangladesh (AIUB).

**Course Instructor:** [Md. Khairul Alam Mazumder](https://github.com/Robinak47)

---

## Authors

**Rezwoan**
- Portfolio: [rezwoan.me](https://rezwoan.me)
- GitHub: [@Rezwoan](https://github.com/Rezwoan)

**Soumik**
- GitHub: [@Soumikdas3210](https://github.com/Soumikdas3210)
