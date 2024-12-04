<?php
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}
include('templates/header.php') ?>

<div class="main-content login-panel login-panel-2">
    <h3 class="panel-title">Welcome! @ PI SCHOOL LIBRARY</h3>
    <div class="login-body login-body-2">
        <div class="d-flex justify-content-center align-items-center">
            <a href="/book" class="text-center">
                <i class="fa-duotone fa-book"></i>
                <h6>Book Collection</h6>
            </a>
        </div>
    </div>
    <div class="login-body login-body-2 my-2">
        <div class="d-flex justify-content-center align-items-center">
            <a href="/visitor" class="text-center">
                <i class="fa-duotone fa-user"></i>
                <h6>Visitors</h6>
            </a>
        </div>
    </div>
    <div class="login-body login-body-2 my-2">
        <div class="d-flex justify-content-center align-items-center">
            <a href="/membership" class="text-center">
                <i class="fa-duotone fa-users"></i>
                <h6>Membership</h6>
            </a>
        </div>
    </div>

    <div class="footer">
        <p>Copyright© <script>
                document.write(new Date().getFullYear())
            </script> All Rights Reserved By <span class="text-primary">BRAMA</span></p>
    </div>
</div>

<?php include('templates/footer.php') ?>