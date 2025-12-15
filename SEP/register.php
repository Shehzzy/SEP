<?php
include("./includes/header.php");
session_start();

// Show messages if any
if(isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}

if(isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
?>
<!-- Register Hero -->
<section class="page-hero">
    <div class="container">
        <h1>Join VaxPakistan</h1>
        <p>Create your account to start managing vaccinations</p>
    </div>
</section>

<!-- Registration Form -->
<section class="registration">
    <div class="container">
        <div class="registration-container">
            <!-- Role Selection -->
            <div class="role-selection">
                <h3>I am registering as:</h3>
                <div class="role-options">
                    <div class="role-option" data-role="parent">
                        <i class="fas fa-user"></i>
                        <h4>Parent/Guardian</h4>
                        <p>Register your child for vaccination</p>
                    </div>
                    <div class="role-option" data-role="hospital">
                        <i class="fas fa-hospital"></i>
                        <h4>Hospital/Clinic</h4>
                        <p>Register as vaccination center</p>
                    </div>
                </div>
            </div>

            <!-- Parent Registration Form (Initially hidden) -->
            <!-- ONLY FIELDS THAT EXIST IN users TABLE -->
            <div id="parentForm" class="registration-form" style="display: none;">
                <h3>Parent Registration</h3>
                <form action="action/register_parent.php" method="POST">
                    <input type="hidden" name="role" value="parent">

                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required>
                    </div>


                    <button type="submit" name="register_parent" class="btn-primary btn-block">
                        Register as Parent
                    </button>
                </form>
                <p class="form-footer">Already have an account? <a href="login.php">Login here</a></p>
            </div>

            <!-- Hospital Registration Form (Initially hidden) -->
            <!-- Hospital needs BOTH users table AND hospitals table fields -->
            <div id="hospitalForm" class="registration-form" style="display: none;">
                <h3>Hospital Registration</h3>
                <form action="action/register_hospital.php" method="POST">
                    <input type="hidden" name="role" value="hospital">

                    <h4>User Account Details (for login)</h4>
                    <div class="form-group">
                        <label>Contact Person Name *</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required>
                    </div>
                    <hr>

                    <h4>Hospital Details</h4>
                    <div class="form-group">
                        <label>Hospital Name *</label>
                        <input type="text" name="hospital_name" required>
                    </div>

                    <div class="form-group">
                        <label>Hospital Address *</label>
                        <textarea name="address" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location">
                    </div>

                    <button type="submit" name="register_hospital" class="btn-primary btn-block">
                        Register Hospital
                    </button>
                </form>
                <p class="form-footer">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</section>


<?php
include("./includes/footer.php");
?>
<script src="assets/js/register.js"></script>