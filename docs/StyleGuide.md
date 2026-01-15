# 🎨 HOSTEL MANAGEMENT SYSTEM - Style Guide

> **Purpose:** This document defines the coding standards, design system, and architecture rules for all contributors working on this project. Follow these guidelines strictly to maintain consistency.

---

## 📁 Project Structure

```
hostel-management-system/
├── index.php                    # Single entry point (Router)
├── app/
│   ├── Controllers/             # Business logic, validation, routing
│   │   ├── Admin/
│   │   ├── Auth/
│   │   ├── Manager/
│   │   └── Student/
│   ├── Models/                  # Database operations only
│   │   └── DB_Connect.php
│   └── Views/                   # HTML templates
│       ├── Admin/
│       │   ├── css/             # View-specific styles
│       │   └── js/              # View-specific scripts
│       ├── Auth/
│       │   ├── css/
│       │   │   ├── login_view.css
│       │   │   └── signup_view.css
│       │   └── js/
│       │       ├── login_view.js
│       │       └── signup_view.js
│       ├── Manager/
│       └── Student/
├── config/
│   └── config.php               # Database credentials
├── docs/
│   └── StyleGuide.md            # This file
└── public/
    └── assets/
        ├── css/
        │   └── style.css        # BASE styles only (global)
        ├── img/
        └── js/
```

---

## 1. 🎨 Design System

### Theme
**Professional, Academic, Clean.** No animations, gradients, or complex effects.

### Fonts
Use the system font stack (no external font imports):
```css
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
```

### Color Palette

| Purpose | Color | Hex Code |
|---------|-------|----------|
| **Primary** | Solid Blue | `#0056b3` |
| **Primary Hover** | Dark Blue | `#004494` |
| **Background** | Light Blue-Grey | `#f4f6f9` |
| **Surface** | White | `#ffffff` |
| **Text Primary** | Dark Gray | `#333333` |
| **Text Secondary** | Medium Gray | `#555555` |
| **Border** | Light Gray | `#dee2e6` |
| **Success** | Green | `#28a745` |
| **Error** | Red | `#dc3545` |
| **Warning** | Yellow | `#ffc107` |

### CSS Variables (Defined in style.css)
```css
:root {
    --primary: #0056b3;
    --primary-hover: #004494;
    --background: #f4f6f9;
    --surface: #ffffff;
    --text-primary: #333333;
    --text-secondary: #555555;
    --border: #dee2e6;
    --success: #28a745;
    --error: #dc3545;
    --warning: #ffc107;
    --shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
```

### Styling Rules
- **Layout:** Use simple Flexbox for alignment. Avoid complex CSS Grid.
- **Visuals:** Flat design. No gradients. Minimal box-shadow.
- **Interactions:** Simple color changes on hover. No transitions or animations.
- **Responsiveness:** Basic media queries to stack elements on mobile.

---

## 2. 📄 CSS File Organization

### Base Styles (`public/assets/css/style.css`)
Contains **ONLY** global styles that apply across the entire site:
- CSS Reset
- CSS Variables
- Typography (h1-h6, p, a)
- Base form controls (.form-group, .form-control)
- Base buttons (.btn, .btn-primary, etc.)
- Base alerts (.alert, .alert-success, etc.)
- Base cards (.card, .card-header)
- Navbar styles
- Table styles
- Badge styles
- Base responsive breakpoints

### View-Specific Styles (`app/Views/{Module}/css/{view_name}.css`)
Each view should have its own CSS file with the same name as the view:
```
app/Views/Auth/css/login_view.css      # For login_view.php
app/Views/Auth/css/signup_view.css     # For signup_view.php
app/Views/Admin/css/dashboard.css      # For dashboard.php
```

### Including Styles in Views
Always include base styles first, then view-specific:
```html
<!-- Base Styles -->
<link rel="stylesheet" href="public/assets/css/style.css">
<!-- View Specific Styles -->
<link rel="stylesheet" href="app/Views/Auth/css/login_view.css">
```

---

## 3. 📜 JavaScript Organization

### View-Specific Scripts (`app/Views/{Module}/js/{view_name}.js`)
Each view can have its own JS file with the same name as the view:
```
app/Views/Auth/js/login_view.js      # For login_view.php
app/Views/Auth/js/signup_view.js     # For signup_view.php
```

### Including Scripts in Views
Add scripts at the end of the body:
```html
    <!-- View Specific Scripts -->
    <script src="app/Views/Auth/js/login_view.js"></script>
</body>
</html>
```

---

## 4. 🛠️ Code Architecture (Procedural MVC)

### Framework
- Pure PHP 8.x (No frameworks like Laravel)
- MySQLi with Prepared Statements

