<?php
include("./includes/header.php");

session_start();

if(isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}

if(isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
?>
<!-- Login Hero -->
<section class="page-hero">
    <div class="container">
        <h1>Welcome Back</h1>
        <p>Login to your VaxPakistan account</p>
    </div>
</section>

<!-- Login Form -->
<section class="login">
    <div class="container">
        <div class="login-container">
            <div class="login-box">
                <h2>Login to Your Account</h2>
                <form id="loginForm" method="POST" action="action/login_process.php">
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required>
                        <div class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <!-- <div class="form-options">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                    </div> -->
                    <button type="submit" class="btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>


                <p class="login-footer">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </div>

            <div class="login-info">
                <h3>One Account, Many Benefits</h3>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Manage vaccination schedules</li>
                    <li><i class="fas fa-check-circle"></i> Book hospital appointments</li>
                    <li><i class="fas fa-check-circle"></i> View digital records</li>
                    <li><i class="fas fa-check-circle"></i> Get timely reminders</li>
                    <li><i class="fas fa-check-circle"></i> Access from anywhere</li>
                </ul>
            </div>
        </div>
    </div>
</section>


<?php
include("./includes/footer.php");
?>
<script src="assets/js/login.js"></script>
<script src="assets/js/index.js"></script>