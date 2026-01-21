<?php
// Manager Hostel View
$page = 'manager_hostels';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Manager</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="admin-layout">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="admin-content">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($action === 'view' && isset($data['hostel'])): ?>
                    <!-- View Hostel Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_hostels">My Hostels</a>
                        <span>/</span>
                        <span class="current">Hostel Details</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Hostel Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['hostel']['name']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Code</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['hostel']['code']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Address</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['hostel']['address'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="badge badge-<?php echo $data['hostel']['status'] === 'ACTIVE' ? 'success' : 'secondary'; ?>">
                                    <?php echo htmlspecialchars($data['hostel']['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics Grid -->
                    <div class="stats-grid" style="margin-bottom: 20px;">
                        <div class="stat-card">
                            <div class="stat-label">Total Floors</div>
                            <div class="stat-value"><?php echo (int)$data['total_floors']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Total Rooms</div>
                            <div class="stat-value"><?php echo (int)$data['total_rooms']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Total Seats</div>
                            <div class="stat-value"><?php echo (int)$data['total_seats']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Occupied</div>
                            <div class="stat-value"><?php echo (int)$data['occupied_seats']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Available</div>
                            <div class="stat-value"><?php echo (int)$data['available_seats']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Pending Applications</div>
                            <div class="stat-value"><?php echo (int)$data['pending_applications']; ?></div>
                        </div>
                    </div>
                    
                    <!-- Floors List -->
                    <?php if (!empty($data['floors'])): ?>
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Floors</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Floor Number</th>
                                        <th>Total Rooms</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['floors'] as $floor): ?>
                                        <tr>
                                            <td>Floor <?php echo (int)$floor['floor_no']; ?></td>
                                            <td><?php echo (int)$floor['room_count']; ?> rooms</td>
                                            <td>
                                                <span class="badge badge-success">Active</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div style="margin-top: 20px;">
                        <a href="index.php?page=manager_hostels" class="btn btn-secondary">Back to My Hostels</a>
                    </div>
                    
                <?php else: ?>
                    <!-- List Hostels -->
                    <div class="page-header">
                        <h2>My Assigned Hostels</h2>
                    </div>
                    
                    <?php if (!empty($data['hostels'])): ?>
                        <div class="hostel-cards">
                            <?php foreach ($data['hostels'] as $hostel): ?>
                                <div class="hostel-card">
                                    <div class="hostel-card-header">
                                        <h3><?php echo htmlspecialchars($hostel['name']); ?></h3>
                                        <span class="hostel-code"><?php echo htmlspecialchars($hostel['code']); ?></span>
                                    </div>
                                    <div class="hostel-card-body">
                                        <div class="hostel-info">
                                            <span class="badge badge-<?php echo $hostel['status'] === 'ACTIVE' ? 'success' : 'secondary'; ?>">
                                                <?php echo htmlspecialchars($hostel['status']); ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($hostel['address'])): ?>
                                        <p class="hostel-address">
                                            <strong>Address:</strong> <?php echo htmlspecialchars($hostel['address']); ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="hostel-card-footer">
                                        <a href="index.php?page=manager_hostels&action=view&id=<?php echo (int)$hostel['id']; ?>" 
                                           class="btn btn-primary btn-sm">View Details</a>
                                        <a href="index.php?page=manager_applications&hostel_id=<?php echo (int)$hostel['id']; ?>" 
                                           class="btn btn-secondary btn-sm">Applications</a>
                                        <a href="index.php?page=manager_allocations&hostel_id=<?php echo (int)$hostel['id']; ?>" 
                                           class="btn btn-secondary btn-sm">Allocations</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <style>
                            .hostel-cards {
                                display: grid;
                                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                                gap: 20px;
                                margin-top: 20px;
                            }
                            .hostel-card {
                                background: white;
                                border: 1px solid #ddd;
                                border-radius: 8px;
                                padding: 20px;
                                transition: box-shadow 0.3s;
                            }
                            .hostel-card:hover {
                                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                            }
                            .hostel-card-header {
                                border-bottom: 2px solid #f0f0f0;
                                padding-bottom: 15px;
                                margin-bottom: 15px;
                            }
                            .hostel-card-header h3 {
                                margin: 0 0 8px 0;
                                font-size: 1.3rem;
                                color: #333;
                            }
                            .hostel-code {
                                background: #e8f4f8;
                                padding: 4px 10px;
                                border-radius: 4px;
                                font-size: 0.85rem;
                                font-weight: 600;
                                color: #0066cc;
                            }
                            .hostel-card-body {
                                margin-bottom: 15px;
                            }
                            .hostel-address {
                                margin: 10px 0 0 0;
                                color: #666;
                                font-size: 0.9rem;
                            }
                            .hostel-card-footer {
                                display: flex;
                                gap: 10px;
                                flex-wrap: wrap;
                            }
                        </style>
                    <?php else: ?>
                        <div class="alert alert-info">
                            You are not currently assigned to manage any hostels. Please contact the administrator.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