### Routing
- **Single Entry Point:** `index.php` handles all requests
- **Links:** All internal links must use: `index.php?page=page_name`
- **Redirects:** `header("Location: index.php?page=target_page");`

### Controllers (`app/Controllers/`)
**Responsibilities:**
- Handle form data cleaning and validation
- Call Model functions
- Load Views with data
- Manage redirects

**Rules:**
- DO NOT echo HTML directly (only load Views)
- DO NOT start session if `index.php` already did (check `session_status()`)
- Use `require_once` with `__DIR__` for includes

```php
// Example Controller
<?php
require_once __DIR__ . '/../../Models/auth_functions.php';

// Validate input
$email = trim($_POST['email'] ?? '');
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_msg = "Invalid email address.";
}

// Call model and load view
require_once __DIR__ . '/../../Views/Auth/login_view.php';
```

### Models (`app/Models/`)
**Responsibilities:**
- Database connections
- Execute SQL queries with prepared statements
- Return data to Controllers

**Rules:**
- DO NOT access `$_POST` or `$_SESSION` directly
- Accept data as function arguments only
- Always use prepared statements

```php
// Example Model Function
function getUserByEmail($conn, $email) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}
```

### Views (`app/Views/`)
**Responsibilities:**
- Pure HTML structure
- Link to external CSS/JS
- Display data passed from Controller

**Rules:**
- DO NOT write complex PHP logic
- Use variables passed from Controller
- All links must use router format

```php
<!-- Example View: example_view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Title</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Module/css/example_view.css">
</head>
<body>
    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>
    
    <a href="index.php?page=other_page">Link Text</a>
    
    <script src="app/Views/Module/js/example_view.js"></script>
</body>
</html>
```

---

## 5. 🛡️ Security & Validation

### Input Validation
- Never trust user input
- Use `trim()` and `empty()` checks in Controller
- Validate emails with `filter_var($email, FILTER_VALIDATE_EMAIL)`

### Student ID Validation (XX-XXXXX-X)
Use `explode()` and `is_numeric()` - **DO NOT use Regex**:
```php
function validateStudentId($student_id) {
    $parts = explode('-', $student_id);
    
    if (count($parts) !== 3) {
        return false;
    }
    
    // Part 1: 2 digits
    if (strlen($parts[0]) !== 2 || !is_numeric($parts[0])) {
        return false;
    }
    
    // Part 2: 5 digits
    if (strlen($parts[1]) !== 5 || !is_numeric($parts[1])) {
        return false;
    }
    
    // Part 3: 1 digit
    if (strlen($parts[2]) !== 1 || !is_numeric($parts[2])) {
        return false;
    }
    
    return true;
}
```

### Password Security
```php
// Hashing (on registration)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Verifying (on login)
if (password_verify($input_password, $stored_hash)) {
    // Password correct
}
```

### SQL Security
Always use prepared statements:
```php
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? AND status = ?");
mysqli_stmt_bind_param($stmt, "ss", $email, $status);
mysqli_stmt_execute($stmt);
```

---

## 6. 📝 Naming Conventions

### Files
- Controllers: `loginController.php`, `AdminController.php`
- Models: `auth_functions.php`, `student_functions.php`
- Views: `login_view.php`, `dashboard.php`
- CSS: `login_view.css`, `dashboard.css` (same name as view file)
- JS: `login_view.js`, `dashboard.js` (same name as view file)

### Variables
- Use snake_case: `$student_id`, `$error_msg`, `$user_data`
- Be descriptive: `$is_valid`, `$total_rooms`, `$current_user`

### Functions
- Use snake_case: `get_user_by_email()`, `validate_student_id()`
- Start with verb: `create_`, `get_`, `update_`, `delete_`, `validate_`

### CSS Classes
- Use lowercase with hyphens: `.form-group`, `.btn-primary`, `.auth-card`
- Be semantic: `.login-form`, `.user-profile`, `.room-list`

---

## 7. ✅ Checklist for New Views

When creating a new view, ensure:

- [ ] Base stylesheet is included first
- [ ] View-specific CSS file created in `app/Views/{Module}/css/`
- [ ] View-specific JS file created if needed in `app/Views/{Module}/js/`
- [ ] All links use router format `index.php?page=page_name`
- [ ] All user output uses `htmlspecialchars()`
- [ ] Form uses `method="POST"` and `action=""`
- [ ] Required fields marked with `<span class="required">*</span>`
- [ ] Alert messages use `.alert-success` or `.alert-error` classes
- [ ] Responsive design tested on mobile

---

## 8. 📋 CSS Class Reference

