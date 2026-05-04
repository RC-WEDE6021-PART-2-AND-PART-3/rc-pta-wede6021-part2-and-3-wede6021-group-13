<?php
session_start();
include 'DBConn.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle user verification
if (isset($_GET['verify'])) {
    $user_id = $_GET['verify'];
    $stmt = $conn->prepare("UPDATE tblUser SET is_verified = 1 WHERE user_id = ?");
    $stmt->execute([$user_id]);
    header("Location: admin_dashboard.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tblUser WHERE user_id = ?");
    $stmt->execute([$user_id]);
    header("Location: admin_dashboard.php");
    exit();
}

// Handle user update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $stmt = $conn->prepare("UPDATE tblUser SET full_name = ?, email = ? WHERE user_id = ?");
    $stmt->execute([$full_name, $email, $user_id]);
    header("Location: admin_dashboard.php");
    exit();
}

// Get all users
$users = $conn->query("SELECT * FROM tblUser ORDER BY user_id DESC")->fetchAll();

// Calculate statistics
$total_users = count($users);
$verified_users = count(array_filter($users, function($u) { return $u['is_verified'] == 1; }));
$pending_users = $total_users - $verified_users;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Clothing Store</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        
        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .btn {
            padding: 5px 12px;
            margin: 0 3px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }
        
        .btn-verify {
            background: #28a745;
            color: white;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-update {
            background: #ffc107;
            color: #333;
        }
        
        .btn-logout {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        
        .edit-form {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            display: none;
        }
        
        .edit-form input {
            padding: 5px;
            margin-right: 5px;
        }
        
        h2 {
            margin: 20px 0;
            color: #333;
        }
        
        .back-link {
            margin-top: 30px;
            text-align: center;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            padding: 10px 20px;
            background: white;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
    <script>
        function toggleEditForm(userId) {
            var form = document.getElementById('edit-form-' + userId);
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>👕 Clothing Store - Admin Dashboard</h1>
        <div>
            Welcome, <?php echo $_SESSION['admin_name']; ?>! 
            <a href="admin_logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
    
    <div class="stats">
        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="number"><?php echo $total_users; ?></div>
        </div>
        <div class="stat-card">
            <h3>Verified Users</h3>
            <div class="number" style="color:#28a745;"><?php echo $verified_users; ?></div>
        </div>
        <div class="stat-card">
            <h3>Pending Verification</h3>
            <div class="number" style="color:#ffc107;"><?php echo $pending_users; ?></div>
        </div>
    </div>
    
    <h2>Customer Management</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <?php if ($user['is_verified']): ?>
                        <span style="color:#28a745;">✓ Verified</span>
                    <?php else: ?>
                        <span style="color:#ffc107;">⏳ Pending</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <?php if (!$user['is_verified']): ?>
                        <a href="?verify=<?php echo $user['user_id']; ?>" class="btn btn-verify" onclick="return confirm('Verify this user?')">Verify</a>
                    <?php endif; ?>
                    <button onclick="toggleEditForm(<?php echo $user['user_id']; ?>)" class="btn btn-update">Update</button>
                    <a href="?delete=<?php echo $user['user_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    
                    <div id="edit-form-<?php echo $user['user_id']; ?>" class="edit-form">
                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            <button type="submit" name="update_user" class="btn btn-update">Save</button>
                            <button type="button" onclick="toggleEditForm(<?php echo $user['user_id']; ?>)" class="btn">Cancel</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="back-link">
        <a href="index.php">← Back to Main Site</a>
    </div>
</body>
</html>