<?php

require_once 'Controller.php';
require_once 'models/Borrow.php';
require_once 'models/Book.php';

class BorrowController extends Controller
{
    public static function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                global $pdo;
                $pdo->beginTransaction();

                $book_id = htmlspecialchars($_POST['book_id']);
                $users_id = htmlspecialchars($_POST['member_id']);
                $borrow_date = htmlspecialchars($_POST['borrow_date']);
                $return_date = htmlspecialchars($_POST['return_date']);

                if (empty($book_id) || empty($users_id) || empty($borrow_date) || empty($return_date)) {
                    throw new Exception("All fields must be filled!");
                }

                $stmt = $pdo->prepare("SELECT status FROM books WHERE id = :book_id");
                $stmt->bindParam(':book_id', $book_id);
                $stmt->execute();
                $book = $stmt->fetch();

                if ($book['status'] === 'borrowed') {
                    throw new Exception("Book is already borrowed!");
                }

                $stmt = $pdo->prepare("INSERT INTO borrow (book_id, users_id, borrow_date, return_date) VALUES (:book_id, :users_id, :borrow_date, :return_date)");
                $stmt->bindParam(':book_id', $book_id);
                $stmt->bindParam(':users_id', $users_id);
                $stmt->bindParam(':borrow_date', $borrow_date);
                $stmt->bindParam(':return_date', $return_date);
                $stmt->execute();

                Book::updateStatus($book_id, 'borrowed');

                $pdo->commit();
                $_SESSION['success'] = "Book borrowed successfully!";
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = $e->getMessage();
            }

            header('location: /book');
            exit;
        }
    }
}