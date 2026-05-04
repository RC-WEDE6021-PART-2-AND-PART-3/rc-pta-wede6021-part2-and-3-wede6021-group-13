<?php
session_start();
include 'DBConn.php';

$error_message = "";
$sticky_username = "";
$sticky_email = "";

// Check if login form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Save entered values for sticky form
    $sticky_username = $username;
    $sticky_email = $email;
    
    try {
        // First, check if user exists
        $sql = "SELECT * FROM tblUser WHERE username = :username AND email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username, ':email' => $email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // User exists, now verify password with MD5 hash
            $sql_check = "SELECT * FROM tblUser WHERE username = :username AND email = :email AND password_hash = MD5(:password)";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->execute([':username' => $username, ':email' => $email, ':password' => $password]);
            $valid_user = $stmt_check->fetch();
            
            if ($valid_user) {
                // Password is correct
                if ($valid_user['is_verified'] == 1) {
                    // Account is verified - login successful
                    $_SESSION['user_id'] = $valid_user['user_id'];
                    $_SESSION['username'] = $valid_user['username'];
                    $_SESSION['full_name'] = $valid_user['full_name'];
                    $_SESSION['is_admin'] = $valid_user['is_admin'];
                    
                    // Display user data using associative read approach
                    echo "<div style='margin:20px; padding:20px; border:2px solid green; background:#f0fff0;'>";
                    echo "<h2>✓ User '" . $valid_user['full_name'] . "' is logged in</h2>";
                    echo "<h3>User Information (Associative Array Output):</h3>";
                    echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
                    echo "<tr style='background:#4CAF50; color:white;'><th>Field</th><th>Value</th></tr>";
                    
                    // Display associative array (only non-numeric keys)
                    foreach ($valid_user as $key => $value) {
                        if (!is_numeric($key)) { // Only show associative keys, not numeric indices
                            echo "<tr>";
                            echo "<td><strong>" . ucfirst(str_replace('_', ' ', $key)) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";
                    echo "<br><a href='dashboard.php' style='display:inline-block; padding:10px 20px; background:#4CAF50; color:white; text-decoration:none;'>Go to Dashboard</a>";
                    echo "</div>";
                    exit(); // Stop further execution
                    
                } else {
                    $error_message = "⚠️ Your account is pending verification by an administrator. Please wait for approval.";
                }
            } else {
                $error_message = "❌ Invalid password. Please try again.";
            }
        } else {
            $error_message = "❌ User not found. Please register first.";
        }
    } catch(PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}

// Handle registration button
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clothing Store - User Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            width: 450px;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .error {
            background-color: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        .register-btn {
            background: #6c757d;
            margin-top: 10px;
        }
        
        .register-btn:hover {
            background: #5a6268;
        }
        
        .admin-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .admin-link a {
            color: #667eea;
            text-decoration: none;
        }
        
        .admin-link a:hover {
            text-decoration: underline;
        }
        
        .info {
            background: #e7f3ff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #0066cc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>👕 Clothing Store Login</h2>
        
        <div class="info">
            💡 Test Users: Bob Johnson (bob.johnson@email.com / bob123) - Verified
        </div>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" novalidate>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo htmlspecialchars($sticky_username); ?>"
                       pattern="[A-Za-z0-9_]{3,20}" 
                       title="Username must be 3-20 characters (letters, numbers, underscore)">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($sticky_email); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            
            <button type="submit" name="login">🔐 Login</button>
            <button type="submit" name="register" class="register-btn">📝 Register New Account</button>
        </form>
        
        <div class="admin-link">
            <a href="admin_login.php">🔧 Admin Login</a>
        </div>
    </div>
</body>
</html>