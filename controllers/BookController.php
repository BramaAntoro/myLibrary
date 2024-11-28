<?php

require_once 'Controller.php';
require_once 'models/Book.php';
class BookController extends Controller
{
    public static function index()
    {
        $query_string = $_SERVER['QUERY_STRING'] ?? NULL;

        if (isset($query_string)) {
            $filter = explode('=', $query_string);
            $data = Book::filter($filter[1]);
            return self::view("views/book.php", $data);
        }
        $listBook = Book::get();
        return self::view("views/book.php", $listBook);
    }

    public static function addBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $author = htmlspecialchars($_POST['author']);
            $year = (int) htmlspecialchars($_POST['year']);

            // Validasi input
            if (empty($title) || empty($author) || empty($year)) {
                $_SESSION['error'] = "All fields must be filled!";
                header('location: /add-book');
                exit;
            }

            // Simpan ke database
            global $pdo;
            $stmt = $pdo->prepare("INSERT INTO books (title, author, year) VALUES (:title, :author, :year)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':year', $year);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Book added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add book!";
            }

            // Redirect ke /book setelah berhasil menambahkan
            header('location: /book');
            exit;
        }

        return self::view("views/add-book.php");
    }

}

if ($uri == '/add-book') {
    return BookController::addBook();
}

BookController::index();
