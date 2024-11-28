<?php

require_once 'config/database.php';

class User
{
    public $id, $name, $username, $password, $role_id;

    // public function __construct($name, $username, $password, $role_id)
    // {
    //     $this->name = $name;
    //     $this->username = $username;
    //     $this->password = $password;
    //     $this->role_id = $role_id;
    // }


    public function auth($username, $password)
    {
        try {
            global $pdo;

            $select = "SELECT * FROM users WHERE username=:username";
            $query = $pdo->prepare($select);
            $query->bindParam(':username', $username);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_OBJ);

            if (!$user) {
                $_SESSION['error'] = "User not registered!";
                header('location: /login');
                exit;
            }

            if (password_verify($password, $user->password)) {
                $_SESSION['is_login'] = true;
                $_SESSION['username'] = $user->username;
                $_SESSION['role_id'] = $user->role_id;

                if ($user->role_id == 1) {
                    header("location: /membership");
                } elseif ($user->role_id == 2) {
                    header("location: /visitor");
                }
                exit;
            }

            $_SESSION['error'] = "Wrong password!";
            header('location: /login');
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function register()
    {
        try {
            global $pdo;

            // Menambahkan user dengan role_id = 2 (member)
            $user = "INSERT INTO users (name, username, password, role_id) VALUES ('$this->name', '$this->username', '$this->password', 2)";
            $pdo->exec($user);  // Eksekusi query untuk memasukkan data

            $_SESSION['success'] = "Registration successful!";
            header('location: /membership');  // Kembali ke halaman membership setelah registrasi
        } catch (PDOException $e) {
            echo $user . "<br>" . $e->getMessage();
        }
    }


}
