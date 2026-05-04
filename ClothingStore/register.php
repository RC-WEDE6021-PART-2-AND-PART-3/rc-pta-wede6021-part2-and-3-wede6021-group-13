<?php
include 'DBConn.php';
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);
    
    try {
        // Check if username or email already exists
        $check = $conn->prepare("SELECT * FROM tblUser WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        
        if ($check->rowCount() > 0) {
            $error_message = "❌ Username or email already exists!";
        } else {
            // Insert new user with pending verification (is_verified = 0)
            $sql = "INSERT INTO tblUser (username, email, password_hash, full_name, is_verified, is_admin) 
                    VALUES (?, ?, MD5(?), ?, 0, 0)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt->execute([$username, $email, $password, $full_name])) {
                $success_message = "✅ Registration successful! Please wait for admin verification before logging in.";
            } else {
                $error_message = "❌ Registration failed. Please try again.";
            }
        }
    } catch(PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Clothing Store</title>
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
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
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
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 Create New Account</h2>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
            <div class="back-link">
                <a href="login.php">← Back to Login Page</a>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (!$success_message): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" required placeholder="Enter your full name">
            </div>
            
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required pattern="[A-Za-z0-9_]{3,20}" placeholder="3-20 characters">
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required placeholder="your@email.com">
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required minlength="6" placeholder="Minimum 6 characters">
            </div>
            
            <button type="submit">Create Account</button>
        </form>
        
        <div class="back-link">
            <a href="login.php">← Already have an account? Login</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>