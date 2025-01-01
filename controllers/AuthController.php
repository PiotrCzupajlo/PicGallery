<?php

class AuthController {

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            if (!empty($username) && !empty($password)) {
                $mongo = new MongoDB\Client(
                    "mongodb://localhost:27017/wai"
                    ,
                    [
                    'username' => 'wai_web'
                    ,
                    'password' => 'w@i_w3b',
                    ]);
                    $db = $mongo->wai;
    
                // Check if the username already exists
                $existingUser = $db->users->findOne(['username' => $username]);
                if ($existingUser) {
                    echo "Username already exists. Please try a different one.";
                    return;
                }
    
                // Hash the password for security
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
                // Save user to the database
                $db ->users->insertOne([
                    'username' => $username,
                    'password' => $hashedPassword
                ]);
    
                // Redirect to login page after successful registration
                header('Location: ?action=login');
                exit();
            } else {
                echo "Both username and password are required.";
            }
        } else {
            // Display the registration form
            require_once __DIR__ . '/../views/auth/register.php';
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                echo "Username and password are required.";
                return;
            }

            // Fetch user from MongoDB
            $mongo = new MongoDB\Client("mongodb://localhost:27017/wai", [
                'username' => 'wai_web',
                'password' => 'w@i_w3b',
            ]);

            $db = $mongo->wai;
            $usersCollection = $db->users;

            $user = $usersCollection->findOne(['username' => $username]);

            if (!$user || !password_verify($password, $user['password'])) {
                echo "Invalid username or password.";
                return;
            }

            $_SESSION['user'] = $username;

            header('Location: ../index.php');
            exit();
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ../index.php');
        exit();
    }
}
