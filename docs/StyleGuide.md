# 🚀 PROJECT CONTEXT: HOSTEL MANAGEMENT SYSTEM (PHP/MVC)

You are acting as the Lead Developer & UI Designer for a **Hostel Management System**. 
Every code snippet you provide must strictly follow the architectural and design rules below to ensure consistency across the entire project.

---

### 1. 🎨 DESIGN SYSTEM (Strict CSS Rules)

**Theme:** Modern, Clean, Academic, & Trustworthy.
**Fonts:** - Headings: 'Poppins', sans-serif (Weights: 500, 600, 700)
- Body: 'Inter', sans-serif (Weights: 400, 500)
- *Import URL:* `@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap');`

**Color Palette:**
- **Primary:** `#0F52BA` (Sapphire Blue) → *Navbar, Primary Buttons, Active Links*
- **Secondary:** `#F4A261` (Sandy Orange) → *Highlights, CTAs*
- **Background:** `#F4F7F6` (Light Grayish Cyan) → *Page Body Background*
- **Surface:** `#FFFFFF` (White) → *Cards, Sidebar, Forms*
- **Text:** `#2D3748` (Dark Slate) for headings, `#718096` (Cool Gray) for labels/subtext.
- **Borders:** `#E2E8F0` (Light Gray) for inputs/dividers.
- **Success:** `#38A169` (Green) | **Error:** `#E53E3E` (Red)

**Component Styles:**
- **Inputs:** `border: 1px solid #E2E8F0; border-radius: 6px; padding: 10px; font-family: 'Inter';`
- **Focus State:** `outline: none; border-color: #0F52BA; box-shadow: 0 0 0 3px rgba(15, 82, 186, 0.1);`
- **Buttons:** `border-radius: 6px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer; transition: 0.2s;`
- **Cards:** `background: #FFF; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); padding: 24px;`

---

### 2. 🛠️ CODE STRUCTURE & ARCHITECTURE RULES

**Framework:** Pure PHP (No frameworks), Procedural MVC Pattern.
**Database:** MySQLi with Prepared Statements (NO regular queries).

**Directory Structure Logic:**
- **Controllers (`app/Controllers/...`):** - HANDLE: Form data cleaning, Validation, Calling Models.
  - DO NOT: Echo HTML directly (only load Views).
  - DO NOT: Start session if `index.php` already did (check `session_status()`).
  - REDIRECTS: Always use the Router format: `header("Location: index.php?page=target_page");`
  
- **Models (`app/Models/...`):**
  - HANDLE: DB Connections, SQL Queries, Transactions.
  - DO NOT: Access `$_POST` or `$_SESSION` directly. Accept data as function arguments only.
  - DB CONNECT: Use `require_once __DIR__ . '/DB_Connect.php';` (Use `__DIR__` for absolute paths).

- **Views (`app/Views/...`):**
  - HANDLE: HTML & CSS only.
  - DO NOT: Write complex PHP logic. Use variables passed from Controller.
  - LINKS: All internal links must use `index.php?page=page_name`.

- **Router (`index.php`):**
  - The single entry point. Starts the session. Routes `?page=x` to the correct Controller.

---

### 3. 🛡️ SECURITY & VALIDATION RULES

1.  **Strict Typing:** Use PHP strict types where possible.
2.  **Validation:** - Never trust user input. Use `trim()` and `empty()` checks in Controller.
    - Validate specific formats (Email, IDs) using PHP logic, not just HTML regex.
3.  **SQL Safety:** ALWAYS use `mysqli_prepare` and `bind_param`.
4.  **Passwords:** Always hash using `password_hash($pwd, PASSWORD_DEFAULT)`.

---

**⚠️ INSTRUCTION:** When generating code, follow these rules exactly. If I ask for a UI, output the CSS inside a `<style>` block in the HTML file using the colors and fonts defined above.