<?php
/**
 * Database Seeder Script
 * 
 * This script will:
 * 1. Clear all existing data from the database
 * 2. Insert fresh, logically connected seed data
 * 
 * Run this script from browser: http://localhost/hostel-management-system/config/seed_database.php
 * Or from CLI: php config/seed_database.php
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials (same as Database.php)
$DB_USER = "rezwoanm_hostelManagementSystem";
$DB_PASS = "hostel-management-system";
$DB_NAME = "rezwoanm_hostel-management-system";
$DB_PORT = 3306;

// Auto-detect host
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $DB_HOST = "167.235.11.154"; // Remote server
} else {
    $DB_HOST = "localhost";
}

// Connect to database
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

echo "<pre style='font-family: monospace; background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 8px;'>\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║           HMS DATABASE SEEDER - Starting...                   ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Disable foreign key checks temporarily
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Tables to clear (in order to avoid FK issues)
$tables = [
    'audit_logs',
    'complaint_messages',
    'complaints',
    'payments',
    'student_invoices',
    'allocations',
    'room_applications',
    'notices',
    'hostel_managers',
    'seats',
    'rooms',
    'floors',
    'hostels',
    'student_profiles',
    'user_roles',
    'users',
    'fee_periods',
    'room_types',
    'complaint_categories',
    'roles'
];

echo "🗑️  Clearing existing data...\n";
foreach ($tables as $table) {
    $conn->query("DELETE FROM `$table`");
    $conn->query("ALTER TABLE `$table` AUTO_INCREMENT = 1");
    echo "   ✓ Cleared: $table\n";
}
echo "\n";

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

// ============================================================
// SEED DATA
// ============================================================

// Default password for all users: "password123"
$defaultPasswordHash = password_hash('password123', PASSWORD_DEFAULT);

echo "📝 Inserting seed data...\n\n";

// ------------------------------------------------------------
// 1. ROLES
// ------------------------------------------------------------
echo "👤 Creating roles...\n";
$roles = [
    [1, 'ADMIN'],
    [2, 'MANAGER'],
    [3, 'STUDENT']
];

foreach ($roles as $role) {
    $stmt = $conn->prepare("INSERT INTO roles (id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $role[0], $role[1]);
    $stmt->execute();
    echo "   ✓ Role: {$role[1]}\n";
}
echo "\n";

// ------------------------------------------------------------
// 2. COMPLAINT CATEGORIES
// ------------------------------------------------------------
echo "📋 Creating complaint categories...\n";
$categories = ['Maintenance', 'Cleanliness', 'Security', 'Noise', 'Plumbing', 'Electrical'];

foreach ($categories as $cat) {
    $stmt = $conn->prepare("INSERT INTO complaint_categories (name) VALUES (?)");
    $stmt->bind_param("s", $cat);
    $stmt->execute();
    echo "   ✓ Category: $cat\n";
}
echo "\n";

// ------------------------------------------------------------
// 3. ROOM TYPES
// ------------------------------------------------------------
echo "🏠 Creating room types...\n";
$roomTypes = [
    ['Single', 1, 4500.00, 'Single occupancy room with private space'],
    ['Double', 2, 3500.00, 'Double sharing room with shared amenities'],
    ['Triple', 3, 2800.00, 'Triple sharing room, budget friendly'],
    ['Quad', 4, 2200.00, 'Four person room, most economical option']
];

foreach ($roomTypes as $rt) {
    $stmt = $conn->prepare("INSERT INTO room_types (name, default_capacity, default_fee, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sids", $rt[0], $rt[1], $rt[2], $rt[3]);
    $stmt->execute();
    echo "   ✓ Room Type: {$rt[0]} (Capacity: {$rt[1]}, Fee: \${$rt[2]})\n";
}
echo "\n";

// ------------------------------------------------------------
// 4. FEE PERIODS
// ------------------------------------------------------------
echo "📅 Creating fee periods...\n";
$feePeriods = [
    ['January 2026', '2026-01-01', '2026-01-31'],
    ['February 2026', '2026-02-01', '2026-02-28'],
    ['March 2026', '2026-03-01', '2026-03-31'],
    ['Q1 2026', '2026-01-01', '2026-03-31']
];

$feePeriodIds = [];
foreach ($feePeriods as $fp) {
    $stmt = $conn->prepare("INSERT INTO fee_periods (name, start_date, end_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fp[0], $fp[1], $fp[2]);
    $stmt->execute();
    $feePeriodIds[] = $conn->insert_id;
    echo "   ✓ Fee Period: {$fp[0]} (ID: " . $conn->insert_id . ")\n";
}
echo "\n";

// ------------------------------------------------------------
// 5. USERS - Admins
// ------------------------------------------------------------
echo "👨‍💼 Creating admin users...\n";
$admins = [
    ['Super Admin', 'admin1@admin.hms', '+8801700000001'],
    ['System Admin', 'admin2@admin.hms', '+8801700000002']
];

$adminIds = [];
foreach ($admins as $admin) {
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password_hash, status) VALUES (?, ?, ?, ?, 'ACTIVE')");
    $stmt->bind_param("ssss", $admin[0], $admin[1], $admin[2], $defaultPasswordHash);
    $stmt->execute();
    $adminIds[] = $conn->insert_id;
    echo "   ✓ Admin: {$admin[0]} ({$admin[1]})\n";
}
echo "\n";

// ------------------------------------------------------------
// 6. USERS - Managers
// ------------------------------------------------------------
echo "👨‍💻 Creating manager users...\n";
$managers = [
    ['Hostel Manager Alpha', 'manager1@manager.hms', '+8801800000001'],
    ['Hostel Manager Beta', 'manager2@manager.hms', '+8801800000002'],
    ['Hostel Manager Gamma', 'manager3@manager.hms', '+8801800000003']
];

$managerIds = [];
foreach ($managers as $manager) {
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password_hash, status) VALUES (?, ?, ?, ?, 'ACTIVE')");
    $stmt->bind_param("ssss", $manager[0], $manager[1], $manager[2], $defaultPasswordHash);
    $stmt->execute();
    $managerIds[] = $conn->insert_id;
    echo "   ✓ Manager: {$manager[0]} ({$manager[1]})\n";
}
echo "\n";

// ------------------------------------------------------------
// 7. USERS - Students
// ------------------------------------------------------------
echo "🎓 Creating student users...\n";
$students = [
    ['Rahim Ahmed', 'rahim.ahmed@student.hms', '+8801900000001', 'CSE', '2024-2025', '2003-05-15', 'Dhaka, Bangladesh'],
    ['Karim Hossain', 'karim.hossain@student.hms', '+8801900000002', 'EEE', '2024-2025', '2003-08-22', 'Chittagong, Bangladesh'],
    ['Fatima Khan', 'fatima.khan@student.hms', '+8801900000003', 'BBA', '2024-2025', '2004-01-10', 'Sylhet, Bangladesh'],
    ['Nusrat Jahan', 'nusrat.jahan@student.hms', '+8801900000004', 'CSE', '2023-2024', '2002-11-30', 'Rajshahi, Bangladesh'],
    ['Arif Rahman', 'arif.rahman@student.hms', '+8801900000005', 'ME', '2024-2025', '2003-07-18', 'Khulna, Bangladesh'],
    ['Tasnim Akter', 'tasnim.akter@student.hms', '+8801900000006', 'Pharmacy', '2024-2025', '2003-03-25', 'Comilla, Bangladesh'],
    ['Sakib Hassan', 'sakib.hassan@student.hms', '+8801900000007', 'Civil', '2023-2024', '2002-09-08', 'Mymensingh, Bangladesh'],
    ['Maliha Islam', 'maliha.islam@student.hms', '+8801900000008', 'Architecture', '2024-2025', '2003-12-05', 'Barishal, Bangladesh'],
    ['Rafiq Uddin', 'rafiq.uddin@student.hms', '+8801900000009', 'CSE', '2024-2025', '2003-04-20', 'Rangpur, Bangladesh'],
    ['Sabina Yasmin', 'sabina.yasmin@student.hms', '+8801900000010', 'English', '2023-2024', '2002-06-12', 'Dhaka, Bangladesh']
];

$studentIds = [];
$studentData = [];
foreach ($students as $index => $student) {
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password_hash, status) VALUES (?, ?, ?, ?, 'ACTIVE')");
    $stmt->bind_param("ssss", $student[0], $student[1], $student[2], $defaultPasswordHash);
    $stmt->execute();
    $userId = $conn->insert_id;
    $studentIds[] = $userId;
    $studentData[$userId] = $student;
    echo "   ✓ Student: {$student[0]} ({$student[1]})\n";
}
echo "\n";

// ------------------------------------------------------------
// 8. USER ROLES
// ------------------------------------------------------------
echo "🔐 Assigning user roles...\n";

// Get role IDs from database (don't assume they are 1, 2, 3)
$roleResult = $conn->query("SELECT id, name FROM roles");
$roleIds = [];
while ($row = $roleResult->fetch_assoc()) {
    $roleIds[$row['name']] = $row['id'];
}

$adminRoleId = $roleIds['ADMIN'] ?? 1;
$managerRoleId = $roleIds['MANAGER'] ?? 2;
$studentRoleId = $roleIds['STUDENT'] ?? 3;

// Assign ADMIN role
foreach ($adminIds as $id) {
    $stmt = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $id, $adminRoleId);
    $stmt->execute();
}
echo "   ✓ Assigned ADMIN role (id: $adminRoleId) to " . count($adminIds) . " users\n";

// Assign MANAGER role
foreach ($managerIds as $id) {
    $stmt = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $id, $managerRoleId);
    $stmt->execute();
}
echo "   ✓ Assigned MANAGER role (id: $managerRoleId) to " . count($managerIds) . " users\n";

// Assign STUDENT role
foreach ($studentIds as $id) {
    $stmt = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $id, $studentRoleId);
    $stmt->execute();
}
echo "   ✓ Assigned STUDENT role (id: $studentRoleId) to " . count($studentIds) . " users\n";
echo "\n";

// ------------------------------------------------------------
// 9. STUDENT PROFILES
// ------------------------------------------------------------
echo "📄 Creating student profiles...\n";
$studentCounter = 1;
foreach ($studentIds as $userId) {
    $data = $studentData[$userId];
    $studentId = 'STU-2026-' . str_pad($studentCounter, 4, '0', STR_PAD_LEFT);
    
    $stmt = $conn->prepare("INSERT INTO student_profiles (user_id, student_id, department, session_year, dob, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $userId, $studentId, $data[3], $data[4], $data[5], $data[6]);
    $stmt->execute();
    echo "   ✓ Profile: $studentId - {$data[0]} ({$data[3]})\n";
    $studentCounter++;
}
echo "\n";

// ------------------------------------------------------------
// 10. HOSTELS
// ------------------------------------------------------------
echo "🏨 Creating hostels...\n";
$hostels = [
    ['Alpha Hostel', 'ALPHA', '123 University Road, Block A, Campus Area', 'ACTIVE'],
    ['Beta Hostel', 'BETA', '125 University Road, Block B, Campus Area', 'ACTIVE'],
    ['Gamma Hostel', 'GAMMA', '127 University Road, Block C, Campus Area', 'ACTIVE']
];

$hostelIds = [];
foreach ($hostels as $hostel) {
    $stmt = $conn->prepare("INSERT INTO hostels (name, code, address, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $hostel[0], $hostel[1], $hostel[2], $hostel[3]);
    $stmt->execute();
    $hostelIds[] = $conn->insert_id;
    echo "   ✓ Hostel: {$hostel[0]} ({$hostel[1]})\n";
}
echo "\n";

// ------------------------------------------------------------
// 11. HOSTEL MANAGERS ASSIGNMENT
// ------------------------------------------------------------
echo "👨‍💼 Assigning managers to hostels...\n";
foreach ($hostelIds as $index => $hostelId) {
    if (isset($managerIds[$index])) {
        $managerId = $managerIds[$index];
        $stmt = $conn->prepare("INSERT INTO hostel_managers (hostel_id, manager_user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $hostelId, $managerId);
        $stmt->execute();
        echo "   ✓ Manager #{$managerId} assigned to Hostel #{$hostelId}\n";
    }
}
echo "\n";

// ------------------------------------------------------------
// 12. FLOORS
// ------------------------------------------------------------
echo "🏗️  Creating floors...\n";
$floorIds = [];
$floorLabels = ['Ground Floor', 'First Floor', 'Second Floor', 'Third Floor'];

foreach ($hostelIds as $hostelId) {
    for ($floorNo = 0; $floorNo <= 3; $floorNo++) {
        $label = $floorLabels[$floorNo];
        $stmt = $conn->prepare("INSERT INTO floors (hostel_id, floor_no, label) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $hostelId, $floorNo, $label);
        $stmt->execute();
        $floorIds[$hostelId][] = $conn->insert_id;
    }
    echo "   ✓ Created 4 floors for Hostel #{$hostelId}\n";
}
echo "\n";

// ------------------------------------------------------------
// 13. ROOMS
// ------------------------------------------------------------
echo "🚪 Creating rooms...\n";
$roomIds = [];
$roomCounter = 0;

foreach ($hostelIds as $hostelId) {
    foreach ($floorIds[$hostelId] as $floorIndex => $floorId) {
        // Create 4 rooms per floor
        for ($r = 1; $r <= 4; $r++) {
            $roomNo = ($floorIndex * 100) + $r; // e.g., 001, 002, 101, 102, etc.
            $roomTypeId = (($r - 1) % 4) + 1; // Cycle through room types
            $capacity = $roomTypeId; // Capacity matches room type
            
            $stmt = $conn->prepare("INSERT INTO rooms (floor_id, room_type_id, room_no, capacity, status) VALUES (?, ?, ?, ?, 'ACTIVE')");
            $roomNoStr = str_pad($roomNo, 3, '0', STR_PAD_LEFT);
            $stmt->bind_param("iisi", $floorId, $roomTypeId, $roomNoStr, $capacity);
            $stmt->execute();
            $roomIds[] = $conn->insert_id;
            $roomCounter++;
        }
    }
}
echo "   ✓ Created $roomCounter rooms across all hostels\n\n";

// ------------------------------------------------------------
// 14. SEATS
// ------------------------------------------------------------
echo "💺 Creating seats...\n";
$seatIds = [];
$seatCounter = 0;

// Get all rooms with their capacities
$result = $conn->query("SELECT id, capacity FROM rooms ORDER BY id");
while ($room = $result->fetch_assoc()) {
    $roomId = $room['id'];
    $capacity = $room['capacity'];
    
    for ($s = 1; $s <= $capacity; $s++) {
        $seatLabel = chr(64 + $s); // A, B, C, D
        $stmt = $conn->prepare("INSERT INTO seats (room_id, seat_label, status) VALUES (?, ?, 'ACTIVE')");
        $stmt->bind_param("is", $roomId, $seatLabel);
        $stmt->execute();
        $seatIds[] = $conn->insert_id;
        $seatCounter++;
    }
}
echo "   ✓ Created $seatCounter seats across all rooms\n\n";

// ------------------------------------------------------------
// 15. ROOM APPLICATIONS
// ------------------------------------------------------------
echo "📝 Creating room applications...\n";
$applicationIds = [];

// First 6 students have approved applications
for ($i = 0; $i < 6; $i++) {
    $studentId = $studentIds[$i];
    $hostelId = $hostelIds[$i % count($hostelIds)];
    $roomTypeId = ($i % 4) + 1;
    $notes = "Application notes from student " . ($i + 1);
    
    $stmt = $conn->prepare("INSERT INTO room_applications (student_user_id, hostel_id, preferred_room_type_id, status, notes, submitted_at, reviewed_at, reviewed_by_manager_user_id) VALUES (?, ?, ?, 'APPROVED', ?, NOW(), NOW(), ?)");
    $managerId = $managerIds[$i % count($managerIds)];
    $stmt->bind_param("iiisi", $studentId, $hostelId, $roomTypeId, $notes, $managerId);
    $stmt->execute();
    $applicationIds[] = $conn->insert_id;
    echo "   ✓ Application #" . $conn->insert_id . " (Student #{$studentId}) - APPROVED\n";
}

// Next 2 students have pending applications
for ($i = 6; $i < 8; $i++) {
    $studentId = $studentIds[$i];
    $hostelId = $hostelIds[$i % count($hostelIds)];
    $roomTypeId = ($i % 4) + 1;
    $notes = "Waiting for room assignment";
    
    $stmt = $conn->prepare("INSERT INTO room_applications (student_user_id, hostel_id, preferred_room_type_id, status, notes, submitted_at) VALUES (?, ?, ?, 'SUBMITTED', ?, NOW())");
    $stmt->bind_param("iiis", $studentId, $hostelId, $roomTypeId, $notes);
    $stmt->execute();
    echo "   ✓ Application #" . $conn->insert_id . " (Student #{$studentId}) - SUBMITTED\n";
}

// Last 2 students have draft applications
for ($i = 8; $i < 10; $i++) {
    $studentId = $studentIds[$i];
    $hostelId = $hostelIds[$i % count($hostelIds)];
    $roomTypeId = ($i % 4) + 1;
    
    $stmt = $conn->prepare("INSERT INTO room_applications (student_user_id, hostel_id, preferred_room_type_id, status) VALUES (?, ?, ?, 'DRAFT')");
    $stmt->bind_param("iii", $studentId, $hostelId, $roomTypeId);
    $stmt->execute();
    echo "   ✓ Application #" . $conn->insert_id . " (Student #{$studentId}) - DRAFT\n";
}
echo "\n";

// ------------------------------------------------------------
// 16. ALLOCATIONS (for approved students)
// ------------------------------------------------------------
echo "🏠 Creating seat allocations...\n";
$allocationIds = [];

for ($i = 0; $i < 6; $i++) {
    $studentId = $studentIds[$i];
    $seatId = $seatIds[$i]; // Assign first 6 seats
    $hostelId = $hostelIds[$i % count($hostelIds)];
    $managerId = $managerIds[$i % count($managerIds)];
    
    $stmt = $conn->prepare("INSERT INTO allocations (student_user_id, seat_id, hostel_id, start_date, status, created_by_manager_user_id) VALUES (?, ?, ?, NOW(), 'ACTIVE', ?)");
    $stmt->bind_param("iiii", $studentId, $seatId, $hostelId, $managerId);
    $stmt->execute();
    $allocationIds[] = $conn->insert_id;
    echo "   ✓ Allocation #" . $conn->insert_id . " - Student #{$studentId} → Seat #{$seatId}\n";
}
echo "\n";

// ------------------------------------------------------------
// 17. STUDENT INVOICES
// ------------------------------------------------------------
echo "💰 Creating student invoices...\n";
$invoiceIds = [];

// Use the first fee period we created
$feePeriodId = $feePeriodIds[0];

for ($i = 0; $i < 6; $i++) {
    $studentId = $studentIds[$i];
    $hostelId = $hostelIds[$i % count($hostelIds)];
    $roomTypeId = ($i % 4) + 1;
    
    // Get fee for room type
    $feeResult = $conn->query("SELECT default_fee FROM room_types WHERE id = $roomTypeId");
    $fee = $feeResult->fetch_assoc()['default_fee'];
    
    // Different statuses
    $statuses = ['PAID', 'PAID', 'PARTIAL', 'PARTIAL', 'DUE', 'DUE'];
    $status = $statuses[$i];
    
    $stmt = $conn->prepare("INSERT INTO student_invoices (student_user_id, hostel_id, period_id, amount_due, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiis", $studentId, $hostelId, $feePeriodId, $fee, $status);
    $stmt->execute();
    $invoiceIds[] = $conn->insert_id;
    echo "   ✓ Invoice #" . $conn->insert_id . " - Student #{$studentId}: \${$fee} ({$status})\n";
}
echo "\n";

// ------------------------------------------------------------
// 18. PAYMENTS
// ------------------------------------------------------------
echo "💳 Creating payments...\n";

$paymentMethods = ['CASH', 'BKASH', 'BANK', 'OTHER'];

// For PAID invoices - full payment
for ($i = 0; $i < 2; $i++) {
    $invoiceId = $invoiceIds[$i];
    $recorderId = $managerIds[$i % count($managerIds)];
    $method = $paymentMethods[$i % count($paymentMethods)];
    $refNo = 'PAY-' . date('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
    
    // Get invoice amount
    $invResult = $conn->query("SELECT amount_due FROM student_invoices WHERE id = $invoiceId");
    $amount = $invResult->fetch_assoc()['amount_due'];
    
    $stmt = $conn->prepare("INSERT INTO payments (invoice_id, amount_paid, method, reference_no, recorded_by_user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idssi", $invoiceId, $amount, $method, $refNo, $recorderId);
    $stmt->execute();
    echo "   ✓ Payment #" . $conn->insert_id . " - Invoice #{$invoiceId}: \${$amount} ({$method})\n";
}

// For PARTIAL invoices - partial payment
for ($i = 2; $i < 4; $i++) {
    $invoiceId = $invoiceIds[$i];
    $recorderId = $managerIds[$i % count($managerIds)];
    $method = $paymentMethods[$i % count($paymentMethods)];
    $refNo = 'PAY-' . date('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
    
    // Get invoice amount and pay half
    $invResult = $conn->query("SELECT amount_due FROM student_invoices WHERE id = $invoiceId");
    $amount = $invResult->fetch_assoc()['amount_due'] / 2;
    
    $stmt = $conn->prepare("INSERT INTO payments (invoice_id, amount_paid, method, reference_no, recorded_by_user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idssi", $invoiceId, $amount, $method, $refNo, $recorderId);
    $stmt->execute();
    echo "   ✓ Payment #" . $conn->insert_id . " - Invoice #{$invoiceId}: \${$amount} (partial, {$method})\n";
}
echo "\n";

// ------------------------------------------------------------
// 19. NOTICES
// ------------------------------------------------------------
echo "📢 Creating notices...\n";
$notices = [
    ['GLOBAL', null, 'Welcome to HMS', 'Welcome to the Hostel Management System. Please familiarize yourself with the rules and regulations.', 'PUBLISHED', $adminIds[0]],
    ['GLOBAL', null, 'Fee Payment Reminder', 'Please ensure all hostel fees are paid by the end of this month to avoid late charges.', 'PUBLISHED', $adminIds[0]],
    ['HOSTEL', $hostelIds[0], 'Water Supply Notice', 'Water supply will be interrupted tomorrow from 10 AM to 2 PM for maintenance work.', 'PUBLISHED', $managerIds[0]],
    ['HOSTEL', $hostelIds[1], 'Room Inspection', 'Room inspection scheduled for this weekend. Please keep your rooms clean and tidy.', 'PUBLISHED', $managerIds[1]],
    ['HOSTEL', $hostelIds[2], 'WiFi Upgrade', 'WiFi network will be upgraded tonight. Expect brief disconnections between 11 PM and 1 AM.', 'PUBLISHED', $managerIds[2]]
];

foreach ($notices as $notice) {
    $stmt = $conn->prepare("INSERT INTO notices (scope, hostel_id, title, body, status, publish_at, created_by_user_id) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("sisssi", $notice[0], $notice[1], $notice[2], $notice[3], $notice[4], $notice[5]);
    $stmt->execute();
    echo "   ✓ Notice: {$notice[2]} ({$notice[0]})\n";
}
echo "\n";

// ------------------------------------------------------------
// 20. COMPLAINTS
// ------------------------------------------------------------
echo "📋 Creating complaints...\n";
$complaints = [
    [$studentIds[0], $hostelIds[0], 1, 'Air Conditioner Not Working', 'The AC in room 001 is not cooling properly. It has been making strange noises for the past 2 days.', 'OPEN'],
    [$studentIds[1], $hostelIds[0], 2, 'Bathroom Cleanliness Issue', 'The common bathroom on the first floor needs more frequent cleaning.', 'IN_PROGRESS'],
    [$studentIds[2], $hostelIds[1], 5, 'Leaking Tap', 'The tap in the washroom is leaking continuously, wasting water.', 'RESOLVED'],
    [$studentIds[3], $hostelIds[1], 4, 'Noise from Construction', 'There is excessive noise from nearby construction during study hours.', 'OPEN'],
    [$studentIds[4], $hostelIds[2], 6, 'Power Socket Not Working', 'Two power sockets near my bed are not functioning.', 'IN_PROGRESS']
];

$complaintIds = [];
foreach ($complaints as $complaint) {
    $stmt = $conn->prepare("INSERT INTO complaints (student_user_id, hostel_id, category_id, subject, description, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $complaint[0], $complaint[1], $complaint[2], $complaint[3], $complaint[4], $complaint[5]);
    $stmt->execute();
    $complaintIds[] = $conn->insert_id;
    echo "   ✓ Complaint #" . $conn->insert_id . ": {$complaint[3]} ({$complaint[5]})\n";
}
echo "\n";

// ------------------------------------------------------------
// 21. COMPLAINT MESSAGES
// ------------------------------------------------------------
echo "💬 Creating complaint messages...\n";

// Messages for first complaint
$messages = [
    [$complaintIds[0], $studentIds[0], 'The AC has been not working for 2 days now. Please send someone to check.'],
    [$complaintIds[0], $managerIds[0], 'We have received your complaint. A technician will visit tomorrow morning.'],
    [$complaintIds[1], $studentIds[1], 'The bathroom needs urgent attention. Cleanliness has been poor lately.'],
    [$complaintIds[1], $managerIds[0], 'We are arranging additional cleaning staff. Thank you for your feedback.'],
    [$complaintIds[2], $studentIds[2], 'The tap has been fixed. Thank you for the quick response!'],
    [$complaintIds[2], $managerIds[1], 'Glad the issue is resolved. Please let us know if there are any other problems.']
];

foreach ($messages as $msg) {
    $stmt = $conn->prepare("INSERT INTO complaint_messages (complaint_id, sender_user_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $msg[0], $msg[1], $msg[2]);
    $stmt->execute();
}
echo "   ✓ Created " . count($messages) . " complaint messages\n\n";

// ------------------------------------------------------------
// 22. AUDIT LOGS
// ------------------------------------------------------------
echo "📜 Creating audit logs...\n";

$auditLogs = [
    [$adminIds[0], 'CREATE', 'hostels', $hostelIds[0], '{"name": "Alpha Hostel", "code": "ALPHA"}'],
    [$adminIds[0], 'CREATE', 'hostels', $hostelIds[1], '{"name": "Beta Hostel", "code": "BETA"}'],
    [$adminIds[0], 'CREATE', 'hostels', $hostelIds[2], '{"name": "Gamma Hostel", "code": "GAMMA"}'],
    [$managerIds[0], 'APPROVE', 'room_applications', $applicationIds[0], '{"status": "APPROVED"}'],
    [$managerIds[0], 'ASSIGN', 'allocations', $allocationIds[0], '{"seat_id": ' . $seatIds[0] . ', "student_id": ' . $studentIds[0] . '}'],
    [$managerIds[1], 'APPROVE', 'room_applications', $applicationIds[1], '{"status": "APPROVED"}'],
    [$managerIds[1], 'CREATE', 'student_invoices', $invoiceIds[0], '{"amount": 4500}'],
    [$managerIds[0], 'RECORD_PAYMENT', 'payments', 1, '{"amount": 4500, "method": "CASH"}'],
    [$adminIds[0], 'CREATE', 'users', $studentIds[0], '{"email": "rahim.ahmed@student.hms", "role": "STUDENT"}'],
    [$adminIds[0], 'UPDATE', 'notices', 1, '{"title": "Welcome to HMS", "status": "PUBLISHED"}']
];

foreach ($auditLogs as $log) {
    $stmt = $conn->prepare("INSERT INTO audit_logs (actor_user_id, action, entity_type, entity_id, meta_json) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $log[0], $log[1], $log[2], $log[3], $log[4]);
    $stmt->execute();
}
echo "   ✓ Created " . count($auditLogs) . " audit log entries\n\n";

// ============================================================
// SUMMARY
// ============================================================
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    SEEDING COMPLETE! ✅                       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "📊 Summary:\n";
echo "   • Roles: 3\n";
echo "   • Complaint Categories: " . count($categories) . "\n";
echo "   • Room Types: " . count($roomTypes) . "\n";
echo "   • Fee Periods: " . count($feePeriods) . "\n";
echo "   • Users:\n";
echo "     - Admins: " . count($adminIds) . "\n";
echo "     - Managers: " . count($managerIds) . "\n";
echo "     - Students: " . count($studentIds) . "\n";
echo "   • Student Profiles: " . count($studentIds) . "\n";
echo "   • Hostels: " . count($hostelIds) . "\n";
echo "   • Floors: " . (count($hostelIds) * 4) . "\n";
echo "   • Rooms: $roomCounter\n";
echo "   • Seats: $seatCounter\n";
echo "   • Room Applications: 10\n";
echo "   • Allocations: " . count($allocationIds) . "\n";
echo "   • Invoices: " . count($invoiceIds) . "\n";
echo "   • Payments: 4\n";
echo "   • Notices: " . count($notices) . "\n";
echo "   • Complaints: " . count($complaints) . "\n";
echo "   • Complaint Messages: " . count($messages) . "\n";
echo "   • Audit Logs: " . count($auditLogs) . "\n\n";

echo "🔐 Default Login Credentials:\n";
echo "   Password for ALL users: password123\n\n";
echo "   Admin:    admin1@admin.hms\n";
echo "   Manager:  manager1@manager.hms\n";
echo "   Student:  rahim.ahmed@student.hms\n\n";

$conn->close();

echo "</pre>";
?>
