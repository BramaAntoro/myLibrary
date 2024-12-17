<?php
require_once 'Controller.php';
require_once 'models/Book.php';

class ReturnController extends Controller
{
    public static function returnBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                global $pdo;
                $pdo->beginTransaction();

                $borrow_id = $_POST['borrow_id'];
                $book_id = $_POST['book_id'];

                $stmt = $pdo->prepare("SELECT borrow_date, return_date FROM borrow WHERE id = :borrow_id");
                $stmt->bindParam(':borrow_id', $borrow_id);
                $stmt->execute();
                $borrow = $stmt->fetch();

                $returnDate = new DateTime($borrow['return_date']);
                $today = new DateTime();
                $denda = 0;

                if ($today > $returnDate) {
                    $diff = $today->diff($returnDate);
                    $denda = $diff->days * 1000; 
                }

                $stmt = $pdo->prepare("UPDATE borrow SET 
                    actual_return_date = CURRENT_DATE,
                    late_fee = :denda 
                    WHERE id = :borrow_id");
                $stmt->bindParam(':denda', $denda);
                $stmt->bindParam(':borrow_id', $borrow_id);
                $stmt->execute();

                Book::updateStatus($book_id, 'available');

                $pdo->commit();

                if ($denda > 0) {
                    $_SESSION['warning'] = "Buku berhasil dikembalikan. Anda dikenakan denda keterlambatan sebesar Rp" . number_format($denda, 0, ',', '.');
                } else {
                    $_SESSION['success'] = "Buku berhasil dikembalikan tepat waktu!";
                }

            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Gagal mengembalikan buku: " . $e->getMessage();
            }

            header('location: /visitor');
            exit;
        }
    }
}