<?php
$number = 1;
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

if (isset($_SESSION['is_login']) == false) {
    header("location: /login");
}

include('templates/header.php') ?>

<a href="/logout" class="btn btn-danger mb-3">Logout</a>
<h1>halaman visitor</h1>


<?php include('templates/footer.php') ?>
