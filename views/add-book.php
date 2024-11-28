<?php
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

if (isset($_SESSION['is_login']) == false) {
    header("location: /login");
}

include('templates/header.php');
?>

<div class="main-content login-panel">
    <div class="login-body">
        <div class="top d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="assets/images/logo-black.png" alt="Logo">
            </div>
            <a href="/"><i class="fa-duotone fa-house-chimney"></i></a>
        </div>
        <div class="bottom">
            <h3 class="panel-title">Add book</h3>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php elseif (isset($_SESSION['success'])): ?>
                <div class="alert alert-success text-center">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/add-book">
                <div class="input-group mb-25">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="title" name="title">
                </div>
                <div class="input-group mb-25">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="author" name="author">
                </div>
                <div class=" input-group mb-25">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="year" name="year">
                </div>
                <button class="btn btn-primary w-100 login-btn" type="submit">Add book</button>
            </form>
        </div>
    </div>
</div>

<?php include('templates/footer.php'); ?>