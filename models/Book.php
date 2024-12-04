<?php

require_once 'config/database.php';

class Book
{
    private $id, $title, $author, $year, $status;

    public function getId()
    {
        return $this->id;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getStatus()
    {
        return $this->status;
    }

    static function filter($search)
    {
        global $pdo;
        $search = "%$search%";
        $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE :search AND status = 'available'");
        $stmt->bindParam(':search', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    static function get()
    {
        global $pdo;
        $query = $pdo->query("SELECT * FROM books WHERE status = 'available'");
        return $query->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    static function getAll()
    {
        global $pdo;
        $query = $pdo->query("SELECT * FROM books");
        return $query->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    static function updateStatus($bookId, $status)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE books SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $bookId);
        return $stmt->execute();
    }
}