### Layout
```css
.card              /* White box with border and shadow */
.card-header       /* Centered header with bottom border */
```

### Forms
```css
.form-group        /* Wrapper for label + input */
.form-control      /* Input, select, textarea styling */
.form-hint         /* Small helper text below input */
.form-row          /* Two-column layout for form fields */
.form-section      /* Grouped section with bottom border */
```

### Buttons
```css
.btn               /* Base button */
.btn-primary       /* Blue button */
.btn-secondary     /* Gray button */
.btn-success       /* Green button */
.btn-danger        /* Red button */
.btn-block         /* Full width */
.btn-lg            /* Large size */
```

### Alerts
```css
.alert             /* Base alert */
.alert-success     /* Green success message */
.alert-error       /* Red error message */
.alert-warning     /* Yellow warning message */
.alert-info        /* Blue info message */
```

### Tables
```css
.table             /* Base table styling */
.table-responsive  /* Horizontal scroll wrapper */
```

### Badges
```css
.badge             /* Base badge */
.badge-success     /* Green */
.badge-warning     /* Yellow */
.badge-danger      /* Red */
.badge-info        /* Blue */
```

---

## 9. 🚀 Master Prompt for AI Assistants

Copy and paste this prompt when starting a new chat session to work on this project:

```
# 🚀 PROJECT CONTEXT: HOSTEL MANAGEMENT SYSTEM (PHP/MVC)

You are working on a **Hostel Management System** - a student semester project.
The goal is a **clean, functional, and organized** project. The design should be pleasant and usable but **static and simple** (no animations, gradients, or complex effects).

---

### 1. 🎨 DESIGN SYSTEM

**Theme:** Professional, Academic, Clean.
**Fonts:** System stack: `'Segoe UI', Tahoma, Geneva, Verdana, sans-serif`

**Color Palette:**
- **Primary:** `#0056b3` (Solid Blue) → Navbar, Primary Buttons
- **Background:** `#f4f6f9` (Light Blue-Grey) → Page Body
- **Surface:** `#ffffff` (White) → Cards, Forms, Sidebar
- **Text:** `#333333` (Dark Gray) for body, `#555555` for secondary text
- **Borders:** `#dee2e6` (Light Gray)
- **Status:** Success `#28a745` | Error `#dc3545` | Warning `#ffc107`

**Styling Rules:**
- Layout: Simple Flexbox. No complex CSS Grid.
- Visuals: Flat design. No gradients. Minimal box-shadow (`0 2px 4px rgba(0,0,0,0.1)`).
- Interactions: Simple color changes on hover. No transitions or animations.

---

### 2. 📄 CSS FILE ORGANIZATION

**Base Styles:** `public/assets/css/style.css`
- Contains ONLY global styles (reset, variables, typography, base components)

**View-Specific Styles:** `app/Views/{Module}/css/{view_name}.css`
- Each view has its own CSS file with the SAME NAME as the view file
- Example: `login_view.php` → `login_view.css`

**View-Specific Scripts:** `app/Views/{Module}/js/{view_name}.js`
- Each view can have its own JS file with the SAME NAME as the view file
- Example: `login_view.php` → `login_view.js`

**Include Order in Views:**
```html
<link rel="stylesheet" href="public/assets/css/style.css">
<link rel="stylesheet" href="app/Views/Module/css/view_name.css">
...
<script src="app/Views/Module/js/view_name.js"></script>
```

---

### 3. 🛠️ CODE ARCHITECTURE (Procedural MVC)

**Framework:** Pure PHP 8.x (No frameworks)
**Database:** MySQLi with Prepared Statements

**Routing:**
- Single Entry Point: `index.php`
- Links: `index.php?page=page_name`
- Redirects: `header("Location: index.php?page=target_page");`

**Directory Rules:**
- **Controllers:** Validate inputs, call Models, load Views. No HTML output.
- **Models:** DB queries only. Accept data as function arguments. No `$_POST`/`$_SESSION`.
- **Views:** Pure HTML. Link to external CSS/JS. Use router format for links.

---

### 4. 🛡️ VALIDATION & SECURITY

- **Email:** `filter_var($email, FILTER_VALIDATE_EMAIL)`
- **Student ID (XX-XXXXX-X):** Use `explode()` and `is_numeric()`. NO Regex.
- **Passwords:** `password_hash()` and `password_verify()`
- **SQL:** Always use `mysqli_prepare()` and `bind_param()`

---

**⚠️ IMPORTANT:** 
- Keep HTML, CSS, and PHP separate
- Do NOT delete files unless explicitly asked
- Create view-specific css/js folders for each module
- Follow the existing project structure
```

---

**Last Updated:** January 2026  
**Maintainer:** Project Team
