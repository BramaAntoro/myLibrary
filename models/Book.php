<?php

require_once 'config/database.php';

class Book
{
    private $id, $title, $author, $year;

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

    static function filter($search)
    {
        global $pdo;
        $search = "%$search%";
        $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE :search");
        $stmt->bindParam(':search', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }


    static function get()
    {
        global $pdo;
        $query = $pdo->query("SELECT * FROM books");
        return $query->fetchAll(PDO::FETCH_CLASS, 'Book');
    }
}
