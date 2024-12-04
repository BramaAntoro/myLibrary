<?php
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

if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success text-center">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
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
            <h3 class="panel-title">Register</h3>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php elseif (isset($_SESSION['success'])): ?>
                <div class="alert alert-success text-center">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif ?>
            <form method="POST" action="/register">
                <div class="input-group mb-25">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="Name" name="name"
                        value="<?= isset($_SESSION['name']) ? $_SESSION['name'] : '' ?>">
                </div>
                <div class="input-group mb-25">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="Username" name="username"
                        value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : '' ?>">
                </div>
                <div class="input-group mb-20">
                    <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                    <input type="password" class="form-control rounded-end" placeholder="Password" name="password">
                    <a role="button" class="password-show"><i class="fa-duotone fa-eye"></i></a>
                </div>
                <button class="btn btn-primary w-100 login-btn" type="submit">Register</button>
            </form>
        </div>
    </div>
</div>

<?php include('templates/footer.php'); ?>