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
                $_SESSION['error'] = "All fields must be filled!";
                $_SESSION['username'] = $username;
                header('location: /login');
                exit;
            }

            $user = new User;
            $user->auth($username, $password); // Authentikasi termasuk validasi role
            exit;
        }
        return self::view("views/login.php");
    }

    public static function register()
    {
        // Pastikan session username dan name kosong saat masuk halaman register
        unset($_SESSION['username']);
        unset($_SESSION['name']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $username = htmlspecialchars($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Validasi input
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

            // Proses registrasi
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
