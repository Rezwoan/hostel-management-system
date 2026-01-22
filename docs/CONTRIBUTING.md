# Contributing to Hostel Management System

Thank you for considering contributing to this project! This guide will help you get started.

## Getting Started

### Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- XAMPP (for local development) or any PHP-compatible server
- Git

### Local Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/Rezwoan/hostel-management-system.git
   ```

2. Navigate to the project directory:
   ```bash
   cd hostel-management-system
   ```

3. Update the database credentials in `app/Models/Database.php`.

4. Import the database schema:
   ```bash
   mysql -u your_username -p your_database < database/schema.sql
   ```

5. (Optional) Seed the database with sample data by visiting:
   ```
   http://localhost/hostel-management-system/database/seed.php
   ```

6. Start your local server and navigate to:
   ```
   http://localhost/hostel-management-system
   ```

## Development Workflow

### Branch Naming Convention

- `feature/<description>` - For new features
- `fix/<description>` - For bug fixes
- `docs/<description>` - For documentation updates

### Commit Messages

Use clear, descriptive commit messages:
- `feat: add student application form`
- `fix: resolve seat allocation conflict`
- `docs: update setup instructions`

### Pull Request Process

1. Fork the repository
2. Create a feature branch from `main`
3. Make your changes
4. Test your changes locally
5. Submit a pull request to `main`

### Code Standards

- Use meaningful variable and function names
- Add comments for complex logic
- Follow the existing code structure
- Use prepared statements for all database queries
- Validate all user inputs server-side

## Project Structure

```
hostel-management-system/
├── app/
│   ├── Controllers/    # Request handlers
│   ├── Models/         # Database operations
│   └── Views/          # UI templates
├── config/             # Configuration files
├── database/           # Schema and migrations
├── docs/               # Documentation
└── public/             # Static assets
```

## Need Help?

If you have questions or run into issues, feel free to open an issue on GitHub.
