<?php

if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

if (!isset($_SESSION['is_login'])) {
    header("location: /login");
    exit;
}

if ($_SESSION['role_id'] != 1) {
    echo "Tidak memiliki akses";
    exit;
}

$number = 1;
$totalBooks = count($data);

include('templates/header.php');
?>
<div class="main-content bg-white">
    <section class="container my-5">
        <h3 class="panel-title text-center">Search Book @ BRAMA LIBRARY</h3>
        <div class="text-center mb-4">
            <strong>Total Books : <?= $totalBooks ?></strong>
        </div>

        <a href="/add-book" class="btn btn-warning mb-3">Add book</a>
        <form class="d-flex justify-content-between align-items-center" method="GET" action="/book">
            <input type="text" class="form-control" placeholder="Cari Buku" name="search" required />
            <button type="submit" class="btn btn-sm btn-primary mx-3">Search</button>
        </form>

        <div class="card my-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Book Borrowing Form</h5>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger text-center">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
                <form method="POST" action="/borrow">
                    <div class="mb-4">
                        <h6 class="card-subtitle mb-3">Select Member</h6>
                        <select class="form-select" name="member_id" required>
                            <option value="">Choose a member...</option>
                            <?php
                            global $pdo;
                            $memberQuery = $pdo->query("SELECT id, name FROM users WHERE role_id = 2");
                            foreach ($memberQuery as $member) {
                                echo "<option value='{$member['id']}'>{$member['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <h6 class="card-subtitle mb-3">Select Book</h6>
                        <select class="form-select" name="book_id" required>
                            <option value="">Choose a book...</option>
                            <?php
                            foreach ($data as $book) {
                                echo "<option value='{$book->getId()}'>{$book->getTitle()} - {$book->getAuthor()}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <h6 class="card-subtitle mb-3">Borrow Date</h6>
                        <input type="date" class="form-control" name="borrow_date" value="<?php echo date('Y-m-d'); ?>"
                            required>
                    </div>

                    <div class="mb-4">
                        <h6 class="card-subtitle mb-3">Return Date</h6>
                        <input type="date" class="form-control" name="return_date" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Borrowing</button>
                </form>
            </div>
        </div>

        <div class="table table-responsive my-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Years</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success text-center">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']);
                    }
                    ?>
                    <?php foreach ($data as $book): ?>
                        <tr>
                            <td><?= $number++ ?></td>
                            <td><?= $book->getTitle() ?></td>
                            <td><?= $book->getAuthor() ?></td>
                            <td><?= $book->getYear() ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mb-0">
            <div class="my-4">
                <a href="/">Back to Home</a>
            </div>
        </div>
        <div class="footer mb-0">
            <p>Copyright©
                <script>
                    document.write(new Date().getFullYear())
                </script> All Rights Reserved By <span class="text-primary">BRAMA</span>
            </p>
        </div>
    </section>
</div>

<?php include('templates/footer.php') ?>