<?php

// 1. Start Session with secure settings
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Configure session security before starting
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    
    // Set session timeout to 2 hours
    ini_set('session.gc_maxlifetime', 7200);
    
    // Set cookie lifetime (0 = until browser closes, unless "remember me" is used)
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false, // Set to true if using HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
    
    // Regenerate session ID periodically to prevent session fixation
    if (!isset($_SESSION['_created'])) {
        $_SESSION['_created'] = time();
    } elseif (time() - $_SESSION['_created'] > 1800) {
        // Regenerate session ID every 30 minutes
        session_regenerate_id(true);
        $_SESSION['_created'] = time();
    }
}

// 2. Get Requested Page (Default to login)
$page = isset($_GET['page']) ? (string)$_GET['page'] : 'login';


// ==========================================
// HELPER FUNCTIONS
// ==========================================

function is_logged_in(): bool
{
    // Checks if the user_id exists in session (set by loginController)
    return !empty($_SESSION['user_id']);
}

function has_role(string $required_role): bool
{
    // Our login logic sets a single primary 'role' in the session
    $user_role = $_SESSION['role'] ?? '';
    return strtoupper($user_role) === strtoupper($required_role);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: index.php?page=login');
        exit;
    }
}

function require_role(string $role): void
{
    require_login();
    
    // Check if the current user has the required role
    if (!has_role($role)) {
        http_response_code(403);
        echo "<h1 style='color:red; text-align:center; margin-top:20%;'>403 Forbidden</h1>";
        echo "<p style='text-align:center;'>You do not have permission to access this page.</p>";
        echo "<p style='text-align:center;'><a href='index.php?page=login'>Go Back</a></p>";
        exit;
    }
}


// ==========================================
// ROUTING LOGIC
// ==========================================

switch ($page) {

    // --- AUTHENTICATION ---
    case 'login':
        // If already logged in, redirect to correct dashboard
        if (is_logged_in() && isset($_SESSION['role'])) {
             $role = strtoupper($_SESSION['role']);
             if ($role === 'ADMIN') header('Location: index.php?page=admin_dashboard');
             elseif ($role === 'MANAGER') header('Location: index.php?page=manager_dashboard');
             elseif ($role === 'STUDENT') header('Location: index.php?page=student_dashboard');
             exit;
        }
        // Load the Controller (which loads the View)
        require_once __DIR__ . '/app/Controllers/Auth/LoginController.php';
        break;

    case 'signup':
        require_once __DIR__ . '/app/Controllers/Auth/SignupController.php';
        break;

    case 'forgot_password':
        require_once __DIR__ . '/app/Controllers/Auth/ForgotPasswordController.php';
        break;

    case 'logout':
        // Log the logout event before destroying session
        if (isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/app/Models/AdminModel.php';
            logLogoutEvent($_SESSION['user_id']);
        }
        
        // Clear remember me cookie and token
        if (isset($_COOKIE['hms_remember_token'])) {
            // Delete token from database
            require_once __DIR__ . '/app/Models/AuthModel.php';
            $token = $_COOKIE['hms_remember_token'];
            $parts = explode(':', $token);
            if (count($parts) === 2) {
                deleteRememberToken($parts[0]); // Delete by selector
            }
            // Clear the cookie
            setcookie('hms_remember_token', '', time() - 3600, '/', '', false, true);
        }
        // Destroy session and redirect to login
        session_unset();
        session_destroy();
        header('Location: index.php?page=login');
        exit;


    // --- DASHBOARDS ---
    case 'admin_dashboard':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminDashboardController.php';
        break;
    
    // --- ADMIN MODULES ---
    case 'admin_users':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminUserController.php';
        break;
        
    case 'admin_students':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminStudentProfileController.php';
        break;
        
    case 'admin_managers':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminManagerController.php';
        break;
        
    case 'admin_admins':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminAdminController.php';
        break;
        
    case 'admin_hostels':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminHostelController.php';
        break;
        
    case 'admin_hostel_managers':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminHostelManagerController.php';
        break;
        
    case 'admin_floors':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminFloorController.php';
        break;
        
    case 'admin_room_types':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminRoomTypeController.php';
        break;
        
    case 'admin_rooms':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminRoomController.php';
        break;
        
    case 'admin_seats':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminSeatController.php';
        break;
        
    case 'admin_applications':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminApplicationController.php';
        break;
        
    case 'admin_allocations':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminAllocationController.php';
        break;
        
    case 'admin_fee_periods':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminFeePeriodController.php';
        break;
        
    case 'admin_invoices':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminInvoiceController.php';
        break;
        
    case 'admin_payments':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminPaymentController.php';
        break;
        
    case 'admin_complaint_categories':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminComplaintCategoryController.php';
        break;
        
    case 'admin_complaints':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminComplaintController.php';
        break;
        
    case 'admin_notices':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminNoticeController.php';
        break;
        
    case 'admin_audit_logs':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminAuditLogController.php';
        break;
        
    case 'admin_login_activity':
        require_role('ADMIN');
        require_once __DIR__ . '/app/Controllers/Admin/AdminLoginActivityController.php';
        break;

    case 'manager_dashboard':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerDashboardController.php';
        break;
    
    case 'manager_hostels':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerHostelController.php';
        break;
    
    case 'manager_applications':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerApplicationController.php';
        break;
    
    case 'manager_allocations':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerAllocationController.php';
        break;
    
    case 'manager_complaints':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerComplaintController.php';
        break;
    
    case 'manager_students':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerStudentController.php';
        break;
    
    case 'manager_fees':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerFeeController.php';
        break;
    
    case 'manager_fee_periods':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerFeePeriodController.php';
        break;
    
    case 'manager_payments':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerPaymentController.php';
        break;
    
    case 'manager_notices':
        require_role('MANAGER');
        require_once __DIR__ . '/app/Controllers/Manager/ManagerNoticeController.php';
        break;

    case 'student_dashboard':
        require_role('STUDENT');
        require_once __DIR__ . '/app/Controllers/Student/StudentController.php';
        break;

    case 'student_applications':
        require_role('STUDENT');
        require_once __DIR__ . '/app/Controllers/Student/StudentApplicationsController.php';
        break;

    case 'student_room':
        require_role('STUDENT');
        require_once __DIR__ . '/app/Controllers/Student/StudentRoomController.php';
        break;

    case 'student_complaints':
        require_role('STUDENT');
        require_once __DIR__ . '/app/Controllers/Student/StudentComplaintsController.php';
        break;

    case 'student_notices':
        require_role('STUDENT');
        require_once __DIR__ . '/app/Controllers/Student/StudentNoticesController.php';
        break;

    case 'student_fees':
        require_role('STUDENT');
        require_once __DIR__ . '/app/Controllers/Student/StudentFeesController.php';
        break;

    case 'student_profile':
        require_role('STUDENT');
        require_once __DIR__ . '/app/Controllers/Student/StudentProfileController.php';
        break;


    // --- 404 NOT FOUND ---
    default:
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page '$page' does not exist.</p>";
        break;
}