<?php
require_once 'Controller.php';
require_once 'models/User.php';

class AuthController extends Controller
{
    public static function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $password = $_POST['password'];

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Username dan password harus diisi!";
                header('location: /login');
                exit;
            }

            global $pdo;
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['is_login'] = true;
                $_SESSION['role_id'] = $user['role_id'];
                
                $_SESSION['user_id'] = $user['id'];

                if ($user['role_id'] == 1) {
                    header('location: /membership');
                } else {
                    header('location: /visitor');
                }
                exit;
            }

            $_SESSION['error'] = "Username atau password salah!";
            header('location: /login');
            exit;
        }
        return self::view("views/login.php");
    }

    public static function register()
    {
        unset($_SESSION['username']);
        unset($_SESSION['name']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $username = htmlspecialchars($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if (empty($name) || empty($username) || empty($password)) {
                $_SESSION['error'] = "All fields must be filled!";
                $_SESSION['name'] = $name;
                $_SESSION['username'] = $username;
                header('location: /register');
                exit;
            }

            $user = new User();
            $user->name = $name;
            $user->username = $username;
            $user->password = $password;

            $user->register();
            exit;
        }

        return self::view("views/register.php");
    }


}

if ($uri == '/login') {
    return AuthController::index();
}

AuthController::register();