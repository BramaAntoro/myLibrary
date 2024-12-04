<?php
$number = 1;
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

if (isset($_SESSION['is_login']) == false) {
    header("location: /login");
}

if ($_SESSION['role_id'] != 1) {
    echo "Tidak memiliki akses";
    exit;
}


include('config/database.php');

include('templates/header.php');
?>

<div class="container">
    <a href="/register" class="btn btn-primary mb-3">Register Member</a>
    <a href='/book' class='btn btn-primary mb-3'>Borrow</a> 
    <a href="/logout" class="btn btn-danger mb-3">Logout</a>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $pdo->query("SELECT * FROM users WHERE role_id = 2");
            $members = $query->fetchAll(PDO::FETCH_OBJ);
            $totalMembers = count($members);
            echo "<p>Total Member: {$totalMembers}</p>";
            $number = 1;
            foreach ($members as $member) {
                echo "<tr>
                        <td>{$number}</td>
                        <td>{$member->name}</td>
                        <td>{$member->username}</td>
                        <td>Member</td>
                        <td>
                            <a href='/membership?delete={$member->id}' class='btn btn-danger'>Delete</a> 
                        </td>
                    </tr>";
                $number++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('templates/footer.php'); ?>