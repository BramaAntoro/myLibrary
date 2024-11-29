<?php
session_start();
define('SECURE_ACCESS', true);

$uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER['QUERY_STRING'] ?? NULL;

// Koneksi Database
require_once 'config/database.php'; 

// Redirect ke halaman login jika belum login
if (!isset($_SESSION['is_login']) && ($uri == '/membership' || $uri == '/book')) {
    header('location: /login');
    exit;
}

// Rute utama
if ($uri == '/') {
    return require 'controllers/HomeController.php';
}

// if ($uri == '/visitor') {
//     return require 'controllers/VisitorController.php';
// }

if ($uri == '/membership') {
    // Validasi akses role_id
    if ($_SESSION['role_id'] != 1) {
        header('location: /login');
        exit;
    }
    return require 'controllers/MembershipController.php';
}

if (strpos($uri, '/membership?delete=') !== false) {
    // Validasi akses role_id
    if ($_SESSION['role_id'] != 1) {
        header('location: /login');
        exit;
    }
    $id = explode('=', $query_string)[1];

    // Hapus data member
    try {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set session success message
        $_SESSION['success'] = "Member berhasil dihapus!";

        // Redirect kembali ke halaman membership
        header('location: /membership');
        exit;
    } catch (PDOException $e) {
        echo "Error deleting member: " . $e->getMessage();
    }
}


if ($uri == '/add-book') {
    // Validasi akses role_id
    if ($_SESSION['role_id'] != 1) {
        header('location: /login');
        exit;
    }
    return require 'controllers/BookController.php';
}

if ($uri == '/add-book' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    return BookController::addBook();
}


if ($uri == '/visitor') {
    // Validasi akses role_id
    if ($_SESSION['role_id'] != 2) {
        header('location: /login');
        exit;
    }
    return require 'controllers/VisitorController.php';
}

if ($uri == '/book' . $query_string) {
    return require 'controllers/BookController.php';
}

if ($uri == '/login' || $uri == '/register') {
    return require 'controllers/AuthController.php';
}

// Redirect ke halaman login jika belum login
if (!isset($_SESSION['is_login']) && ($uri == '/membership' || $uri == '/book')) {
    header('location: /login');
    exit;
}


if ($uri == '/logout') {
    session_start();
    session_unset(); 
    session_destroy(); 
    header('location: /'); 
    exit;
}


return require 'views/notFoundPage.php';