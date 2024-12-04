<?php

require_once 'config/database.php';

class Borrow 
{
    private $id, $book_id, $users_id, $borrow_date, $return_date;

    public function getId() 
    {
        return $this->id;
    }

    public function getBookId() 
    {
        return $this->book_id;
    }

    public function getUsersId() 
    {
        return $this->users_id;
    }

    public function getBorrowDate() 
    {
        return $this->borrow_date;
    }

    public function getReturnDate() 
    {
        return $this->return_date;
    }

    static function get() 
    {
        global $pdo;
        $query = $pdo->query("SELECT * FROM borrow");
        return $query->fetchAll(PDO::FETCH_CLASS, 'Borrow');
    }
}