<?php
$number = 1;
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

if (isset($_SESSION['is_login']) == false) {
    header("location: /login");
}

if ($_SESSION['role_id'] != 2) {
    echo "Tidak memiliki akses";
    exit;
}

include('templates/header.php');

global $pdo;
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT 
        books.title,
        books.author,
        books.year,
        borrow.id as borrow_id,
        borrow.book_id,
        borrow.borrow_date,
        borrow.return_date,
        borrow.actual_return_date,
        borrow.late_fee
    FROM borrow
    JOIN books ON borrow.book_id = books.id
    WHERE borrow.users_id = :user_id
    ORDER BY borrow.borrow_date DESC
");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalBooks = count($borrowed_books);
?>

<div class="main-content bg-white">
    <section class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="panel-title">My Borrowed Books</h3>
            <a href="/logout" class="btn btn-danger">Logout</a>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['warning'])): ?>
            <div class="alert alert-warning">
                <?= $_SESSION['warning']; ?>
                <?php unset($_SESSION['warning']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <strong>Total Books : <?= $totalBooks ?></strong>
        </div>
        <?php if (empty($borrowed_books)): ?>
            <div class="alert alert-info">
                You haven't borrowed any books yet.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Year</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Late Fee</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($borrowed_books as $book): ?>
                            <tr>
                                <td><?= $number++ ?></td>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><?= htmlspecialchars($book['year']) ?></td>
                                <td><?= date('d F Y', strtotime($book['borrow_date'])) ?></td>
                                <td><?= date('d F Y', strtotime($book['return_date'])) ?></td>
                                <td>
                                    <?php 
                                    $today = new DateTime();
                                    $returnDate = new DateTime($book['return_date']);
                                    if ($book['actual_return_date']) {
                                        echo '<span class="text-success">Dikembalikan</span>';
                                    } else if ($today > $returnDate) {
                                        echo '<span class="text-danger">Terlambat</span>';
                                    } else {
                                        echo '<span class="text-primary">Dipinjam</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($book['late_fee'] > 0) {
                                        echo 'Rp ' . number_format($book['late_fee'], 0, ',', '.');
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if (!$book['actual_return_date']): ?>
                                        <form method="POST" action="/return-book">
                                            <input type="hidden" name="borrow_id" value="<?= $book['borrow_id'] ?>">
                                            <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Kembalikan</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Sudah dikembalikan</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

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